<?php

require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

pafAdminRequireLogin();

$pdo = getPDOConnection();
$reportingReady = true;

try {
    pafEnsureResultTables($pdo);
} catch (Throwable $exception) {
    $reportingReady = false;
}

$search = trim((string) ($_GET['search'] ?? ''));
$testId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : 0;
$subjectId = isset($_GET['subject_id']) && (int) $_GET['subject_id'] > 0 ? (int) $_GET['subject_id'] : 0;

$tests = pafFetchTests($pdo);
$subjects = $testId > 0 ? pafFetchTestSubjects($pdo, $testId) : $pdo->query(
    'SELECT id, name, test_id FROM subjects ORDER BY name ASC, id ASC'
)->fetchAll(PDO::FETCH_ASSOC);

$students = [];
if ($reportingReady) {
    $params = [];
    if ($testId > 0) {
        $sql = 'SELECT DISTINCT
                    u.id,
                    u.name,
                    u.father_name,
                    u.picture,
                    u.group_name,
                    o.test_id,
                    t.test_name,
                    o.overall_percentage,
                    o.result_status,
                    o.merit_position,
                    o.completed_at
                FROM useres u
                LEFT JOIN overall_test_results o ON o.user_id = u.id AND o.test_id = ?
                LEFT JOIN tests t ON t.id = o.test_id';
        $params[] = $testId;
    } else {
        $sql = 'SELECT
                    u.id,
                    u.name,
                    u.father_name,
                    u.picture,
                    u.group_name,
                    o.test_id,
                    t.test_name,
                    o.overall_percentage,
                    o.result_status,
                    o.merit_position,
                    o.completed_at
                FROM useres u
                LEFT JOIN overall_test_results o
                    ON o.id = (
                        SELECT o2.id
                        FROM overall_test_results o2
                        WHERE o2.user_id = u.id
                        ORDER BY o2.completed_at DESC, o2.id DESC
                        LIMIT 1
                    )
                LEFT JOIN tests t ON t.id = o.test_id';
    }

    if ($subjectId > 0) {
        $sql .= ' LEFT JOIN subject_result_summaries srs ON srs.user_id = u.id AND srs.subject_id = ?';
        $params[] = $subjectId;
    }

    $conditions = [];
    if ($search !== '') {
        $conditions[] = '(u.id LIKE ? OR u.name LIKE ? OR u.father_name LIKE ? OR u.group_name LIKE ?)';
        $searchLike = '%' . $search . '%';
        array_push($params, $searchLike, $searchLike, $searchLike, $searchLike);
    }

    if ($subjectId > 0) {
        $conditions[] = 'srs.subject_id IS NOT NULL';
    }

    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY u.name ASC, u.id ASC';

    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $students = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $fallbackSql = 'SELECT id, name, father_name, picture, group_name, NULL AS test_id, NULL AS test_name,
                           NULL AS overall_percentage, NULL AS result_status, NULL AS merit_position, NULL AS completed_at
                    FROM useres';
    $fallbackParams = [];
    if ($search !== '') {
        $fallbackSql .= ' WHERE (id LIKE ? OR name LIKE ? OR father_name LIKE ? OR group_name LIKE ?)';
        $searchLike = '%' . $search . '%';
        array_push($fallbackParams, $searchLike, $searchLike, $searchLike, $searchLike);
    }
    $fallbackSql .= ' ORDER BY name ASC, id ASC';
    $fallbackStatement = $pdo->prepare($fallbackSql);
    $fallbackStatement->execute($fallbackParams);
    $students = $fallbackStatement->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students and Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Students</h1>
                    <p>Search students, filter by test or subject, and open detailed reports.</p>
                </div>
                <div class="action-row">
                    <a class="btn btn-secondary" href="admin1_pannel.php">Back To Dashboard</a>
                </div>
            </section>

            <?php if (!$reportingReady): ?>
                <div class="flash warning">
                    Detailed report tables are not available right now. Student records still load, but full result reports may be limited.
                </div>
            <?php endif; ?>

            <section class="panel-card">
                <div class="stats-grid">
                    <div class="metric-card">
                        <span>Visible Students</span>
                        <strong><?= count($students) ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Tests Available</span>
                        <strong><?= count($tests) ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Subjects Available</span>
                        <strong><?= count($subjects) ?></strong>
                    </div>
                </div>
            </section>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Filters</h2>
                        <p>Use one or more filters to narrow the report list.</p>
                    </div>
                </div>
                <form method="GET" class="toolbar">
                    <div class="filters-grid">
                        <div>
                            <label class="label" for="search">Search Student</label>
                            <input id="search" type="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="ID, name, father name, group">
                        </div>
                        <div>
                            <label class="label" for="test_id">Filter By Test</label>
                            <select id="test_id" name="test_id">
                                <option value="">All tests</option>
                                <?php foreach ($tests as $test): ?>
                                    <option value="<?= (int) $test['id'] ?>" <?= $testId === (int) $test['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($test['test_name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="label" for="subject_id">Filter By Subject</label>
                            <select id="subject_id" name="subject_id">
                                <option value="">All subjects</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= (int) $subject['id'] ?>" <?= $subjectId === (int) $subject['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subject['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="toolbar-row">
                        <div class="muted"><?= count($students) ?> student(s) found</div>
                        <div class="action-row">
                            <button class="btn btn-primary" type="submit">Apply Filters</button>
                            <a class="btn btn-secondary" href="show-users.php">Reset</a>
                        </div>
                    </div>
                </form>
            </section>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Student List</h2>
                        <p>Open full test reports or manage individual students.</p>
                    </div>
                </div>

                <?php if ($students === []): ?>
                    <div class="empty-state">No students matched the selected filters.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Group</th>
                                    <th>Latest Test</th>
                                    <th>Overall</th>
                                    <th>Status</th>
                                    <th>Merit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <?php
                                    $picture = trim((string) ($student['picture'] ?? ''));
                                    $picture = pafAdminAvatar($picture, 50);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="student-cell">
                                                <img src="<?= htmlspecialchars($picture, ENT_QUOTES, 'UTF-8') ?>" alt="Student picture">
                                                <div>
                                                    <strong><?= htmlspecialchars($student['name'] ?: $student['id'], ENT_QUOTES, 'UTF-8') ?></strong>
                                                    <div class="muted"><?= htmlspecialchars($student['id'], ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php if (!empty($student['father_name'])): ?>
                                                        <div class="muted"><?= htmlspecialchars($student['father_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars((string) ($student['group_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?: 'N/A' ?></td>
                                        <td><?= htmlspecialchars((string) ($student['test_name'] ?? 'No completed test'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= $student['overall_percentage'] !== null ? number_format((float) $student['overall_percentage'], 2) . '%' : 'N/A' ?></td>
                                        <td>
                                            <?php if (!empty($student['result_status'])): ?>
                                                <span class="badge <?= strtolower((string) $student['result_status']) === 'pass' ? 'pass' : 'fail' ?>">
                                                    <?= htmlspecialchars((string) $student['result_status'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="muted">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= !empty($student['merit_position']) ? '#' . (int) $student['merit_position'] : 'N/A' ?></td>
                                        <td>
                                            <div class="action-row">
                                                <button
                                                    class="btn btn-primary"
                                                    type="button"
                                                    onclick="openResultModal('<?= htmlspecialchars($student['id'], ENT_QUOTES, 'UTF-8') ?>', '<?= (int) ($testId ?: ($student['test_id'] ?? 0)) ?>')"
                                                >
                                                    View Report
                                                </button>
                                                <button
                                                    class="btn btn-warning"
                                                    type="button"
                                                    onclick="rescheduleTest('<?= htmlspecialchars($student['id'], ENT_QUOTES, 'UTF-8') ?>', '<?= (int) ($testId ?: 0) ?>')"
                                                >
                                                    Reschedule
                                                </button>
                                                <button
                                                    class="btn btn-danger"
                                                    type="button"
                                                    onclick="deleteUser('<?= htmlspecialchars($student['id'], ENT_QUOTES, 'UTF-8') ?>')"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Student Result Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="resultModalBody">
                    <div class="empty-state">Loading report...</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openResultModal(userId, testId) {
            $('#resultModal').modal('show');
            $('#resultModalBody').html('<div class="empty-state">Loading report...</div>');

            const data = { user_id: userId };
            if (testId && Number(testId) > 0) {
                data.test_id = testId;
            }

            $.ajax({
                url: 'fetch_results.php',
                method: 'GET',
                data: data,
                success: function(response) {
                    $('#resultModalBody').html(response);
                },
                error: function() {
                    $('#resultModalBody').html('<div class="empty-state">Unable to load the result report.</div>');
                }
            });
        }

        function rescheduleTest(userId, testId) {
            if (!confirm('Reschedule this student\'s test results?')) {
                return;
            }

            const data = { user_id: userId };
            if (testId && Number(testId) > 0) {
                data.test_id = testId;
            }

            $.ajax({
                url: 'reschedule_test.php',
                method: 'GET',
                data: data,
                success: function(response) {
                    if (response === 'success') {
                        window.location.reload();
                    } else {
                        alert('Error rescheduling test: ' + response);
                    }
                },
                error: function() {
                    alert('Error rescheduling test.');
                }
            });
        }

        function deleteUser(userId) {
            if (!confirm('Delete this student and all linked result records?')) {
                return;
            }

            $.ajax({
                url: 'delete_useres.php',
                method: 'GET',
                data: { user_id: userId },
                success: function(response) {
                    if (response === 'success') {
                        window.location.reload();
                    } else {
                        alert('Error deleting user: ' + response);
                    }
                },
                error: function() {
                    alert('Error deleting user.');
                }
            });
        }
    </script>
</body>
</html>
