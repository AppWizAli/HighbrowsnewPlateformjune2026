<?php
  include '../preloader.php';
include '../../Database/config.php';


if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $delete = "DELETE FROM review_content WHERE id = $id";
  if (mysqli_query($conn, $delete)) {
    header("Location: show_reviews.php");
    exit();
  } else {
    echo "Delete failed: " . mysqli_error($conn);
  }
}
?>
