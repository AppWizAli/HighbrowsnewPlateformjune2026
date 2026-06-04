<?php
include "config.php";
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
    $delete_id = $_GET['user_id']; 

    // Begin transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Step 1: Delete related records from the 'answers' table
        $delete_answers_sql = "DELETE FROM answers WHERE user_id = ?";
        $stmt = $conn->prepare($delete_answers_sql);
        $stmt->bind_param("s", $delete_id); // Bind as a string
        if (!$stmt->execute()) {
            throw new Exception('Error deleting related records in answers table.');
        }
        $stmt->close();

        // Step 2: Delete related records from the 'results' table
        $delete_results_sql = "DELETE FROM results WHERE user_id = ?";
        $stmt = $conn->prepare($delete_results_sql);
        $stmt->bind_param("s", $delete_id); // Bind as a string
        if (!$stmt->execute()) {
            throw new Exception('Error deleting related records in results table.');
        }
        $stmt->close();

        // Step 3: Delete the user from the 'useres' table
        $delete_user_sql = "DELETE FROM useres WHERE id = ?";
        $stmt = $conn->prepare($delete_user_sql);
        $stmt->bind_param("s", $delete_id); // Bind as a string
        if ($stmt->execute()) {
            // Commit the transaction if all deletions were successful
            $conn->commit();
            echo 'success'; // Return success message for AJAX
        } else {
            throw new Exception('Error deleting user.');
        }
        $stmt->close();

    } catch (Exception $e) {
        // Rollback the transaction if any deletion fails
        $conn->rollback();
        echo 'error: ' . $e->getMessage(); // Return error message
    }
} else {
    echo 'invalid_request'; // Return error message for invalid request
}
