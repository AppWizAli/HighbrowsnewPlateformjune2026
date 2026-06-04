<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
include '../../Database/config.php';

$error = '';
$success = '';
$maxFileSize = 100 * 1024 * 1024; // 100MB limit

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $description2 = trim($_POST['description2']);
    $videoUrl = trim($_POST['video_url'] ?? '');

    $videoFileName = null;
    $thumbnailFileName = null;

    if (empty($title) || empty($description)) {
        $error = "Title and Description are required.";
    } elseif (!empty($videoUrl) && isset($_FILES['main_video']) && $_FILES['main_video']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error = "Please choose either a video file or a video URL, not both.";
    } else {
        // ✅ Handle video file upload (if no URL provided)
        if (empty($videoUrl) && isset($_FILES['main_video']) && $_FILES['main_video']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['main_video']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['main_video']['tmp_name'];
                $fileName = $_FILES['main_video']['name'];
                $fileSize = $_FILES['main_video']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['mp4', 'webm', 'ogg'];
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $error = "Invalid video format. Allowed: mp4, webm, ogg.";
                } elseif ($fileSize > $maxFileSize) {
                    $error = "Video file size exceeds 100MB.";
                } else {
                    $uploadDir = __DIR__ . '/uploads/reviews/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $newFileName = uniqid('review_video_', true) . '.' . $fileExtension;
                    $destPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $videoFileName = $newFileName;
                    } else {
                        $error = "Error moving uploaded video file.";
                    }
                }
            } else {
                $error = "Video upload error code: " . $_FILES['main_video']['error'];
            }
        }

        // ✅ Handle video URL validation
        if (!empty($videoUrl) && empty($videoFileName)) {
            if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                $error = "Invalid video URL format.";
            }
        }

        // ✅ Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbTmpPath = $_FILES['thumbnail']['tmp_name'];
                $thumbName = $_FILES['thumbnail']['name'];
                $thumbSize = $_FILES['thumbnail']['size'];
                $thumbExtension = strtolower(pathinfo($thumbName, PATHINFO_EXTENSION));
                $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($thumbExtension, $allowedImageExtensions)) {
                    $error = "Invalid thumbnail format.";
                } elseif ($thumbSize > (5 * 1024 * 1024)) {
                    $error = "Thumbnail exceeds 5MB limit.";
                } else {
                    $uploadDir = __DIR__ . '/uploads/reviews/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $thumbNewName = uniqid('thumb_', true) . '.' . $thumbExtension;
                    $thumbDest = $uploadDir . $thumbNewName;
                    if (move_uploaded_file($thumbTmpPath, $thumbDest)) {
                        $thumbnailFileName = $thumbNewName;
                    } else {
                        $error = "Failed to upload thumbnail.";
                    }
                }
            } else {
                $error = "Thumbnail upload error code: " . $_FILES['thumbnail']['error'];
            }
        }

        // ✅ Insert data if no error
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO review_content (title, description, main_video, thumbnail, description2, video_url) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                $error = "Database prepare error: " . $conn->error;
            } else {
                $stmt->bind_param("ssssss", $title, $description, $videoFileName, $thumbnailFileName, $description2, $videoUrl);
                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: show_reviews.php?success=" . urlencode("Review added successfully!"));
                    exit();
                } else {
                    $error = "Execute failed: " . $stmt->error;
                    $stmt->close();
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Review</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="../../Includes/sidebar.css" />
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css" />
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css" />
    <style>
        /* Your CSS styles as provided before */
        /* ... (use your existing CSS here) ... */
    </style>
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>

<div class="main">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-plus-circle fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Add New Review</h1>
                    <small class="text-muted">Create a new customer review entry.</small>
                </div>
            </div>
        </div>
    </header>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-card mx-auto">
        <div class="mb-3 form-group">
            <label for="title">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" required />
        </div>

        <div class="mb-3 form-group">
            <label for="description">Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control" rows="4" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
        </div>

        <div class="mb-3 form-group">
            <label for="description2">Additional Description</label>
            <textarea name="description2" id="description2" class="form-control" rows="3"><?= isset($_POST['description2']) ? htmlspecialchars($_POST['description2']) : '' ?></textarea>
        </div>

        <div class="mb-3 form-group">
            <label for="thumbnail">Upload Thumbnail (JPG, PNG, WebP)</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="form-control" />
            <small class="form-text text-muted">Max size 5MB.</small>
        </div>

        <div class="mb-3 form-group">
            <label for="main_video">Upload Video (mp4, webm, ogg)</label>
            <input type="file" name="main_video" id="main_video" accept="video/mp4,video/webm,video/ogg" class="form-control" />
            <small class="form-text text-muted">Max size 100MB.</small>
        </div>
<div class="mb-3 form-group">
  <label for="video_url">Or Enter Video URL (YouTube, Vimeo, etc)</label>
  <input type="url" name="video_url" id="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=abc123">
  <small class="form-text text-muted">Only fill this if not uploading a video file.</small>
</div>


        <div class="form-footer d-flex justify-content-end">
            <button type="submit" name="submit" class=" btn-submit">Add Review</button>
            <a href="show_reviews.php" class="btn btn-secondary mx-2">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
</body>
</html>
