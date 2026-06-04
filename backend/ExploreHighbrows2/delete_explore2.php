<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../LoginReg/login.php");
    exit();
}
include '../Index/preloader.php';
include '../Database/config.php';

if (!isset($_GET['id'])) {
    header("Location: show_explore2.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch the existing image file names
$selectQuery = "SELECT main_image, main_image2 FROM explore_highbrows2 WHERE id = ?";
$stmt = $conn->prepare($selectQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: show_explore2.php?error=Not Found");
    exit();
}

// Define upload directory
$uploadDir = __DIR__ . '/uploads/explore/';

// Delete image files if they exist
if (!empty($row['main_image']) && file_exists($uploadDir . $row['main_image'])) {
    unlink($uploadDir . $row['main_image']);
}
if (!empty($row['main_image2']) && file_exists($uploadDir . $row['main_image2'])) {
    unlink($uploadDir . $row['main_image2']);
}

// Delete record from database
$deleteQuery = "DELETE FROM explore_highbrows2 WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: show_explore2.php?message=Record Deleted Successfully");
    exit();
} else {
    header("Location: show_explore2.php?error=Delete Failed");
    exit();
}
?>
