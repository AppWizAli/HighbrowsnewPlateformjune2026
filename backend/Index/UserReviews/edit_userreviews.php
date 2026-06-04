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

if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in edit_userreviews.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
}

$baseBackendDir = dirname(__DIR__, 2);
$userReviewsUploadDirServerSide = $baseBackendDir . '/uploads/user_reviews/';

$webRootPrefix = '';
$userReviewsUploadDirWebRelative = $webRootPrefix . '/backend/uploads/user_reviews/';

function getUserReviewImageDisplayPath($imagePathFromDB, $userReviewsUploadDirWebRelative) {
    if (empty($imagePathFromDB)) {
        return 'https://via.placeholder.com/100?text=No+Image';
    }
    if (strpos($imagePathFromDB, $userReviewsUploadDirWebRelative) === 0) {
        return htmlspecialchars($imagePathFromDB);
    }
    return htmlspecialchars($userReviewsUploadDirWebRelative . basename($imagePathFromDB));
}

function handleFileUpload($fileInputName, $uploadDirServerSide, $webPathPrefix, $maxSize, $allowedExts, &$errors) {
    $file = $_FILES[$fileInputName];
    $dbPath = '';

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['dbPath' => ''];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "An error occurred during " . str_replace('_', ' ', $fileInputName) . " upload: " . $file['error'];
        return ['dbPath' => ''];
    }

    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExts)) {
        $errors[] = "Invalid file type for " . str_replace('_', ' ', $fileInputName) . ". Allowed: " . implode(', ', $allowedExts) . ".";
    }
    if ($file['size'] > $maxSize) {
        $errors[] = str_replace('_', ' ', $fileInputName) . " file is too large. Maximum allowed size is " . ($maxSize / 1024 / 1024) . "MB.";
    }

    if (empty($errors)) {
        $fileNameOnServer = uniqid($fileInputName . '_', true) . '.' . $fileExtension;
        $targetPathServerSide = $uploadDirServerSide . $fileNameOnServer;
        $dbPath = $webPathPrefix . $fileNameOnServer;

        if (!is_dir($uploadDirServerSide)) {
            if (!mkdir($uploadDirServerSide, 0755, true)) {
                $errors[] = "Server error: Failed to create image upload directory.";
                error_log("Failed to create directory: " . $uploadDirServerSide);
                return ['dbPath' => ''];
            }
        }
        if (!is_writable($uploadDirServerSide)) {
            $errors[] = "Server error: Image upload directory is not writable. Check permissions.";
            error_log("Directory not writable: " . $uploadDirServerSide);
            return ['dbPath' => ''];
        }

        if (!move_uploaded_file($file['tmp_name'], $targetPathServerSide)) {
            $errors[] = "Failed to upload " . str_replace('_', ' ', $fileInputName) . ". Please try again.";
            error_log("Failed to move uploaded file: " . $file['tmp_name'] . " to " . $targetPathServerSide . " (PHP Error: " . $file['error'] . ")");
            $dbPath = '';
        }
    }
    return ['dbPath' => $dbPath];
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: show_userreviews.php?message=' . urlencode("Error: No User Review ID provided.") . '&type=danger');
    exit();
}

$id = intval($_GET['id']);
$row = null;

