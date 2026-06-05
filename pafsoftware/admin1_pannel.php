<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

$stats = [
    'tests' => (int) $pdo->query('SELECT COUNT(*) FROM tests')->fetchColumn(),
    'subjects' => (int) $pdo->query('SELECT COUNT(*) FROM subjects')->fetchColumn(),
    'questions' => (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn(),
    'students' => (int) $pdo->query('SELECT COUNT(*) FROM useres')->fetchColumn(),
    'subject_results' => (int) $pdo->query('SELECT COUNT(*) FROM subject_result_summaries')->fetchColumn(),
    'overall_results' => (int) $pdo->query('SELECT COUNT(*) FROM overall_test_results')->fetchColumn(),
];

$recentResults = $pdo->query(
    'SELECT o.user_id, o.student_name, t.test_name, o.overall_percentage, o.result_status, o.merit_position, o.completed_at
     FROM overall_test_results o
     INNER JOIN tests t ON t.id = o.test_id
     ORDER BY o.completed_at DESC
     LIMIT 8'
)->fetchAll(PDO::FETCH_ASSOC);
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
                    <p>Clean overview of tests, students, questions, and published results.</p>
                </div>
                <a class="btn btn-secondary" href="logout.php">Logout</a>
            </section>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Overview</h2>
                        <p>Live counts from the PAF testing system.</p>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <span>Total Tests</span>
                        <strong><?= $stats['tests'] ?></strong>
                    </div>
                    <div class="stat-card">
                        <span>Total Subjects</span>
                        <strong><?= $stats['subjects'] ?></strong>
                    </div>
                    <div class="stat-card">
                        <span>Total Questions</span>
                        <strong><?= $stats['questions'] ?></strong>
                    </div>
                    <div class="stat-card">
                        <span>Total Students</span>
                        <strong><?= $stats['students'] ?></strong>
                    </div>
                    <div class="stat-card">
                        <span>Subject Results</span>
                        <strong><?= $stats['subject_results'] ?></strong>
                    </div>
                    <div class="stat-card">
                        <span>Overall Results</span>
                        <strong><?= $stats['overall_results'] ?></strong>
                    </div>
                </div>
            </section>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Quick Actions</h2>
                        <p>Most-used admin tasks.</p>
                    </div>
                </div>
                <div class="action-row">
                    <a class="btn btn-primary" href="add_test.php">Add Test</a>
                    <a class="btn btn-secondary" href="add-subject.php">Add Subject</a>
                    <a class="btn btn-secondary" href="add-questions.php">Add Questions</a>
                    <a class="btn btn-success" href="show-users.php">Open Student Results</a>
                </div>
            </section>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Recent Overall Results</h2>
                        <p>Latest students with generated overall scores.</p>
                    </div>
                </div>
                <?php if ($recentResults === []): ?>
                    <div class="empty-state">No overall results have been generated yet.</div>
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
                                            <strong><?= htmlspecialchars($row['student_name'] ?: $row['user_id'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <div class="muted"><?= htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8') ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($row['test_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= number_format((float) $row['overall_percentage'], 2) ?>%</td>
                                        <td>
                                            <span class="badge <?= strtolower((string) $row['result_status']) === 'pass' ? 'pass' : 'fail' ?>">
                                                <?= htmlspecialchars($row['result_status'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </td>
                                        <td><?= $row['merit_position'] ? '#' . (int) $row['merit_position'] : 'N/A' ?></td>
                                        <td><?= htmlspecialchars((string) $row['completed_at'], ENT_QUOTES, 'UTF-8') ?></td>
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
