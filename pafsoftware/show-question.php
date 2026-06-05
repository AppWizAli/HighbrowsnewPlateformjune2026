<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';

pafAdminRequireLogin();

$pdo = getPDOConnection();
$flash = pafAdminPullFlash();

$tests = $pdo->query('SELECT id, test_name FROM tests ORDER BY test_name ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC);
$testId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : 0;
$subjectId = isset($_GET['subject_id']) && (int) $_GET['subject_id'] > 0 ? (int) $_GET['subject_id'] : 0;
$search = trim((string) ($_GET['search'] ?? ''));

$selectedSubject = null;
if ($subjectId > 0) {
    $selectedSubjectStatement = $pdo->prepare(
        'SELECT id, test_id, name, time_in_minutes
         FROM subjects
         WHERE id = ?
         LIMIT 1'
    );
    $selectedSubjectStatement->execute([$subjectId]);
    $selectedSubject = $selectedSubjectStatement->fetch(PDO::FETCH_ASSOC) ?: null;

    if ($selectedSubject === null) {
        $subjectId = 0;
    } elseif ($testId <= 0) {
        $testId = (int) $selectedSubject['test_id'];
    } elseif ($testId !== (int) $selectedSubject['test_id']) {
        $subjectId = 0;
        $selectedSubject = null;
    }
}

$subjectsSql = 'SELECT id, test_id, name, time_in_minutes FROM subjects';
$subjectParams = [];
if ($testId > 0) {
    $subjectsSql .= ' WHERE test_id = ?';
    $subjectParams[] = $testId;
} else {
    $subjectsSql .= ' WHERE 1 = 0';
}
$subjectsSql .= ' ORDER BY name ASC, id ASC';
$subjectsStatement = $pdo->prepare($subjectsSql);
$subjectsStatement->execute($subjectParams);
$subjects = $subjectsStatement->fetchAll(PDO::FETCH_ASSOC);

if ($subjectId > 0) {
    $validSubjectIds = array_map(static fn(array $subject): int => (int) $subject['id'], $subjects);
    if (!in_array($subjectId, $validSubjectIds, true)) {
        $subjectId = 0;
    }
}

$questionSql = 'SELECT
                    q.id,
                    q.subject_id,
                    q.sequence_number,
                    q.question_text,
                    q.question_image,
                    q.option_a,
                    q.option_a_image,
                    q.option_b,
                    q.option_b_image,
                    q.option_c,
                    q.option_c_image,
                    q.option_d,
                    q.option_d_image,
                    q.option_e,
                    q.option_e_image,
                    q.correct_answer,
                    s.test_id,
                    s.name AS subject_name,
                    t.test_name
                FROM questions q
                INNER JOIN subjects s ON s.id = q.subject_id
                INNER JOIN tests t ON t.id = s.test_id';
$questionParams = [];
$conditions = [];

if ($testId > 0) {
    $conditions[] = 's.test_id = ?';
    $questionParams[] = $testId;
}

if ($subjectId > 0) {
    $conditions[] = 'q.subject_id = ?';
    $questionParams[] = $subjectId;
}

if ($search !== '') {
    $conditions[] = '(q.question_text LIKE ? OR s.name LIKE ? OR t.test_name LIKE ?)';
    $searchLike = '%' . $search . '%';
    array_push($questionParams, $searchLike, $searchLike, $searchLike);
}

if ($conditions !== []) {
    $questionSql .= ' WHERE ' . implode(' AND ', $conditions);
}

$questionSql .= ' ORDER BY t.test_name ASC, s.name ASC, q.sequence_number ASC, q.id ASC';
$questionStatement = $pdo->prepare($questionSql);
$questionStatement->execute($questionParams);
$questions = $questionStatement->fetchAll(PDO::FETCH_ASSOC);

$summary = [
    'questions' => count($questions),
    'subjects' => count(array_unique(array_map(static fn(array $row): int => (int) $row['subject_id'], $questions))),
    'tests' => count(array_unique(array_map(static fn(array $row): int => (int) $row['test_id'], $questions))),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Questions</h1>
                    <p>Filter by test or subject, then edit the question bank with less clutter and faster review.</p>
                </div>
                <div class="action-row">
                    <a class="btn btn-primary" href="add-questions.php<?= $testId > 0 ? '?test_id=' . $testId . ($subjectId > 0 ? '&subject_id=' . $subjectId : '') : '' ?>">Add Questions</a>
                    <a class="btn btn-secondary" href="show-subject.php<?= $testId > 0 ? '?test_id=' . $testId : '' ?>">Open Subjects</a>
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
                        <span>Visible Questions</span>
                        <strong><?= $summary['questions'] ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Visible Subjects</span>
                        <strong><?= $summary['subjects'] ?></strong>
                    </div>
                    <div class="metric-card">
                        <span>Visible Tests</span>
                        <strong><?= $summary['tests'] ?></strong>
                    </div>
                </div>
            </section>

            <section class="table-card">
                <div class="panel-head">
                    <div>
                        <h2>Question Bank</h2>
                        <p>Use a smaller, cleaner table and open full edit only when needed.</p>
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
                        <div>
                            <label class="label" for="subject_id">Filter By Subject</label>
                            <select id="subject_id" name="subject_id">
                                <option value=""><?= $testId > 0 ? 'All subjects' : 'Select a test first' ?></option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= (int) $subject['id'] ?>" <?= $subjectId === (int) $subject['id'] ? 'selected' : '' ?>>
                                        <?= pafAdminEsc($subject['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="label" for="search">Search</label>
                            <input id="search" type="search" name="search" value="<?= pafAdminEsc($search) ?>" placeholder="Question text, subject, or test">
                        </div>
                    </div>
                    <div class="toolbar-row">
                        <div class="muted"><?= count($questions) ?> question(s) found</div>
                        <div class="action-row">
                            <button class="btn btn-primary" type="submit">Apply</button>
                            <a class="btn btn-secondary" href="show-question.php">Reset</a>
                            <?php if ($subjectId > 0): ?>
                                <button class="btn btn-danger" type="button" onclick="deleteAllQuestions(<?= $subjectId ?>)">Delete All In Subject</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <?php if ($questions === []): ?>
                    <div class="empty-state">No questions matched the current filters.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Subject</th>
                                    <th>Media</th>
                                    <th>Correct</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($questions as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="question-summary">
                                                <strong>Q<?= (int) $row['sequence_number'] ?>. <?= pafAdminEsc($row['question_text']) ?></strong>
                                                <div class="chip-row">
                                                    <span class="chip">A: <?= pafAdminEsc($row['option_a']) ?: '-' ?></span>
                                                    <span class="chip">B: <?= pafAdminEsc($row['option_b']) ?: '-' ?></span>
                                                    <span class="chip">C: <?= pafAdminEsc($row['option_c']) ?: '-' ?></span>
                                                    <span class="chip">D: <?= pafAdminEsc($row['option_d']) ?: '-' ?></span>
                                                    <span class="chip">E: <?= pafAdminEsc($row['option_e']) ?: '-' ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?= pafAdminEsc($row['subject_name']) ?></strong>
                                            <div class="muted"><?= pafAdminEsc($row['test_name']) ?></div>
                                        </td>
                                        <td>
                                            <div class="chip-row">
                                                <?php if (!empty($row['question_image'])): ?>
                                                    <img class="media-thumb" src="<?= pafAdminEsc($row['question_image']) ?>" alt="Question image">
                                                <?php endif; ?>
                                                <?php foreach (['option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image'] as $imageField): ?>
                                                    <?php if (!empty($row[$imageField])): ?>
                                                        <img class="media-thumb" src="<?= pafAdminEsc($row[$imageField]) ?>" alt="Option image">
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php if (empty($row['question_image']) && empty($row['option_a_image']) && empty($row['option_b_image']) && empty($row['option_c_image']) && empty($row['option_d_image']) && empty($row['option_e_image'])): ?>
                                                    <span class="muted">No media</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><span class="chip"><?= pafAdminEsc($row['correct_answer']) ?></span></td>
                                        <td>
                                            <div class="table-actions">
                                                <a class="btn btn-secondary" href="edit_question.php?id=<?= (int) $row['id'] ?>">Edit</a>
                                                <button class="btn btn-danger" type="button" onclick="deleteQuestion(<?= (int) $row['id'] ?>)">Delete</button>
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

    <script>
        const testFilter = document.getElementById('test_id');
        const subjectFilter = document.getElementById('subject_id');

        if (testFilter && subjectFilter) {
            testFilter.addEventListener('change', function() {
                const selectedTestId = this.value;

                if (!selectedTestId) {
                    subjectFilter.innerHTML = '<option value="">Select a test first</option>';
                    return;
                }

                subjectFilter.innerHTML = '<option value="">Loading subjects...</option>';

                fetch('get_subjects_by_test.php?test_id=' + encodeURIComponent(selectedTestId))
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(html) {
                        subjectFilter.innerHTML = html;
                    })
                    .catch(function() {
                        subjectFilter.innerHTML = '<option value="">Unable to load subjects</option>';
                    });
            });
        }

        function deleteQuestion(id) {
            if (!confirm('Delete this question?')) {
                return;
            }

            fetch('delete_question.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.status === 'success') {
                    window.location.reload();
                    return;
                }

                alert(data.message || 'Unable to delete question.');
            })
            .catch(function() {
                alert('Unable to delete question.');
            });
        }

        function deleteAllQuestions(subjectId) {
            if (!confirm('Delete all questions in this subject?')) {
                return;
            }

            fetch('delete_all_questions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'subject_id=' + encodeURIComponent(subjectId)
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(response) {
                if (response.trim() === 'success') {
                    window.location.reload();
                    return;
                }

                alert('Unable to delete all subject questions.');
            })
            .catch(function() {
                alert('Unable to delete all subject questions.');
            });
        }
    </script>
</body>
</html>
