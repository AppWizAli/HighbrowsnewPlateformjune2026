<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_id = $_POST['test_id'];
    $subjects = $_POST['subjects'];
    $times = $_POST['times'];

    // Insert subjects for the selected test
    foreach ($subjects as $key => $subject_name) {
        $time = $times[$key];

        $query = "INSERT INTO subjects (test_id, name, time_in_minutes) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isi", $test_id, $subject_name, $time);
        $stmt->execute();
    }

    // Redirect back to the admin panel or show success message
    header('Location: show-subject.php');
}
?>
