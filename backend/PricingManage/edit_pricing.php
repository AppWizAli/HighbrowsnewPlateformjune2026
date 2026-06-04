<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../Index/preloader.php';
include '../Database/config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: show_pricing.php");
    exit;
}

$query = $conn->prepare("SELECT * FROM pricing_manage WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("UPDATE pricing_manage SET title = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $title, $price, $description, $id);

    if ($stmt->execute()) {
        $message = "Pricing updated successfully!";
        $messageType = 'success';
        $data = ['title' => $title, 'price' => $price, 'description' => $description];
    } else {
        $message = "Error updating pricing: " . $conn->error;
        $messageType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Pricing Plan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../Index/HeroAreas/css/index.css">
    <link rel="stylesheet" href="../Includes/sidebar.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
</head>

<body>
<?php
$sidebarPath = '../Includes/sidebar.php';
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
                <i class="fas fa-pen-to-square fa-2x text-warning me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Edit Pricing Plan</h1>
                    <small class="text-muted">Update the details of your pricing plan.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_pricing.php" class="text-decoration-none text-success fw-semibold">Pricing</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Pricing Plan Details</h2>
        <p class="form-subheading">Modify the information below to update this pricing package.</p>
    </div>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" required
                               value="<?= htmlspecialchars($data['title']) ?>">
                        <div class="invalid-feedback">Please enter a pricing title.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="price" class="form-label">Price (USD) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" required
                               value="<?= htmlspecialchars($data['price']) ?>">
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control"><?= htmlspecialchars($data['description']) ?></textarea>
                        <div class="invalid-feedback">Please enter a description (optional).</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 d-flex justify-content-end gap-3">
                <button type="submit" class="btn-submit">Update Pricing</button>
                <a href="show_pricing.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script>
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
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
