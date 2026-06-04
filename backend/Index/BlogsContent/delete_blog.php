<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../LoginReg/login.php");
    exit();
}
  include '../preloader.php';
include '../../Database/config.php'; // ✅ Corrected path

$id = intval($_GET['id']);

// Optionally delete files from folder here

$conn->query("DELETE FROM blogs_content WHERE id = $id");

header("Location: view_blogs.php");
exit();
?>
