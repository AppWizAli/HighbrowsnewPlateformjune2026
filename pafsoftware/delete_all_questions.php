<?php
include 'config.php'; // Ensure this file correctly sets up $conn and other configurations
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get the subject ID from the POST request
$subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : 0;

if ($subject_id > 0) {
    // Begin a transaction
    $conn->begin_transaction();
    
    try {
        // Delete questions related to the subject
        $delete_questions_sql = "DELETE FROM questions WHERE subject_id = ?";
        $delete_questions_stmt = $conn->prepare($delete_questions_sql);
        if ($delete_questions_stmt === false) {
            throw new Exception("Error preparing SQL: " . $conn->error);
        }
        $delete_questions_stmt->bind_param("i", $subject_id);
        if (!$delete_questions_stmt->execute()) {
            throw new Exception("Error executing deletion: " . $delete_questions_stmt->error);
        }
        $delete_questions_stmt->close();

        // Commit the transaction
        $conn->commit();
        
        // Return success response
        echo 'success';
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if something fails
        $conn->rollback();
        // Return error response
        echo 'error';
        exit();
    }
} else {
    echo 'error';
    exit();
}

?>
