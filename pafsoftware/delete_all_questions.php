<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

pafAdminRequireLogin();

$subjectId = isset($_POST['subject_id']) ? (int) $_POST['subject_id'] : 0;
if ($subjectId <= 0) {
    echo 'error';
    exit();
}

$pdo = getPDOConnection();
$reportingReady = true;

try {
    pafEnsureResultTables($pdo);
} catch (Throwable $exception) {
    $reportingReady = false;
}

try {
    $pdo->beginTransaction();

    $questionStatement = $pdo->prepare('SELECT id FROM questions WHERE subject_id = ?');
    $questionStatement->execute([$subjectId]);
    $questionIds = array_map('intval', $questionStatement->fetchAll(PDO::FETCH_COLUMN));

    if ($questionIds !== []) {
        $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
        $deleteAnswers = $pdo->prepare("DELETE FROM answers WHERE question_id IN ($placeholders)");
        $deleteAnswers->execute($questionIds);
    }

    if ($reportingReady) {
        $deleteQuestionResults = $pdo->prepare('DELETE FROM question_result_details WHERE subject_id = ?');
        $deleteQuestionResults->execute([$subjectId]);
    }

    $deleteQuestions = $pdo->prepare('DELETE FROM questions WHERE subject_id = ?');
    $deleteQuestions->execute([$subjectId]);

    $pdo->commit();
    echo 'success';
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo 'error';
}
