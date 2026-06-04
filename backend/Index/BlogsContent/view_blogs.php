<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../../Database/config.php'; // ✅ Corrected path
  include '../preloader.php';
// Fetch Blogs
$sql = "SELECT * FROM blogs_content ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Blog Management - Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">
    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">

    <style>
 
      
        /* Image styling inside table (instead of video) */
        td img {
            width: 100%;
            max-width: 120px; /* Adjusted size for images */
            height: 70px; /* Fixed height for consistency */
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            border: 2px solid #b0c7b0;
            display: block;
            margin: 0 auto;
        }

      
    </style>
</head>
<body>

<?php include '../../Includes/sidebar.php'; ?>

<div class="main">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-blog fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Blog Content Management</h1>
                    <small class="text-muted">View and manage blog entries from here.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Blogs</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php
    if (isset($_GET['message'])) {
        $message = htmlspecialchars($_GET['message']);
        $alertClass = strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger';
        echo '<div class="alert ' . $alertClass . ' mt-3" role="alert">' . $message . '</div>';
    }
    ?>

    <div class="content-header">
        <div class="left-info">
            <h2>Manage Blog Posts</h2>
            <p>Below is a list of all blog posts. You can edit, delete, or add new posts here.</p>
        </div>
        <div class="action-area">
            <a href="add_blog.php" class="btn-add">
                <i class="fas fa-plus"></i> Add New Blog
            </a>
        </div>
    </div>

    <div class="search-filter ">
        <div class="search-box ">
           <input type="text" id="searchInput" placeholder="Search by ID or Title..." aria-label="Search blogs" onkeypress="handleSearchKey(event)">

            <i class="bi bi-search"></i>
        </div>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table aria-describedby="blog-table-description" id="blogTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Main Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td>
                                    <?php if (!empty($row['main_image'])): ?>
                                        <img src="/backend/uploads/blogs/<?= htmlspecialchars($row['main_image']); ?>" alt="Main Blog Image">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars(substr($row['description'], 0, 100)) . (strlen($row['description']) > 100 ? '...' : ''); ?></td>
                                <td><?= htmlspecialchars($row['category_name']); ?></td>
                                <td class="action-btns">
                                    <a class="edit" href="edit_blog.php?id=<?= $row['id']; ?>" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a class="delete" href="delete_blog.php?id=<?= $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this blog entry?');"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No blog content found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    function handleSearchKey(e) {
        if (e.key === 'Enter') {
            filterTable();
        }
    }

    function filterTable() {
        var input = document.getElementById("searchInput");
        var filter = input.value.toUpperCase();
        var table = document.getElementById("blogTable");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) { // start from 1 to skip header
            var tdId = tr[i].getElementsByTagName("td")[0]; // ID column
            var tdTitle = tr[i].getElementsByTagName("td")[2]; // Title column
            var show = false;

            if (tdId && tdId.textContent.toUpperCase().indexOf(filter) > -1) {
                show = true;
            }

            if (tdTitle && tdTitle.textContent.toUpperCase().indexOf(filter) > -1) {
                show = true;
            }

            tr[i].style.display = show ? "" : "none";
        }
    }
</script>

</body>
</html>

<?php $conn->close(); ?>