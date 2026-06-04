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

// Read message from GET if available
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
    $messageType = htmlspecialchars(urldecode($_GET['type']));
}

if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in show_userreviews.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
}

$webRootPrefix = '';
$userReviewsUploadDirWebRelative = $webRootPrefix . '/backend/uploads/user_reviews/';

// ✅ Fix: Show correct image path
function getUserReviewImageDisplayPath($imagePathFromDB, $userReviewsUploadDirWebRelative) {
    if (empty($imagePathFromDB)) {
        return 'https://via.placeholder.com/100x75?text=No+Image';
    }

    // Already has full path
    if (strpos($imagePathFromDB, $userReviewsUploadDirWebRelative) === 0) {
        return htmlspecialchars($imagePathFromDB) . '?v=' . time();
    }

    return htmlspecialchars($userReviewsUploadDirWebRelative . basename($imagePathFromDB)) . '?v=' . time();
}

// Fetch all user reviews
$userReviewQuery = "SELECT * FROM user_reviews ORDER BY id DESC";
$userReviewResult = null;

if ($conn && !$conn->connect_error) {
    $userReviewResult = $conn->query($userReviewQuery);
    if (!$userReviewResult) {
        error_log("Database query failed in show_userreviews.php: " . $conn->error);
        $message = "Error fetching user review data: " . $conn->error;
        $messageType = "danger";
    }
} else {
    $message = "Database connection not available. Cannot display user reviews.";
    $messageType = "danger";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Reviews Admin</title>

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
   

        <!-- Header Section -->
        <header class="top-header mb-4 enhanced-shadow">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-star fa-2x text-primary me-3 mt-1"></i>
                    <div>
                        <h1 class="h4 fw-bold text-dark mb-1">User Reviews Management</h1>
                        <small class="text-muted">View and manage customer feedback from here.</small>
                    </div>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a>
                        </li>
                        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">User Reviews</li>
                    </ol>
                </nav>
            </div>
        </header>

        <!-- Alert Message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show text-center" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Content Header -->
        <div class="content-header d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Manage User Reviews</h2>
                <p>Below is a list of all user reviews. You can edit, delete, or add new reviews here.</p>
            </div>
           <div class="action-area">
                <a href="add_userreviews.php" class="btn-add"><i class="fas fa-plus"></i> Add Reviews</a>
            </div>
        </div>

        <!-- Search Bar -->
  <div class="search-box d-flex align-items-center">
<input type="text" id="searchInput" class="form-control" placeholder="Search by ID or Title...">

   <i class="bi bi-search fs-5 text-primary" id="searchIcon" style="cursor: pointer;"></i>

</div>


        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table-wrapper">
                <thead >
                    <tr>
                        <th>ID</th>
                        <th>Main Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userReviewTableBody">
                    <?php if ($userReviewResult && $userReviewResult->num_rows > 0): ?>
                        <?php while ($row = $userReviewResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td>
                                    <img src="<?= getUserReviewImageDisplayPath($row['main_image'], $userReviewsUploadDirWebRelative) ?>"
                                         alt="Review Image" class="rounded border" width="100">
                                </td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '...')) ?></td>
                                  <td class="action-btns" data-label="Actions">
                                    <a href="edit_userreviews.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_userreviews.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this military moment? This action cannot be undone.');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No user reviews found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
  
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>

<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toUpperCase().trim();
    const rows = document.querySelectorAll("#userReviewTableBody tr");

    rows.forEach(row => {
        const id = row.children[0].textContent.toUpperCase();
        const title = row.children[2].textContent.toUpperCase();
        const match = id.includes(input) || title.includes(input);
        row.style.display = match ? "" : "none";
    });
}

// Trigger search on Enter key
document.getElementById("searchInput").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        filterTable();
    }
});

// Trigger search on icon click
document.getElementById("searchIcon").addEventListener("click", function() {
    filterTable();
});
</script>


</body>
</html>


