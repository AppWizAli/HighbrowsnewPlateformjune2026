<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include "config.php";

// Fetch tests from the database
$tests = [];
$query = "SELECT id, test_name FROM tests";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tests[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Welcome to the Admin Panel</h1>
            </header>

            <section class="main-session">
                <h2>Add Subject to Test</h2>
            </section>

            <div class="main2">
                <section>
                    <div class="container mt-4">
                        <!-- Dropdown to select test -->
                        <form id="test-form" method="post" action="insert_subjects.php">
                            <div class="form-group">
                                <label for="testSelect">Select Test:</label>
                                <select id="testSelect" name="test_id" class="form-control" required>
                                    <option value="">Select a Test</option>
                                    <?php foreach ($tests as $test): ?>
                                        <option value="<?= $test['id']; ?>"><?= htmlspecialchars($test['test_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Form to generate subject input fields -->
                            <div class="form-group">
                                <label for="subjectCount">Number of Subjects to Add:</label>
                                <input type="number" id="subjectCount" class="form-control" min="1" required>
                            </div>
                            <button type="button" id="generateForm" class="btn btn-primary">Add Subjects</button>

                            <!-- Form to submit subjects and times -->
                            <div id="subjectInputs"></div>
                            <button type="submit" class="btn btn-success mt-4">Submit</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function() {
        $('#generateForm').click(function() {
            const subjectCount = $('#subjectCount').val();
            const subjectInputs = $('#subjectInputs');
            subjectInputs.empty();

            // Dynamically add subject name and time fields for each subject
            for (let i = 1; i <= subjectCount; i++) {
                const inputGroup = $(`
                    <div class="form-group">
                        <label for="subject${i}">Subject ${i}:</label>
                        <input type="text" id="subject${i}" name="subjects[]" class="form-control mb-2" placeholder="Enter subject name" required>

                        <label for="time${i}">Time (in minutes):</label>
                        <input type="number" id="time${i}" name="times[]" class="form-control mb-2" placeholder="Enter time in minutes" min="1" required>
                    </div>
                `);
                subjectInputs.append(inputGroup);
            }
        });
    });
    </script>
</body>
</html>
