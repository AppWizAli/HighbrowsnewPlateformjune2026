<?php

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

function pafEscResult($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$userId = isset($_GET['user_id']) ? pafNormaliseUserId($_GET['user_id']) : '';
$testId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : null;

if ($userId === '') {
    echo '<p>User ID not provided.</p>';
    exit();
}

$tree = pafGetUserResultTree($pdo, $userId, $testId);

if ($tree['tests'] === []) {
    echo '<p>No saved results found for this student yet.</p>';
    exit();
}
?>
<style>
    .result-shell {
        font-family: Arial, sans-serif;
        color: #152235;
    }

    .result-shell * {
        box-sizing: border-box;
    }

    .result-top,
    .result-card,
    .question-card {
        border: 1px solid #d9e2ec;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    .result-top,
    .result-card {
        padding: 18px 20px;
        margin-bottom: 18px;
    }

    .result-top {
        display: grid;
        grid-template-columns: 96px 1fr;
        gap: 18px;
        align-items: center;
    }

    .result-top img {
        width: 96px;
        height: 96px;
        border-radius: 18px;
        object-fit: cover;
        border: 1px solid #d9e2ec;
    }

    .result-grid,
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }

    .summary-box {
        padding: 14px;
        border-radius: 14px;
        background: #f8fbff;
        border: 1px solid #d9e2ec;
    }

    .summary-box span {
        display: block;
        color: #5b7088;
        font-size: 0.86rem;
        margin-bottom: 8px;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .status-pill.pass {
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.fail {
        background: #fee2e2;
        color: #991b1b;
    }

    table.result-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
    }

    .result-table th,
    .result-table td {
        border-bottom: 1px solid #e6edf5;
        padding: 12px 10px;
        text-align: left;
        vertical-align: top;
    }

    .result-table th {
        color: #5b7088;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    details {
        margin-top: 14px;
        border: 1px solid #d9e2ec;
        border-radius: 16px;
        background: #fcfdff;
        padding: 14px 16px;
    }

    summary {
        cursor: pointer;
        font-weight: 700;
    }

    .question-card {
        padding: 14px 16px;
        margin-top: 12px;
    }

    .question-card.correct {
        border-left: 5px solid #22c55e;
    }

    .question-card.wrong {
        border-left: 5px solid #ef4444;
    }

    .question-card.pending {
        border-left: 5px solid #94a3b8;
    }

    .tag-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .tag {
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .tag.correct {
        background: #dcfce7;
        color: #166534;
    }

    .tag.wrong {
        background: #fee2e2;
        color: #991b1b;
    }

    .tag.review {
        background: #fef3c7;
        color: #92400e;
    }

    .tag.pending {
        background: #e2e8f0;
        color: #334155;
    }
</style>

<div class="result-shell">
    <?php
    $student = $tree['student'];
    $picture = trim((string) ($student['picture'] ?? ''));
    if ($picture === '') {
        $picture = 'data:image/svg+xml;utf8,' . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 96 96">
                <rect width="96" height="96" rx="18" fill="#e8eef5"/>
                <circle cx="48" cy="35" r="18" fill="#9db1c7"/>
                <path d="M20 82c4-15 17-24 28-24s24 9 28 24" fill="#9db1c7"/>
            </svg>'
        );
    }
    ?>
    <div class="result-top">
        <img src="<?= pafEscResult($picture) ?>" alt="Student picture">
        <div>
            <h2 style="margin:0;"><?= pafEscResult($student['name'] ?: $student['id']) ?></h2>
            <div style="margin-top:6px; color:#5b7088;">Roll Number: <?= pafEscResult($student['id']) ?></div>
            <?php if (!empty($student['father_name'])): ?>
                <div style="margin-top:4px; color:#5b7088;">Father Name: <?= pafEscResult($student['father_name']) ?></div>
            <?php endif; ?>
            <?php if (!empty($student['group_name'])): ?>
                <div style="margin-top:4px; color:#5b7088;">Group: <?= pafEscResult($student['group_name']) ?></div>
            <?php endif; ?>

            <div class="result-grid">
                <div class="summary-box">
                    <span>Total Subjects</span>
                    <strong><?= (int) $tree['overall']['total_subjects'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Questions</span>
                    <strong><?= (int) $tree['overall']['total_questions'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Correct</span>
                    <strong><?= (int) $tree['overall']['total_correct'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Wrong</span>
                    <strong><?= (int) $tree['overall']['total_wrong'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Overall Percentage</span>
                    <strong><?= number_format((float) $tree['overall']['percentage'], 2) ?>%</strong>
                </div>
                <div class="summary-box">
                    <span>Overall Result</span>
                    <strong><?= pafEscResult($tree['overall']['result_status']) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($tree['tests'] as $test): ?>
        <div class="result-card">
            <div class="section-head">
                <div>
                    <h3 style="margin:0;"><?= pafEscResult($test['test_name']) ?></h3>
                    <div style="margin-top:6px; color:#5b7088;">Overall test result with subject-wise and question-wise breakdown</div>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="status-pill <?= strtolower($test['overall_result']) === 'pass' ? 'pass' : 'fail' ?>">
                        <?= pafEscResult($test['overall_result']) ?>
                    </span>
                    <?php if (!empty($test['merit_position'])): ?>
                        <span class="status-pill" style="background:#e0f2fe; color:#0c4a6e;">Merit #<?= (int) $test['merit_position'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-box">
                    <span>Total Subjects</span>
                    <strong><?= (int) $test['total_subjects'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Questions</span>
                    <strong><?= (int) $test['total_questions'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Correct</span>
                    <strong><?= (int) $test['total_correct'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Wrong</span>
                    <strong><?= (int) $test['total_wrong'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Overall Percentage</span>
                    <strong><?= number_format((float) $test['overall_percentage'], 2) ?>%</strong>
                </div>
                <div class="summary-box">
                    <span>Not Answered</span>
                    <strong><?= (int) $test['total_not_answered'] ?></strong>
                </div>
            </div>

            <table class="result-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Total MCQs</th>
                        <th>Attempted</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Not Answered</th>
                        <th>Review</th>
                        <th>Percentage</th>
                        <th>Pass/Fail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($test['subjects'] as $subject): ?>
                        <tr>
                            <td><?= pafEscResult($subject['subject_name']) ?></td>
                            <td><?= (int) $subject['total_questions'] ?></td>
                            <td><?= (int) $subject['attempted_questions'] ?></td>
                            <td><?= (int) $subject['correct_answers'] ?></td>
                            <td><?= (int) $subject['wrong_answers'] ?></td>
                            <td><?= (int) $subject['not_answered_questions'] ?></td>
                            <td><?= (int) $subject['review_marked_questions'] ?></td>
                            <td><?= number_format((float) $subject['percentage'], 2) ?>%</td>
                            <td><?= pafEscResult($subject['result_status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php foreach ($test['subjects'] as $subject): ?>
                <details>
                    <summary><?= pafEscResult($subject['subject_name']) ?> Question Review</summary>
                    <?php if ($subject['questions'] === []): ?>
                        <p style="margin-top:12px;">No saved question details for this subject.</p>
                    <?php else: ?>
                        <?php foreach ($subject['questions'] as $question): ?>
                            <?php
                            $statusName = strtolower(str_replace(' ', '_', $question['result_status']));
                            if ($statusName === 'not_answered') {
                                $statusName = 'pending';
                            }
                            ?>
                            <div class="question-card <?= $statusName ?>">
                                <p style="margin:0 0 10px;"><strong>Q<?= (int) $question['sequence_number'] ?>:</strong> <?= pafEscResult($question['question_text']) ?></p>
                                <p style="margin:0 0 8px;"><strong>Student Answer:</strong> <?= pafEscResult($question['selected_answer_label']) ?></p>
                                <p style="margin:0;"><strong>Correct Answer:</strong> <?= pafEscResult($question['correct_answer_label']) ?></p>
                                <div class="tag-row">
                                    <span class="tag <?= $statusName === 'correct' ? 'correct' : ($statusName === 'wrong' ? 'wrong' : 'pending') ?>">
                                        <?= pafEscResult($question['result_status']) ?>
                                    </span>
                                    <?php if ((int) $question['mark_for_review'] === 1): ?>
                                        <span class="tag review">Review Marked</span>
                                    <?php endif; ?>
                                    <?php if ((int) $question['is_skipped'] === 1): ?>
                                        <span class="tag pending">No Selection</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </details>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
