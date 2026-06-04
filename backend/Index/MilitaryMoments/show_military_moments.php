<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}

// Initialize messages for feedback
$message = '';
$messageType = '';
$result = null;

if (isset($_GET['success'])) {
    $message = htmlspecialchars($_GET['success']);
    $messageType = 'success';
} elseif (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
    $messageType = 'danger';
}
  include '../preloader.php';
include '../../Database/config.php';

if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in show_military_moments.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
} else {
    $query = "SELECT * FROM military_moment ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Database query failed in show_military_moments.php: " . mysqli_error($conn));
        $message = "ERROR: Could not fetch military moments from the database. Please try again later.";
        $messageType = "danger";
        $result = null;
    }
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Military Moments - Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css"> 
    <style>
        .image-preview {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            display: block;
            margin: 0 auto;
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
<section class="container-fluid p-0">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-camera-retro fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Military Moments Content Management</h1>
                    <small class="text-muted">View and manage military moments from here.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Military Moments</li>
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

    <div class="content-header d-flex justify-content-between align-items-center mb-3">
        <div class="left-info">
            <h2>Manage Your Military Moments</h2>
            <p>Below is a list of all military moments. You can edit, delete, or add new moments here.</p>
        </div>
        <div class="action-area">
            <a href="add_military_moments.php" class="btn-add"><i class="fas fa-plus"></i> Add Moment</a>
        </div>
    </div>

    <div class="search-box d-flex align-items-center">
        <input type="text" id="searchInput" placeholder="Search moments..." aria-label="Search moments" class="" />
        <i class="bi bi-search"></i>
    </div>

    <div class="table-wrapper">
        <table aria-describedby="military-moments-table-description">
            <caption id="military-moments-table-description" class="visually-hidden">List of military moments with details and actions.</caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Main Image</th>
                    <th>Second Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="momentsTableBody">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                            <td data-label="Main Image">
                                <?php if (!empty($row['main_image'])): ?>
                                    <img src="../../uploads/military_moments/<?= htmlspecialchars($row['main_image']) ?>" alt="Main Image" class="image-preview">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Second Image">
                                <?php if (!empty($row['main_image2'])): ?>
                                    <img src="../../uploads/military_moments/<?= htmlspecialchars($row['main_image2']) ?>" alt="Second Image" class="image-preview">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Title"><?= htmlspecialchars($row['title']) ?></td>
                            <td data-label="Description"><?= htmlspecialchars(substr($row['description'], 0, 100)) . (strlen($row['description']) > 100 ? '...' : '') ?></td>
                            <td class="action-btns" data-label="Actions">
                                <a href="edit_military_moments.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_military_moments.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this military moment? This action cannot be undone.');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <?php if (!empty($message) && $messageType === 'danger'): ?>
                                <?= htmlspecialchars($message) ?>
                            <?php else: ?>
                                No military moments found. Click "Add New Moment" to create one.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const momentsTableBody = document.getElementById('momentsTableBody');

    function filterTable() {
        const filter = searchInput.value.toLowerCase();
        const rows = Array.from(momentsTableBody.children);

        rows.forEach(row => {
            const isMessageRow = row.querySelector('td')?.colSpan === 6;
            if (!isMessageRow) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            }
        });
    }

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterTable();
        }
    });

    searchBtn?.addEventListener('click', filterTable);
</script>

</body>
</html>
