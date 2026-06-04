<?php
include "config.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $conn->begin_transaction();

    try {
       
        $delete_answers_sql = "DELETE FROM answers"; 
        if (!$conn->query($delete_answers_sql)) {
            throw new Exception('Error deleting records from the answers table.');
        }


        $delete_results_sql = "DELETE FROM results"; 
        if (!$conn->query($delete_results_sql)) {
            throw new Exception('Error deleting records from the results table.');
        }

        $delete_users_sql = "DELETE FROM useres";
        if (!$conn->query($delete_users_sql)) {
            throw new Exception('Error deleting users.');
        }

        $conn->commit();
        echo 'success'; 

    } catch (Exception $e) {
     
        $conn->rollback();
        echo 'error: ' . $e->getMessage(); 
    }
}

// Close the connection
$conn->close();
?>
