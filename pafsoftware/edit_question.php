<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';

pafAdminRequireLogin();

$pdo = getPDOConnection();
$flash = pafAdminPullFlash();
$questionId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($questionId <= 0) {
    pafAdminSetFlash('error', 'Question not found.');
    header('Location: show-question.php');
    exit();
}

$statement = $pdo->prepare(
    'SELECT
        q.*,
        s.test_id,
        s.name AS subject_name,
        t.test_name
     FROM questions q
     INNER JOIN subjects s ON s.id = q.subject_id
     INNER JOIN tests t ON t.id = s.test_id
     WHERE q.id = ?
     LIMIT 1'
);
$statement->execute([$questionId]);
$question = $statement->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    pafAdminSetFlash('error', 'Question not found.');
    header('Location: show-question.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include __DIR__ . '/header.php'; ?>

        <main class="main-content">
            <section class="page-hero">
                <div>
                    <h1>Edit Question</h1>
                    <p><?= pafAdminEsc($question['test_name']) ?> / <?= pafAdminEsc($question['subject_name']) ?> / Q<?= (int) $question['sequence_number'] ?></p>
                </div>
                <div class="action-row">
                    <a class="btn btn-secondary" href="show-question.php?test_id=<?= (int) $question['test_id'] ?>&subject_id=<?= (int) $question['subject_id'] ?>">Back To Question Bank</a>
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
                        <h2>Question Editor</h2>
                        <p>Update text, answer keys, and optional media from one compact form.</p>
                    </div>
                </div>

                <form class="sheet-form" method="POST" action="update_question.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= (int) $question['id'] ?>">
                    <input type="hidden" name="return_test_id" value="<?= (int) $question['test_id'] ?>">
                    <input type="hidden" name="return_subject_id" value="<?= (int) $question['subject_id'] ?>">

                    <div>
                        <label class="label" for="question_text">Question Text</label>
                        <textarea id="question_text" name="question_text" required><?= pafAdminEsc($question['question_text']) ?></textarea>
                    </div>

                    <div>
                        <label class="label" for="question_image">Question Image</label>
                        <?php if (!empty($question['question_image'])): ?>
                            <div class="chip-row" style="margin-bottom:10px;">
                                <img class="media-thumb" src="<?= pafAdminEsc($question['question_image']) ?>" alt="Question image">
                            </div>
                        <?php endif; ?>
                        <input id="question_image" type="file" name="question_image" accept="image/*">
                    </div>

                    <div class="question-builder-grid">
                        <?php foreach (['a', 'b', 'c', 'd', 'e'] as $option): ?>
                            <div>
                                <label class="label" for="option_<?= $option ?>">Option <?= strtoupper($option) ?> Text</label>
                                <input id="option_<?= $option ?>" type="text" name="option_<?= $option ?>" value="<?= pafAdminEsc($question['option_' . $option]) ?>">
                            </div>
                            <div>
                                <label class="label" for="option_<?= $option ?>_image">Option <?= strtoupper($option) ?> Image</label>
                                <?php if (!empty($question['option_' . $option . '_image'])): ?>
                                    <div class="chip-row" style="margin-bottom:10px;">
                                        <img class="media-thumb" src="<?= pafAdminEsc($question['option_' . $option . '_image']) ?>" alt="Option image">
                                    </div>
                                <?php endif; ?>
                                <input id="option_<?= $option ?>_image" type="file" name="option_<?= $option ?>_image" accept="image/*">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div>
                        <label class="label" for="correct_answer">Correct Answer</label>
                        <select id="correct_answer" name="correct_answer" required>
                            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $answer): ?>
                                <option value="<?= $answer ?>" <?= strtoupper((string) $question['correct_answer']) === $answer ? 'selected' : '' ?>>
                                    Option <?= $answer ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="action-row">
                        <button class="btn btn-primary" type="submit">Update Question</button>
                        <a class="btn btn-secondary" href="show-question.php?test_id=<?= (int) $question['test_id'] ?>&subject_id=<?= (int) $question['subject_id'] ?>">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
