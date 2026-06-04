<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
include '../preloader.php';

// CONFIG
$webUploadDir  = '/Highbrows/backend/Index/ReviewsContent/uploads/reviews/';
$fsUploadDir   = $_SERVER['DOCUMENT_ROOT'] . $webUploadDir;
$maxVideoSize  = 100 * 1024 * 1024; // 100MB
$maxThumbSize  = 5 * 1024 * 1024;   // 5MB
$allowedVideoMimes = ['video/mp4', 'video/webm', 'video/ogg'];
$allowedImageExts  = ['jpg', 'jpeg', 'png', 'webp'];

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: show_reviews.php');
    exit();
}

$res = $conn->query("SELECT * FROM review_content WHERE id=" . (int)$id);
if (!$row = $res->fetch_assoc()) {
    header('Location: show_reviews.php');
    exit();
}

$error = '';
$success = '';
$newVideoFile = $row['main_video'];
$newThumbFile = $row['thumbnail'];
$newVideoUrl  = $row['video_url'] ?? '';

if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $descr = trim($_POST['description']);
    $descr2 = trim($_POST['description2']);
    $videoUrl = trim($_POST['video_url']);

    if ($title === '' || $descr === '') {
        $error = 'Title and Description are required.';
    }

    // Handle optional video URL
    if (!$error) {
        if (!empty($videoUrl)) {
            if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                $newVideoUrl = $videoUrl;
                $newVideoFile = null; // Clear uploaded video if URL is provided
            } else {
                $error = "Invalid video URL format.";
            }
        } else {
            $newVideoUrl = null; // Clear previous URL if new one is empty
        }
    }

    // Handle video file upload only if URL is empty
    if (!$error && empty($newVideoUrl) && isset($_FILES['main_video']) && $_FILES['main_video']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['main_video'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $error = 'Upload error (code ' . $f['error'] . ')';
        } elseif ($f['size'] > $maxVideoSize) {
            $error = 'Video file exceeds 100 MB.';
        } elseif (!in_array(mime_content_type($f['tmp_name']), $allowedVideoMimes)) {
            $error = 'Unsupported video format.';
        } else {
            if (!is_dir($fsUploadDir)) mkdir($fsUploadDir, 0755, true);
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            $newVideoFile = uniqid('video_', true) . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $fsUploadDir . $newVideoFile)) {
                if ($row['main_video'] && file_exists($fsUploadDir . $row['main_video'])) {
                    unlink($fsUploadDir . $row['main_video']);
                }
                $newVideoUrl = null; // Clear URL if file uploaded
            } else {
                $error = 'Failed to move uploaded video.';
            }
        }
    }

    // Handle thumbnail upload
    if (!$error && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
        $t = $_FILES['thumbnail'];
        $ext = strtolower(pathinfo($t['name'], PATHINFO_EXTENSION));
        if ($t['error'] !== UPLOAD_ERR_OK) {
            $error = 'Thumbnail upload error (code ' . $t['error'] . ')';
        } elseif ($t['size'] > $maxThumbSize) {
            $error = 'Thumbnail exceeds 5 MB.';
        } elseif (!in_array($ext, $allowedImageExts)) {
            $error = 'Unsupported thumbnail format.';
        } else {
            $newThumbFile = uniqid('thumb_', true) . '.' . $ext;
            if (move_uploaded_file($t['tmp_name'], $fsUploadDir . $newThumbFile)) {
                if ($row['thumbnail'] && file_exists($fsUploadDir . $row['thumbnail'])) {
                    unlink($fsUploadDir . $row['thumbnail']);
                }
            } else {
                $error = 'Failed to upload thumbnail.';
            }
        }
    }

    // Update database
    if (!$error) {
        $stmt = $conn->prepare("UPDATE review_content SET title=?, description=?, main_video=?, video_url=?, thumbnail=?, description2=? WHERE id=?");
        $stmt->bind_param('ssssssi', $title, $descr, $newVideoFile, $newVideoUrl, $newThumbFile, $descr2, $id);
        if ($stmt->execute()) {
            header('Location: show_reviews.php?message=' . urlencode('Review updated!'));
            exit();
        } else {
            $error = 'DB error: ' . $stmt->error;
        }
    }
}

