<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
include '../../Database/config.php';

$id = $_GET['id'] ?? 0;
$result = $conn->query("SELECT * FROM about_section WHERE id=$id");
$row = $result->fetch_assoc();
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['main_image'])) {
    $uploadDir = 'uploads/';
    $imgName = $_FILES['main_image']['name'];
    $tmpName = $_FILES['main_image']['tmp_name'];
    $targetPath = $uploadDir . uniqid('img_', true) . '_' . basename($imgName);

    if (move_uploaded_file($tmpName, $targetPath)) {
        if (file_exists($row['main_image'])) unlink($row['main_image']);

        $stmt = $conn->prepare("UPDATE about_section SET main_image=? WHERE id=?");
        $stmt->bind_param("si", $targetPath, $id);
        $stmt->execute();
        $message = "Image updated successfully.";
        header("Location: show_about.php?message=" . urlencode($message));
        exit();
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
  <title>Edit About Image</title>
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
        <i class="bi bi-pencil-square fa-2x text-primary me-3 mt-1"></i>
        <div>
          <h1 class="h4 fw-bold text-dark mb-1">Edit About Image</h1>
          <small class="text-muted">Update the image for the About section.</small>
        </div>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
          <li class="breadcrumb-item"><a href="show_about.php" class="text-decoration-none text-primary fw-semibold">About Section</a></li>
          <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit</li>
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
    <h2 class="form-heading text-start">Update Image</h2>
    <p class="form-subheading text-start">Upload a new image to replace the existing one.</p>
  </div>

  <div class="form-card">
    <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
      <div class="row d-flex flex-column gap-3">
        <div class="col">
          <div class="form-group">
            <label for="main_image" class="form-label">Replace Image</label>
            <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" required />
            <div class="invalid-feedback">Please select a new image.</div>
            <small class="form-text text-muted">Upload a new image to replace the existing one.</small>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="form-label">Current Image Preview</label><br>
            <img src="<?= htmlspecialchars($row['main_image']) ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
          </div>
        </div>
      </div>

      <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
        <button type="submit" class="btn-submit">Update Image</button>
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
