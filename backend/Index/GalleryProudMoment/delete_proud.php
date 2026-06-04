<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';
  include '../preloader.php';
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT main_image FROM proud_moment WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if (file_exists('../../uploads/proud_moments/' . $row['main_image'])) {
    unlink('../../uploads/proud_moments/' . $row['main_image']);
}

mysqli_query($conn, "DELETE FROM proud_moment WHERE id = $id");
header('Location: show_proud.php');
exit();
