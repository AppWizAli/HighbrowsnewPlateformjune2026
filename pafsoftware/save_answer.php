<?php
require_once 'db_config.php';
require_once 'result_service.php';

$pdo = getPDOConnection();
header('Content-Type: application/json');

if (isset($_POST['user_id'], $_POST['question_id'])) {
    $user_id = pafNormaliseUserId($_POST['user_id']);
    $question_id = (int) $_POST['question_id'];
    $answer = isset($_POST['answer']) ? trim((string) $_POST['answer']) : '';
    $mark_for_review = isset($_POST['mark_for_review']) ? 1 : 0;
    $no_answer_selected = isset($_POST['no_answer_selected']) ? (string) $_POST['no_answer_selected'] : '0';
    
    $is_skipped = 0;
    if (($answer === '' || $answer === 'F' || $no_answer_selected === '1') && $mark_for_review === 0) {
        $is_skipped = 1;
    }

    $sql = "SELECT answer FROM answers WHERE user_id = ? AND question_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $question_id]);
    $existingAnswer = $stmt->fetchColumn();

    if ($existingAnswer !== false) {
        $sql = "UPDATE answers SET answer = ?, mark_for_review = ?, is_skipped = ? WHERE user_id = ? AND question_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$answer, $mark_for_review, $is_skipped, $user_id, $question_id]);
    } else {
        $sql = "INSERT INTO answers (user_id, question_id, answer, mark_for_review, is_skipped) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $question_id, $answer, $mark_for_review, $is_skipped]);
    }

    echo json_encode([
        'success' => true,
        'answer' => $answer,
        'mark_for_review' => $mark_for_review,
        'is_skipped' => $is_skipped,
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required POST data',
    ]);
}
?>
