<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
  include '../preloader.php';
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['main_image'])) {
    $uploadDir = 'uploads/';
    $imgName = $_FILES['main_image']['name'];
    $tmpName = $_FILES['main_image']['tmp_name'];
    $targetPath = $uploadDir . uniqid('img_', true) . '_' . basename($imgName);

    if (move_uploaded_file($tmpName, $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO about_section (main_image) VALUES (?)");
        $stmt->bind_param("s", $targetPath);
        $stmt->execute();
        $message = "Image uploaded successfully.";
        $messageType = "success";
    } else {
        $message = "Image upload failed.";
        $messageType = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add About Image</title>
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
        <i class="bi bi-file-earmark-image fa-2x text-primary me-3 mt-1"></i>
        <div>
          <h1 class="h4 fw-bold text-dark mb-1">About Section</h1>
          <small class="text-muted">Upload the main image for the About section.</small>
        </div>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
          <li class="breadcrumb-item"><a href="show_about.php" class="text-decoration-none text-primary fw-semibold">About Section</a></li>
          <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Image</li>
        </ol>
      </nav>
    </div>
  </header>

  <?php if (!empty($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show text-center" role="alert">
      <?= htmlspecialchars($message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="form-header">
    <h2 class="form-heading text-start">Add New Image</h2>
    <p class="form-subheading text-start">Select an image file for the About section.</p>
  </div>

  <div class="form-card">
    <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
      <div class="row d-flex flex-column gap-3">
        <div class="col">
          <div class="form-group">
            <label for="main_image" class="form-label">Main Image</label>
            <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" required />
            <div class="invalid-feedback">Please upload an image.</div>
            <small class="form-text text-muted">Allowed formats: JPG, PNG, WebP. Max size: 5MB.</small>
          </div>
        </div>
      </div>

      <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
        <button type="submit" class=" btn-submit">Add Image</button>
        <a href="show_about.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(form => {
      form.addEventListener('submit', event => {
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
