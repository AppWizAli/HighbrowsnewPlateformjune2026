<?php
session_start();
include 'config.php'; // Ensure this file includes your DB connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch tests from the database
$query = "SELECT id, test_name FROM tests";
$result = mysqli_query($conn, $query);

$testOptions = "";
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $testOptions .= "<option value='{$row['id']}'>{$row['test_name']}</option>";
    }
} else {
    $testOptions = "<option value=''>Error fetching tests</option>";
}

echo $testOptions;
?>
