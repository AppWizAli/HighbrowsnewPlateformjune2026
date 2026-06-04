<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../../Database/config.php';
  include '../preloader.php';
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid ID.";
    exit();
}

$id = intval($_GET['id']);
$deleteQuery = "DELETE FROM heroareas WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
header('Location:/backend/Index/HeroAreas/show_heroareas.php');
    exit();
} else {
    echo "Delete failed.";
}
?>
