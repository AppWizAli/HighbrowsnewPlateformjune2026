<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
include '../../Database/config.php';
$success = ''; // ✅ Initialize to prevent warning

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Blog ID.";
    exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM blogs_content WHERE id = $id");
if ($result->num_rows !== 1) {
    echo "Blog not found.";
    exit();
}

$row = $result->fetch_assoc();
$uploadDir = "../../uploads/blogs/";

// Handle update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $content = $_POST['blog_content'] ?? '';
    $category = $_POST['category_name'] ?? '';

    // Keep existing images unless replaced
    $main_image = $row['main_image'];
    $side_image = $row['side_image'];
    $side_image2 = $row['side_image2'];

    $fields = ['main_image', 'side_image', 'side_image2'];
    foreach ($fields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $fileName = time() . "_" . basename($_FILES[$field]['name']);
            $targetFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetFilePath)) {
                if (!empty($row[$field]) && file_exists($uploadDir . $row[$field])) {
                    unlink($uploadDir . $row[$field]); // Delete old image
                }
                $$field = $fileName; // Update variable
            }
        }
    }

    // Update database
    $stmt = $conn->prepare("UPDATE blogs_content SET
        title = ?, description = ?, blog_content = ?, category_name = ?,
        main_image = ?, side_image = ?, side_image2 = ?
        WHERE id = ?");
    $stmt->bind_param("sssssssi", $title, $description, $content, $category, $main_image, $side_image, $side_image2, $id);

    if ($stmt->execute()) {
        $success = "Blog updated successfully!"; // ✅ Show success instead of redirect
    header("Location: view_blogs.php?message=Blog+updated&type=success");

    } else {
        $success = "Update failed: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Blog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    
</head>
<body>
    <?php include '../../Includes/sidebar.php'; ?>

    <div class="main">
        <header class="top-header mb-4 enhanced-shadow">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-blog fa-2x text-info me-3 mt-1"></i>
                    <div>
                        <h1 class="h4 fw-bold text-dark mb-1">Edit Blog Post</h1>
                        <small class="text-muted">Update details for blog entry ID: <?= htmlspecialchars($id) ?>.</small>
                    </div>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                        <li class="breadcrumb-item"><a href="view_blogs.php" class="text-decoration-none text-success fw-semibold">Blog Content</a></li>
                        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Blog</li>
                    </ol>
                </nav>
            </div>
        </header>

        <div class="form-header">
            <h2 class="form-heading">Update Blog Details</h2>
            <p class="form-subheading">Modify the information below to update this blog post. All fields are required unless specified.</p>
        </div>

       <?php if ($success): ?>
    <div class="alert <?= strpos($success, 'failed') !== false ? 'alert-danger' : 'alert-success' ?>">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>


        <div class="form-card">
            <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="main_image">Main Image:</label><br>
                            <?php if (!empty($row['main_image'])): ?>
                                <img src="../../uploads/blogs/<?= htmlspecialchars($row['main_image']) ?>" class="img-fluid mb-2" alt="Main Image">
                            <?php else: ?>
                                <p class="text-muted">No main image uploaded.</p>
                            <?php endif; ?>
                            <input type="file" id="main_image" name="main_image" accept="image/*" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="side_image">Side Image 1:</label><br>
                            <?php if (!empty($row['side_image'])): ?>
                                <img src="../../uploads/blogs/<?= htmlspecialchars($row['side_image']) ?>" class="img-fluid mb-2" alt="Side Image 1">
                            <?php else: ?>
                                <p class="text-muted">No side image 1 uploaded.</p>
                            <?php endif; ?>
                            <input type="file" id="side_image" name="side_image" accept="image/*" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="side_image2">Side Image 2:</label><br>
                            <?php if (!empty($row['side_image2'])): ?>
                                <img src="../../uploads/blogs/<?= htmlspecialchars($row['side_image2']) ?>" class="img-fluid mb-2" alt="Side Image 2">
                            <?php else: ?>
                                <p class="text-muted">No side image 2 uploaded.</p>
                            <?php endif; ?>
                            <input type="file" id="side_image2" name="side_image2" accept="image/*" class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required />
                    <div class="invalid-feedback">Please enter the blog title.</div>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($row['description']) ?></textarea>
                    <div class="invalid-feedback">Please enter the description.</div>
                </div>

                <div class="form-group">
                    <label for="editor">Content:</label>
                    <div class="ckeditor-container">
                        <textarea id="editor" name="blog_content" class="form-control" required><?= htmlspecialchars($row['blog_content']) ?></textarea>
                    </div>
                    <div class="invalid-feedback">Please enter the blog content.</div>
                </div>

                <div class="form-group">
                    <label for="category_name">Category:</label>
                    <input type="text" id="category_name" name="category_name" class="form-control" value="<?= htmlspecialchars($row['category_name']) ?>" required />
                    <div class="invalid-feedback">Please enter a category.</div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Update Blog</button>
                    <a href="/Highbrows/backend/Index/BlogsContent/view_blogs.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        CKEDITOR.replace('editor');

        // Bootstrap validation example
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Includes/sidebar.js"></script>
</body>
</html>