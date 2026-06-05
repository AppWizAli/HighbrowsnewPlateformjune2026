<?php

include "config.php";  // Assuming this contains your database connection

// Set content type to JSON
header('Content-Type: application/json');

// Get form data
$name = $_POST['name'];
$father_name = $_POST['father_name'];
$picture = $_FILES['picture'];
$group_name = $_POST['group_name'];
$registration_key = $_POST['registration_key'];  // Get the registration key from the form

// Check if user already exists with the same registration key
$check_sql = "SELECT id FROM useres WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $registration_key);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    // User with the same registration key already exists
    echo json_encode(['status' => 'error', 'message' => 'User already exists with this registration key']);
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Define the picture upload path
$picturePath = 'uploads/' . basename($picture['name']);

// Move the uploaded file to the desired directory
if (!move_uploaded_file($picture['tmp_name'], $picturePath)) {
    echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
    $conn->close();
    exit();
}

// Prepare the SQL statement
$sql = "INSERT INTO useres (id, name, father_name, picture, group_name) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error preparing the statement: ' . $conn->error]);
    $conn->close();
    exit();
}

// Bind parameters (use the provided registration key)
$stmt->bind_param("sssss", $registration_key, $name, $father_name, $picturePath, $group_name);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful', 'user_id' => $registration_key]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
