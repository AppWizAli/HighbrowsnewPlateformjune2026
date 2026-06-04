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
    error_log("Database connection failed in edit_academicfaqs.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: show_academicfaqs.php?message=' . urlencode("Error: No FAQ ID provided.") . '&type=danger');
    exit();
}

$id = intval($_GET['id']);
$row = null;

if ($conn && !$conn->connect_error) {
    // Using backticks for the table name
    $stmt = $conn->prepare("SELECT id, title, description FROM `academic_faqs` WHERE id = ?");
    if (!$stmt) {
        error_log("Database prepare failed (select): " . $conn->error);
        $message = "An internal database error occurred while fetching FAQ data.";
        $messageType = "danger";
    } else {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            header('Location: show_academicfaqs.php?message=' . urlencode("Error: FAQ not found or invalid ID.") . '&type=danger');
            $stmt->close();
            $conn->close();
            exit();
        }
        $row = $result->fetch_assoc();
        $stmt->close();
    }
} else {
    $row = ['id' => $id, 'title' => '', 'description' => ''];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $messageType !== 'danger') {
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
        $message = "Error(s) during FAQ update:<br>" . implode("<br>", $formErrors);
        $messageType = "danger";
    } else {
        // Using backticks for the table name
        $updateQuery = "UPDATE `academic_faqs` SET title=?, description=? WHERE id=?";
        $stmt = $conn->prepare($updateQuery);

        if (!$stmt) {
            error_log("Database prepare failed (update): " . $conn->error);
            $message = "An internal database error occurred while preparing to update the FAQ.";
            $messageType = "danger";
        } else {
            $stmt->bind_param("ssi", $title, $description, $id);

            if ($stmt->execute()) {
                $message = "";
                $messageType = "success";
                header('Location: show_academicfaqs.php?message=' . urlencode($message) . '&type=' . urlencode($messageType));
                $stmt->close();
                $conn->close();
                exit();
            } else {
                error_log("Database execute failed (update): " . $stmt->error);
                $message = "Failed to update FAQ: " . $stmt->error;
                $messageType = "danger";
            }
            $stmt->close();
        }
    }
    // Re-fetch data if there was an error and no redirect happened
    if ($messageType === 'danger' && $conn && !$conn->connect_error) {
        $stmt_reget = $conn->prepare("SELECT id, title, description FROM `academic_faqs` WHERE id = ?");
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
    <title>Edit Academic FAQ</title>

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
                    <small class="text-muted">Edit frequently asked questions related to academics.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-info fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="show_academicfaqs.php" class="text-decoration-none text-info fw-semibold">Academic FAQs</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit FAQ</li>
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
        <h2 class="form-heading">Edit Academic FAQ</h2>
        <p class="form-subheading">Update the question and answer for this FAQ entry.</p>
    </div>

    <div class="form-card">
        <form action="" method="POST" class="needs-validation" novalidate>
            <div class="row d-flex flex-column gap-3">
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="form-group">
                        <label for="title" class="form-label">Title (Question)</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($row['title'] ?? '') ?>" required />
                        <div class="invalid-feedback">Please enter a title for the FAQ.</div>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-6">
                    <div class="form-group">
                        <label for="description" class="form-label">Description (Answer)</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($row['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback">Please enter a description for the FAQ.</div>
                    </div>
                </div>
            </div>

            <div class="form-footer mt-4 text-end d-flex justify-content-end gap-3">
                <button type="submit" class="btn-submit">Update FAQ</button>
                <a href="show_academicfaqs.php" class="btn btn-secondary">Cancel</a>
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