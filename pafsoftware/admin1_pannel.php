<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

pafAdminRequireLogin();

$pdo = getPDOConnection();
$flash = pafAdminPullFlash();
$reportingReady = true;

try {
    pafEnsureResultTables($pdo);
} catch (Throwable $exception) {
    $reportingReady = false;
}

$stats = [
    'tests' => (int) $pdo->query('SELECT COUNT(*) FROM tests')->fetchColumn(),
    'subjects' => (int) $pdo->query('SELECT COUNT(*) FROM subjects')->fetchColumn(),
    'questions' => (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn(),
    'students' => (int) $pdo->query('SELECT COUNT(*) FROM useres')->fetchColumn(),
    'subject_results' => 0,
    'overall_results' => 0,
];

if ($reportingReady) {
    $stats['subject_results'] = (int) $pdo->query('SELECT COUNT(*) FROM subject_result_summaries')->fetchColumn();
    $stats['overall_results'] = (int) $pdo->query('SELECT COUNT(*) FROM overall_test_results')->fetchColumn();
}

$testBreakdown = $pdo->query(
    'SELECT
        t.test_name,
        COUNT(DISTINCT s.id) AS subject_count,
        COUNT(q.id) AS question_count
     FROM tests t
     LEFT JOIN subjects s ON s.test_id = t.id
     LEFT JOIN questions q ON q.subject_id = s.id
     GROUP BY t.id, t.test_name
     ORDER BY question_count DESC, subject_count DESC, t.test_name ASC
     LIMIT 6'
)->fetchAll(PDO::FETCH_ASSOC);

$recentResults = [];
if ($reportingReady) {
    $recentResults = $pdo->query(
        'SELECT
            o.user_id,
            o.student_name,
            t.test_name,
            o.overall_percentage,
            o.result_status,
            o.merit_position,
            o.completed_at
         FROM overall_test_results o
         INNER JOIN tests t ON t.id = o.test_id
         ORDER BY o.completed_at DESC
         LIMIT 6'
    )->fetchAll(PDO::FETCH_ASSOC);
}

$systemNotes = [
    ['label' => 'Tests ready', 'value' => $stats['tests'] . ' live test(s)', 'tone' => 'primary'],
    ['label' => 'Question bank', 'value' => $stats['questions'] . ' total MCQs', 'tone' => 'success'],
    ['label' => 'Students', 'value' => $stats['students'] . ' registered record(s)', 'tone' => 'warning'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAF Admin Dashboard</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Dashboard</h1>
                    <p>Clean, quick control for tests, subjects, questions, students, and result reporting.</p>
                </div>
                <div class="action-row">
                    <a class="btn btn-primary" href="show_test.php?open=add">New Test</a>
                    <a class="btn btn-secondary" href="show-subject.php?open=add">New Subject</a>
                    <a class="btn btn-secondary" href="show-question.php">Question Bank</a>
                    <a class="btn btn-secondary" href="register.php">Student Register</a>
                    <a class="btn btn-secondary" href="userlogin.php" target="_blank" rel="noopener noreferrer">Student Login</a>
                    <a class="btn btn-ghost" href="logout.php">Logout</a>
                </div>
            </section>

            <?php if ($flash): ?>
                <div class="flash <?= pafAdminEsc($flash['type']) ?>">
                    <?= pafAdminEsc($flash['message']) ?>
                </div>
            <?php endif; ?>

            <?php if (!$reportingReady): ?>
                <div class="flash warning">
                    Detailed result tables are not ready on this hosting environment yet. Core admin management is still available.
                </div>
            <?php endif; ?>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Live Snapshot</h2>
                        <p>Small, clear metrics for what matters most right now.</p>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="metric-card">
                        <span>Total Tests</span>
                        <strong><?= $stats['tests'] ?></strong>
                        <div class="delta primary">active catalog</div>
                    </div>
                    <div class="metric-card">
                        <span>Total Subjects</span>
                        <strong><?= $stats['subjects'] ?></strong>
                        <div class="delta primary">linked to tests</div>
                    </div>
                    <div class="metric-card">
                        <span>Total Questions</span>
                        <strong><?= $stats['questions'] ?></strong>
                        <div class="delta success">question bank</div>
                    </div>
                    <div class="metric-card">
                        <span>Total Students</span>
                        <strong><?= $stats['students'] ?></strong>
                        <div class="delta warning">student records</div>
                    </div>
                    <div class="metric-card">
                        <span>Subject Results</span>
                        <strong><?= $stats['subject_results'] ?></strong>
                        <div class="delta primary">saved summaries</div>
                    </div>
                    <div class="metric-card">
                        <span>Overall Results</span>
                        <strong><?= $stats['overall_results'] ?></strong>
                        <div class="delta success">published reports</div>
                    </div>
                </div>
            </section>

            <section class="dashboard-grid">
                <div class="panel-card">
                    <div class="panel-head">
                        <div>
                            <h2>Test Coverage</h2>
                            <p>Quick look at how complete each test is.</p>
                        </div>
                        <a class="btn btn-secondary" href="show_test.php">Manage Tests</a>
                    </div>

                    <?php if ($testBreakdown === []): ?>
                        <div class="empty-state">No tests available yet.</div>
                    <?php else: ?>
                        <?php
                        $maxQuestions = max(array_map(static fn(array $row): int => (int) $row['question_count'], $testBreakdown)) ?: 1;
                        ?>
                        <div class="chart-list">
                            <?php foreach ($testBreakdown as $row): ?>
                                <?php $width = ((int) $row['question_count'] / $maxQuestions) * 100; ?>
                                <div class="chart-row">
                                    <div class="chart-top">
                                        <div>
                                            <strong><?= pafAdminEsc($row['test_name']) ?></strong>
                                            <div class="muted"><?= (int) $row['subject_count'] ?> subject(s)</div>
                                        </div>
                                        <div><?= (int) $row['question_count'] ?> MCQs</div>
                                    </div>
                                    <div class="chart-track">
                                        <div class="chart-fill" style="width: <?= number_format($width, 2, '.', '') ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="panel-card">
                    <div class="panel-head">
                        <div>
                            <h2>Quick Focus</h2>
                            <p>Short admin cues for today’s work.</p>
                        </div>
                    </div>
                    <div class="subtle-list">
                        <?php foreach ($systemNotes as $note): ?>
                            <div class="subtle-list-item">
                                <div>
                                    <strong><?= pafAdminEsc($note['label']) ?></strong>
                                    <span><?= pafAdminEsc($note['value']) ?></span>
                                </div>
                                <span class="delta <?= pafAdminEsc($note['tone']) ?>">ready</span>
                            </div>
                        <?php endforeach; ?>
                        <div class="subtle-list-item">
                            <div>
                                <strong>Student reports</strong>
                                <span><?= $reportingReady ? 'View detailed subject and question reports in Students.' : 'Reporting storage still needs attention.' ?></span>
                            </div>
                            <a class="btn btn-secondary" href="show-users.php">Open</a>
                        </div>
                        <div class="subtle-list-item">
                            <div>
                                <strong>Student access</strong>
                                <span>Open registration here or launch student login in a separate tab.</span>
                            </div>
                            <div class="action-row">
                                <a class="btn btn-secondary" href="register.php">Register</a>
                                <a class="btn btn-secondary" href="userlogin.php" target="_blank" rel="noopener noreferrer">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Recent Overall Results</h2>
                        <p>Newest published student outcomes.</p>
                    </div>
                    <a class="btn btn-secondary" href="show-users.php">Open Students</a>
                </div>

                <?php if ($recentResults === []): ?>
                    <div class="empty-state">No overall result reports are available yet.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Test</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Merit</th>
                                    <th>Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentResults as $row): ?>
                                    <tr>
                                        <td>
                                            <strong><?= pafAdminEsc($row['student_name'] ?: $row['user_id']) ?></strong>
                                            <div class="muted"><?= pafAdminEsc($row['user_id']) ?></div>
                                        </td>
                                        <td><?= pafAdminEsc($row['test_name']) ?></td>
                                        <td><?= number_format((float) $row['overall_percentage'], 2) ?>%</td>
                                        <td>
                                            <span class="badge <?= strtolower((string) $row['result_status']) === 'pass' ? 'pass' : 'fail' ?>">
                                                <?= pafAdminEsc($row['result_status']) ?>
                                            </span>
                                        </td>
                                        <td><?= !empty($row['merit_position']) ? '#' . (int) $row['merit_position'] : 'N/A' ?></td>
                                        <td><?= pafAdminEsc((string) $row['completed_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
