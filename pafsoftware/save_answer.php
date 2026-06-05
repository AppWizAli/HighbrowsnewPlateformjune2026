<?php

header('Content-Type: application/json');

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

if (!isset($_POST['user_id'], $_POST['question_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required POST data.',
    ]);
    exit();
}

$userId = pafNormaliseUserId($_POST['user_id']);
$questionId = (int) $_POST['question_id'];
$answer = isset($_POST['answer']) ? trim((string) $_POST['answer']) : '';
$markForReview = isset($_POST['mark_for_review']) ? 1 : 0;
$noAnswerSelected = isset($_POST['no_answer_selected']) ? (string) $_POST['no_answer_selected'] : '0';
$isSkipped = 0;

if (($answer === '' || $answer === 'F' || $noAnswerSelected === '1') && $markForReview === 0) {
    $isSkipped = 1;
}

$selectStatement = $pdo->prepare(
    'SELECT question_id
     FROM answers
     WHERE user_id = ? AND question_id = ?
     LIMIT 1'
);
$selectStatement->execute([$userId, $questionId]);
$existingQuestionId = $selectStatement->fetchColumn();

if ($existingQuestionId) {
    $statement = $pdo->prepare(
        'UPDATE answers
         SET answer = ?, mark_for_review = ?, is_skipped = ?
         WHERE user_id = ? AND question_id = ?'
    );
    $statement->execute([$answer, $markForReview, $isSkipped, $userId, $questionId]);
} else {
    $statement = $pdo->prepare(
        'INSERT INTO answers (user_id, question_id, answer, mark_for_review, is_skipped)
         VALUES (?, ?, ?, ?, ?)'
    );
    $statement->execute([$userId, $questionId, $answer, $markForReview, $isSkipped]);
}

echo json_encode([
    'success' => true,
    'question_id' => $questionId,
    'answer' => $answer,
    'mark_for_review' => $markForReview,
    'is_skipped' => $isSkipped,
    'status' => pafQuestionStatusName([
        'answer' => $answer,
        'mark_for_review' => $markForReview,
        'is_skipped' => $isSkipped,
    ]),
]);
