<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../Database/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name         = trim($_POST['name']);
    $email        = trim($_POST['email']);
    $countryCode  = trim($_POST['country_code']); // e.g. +92
    $number       = trim($_POST['phone_number']);
    $message      = trim($_POST['message']);

    // Clean number
    $cleanNumber = preg_replace('/[^0-9]/', '', $number);

    // Combine country code with number
    $fullNumber = $countryCode . $cleanNumber;

    $stmt = $conn->prepare("INSERT INTO contact_users (name, email, number, message) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $fullNumber, $message);

        if ($stmt->execute()) {
            header("Location: ../../Index/contact.php?status=success");
            exit();
        } else {
            header("Location: ../../Index/contact.php?status=error&msg=" . urlencode($stmt->error));
            exit();
        }

        $stmt->close();
    } else {
        header("Location: ../../Index/contact.php?status=error&msg=" . urlencode($conn->error));
        exit();
    }

    $conn->close();
}
?>
