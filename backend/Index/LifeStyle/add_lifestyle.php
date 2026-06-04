<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
include '../preloader.php';

$message = '';
$messageType = 'danger';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entryType = $_POST['entry_type'] ?? 'full';
    $title2 = $_POST['title2'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($entryType === 'full') {
        $title = $_POST['title'] ?? '';
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $imgName = $_FILES['main_image']['name'];
            $tmpName = $_FILES['main_image']['tmp_name'];
            $uploadDir = 'uploads/';
            $filePath = $uploadDir . uniqid('img_', true) . '_' . basename($imgName);

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($tmpName, $filePath)) {
                $stmt = $conn->prepare("INSERT INTO life_style (main_image, title, title2, description) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $filePath, $title, $title2, $description);
                $stmt->execute();
                $message = "Full lifestyle entry added.";
                $messageType = 'success';
            } else {
                $message = "Image upload failed.";
            }
        } else {
            $message = "Please select an image.";
        }

    } elseif ($entryType === 'quick') {
        // Use last full entry’s image and title
        $res = $conn->query("SELECT main_image, title FROM life_style ORDER BY id DESC LIMIT 1");
        $last = $res->fetch_assoc();

        if ($last) {
            $filePath = $last['main_image'];
            $title = $last['title'];

            $stmt = $conn->prepare("INSERT INTO life_style (main_image, title, title2, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $filePath, $title, $title2, $description);
            $stmt->execute();
            $message = "Quick lifestyle entry added.";
            $messageType = 'success';
        } else {
            $message = "No base entry found. Please first add a full entry.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Life Style</title>

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
                    <h1 class="h4 fw-bold text-dark mb-1">Life Style Management</h1>
                    <small class="text-muted">Add new lifestyle content to your site.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_lifestyle.php" class="text-decoration-none text-primary fw-semibold">Life Style</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Life Style</li>
                </ol>
            </nav>
        </div>
    </header>

    <!-- Alert Message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Title -->
    <div class="form-header">
        <h2 class="form-heading text-start">Add New Life Style Entry</h2>
        <p class="form-subheading text-start">Choose entry type and fill out the form accordingly.</p>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">

                <!-- Entry Type -->
                <div class="col">
                    <div class="form-group">
                        <label for="entry_type" class="form-label">Entry Type</label>
                        <select name="entry_type" id="entry_type" class="form-select" required onchange="toggleFields()">
                            <option value="full" <?= ($_POST['entry_type'] ?? '') === 'full' ? 'selected' : '' ?>>Full Entry</option>
                            <option value="quick" <?= ($_POST['entry_type'] ?? '') === 'quick' ? 'selected' : '' ?>>Quick Entry</option>
                        </select>
                    </div>
                </div>

                <!-- Main Image -->
                <div class="col full-entry">
                    <div class="form-group">
                        <label for="main_image" class="form-label">Main Image</label>
                        <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Supported formats: JPG, JPEG, PNG, GIF (Max 5MB).</small>
                    </div>
                </div>

                <!-- Title -->
                <div class="col full-entry">
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                    </div>
                </div>

                <!-- Title2 -->
                <div class="col">
                    <div class="form-group">
                        <label for="title2" class="form-label">Title 2</label>
                        <input type="text" id="title2" name="title2" class="form-control" placeholder="Enter secondary title" value="<?= htmlspecialchars($_POST['title2'] ?? '') ?>">
                    </div>
                </div>

                <!-- Description -->
                <div class="col">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Enter description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" class="btn-submit btn btn-primary">Add Life Style</button>
                <a href="show_lifestyle.php" class="btn btn-secondary">Cancel</a>
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

    document.addEventListener('DOMContentLoaded', function () {
        const alertElement = document.querySelector('.alert');
        if (alertElement && !alertElement.classList.contains('alert-danger')) {
            setTimeout(() => {
                const bootstrapAlert = bootstrap.Alert.getInstance(alertElement);
                if (bootstrapAlert) bootstrapAlert.close();
            }, 7000);
        }
        toggleFields();
    });

    function toggleFields() {
        const type = document.getElementById('entry_type').value;
        const fullFields = document.querySelectorAll('.full-entry');
        fullFields.forEach(field => {
            field.style.display = (type === 'full') ? 'block' : 'none';
            const input = field.querySelector('input');
            if (input) input.required = (type === 'full');
        });
    }
</script>
</body>
</html>
