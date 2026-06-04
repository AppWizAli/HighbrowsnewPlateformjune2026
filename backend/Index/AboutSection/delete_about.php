<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
  include '../preloader.php';
include '../../Database/config.php';

$id = $_GET['id'] ?? 0;
$res = $conn->query("SELECT * FROM about_section WHERE id=$id");
$row = $res->fetch_assoc();

if ($row && file_exists($row['main_image'])) {
    unlink($row['main_image']);
}

$conn->query("DELETE FROM about_section WHERE id=$id");
header("Location: show_about.php");
exit();
