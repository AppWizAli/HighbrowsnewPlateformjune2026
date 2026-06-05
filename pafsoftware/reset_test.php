<?php
session_start();

// Remove the selected test ID from the session
unset($_SESSION['selected_test_id']);

// Redirect back to the main page
header("Location: index.php"); // Replace with the main page if it's different
exit();
?>
