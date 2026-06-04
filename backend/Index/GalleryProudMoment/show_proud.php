<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
  include '../preloader.php';
// Check for database connection error before proceeding with query
if (!$conn) {
    // Log the error for server-side debugging
    error_log("Database connection failed in show_proud.php: " . mysqli_connect_error());
    // Set a message to display on the page
    $errorMessage = "Database connection failed. Please try again later or contact support.";
    $query = false; // Indicate that query could not be executed
} else {
    $query = mysqli_query($conn, "SELECT * FROM proud_moment ORDER BY id DESC");
    if (!$query) {
        error_log("Query failed in show_proud.php: " . mysqli_error($conn));
        $errorMessage = "Error retrieving proud moments. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proud Moments Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <link rel="stylesheet" href="../../Includes/sidebar.css" />
    <link rel="stylesheet" href="../../Index/HeroAreas/css/heroareas.css" />
    <link rel="stylesheet" href="../../Index/HeroAreas/css/index.css" />

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
    <section class="container-fluid flex-grow-1 d-flex flex-column p-3">
        <header class="top-header mb-4 enhanced-shadow">
            <div class="container-fluid px-4"> <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    
                    <div class="d-flex align-items-start">
                        <i class="fas fa-star fa-2x text-success me-3 mt-1"></i>
                        <div>
                            <h1 class="h4 fw-bold text-dark mb-1">Proud Moments Management</h1>
                            <small class="text-muted">Manage and celebrate your proudest achievements here.</small>
                        </div>
                    </div>
                    
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item">
                                <a href="/Highbrows/backend/Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a>
                            </li>
                            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Proud Moments</li>
                        </ol>
                    </nav>
                    
                </div>
            </div>
        </header>


        <div class="content-header">
            <div class="left-info">
                <h2>Manage Proud Moments</h2>
                <p>Below is a list of all proud moments. You can edit, delete, or add new entries here.</p>
            </div>
            <div class="action-area">
                <a href="insert_proud.php" class="btn-add"><i class="fas fa-plus"></i> Add Gallery </a>
            </div>
        </div>

     <div class="search-filter mb-4">
    <div class="search-box d-flex align-items-center">
        <input type="text" id="searchInput" class="form-control me-2" placeholder="Search by ID or Title..." aria-label="Search">
        <i class="bi bi-search text-success fs-5" id="searchBtn" style="cursor: pointer;"></i>
    </div>
</div>


        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="proudTable">
                    <?php
                    if ($query && mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td data-label='ID'>" . htmlspecialchars($row['id']) . "</td>";
                            // Corrected image path: Removed leading '/' to make it relative to the current file
                            echo '<td data-label="Image"><img src="uploads/proud_moments/' . htmlspecialchars($row['main_image']) . '" alt="' . htmlspecialchars($row['title']) . '"></td>';
                            echo "<td data-label='Title'>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td data-label='Description'>" . htmlspecialchars($row['description']) . "</td>";
                            echo '<td data-label="Actions" class="action-btns">
                                    <a href="edit_proud_moment.php?id=' . $row['id'] . '" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="delete_proud.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this item?\')" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                </td>';
                            echo "</tr>";
                        }
                    } else if ($query && mysqli_num_rows($query) === 0) {
                        echo '<tr><td colspan="5" class="text-center text-muted">No proud moments found.</td></tr>';
                    } else {
                       
                        echo '<tr><td colspan="5" class="text-center text-danger">Failed to load proud moments.</td></tr>';
                    }
                    mysqli_close($conn); // Close the database connection
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const tableBody = document.getElementById('proudTable');
    const initialRows = Array.from(tableBody.querySelectorAll('tr')).filter(row => !row.id.includes('no-results-row'));

    function filterTable() {
        const filter = searchInput.value.trim().toLowerCase();
        let found = false;

        initialRows.forEach(row => {
            const id = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase();
            const title = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();

            if ((id && id.includes(filter)) || (title && title.includes(filter)) || filter === "") {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        // No Results Handling
        const noResultsRowId = 'no-results-row';
        let noResultsRow = document.getElementById(noResultsRowId);

        if (!found && filter.length > 0) {
            if (!noResultsRow) {
                noResultsRow = tableBody.insertRow();
                noResultsRow.id = noResultsRowId;
                const cell = noResultsRow.insertCell();
                cell.colSpan = 5;
                cell.classList.add('text-center', 'text-muted', 'py-3');
                cell.textContent = 'No matching proud moments found.';
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }

    // Trigger on Enter key
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            filterTable();
        }
    });

    // Trigger on search icon click
    searchBtn.addEventListener('click', filterTable);
</script>

</body>
</html>