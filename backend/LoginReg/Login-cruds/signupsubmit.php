<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$username || !$password) {
        echo "<script>alert('Please fill all fields.'); window.history.back();</script>";
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user in DB securely
    $stmt = $conn->prepare("INSERT INTO admin (name, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $username, $hashed_password);

    if ($stmt->execute()) {
        // Success — redirect to login page
       header("Location: ../Showadmin.php?signup=success");

        exit();
    } else {
        if ($conn->errno === 1062) {
            echo "<script>alert('Username already exists!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Database error: " . addslashes($conn->error) . "'); window.history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../Showadmin.php"); // Redirect if accessed directly
    exit();
}
?>
   