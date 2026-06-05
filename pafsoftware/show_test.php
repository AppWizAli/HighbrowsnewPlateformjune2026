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

        $subjectStatement = $pdo->prepare('SELECT id FROM subjects WHERE test_id = ?');
        $subjectStatement->execute([$deleteId]);
        $subjectIds = array_map('intval', $subjectStatement->fetchAll(PDO::FETCH_COLUMN));

        $questionIds = [];
        if ($subjectIds !== []) {
            $subjectPlaceholders = implode(',', array_fill(0, count($subjectIds), '?'));
            $questionStatement = $pdo->prepare("SELECT id FROM questions WHERE subject_id IN ($subjectPlaceholders)");
            $questionStatement->execute($subjectIds);
            $questionIds = array_map('intval', $questionStatement->fetchAll(PDO::FETCH_COLUMN));
        }

        if ($questionIds !== []) {
            $questionPlaceholders = implode(',', array_fill(0, count($questionIds), '?'));
            $deleteAnswers = $pdo->prepare("DELETE FROM answers WHERE question_id IN ($questionPlaceholders)");
            $deleteAnswers->execute($questionIds);
        }

        if ($reportingReady) {
            $deleteQuestionResults = $pdo->prepare('DELETE FROM question_result_details WHERE test_id = ?');
            $deleteQuestionResults->execute([$deleteId]);

            $deleteSubjectResults = $pdo->prepare('DELETE FROM subject_result_summaries WHERE test_id = ?');
            $deleteSubjectResults->execute([$deleteId]);

            $deleteOverallResults = $pdo->prepare('DELETE FROM overall_test_results WHERE test_id = ?');
            $deleteOverallResults->execute([$deleteId]);
        }

        if ($subjectIds !== []) {
            $subjectPlaceholders = implode(',', array_fill(0, count($subjectIds), '?'));
            $deleteLegacyResults = $pdo->prepare("DELETE FROM results WHERE subject_id IN ($subjectPlaceholders)");
            $deleteLegacyResults->execute($subjectIds);

            $deleteQuestions = $pdo->prepare("DELETE FROM questions WHERE subject_id IN ($subjectPlaceholders)");
            $deleteQuestions->execute($subjectIds);

            $deleteSubjects = $pdo->prepare("DELETE FROM subjects WHERE id IN ($subjectPlaceholders)");
            $deleteSubjects->execute($subjectIds);
        }

        $deleteTest = $pdo->prepare('DELETE FROM tests WHERE id = ?');
        $deleteTest->execute([$deleteId]);

        $pdo->commit();
        pafAdminSetFlash('success', 'Test and linked records deleted successfully.');
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        pafAdminSetFlash('error', 'Unable to delete the selected test right now.');
    }

    header('Location: show_test.php');
    exit();
}

$search = trim((string) ($_GET['search'] ?? ''));
$openSheet = (string) ($_GET['open'] ?? '');
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;

$params = [];
$sql = 'SELECT
            t.id,
            t.test_id,
            t.test_name,
            t.date_added,
            COUNT(DISTINCT s.id) AS subject_count,
            COUNT(q.id) AS question_count
        FROM tests t
        LEFT JOIN subjects s ON s.test_id = t.id
        LEFT JOIN questions q ON q.subject_id = s.id';

if ($search !== '') {
    $sql .= ' WHERE t.test_name LIKE ? OR t.test_id LIKE ?';
    $searchLike = '%' . $search . '%';
    $params[] = $searchLike;
    $params[] = $searchLike;
}

$sql .= ' GROUP BY t.id, t.test_id, t.test_name, t.date_added
          ORDER BY t.date_added DESC, t.id DESC';

$statement = $pdo->prepare($sql);
$statement->execute($params);
$tests = $statement->fetchAll(PDO::FETCH_ASSOC);

$editData = null;
if ($editId > 0) {
    $editStatement = $pdo->prepare('SELECT id, test_id, test_name, date_added FROM tests WHERE id = ? LIMIT 1');
    $editStatement->execute([$editId]);
    $editData = $editStatement->fetch(PDO::FETCH_ASSOC) ?: null;
}

