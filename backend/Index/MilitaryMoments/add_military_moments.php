<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
  include '../preloader.php';
$formData = ['title' => '', 'description' => ''];
$success_message = '';
$error_message = '';

// ✅ Check if there's already a record with title/description
$checkQuery = "SELECT COUNT(*) AS total FROM military_moment WHERE TRIM(title) != '' OR TRIM(description) != ''";
$checkResult = mysqli_query($conn, $checkQuery);
$row = mysqli_fetch_assoc($checkResult);
$hasExistingData = $row['total'] > 0;

// 🟢 FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $formData['title'] = $title;
    $formData['description'] = $description;

    if (!$hasExistingData && (empty($title) || empty($description))) {
        $error_message = "Title and Description are required.";
    } else {
        $uploadDir = __DIR__ . '/../../uploads/military_moments/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $mainImageName = null;
        $mainImage2Name = null;

        // Main Image
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['main_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($ext, $allowed)) {
                $error_message = "Main image must be JPG, PNG or GIF.";
            } else {
                $fileName = uniqid('mil_main_', true) . '.' . $ext;
                $target = $uploadDir . $fileName;
                if (!move_uploaded_file($file['tmp_name'], $target)) {
                    $error_message = "Failed to upload main image.";
                } else {
                    $mainImageName = $fileName;
                }
            }
        }

        // Second Image
        if (isset($_FILES['main_image2']) && $_FILES['main_image2']['error'] === UPLOAD_ERR_OK) {
            $file2 = $_FILES['main_image2'];
            $ext2 = strtolower(pathinfo($file2['name'], PATHINFO_EXTENSION));
            if (in_array($ext2, ['jpg', 'jpeg', 'png', 'gif'])) {
                $fileName2 = uniqid('mil_sec_', true) . '.' . $ext2;
                $target2 = $uploadDir . $fileName2;
                if (move_uploaded_file($file2['tmp_name'], $target2)) {
                    $mainImage2Name = $fileName2;
                }
            }
        }

        if (!$error_message && ($mainImageName || $mainImage2Name || !$hasExistingData)) {
            $stmt = $conn->prepare("INSERT INTO military_moment (main_image, main_image2, title, description) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $mainImageName, $mainImage2Name, $title, $description);
                if ($stmt->execute()) {
                    $success_message = "Military Moment Added Successfully!";
                    $formData = ['title' => '', 'description' => ''];
                } else {
                    $error_message = "Database insert failed: " . $stmt->error;
                    if ($mainImageName && file_exists($uploadDir . $mainImageName)) unlink($uploadDir . $mainImageName);
                    if ($mainImage2Name && file_exists($uploadDir . $mainImage2Name)) unlink($uploadDir . $mainImage2Name);
                }
                $stmt->close();
            } else {
                $error_message = "Database error: " . $conn->error;
            }
        } elseif (!$mainImageName && !$mainImage2Name) {
            $error_message = "At least one image is required.";
        }
    }
}
?>

<!-- HTML Starts Here -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Military Moment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
</head>
<body>

<?php
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
                    <h1 class="h4 fw-bold text-dark mb-1">Add New Military Moment</h1>
                    <small class="text-muted">Create a new entry for military moments with images and descriptions.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_military_moments.php" class="text-decoration-none text-success fw-semibold">Military Moments</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Moment</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show text-center"><?= htmlspecialchars($success_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center"><?= htmlspecialchars($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="title">Title <?= !$hasExistingData ? '<span class="text-danger">*</span>' : '' ?></label>
                        <input type="text" id="title" name="title" class="form-control"
                            placeholder="e.g. Graduation Ceremony 2024" <?= !$hasExistingData ? 'required' : '' ?>
                            value="<?= htmlspecialchars($formData['title']) ?>">
                        <div class="invalid-feedback">Please enter a title for the military moment.</div>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="description">Description <?= !$hasExistingData ? '<span class="text-danger">*</span>' : '' ?></label>
                        <textarea id="description" name="description" class="form-control" rows="3" <?= !$hasExistingData ? 'required' : '' ?>><?= htmlspecialchars($formData['description']) ?></textarea>
                        <small class="form-text text-muted">A short description of this military moment.</small>
                        <div class="invalid-feedback">Please enter a description for the military moment.</div>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="main_image">Main Image <?= !$hasExistingData ? '<span class="text-danger">*</span>' : '' ?></label>
                        <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" <?= !$hasExistingData ? 'required' : '' ?>>
                        <small class="form-text text-muted">Upload the primary image for this moment. (JPG, JPEG, PNG, GIF)</small>
                        <div class="invalid-feedback">Please select a main image for the military moment.</div>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="main_image2">Second Image (Optional)</label>
                        <input type="file" id="main_image2" name="main_image2" class="form-control" accept="image/*">
                        <small class="form-text text-muted">An optional second image for this moment.</small>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 d-flex justify-content-end gap-3">
                <button type="submit" name="submit" class="btn btn-success px-4">Add Moment</button>
                <a href="show_military_moments.php" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
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
