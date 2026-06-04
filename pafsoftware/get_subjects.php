<?php
include 'config.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects
$sql = "SELECT id, name FROM subjects";
$result = $conn->query($sql);

$options = "";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $options .= "<option value='".$row['id']."'>".$row['name']."</option>";
    }
} else {
    $options = "<option value=''>No subjects available</option>";
}
echo $options;

$conn->close();
?>
