<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../Database/config.php';

$query = "SELECT * FROM admin ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Panel - Manage Admins</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="login.css">
   <link rel="stylesheet" href="../Includes/sidebar.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/index.css"> 
    <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">

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
$sidebarPath = '../Includes/sidebar.php';
if (file_exists($sidebarPath)) {
    include $sidebarPath;
} else {
    echo "<p class='text-danger p-3'>Sidebar file not found: $sidebarPath</p>";
}
?>

<div class="main">
  <section class="container-fluid p-4">
    <header class="top-header mb-4 enhanced-shadow">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="d-flex align-items-start">
          <i class="fas fa-user-shield fa-2x text-success me-3 mt-1"></i>
          <div>
            <h1 class="h4 fw-bold text-dark mb-1">Admin Management</h1>
            <small class="text-muted">Manage admin users from here.</small>
          </div>
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item">
              <a href="#" class="text-decoration-none text-success fw-semibold">Home</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Admins</li>
          </ol>
        </nav>
      </div>
    </header>

    <div class="content-header d-flex justify-content-between align-items-center mb-3">
      <div class="left-info">
        <h2>Manage Admin Users</h2>
        <p>You can add, edit, or delete admin users.</p>
      </div>
      <div class="action-area">
        <a href="Signup.php" class="btn-add"><i class="fas fa-plus"></i> Add Admin</a>
      </div>
    </div>

    <div class="search-filter mb-3">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search admin by ID or Name..." />
        <i class="bi bi-search text-success ms-2"></i>
      </div>
    </div>

    <div class="table-wrapper">
      <table >
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="adminTableBody">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= $row['created_at'] ?></td>
                 <td class="action-btns" data-label="Actions">
                                  
                                    <a href="delete_admin.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this service?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No admin records found.</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../Includes/sidebar.js"></script>
<script>
function filterAdmins() {
    const input = document.getElementById("searchInput").value.toUpperCase();
    const rows = document.querySelectorAll("#adminTableBody tr");

    rows.forEach(row => {
        const id = row.cells[0].textContent.toUpperCase();
        const name = row.cells[1].textContent.toUpperCase();
        row.style.display = (id.includes(input) || name.includes(input)) ? "" : "none";
    });
}

document.querySelector(".bi-search").addEventListener("click", filterAdmins);
document.getElementById("searchInput").addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        filterAdmins();
    }
});
</script>
</body>
</html>