if ($conn && !$conn->connect_error) {
    $stmt = $conn->prepare("SELECT id, main_image, title, description FROM user_reviews WHERE id = ?");
    if (!$stmt) {
        error_log("Database prepare failed (select): " . $conn->error);
        $message = "An internal database error occurred while fetching user review data.";
        $messageType = "danger";
    } else {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            header('Location: show_userreviews.php?message=' . urlencode("Error: User review not found or invalid ID.") . '&type=danger');
            $stmt->close();
            $conn->close();
            exit();
        }
        $row = $result->fetch_assoc();
        $stmt->close();
    }
} else {
    $row = ['id' => $id, 'main_image' => '', 'title' => '', 'description' => ''];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $messageType !== 'danger') {
    $maxFileSize = 5 * 1024 * 1024;
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    $uploadErrors = [];

    $newMainImagePath = $row['main_image'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $uploadErrors[] = "Review title is required.";
    }
    if (empty($description)) {
        $uploadErrors[] = "Review description is required.";
    }

    if (isset($_FILES["main_image"]) && $_FILES["main_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = handleFileUpload("main_image", $userReviewsUploadDirServerSide, $userReviewsUploadDirWebRelative, $maxFileSize, $allowedExts, $uploadErrors);
        if (empty($uploadErrors) && !empty($uploadResult['dbPath'])) {
            $newMainImagePath = $uploadResult['dbPath'];
            if (!empty($row['main_image'])) {
                $oldImageServerPath = str_replace($userReviewsUploadDirWebRelative, $userReviewsUploadDirServerSide, $row['main_image']);
                $oldImageServerPath = realpath($oldImageServerPath);
                if ($oldImageServerPath && file_exists($oldImageServerPath) && is_file($oldImageServerPath)) {
                    if (!unlink($oldImageServerPath)) {
                        error_log("Failed to delete old review image: " . $oldImageServerPath);
                    }
                }
            }
        }
    }

    if (!empty($uploadErrors)) {
        $message = "Upload Errors:<br>" . implode("<br>", $uploadErrors);
        $messageType = "danger";
    } else {
        $updateQuery = "UPDATE user_reviews SET main_image=?, title=?, description=? WHERE id=?";
        $stmt = $conn->prepare($updateQuery);

        if (!$stmt) {
            error_log("Database prepare failed (update): " . $conn->error);
            $message = "An internal database error occurred while preparing to update the review.";
            $messageType = "danger";
        } else {
            $stmt->bind_param("sssi", $newMainImagePath, $title, $description, $id);

            if ($stmt->execute()) {
                $message = "";
                $messageType = "success";
                header('Location: show_userreviews.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
                $stmt->close();
                $conn->close();
                exit();
            } else {
                error_log("Database execute failed (update): " . $stmt->error);
                $message = "Failed to update user review: " . $stmt->error;
                $messageType = "danger";
            }
            $stmt->close();
        }
    }
    if ($messageType === 'danger' && $conn && !$conn->connect_error) {
        $stmt_reget = $conn->prepare("SELECT id, main_image, title, description FROM user_reviews WHERE id = ?");
        if ($stmt_reget) {
            $stmt_reget->bind_param("i", $id);
            $stmt_reget->execute();
            $result_reget = $stmt_reget->get_result();
            if ($result_reget->num_rows === 1) {
                $row = $result_reget->fetch_assoc();
            }
            $stmt_reget->close();
        }
    }
}


if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
    $messageType = htmlspecialchars(urldecode($_GET['type']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit User Review</title>

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
                    <small class="text-muted">Edit customer reviews for your website.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_userreviews.php" class="text-decoration-none text-primary fw-semibold">User Reviews</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Review</li>
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
        <h2 class="form-heading">Edit User Review</h2>
        <p class="form-subheading">Update the details for this user review. Uploading a new image will replace the existing one.</p>
    </div>

    <div class="form-card">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row d-flex flex-column gap-3">
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="form-group">
                        <label for="main_image" class="form-label">Main Image</label><br>
                        <img src="<?= getUserReviewImageDisplayPath($row['main_image'], $userReviewsUploadDirWebRelative) ?>" alt="Current Review Image" width="100" class="mb-2 rounded" />
                        <input type="file" id="main_image" name="main_image" class="form-control" accept="image/*" />
                        <small class="form-text text-muted">Upload a new image for the review (Max 5MB, JPG, PNG, GIF). Leave empty to keep current image.</small>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($row['title'] ?? '') ?>" required />
                        <div class="invalid-feedback">Please enter a title for the review.</div>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback">Please enter a description for the review.</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" class="btn-submit">Update Review</button>
                <a href="show_userreviews.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
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