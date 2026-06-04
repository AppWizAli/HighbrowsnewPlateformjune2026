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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $errors = [];

    // Upload directory
    $uploadDir = dirname(__DIR__, 2) . '/uploads/cadet_moments/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!is_writable($uploadDir)) {
        $errors[] = "Upload directory is not writable.";
    }

    $mainImageName = '';
    $mainImage2Name = '';
    $maxFileSize = 5 * 1024 * 1024;
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    $uploadErrors = [];

    function handleUpload($input, $dir, $maxSize, $exts, &$errs, $required = false) {
        if (!isset($_FILES[$input]) || $_FILES[$input]['error'] === UPLOAD_ERR_NO_FILE) {
            if ($required) $errs[] = "$input is required.";
            return '';
        }

        $file = $_FILES[$input];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = uniqid($input . '_', true) . '.' . $ext;
        $path = $dir . $name;

        if (!in_array($ext, $exts)) $errs[] = "$input must be jpg, jpeg, png or gif.";
        if ($file['size'] > $maxSize) $errs[] = "$input is too large.";

        if (empty($errs)) {
            if (!move_uploaded_file($file['tmp_name'], $path)) {
                $errs[] = "Failed to upload $input.";
            }
        }
        return empty($errs) ? $name : '';
    }

    // Upload images (not required)
    $mainImageName = handleUpload('main_image', $uploadDir, $maxFileSize, $allowedExts, $uploadErrors, false);
    $mainImage2Name = handleUpload('main_image2', $uploadDir, $maxFileSize, $allowedExts, $uploadErrors, false);

    // Determine the type of entry
    $type = (!empty($title) && !empty($description)) ? 'full' : 'image_only';

    if ($type === 'full' && (empty($mainImageName) && empty($mainImage2Name))) {
        $errors[] = "At least one image is required for a full entry.";
    }

    if ($type === 'image_only' && empty($mainImageName) && empty($mainImage2Name)) {
        $errors[] = "At least one image is required.";
    }

    if (!empty($uploadErrors)) {
        $errors = array_merge($errors, $uploadErrors);
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO cadet_moments (main_image, main_image2, title, description, type) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $mainImageName, $mainImage2Name, $title, $description, $type);
            if ($stmt->execute()) {
                $message = "Cadet moment added successfully.";
                header("Location: view_cadet_moments.php?success=" . urlencode($message));
                exit();
            } else {
                $message = "Error executing query: " . $stmt->error;
                $messageType = 'danger';
            }
            $stmt->close();
        } else {
            $message = "Prepare failed: " . $conn->error;
            $messageType = 'danger';
        }
    } else {
        $message = implode("<br>", $errors);
        $messageType = 'danger';
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Cadet Moment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">

</head>
<body>

<?php
// Safely include sidebar if file exists
$sidebarPath = '../../Includes/sidebar.php';
if (file_exists($sidebarPath)) {
    include $sidebarPath;
} else {
    echo "<p class='text-danger p-3'>Sidebar file not found: $sidebarPath</p>";
}
?>

<div class="main container py-4">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-camera-retro fa-2x text-info me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Add New Cadet Moment</h1>
                    <small class="text-muted">Create a new entry for cadet moments with images and descriptions.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="view_cadet_moments.php" class="text-decoration-none text-success fw-semibold">Cadet Moments</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Moment</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Add Moment Details</h2>
        <p class="form-subheading">Fill in the information below to create a new cadet moment. All fields are required unless specified.</p>
    </div>

    <?php
    if (!empty($message)) { // Display message if set
        echo '<div class="alert alert-' . $messageType . ' alert-dismissible fade show text-center" role="alert">' . $message . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>

    <div class="form-card">
       <!-- Remove required attributes from title, description, and main_image -->
<form action="add_cadet_moment.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="row d-flex flex-column gap-3">

        <!-- Title (Optional Now) -->
        <div class="col col-sm-12">
            <div class="form-group mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control"
                       placeholder="e.g. Graduation Ceremony 2024"
                       value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                <div class="invalid-feedback">Please provide a title.</div>
            </div>
        </div>

        <!-- Description (Optional Now) -->
        <div class="col col-sm-12">
            <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                <small class="form-text text-muted">A short description of this cadet moment.</small>
                <div class="invalid-feedback">Please provide a description.</div>
            </div>
        </div>

        <!-- Main Image (Now Optional) -->
        <div class="col col-sm-12">
            <div class="form-group mb-3">
                <label for="main_image" class="form-label">Main Image</label>
                <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*">
                <small class="form-text text-muted">Upload the primary image for this moment (Max 5MB, JPG, PNG, GIF).</small>
                <div class="invalid-feedback">Please select a main image.</div>
            </div>
        </div>

        <!-- Optional Second Image -->
        <div class="col col-sm-12">
            <div class="form-group mb-3">
                <label for="main_image2" class="form-label">Second Image (Optional)</label>
                <input type="file" id="main_image2" name="main_image2" class="form-control" accept="image/*">
                <small class="form-text text-muted">An optional second image for this moment (Max 5MB, JPG, PNG, GIF).</small>
                <div class="invalid-feedback">Please select a valid second image file.</div>
            </div>
        </div>
    </div>

    <!-- Submit & Back -->
    <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
        <button type="submit" name="submit" class="btn-submit">Add Moment</button>
        <a href="view_cadet_moments.php" class="btn btn-secondary">Back to List</a>
    </div>
</form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    // Bootstrap form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

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