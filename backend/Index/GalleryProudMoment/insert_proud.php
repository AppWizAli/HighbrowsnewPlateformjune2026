<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
  include '../preloader.php';
$uploadDir = __DIR__ . '/uploads/proud_moments/';

// Ensure the directory exists and is writable
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
if (!is_writable($uploadDir)) {

    error_log("Upload directory is not writable: " . $uploadDir);
    $message = "Server error: Upload directory is not writable. Please check permissions.";
}


$message = ''; // Initialize message to an empty string
$messageType = ''; // To control alert class (success/danger/info)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title'] ?? ''); // Use null coalescing operator for safety
    $description = trim($_POST['description'] ?? '');
    $file = $_FILES['main_image'] ?? null; // Use null coalescing for safety

    // Basic validation for title and description
    if (empty($title) || empty($description)) {
        $message = "Title and Description cannot be empty.";
        $messageType = "danger";
    } elseif (empty($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $message = "Please choose an image.";
        $messageType = "danger";
    } elseif (!empty($file['name'])) {
        // Validate file type and size
        $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts)) {
            $message = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.";
            $messageType = "danger";
        } elseif ($file['size'] > $maxFileSize) {
            $message = "File is too large. Maximum allowed size is 5MB.";
            $messageType = "danger";
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            // Handle various upload errors
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $message = "Uploaded file exceeds maximum file size.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = "File was only partially uploaded.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = "Missing a temporary folder on the server.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = "Failed to write file to disk. Check server permissions.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $message = "A PHP extension stopped the file upload.";
                    break;
                default:
                    $message = "Unknown upload error.";
            }
            $messageType = "danger";
        } else {
            $fileName = uniqid('proud_', true) . '.' . $ext;
            $target = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Insert data into database
                $stmt = $conn->prepare("INSERT INTO proud_moment (main_image, title, description) VALUES (?, ?, ?)");
                if (!$stmt) {
                    error_log("Prepare failed: " . $conn->error);
                    $message = "Database error: Could not prepare statement. Contact support.";
                    $messageType = "danger";
                    if (file_exists($target)) { // Delete uploaded file if DB preparation fails
                        unlink($target);
                    }
                } else {
                    $stmt->bind_param("sss", $fileName, $title, $description);
                    if ($stmt->execute()) {
                        $message = "Proud moment added successfully!";
                        $messageType = "success";
                        // Clear form fields after successful submission
                        $_POST = [];
                    } else {
                        error_log("Database error inserting proud moment: " . $stmt->error);
                        $message = "An error occurred while saving to the database. Please try again.";
                        $messageType = "danger";
                        // If DB insert fails, delete the uploaded file to avoid orphaned files
                        if (file_exists($target)) {
                            unlink($target);
                        }
                    }
                    $stmt->close();
                }
            } else {
                $message = "Image upload failed. Check server permissions for: " . htmlspecialchars($uploadDir);
                $messageType = "danger";
            }
        }
    }
}
$conn->close(); // Close connection after all database operations
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Proud Moment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">

    
</head>

<body>

    <?php include '../../Includes/sidebar.php'; ?>

    <div class="main">
        <header class="top-header mb-4 enhanced-shadow">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-trophy fa-2x text-info me-3 mt-1"></i> <div>
                        <h1 class="h4 fw-bold text-dark mb-1">Add New Proud Moment</h1>
                        <small class="text-muted">Showcase a new achievement or special moment.</small>
                    </div>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                        <li class="breadcrumb-item"><a href="show_proud.php" class="text-decoration-none text-success fw-semibold">Proud Moments</a></li>
                        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add New</li>
                    </ol>
                </nav>
            </div>
        </header>

        <div class="form-header text-start">
            <h2 class="form-heading">Proud Moment Details</h2>
            <p class="form-subheading">Fill in the information below to add a new proud moment. All fields are required.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($messageType) ?> text-center">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="main_image">Main Image <span class="text-danger">*</span></label>
                    <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Allowed formats: JPG, JPEG, PNG, GIF. Max size: 5MB.</small>
                    <div class="invalid-feedback">Please choose an image for the proud moment.</div>
                </div>

                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" required
                        value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                    <div class="invalid-feedback">Please enter a title for the proud moment.</div>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="3" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                    <div class="invalid-feedback">Please enter a description for the proud moment.</div>
                </div>

                <div class="form-footer mt-4">
                    <button type="submit" name="submit" class="btn-submit">Add Gallery</button>
                    <a href="show_proud.php" class="btn btn-secondary ms-2">Back to </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Includes/sidebar.js"></script>
    <script>
        // Bootstrap validation script
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
</body>
</html>