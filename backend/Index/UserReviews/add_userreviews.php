<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
  include '../preloader.php';
$message = '';
$messageType = '';

if (isset($_POST['submit'])) {
    if (!isset($conn) || $conn->connect_error) {
        error_log("DB connection failed: " . ($conn->connect_error ?? 'No conn object'));
        $message = "ERROR: DB connection failed.";
        $messageType = "danger";
    } else {
        // Server path to save images
           $uploadDir = __DIR__ . '/../../uploads/user_reviews/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Allowed types and size
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 5 * 1024 * 1024;

        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        $errors = [];

        if (empty($title)) $errors[] = "Title is required.";
        if (empty($description)) $errors[] = "Description is required.";

        $mainImage = '';

        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['main_image']['tmp_name'];
            $fileName = basename($_FILES['main_image']['name']);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExts)) {
                $errors[] = "Only JPG, PNG, and GIF files are allowed.";
            } elseif ($_FILES['main_image']['size'] > $maxSize) {
                $errors[] = "File size exceeds 5MB limit.";
            } else {
                $newFileName = uniqid('review_', true) . '.' . $ext;
                $targetPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $mainImage = $newFileName; // ✅ Save only filename in DB
                } else {
                    $errors[] = "Failed to upload image.";
                }
            }
        } else {
            $errors[] = "Please upload an image.";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO user_reviews (main_image, title, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $mainImage, $title, $description);

            if ($stmt->execute()) {
                $message = "User Review added successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to insert review: " . $stmt->error;
                $messageType = "danger";
                error_log("Insert failed: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $message = implode("<br>", $errors);
            $messageType = "danger";
        }
        $conn->close();
    }
}

// Display alert if redirected with message
if (empty($message) && isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
    $messageType = htmlspecialchars(urldecode($_GET['type']));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add User Review</title>


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
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-star fa-2x text-primary me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">User Reviews Management</h1>
                    <small class="text-muted">Add new customer reviews for your website.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_userreviews.php" class="text-decoration-none text-primary fw-semibold">User Reviews</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Review</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php
    if (!empty($message)) {
        echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show text-center" role="alert">' . htmlspecialchars($message) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>

    <div class="form-header">
        <h2 class="form-heading text-start">Add New User Review</h2>
        <p class="form-subheading text-start">Enter the details for the new user review, including an image, title, and description.</p>
    </div>

    <div class="form-card">
        <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">
                <div class="col">
                    <div class="form-group">
                        <label for="main_image" class="form-label">Main Image</label>
                        <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" required />
                        <div class="invalid-feedback">Please select an image for the review.</div>
                        <small class="form-text text-muted">Select the main image for the review. Allowed formats: JPG, JPEG, PNG, GIF (Max 5MB).</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter review title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" />
                        <div class="invalid-feedback">Please enter a title for the review.</div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Enter review description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback">Please enter a description for the review.</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" name="submit" class="btn-submit">Add Review</button>
                <a href="show_userreviews.php" class="btn btn-secondary">Cancel</a>
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

    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            // Dismiss success/warning alerts automatically after 7 seconds
            if (!alertElement.classList.contains('alert-danger')) {
                setTimeout(() => {
                    const bootstrapAlert = bootstrap.Alert.getInstance(alertElement);
                    if (bootstrapAlert) {
                        bootstrapAlert.close();
                    }
                }, 7000);
            }
        }
    });
</script>
</body>
</html>