<?php
session_start();
// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Hero Area Images</title>

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
                <i class="fas fa-images fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Hero Area Image Management</h1>
                    <small class="text-muted">Upload and manage hero section image and video for your website.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_heroareas.php" class="text-decoration-none text-success fw-semibold">Hero Areas</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Media</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header">
        <h2 class="form-heading text-start">Add Hero Area Media</h2>
        <p class="form-subheading text-start">
            Upload one image or one video for your website's hero section. Either or both are allowed.
        </p>
    </div>

   <form action="../../Index/HeroAreas/submit_heroareas.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
  <div class="row d-flex flex-column gap-3">
      
    <!-- ✅ Multiple Image Upload -->
    <div class="col">
      <div class="form-group">
        <label for="main_image" class="form-label">Main Images</label>
        <input type="file" id="main_image" name="main_image[]" class="form-control" accept="image/*" multiple />
        <div class="invalid-feedback">Please select at least one image.</div>
        <small class="form-text text-muted">You can upload multiple images. Allowed: JPG, JPEG, PNG, GIF.</small>
      </div>
    </div>

    <!-- ✅ Multiple Video Upload -->
    <div class="col">
      <div class="form-group">
        <label for="video_content" class="form-label">Hero Videos</label>
        <input type="file" id="video_content" name="video_content[]" class="form-control" accept="video/mp4,video/webm,video/ogg" multiple />
        <div class="invalid-feedback">Please select at least one video.</div>
        <small class="form-text text-muted">You can upload multiple videos. Allowed: MP4, WEBM, OGG.</small>
      </div>
    </div>

  </div>

  <div class="form-footer mt-4">
    <button type="submit" name="submit" class="btn-submit">Upload Media</button>
    <a href="show_heroareas.php" class="btn btn-secondary">Back</a>
  </div>
</form>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    // Bootstrap form validation script
    (function () {
        'use strict';
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation');
        // Loop over them and prevent submission
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