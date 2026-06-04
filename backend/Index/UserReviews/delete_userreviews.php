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
    error_log("Database connection failed in delete_userreviews.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
    header('Location: show_userreviews.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
    exit();
}

$baseBackendDir = dirname(__DIR__, 2);
$userReviewsUploadDirServerSide = $baseBackendDir . '/uploads/user_reviews/';


$webRootPrefix = '/Highbrows';
$userReviewsUploadDirWebRelative = $webRootPrefix . '/backend/uploads/user_reviews/';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // First, fetch the image path to delete the file from the server
    $getImageQuery = "SELECT main_image FROM user_reviews WHERE id = ?";
    $stmt = $conn->prepare($getImageQuery);

    if ($stmt === false) {
        error_log("Database prepare failed (delete - get image): " . $conn->error);
        $message = "An internal database error occurred while preparing to delete the review.";
        $messageType = "danger";
    } else {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $imageToDeleteDBPath = $row['main_image'];

            // Convert web-accessible path to server-side path for deletion
            if (!empty($imageToDeleteDBPath)) {
                $fileToDeleteServerPath = str_replace($userReviewsUploadDirWebRelative, $userReviewsUploadDirServerSide, $imageToDeleteDBPath);
                $fileToDeleteServerPath = realpath($fileToDeleteServerPath); // Resolve to absolute path for safety

                // Ensure the file exists and is within the allowed upload directory before unlinking
                if ($fileToDeleteServerPath && file_exists($fileToDeleteServerPath) && is_file($fileToDeleteServerPath)) {
                    // Check if the file is actually within the uploads directory to prevent directory traversal attacks
                    if (strpos($fileToDeleteServerPath, $userReviewsUploadDirServerSide) === 0) {
                        if (!unlink($fileToDeleteServerPath)) {
                            error_log("Failed to delete user review image file: " . $fileToDeleteServerPath);
                            // Not critical enough to stop DB deletion, but good to log
                        }
                    } else {
                        error_log("Attempted to delete file outside of user_reviews upload directory: " . $fileToDeleteServerPath);
                    }
                }
            }

            // Now, delete the record from the database
            $deleteQuery = "DELETE FROM user_reviews WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);

            if ($stmt === false) {
                error_log("Database prepare failed (delete - record): " . $conn->error);
                $message = "An internal database error occurred while preparing to delete the review record.";
                $messageType = "danger";
            } else {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $message = "";
                    $messageType = "success";
                } else {
                    error_log("Database execute failed (delete): " . $stmt->error);
                    $message = "Failed to delete user review: " . $stmt->error;
                    $messageType = "danger";
                }
                $stmt->close();
            }
        } else {
            $message = "User Review not found.";
            $messageType = "warning";
        }
    }
} else {
    $message = "Invalid request: No ID provided for deletion.";
    $messageType = "danger";
}

$conn->close();

header('Location: show_userreviews.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
exit();
?>