<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
  include '../preloader.php';
$result = $conn->query("SELECT * FROM about_section");
$message = $_GET['message'] ?? '';
$messageType = $_GET['type'] ?? 'success';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Section Admin</title>
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
  <!-- Header Section -->
  <header class="top-header mb-4 enhanced-shadow">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
      <div class="d-flex align-items-start">
        <i class="bi bi-image fa-2x text-primary me-3 mt-1"></i>
        <div>
          <h1 class="h4 fw-bold text-dark mb-1">About Section Management</h1>
          <small class="text-muted">Manage main image of your About section here.</small>
        </div>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0">
          <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-primary fw-semibold">Home</a></li>
          <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">About Section</li>
        </ol>
      </nav>
    </div>
  </header>

  <!-- Alert Message -->
  <?php if (!empty($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($messageType) ?> alert-dismissible fade show text-center" role="alert">
      <?= htmlspecialchars($message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Content Header -->
  <div class="content-header d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>About Section Images</h2>
      <p>View or edit the image used in the About section.</p>
    </div>
    <div class="action-area">
      <a href="add_about.php" class="btn-add"><i class="fas fa-plus-circle me-2"></i>Add Image</a>
    </div>
  </div>
<div class="search-filter mb-3">
    <div class="search-box position-relative">
        <input type="text" id="aboutSearchInput" class="" placeholder="Search by ID..." onkeypress="handleAboutSearchKey(event)" />
        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-primary" style="cursor: pointer;" onclick="filterAboutTable()"></i>
    </div>
</div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table-wrapper">
      <thead class="">
        <tr>
          <th>ID</th>
          <th>Main Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td>
                <img src="<?= htmlspecialchars($row['main_image']) ?>" alt="About Image" class="img-thumbnail border" style="max-width: 150px;">
              </td>
              <td class="action-btns">
                <a href="edit_about.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning me-2">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="delete_about.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this image?');">
                  <i class="fas fa-trash"></i> Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center text-muted py-4">No about images found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
function filterAboutTable() {
    const input = document.getElementById("aboutSearchInput").value.trim().toUpperCase();
    const rows = document.querySelectorAll("#aboutTableBody tr");

    rows.forEach(row => {
        const id = row.children[0].textContent.toUpperCase();
        row.style.display = id.includes(input) ? "" : "none";
    });
}

function handleAboutSearchKey(event) {
    if (event.key === "Enter") {
        filterAboutTable();
    }
}
</script>

</body>
</html>
