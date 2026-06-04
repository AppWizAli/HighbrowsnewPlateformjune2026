<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
include '../../Database/config.php';

$message = '';
$messageType = '';

if (isset($_POST['submit'])) {

    if (!isset($conn) || $conn->connect_error) {
        error_log("Database connection failed in add_academicfaqs.php (submit handler): " . ($conn->connect_error ?? 'Connection object not found'));
        $message = "ERROR: Database connection failed. Please try again later or contact support.";
        $messageType = "danger";
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $formErrors = [];

        if (empty($title)) {
            $formErrors[] = "FAQ title is required.";
        }
        if (empty($description)) {
            $formErrors[] = "FAQ description is required.";
        }

        if (!empty($formErrors)) {
            $message = "Error(s) during FAQ submission:<br>" . implode("<br>", $formErrors);
            $messageType = "danger";
        } else {
            // Using backticks for the table name due to the apostrophe
            $insertQuery = "INSERT INTO `academic_faqs` (title, description) VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt === false) {
                error_log("Database prepare failed (insert): " . $conn->error);
                $message = "An internal database error occurred while preparing to add the FAQ.";
                $messageType = "danger";
            } else {
                $stmt->bind_param("ss", $title, $description);

                if ($stmt->execute()) {
                    $message = "FAQ added successfully!";
                    $messageType = "success";
                    $_POST = array(); // Clear form fields after successful submission
                } else {
                    error_log("Database execute failed (insert): " . $stmt->error);
                    $message = "Failed to add FAQ: " . $stmt->error;
                    $messageType = "danger";
                }
                $stmt->close();
            }
        }
    }
   
}

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
    <title>Add Academic FAQ</title>

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
                <i class="fas fa-question-circle fa-2x text-info me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Academic FAQs Management</h1>
                    <small class="text-muted">Add new frequently asked questions related to academics.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-info fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_academicfaqs.php" class="text-decoration-none text-info fw-semibold">Academic FAQs</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add FAQ</li>
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
        <h2 class="form-heading text-start">Add New Academic FAQ</h2>
        <p class="form-subheading text-start">Enter the question and answer for the new FAQ entry.</p>
    </div>

    <div class="form-card">
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">
                <div class="col">
                    <div class="form-group">
                        <label for="title" class="form-label">Title (Question)</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter FAQ question" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" />
                        <div class="invalid-feedback">Please enter a title for the FAQ.</div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="description" class="form-label">Description (Answer)</label>
                        <textarea id="description" name="description" class="form-control" rows="5" placeholder="Enter FAQ answer" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback">Please enter a description for the FAQ.</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" name="submit" class="btn-submit">Add FAQ</button>
                <a href="show_academicfaqs.php" class="btn btn-secondary">Cancel</a>
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