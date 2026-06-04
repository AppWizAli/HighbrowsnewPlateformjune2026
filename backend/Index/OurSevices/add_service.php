<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php'); // Adjusted relative path for login
    exit();
}
include '../../Database/config.php'; // Corrected path
  include '../preloader.php';
$message = '';
$messageType = ''; // 'success', 'danger', 'info'

// Check for database connection error
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in add_service.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
}

$uploadDir = __DIR__ . '/uploads/services/';

// Ensure the directory exists and is writable
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        error_log("Failed to create upload directory: " . $uploadDir);
        $message = "Server error: Failed to create upload directory. Please contact support.";
        $messageType = "danger";
    }
}
if (empty($message) && !is_writable($uploadDir)) {
    error_log("Upload directory is not writable: " . $uploadDir);
    $message = "Server error: Upload directory is not writable. Please check server permissions (e.g., set to 755 or 777 temporarily for testing).";
    $messageType = "danger";
}


if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $imageName = null; // Initialize image name

    // Validate input fields
    if (empty($title) || empty($description)) {
        $message = "Please fill in all required fields (Title and Description).";
        $messageType = "danger";
    } elseif (empty($message) && (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] === UPLOAD_ERR_NO_FILE)) {
        $message = "Please upload an image for the service.";
        $messageType = "danger";
    } elseif (empty($message)) {
        // Image upload handling
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['main_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $maxFileSize = 5 * 1024 * 1024; // 5 MB
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($ext, $allowedExts)) {
                $message = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.";
                $messageType = "danger";
            } elseif ($file['size'] > $maxFileSize) {
                $message = "File is too large. Maximum allowed size is 5MB.";
                $messageType = "danger";
            } else {
                $fileName = uniqid('service_', true) . '.' . $ext;
                $target = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $imageName = $fileName;
                } else {
                    error_log("Failed to move uploaded file to: " . $target . " (Error: " . $file['error'] . ")");
                    $message = "Image upload failed. Please check server permissions.";
                    $messageType = "danger";
                }
            }
        } else {
            // Handle other potential file upload errors
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                 switch ($_FILES['main_image']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $message = "Uploaded image exceeds maximum file size allowed.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $message = "Image was only partially uploaded. Please try again.";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $message = "Server error: Missing a temporary folder for uploads.";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $message = "Server error: Failed to write image to disk.";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $message = "A PHP extension stopped the image upload.";
                        break;
                    default:
                        $message = "An unknown image upload error occurred.";
                }
                $messageType = "danger";
            }
        }
    }

    // If no errors so far, proceed with database insertion
    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO our_services (main_image, title, description) VALUES (?, ?, ?)");
        if (!$stmt) {
            error_log("Database prepare failed: " . $conn->error);
            $message = "An internal database error occurred.";
            $messageType = "danger";
            // If image was uploaded but DB insert fails, delete the uploaded image
            if ($imageName && file_exists($uploadDir . $imageName)) {
                unlink($uploadDir . $imageName);
            }
        } else {
            $stmt->bind_param("sss", $imageName, $title, $description);
            if ($stmt->execute()) {
                header('Location: show_services.php?success=Service+added+successfully!');
                exit();
            } else {
                error_log("Database execute failed: " . $stmt->error);
                $message = "An error occurred while adding the service to the database.";
                $messageType = "danger";
                // If image was uploaded but DB insert fails, delete the uploaded image
                if ($imageName && file_exists($uploadDir . $imageName)) {
                    unlink($uploadDir . $imageName);
                }
            }
            $stmt->close();
        }
    }
}

// Close the database connection
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Service</title>
e>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">

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
                <i class="fas fa-hand-holding-usd fa-2x text-info me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Add New Service</h1>
                    <small class="text-muted">Showcase a new service you offer.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_services.php" class="text-decoration-none text-success fw-semibold">Services</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add New</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Service Details</h2>
        <p class="form-subheading">Fill in the information below to add a new service. All fields are required.</p>
    </div>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="main_image" class="form-label">Main Image <span class="text-danger">*</span></label>
                        <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*" required>
                        <small class="form-text text-muted">Allowed formats: JPG, JPEG, PNG, GIF. Max size: 5MB.</small>
                        <div class="invalid-feedback">Please choose an image for the service.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Web Development" required
                            value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                        <div class="invalid-feedback">Please enter a title for the service.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="3" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        <div class="invalid-feedback">Please enter a description for the service.</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 d-flex justify-content-end gap-3">
                <button type="submit" name="submit" class="btn-submit">Add Service</button>
                <a href="show_services.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
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