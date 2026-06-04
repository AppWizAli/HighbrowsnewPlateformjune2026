<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { // Added strict comparison and check for true
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
  include '../preloader.php';
// Initialize messages for feedback
$message = '';
$messageType = ''; // 'success', 'danger', 'info'

// Check for database connection error
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in edit_proud_moment.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
    // If DB connection fails, display error and exit, as further operations will fail
    // You might want to redirect to an error page or simply stop rendering the form
    die("ERROR: Database connection failed. Please try again later or contact support.");
}

// Ensure ID is provided and is an integer to prevent SQL injection for the initial fetch
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) {
    header('Location: show_proud.php?error=Invalid+proud+moment+ID+provided.'); // Redirect with error message
    exit();
}

// Fetch existing data for the proud moment
$stmt_fetch = $conn->prepare("SELECT * FROM proud_moment WHERE id = ?");
if (!$stmt_fetch) {
    error_log("Prepare failed (fetch): " . $conn->error);
    die("Database error: Could not prepare data fetch. Please contact support.");
}
$stmt_fetch->bind_param("i", $id);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();
$row = $result->fetch_assoc();
$stmt_fetch->close();

if (!$row) {
    // No record found for the given ID
    header('Location: show_proud.php?error=Proud+moment+not+found.');
    exit();
}

// --- CORRECTED UPLOAD DIRECTORY PATH ---
// This script is in 'GalleryProudMom/'
// The uploads are in 'GalleryProudMom/uploads/proud_moments/'
$uploadDir = __DIR__ . '/uploads/proud_moments/'; // Removed the redundant '../'

// Ensure the directory exists and is writable
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) { // Create directory recursively with 0755 permissions
        error_log("Failed to create upload directory: " . $uploadDir);
        $message = "Server error: Failed to create upload directory. Please contact support.";
        $messageType = "danger";
    }
}
if (empty($message) && !is_writable($uploadDir)) { // Only check if no error from mkdir
    error_log("Upload directory is not writable: " . $uploadDir);
    $message = "Server error: Upload directory is not writable. Please check server permissions (e.g., set to 755 or 777 temporarily for testing).";
    $messageType = "danger";
}


if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $new_image_filename = $row['main_image']; // Start with the existing image name

    // Check if a new image file was uploaded AND if there was no initial directory error
    if (empty($message) && !empty($_FILES['main_image']['name']) && $_FILES['main_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['main_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // --- File Validation ---
        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowedExts)) {
            $message = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.";
            $messageType = "danger";
        } elseif ($file['size'] > $maxFileSize) {
            $message = "File is too large. Maximum allowed size is 5MB.";
            $messageType = "danger";
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            // Handle various upload errors specifically
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $message = "Uploaded file exceeds maximum file size allowed by server configuration.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = "File was only partially uploaded. Please try again.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = "Missing a temporary folder for uploads on the server.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = "Failed to write file to disk. Check server permissions or disk space.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $message = "A PHP extension stopped the file upload unexpectedly.";
                    break;
                default:
                    $message = "An unknown upload error occurred.";
            }
            $messageType = "danger";
        } else {
            // Valid file, proceed with upload
            $fileName = uniqid('proud_', true) . '.' . $ext;
            $target = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Upload successful, delete old image if it exists and is different
                $oldImagePath = $uploadDir . $row['main_image'];
                if (!empty($row['main_image']) && file_exists($oldImagePath) && $row['main_image'] !== $fileName) {
                    unlink($oldImagePath); // Delete the old image file
                }
                $new_image_filename = $fileName; // Update image name for database
            } else {
                // Failed to move file - check permissions or target path
                error_log("Failed to move uploaded file to: " . $target . " (Error: " . $file['error'] . ")");
                $message = "Image upload failed. Please check server permissions or target directory. " . htmlspecialchars($uploadDir);
                $messageType = "danger";
            }
        }
    }

    // Only proceed with database update if no image upload or directory error occurred
    if (empty($message)) {
        // Use prepared statements for update
        $stmt_update = $conn->prepare("UPDATE proud_moment SET main_image=?, title=?, description=? WHERE id=?");
        if (!$stmt_update) {
            error_log("Database prepare failed (update): " . $conn->error);
            $message = "An internal database error occurred while preparing update.";
            $messageType = "danger";
        } else {
            $stmt_update->bind_param("sssi", $new_image_filename, $title, $description, $id);
            if ($stmt_update->execute()) {
                // Redirect to show_proud.php with a success message
                header('Location: show_proud.php?success=Proud+moment+updated+successfully!');
                exit();
            } else {
                error_log("Database execute failed (update): " . $stmt_update->error);
                $message = "An error occurred while updating the proud moment in the database.";
                $messageType = "danger";
                // If DB update fails, and a new image was uploaded, consider deleting it to prevent orphaned files
                if ($new_image_filename !== $row['main_image'] && file_exists($uploadDir . $new_image_filename)) {
                    unlink($uploadDir . $new_image_filename);
                }
            }
            $stmt_update->close();
        }
    }
    // If there was a message, it will be displayed on the page
}

// Close the database connection at the end of the script
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Proud Moment</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css" />
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css" />
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css" />

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
<?php include '../../Includes/sidebar.php'; ?>

<div class="main container py-4">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-edit fa-2x text-info me-3 mt-1"></i> 
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Edit Proud Moment</h1>
                    <small class="text-muted">Update details of your proud achievement.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_proud.php" class="text-decoration-none text-success fw-semibold">Proud Moments</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Moment</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start"> <h2 class="form-heading">Update Proud Moment Details</h2>
        <p class="form-subheading">Modify the information below to update this proud moment. All fields are required.</p>
    </div>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

            <div class="form-group mb-3"> <label for="main_image" class="form-label">Current Image</label><br>
                <?php if (!empty($row['main_image'])) : ?>
                    <div class="mb-2">
                        <img src="uploads/proud_moments/<?= htmlspecialchars($row['main_image']) ?>" class="img-preview rounded" alt="Current Image">
                    </div>
                <?php else : ?>
                    <p class="text-muted">No image uploaded.</p>
                <?php endif; ?>
                <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*" />
                <small class="form-text text-muted">Upload a new image (JPG, JPEG, PNG, GIF). Max size: 5MB.</small>
                <div class="invalid-feedback">Please choose a valid image file.</div>
            </div>

            <div class="form-group mb-3"> <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control" required>
                <div class="invalid-feedback">Please enter a title.</div>
            </div>

            <div class="form-group mb-3"> <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" id="description" rows="4" class="form-control" required><?= htmlspecialchars($row['description']) ?></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>

            <div class="form-footer mt-4">
                <button type="submit" name="update" class="btn-submit">Update</button>
                <a href="show_proud.php" class="btn btn-secondary ms-2">Back to </a> </div>
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