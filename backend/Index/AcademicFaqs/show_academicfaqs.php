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

if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed in show_academicfaqs.php: " . ($conn->connect_error ?? 'Connection object not found'));
    $message = "ERROR: Database connection failed. Please try again later or contact support.";
    $messageType = "danger";
}

$faqQuery = "SELECT id, title, description FROM `academic_faqs` ORDER BY id DESC";
$faqResult = null;
if ($conn && !$conn->connect_error) {
    $faqResult = $conn->query($faqQuery);
    if (!$faqResult) {
        error_log("Database query failed in show_academicfaqs.php: " . $conn->error);
        $message = "Error fetching FAQ data: " . $conn->error;
        $messageType = "danger";
    }
} else {
    $message = "Database connection not available. Cannot display FAQs.";
    $messageType = "danger";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Academic FAQs Admin</title>

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
    <section class="flex-grow-1 d-flex flex-column">

        <header class="top-header mb-4 enhanced-shadow">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-question-circle fa-2x text-info me-3 mt-1"></i>
                    <div>
                        <h1 class="h4 fw-bold text-dark mb-1">Academic FAQs Management</h1>
                        <small class="text-muted">Manage frequently asked questions related to academics.</small>
                    </div>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a href="../../Index/index.php" class="text-decoration-none text-info fw-semibold">Home</a>
                        </li>
                        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Academic FAQs</li>
                    </ol>
                </nav>
            </div>
        </header>

        <?php
        if (!empty($message)) {
            echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show text-center" role="alert">' . htmlspecialchars($message) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        ?>

        <div class="content-header">
            <div class="left-info">
                <h2>Manage Academic FAQs</h2>
                <p>Below is a list of all frequently asked questions. You can edit, delete, or add new entries here.</p>
            </div>
            <div class="action-area">
                <a href="add_academicfaqs.php" class="btn-add">
                    <i class="fas fa-plus"></i> Add FAQ
                </a>
            </div>
        </div>

        <div class="search-filter">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search FAQs..." aria-label="Search FAQs" />
                <i class="bi bi-search text-info ms-2"></i>
            </div>
        </div>

        <div class="table-wrapper">
            <table aria-describedby="academic-faqs-table-description">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="faqTableBody">
                    <?php
                    if ($faqResult && $faqResult->num_rows > 0) {
                        while ($row = $faqResult->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                            echo '<td>' . htmlspecialchars(substr($row['description'], 0, 150)) . (strlen($row['description']) > 150 ? '...' : '') . '</td>';
                            echo '<td class="action-btns">';
                            echo '<a href="edit_academicfaqs.php?id=' . urlencode($row['id']) . '" title="Edit"><i class="fas fa-edit"></i> Edit</a>';
                            echo '<a href="delete_academicfaqs.php?id=' . urlencode($row['id']) . '" title="Delete" onclick="return confirm(\'Are you sure you want to delete this FAQ entry?\');"><i class="fas fa-trash"></i> Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center text-muted py-3">';
                        if (!empty($message) && $messageType === 'danger') {
                            echo $message;
                        } else {
                            echo 'No academic FAQs found.';
                        }
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../Includes/sidebar.js"></script>
<script>
function filterTable() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toUpperCase();
    var tableBody = document.getElementById("faqTableBody");
    var tr = tableBody.getElementsByTagName("tr");

    let found = false;
    let noResultsRowId = 'no-results-row';
    let noResultsRow = document.getElementById(noResultsRowId);

    for (let i = 0; i < tr.length; i++) {
        if (tr[i].id === noResultsRowId) continue;

        var cells = tr[i].getElementsByTagName("td");
        var match = false;

        if (cells[1] && cells[2]) {
            let txtValue = (cells[1].textContent || cells[1].innerText) + " " +
                           (cells[2].textContent || cells[2].innerText);
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                match = true;
            }
        }

        tr[i].style.display = match ? "" : "none";
        if (match) found = true;
    }

    // Show/hide no-results row
    if (!found && filter.length > 0) {
        if (!noResultsRow) {
            noResultsRow = tableBody.insertRow();
            noResultsRow.id = noResultsRowId;
            const cell = noResultsRow.insertCell();
            cell.colSpan = 4;
            cell.className = 'text-center text-muted py-3';
            cell.textContent = 'No matching FAQs found.';
        }
        noResultsRow.style.display = '';
    } else {
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
}

// Trigger filter on Enter key
document.getElementById("searchInput").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        filterTable();
    }
});

// Trigger filter on search icon click
document.querySelector(".search-box i").addEventListener("click", function() {
    filterTable();
});

document.addEventListener('DOMContentLoaded', function () {
    const alertElement = document.querySelector('.alert');
    if (alertElement && !alertElement.classList.contains('alert-danger')) {
        setTimeout(() => {
            const bootstrapAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
            bootstrapAlert.close();
        }, 7000);
    }
});
</script>

</body>
</html>
