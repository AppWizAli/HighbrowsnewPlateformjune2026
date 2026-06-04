<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $option_e = $_POST['option_e'];
    $question_image = $_POST['question_image'];
    $option_a_image = $_POST['option_a_image'];
    $option_b_image = $_POST['option_b_image'];
    $option_c_image = $_POST['option_c_image'];
    $option_d_image = $_POST['option_d_image'];
    $option_e_image = $_POST['option_e_image'];

    $sql = "UPDATE questions SET
        question_text = ?, 
        question_image = ?,
        option_a = ?, 
        option_a_image = ?,
        option_b = ?, 
        option_b_image = ?,
        option_c = ?, 
        option_c_image = ?,
        option_d = ?, 
        option_d_image = ?,
        option_e = ?, 
        option_e_image = ?
        WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", 
        $question_text, $question_image, 
        $option_a, $option_a_image, 
        $option_b, $option_b_image, 
        $option_c, $option_c_image, 
        $option_d, $option_d_image, 
        $option_e, $option_e_image, 
        $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
