<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php'); // Adjusted relative path for login
    exit();
}
include '../../Database/config.php'; // Corrected path
  include '../preloader.php';
// Initialize messages for feedback
$message = '';
$messageType = ''; // 'success', 'danger', 'info'

// Check for success/error messages from other pages (e.g., after add, edit, delete)
if (isset($_GET['success'])) {
    $message = htmlspecialchars($_GET['success']);
    $messageType = 'success';
} elseif (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
    $messageType = 'danger';
}

// Check for database connection error
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in show_services.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
}


// Fetch services data
$query = "SELECT * FROM our_services ORDER BY id DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    error_log("Database query failed in show_services.php: " . mysqli_error($conn));
    $message = "ERROR: Could not fetch services from the database. Please try again later.";
    $messageType = "danger";
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
    <title>Services Content - All Services</title>
e>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">

    <style>
  

        .image-preview {
            max-width: 100px; /* Adjust as needed */
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

<div class="main">
    <section class="container-fluid p-4">

        <header class="top-header mb-4 enhanced-shadow">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-center gap-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-hand-holding-usd fa-2x text-success me-3 mt-1"></i>
                    <div>
                        <h1 class="h4 fw-bold text-dark mb-1">Services Content Management</h1>
                        <small class="text-muted">View and manage the services you offer from here.</small>
                    </div>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                        </li>
                        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Services Content</li>
                    </ol>
                </nav>
            </div>
        </header>

        <?php if (!empty($message)) : ?>
            <div class="alert alert-<?= htmlspecialchars($messageType) ?> text-center">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="content-header d-flex justify-content-between align-items-center mb-3">
            <div class="left-info">
                <h2>Manage Your Services</h2>
                <p>Below is a list of all services. You can edit, delete, or add new services here.</p>
            </div>
            <div class="action-area">
                <a href="add_service.php" class="btn-add"><i class="fas fa-plus"></i> Add New Service</a>
            </div>
        </div>

        <div class="search-filter mb-3">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search services..." aria-label="Search services" />
                <i class="bi bi-search text-success ms-2"></i>
            </div>
        </div>

        <div class="table-wrapper">
            <table aria-describedby="services-table-description">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="servicesTableBody">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                                <td data-label="Image">
                                    <?php if (!empty($row['main_image'])): ?>
                                        <img src="uploads/services/<?= htmlspecialchars($row['main_image']) ?>" alt="Service Image" class="image-preview">
                                    <?php else: ?>
                                        <span class="text-muted">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Title"><?= htmlspecialchars($row['title']) ?></td>
                                <td data-label="Description"><?= htmlspecialchars(substr($row['description'], 0, 100)) . (strlen($row['description']) > 100 ? '...' : '') ?></td>
                                <td class="action-btns" data-label="Actions">
                                    <a href="edit_service.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_service.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this service?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No services found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
function filterServicesTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.trim().toUpperCase();
    const tableBody = document.getElementById("servicesTableBody");
    const tr = tableBody.getElementsByTagName("tr");

    let found = false;
    const noResultsRowId = 'no-results-row';
    let noResultsRow = document.getElementById(noResultsRowId);

    for (let i = 0; i < tr.length; i++) {
        if (tr[i].id === noResultsRowId) continue;

        const idCell = tr[i].getElementsByTagName("td")[0];
        const titleCell = tr[i].getElementsByTagName("td")[2];

        const idText = idCell ? idCell.textContent.toUpperCase() : "";
        const titleText = titleCell ? titleCell.textContent.toUpperCase() : "";

        if (idText.includes(filter) || titleText.includes(filter)) {
            tr[i].style.display = "";
            found = true;
        } else {
            tr[i].style.display = "none";
        }
    }

    if (!found && filter.length > 0) {
        if (!noResultsRow) {
            noResultsRow = tableBody.insertRow();
            noResultsRow.id = noResultsRowId;
            const cell = noResultsRow.insertCell();
            cell.colSpan = 5;
            cell.classList.add('text-center', 'text-muted', 'py-3');
            cell.textContent = 'No matching services found.';
        }
        noResultsRow.style.display = '';
    } else {
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
}

// Trigger filter on Enter key press
document.getElementById("searchInput").addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        filterServicesTable();
    }
});

// Trigger filter on search icon click
document.querySelector(".bi-search").addEventListener("click", function() {
    filterServicesTable();
});
</script>

</body>
</html>