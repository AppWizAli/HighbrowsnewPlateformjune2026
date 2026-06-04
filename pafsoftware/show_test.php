<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Replace with your actual DB connection file

// Delete test if delete_id is set
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM tests WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch tests for displaying in the table
$tests_query = "SELECT * FROM tests";
$tests_result = $conn->query($tests_query);

// Fetch data to edit if edit_id is set
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = "SELECT * FROM tests WHERE id = ?";
    $stmt = $conn->prepare($edit_query);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    $edit_data = $edit_result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Tests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Manage Tests</h1>
            </header>
            
            <!-- List of tests -->
            <section>
                <h2 class="mt-4">All Tests</h2>
                <div class="container mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Test ID</th>
                                <th>Test Name</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($tests_result && $tests_result->num_rows > 0) {
                                while ($test_row = $tests_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($test_row['test_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($test_row['test_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($test_row['date_added']) . "</td>";
                                    echo "<td>";
                                    echo "<form method='POST' style='display:inline-block;' action=''>";
                                    echo "<input type='hidden' name='delete_id' value='" . htmlspecialchars($test_row['id']) . "'>";
                                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                                    echo "</form> ";
                                    echo "<a href='?edit=" . htmlspecialchars($test_row['id']) . "' class='btn btn-primary btn-sm'>Edit</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No tests found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if ($edit_data) : ?>
            <!-- Edit Test Form -->
            <div class="edit-form container mt-5">
                <h3>Edit Test</h3>
                <form method="POST" action="update_test.php">
                    <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($edit_data['id']); ?>">
                    <div class="form-group">
                        <label for="edit_test_name">Test Name</label>
                        <input type="text" name="edit_test_name" id="edit_test_name" class="form-control" value="<?php echo htmlspecialchars($edit_data['test_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_date_added">Date Added</label>
                        <input type="date" name="edit_date_added" id="edit_date_added" class="form-control" value="<?php echo htmlspecialchars($edit_data['date_added']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">Update Test</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
