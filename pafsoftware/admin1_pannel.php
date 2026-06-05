<?php

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch admin data (if needed)
$admin_id = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
       <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <!-- Initial content or default page -->
            <header>
                <h1>Welcome to the Admin Panel</h1>
            </header>
            <section style="display:flex; width:90%; margin: auto; align-items: center; justify-content: space-between; flex-wrap: wrap; " >
                <h2>Dashboard Overview</h2>
                <a class="nav-link"  style="text-decoration:none; border: none; padding:12px 16px; background-color:rgb(27, 167, 253); color:white; border-radius:12px; " href="logout.php">Logout</a>
            </section>
            <div class="main2">
                <!-- Additional content can go here -->
            </div>
        </div>
    </div>

    <script>
        function loadPage(page) {
            const mainContent = document.getElementById('main-content');
            fetch(`${page}.html`)
                .then(response => response.text())
                .then(data => {
                    mainContent.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    mainContent.innerHTML = '<p>Error loading page.</p>';
                });
        }
    </script>
</body>
</html>
