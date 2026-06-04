<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
include '../preloader.php';

$id = $_GET['id'] ?? 0;
$message = '';
$messageType = 'danger';

// Fetch existing data
$result = $conn->query("SELECT * FROM life_style WHERE id=$id");
if (!$result || $result->num_rows === 0) {
    die("Invalid ID.");
}
$row = $result->fetch_assoc();
$currentImage = $row['main_image'];
$currentTitle = $row['title'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entryType = $_POST['entry_type'] ?? 'full';
    $title2 = $_POST['title2'] ?? '';
    $description = $_POST['description'] ?? '';
    $imagePath = $currentImage;
    $title = $currentTitle;

    if ($entryType === 'full') {
        $title = $_POST['title'] ?? '';

        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $imgName = $_FILES['main_image']['name'];
            $tmpName = $_FILES['main_image']['tmp_name'];
            $imagePath = $uploadDir . uniqid('img_', true) . '_' . basename($imgName);

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($tmpName, $imagePath)) {
                if (file_exists($currentImage)) unlink($currentImage);
            } else {
                $message = "Failed to upload new image.";
                $imagePath = $currentImage;
            }
        }
    }

    $stmt = $conn->prepare("UPDATE life_style SET main_image=?, title=?, title2=?, description=? WHERE id=?");
    $stmt->bind_param("ssssi", $imagePath, $title, $title2, $description, $id);
    if ($stmt->execute()) {
        header("Location: show_lifestyle.php?message=Updated successfully");
        exit();
    } else {
        $message = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Life Style</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
  <link rel="stylesheet" href="../../Includes/sidebar.css">
  <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>

<div class="main container py-4">
    <!-- Header Section -->
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-leaf fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Edit Life Style Entry</h1>
                    <small class="text-muted">Modify the selected lifestyle entry.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_lifestyle.php" class="text-decoration-none text-primary fw-semibold">Life Style</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Life Style</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">

                <!-- Entry Type -->
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Edit Type</label>
                        <select name="entry_type" id="entry_type" class="form-select" onchange="toggleFields()">
                            <option value="full">Full Edit</option>
                            <option value="quick">Quick Edit</option>
                        </select>
                    </div>
                </div>

                <!-- Image -->
                <div class="col full-entry">
                    <div class="form-group">
                        <label class="form-label">Main Image</label>
                        <input type="file" name="main_image" class="form-control">
                        <img src="<?= htmlspecialchars($currentImage) ?>" width="120" class="mt-2 rounded border">
                    </div>
                </div>

                <!-- Title -->
                <div class="col full-entry">
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($currentTitle) ?>">
                    </div>
                </div>

                <!-- Title 2 -->
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Title 2</label>
                        <input type="text" name="title2" class="form-control" value="<?= htmlspecialchars($row['title2']) ?>">
                    </div>
                </div>

                <!-- Description -->
                <div class="col">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($row['description']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="show_lifestyle.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    // Form Validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    })();

    // Toggle Edit Fields
    function toggleFields() {
        const entryType = document.getElementById('entry_type').value;
        const fullFields = document.querySelectorAll('.full-entry');
        fullFields.forEach(field => {
            field.style.display = entryType === 'full' ? 'block' : 'none';
        });
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', () => {
        toggleFields();
    });
</script>
</body>
</html>
