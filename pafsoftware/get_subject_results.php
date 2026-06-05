<?php
header('Content-Type: application/json');

require_once 'db_config.php';

$pdo = getPDOConnection();

// Check if POST data is available
if (isset($_POST['user_id'], $_POST['subject_id'])) {
    $user_id = (int) $_POST['user_id'];
    $subject_id = (int) $_POST['subject_id'];

    try {
        // Get total questions for this subject
        $sql = "SELECT COUNT(*) as total_questions FROM questions WHERE subject_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_id]);
        $totalQuestions = $stmt->fetchColumn();

        // Get correct answers for this subject
        $sql = "SELECT COUNT(*) as correct_answers 
                FROM answers a 
                JOIN questions q ON a.question_id = q.id 
                WHERE a.user_id = ? AND q.subject_id = ? AND a.answer = q.correct_answer";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $subject_id]);
        $correctAnswers = $stmt->fetchColumn();

        // Calculate percentage
        $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        // Get subject name
        $sql = "SELECT name FROM subjects WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_id]);
        $subjectName = $stmt->fetchColumn();

        $response = [
            'success' => true,
            'subject_name' => $subjectName,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'percentage' => $percentage
        ];

        echo json_encode($response);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Missing required POST data']);
}
?>
