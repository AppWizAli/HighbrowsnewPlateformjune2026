<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_id = $_POST['id'];

    // Prepare the SQL statement to delete the question
    $sql = "DELETE FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Question deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => ' deleting question' ] );
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
