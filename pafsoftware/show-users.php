<?php
include "config.php";
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Handle filtering by group
$groupFilter = isset($_GET['group']) ? $_GET['group'] : '';

// Fetch users with optional group filter
$sql = "SELECT id, name, father_name, picture, group_name FROM useres";

if ($groupFilter) {
    $sql .= " WHERE group_name = ?";
}

$stmt = $conn->prepare($sql);

if ($groupFilter) {
    $stmt->bind_param("s", $groupFilter);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
<div class="main">
    <?php include "header.php"; ?>
    <div class="main-content" id="main-content">
        <header class="mb-4">
            <h1>Students And Results</h1>
            <p class="text-muted mb-0">Open each student profile to inspect test-wise, subject-wise, and question-wise performance.</p>
        </header>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <div>
                <strong>Student Registry</strong>
                <div class="text-muted small">Manage students, remove entries, and reset test attempts.</div>
            </div>
            <button class="btn btn-danger" id="deleteAllUsers">Delete All Users</button>
        </div>

        <!-- Users Table -->
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Father's Name</th>
                    <th>Picture</th>
                    <th>Group Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['father_name']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row['picture']) . "' class='img-fluid' style='max-width: 100px;'></td>";
                        echo "<td>" . htmlspecialchars($row['group_name']) . "</td>";
                        echo "<td>";
                        // Show Result button
                        echo "<button type='button' class='btn btn-info btn-sm show-result' data-id='" . htmlspecialchars($row['id']) . "'>View Full Result</button> ";
                        // Delete User button
                        echo "<button type='button' class='btn btn-danger btn-sm delete-user' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button> ";
                        // Reschedule Test button
                        echo "<button type='button' class='btn btn-warning btn-sm reschedule-test' data-id='" . htmlspecialchars($row['id']) . "'>Reschedule Test</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for showing results -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Student Result Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Results will be dynamically injected here -->
                <div id="resultTable">
                    <!-- Loading message -->
                    <p>Loading results...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Handle the Show Result button click
    $('.show-result').click(function() {
        var userId = $(this).data('id'); // Get the user ID
        console.log(userId);
        $('#resultModal').modal('show'); // Show the modal

        // Fetch and display the result via AJAX
        $.ajax({
            url: 'fetch_results.php', // Backend script to fetch the results
            method: 'GET',
            data: { user_id: userId },
            success: function(response) {
                $('#resultTable').html(response); // Inject the result into the modal
            },
            error: function() {
                $('#resultTable').html('<p>Error fetching results.</p>'); // Error handling
            }
        });
    });

    // Handle the Delete User button click
    $('.delete-user').click(function() {
        var userId = $(this).data('id');

        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: 'delete_useres.php', // Backend script to delete the user
                method: 'GET',
                data: { user_id: userId },
                success: function(response) {
                    if (response === 'success') {
                        alert('User deleted successfully.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error deleting user: ' + response); // Display error message
                    }
                },
                error: function() {
                    alert('Error deleting user.');
                }
            });
        }
    });

    // Handle the Reschedule Test button click
    $('.reschedule-test').click(function() {
        var userId = $(this).data('id');
console.log(userId);
        if (confirm('Are you sure you want to reschedule this test? The user will be able to take the test again.')) {
            $.ajax({
                url: 'reschedule_test.php', // Backend script to delete the user's test result
                method: 'GET',
                data: { user_id: userId },
                success: function(response) {
                    if (response === 'success') {
                        alert('Test rescheduled successfully. The user can now attempt the test again.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error rescheduling test: ' + response);
                    }
                },
                error: function() {
                    alert('Error rescheduling test.');
                }
            });
        }
    });

    // Handle the Delete All Users button click
    $('#deleteAllUsers').click(function() {
        if (confirm('Are you sure you want to delete all users? This action cannot be undone.')) {
            $.ajax({
                url: 'delete_all_users.php', // Backend script to delete all users
                method: 'POST',
                success: function(response) {
                    if (response == 'success') {
                        alert('All users deleted successfully.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error deleting users.');
                    }
                },
                error: function() {
                    alert('Error deleting users.');
                }
            });
        }
    });
});
</script>
</body>
</html>
