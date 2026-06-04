<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../Index/preloader.php';
include '../Database/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $description2 = trim($_POST['description2'] ?? '');

    $main_image = $_FILES['main_image'] ?? null;
    $main_image2 = $_FILES['main_image2'] ?? null;

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    function uploadImage($file, $uploadDir) {
        if (!empty($file) && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = uniqid('explore_', true) . "." . $ext;
            $dest = $uploadDir . $newName;
            return move_uploaded_file($file['tmp_name'], $dest) ? $newName : null;
        }
        return null;
    }

    $mainImageName = uploadImage($main_image, $uploadDir);
    $mainImage2Name = uploadImage($main_image2, $uploadDir);

    // ✅ If all fields are empty, show error
    if (empty($title) && empty($description) && empty($description2) && !$mainImageName && !$mainImage2Name) {
        $error = "Please provide at least one field (image or text).";
    } else {
        $stmt = $conn->prepare("INSERT INTO explore_highbrows2 (main_image, main_image2, title, description, description2) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $mainImageName, $mainImage2Name, $title, $description, $description2);
        if ($stmt->execute()) {
            header("Location: show_explore2.php?success_message=" . urlencode("Explore content added successfully."));
            exit();
        } else {
            $error = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Explore Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Includes/sidebar.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/index.css"> 
</head>
<body>
<?php include '../Includes/sidebar.php'; ?>
<div class="main">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-compass fa-2x text-info me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Add Explore Content</h1>
                    <small class="text-muted">Create a new entry for the Explore Highbrows section.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_explore2.php" class="text-decoration-none text-success fw-semibold">Explore Content</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Explore</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Add Explore Details</h2>
        <p class="form-subheading">Fill in the information below to create a new Explore section entry. All fields are required unless marked optional.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data">
            <div class="row d-flex flex-column gap-3">

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="main_image">Main Image</label>
                        <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">This will be the primary visual for your explore card.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="main_image2">Second Image</label>
                        <input type="file" name="main_image2" id="main_image2" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Secondary image to appear alongside or below the main one.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        <small class="form-text text-muted">Provide a short summary or introduction.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="description2">Additional Description (Optional)</label>
                        <textarea name="description2" id="description2" class="form-control" rows="3"></textarea>
                        <small class="form-text text-muted">You may add a more detailed explanation or second paragraph.</small>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end">
                <button type="submit" name="submit" class="btn-submit">Submit Explore</button>
                <a href="show_explore2.php" class="btn btn-secondary ms-2">Back to List</a>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script>
</body>
</html>
