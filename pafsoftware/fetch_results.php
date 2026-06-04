<?php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();

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

$userStatement = $pdo->prepare('SELECT id, name, father_name, picture, group_name FROM useres WHERE id = ? LIMIT 1');
$userStatement->execute([$userId]);
$user = $userStatement->fetch();

if (!$user) {
    echo '<p>User not found.</p>';
    exit();
}

$resultTree = pafGetUserResultTree($pdo, $userId, $testId);
$answerDetails = pafFetchAnswerReviewData($pdo, $userId, $testId);

if ($resultTree['tests'] === []) {
    echo '<p>No saved results found for this student yet.</p>';
    exit();
}

$detailMap = [];
foreach ($answerDetails as $testBlock) {
    $detailMap[$testBlock['test_id']] = [];
    foreach ($testBlock['subjects'] as $subjectBlock) {
        $detailMap[$testBlock['test_id']][$subjectBlock['subject_id']] = $subjectBlock['questions'];
    }
}
?>
<style>
    .result-shell {
        font-family: 'Outfit', sans-serif;
        color: #122033;
    }

    .result-shell * {
        box-sizing: border-box;
    }

    .student-card,
    .result-card {
        border: 1px solid #d7e2ee;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(18, 32, 51, 0.08);
        padding: 20px;
        margin-bottom: 18px;
    }

    .student-card {
        display: grid;
        grid-template-columns: 86px 1fr;
        gap: 18px;
        align-items: center;
    }

    .student-card img {
        width: 86px;
        height: 86px;
        object-fit: cover;
        border-radius: 18px;
        border: 1px solid #d7e2ee;
    }

    .student-grid,
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }

    .summary-box {
        border-radius: 16px;
        background: #f8fbff;
        border: 1px solid #d7e2ee;
        padding: 14px;
    }

    .summary-box span {
        display: block;
        color: #62748b;
        margin-bottom: 8px;
        font-size: 0.88rem;
    }

    .test-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        background: #eaf3ff;
        color: #0d6efd;
        padding: 8px 12px;
        font-weight: 700;
    }

    table.result-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 14px;
    }

    .result-table th,
    .result-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #e3ebf4;
        text-align: left;
        vertical-align: top;
    }

    .result-table th {
        color: #51657c;
        font-size: 0.88rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .details-block {
        margin-top: 18px;
    }

    .details-block details {
        border: 1px solid #d7e2ee;
        border-radius: 16px;
        padding: 14px 16px;
        background: #fbfdff;
        margin-bottom: 12px;
    }

    .details-block summary {
        cursor: pointer;
        font-weight: 700;
    }

    .question-review {
        margin-top: 14px;
        border: 1px solid #e2eaf3;
        border-radius: 16px;
        background: #fff;
        padding: 14px;
    }

    .question-review.correct {
        border-left: 5px solid #1f9d5b;
    }

    .question-review.wrong {
        border-left: 5px solid #dc3545;
    }

    .question-review p {
        margin: 0 0 10px;
        line-height: 1.6;
    }

    .question-tags {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .question-tags span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .tag-correct {
        background: #daf6e6;
        color: #0c6a3d;
    }

    .tag-wrong {
        background: #ffe2e6;
        color: #a62633;
    }

    .tag-review {
        background: #fff3c3;
        color: #7b5b00;
    }

    .tag-skip {
        background: #edf2f7;
        color: #536579;
    }
</style>

<div class="result-shell">
    <div class="student-card">
        <?php $picture = trim((string) ($user['picture'] ?? '')) ?: 'images/default-user.png'; ?>
        <img src="<?= pafEscResult($picture) ?>" alt="Student picture">
        <div>
            <h3 style="margin:0;"><?= pafEscResult($user['name']) ?></h3>
            <div style="color:#62748b; margin-top:6px;">ID: <?= pafEscResult($user['id']) ?></div>
            <div style="color:#62748b; margin-top:4px;">Father Name: <?= pafEscResult($user['father_name']) ?></div>
            <div style="color:#62748b; margin-top:4px;">Group: <?= pafEscResult($user['group_name']) ?></div>

            <div class="student-grid">
                <div class="summary-box">
                    <span>Total Correct</span>
                    <strong><?= (int) $resultTree['overall']['correct_answers'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Questions</span>
                    <strong><?= (int) $resultTree['overall']['total_questions'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Overall Percentage</span>
                    <strong><?= number_format((float) $resultTree['overall']['percentage'], 2) ?>%</strong>
                </div>
                <div class="summary-box">
                    <span>Subjects Completed</span>
                    <strong><?= (int) $resultTree['overall']['subjects'] ?></strong>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($resultTree['tests'] as $test): ?>
        <div class="result-card">
            <div class="test-heading">
                <div>
                    <h3 style="margin:0;"><?= pafEscResult($test['test_name']) ?></h3>
                    <div style="color:#62748b; margin-top:6px;">Test-wise result with subject breakdown and answer detail</div>
                </div>
                <span class="chip"><i class="fa-solid fa-chart-column"></i> <?= number_format((float) $test['percentage'], 2) ?>%</span>
            </div>

            <div class="summary-grid">
                <div class="summary-box">
                    <span>Correct Answers</span>
                    <strong><?= (int) $test['correct_answers'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Questions</span>
                    <strong><?= (int) $test['total_questions'] ?></strong>
                </div>
                <div class="summary-box">
                    <span>Subjects</span>
                    <strong><?= count($test['subjects']) ?></strong>
                </div>
                <div class="summary-box">
                    <span>Percentage</span>
                    <strong><?= number_format((float) $test['percentage'], 2) ?>%</strong>
                </div>
            </div>

            <table class="result-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Skipped</th>
                        <th>Total</th>
                        <th>Review Marked</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($test['subjects'] as $subject): ?>
                        <tr>
                            <td><?= pafEscResult($subject['subject_name']) ?></td>
                            <td><?= (int) $subject['correct_answers'] ?></td>
                            <td><?= (int) $subject['wrong_answers'] ?></td>
                            <td><?= (int) $subject['skipped_questions'] ?></td>
                            <td><?= (int) $subject['total_questions'] ?></td>
                            <td><?= (int) $subject['review_questions'] ?></td>
                            <td><?= number_format((float) $subject['percentage'], 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="details-block">
                <?php foreach ($test['subjects'] as $subject): ?>
                    <?php $questions = $detailMap[$test['test_id']][$subject['subject_id']] ?? []; ?>
                    <details>
                        <summary><?= pafEscResult($subject['subject_name']) ?> Question Review</summary>
                        <?php if ($questions === []): ?>
                            <p style="margin-top:12px;">No question detail found for this subject.</p>
                        <?php else: ?>
                            <?php foreach ($questions as $question): ?>
                                <div class="question-review <?= $question['is_correct'] ? 'correct' : 'wrong' ?>">
                                    <p><strong>Q<?= (int) $question['sequence_number'] ?>:</strong> <?= pafEscResult($question['question_text']) ?></p>
                                    <p><strong>Student Answer:</strong> <?= pafEscResult($question['user_answer_label']) ?></p>
                                    <p><strong>Correct Answer:</strong> <?= pafEscResult($question['correct_answer_label']) ?></p>
                                    <div class="question-tags">
                                        <span class="<?= $question['is_correct'] ? 'tag-correct' : 'tag-wrong' ?>">
                                            <i class="fa-solid <?= $question['is_correct'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                            <?= $question['is_correct'] ? 'Correct' : 'Wrong' ?>
                                        </span>
                                        <?php if ((int) $question['mark_for_review'] === 1): ?>
                                            <span class="tag-review"><i class="fa-solid fa-flag"></i> Review Marked</span>
                                        <?php endif; ?>
                                        <?php if ((int) $question['is_skipped'] === 1 || $question['user_answer'] === 'F' || $question['user_answer'] === ''): ?>
                                            <span class="tag-skip"><i class="fa-solid fa-forward"></i> Skipped / No Selection</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
