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
$moment = null;
$id = 0;

if (!isset($conn) || $conn->connect_error) {
    $message = "ERROR: Database connection failed.";
    $messageType = "danger";
} else {
    $baseUploadDirServerPath = dirname(__DIR__, 2) . '/uploads/cadet_moments/';
    $baseUploadDirWebPath = '../../uploads/cadet_moments/';

    if (!is_dir($baseUploadDirServerPath)) {
        mkdir($baseUploadDirServerPath, 0755, true);
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int)$_GET['id'];
    } elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = (int)$_POST['id'];
    } else {
        $message = "Invalid request.";
        $messageType = "danger";
        $conn->close();
        goto end_script_php;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        $stmt = $conn->prepare("SELECT main_image, main_image2 FROM cadet_moments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            $message = "Cadet moment not found.";
            $messageType = "danger";
            goto end_post_processing;
        }

        $new_main_image_name_db = $row['main_image'];
        $new_main_image2_name_db = $row['main_image2'];
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 5 * 1024 * 1024;

        // main_image upload
        if (!empty($_FILES['main_image']['name'])) {
            $file = $_FILES['main_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExts) && $file['size'] <= $maxFileSize) {
                $fileName = uniqid('cadet_main_', true) . '.' . $ext;
                $targetPath = $baseUploadDirServerPath . $fileName;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $new_main_image_name_db = $fileName;
                }
            }
        }

        // main_image2 upload
        if (!empty($_FILES['main_image2']['name'])) {
            $file2 = $_FILES['main_image2'];
            $ext2 = strtolower(pathinfo($file2['name'], PATHINFO_EXTENSION));
            if (in_array($ext2, $allowedExts) && $file2['size'] <= $maxFileSize) {
                $fileName2 = uniqid('cadet_sec_', true) . '.' . $ext2;
                $targetPath2 = $baseUploadDirServerPath . $fileName2;
                if (move_uploaded_file($file2['tmp_name'], $targetPath2)) {
                    $new_main_image2_name_db = $fileName2;
                }
            }
        }

        $stmt_update = $conn->prepare("UPDATE cadet_moments SET title = ?, description = ?, main_image = ?, main_image2 = ? WHERE id = ?");
        $stmt_update->bind_param("ssssi", $title, $description, $new_main_image_name_db, $new_main_image2_name_db, $id);
        if ($stmt_update->execute()) {
            header('Location: view_cadet_moments.php?id=' . urlencode($id) . '&success=Cadet+moment+updated+successfully');
            exit();
        } else {
            $message = "Database update failed.";
            $messageType = "danger";
        }
        $stmt_update->close();
    }

    end_post_processing:

    $stmt_fetch = $conn->prepare("SELECT * FROM cadet_moments WHERE id = ?");
    $stmt_fetch->bind_param("i", $id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    $moment = $result->fetch_assoc();
    $stmt_fetch->close();
    $conn->close();
}
end_script_php:
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Cadet Moment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">

    <style>
        .current-image-preview {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
            margin-top: 10px;
            display: block; /* Ensures it takes its own line below label */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-card {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 0.8rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid #e0e0e0;
        }
        .form-header {
            margin-bottom: 2rem;
        }
        .form-heading {
            font-size: 1.75rem;
            color: #343a40;
            font-weight: 600;
        }
        .form-subheading {
            font-size: 1rem;
            color: #6c757d;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .top-header {
            background: linear-gradient(to right, #e9ecef, #dee2e6);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #ced4da;
            border-radius: 0.75rem;
            margin-bottom: 2.5rem;
        }
        .enhanced-shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .alert {
            margin-top: 1.5rem;
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
                    <h1 class="h4 fw-bold text-dark mb-1">Edit Cadet Moment</h1>
                    <small class="text-muted">Update details of your cadet moment.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="show_cadet_moments.php" class="text-decoration-none text-success fw-semibold">Cadet Moments</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Moment</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="form-header text-start">
        <h2 class="form-heading">Update Cadet Moment Details</h2>
        <p class="form-subheading">Modify the information below to update this cadet moment.</p>
    </div>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show text-center" role="alert">
            <?= $message // $message content is already handled for HTML safety in PHP ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($moment !== null && $messageType !== 'danger') : // Only show form if data was successfully fetched and no critical error ?>
    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= htmlspecialchars($moment['id']) ?>">

            <div class="row d-flex flex-column gap-3">
                <div class="col-sm-12">
                    <div class="form-group mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" value="<?= htmlspecialchars($moment['title']) ?>" class="form-control" >
                        <div class="invalid-feedback">Please enter a title.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="4" class="form-control" ><?= htmlspecialchars($moment['description']) ?></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group mb-3">
                        <label for="main_image" class="form-label">Main Image</label><br>
                        <?php
                        // Construct full web path for current images for display
                        $currentMainImageWebPath = $baseUploadDirWebPath . $moment['main_image'];
                        ?>
                        <?php if (!empty($moment['main_image']) && file_exists($baseUploadDirServerPath . $moment['main_image'])) : ?>
                            <div class="mb-2">
                                <img src="<?= htmlspecialchars($currentMainImageWebPath) ?>" class="current-image-preview rounded" alt="Current Main Image">
                            </div>
                        <?php else : ?>
                            <p class="text-muted">No main image uploaded for this moment.</p>
                        <?php endif; ?>
                        <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*" />
                        <small class="form-text text-muted">Upload a new main image (JPG, JPEG, PNG, GIF). Max size: 5MB. Leave blank to keep current image.</small>
                        <div class="invalid-feedback">Please select a valid main image file.</div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group mb-3">
                        <label for="main_image2" class="form-label">Second Image (Optional)</label><br>
                        <?php
                        // Construct full web path for current images for display
                        $currentMainImage2WebPath = $baseUploadDirWebPath . $moment['main_image2'];
                        ?>
                        <?php if (!empty($moment['main_image2']) && file_exists($baseUploadDirServerPath . $moment['main_image2'])) : ?>
                            <div class="mb-2">
                                <img src="<?= htmlspecialchars($currentMainImage2WebPath) ?>" class="current-image-preview rounded" alt="Current Second Image">
                            </div>
                        <?php else : ?>
                            <p class="text-muted">No second image uploaded for this moment.</p>
                        <?php endif; ?>
                        <input type="file" name="main_image2" id="main_image2" class="form-control" accept="image/*" />
                        <small class="form-text text-muted">Upload a new second image (JPG, JPEG, PNG, GIF). Max size: 5MB. Leave blank to keep current image.</small>
                        <div class="invalid-feedback">Please select a valid second image file.</div>
                    </div>
                </div>


            </div>

            <div class="form-footer mt-4 d-flex justify-content-end gap-3">
                <button type="submit" name="update" class="btn-submit">Update</button>
                <a href="view_cadet_moments.php?id=<?= htmlspecialchars($moment['id']) ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    <?php elseif ($moment === null && $messageType === 'danger'): // Display error if moment data couldn't be fetched ?>
        <div class="form-card text-center p-5">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <p class="lead">Could not load cadet moment for editing.</p>
            <p>Please check the error message above or try again later.</p>
            <a href="show_cadet_moments.php" class="btn btn-primary mt-3"><i class="fas fa-arrow-left"></i> Back to Cadet Moments</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    // Form validation using Bootstrap's built-in validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // Dismiss alerts after a few seconds (optional, but good for user experience)
    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            // Check if the alert is not of type 'danger' before auto-dismissing
            if (!alertElement.classList.contains('alert-danger')) {
                setTimeout(() => {
                    const bootstrapAlert = bootstrap.Alert.getInstance(alertElement);
                    if (bootstrapAlert) {
                        bootstrapAlert.close();
                    }
                }, 7000); // Alert will dismiss after 7 seconds for non-danger messages
            }
        }
    });
</script>
</body>
</html>