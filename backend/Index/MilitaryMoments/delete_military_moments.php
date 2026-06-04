<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
include '../../Database/config.php'; // Corrected path
  include '../preloader.php';
// Initialize variables for statement and connection to ensure they can be closed
$stmt_fetch_images = null;
$stmt_delete = null;

// Check for database connection error
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in delete_military_moment.php: " . ($conn->connect_error ?? 'Connection object not found'));
    header('Location: show_military_moments.php?error=Database+connection+failed.+Please+try+again+later.');
    exit(); // Exit immediately if no DB connection
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    // Close connection before exiting if ID is invalid
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    header('Location: show_military_moments.php?error=Invalid+moment+ID+provided+for+deletion.');
    exit();
}

// First, fetch the image filenames to delete the files
$stmt_fetch_images = $conn->prepare("SELECT main_image, main_image2 FROM military_moment WHERE id = ?");
if (!$stmt_fetch_images) {
    error_log("Prepare failed (fetch images for delete): " . $conn->error);
    // Close connection before exiting
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    header('Location: show_military_moments.php?error=Internal+server+error+during+deletion+process.');
    exit();
}
$stmt_fetch_images->bind_param("i", $id);
$stmt_fetch_images->execute();
$result_fetch_images = $stmt_fetch_images->get_result();
$row_images = $result_fetch_images->fetch_assoc();
$stmt_fetch_images->close(); // Close fetch statement after use

if (!$row_images) {
    // Close connection before exiting
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    header('Location: show_military_moments.php?error=Military+moment+not+found+for+deletion.');
    exit();
}

$mainImageToDelete = $row_images['main_image'];
$mainImage2ToDelete = $row_images['main_image2'];
$uploadDir = __DIR__ . '/uploads/military_moments/';

// Delete the record from the database
$stmt_delete = $conn->prepare("DELETE FROM military_moment WHERE id = ?");
if (!$stmt_delete) {
    error_log("Prepare failed (delete record): " . $conn->error);
    // Close connection before exiting
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    header('Location: show_military_moments.php?error=Internal+server+error+during+deletion.');
    exit();
}
$stmt_delete->bind_param("i", $id);

if ($stmt_delete->execute()) {
    // If database deletion is successful, then delete the files
    $fileDeletionSuccess = true;

    if (!empty($mainImageToDelete) && file_exists($uploadDir . $mainImageToDelete)) {
        if (!unlink($uploadDir . $mainImageToDelete)) {
            error_log("Failed to delete main military moment image file: " . $uploadDir . $mainImageToDelete);
            $fileDeletionSuccess = false;
        }
    }

    if (!empty($mainImage2ToDelete) && file_exists($uploadDir . $mainImage2ToDelete)) {
        if (!unlink($uploadDir . $mainImage2ToDelete)) {
            error_log("Failed to delete second military moment image file: " . $uploadDir . $mainImage2ToDelete);
            $fileDeletionSuccess = false;
        }
    }

    $stmt_delete->close(); // Close delete statement before redirecting
    if (isset($conn) && $conn instanceof mysqli) { // Close connection before redirecting
        $conn->close();
    }

    if ($fileDeletionSuccess) {
        header('Location: show_military_moments.php?success=Military+moment+deleted+successfully!');
    } else {
        header('Location: show_military_moments.php?success=Military+moment+deleted+from+database,+but+some+image+files+could+not+be+removed.');
    }
    exit();
} else {
    error_log("Database execute failed (delete): " . $stmt_delete->error);
    $stmt_delete->close(); // Close delete statement on failure
    if (isset($conn) && $conn instanceof mysqli) { // Close connection on failure
        $conn->close();
    }
    header('Location: show_military_moments.php?error=Error+deleting+military+moment+from+database.');
    exit();
}

// No need for a final $stmt_delete->close() here as it's handled within the if/else.
// The final $conn->close() is also handled within the if/else blocks that lead to exit().
// This structure ensures resources are closed as soon as they are no longer needed, before an exit.
?>