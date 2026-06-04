<?php
include 'config.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Check if there are any related questions
    $check_sql = "SELECT COUNT(*) as count FROM questions WHERE subject_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Delete all related questions
        $delete_questions_sql = "DELETE FROM questions WHERE subject_id = ?";
        $stmt = $conn->prepare($delete_questions_sql);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            echo "<script>alert('All related questions deleted successfully.');</script>";
        } else {
            echo "<script>alert('Error deleting related questions.');</script>";
        }
    }

    // Proceed with deletion of the subject
    $delete_subject_sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($delete_subject_sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Subject deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting subject');</script>";
    }

    $stmt->close();
}

// Handle the edit request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $edit_name = $_POST['edit_name'];
    $edit_time = $_POST['edit_time'];

    // Update the subject in the database
    $update_sql = "UPDATE subjects SET name = ?, time_in_minutes = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sii", $edit_name, $edit_time, $edit_id);

    if ($stmt->execute()) {
        // Redirect to the same page without the 'edit' query parameter
        echo "<script>alert('Subject updated successfully'); window.location.href='show-subject.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating subject');</script>";
    }

    $stmt->close();
}

// Fetch all tests from the database
$test_sql = "SELECT id, test_name, date_added FROM tests";
$test_result = $conn->query($test_sql);

// Fetch subjects based on the selected test
$test_id = isset($_POST['test_id']) ? $_POST['test_id'] : '';
$subjects_result = null;

if (!empty($test_id)) {
    $subject_sql = "SELECT id, name, time_in_minutes FROM subjects WHERE test_id = ?";
    $subject_stmt = $conn->prepare($subject_sql);
    $subject_stmt->bind_param("i", $test_id);
    $subject_stmt->execute();
    $subjects_result = $subject_stmt->get_result();
}

// Fetch data for editing if edit is requested
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_subject_id = $_GET['edit'];
    $edit_subject_sql = "SELECT id, name, time_in_minutes FROM subjects WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_subject_sql);
    $edit_stmt->bind_param("i", $edit_subject_id);
    $edit_stmt->execute();
    $edit_data = $edit_stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Welcome to the Admin Panel</h1>
            </header>
            <section>
                <h2>All Subjects</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="testSelect">Select Test</label>
                        <select name="test_id" id="testSelect" class="form-control" onchange="this.form.submit()">
                            <option value="">Select a test</option>
                            <?php
                            if ($test_result->num_rows > 0) {
                                while ($test_row = $test_result->fetch_assoc()) {
                                    $selected = ($test_id == $test_row['id']) ? 'selected' : '';
                                    echo "<option value='" . $test_row['id'] . "' $selected>" . $test_row['test_name'] . " (Added on: " . $test_row['date_added'] . ")</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </section>

            <div class="main2">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Subject Name</th>
                            <th>Time for Subject (minutes)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($subjects_result && $subjects_result->num_rows > 0) {
                            // Output data for each subject
                            while ($subject_row = $subjects_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $subject_row['name'] . "</td>";
                                echo "<td>" . $subject_row['time_in_minutes'] . "</td>";
                                echo "<td>";
                                echo "<form method='POST' style='display:inline-block;'>";
                                echo "<input type='hidden' name='delete_id' value='" . $subject_row['id'] . "'>";
                                echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                                echo "</form> ";
                                echo "<a href='?edit=" . $subject_row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No subjects found for this test</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if ($edit_data) : ?>
            <div class="edit-form" style="margin-left:12px; margin-top:30px;">
                <h3>Edit Subject</h3>
                <form method="POST" action="">
                    <input type="hidden" name="edit_id" value="<?php echo $edit_data['id']; ?>">
                    <div class="form-group">
                        <label for="edit_name">Subject Name</label>
                        <input type="text" name="edit_name" id="edit_name" class="form-control" value="<?php echo $edit_data['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_time">Time for Subject (minutes)</label>
                        <input type="number" name="edit_time" id="edit_time" class="form-control" value="<?php echo $edit_data['time_in_minutes']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">Update Subject</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
