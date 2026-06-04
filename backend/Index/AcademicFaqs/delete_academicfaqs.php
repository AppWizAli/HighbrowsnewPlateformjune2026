<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
  
include '../../Database/config.php';
include '../preloader.php';
$message = '';
$messageType = '';

if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed.";
    $messageType = "danger";
    header('Location: show_academicfaqs.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // ✅ Updated safe table name
    $deleteQuery = "DELETE FROM academic_faqs WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $message = "Failed to prepare deletion query.";
        $messageType = "danger";
    } else {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "FAQ deleted successfully!";
            $messageType = "success";
        } else {
            error_log("Execute failed: " . $stmt->error);
            $message = "Failed to delete FAQ.";
            $messageType = "danger";
        }
        $stmt->close();
    }
} else {
    $message = "Invalid deletion request: Missing ID.";
    $messageType = "danger";
}

$conn->close();

header('Location: show_academicfaqs.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
exit();
?>
