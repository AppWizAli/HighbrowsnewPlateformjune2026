<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../Database/config.php';
include '../Index/preloader.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: show_explore2.php'); // ✅ Corrected
    exit();
}

// ✅ Use correct table name
$query = "SELECT * FROM explore_highbrows2 WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if (!$row = $result->fetch_assoc()) {
    header('Location: show_explore2.php');
    exit();
}

$error = '';
$success = '';

if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $description2 = trim($_POST['description2']);

    $main_image = $row['main_image'];
    $main_image2 = $row['main_image2'];

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $main_image = uniqid('img1_', true) . '.' . pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir . $main_image);
    }

    if (isset($_FILES['main_image2']) && $_FILES['main_image2']['error'] === UPLOAD_ERR_OK) {
        $main_image2 = uniqid('img2_', true) . '.' . pathinfo($_FILES['main_image2']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['main_image2']['tmp_name'], $uploadDir . $main_image2);
    }

    // ✅ Use correct table name
    $stmt = $conn->prepare("UPDATE explore_highbrows2 SET main_image=?, main_image2=?, title=?, description=?, description2=? WHERE id=?");
    $stmt->bind_param("sssssi", $main_image, $main_image2, $title, $description, $description2, $id);
    if ($stmt->execute()) {
        header("Location: show_explore2.php?message=" . urlencode("Record updated successfully.")); // ✅ Correct redirect
        exit();
    } else {
        $error = "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Explore Section</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../Includes/sidebar.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/index.css"> 
    
    <style>
        .form-card {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
         
            margin: 0 auto;
        }
    </style>
</head>
<body>
<?php include '../Includes/sidebar.php'; ?>
<div class="main">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-edit fa-2x text-warning me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Edit Explore Content</h1>
                    <small class="text-muted">Update the details of the selected Explore section entry.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_explore.php" class="text-decoration-none text-success fw-semibold">Explore Content</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Explore</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Edit Explore Details</h2>
        <p class="form-subheading">Update the fields below to modify this Explore content. Images are optional to change.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data">
            <div class="row d-flex flex-column gap-3">

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($row['title']) ?>" >
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" rows="3" ><?= htmlspecialchars($row['description']) ?></textarea>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label for="description2">Additional Description</label>
                        <textarea class="form-control" name="description2" rows="3"><?= htmlspecialchars($row['description2']) ?></textarea>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label>Main Image (Current)</label><br>
                        <img src="uploads/<?= htmlspecialchars($row['main_image']) ?>" width="120" class="img-thumbnail mb-2">
                        <input type="file" name="main_image" class="form-control">
                        <small class="form-text text-muted">Leave empty to keep existing image.</small>
                    </div>
                </div>

                <div class="col col-sm-12">
                    <div class="form-group">
                        <label>Main Image 2 (Current)</label><br>
                        <img src="uploads/<?= htmlspecialchars($row['main_image2']) ?>" width="120" class="img-thumbnail mb-2">
                        <input type="file" name="main_image2" class="form-control">
                        <small class="form-text text-muted">Leave empty to keep existing image.</small>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end">
                <button type="submit" name="update" class="btn-submit">Update Explore</button>
                <a href="show_explore2.php" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script>
</body>
</html>