// Optional: Convert YouTube URL to embed format (for preview)
function convertToEmbedUrl($url) {
    if (strpos($url, 'youtube.com') !== false) {
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        return isset($params['v']) ? "https://www.youtube.com/embed/" . $params['v'] : $url;
    }
    if (strpos($url, 'youtu.be') !== false) {
        return "https://www.youtube.com/embed/" . basename(parse_url($url, PHP_URL_PATH));
    }
    return $url;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../../Includes/sidebar.css" />
  <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css" />
  <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css" />
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>

<div class="main">
  <header class="top-header mb-4 enhanced-shadow">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
      <div class="d-flex align-items-start">
        <i class="fa-solid fa-pen fa-2x text-primary me-3 mt-1"></i>
        <div>
          <h1 class="h4 fw-bold text-dark mb-1">Edit Review</h1>
          <small class="text-muted">Edit review details, video, and thumbnail below.</small>
        </div>
      </div>
    </div>
  </header>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="form-card mx-auto">
    <!-- Title -->
    <div class="mb-3 form-group">
      <label for="title">Title <span class="text-danger">*</span></label>
      <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required />
    </div>

    <!-- Description -->
    <div class="mb-3 form-group">
      <label for="description">Description <span class="text-danger">*</span></label>
      <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($row['description']) ?></textarea>
    </div>

    <!-- Additional Description -->
    <div class="mb-3 form-group">
      <label for="description2">Additional Description</label>
      <textarea name="description2" id="description2" class="form-control" rows="3"><?= htmlspecialchars($row['description2']) ?></textarea>
    </div>

    <!-- Thumbnail Preview -->
    <?php if ($row['thumbnail'] && file_exists($fsUploadDir . $row['thumbnail'])): ?>
      <div class="mb-3">
        <label class="form-label">Current Thumbnail</label><br>
        <img src="<?= $webUploadDir . htmlspecialchars($row['thumbnail']) ?>" width="200" class="rounded border" />
      </div>
    <?php endif; ?>

    <!-- Upload New Thumbnail -->
    <div class="mb-3 form-group">
      <label for="thumbnail">Upload New Thumbnail (Optional)</label>
      <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="form-control" />
      <small class="form-text text-muted">Allowed: JPG, PNG, WebP – Max size: 5MB</small>
    </div>

    <!-- Uploaded Video Preview -->
    <?php if (!empty($row['main_video']) && strpos($row['main_video'], 'http') === false && file_exists($fsUploadDir . $row['main_video'])): ?>
      <div class="mb-3 form-group">
        <label class="form-label">Current Uploaded Video</label><br>
        <video width="320" controls class="rounded border">
          <source src="<?= $webUploadDir . htmlspecialchars($row['main_video']) ?>" type="<?= mimeFromExt($row['main_video']) ?>">
          Your browser does not support the video tag.
        </video>
      </div>
    <?php endif; ?>

    <!-- Video URL Preview -->
    <?php if (!empty($row['main_video']) && strpos($row['main_video'], 'http') === 0): ?>
      <div class="mb-3 form-group">
        <label class="form-label">Current Video URL Preview</label><br>
        <iframe width="320" height="180" class="rounded border"
                src="<?= htmlspecialchars(convertToEmbedUrl($row['main_video'])) ?>"
                frameborder="0" allowfullscreen></iframe>
      </div>
    <?php endif; ?>

    <!-- Upload New Video -->
    <div class="mb-4 form-group">
      <label for="main_video">Upload New Video (Optional)</label>
      <input type="file" name="main_video" id="main_video" accept="video/mp4,video/webm,video/ogg" class="form-control">
      <small class="form-text text-muted">Accepted: MP4, WebM, OGG – Max size: 100MB</small>
    </div>

    <!-- Or Video URL -->
<div class="mb-3 form-group">
  <label for="video_url">Or Enter Video URL (YouTube, Vimeo, etc) (Optional)</label>
  <input
    type="url"
    name="video_url"
    id="video_url"
    class="form-control"
    placeholder="https://www.youtube.com/watch?v=xyz123"
    value="<?= htmlspecialchars($row['video_url'] ?? '') ?>"
  />
  <small class="form-text text-muted">
    If provided, this will be used instead of an uploaded video.
  </small>
</div>


    <!-- Submit -->
    <div class="form-footer d-flex justify-content-end">
      <button type="submit" name="update" class="btn btn-success">Update Review</button>
      <a href="show_reviews.php" class="btn btn-secondary mx-2">Cancel</a>
    </div>
  </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
</body>
</html>
