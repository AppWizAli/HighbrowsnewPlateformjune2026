<?php

header('Content-Type: application/json');

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

if (!isset($_POST['user_id'], $_POST['subject_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required POST data.',
    ]);
    exit();
}

$userId = pafNormaliseUserId($_POST['user_id']);
$subjectId = (int) $_POST['subject_id'];

try {
    $pdo->beginTransaction();

    $summary = pafUpsertSubjectResult($pdo, $userId, $subjectId);
    $overall = pafUpsertOverallResult($pdo, $userId, $summary['test_id']);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'student_id' => $userId,
        'student_name' => (string) ($summary['student']['name'] ?? ''),
        'test_id' => $summary['test_id'],
        'test_name' => $summary['test_name'],
        'subject_id' => $summary['subject_id'],
        'subject_name' => $summary['subject_name'],
        'total_questions' => $summary['total_questions'],
        'attempted_questions' => $summary['attempted_questions'],
        'correct_answers' => $summary['correct_answers'],
        'wrong_answers' => $summary['wrong_answers'],
        'not_answered_questions' => $summary['not_answered_questions'],
        'review_questions' => $summary['review_questions'],
        'percentage' => $summary['percentage'],
        'subject_result' => $summary['subject_result'],
        'overall_result' => $overall,
    ]);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'error' => 'Unable to save subject result: ' . $exception->getMessage(),
    ]);
}
