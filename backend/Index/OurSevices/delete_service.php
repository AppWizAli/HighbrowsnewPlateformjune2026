<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php'); // Adjusted relative path for login
    exit();
}
include '../../Database/config.php'; // Corrected path
  include '../preloader.php';
// Check for database connection error
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in delete_service.php: " . ($conn->connect_error ?? 'Connection object not found'));
    header('Location: show_services.php?error=Database+connection+failed.+Please+try+again+later.');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: show_services.php?error=Invalid+service+ID+provided+for+deletion.');
    exit();
}

// First, fetch the image filename to delete the file
$stmt_fetch_image = $conn->prepare("SELECT main_image FROM our_services WHERE id = ?");
if (!$stmt_fetch_image) {
    error_log("Prepare failed (fetch image for delete): " . $conn->error);
    header('Location: show_services.php?error=Internal+server+error+during+deletion+process.');
    exit();
}
$stmt_fetch_image->bind_param("i", $id);
$stmt_fetch_image->execute();
$result_fetch_image = $stmt_fetch_image->get_result();
$row_image = $result_fetch_image->fetch_assoc();
$stmt_fetch_image->close();

if (!$row_image) {
    header('Location: show_services.php?error=Service+not+found+for+deletion.');
    exit();
}

$imageToDelete = $row_image['main_image'];
$uploadDir = __DIR__ . '/uploads/services/';
$imagePath = $uploadDir . $imageToDelete;

// Delete the record from the database first
$stmt_delete = $conn->prepare("DELETE FROM our_services WHERE id = ?");
if (!$stmt_delete) {
    error_log("Prepare failed (delete record): " . $conn->error);
    header('Location: show_services.php?error=Internal+server+error+during+deletion.');
    exit();
}
$stmt_delete->bind_param("i", $id);

if ($stmt_delete->execute()) {
    // If database deletion is successful, then delete the file
    if (!empty($imageToDelete) && file_exists($imagePath)) {
        if (!unlink($imagePath)) {
            error_log("Failed to delete service image file: " . $imagePath);
            // Log the error but don't stop, as DB record is already gone.
            // You might inform the user that the user that the file deletion failed but the record is gone.
            header('Location: show_services.php?success=Service+deleted+successfully,+but+image+file+could+not+be+removed.');
            exit();
        }
    }
    header('Location: show_services.php?success=Service+deleted+successfully!');
    exit();
} else {
    error_log("Database execute failed (delete): " . $stmt_delete->error);
    header('Location: show_services.php?error=Error+deleting+service+from+database.');
    exit();
}

$stmt_delete->close();
// Close the database connection
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>