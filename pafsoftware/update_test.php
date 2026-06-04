<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Replace with your actual DB connection file

// Check if the form was submitted and required fields are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['edit_test_name'], $_POST['edit_date_added'])) {
    $edit_id = $_POST['edit_id'];
    $edit_test_name = $_POST['edit_test_name'];
    $edit_date_added = $_POST['edit_date_added'];

    // Prepare the update query
    $update_query = "UPDATE tests SET test_name = ?, date_added = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $edit_test_name, $edit_date_added, $edit_id);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Redirect to the main admin page with a success message
        $_SESSION['message'] = "Test updated successfully!";
        header('Location: show_test.php'); // Replace with your actual admin panel page
        exit();
    } else {
        // Redirect with an error message
        $_SESSION['error'] = "Failed to update the test. Please try again.";
        header('Location: show_test.php');
        exit();
    }

    $stmt->close();
} else {
    // Redirect if form data is missing
    $_SESSION['error'] = "Invalid request. Please fill out all required fields.";
    header('Location: admin_panel.php');
    exit();
}

// Close the database connection
$conn->close();
?>
