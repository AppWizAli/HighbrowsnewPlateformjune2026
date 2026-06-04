<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';

$message = '';
$messageType = '';

if (isset($_POST['submit'])) {
    if (!isset($conn) || $conn->connect_error) {
        $message = "Database connection failed.";
        $messageType = "danger";
        header('Location: show_heroareas.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
        exit();
    }

    $baseBackendDir = dirname(__DIR__, 2); // Points to /backend
    $uploadDir = $baseBackendDir . '/uploads/heroareas/';
    $webPath = '/backend/uploads/heroareas/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $allowedImageExts = ['jpg', 'jpeg', 'png', 'gif'];
    $allowedVideoExts = ['mp4', 'webm', 'ogg'];
    $maxSize = 20 * 1024 * 1024; // 20MB

    $errors = [];

    // 🔄 Function to upload multiple files
    function uploadMultipleFiles($fileInput, $uploadDir, $webPath, $allowedExts, $maxSize, &$errors) {
        $paths = [];

        if (!isset($_FILES[$fileInput])) return [];

        foreach ($_FILES[$fileInput]['name'] as $index => $name) {
            if ($_FILES[$fileInput]['error'][$index] === UPLOAD_ERR_NO_FILE) continue;

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExts)) {
                $errors[] = "Invalid file type: $name";
                continue;
            }

            if ($_FILES[$fileInput]['size'][$index] > $maxSize) {
                $errors[] = "File too large: $name";
                continue;
            }

            $newName = uniqid($fileInput . '_', true) . '.' . $ext;
            $targetPath = $uploadDir . $newName;
            $dbPath = $webPath . $newName;

            if (move_uploaded_file($_FILES[$fileInput]['tmp_name'][$index], $targetPath)) {
                $paths[] = $dbPath;
            } else {
                $errors[] = "Upload failed: $name";
            }
        }

        return $paths;
    }

    // 📤 Upload files
    $mainImagePaths = uploadMultipleFiles('main_image', $uploadDir, $webPath, $allowedImageExts, $maxSize, $errors);
    $videoPaths = uploadMultipleFiles('video_content', $uploadDir, $webPath, $allowedVideoExts, $maxSize, $errors);

    $mainImageStr = implode(',', $mainImagePaths);
    $videoStr = implode(',', $videoPaths);

    if (empty($mainImageStr) && empty($videoStr)) {
        $errors[] = "Please upload at least one image or video.";
    }

    // 💾 Insert into database
    if (!empty($errors)) {
        $message = implode("<br>", $errors);
        $messageType = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO heroareas (main_image, video_content) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $mainImageStr, $videoStr);
            if ($stmt->execute()) {
                $message = "Upload successful.";
                $messageType = "success";
            } else {
                $message = "Insert failed: " . $stmt->error;
                $messageType = "danger";
            }
            $stmt->close();
        } else {
            $message = "Prepare failed: " . $conn->error;
            $messageType = "danger";
        }
    }

    $conn->close();
    header('Location: show_heroareas.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
    exit();
} else {
    header('Location: add_heroareas.php?message=' . urlencode("Access denied.") . '&type=danger');
    exit();
}
