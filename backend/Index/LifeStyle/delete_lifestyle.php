<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
  include '../preloader.php';
$id = $_GET['id'] ?? 0;
$result = $conn->query("SELECT main_image FROM life_style WHERE id=$id");
if ($row = $result->fetch_assoc()) {
    if (file_exists($row['main_image'])) unlink($row['main_image']);
}
$conn->query("DELETE FROM life_style WHERE id=$id");
header("Location: show_lifestyle.php?message=Deleted successfully");
exit();
?>
