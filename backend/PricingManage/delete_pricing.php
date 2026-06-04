<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../Index/preloader.php';
include '../Database/config.php';
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM pricing_manage WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: show_pricing.php?success=Deleted");
} else {
    echo "Failed to delete.";
}
$stmt->close();
?>
