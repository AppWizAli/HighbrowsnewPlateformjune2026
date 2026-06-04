<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}


include '../Index/preloader.php';
include '../Database/config.php';
$result = $conn->query("SELECT * FROM pricing_manage ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pricing Plans - Manage</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="../Index/HeroAreas/css/index.css">
  <link rel="stylesheet" href="../Includes/sidebar.css">
  <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
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
          <i class="fas fa-dollar-sign fa-2x text-primary me-3 mt-1"></i>
          <div>
            <h1 class="h4 fw-bold text-dark mb-1">Pricing Management</h1>
            <small class="text-muted">View, add, edit or remove pricing plans here.</small>
          </div>
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item">
              <a href="../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Pricing</li>
          </ol>
        </nav>
      </div>
    </header>

    <div class="content-header d-flex justify-content-between align-items-center mb-3">
      <div class="left-info">
        <h2>Manage Your Pricing Plans</h2>
        <p>List of all pricing packages. Edit, delete or add new plans as needed.</p>
      </div>
      <div class="action-area">
        <a href="add_pricing.php" class="btn-add"><i class="fas fa-plus"></i> Add New Plan</a>
      </div>
    </div>

    <div class="search-filter mb-3">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search by title or price..." aria-label="Search pricing" />
        <i class="bi bi-search text-success ms-2"></i>
      </div>
    </div>

    <div class="table-wrapper">
      <table aria-describedby="pricing-table-description">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Price</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="pricingTableBody">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                <td data-label="Title"><?= htmlspecialchars($row['title']) ?></td>
                <td data-label="Price">Rs<?= htmlspecialchars($row['price']) ?></td>
                <td data-label="Description"><?= htmlspecialchars(substr($row['description'], 0, 100)) ?><?= strlen($row['description']) > 100 ? '...' : '' ?></td>
                <td class="action-btns" data-label="Actions">
                  <a href="edit_pricing.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a href="delete_pricing.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this pricing plan?');">
                    <i class="fas fa-trash-alt"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted py-4">No pricing plans found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script>
<script>
function filterPricingTable() {
  const input = document.getElementById("searchInput");
  const filter = input.value.trim().toUpperCase();
  const tableBody = document.getElementById("pricingTableBody");
  const tr = tableBody.getElementsByTagName("tr");

  let found = false;
  const noResultsRowId = 'no-results-row';
  let noResultsRow = document.getElementById(noResultsRowId);

  for (let i = 0; i < tr.length; i++) {
    if (tr[i].id === noResultsRowId) continue;

    const titleCell = tr[i].getElementsByTagName("td")[1];
    const priceCell = tr[i].getElementsByTagName("td")[2];

    const titleText = titleCell ? titleCell.textContent.toUpperCase() : "";
    const priceText = priceCell ? priceCell.textContent.toUpperCase() : "";

    if (titleText.includes(filter) || priceText.includes(filter)) {
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
      cell.textContent = 'No matching pricing plans found.';
    }
    noResultsRow.style.display = '';
  } else {
    if (noResultsRow) {
      noResultsRow.style.display = 'none';
    }
  }
}

document.getElementById("searchInput").addEventListener("keydown", function(e) {
  if (e.key === "Enter") {
    e.preventDefault();
    filterPricingTable();
  }
});

document.querySelector(".bi-search").addEventListener("click", function() {
  filterPricingTable();
});
</script>

</body>
</html>
