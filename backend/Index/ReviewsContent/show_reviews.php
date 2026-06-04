<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
  include '../preloader.php';
// Fetch reviews content data
$query = "SELECT * FROM review_content ORDER BY id DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<?php
function getPopupLink($url, $videoFile, $uploadDir) {
    if (!empty($url)) {
        $url = strtok($url, '?'); // remove ?si or other params

        // YouTube (short & full)
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            if (preg_match('#youtu\.be/([a-zA-Z0-9_-]{11})#', $url, $matches) || preg_match('/v=([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }

        // Vimeo
        if (strpos($url, 'vimeo.com') !== false) {
            if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }

        return $url; // direct .mp4 or any other supported video URL
    }

    // If URL is empty, fallback to uploaded file
    if (!empty($videoFile)) {
        return $uploadDir . $videoFile;
    }

    return ''; // nothing found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reviews Content - All Reviews</title>
 
   


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="/backend/Index/HeroAreas/css/index.css">
    <link rel="stylesheet" href="/backend/Includes/sidebar.css">
    <link rel="stylesheet" href="/backend/Index/HeroAreas/css/heroareas.css">
    
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>

<div class="main">
  <section class="container-fluid p-4">

    <!-- Header -->
    <header class="top-header mb-4 enhanced-shadow">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-center gap-3">
        <div class="d-flex align-items-start">
          <i class="fas fa-image fa-2x text-success me-3 mt-1"></i>
          <div>
            <h1 class="h4 fw-bold text-dark mb-1">Reviews Content Management</h1>
            <small class="text-muted">View and manage customer reviews content from here.</small>
          </div>
        </div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item">
              <a href="#" class="text-decoration-none text-success fw-semibold">Home</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Reviews Content</li>
          </ol>
        </nav>
      </div>
    </header>

    <!-- Top Info & Add Button -->
    <div class="content-header d-flex justify-content-between align-items-center mb-3">
      <div class="left-info">
        <h2>Manage Customer Reviews</h2>
        <p>Below is a list of all customer reviews. You can edit, delete, or add new reviews here.</p>
      </div>
      <div class="action-area">
        <a href="add_review.php" class="btn-add"><i class="fas fa-plus"></i> Add Review</a>
      </div>
    </div>

    <!-- Search Filter -->
    <div class="search-filter mb-3">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search reviews..." aria-label="Search reviews" />
        <i class="bi bi-search text-success ms-2" id="searchBtn" style="cursor:pointer;"></i>
      </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
      <table class="table-wrappere">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Thumbnail</th>
            <th>Main Video</th>
            <th>Description 2</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="reviewsTableBody">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>

                <!-- Thumbnail -->
                <td class="text-center">
                  <?php
                    $thumb = $row['thumbnail'];
                    $thumbPath = '/backend/Index/ReviewsContent/uploads/reviews/' . $thumb;
                    $filePath = $_SERVER['DOCUMENT_ROOT'] . $thumbPath;
                  ?>
                  <?php if (!empty($thumb) && file_exists($filePath)): ?>
                    <img src="<?= $thumbPath ?>" width="100" height="70" class="img-thumbnail" alt="Thumbnail">
                  <?php else: ?>
                    <img src="/backend/Assets/video-thumbnail.jpg" width="100" height="70" class="img-thumbnail" alt="Default Thumbnail">
                  <?php endif; ?>
                </td>

                <!-- Main Video OR Video URL -->
    <td class="text-center">
  <?php
    $videoFile = $row['main_video'] ?? '';
    $videoUrl  = $row['video_url'] ?? '';
    $uploadPath = '/backend/Index/ReviewsContent/uploads/reviews/';

    $embedLink = getPopupLink($videoUrl, $videoFile, $uploadPath);
    $isYouTubeEmbed = strpos($embedLink, 'youtube.com/embed') !== false || strpos($embedLink, 'youtu.be/embed') !== false;
    $isVimeoEmbed = strpos($embedLink, 'vimeo.com') !== false;
    $isIframe = $isYouTubeEmbed || $isVimeoEmbed;
  ?>

  <?php if (!empty($embedLink)): ?>
    <?php if ($isIframe): ?>
      <iframe width="160" height="90" src="<?= htmlspecialchars($embedLink) ?>" frameborder="0" allowfullscreen style="border-radius: 6px;"></iframe>
    <?php else: ?>
      <video controls width="160" style="border-radius: 6px;">
        <source src="<?= htmlspecialchars($embedLink) ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    <?php endif; ?>
  <?php else: ?>
    <span class="text-muted">No video</span>
  <?php endif; ?>
</td>



                <td><?= htmlspecialchars($row['description2']) ?></td>

                <!-- Actions -->
                 <td class="action-btns" data-label="Actions">
                                    <a href="edit_review.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_review.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this service?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No reviews found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/backend/Includes/sidebar.js"></script>
<script>
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');
  const reviewsTableBody = document.getElementById('reviewsTableBody');

  function filterTable() {
    const filter = searchInput.value.toLowerCase();
    Array.from(reviewsTableBody.children).forEach(row => {
      const idCell = row.children[0]?.textContent.toLowerCase() || '';
      const titleCell = row.children[1]?.textContent.toLowerCase() || '';
      const shouldShow = idCell.includes(filter) || titleCell.includes(filter);
      row.style.display = shouldShow ? '' : 'none';
    });
  }

  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      filterTable();
    }
  });

  searchBtn.addEventListener('click', filterTable);
</script>

</body>
</html>
