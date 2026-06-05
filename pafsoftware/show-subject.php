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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int) $_POST['delete_id'];

    try {
        $pdo->beginTransaction();

        $questionStatement = $pdo->prepare('SELECT id FROM questions WHERE subject_id = ?');
        $questionStatement->execute([$deleteId]);
        $questionIds = array_map('intval', $questionStatement->fetchAll(PDO::FETCH_COLUMN));

        if ($questionIds !== []) {
            $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
            $deleteAnswers = $pdo->prepare("DELETE FROM answers WHERE question_id IN ($placeholders)");
            $deleteAnswers->execute($questionIds);
        }

        if ($reportingReady) {
            $deleteQuestionResults = $pdo->prepare('DELETE FROM question_result_details WHERE subject_id = ?');
            $deleteQuestionResults->execute([$deleteId]);

            $deleteSubjectResults = $pdo->prepare('DELETE FROM subject_result_summaries WHERE subject_id = ?');
            $deleteSubjectResults->execute([$deleteId]);
        }

        $deleteLegacyResults = $pdo->prepare('DELETE FROM results WHERE subject_id = ?');
        $deleteLegacyResults->execute([$deleteId]);

        $deleteQuestions = $pdo->prepare('DELETE FROM questions WHERE subject_id = ?');
        $deleteQuestions->execute([$deleteId]);

        $deleteSubject = $pdo->prepare('DELETE FROM subjects WHERE id = ?');
        $deleteSubject->execute([$deleteId]);

        $pdo->commit();
        pafAdminSetFlash('success', 'Subject and linked questions deleted successfully.');
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        pafAdminSetFlash('error', 'Unable to delete the selected subject right now.');
    }

    header('Location: show-subject.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['edit_name'], $_POST['edit_time'], $_POST['edit_test_id'])) {
    $editId = (int) $_POST['edit_id'];
    $editName = trim((string) $_POST['edit_name']);
    $editTime = (int) $_POST['edit_time'];
    $editTestId = (int) $_POST['edit_test_id'];

    if ($editId > 0 && $editName !== '' && $editTime > 0 && $editTestId > 0) {
        $updateStatement = $pdo->prepare('UPDATE subjects SET test_id = ?, name = ?, time_in_minutes = ? WHERE id = ?');
        $updated = $updateStatement->execute([$editTestId, $editName, $editTime, $editId]);
        pafAdminSetFlash($updated ? 'success' : 'error', $updated ? 'Subject updated successfully.' : 'Failed to update the selected subject.');
    } else {
        pafAdminSetFlash('error', 'Please complete all subject edit fields.');
    }

    header('Location: show-subject.php' . ($editTestId > 0 ? '?test_id=' . $editTestId : ''));
    exit();
}

