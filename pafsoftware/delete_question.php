<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

header('Content-Type: application/json');

pafAdminRequireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$questionId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($questionId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Question not found.']);
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

    $deleteAnswers = $pdo->prepare('DELETE FROM answers WHERE question_id = ?');
    $deleteAnswers->execute([$questionId]);

    if ($reportingReady) {
        $deleteQuestionResults = $pdo->prepare('DELETE FROM question_result_details WHERE question_id = ?');
        $deleteQuestionResults->execute([$questionId]);
    }

    $deleteQuestion = $pdo->prepare('DELETE FROM questions WHERE id = ?');
    $deleteQuestion->execute([$questionId]);

    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Question deleted successfully.']);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode(['status' => 'error', 'message' => 'Unable to delete the selected question.']);
}
