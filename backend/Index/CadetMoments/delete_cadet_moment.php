<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php'); // Corrected path to login.php
    exit();
}
include '../../Database/config.php'; // Corrected path
  include '../preloader.php';
// --- Database Connection Check ---
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in delete_cadet_moment.php: " . ($conn->connect_error ?? 'Connection object not found'));
    header("Location: view_cadet_moments.php?status=error&message=" . urlencode("Database connection failed. Please try again later."));
    exit();
}

// Define the base path for images (IMPORTANT for file_exists and unlink)
// This path must match the server path where images are *stored* from add/edit operations.
// Assuming your 'uploads' folder is at 'backend/uploads/cadet_moments/'
$baseUploadDirServerPath = dirname(__DIR__, 2) . '/uploads/cadet_moments/';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to integer for safety

    // 1. Fetch image paths before deleting the record using a prepared statement
    $sql_fetch_images = "SELECT main_image, main_image2 FROM cadet_moments WHERE id = ?";
    $stmt_fetch_images = $conn->prepare($sql_fetch_images);

    if ($stmt_fetch_images) {
        $stmt_fetch_images->bind_param("i", $id);
        $stmt_fetch_images->execute();
        $result_images = $stmt_fetch_images->get_result();

        if ($result_images && $result_images->num_rows > 0) {
            $row_images = $result_images->fetch_assoc();
            $main_image_filename = $row_images['main_image']; // This is just the filename
            $main_image2_filename = $row_images['main_image2']; // This is just the filename

            // Construct full server paths for deletion
            $main_image_full_path = $baseUploadDirServerPath . $main_image_filename;
            $main_image2_full_path = $baseUploadDirServerPath . $main_image2_filename;

            // Delete main_image file if it exists and path is valid
            if (!empty($main_image_filename) && file_exists($main_image_full_path)) {
                if (unlink($main_image_full_path)) {
                    // File deleted successfully
                } else {
                    error_log("Failed to delete main image file: " . $main_image_full_path);
                    // Optionally, you could send a warning message back
                    // $file_delete_warning = "Warning: Main image file could not be deleted.";
                }
            }

            // Delete main_image2 file if it exists and path is valid
            if (!empty($main_image2_filename) && file_exists($main_image2_full_path)) {
                if (unlink($main_image2_full_path)) {
                    // File deleted successfully
                } else {
                    error_log("Failed to delete second image file: " . $main_image2_full_path);
                    // Optionally, you could send a warning message back
                    // $file_delete_warning .= " Warning: Second image file could not be deleted.";
                }
            }
        }
        $stmt_fetch_images->close();
    } else {
        error_log("Prepare failed (fetch images for delete): " . $conn->error);
        header("Location: view_cadet_moments.php?status=error&message=" . urlencode("Database error: Could not prepare to fetch image paths."));
        $conn->close();
        exit();
    }

    // 2. Delete the record from the database using a prepared statement
    $sql_delete = "DELETE FROM cadet_moments WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);

    if ($stmt_delete) {
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            // Redirect with success message (and any file delete warnings if you implemented them)
            $redirect_message = "Cadet moment deleted successfully!";
            // if (isset($file_delete_warning)) {
            //     $redirect_message .= $file_delete_warning;
            // }
            header("Location: view_cadet_moments.php?status=deleted&message=" . urlencode($redirect_message));
            exit();
        } else {
            error_log("Database execute failed (delete record): " . $stmt_delete->error);
            header("Location: view_cadet_moments.php?status=error&message=" . urlencode("Database error: " . $stmt_delete->error));
            exit();
        }
        $stmt_delete->close();
    } else {
        error_log("Prepare failed (delete record): " . $conn->error);
        header("Location: view_cadet_moments.php?status=error&message=" . urlencode("Database error: Could not prepare delete statement."));
        exit();
    }
} else {
    // If no ID or invalid ID is provided
    header("Location: view_cadet_moments.php?status=error&message=" . urlencode("Invalid moment ID provided for deletion."));
    exit();
}

$conn->close(); // Ensure connection is closed on all exit paths
?>