<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
include '../../Database/config.php';

$message = '';
$messageType = '';

if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in edit_heroareas.php");
    $message = "ERROR: Database connection failed.";
    $messageType = "danger";
}

$baseBackendDir = dirname(__DIR__, 2);
$uploadDirServer = $baseBackendDir . '/uploads/heroareas/';
$webRootPrefix = '';
$uploadDirWeb = $webRootPrefix . '/backend/uploads/heroareas/';

function getMediaPath($path, $uploadDirWeb) {
    if (empty($path)) return '';
    if (strpos($path, $uploadDirWeb) === 0) return htmlspecialchars($path);
    return htmlspecialchars($uploadDirWeb . basename($path));
}

function handleUpload($fileKey, $dirServer, $dirWeb, $maxSize, $allowedExts, &$errors) {
    $file = $_FILES[$fileKey];
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return '';

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading $fileKey.";
        return '';
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        $errors[] = "Invalid $fileKey type.";
        return '';
    }
    if ($file['size'] > $maxSize) {
        $errors[] = "$fileKey is too large.";
        return '';
    }

    $filename = uniqid($fileKey . '_', true) . '.' . $ext;
    $serverPath = $dirServer . $filename;
    $webPath = $dirWeb . $filename;

    if (!is_dir($dirServer)) mkdir($dirServer, 0755, true);

    if (move_uploaded_file($file['tmp_name'], $serverPath)) return $webPath;

    $errors[] = "Failed to save $fileKey.";
    return '';
}

// Get ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: show_heroareas.php?message=' . urlencode("Invalid ID") . '&type=danger');
    exit();
}
$id = intval($_GET['id']);

$row = ['main_image' => '', 'video_content' => ''];
if ($conn && !$conn->connect_error) {
    $stmt = $conn->prepare("SELECT id, main_image, video_content FROM heroareas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) $row = $result->fetch_assoc();
    else {
        header('Location: show_heroareas.php?message=' . urlencode("Hero area not found.") . '&type=danger');
        exit();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maxSize = 10 * 1024 * 1024; // 10 MB
    $errors = [];

    $newImagePath = $row['main_image'];
    $newVideoPath = $row['video_content'];

    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $imagePath = handleUpload('main_image', $uploadDirServer, $uploadDirWeb, $maxSize, ['jpg', 'jpeg', 'png', 'webp'], $errors);
        if ($imagePath) {
            $newImagePath = $imagePath;
            if (!empty($row['main_image'])) {
                $old = realpath(str_replace($uploadDirWeb, $uploadDirServer, $row['main_image']));
                if (is_file($old)) unlink($old);
            }
        }
    }

    if (isset($_FILES['video_content']) && $_FILES['video_content']['error'] !== UPLOAD_ERR_NO_FILE) {
        $videoPath = handleUpload('video_content', $uploadDirServer, $uploadDirWeb, $maxSize, ['mp4', 'webm', 'ogg'], $errors);
        if ($videoPath) {
            $newVideoPath = $videoPath;
            if (!empty($row['video_content'])) {
                $old = realpath(str_replace($uploadDirWeb, $uploadDirServer, $row['video_content']));
                if (is_file($old)) unlink($old);
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE heroareas SET main_image=?, video_content=? WHERE id=?");
        $stmt->bind_param("ssi", $newImagePath, $newVideoPath, $id);
        if ($stmt->execute()) {
            header('Location: show_heroareas.php?message=' . urlencode("Hero area updated.") . '&type=success');
            exit();
        } else {
            $message = "Update failed: " . $stmt->error;
            $messageType = "danger";
        }
        $stmt->close();
    } else {
        $message = implode("<br>", $errors);
        $messageType = "danger";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Hero Area</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
    
   
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>
<div class="main container py-4">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-images fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Hero Area Image Management</h1>
                    <small class="text-muted">Upload and manage hero section media for your website.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_heroareas.php" class="text-decoration-none text-success fw-semibold">Hero Areas</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Media</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show text-center" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="form-header">
        <h2 class="form-heading">Edit Hero Area Media</h2>
        <p class="form-subheading">Upload a new main image or video content to update your hero section.</p>
    </div>

    <div class="form-card">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row d-flex flex-column gap-4">

                <!-- MAIN IMAGE -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="main_image" class="form-label">Main Image</label><br>
                        <?php if (!empty($row['main_image'])): ?>
                            <img src="<?= getMediaPath($row['main_image'], $uploadDirWeb) ?>" alt="Main Image" width="100" class="mb-2 rounded" />
                        <?php endif; ?>
                        <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" />
                        <small class="form-text text-muted">Upload a new main image (Max 10MB, JPG, PNG, WEBP).</small>
                    </div>
                </div>

                <!-- VIDEO CONTENT -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="video_content" class="form-label">Video Content</label><br>
                        <?php if (!empty($row['video_content'])): ?>
                            <video width="150" height="100" controls class="mb-2 rounded">
                                <source src="<?= getMediaPath($row['video_content'], $uploadDirWeb) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video><br>
                        <?php endif; ?>
                        <input type="file" id="video_content" name="video_content" class="form-control" accept="video/*" />
                        <small class="form-text text-muted">Upload new video (MP4/WEBM/OGG, Max 10MB).</small>
                    </div>
                </div>

            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" class="btn-submit">Update</button>
                <a href="show_heroareas.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    // Optional: Auto-dismiss alerts that are not 'danger' after a few seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            if (!alertElement.classList.contains('alert-danger')) {
                setTimeout(() => {
                    const bootstrapAlert = bootstrap.Alert.getInstance(alertElement);
                    if (bootstrapAlert) {
                        bootstrapAlert.close();
                    }
                }, 7000); // Dismiss after 7 seconds
            }
        }
    });
</script>
</body>
</html>