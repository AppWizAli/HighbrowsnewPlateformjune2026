<?php
session_start();

unset($_SESSION['selected_test_id']);

header("Location: exam.php");
exit();
?>
