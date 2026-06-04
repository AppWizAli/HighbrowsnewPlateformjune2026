<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
  include '../preloader.php';
$result = $conn->query("SELECT * FROM life_style ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Life Style Management</title>

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
        <i class="fas fa-leaf fa-2x text-success me-3 mt-1"></i>
        <div>
          <h1 class="h4 fw-bold text-dark mb-1">Life Style Entries</h1>
          <small class="text-muted">Manage your life style data here.</small>
        </div>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
          <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Life Style</li>
        </ol>
      </nav>
    </div>
  </header>

  <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
      <?= htmlspecialchars($_GET['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="content-header d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Manage Entries</h2>
      <p>View, edit, or delete lifestyle content entries below.</p>
    </div>
    <a href="add_lifestyle.php" class="btn-add"><i class="fas fa-plus"></i> Add New</a>
  </div>
<div class="search-filter mb-3">
    <div class="search-box position-relative">
        <input type="text" id="searchInput" class="" placeholder="Search by ID or Title..." onkeypress="handleSearchKey(event)" />
        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-primary" style="cursor: pointer;" onclick="filterTable()"></i>
    </div>
</div>


  <div class="table-responsive">
    <table class="table-wrapper">
      <thead class="">
        <tr>
          <th>ID</th>
          <th>Main Image</th>
          <th>Title</th>
          <th>Title 2</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><img src="<?= htmlspecialchars($row['main_image']) ?>" alt="Life Style" class="rounded border" width="100"></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['title2']) ?></td>
              <td><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 80, '...')) ?></td>
                   <td class="action-btns" data-label="Actions">
                                    <a href="edit_lifestyle.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_lifestyle.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this military moment? This action cannot be undone.');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-3">No life style entries found.</td>
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
    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const id = row.children[0].textContent.toUpperCase();
        const title = row.children[2].textContent.toUpperCase();
        const match = id.includes(input) || title.includes(input);
        row.style.display = match ? "" : "none";
    });
}

function handleSearchKey(event) {
    if (event.key === "Enter") {
        filterTable();
    }
}
</script>

</body>
</html>
