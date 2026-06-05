<?php
// reschedule_test.php
include "config.php";

// Check if user_id is provided
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Begin a transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Step 1: Delete related records from the 'answers' table
        $deleteAnswersSql = "DELETE FROM answers WHERE user_id = ?";
        $stmt = $conn->prepare($deleteAnswersSql);
        $stmt->bind_param("s", $userId); // Bind as a string for varchar user_id
        if (!$stmt->execute()) {
            throw new Exception('Error deleting from answers table.');
        }
        $stmt->close();

        // Step 2: Delete the user's record from the 'results' table
        $deleteResultsSql = "DELETE FROM results WHERE user_id = ?";
        $stmt = $conn->prepare($deleteResultsSql);
        $stmt->bind_param("s", $userId); // Bind as a string for varchar user_id
        if (!$stmt->execute()) {
            throw new Exception('Error deleting from results table.');
        }
        $stmt->close();

        // Commit the transaction if both deletions succeed
        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        // Roll back the transaction if any deletion fails
        $conn->rollback();
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'invalid_request';
}
?>
