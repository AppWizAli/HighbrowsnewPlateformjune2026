<?php
require_once 'db_config.php';

$pdo = getPDOConnection();

// Check if POST data is available
if (isset($_POST['user_id'], $_POST['question_id'])) {
    $user_id = (int) $_POST['user_id'];
    $question_id = (int) $_POST['question_id'];
    $answer = isset($_POST['answer']) ? $_POST['answer'] : '';
    $mark_for_review = isset($_POST['mark_for_review']) ? 1 : 0;
    $no_answer_selected = isset($_POST['no_answer_selected']) ? $_POST['no_answer_selected'] : '0';
    
    $is_skipped = 0;
    if ((empty($answer) || $answer === 'F' || $no_answer_selected === '1') && $mark_for_review == 0) {
        $is_skipped = 1;
    }
    
    error_log("Debug - Final values: Answer='$answer', Mark for review=$mark_for_review, No answer selected='$no_answer_selected', Is skipped=$is_skipped");
    error_log("Debug - POST data: " . print_r($_POST, true));

    // Check if the answer already exists
    $sql = "SELECT answer FROM answers WHERE user_id = ? AND question_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $question_id]);
    $existingAnswer = $stmt->fetchColumn();

    if ($existingAnswer !== false) {
        // Update existing answer
        $sql = "UPDATE answers SET answer = ?, mark_for_review = ?, is_skipped = ? WHERE user_id = ? AND question_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$answer, $mark_for_review, $is_skipped, $user_id, $question_id]);
        error_log("Updated existing answer for user $user_id, question $question_id");
    } else {
        // Insert new answer
        $sql = "INSERT INTO answers (user_id, question_id, answer, mark_for_review, is_skipped) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $question_id, $answer, $mark_for_review, $is_skipped]);
        error_log("Inserted new answer for user $user_id, question $question_id");
    }

    if ($stmt) {
        echo "Answer saved successfully";
    } else {
        echo "Error: Could not save the answer";
    }
} else {
    echo "Error: Missing required POST data";
}
?>
