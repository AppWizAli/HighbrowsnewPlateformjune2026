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
$row = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($conn) || $conn->connect_error) {
    $message = "Database connection failed.";
    $messageType = "danger";
} elseif ($id <= 0) {
    header('Location: show_military_moments.php?error=Invalid+ID');
    exit();
} else {
    $stmt = $conn->prepare("SELECT * FROM military_moment WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        header('Location: show_military_moments.php?error=Data+not+found');
        exit();
    }
}

$uploadDir = __DIR__ . '/../../uploads/military_moments/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']) && $row) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $mainImageName = $row['main_image'];
    $mainImage2Name = $row['main_image2'];

    // Main Image
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('milmain_', true) . '.' . $ext;
        if (move_uploaded_file($_FILES['main_image']['tmp_name'], $uploadDir . $fileName)) {
            if (!empty($mainImageName) && file_exists($uploadDir . $mainImageName)) unlink($uploadDir . $mainImageName);
            $mainImageName = $fileName;
        }
    }

    // Second Image
    if (isset($_FILES['main_image2']) && $_FILES['main_image2']['error'] === UPLOAD_ERR_OK) {
        $ext2 = strtolower(pathinfo($_FILES['main_image2']['name'], PATHINFO_EXTENSION));
        $fileName2 = uniqid('milsec_', true) . '.' . $ext2;
        if (move_uploaded_file($_FILES['main_image2']['tmp_name'], $uploadDir . $fileName2)) {
            if (!empty($mainImage2Name) && file_exists($uploadDir . $mainImage2Name)) unlink($uploadDir . $mainImage2Name);
            $mainImage2Name = $fileName2;
        }
    }

    // Update DB
    $stmt = $conn->prepare("UPDATE military_moment SET main_image=?, main_image2=?, title=?, description=? WHERE id=?");
    $stmt->bind_param("ssssi", $mainImageName, $mainImage2Name, $title, $description, $id);
    if ($stmt->execute()) {
        header('Location: show_military_moments.php?success=Military+moment+updated+successfully');
        exit();
    } else {
        $message = "Update failed. Please try again.";
        $messageType = "danger";
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
    <title>Edit Military Moment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
    <style>
        .img-preview {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<?php
$sidebarPath = '../../Includes/sidebar.php';
if (file_exists($sidebarPath)) {
    include $sidebarPath;
} else {
    echo "<p class='text-danger p-3'>Sidebar file not found: $sidebarPath</p>";
}
?>

<div class="main container py-4">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-edit fa-2x text-info me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Edit Military Moment</h1>
                    <small class="text-muted">Update details of your military moment.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_military_moments.php" class="text-decoration-none text-success fw-semibold">Military Moments</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Moment</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($row !== null && $messageType !== 'danger') : ?>
        <div class="form-card">
          <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

    <div class="row d-flex flex-column gap-3">
        <div class="col-sm-12">
            <div class="form-group mb-3">
                <label for="main_image" class="form-label">Main Image</label><br>
                <?php if (!empty($row['main_image'])) : ?>
                    <div class="mb-2">
                        <img src="../../uploads/military_moments/<?= htmlspecialchars($row['main_image']) ?>" class="img-preview rounded" alt="Current Main Image">
                    </div>
                <?php else : ?>
                    <p class="text-muted">No main image uploaded for this moment.</p>
                <?php endif; ?>
                <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*" />
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group mb-3">
                <label for="main_image2" class="form-label">Second Image (Optional)</label><br>
                <?php if (!empty($row['main_image2'])) : ?>
                    <div class="mb-2">
                        <img src="../../uploads/military_moments/<?= htmlspecialchars($row['main_image2']) ?>" class="img-preview rounded" alt="Current Second Image">
                    </div>
                <?php else : ?>
                    <p class="text-muted">No second image uploaded for this moment.</p>
                <?php endif; ?>
                <input type="file" name="main_image2" id="main_image2" class="form-control" accept="image/*" />
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control"><?= htmlspecialchars($row['description']) ?></textarea>
            </div>
        </div>
    </div>

    <div class="form-footer mt-4 d-flex justify-content-end gap-3">
        <button type="submit" name="update" class="btn-submit">Update</button>
        <a href="show_military_moments.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
</body>
</html>
