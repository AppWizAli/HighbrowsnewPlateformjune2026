<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../Database/config.php';
$id = $_GET['id'];

$conn->query("DELETE FROM contact_users WHERE id = $id");

header("Location: view_contacts.php");
?>
