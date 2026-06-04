<?php
session_start();
include 'config.php'; // Ensure this file includes your DB connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Check if test_id is provided
$testId = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

$subjectOptions = "";
if ($testId > 0) {
    // Fetch subjects based on the selected test
    $query = "SELECT id, name, time_in_minutes FROM subjects WHERE test_id = $testId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $subjectOptions .= "<option value='{$row['id']}'>{$row['name']} (Time: {$row['time_in_minutes']} mins)</option>";
            }
        } else {
            $subjectOptions = "<option value=''>No subjects found</option>";
        }
    } else {
        $subjectOptions = "<option value=''>Error fetching subjects</option>";
    }
} else {
    $subjectOptions = "<option value=''>Select a test first</option>";
}

echo $subjectOptions;
?>
