<?php

include "config.php";  // Assuming this contains your database connection

// Set content type to JSON
header('Content-Type: application/json');

$name = trim((string) ($_POST['name'] ?? ''));
$father_name = trim((string) ($_POST['father_name'] ?? ''));
$group_name = trim((string) ($_POST['group_name'] ?? ''));
$registration_key = trim((string) ($_POST['registration_key'] ?? ''));
$picture = $_FILES['picture'] ?? null;

if ($name === '' || $father_name === '' || $group_name === '' || $registration_key === '' || !$picture) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

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

if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

$extension = strtolower(pathinfo((string) $picture['name'], PATHINFO_EXTENSION));
$extension = $extension !== '' ? $extension : 'jpg';
$safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $registration_key) . '_' . uniqid('', true) . '.' . $extension;
$picturePath = 'uploads/' . $safeFileName;

if (!move_uploaded_file($picture['tmp_name'], $picturePath)) {
    echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
    $conn->close();
    exit();
}

$sql = "INSERT INTO useres (id, name, father_name, picture, group_name) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Error preparing the statement: ' . $conn->error]);
    $conn->close();
    exit();
}

$stmt->bind_param("sssss", $registration_key, $name, $father_name, $picturePath, $group_name);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful', 'user_id' => $registration_key]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
