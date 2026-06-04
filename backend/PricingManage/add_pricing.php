<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}




include '../Index/preloader.php';
include '../Database/config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($price)) {
        $message = "Title and Price are required.";
        $messageType = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO pricing_manage (title, price, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $title, $price, $description);
        if ($stmt->execute()) {
            $message = "Pricing plan added successfully.";
            $messageType = "success";
        } else {
            $message = "Error adding pricing plan.";
            $messageType = "danger";
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
    <title>Add Pricing Plan</title>

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
                <i class="fas fa-money-bill-wave fa-2x text-primary me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Add Pricing Plan</h1>
                    <small class="text-muted">Define pricing for your services or products.</small>
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
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Pricing Plan Details</h2>
        <p class="form-subheading">Fill in the form below to add a new pricing package.</p>
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
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Basic Plan" required
                               value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                        <div class="invalid-feedback">Please enter a pricing title.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="price" class="form-label">Price (pkr) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="e.g. 49.99 rs" required
                               value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>">
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        <div class="invalid-feedback">Please enter a description (optional).</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 d-flex justify-content-end gap-3">
                <button type="submit" class="btn-submit">Add Pricing</button>
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