$tests = $pdo->query('SELECT id, test_id, test_name, date_added FROM tests ORDER BY test_name ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC);
$testId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : 0;
$openSheet = (string) ($_GET['open'] ?? '');
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;

$sql = 'SELECT
            s.id,
            s.test_id,
            s.name,
            s.time_in_minutes,
            t.test_name,
            COUNT(q.id) AS question_count
        FROM subjects s
        INNER JOIN tests t ON t.id = s.test_id
        LEFT JOIN questions q ON q.subject_id = s.id';
$params = [];

if ($testId > 0) {
    $sql .= ' WHERE s.test_id = ?';
    $params[] = $testId;
}

$sql .= ' GROUP BY s.id, s.test_id, s.name, s.time_in_minutes, t.test_name
          ORDER BY t.test_name ASC, s.id ASC';

$subjectStatement = $pdo->prepare($sql);
$subjectStatement->execute($params);
$subjects = $subjectStatement->fetchAll(PDO::FETCH_ASSOC);

$editData = null;
if ($editId > 0) {
    $editStatement = $pdo->prepare('SELECT id, test_id, name, time_in_minutes FROM subjects WHERE id = ? LIMIT 1');
    $editStatement->execute([$editId]);
    $editData = $editStatement->fetch(PDO::FETCH_ASSOC) ?: null;
}

$summary = [
    'subjects' => count($subjects),
    'questions' => array_sum(array_map(static fn(array $row): int => (int) $row['question_count'], $subjects)),
    'avg_time' => $subjects === [] ? 0 : round(array_sum(array_map(static fn(array $row): int => (int) $row['time_in_minutes'], $subjects)) / count($subjects)),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Subjects</h1>
                    <p>Group subjects under each test, adjust timing, and keep the structure tidy.</p>
                </div>
                <div class="action-row">
                    <button class="btn btn-primary" type="button" onclick="openSheet('addSubjectSheet')">Add Subject</button>
                    <a class="btn btn-secondary" href="show_test.php">Back To Tests</a>
                </div>
            </section>

            <?php if ($flash): ?>
                <div class="flash <?= pafAdminEsc($flash['type']) ?>">
                    <?= pafAdminEsc($flash['message']) ?>
                </div>
            <?php endif; ?>

            <section class="panel-card">
                <div class="stats-grid">
                    <div class="metric-card">
                        <span>Visible Subjects</span>
                        <strong><?= $summary['subjects'] ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Visible MCQs</span>
                        <strong><?= $summary['questions'] ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Average Time</span>
                        <strong><?= $summary['avg_time'] ?> min</strong>
                    </div>
                </div>
            </section>

            <section class="table-card">
                <div class="panel-head">
                    <div>
                        <h2>Subject Directory</h2>
                        <p>Filter by test, then add or refine subject records from the same page.</p>
                    </div>
                </div>

                <form method="GET" class="toolbar">
                    <div class="filters-grid">
                        <div>
                            <label class="label" for="test_id">Filter By Test</label>
                            <select id="test_id" name="test_id">
                                <option value="">All tests</option>
                                <?php foreach ($tests as $test): ?>
                                    <option value="<?= (int) $test['id'] ?>" <?= $testId === (int) $test['id'] ? 'selected' : '' ?>>
                                        <?= pafAdminEsc($test['test_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="toolbar-row">
                        <div class="muted"><?= count($subjects) ?> subject(s) visible</div>
                        <div class="action-row">
                            <button class="btn btn-primary" type="submit">Apply</button>
                            <a class="btn btn-secondary" href="show-subject.php">Reset</a>
                        </div>
                    </div>
                </form>

                <?php if ($subjects === []): ?>
                    <div class="empty-state">No subjects are available for the selected filter.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Test</th>
                                    <th>Time</th>
                                    <th>Questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $row): ?>
                                    <tr>
                                        <td>
                                            <strong><?= pafAdminEsc($row['name']) ?></strong>
                                            <div class="muted">Subject ID: <?= (int) $row['id'] ?></div>
                                        </td>
                                        <td><?= pafAdminEsc($row['test_name']) ?></td>
                                        <td><?= (int) $row['time_in_minutes'] ?> min</td>
                                        <td><?= (int) $row['question_count'] ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a class="btn btn-secondary" href="show-question.php?test_id=<?= (int) $row['test_id'] ?>&subject_id=<?= (int) $row['id'] ?>">Questions</a>
                                                <a class="btn btn-secondary" href="show-subject.php?edit=<?= (int) $row['id'] ?>&test_id=<?= (int) $row['test_id'] ?>">Edit</a>
                                                <form method="POST" onsubmit="return confirm('Delete this subject and its linked questions?');">
                                                    <input type="hidden" name="delete_id" value="<?= (int) $row['id'] ?>">
                                                    <button class="btn btn-danger" type="submit">Delete</button>
                                                </form>
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

    <div class="sheet-backdrop" id="addSubjectSheet">
        <div class="sheet">
            <div class="sheet-head">
                <div>
                    <h3>Add Subjects</h3>
                    <p class="helper-text">Choose one test and add one or more subjects in a clean batch.</p>
                </div>
                <button class="btn btn-ghost" type="button" onclick="closeSheet('addSubjectSheet')">Close</button>
            </div>
            <form class="sheet-form" method="POST" action="insert_subjects.php" id="addSubjectForm">
                <div>
                    <label class="label" for="modal_test_id">Select Test</label>
                    <select id="modal_test_id" name="test_id" required>
                        <option value="">Select a test</option>
                        <?php foreach ($tests as $test): ?>
                            <option value="<?= (int) $test['id'] ?>" <?= $testId === (int) $test['id'] ? 'selected' : '' ?>>
                                <?= pafAdminEsc($test['test_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="subjectRows">
                    <div class="subject-row">
                        <div>
                            <label class="label">Subject Name</label>
                            <input type="text" name="subjects[]" required>
                        </div>
                        <div>
                            <label class="label">Time</label>
                            <input type="number" name="times[]" min="1" placeholder="minutes" required>
                        </div>
                        <button class="btn btn-ghost" type="button" onclick="removeSubjectRow(this)">Remove</button>
                    </div>
                </div>

                <div class="action-row">
                    <button class="btn btn-secondary" type="button" onclick="addSubjectRow()">Add Another</button>
                    <button class="btn btn-primary" type="submit">Save Subjects</button>
                </div>
            </form>
        </div>
    </div>

    <div class="sheet-backdrop" id="editSubjectSheet">
        <div class="sheet">
            <div class="sheet-head">
                <div>
                    <h3>Edit Subject</h3>
                    <p class="helper-text">Adjust the test, title, or time for the selected subject.</p>
                </div>
                <a class="btn btn-ghost" href="show-subject.php<?= $testId > 0 ? '?test_id=' . $testId : '' ?>">Close</a>
            </div>
            <?php if ($editData): ?>
                <form class="sheet-form" method="POST">
                    <input type="hidden" name="edit_id" value="<?= (int) $editData['id'] ?>">
                    <div>
                        <label class="label" for="edit_test_id">Test</label>
                        <select id="edit_test_id" name="edit_test_id" required>
                            <?php foreach ($tests as $test): ?>
                                <option value="<?= (int) $test['id'] ?>" <?= (int) $editData['test_id'] === (int) $test['id'] ? 'selected' : '' ?>>
                                    <?= pafAdminEsc($test['test_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="label" for="edit_name">Subject Name</label>
                        <input id="edit_name" type="text" name="edit_name" value="<?= pafAdminEsc($editData['name']) ?>" required>
                    </div>
                    <div>
                        <label class="label" for="edit_time">Time In Minutes</label>
                        <input id="edit_time" type="number" name="edit_time" min="1" value="<?= (int) $editData['time_in_minutes'] ?>" required>
                    </div>
                    <div class="action-row">
                        <button class="btn btn-primary" type="submit">Update Subject</button>
                        <a class="btn btn-secondary" href="show-subject.php<?= $testId > 0 ? '?test_id=' . $testId : '' ?>">Cancel</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="empty-state">The requested subject could not be loaded.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openSheet(id) {
            const sheet = document.getElementById(id);
            if (sheet) {
                sheet.classList.add('is-open');
            }
        }

        function closeSheet(id) {
            const sheet = document.getElementById(id);
            if (sheet) {
                sheet.classList.remove('is-open');
            }
        }

        function addSubjectRow() {
            const container = document.getElementById('subjectRows');
            const row = document.createElement('div');
            row.className = 'subject-row';
            row.innerHTML = `
                <div>
                    <label class="label">Subject Name</label>
                    <input type="text" name="subjects[]" required>
                </div>
                <div>
                    <label class="label">Time</label>
                    <input type="number" name="times[]" min="1" placeholder="minutes" required>
                </div>
                <button class="btn btn-ghost" type="button" onclick="removeSubjectRow(this)">Remove</button>
            `;
            container.appendChild(row);
        }

        function removeSubjectRow(button) {
            const rows = document.querySelectorAll('#subjectRows .subject-row');
            if (rows.length === 1) {
                rows[0].querySelector('input[name="subjects[]"]').value = '';
                rows[0].querySelector('input[name="times[]"]').value = '';
                return;
            }

            button.closest('.subject-row').remove();
        }

        document.querySelectorAll('.sheet-backdrop').forEach(function(backdrop) {
            backdrop.addEventListener('click', function(event) {
                if (event.target === backdrop) {
                    backdrop.classList.remove('is-open');
                }
            });
        });

        <?php if ($openSheet === 'add'): ?>
        openSheet('addSubjectSheet');
        <?php endif; ?>

        <?php if ($editData): ?>
        openSheet('editSubjectSheet');
        <?php endif; ?>
    </script>
</body>
</html>
