<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';

pafAdminRequireLogin();

$pdo = getPDOConnection();
$flash = pafAdminPullFlash();

$tests = $pdo->query('SELECT id, test_name FROM tests ORDER BY test_name ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC);
$selectedTestId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : ((int) ($tests[0]['id'] ?? 0));
$selectedSubjectId = isset($_GET['subject_id']) && (int) $_GET['subject_id'] > 0 ? (int) $_GET['subject_id'] : 0;

$subjects = [];
if ($selectedTestId > 0) {
    $subjectStatement = $pdo->prepare('SELECT id, name, time_in_minutes FROM subjects WHERE test_id = ? ORDER BY name ASC, id ASC');
    $subjectStatement->execute([$selectedTestId]);
    $subjects = $subjectStatement->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Add Questions</h1>
                    <p>Import from Excel or build the question set manually with a cleaner editor.</p>
                </div>
                <div class="action-row">
                    <a class="btn btn-secondary" href="show-question.php<?= $selectedTestId > 0 ? '?test_id=' . $selectedTestId . ($selectedSubjectId > 0 ? '&subject_id=' . $selectedSubjectId : '') : '' ?>">Back To Question Bank</a>
                </div>
            </section>

            <?php if ($flash): ?>
                <div class="flash <?= pafAdminEsc($flash['type']) ?>">
                    <?= pafAdminEsc($flash['message']) ?>
                </div>
            <?php endif; ?>

            <section class="panel-card">
                <div class="panel-head">
                    <div>
                        <h2>Target Subject</h2>
                        <p>Select the test and subject once. Both import and manual entry will use the same selection.</p>
                    </div>
                </div>

                <form id="question-form" method="POST" action="insert_questions.php" enctype="multipart/form-data" class="toolbar">
                    <div class="filters-grid">
                        <div>
                            <label class="label" for="test">Select Test</label>
                            <select id="test" name="test_id" required>
                                <?php foreach ($tests as $test): ?>
                                    <option value="<?= (int) $test['id'] ?>" <?= $selectedTestId === (int) $test['id'] ? 'selected' : '' ?>>
                                        <?= pafAdminEsc($test['test_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="label" for="subject">Select Subject</label>
                            <select id="subject" name="subject_id" required>
                                <option value="">Select a subject</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= (int) $subject['id'] ?>" <?= $selectedSubjectId === (int) $subject['id'] ? 'selected' : '' ?>>
                                        <?= pafAdminEsc($subject['name']) ?> (<?= (int) $subject['time_in_minutes'] ?> min)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="dashboard-grid">
                        <div class="detail-card">
                            <div class="detail-head">
                                <div>
                                    <h3>Import From Excel</h3>
                                    <div class="detail-meta">Upload `.xls` or `.xlsx` for quick bulk entry.</div>
                                </div>
                            </div>
                            <div class="sheet-form">
                                <div>
                                    <label class="label" for="excelFile">Excel File</label>
                                    <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx">
                                </div>
                                <div class="action-row">
                                    <button type="submit" name="import" class="btn btn-primary">Import Questions</button>
                                </div>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-head">
                                <div>
                                    <h3>Manual Entry</h3>
                                    <div class="detail-meta">Add individual questions with text, optional images, and answer keys.</div>
                                </div>
                            </div>

                            <div id="questionInputs" class="sheet-form"></div>

                            <div class="action-row">
                                <button type="button" id="addQuestion" class="btn btn-secondary">Add Another Question</button>
                                <button type="submit" class="btn btn-success">Save Manual Questions</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <template id="questionTemplate">
        <div class="question-card">
            <div class="split-row">
                <h3 class="question-title"></h3>
                <button type="button" class="btn btn-danger remove-question">Remove</button>
            </div>

            <div>
                <label class="label">Question Text</label>
                <textarea data-field="question_text"></textarea>
            </div>

            <div>
                <label class="label">Question Image</label>
                <input type="file" data-field="question_image" accept="image/*">
            </div>

            <div class="question-builder-grid">
                <div>
                    <label class="label">Option A Text</label>
                    <input type="text" data-field="option_a_text">
                </div>
                <div>
                    <label class="label">Option A Image</label>
                    <input type="file" data-field="option_a_image" accept="image/*">
                </div>
                <div>
                    <label class="label">Option B Text</label>
                    <input type="text" data-field="option_b_text">
                </div>
                <div>
                    <label class="label">Option B Image</label>
                    <input type="file" data-field="option_b_image" accept="image/*">
                </div>
                <div>
                    <label class="label">Option C Text</label>
                    <input type="text" data-field="option_c_text">
                </div>
                <div>
                    <label class="label">Option C Image</label>
                    <input type="file" data-field="option_c_image" accept="image/*">
                </div>
                <div>
                    <label class="label">Option D Text</label>
                    <input type="text" data-field="option_d_text">
                </div>
                <div>
                    <label class="label">Option D Image</label>
                    <input type="file" data-field="option_d_image" accept="image/*">
                </div>
                <div>
                    <label class="label">Option E Text</label>
                    <input type="text" data-field="option_e_text">
                </div>
                <div>
                    <label class="label">Option E Image</label>
                    <input type="file" data-field="option_e_image" accept="image/*">
                </div>
            </div>

            <div>
                <label class="label">Correct Answer</label>
                <select data-field="correct_answer">
                    <option value="A">Option A</option>
                    <option value="B">Option B</option>
                    <option value="C">Option C</option>
                    <option value="D">Option D</option>
                    <option value="E">Option E</option>
                </select>
            </div>
        </div>
    </template>

    <script>
        const questionInputs = document.getElementById('questionInputs');
        const questionTemplate = document.getElementById('questionTemplate');
        const testSelect = document.getElementById('test');
        const subjectSelect = document.getElementById('subject');

        function updateQuestionTitles() {
            const cards = questionInputs.querySelectorAll('.question-card');
            cards.forEach(function(card, index) {
                card.querySelector('.question-title').textContent = 'Question ' + (index + 1);
            });
        }

        function applyFieldNames(card, index) {
            card.querySelectorAll('[data-field]').forEach(function(field) {
                const fieldName = field.getAttribute('data-field');
                field.name = 'questions[' + index + '][' + fieldName + ']';
            });
        }

        function refreshQuestionIndexes() {
            const cards = questionInputs.querySelectorAll('.question-card');
            cards.forEach(function(card, index) {
                applyFieldNames(card, index);
            });
            updateQuestionTitles();
        }

        function addQuestionCard() {
            const clone = questionTemplate.content.cloneNode(true);
            const card = clone.querySelector('.question-card');
            card.querySelector('.remove-question').addEventListener('click', function() {
                if (questionInputs.querySelectorAll('.question-card').length === 1) {
                    card.querySelectorAll('input[type="text"], textarea').forEach(function(field) {
                        field.value = '';
                    });
                    card.querySelectorAll('input[type="file"]').forEach(function(field) {
                        field.value = '';
                    });
                    card.querySelector('select').selectedIndex = 0;
                    return;
                }

                card.remove();
                refreshQuestionIndexes();
            });

            questionInputs.appendChild(clone);
            refreshQuestionIndexes();
        }

        function loadSubjectsForTest(selectedSubjectId) {
            const testId = testSelect.value;
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';

            fetch('get_subjects_by_test.php?test_id=' + encodeURIComponent(testId))
                .then(function(response) {
                    return response.text();
                })
                .then(function(html) {
                    subjectSelect.innerHTML = html || '<option value="">No subjects found</option>';
                    if (selectedSubjectId) {
                        subjectSelect.value = selectedSubjectId;
                    }
                })
                .catch(function() {
                    subjectSelect.innerHTML = '<option value="">Unable to load subjects</option>';
                });
        }

        document.getElementById('addQuestion').addEventListener('click', addQuestionCard);

        testSelect.addEventListener('change', function() {
            loadSubjectsForTest('');
        });

        addQuestionCard();
    </script>
</body>
</html>