$summary = [
    'tests' => count($tests),
    'subjects' => array_sum(array_map(static fn(array $row): int => (int) $row['subject_count'], $tests)),
    'questions' => array_sum(array_map(static fn(array $row): int => (int) $row['question_count'], $tests)),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tests</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Tests</h1>
                    <p>One place to add, review, edit, and clean up all tests.</p>
                </div>
                <div class="action-row">
                    <button class="btn btn-primary" type="button" onclick="openSheet('addTestSheet')">Add Test</button>
                    <a class="btn btn-secondary" href="show-subject.php">Open Subjects</a>
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
                        <span>Visible Tests</span>
                        <strong><?= $summary['tests'] ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Linked Subjects</span>
                        <strong><?= $summary['subjects'] ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Total MCQs</span>
                        <strong><?= $summary['questions'] ?></strong>
                    </div>
                </div>
            </section>

            <section class="table-card">
                <div class="panel-head">
                    <div>
                        <h2>Test Directory</h2>
                        <p>Search by name or test code, then manage records from one table.</p>
                    </div>
                </div>

                <form method="GET" class="toolbar">
                    <div class="filters-grid">
                        <div>
                            <label class="label" for="search">Search</label>
                            <input id="search" type="search" name="search" value="<?= pafAdminEsc($search) ?>" placeholder="Test name or code">
                        </div>
                    </div>
                    <div class="toolbar-row">
                        <div class="muted"><?= count($tests) ?> test(s) found</div>
                        <div class="action-row">
                            <button class="btn btn-primary" type="submit">Apply</button>
                            <a class="btn btn-secondary" href="show_test.php">Reset</a>
                        </div>
                    </div>
                </form>

                <?php if ($tests === []): ?>
                    <div class="empty-state">No tests match the current search.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Test</th>
                                    <th>Date Added</th>
                                    <th>Subjects</th>
                                    <th>Questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tests as $row): ?>
                                    <tr>
                                        <td>
                                            <strong><?= pafAdminEsc($row['test_name']) ?></strong>
                                            <div class="muted">Code: <?= pafAdminEsc($row['test_id']) ?></div>
                                        </td>
                                        <td><?= pafAdminEsc($row['date_added']) ?></td>
                                        <td><?= (int) $row['subject_count'] ?></td>
                                        <td><?= (int) $row['question_count'] ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a class="btn btn-secondary" href="show-subject.php?test_id=<?= (int) $row['id'] ?>">Subjects</a>
                                                <a class="btn btn-secondary" href="show_test.php?edit=<?= (int) $row['id'] ?>">Edit</a>
                                                <form method="POST" onsubmit="return confirm('Delete this test and all linked data?');">
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

    <div class="sheet-backdrop" id="addTestSheet">
        <div class="sheet">
            <div class="sheet-head">
                <div>
                    <h3>Add Test</h3>
                    <p class="helper-text">Create a new test without leaving the listing page.</p>
                </div>
                <button class="btn btn-ghost" type="button" onclick="closeSheet('addTestSheet')">Close</button>
            </div>
            <form class="sheet-form" method="POST" action="insert_test.php">
                <div>
                    <label class="label" for="test_id">Test Code</label>
                    <input id="test_id" type="text" name="test_id" required>
                </div>
                <div>
                    <label class="label" for="test_name">Test Name</label>
                    <input id="test_name" type="text" name="test_name" required>
                </div>
                <div>
                    <label class="label" for="date_added">Date Added</label>
                    <input id="date_added" type="date" name="date_added" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="action-row">
                    <button class="btn btn-primary" type="submit">Save Test</button>
                    <button class="btn btn-secondary" type="button" onclick="closeSheet('addTestSheet')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="sheet-backdrop" id="editTestSheet">
        <div class="sheet">
            <div class="sheet-head">
                <div>
                    <h3>Edit Test</h3>
                    <p class="helper-text">Update the visible test details only.</p>
                </div>
                <a class="btn btn-ghost" href="show_test.php">Close</a>
            </div>
            <?php if ($editData): ?>
                <form class="sheet-form" method="POST" action="update_test.php">
                    <input type="hidden" name="edit_id" value="<?= (int) $editData['id'] ?>">
                    <div>
                        <label class="label" for="edit_test_code">Test Code</label>
                        <input id="edit_test_code" type="text" value="<?= pafAdminEsc($editData['test_id']) ?>" disabled>
                    </div>
                    <div>
                        <label class="label" for="edit_test_name">Test Name</label>
                        <input id="edit_test_name" type="text" name="edit_test_name" value="<?= pafAdminEsc($editData['test_name']) ?>" required>
                    </div>
                    <div>
                        <label class="label" for="edit_date_added">Date Added</label>
                        <input id="edit_date_added" type="date" name="edit_date_added" value="<?= pafAdminEsc($editData['date_added']) ?>" required>
                    </div>
                    <div class="action-row">
                        <button class="btn btn-primary" type="submit">Update Test</button>
                        <a class="btn btn-secondary" href="show_test.php">Cancel</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="empty-state">The requested test could not be loaded.</div>
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

        document.querySelectorAll('.sheet-backdrop').forEach(function(backdrop) {
            backdrop.addEventListener('click', function(event) {
                if (event.target === backdrop) {
                    backdrop.classList.remove('is-open');
                }
            });
        });

        <?php if ($openSheet === 'add'): ?>
        openSheet('addTestSheet');
        <?php endif; ?>

        <?php if ($editData): ?>
        openSheet('editTestSheet');
        <?php endif; ?>
    </script>
</body>
</html>
