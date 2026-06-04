<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../Index/preloader.php';
include '../Database/config.php';
$result = $conn->query("SELECT * FROM contact_users ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact Submissions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Includes/sidebar.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/index.css">

    
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
    <header class="top-header mb-4 enhanced-shadow">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start">
                <i class="fas fa-envelope fa-2x text-success me-3 mt-1"></i>
                <div>
                    <h1 class="h4 fw-bold text-dark mb-1">Contact Submissions</h1>
                    <small class="text-muted">Manage all user messages submitted through the contact form.</small>
                </div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="../Index/index.php" class="text-decoration-none text-success fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Contact Users</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="search-filter px-2">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by ID or Name" />
            <i class="bi bi-search" onclick="filterTable()"></i>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table-wrapper" id="contactTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>WhatsApp</th> <!-- ✅ New Column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../Database/config.php';
                $query = "SELECT * FROM contact_users ORDER BY id DESC";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $cleanNumber = preg_replace('/[^0-9]/', '', $row['number']); // ✅ Remove symbols
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['number']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td><?= $row['submitted_at'] ?></td>
                        <td>
                            <?php if (!empty($cleanNumber)): ?>
                                                          <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $row['number']) ?>" target="_blank" class="text-success">
    <i class="fab fa-whatsapp fa-lg" style="font-size: 27px;"></i>
</a>

                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-btns">
                            <a class="delete" href="delete_contact.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this record?')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr><td colspan="8">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.trim().toLowerCase();
    const table = document.getElementById("contactTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        const idCell = rows[i].getElementsByTagName("td")[0];
        const nameCell = rows[i].getElementsByTagName("td")[1];
        const idText = idCell.textContent || idCell.innerText;
        const nameText = nameCell.textContent || nameCell.innerText;

        if (idText.toLowerCase().includes(filter) || nameText.toLowerCase().includes(filter)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}

document.getElementById("searchInput").addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        filterTable();
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script>
</body>
</html>
