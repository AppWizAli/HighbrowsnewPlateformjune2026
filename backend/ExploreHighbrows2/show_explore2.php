<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../LoginReg/login.php");
    exit();
}
include '../Index/preloader.php';
include '../Database/config.php';

$query = "SELECT * FROM explore_highbrows2 ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Explore Highbrows</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../Includes/sidebar.css">
  <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
  <link rel="stylesheet" href="../Index/HeroAreas/css/index.css"> 
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      vertical-align: middle;
      text-align: center;
      padding: 10px;
      border-bottom: 1px solid #dee2e6;
    }
    img.thumb {
      width: 100px;
      height: auto;
      border-radius: 5px;
    }
    .action-btns a {
      margin-right: 5px;
    }
    .top-header {
      padding: 20px;
      background: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      margin-bottom: 20px;
    }
  </style>
  <style>
.clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  
}
</style>

</head>
<body>

<?php include '../Includes/sidebar.php'; ?>

<div class="main">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-compass fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Explore Content Management</h1>
                    <small class="text-muted">View and manage Explore section entries from here.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Explore</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php if (isset($_GET['message'])): ?>
        <?php
            $message = htmlspecialchars($_GET['message']);
            $alertClass = strpos($message, 'success') !== false ? 'alert-success' : 'alert-danger';
        ?>
        <div class="alert <?= $alertClass ?> mt-3"><?= $message ?></div>
    <?php endif; ?>

    <div class="content-header d-flex justify-content-between align-items-center mb-3">
        <div class="left-info">
            <h2>Manage Explore Entries</h2>
            <p>Below is a list of all Explore entries. You can edit, delete, or add new ones.</p>
        </div>
        <div class="action-area">
            <a href="add_explore2.php" class="btn-add">
                <i class="fas fa-plus"></i> Add New Explore
            </a>
        </div>
    </div>

    <div class="search-filter mb-3">
        <div class="search-box position-relative">
            <input type="text" id="searchInput" placeholder="Search by ID or Title..." aria-label="Search Explore Content">
            <i class="bi bi-search position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="filterExploreTable()"></i>
        </div>
    </div>

    <div id="explore-table-description" class="visually-hidden">Explore table with listing and actions.</div>

    <div class="table-container">
        <div class="table-wrapper">
            <table id="exploreTable" aria-describedby="explore-table-description">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Main Image</th>
                        <th>Main Image 2</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Description 2</th>
                      
                        <th>Actions</th>
                    </tr>
                </thead>
             <tbody>
<?php if ($result && mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td>
                <?php if (!empty($row['main_image'])): ?>
                    <img src="/backend/ExploreHighbrows2/uploads/<?= htmlspecialchars($row['main_image']) ?>" class="img-thumbnail" style="width: 80px;" alt="Main Image">
                <?php else: ?>
                    <span class="text-muted">No image</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($row['main_image2'])): ?>
                    <img src="/backend/ExploreHighbrows2/uploads/<?= htmlspecialchars($row['main_image2']) ?>" class="img-thumbnail" style="width: 80px;" alt="Main Image 2">
                <?php else: ?>
                    <span class="text-muted">No image</span>
                <?php endif; ?>
            </td>
            <td><div class="clamp-2"><?= htmlspecialchars($row['title']) ?></div></td>
            <td><div class="clamp-2"><?= htmlspecialchars($row['description']) ?></div></td>
            <td><div class="clamp-2"><?= htmlspecialchars($row['description2']) ?></div></td>
          
            <td class="action-btns">
                <a class="edit" href="edit_explore2.php?id=<?= htmlspecialchars($row['id']) ?>" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                <a class="delete" href="delete_explore2.php?id=<?= htmlspecialchars($row['id']) ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this cadet moment? This action cannot be undone.');"><i class="fas fa-trash-alt"></i> Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="8" class="text-center text-muted py-4">No Explore content found.</td></tr>
<?php endif; ?>
</tbody>

            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script>
<script>
function filterExploreTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#exploreTable tbody tr");
    rows.forEach(row => {
        const id = row.children[0].textContent.toLowerCase();
        const title = row.children[3].textContent.toLowerCase();
        const match = id.includes(input) || title.includes(input);
        row.style.display = match ? "" : "none";
    });
}

document.getElementById("searchInput").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        filterExploreTable();
    }
});
</script>

</body>
</html>
