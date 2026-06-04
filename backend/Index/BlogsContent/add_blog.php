<?php
session_start();
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
    <title>Add Blog Content</title>

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
                <i class="fas fa-blog fa-2x text-info me-3 mt-1"></i> <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Add New Blog Post</h1>
                    <small class="text-muted">Create a new blog entry with images and content.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="view_blogs.php" class="text-decoration-none text-success fw-semibold">Blog Content</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Blog</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Add Blog Details</h2>
        <p class="form-subheading">Fill in the information below to create a new blog post. All fields are required unless specified.</p>
    </div>

    <?php
    if (isset($_GET['success_message'])) {
        echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_GET['success_message']) . '</div>';
    }
    if (isset($_GET['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error_message']) . '</div>';
    }
    ?>

    <div class="form-card">
        <form action="submit_blog.php" method="POST" enctype="multipart/form-data" novalidate>
            <div class="row d-flex flex-column gap-3">
                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="category_name">Category <span class="text-danger">*</span></label>
                        <input type="text" id="category_name" name="category_name" class="form-control" placeholder="e.g. Technology, Health, etc." required>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="main_image">Main Image <span class="text-danger">*</span></label>
                        <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" required>
                        <small class="form-text text-muted">Upload the primary image for your blog post (e.g., banner image).</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="description">Short Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                        <small class="form-text text-muted">A brief summary of your blog post.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="editor">Blog Content <span class="text-danger">*</span></label>
                        <textarea id="editor" name="blog_content" class="form-control" required></textarea>
                        <small class="form-text text-muted">The main content of your blog post.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="side_image">Side Image 1 (Optional)</label>
                        <input type="file" id="side_image" name="side_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">An additional image to accompany your blog post.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="side_image2">Side Image 2 (Optional)</label>
                        <input type="file" id="side_image2" name="side_image2" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Another optional image for your blog post.</small>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end">
                <button type="submit" name="submit" class="btn-submit">Submit Blog </button>
                <a href="view_blogs.php" class="btn btn-secondary ms-2">Back to</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    // Basic client-side form validation for Bootstrap
    (function () {
        'use strict';
        var form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
</script>
</body>
</html>