<?php

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Add New Test</h1>
            </header>
            <section class="main-session">
                <h2>Add Test Details</h2>
            </section>
            <div class="main2">
            <section>
                <div class="container mt-4">
                    <!-- Form to add test details -->
                    <form id="test-form" method="post" action="insert_test.php">
                        <div class="form-group">
                            <label for="testId">Test ID:</label>
                            <input type="text" id="testId" name="test_id" class="form-control mb-2" placeholder="Enter test ID" required>
                        </div>

                        <div class="form-group">
                            <label for="testName">Test Name:</label>
                            <input type="text" id="testName" name="test_name" class="form-control mb-2" placeholder="Enter test name" required>
                        </div>

                        <div class="form-group">
                            <label for="dateAdded">Date Added:</label>
                            <input type="date" id="dateAdded" name="date_added" class="form-control mb-2" required>
                        </div>

                        <button type="submit" class="btn btn-success mt-4">Add Test</button>
                    </form>
                </div>
            </section>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</body>
</html>
