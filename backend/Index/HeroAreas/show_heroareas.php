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

if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
    $messageType = htmlspecialchars(urldecode($_GET['type']));
}

$webRootPrefix = '/Highbrows';
$heroAreasUploadDirWebRelative = $webRootPrefix . '/backend/uploads/heroareas/';

$heroQuery = "SELECT * FROM heroareas ORDER BY id DESC";
$heroResult = null;

if ($conn && !$conn->connect_error) {
    $heroResult = $conn->query($heroQuery);
    if (!$heroResult) {
        $message = "Error fetching hero area data: " . $conn->error;
        $messageType = "danger";
    }
} else {
    $message = "Database connection failed.";
    $messageType = "danger";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hero Area Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../../Includes/sidebar.css" />
  <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css" />
  <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css" />

  <style>
    td img {
        width: 100%;
        max-width: 120px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        border: 2px solid #b0c7b0;
        display: block;
        margin: 0 auto;
    }
    td video {
        width: 120px;
        height: 70px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        border: 2px solid #ccc;
        object-fit: cover;
        display: block;
        margin: 0 auto;
    }
  </style>
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>

<div class="main container py-4">
  <section class="flex-grow-1 d-flex flex-column">
    <header class="top-header mb-4 enhanced-shadow">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="d-flex align-items-start">
          <i class="fas fa-image fa-2x text-success me-3 mt-1"></i>
          <div>
            <h1 class="h4 fw-bold text-dark mb-1">Hero Area Management</h1>
            <small class="text-muted">View and manage hero section content from here.</small>
          </div>
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Hero Area</li>
          </ol>
        </nav>
      </div>
    </header>

    <?php
    if (!empty($message)) {
        echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show text-center" role="alert">'
           . $message .
           '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>

    <div class="content-header">
      <div class="left-info">
        <h2>Manage Hero Areas</h2>
        <p>Below is a list of all hero area content. You can edit, delete, or add new items here.</p>
      </div>
      <div class="action-area">
        <a href="insert_heroareas.php" class="btn-add">
          <i class="fas fa-plus"></i> Add Media
        </a>
      </div>
    </div>

    <div class="search-filter">
      <div class="search-box d-flex align-items-center">
        <input type="text" id="heroSearchInput" placeholder="Search by ID only" aria-label="Search by ID">
        <i class="bi bi-search text-success ms-2 fs-5" id="searchBtn" style="cursor: pointer;"></i>
      </div>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Video</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="heroareaTableBody">
          <?php
          if ($heroResult && $heroResult->num_rows > 0) {
              while ($row = $heroResult->fetch_assoc()) {
                  echo '<tr>';
                  echo '<td>' . htmlspecialchars($row['id']) . '</td>';

                  // Display Image
                  echo '<td>';
                  if (!empty($row['main_image'])) {
                      echo '<img src="' . htmlspecialchars($row['main_image']) . '" alt="Main Image">';
                  } else {
                      echo '<span class="text-muted">No image</span>';
                  }
                  echo '</td>';

                  // Display Video
                  echo '<td>';
                  if (!empty($row['video_content'])) {
                      echo '<video controls>';
                      echo '<source src="' . htmlspecialchars($row['video_content']) . '" type="video/mp4">';
                      echo 'Your browser does not support the video tag.';
                      echo '</video>';
                  } else {
                      echo '<span class="text-muted">No video</span>';
                  }
                  echo '</td>';

                  // Action buttons
                  echo '<td class="action-btns">';
                  echo '<a href="edit_heroareas.php?id=' . urlencode($row['id']) . '" title="Edit"><i class="fas fa-edit"></i> Edit</a>';
                  echo '<a href="delete_heroareas.php?id=' . urlencode($row['id']) . '" title="Delete" onclick="return confirm(\'Are you sure you want to delete this hero area?\');"><i class="fas fa-trash"></i> Delete</a>';
                  echo '</td>';

                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="4" class="text-center text-muted py-3">';
              echo !empty($message) ? $message : 'No hero areas found.';
              echo '</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('heroSearchInput');
    const button = document.getElementById('searchBtn');

    function filterById() {
        const filter = input.value.trim().toUpperCase();
        const tableBody = document.getElementById("heroareaTableBody");
        const tr = tableBody.getElementsByTagName("tr");

        let found = false;
        let noResultsRowId = 'no-results-row';
        let noResultsRow = document.getElementById(noResultsRowId);

        for (let i = 0; i < tr.length; i++) {
            if (tr[i].id === noResultsRowId) continue;
            const idCell = tr[i].getElementsByTagName("td")[0];
            if (idCell) {
                const idText = idCell.textContent || idCell.innerText;
                if (idText.toUpperCase() === filter || filter === "") {
                    tr[i].style.display = "";
                    found = true;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        if (!found && filter.length > 0) {
            if (!noResultsRow) {
                noResultsRow = tableBody.insertRow();
                noResultsRow.id = noResultsRowId;
                const cell = noResultsRow.insertCell();
                cell.colSpan = 4;
                cell.classList.add('text-center', 'text-muted', 'py-3');
                cell.textContent = 'No matching hero areas found.';
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }

    button.addEventListener('click', filterById);
    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            filterById();
        }
    });
});
</script>

</body>
</html>


