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
                <div style="display:flex; gap:20px; flex-wrap:wrap; width:90%; margin:20px auto;">
                    <a href="register.php" target="_blank" style="text-decoration:none; min-width:240px; flex:1; border:1px solid #d9e3f0; border-radius:16px; padding:22px; background:#fff; color:#111827; box-shadow:0 8px 24px rgba(15, 23, 42, 0.08);">
                        <h3 style="margin:0 0 10px 0;">Student Register</h3>
                        <p style="margin:0; color:#4b5563;">Open the student registration page in a new tab.</p>
                    </a>
                    <a href="userlogin.php" target="_blank" style="text-decoration:none; min-width:240px; flex:1; border:1px solid #d9e3f0; border-radius:16px; padding:22px; background:#fff; color:#111827; box-shadow:0 8px 24px rgba(15, 23, 42, 0.08);">
                        <h3 style="margin:0 0 10px 0;">Student Login</h3>
                        <p style="margin:0; color:#4b5563;">Open the student login page in a new tab.</p>
                    </a>
                </div>
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
