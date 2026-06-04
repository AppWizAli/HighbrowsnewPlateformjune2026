<?php
// Start the session
session_start();

// Destroy the session
session_destroy();

// Return a JSON response
echo json_encode(['success' => true]);
exit();
?>
