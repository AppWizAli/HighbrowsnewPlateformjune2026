<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php'); // Corrected path to login.php based on your structure
    exit();
}
include '../../Database/config.php'; // Corrected path
  include '../preloader.php';
$message = '';
// Check for success/error messages passed from other pages (e.g., after editing)
if (isset($_GET['success'])) {
    $displayMessage = htmlspecialchars(urldecode($_GET['success']));
    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $displayMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
} elseif (isset($_GET['error'])) {
    $displayMessage = htmlspecialchars(urldecode($_GET['error']));
    $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $displayMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
} elseif (isset($_GET['message']) && isset($_GET['type'])) {
    // This part is for generic messages, can be combined with success/error above or kept separate
    $displayMessage = htmlspecialchars(urldecode($_GET['message']));
    $displayMessageType = htmlspecialchars(urldecode($_GET['type']));
    $message = '<div class="alert alert-' . $displayMessageType . ' alert-dismissible fade show" role="alert">' . $displayMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}


// --- Database Connection Check ---
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in view_cadet_moments.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message .= '<div class="alert alert-danger" role="alert">
                    <strong>ERROR:</strong> Database connection failed. Please try again later or contact support.
                   </div>';
    $result = null;
} else {
    $sql = "SELECT id, main_image, main_image2, title, description FROM cadet_moments ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        $message .= '<div class="alert alert-danger" role="alert">
                            <strong>Database Error:</strong> Could not retrieve cadet moments. Please check your database table (`cadet_moments`) and column names. SQL Error: ' . $conn->error . '
                           </div>';
        $result = null;
    }
    $conn->close();
}

$baseUploadDirServerPath = dirname(__DIR__, 2) . '/uploads/cadet_moments/';


$baseUploadDirWebPath = '../../uploads/cadet_moments/';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Cadet Moments</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../../Includes/sidebar.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css">

    <style>
        /* Image styling inside table */
        td img {
            width: 100%;
            max-width: 100px; /* Adjusted size for images */
            height: 60px; /* Fixed height for consistency */
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

<?php
$sidebarPath = '../../Includes/sidebar.php';
if (file_exists($sidebarPath)) {
    include $sidebarPath;
} else {
    echo "<p class='text-danger p-3'>Sidebar file not found: $sidebarPath</p>";
}
?>

<div class="main">
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-list fa-2x text-info me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Cadet Moments List</h1>
                    <small class="text-muted">Manage all cadet moments.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Cadet Moments</li>
                </ol>
            </nav>
        </div>
    </header>

    <?php echo $message; // Display success/error messages ?>

    <div class="content-header">
        <div class="left-info">
            <h2>Manage Cadet Moments</h2>
            <p>Below is a list of all cadet moments. You can edit, delete, or add new moments here.</p>
        </div>
        <div class="action-area">
            <a href="add_cadet_moment.php" class="btn-add">
                <i class="fas fa-plus"></i> Add Moment
            </a>
        </div>
    </div>

<div class="search-box">
    <input type="text" id="searchInput" placeholder="Search cadet moments..." aria-label="Search cadet moments" />
    <i class="bi bi-search" id="searchIcon" style="cursor:pointer;"></i>
</div>


    <div class="table-container">
        <div class="table-wrapper">
            <table aria-describedby="cadet-moments-table-description" id="cadetMomentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Main Image</th>
                        <th>Second Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()):
                            // Construct full server path for file_exists() check
                            // This now correctly points to the 'uploads' folder relative to your backend root.
                            $mainImageFullPath = $baseUploadDirServerPath . $row['main_image'];
                            $mainImage2FullPath = $baseUploadDirServerPath . $row['main_image2'];

                            // Construct full web path for <img> src
                            // This now correctly points to the 'uploads' folder relative to your backend root.
                            $mainImageWebPath = $baseUploadDirWebPath . $row['main_image'];
                            $mainImage2WebPath = $baseUploadDirWebPath . $row['main_image2'];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . (strlen($row['description']) > 100 ? '...' : ''); ?></td>
                                <td>
                                    <?php if (!empty($row['main_image']) && file_exists($mainImageFullPath)): ?>
                                        <img src="<?php echo htmlspecialchars($mainImageWebPath); ?>" alt="Main Image">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['main_image2']) && file_exists($mainImage2FullPath)): ?>
                                        <img src="<?php echo htmlspecialchars($mainImage2WebPath); ?>" alt="Second Image">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td class="action-btns">
                                    <a class="edit" href="edit_cadet_moment.php?id=<?php echo $row['id']; ?>" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                                    <a class="delete" href="delete_cadet_moment.php?id=<?php echo $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this cadet moment? This action cannot be undone.');"><i class="fas fa-trash-alt"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No cadet moments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("searchInput");
        const searchIcon = document.getElementById("searchIcon");
        const table = document.getElementById("cadetMomentsTable");
        const rows = table.getElementsByTagName("tr");

        function filterTable() {
            const filter = input.value.toUpperCase();

            for (let i = 1; i < rows.length; i++) { // Start at 1 to skip header row
                const cells = rows[i].getElementsByTagName("td");

                if (cells.length > 0) {
                    const id = cells[0].textContent || cells[0].innerText;
                    const title = cells[1].textContent || cells[1].innerText;

                    if (
                        id.toUpperCase().indexOf(filter) > -1 ||
                        title.toUpperCase().indexOf(filter) > -1
                    ) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }

        // Trigger on Enter key
        input.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                filterTable();
            }
        });

        // Trigger on search icon click
        searchIcon.addEventListener("click", filterTable);
    });
</script>

</body>
</html>