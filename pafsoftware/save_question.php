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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $option_e = $_POST['option_e'];
    $correct_answer = $_POST['correct_answer'];
    // Get other fields if needed

    $sql = "UPDATE questions SET 
            question_text = ?, 
            option_a = ?, 
            option_b = ?, 
            option_c = ?, 
            option_d = ?, 
            option_e = ?, 
            correct_answer = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $option_e, $correct_answer, $question_id);
    $stmt->execute();

    // Handle image uploads if provided
    $upload_dir = 'uploads/';
    $uploaded_images = [];

    if (isset($_FILES['question_image_upload']) && $_FILES['question_image_upload']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['question_image_upload']['tmp_name'];
        $imageName = $_FILES['question_image_upload']['name'];
        $imagePath = $upload_dir . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $uploaded_images['question_image'] = $imagePath;
    }

    if (isset($_FILES['option_a_image_upload']) && $_FILES['option_a_image_upload']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['option_a_image_upload']['tmp_name'];
        $imageName = $_FILES['option_a_image_upload']['name'];
        $imagePath = $upload_dir . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $uploaded_images['option_a_image'] = $imagePath;
    }

    if (isset($_FILES['option_b_image_upload']) && $_FILES['option_b_image_upload']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['option_b_image_upload']['tmp_name'];
        $imageName = $_FILES['option_b_image_upload']['name'];
        $imagePath = $upload_dir . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $uploaded_images['option_b_image'] = $imagePath;
    }

    if (isset($_FILES['option_c_image_upload']) && $_FILES['option_c_image_upload']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['option_c_image_upload']['tmp_name'];
        $imageName = $_FILES['option_c_image_upload']['name'];
        $imagePath = $upload_dir . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $uploaded_images['option_c_image'] = $imagePath;
    }

    if (isset($_FILES['option_d_image_upload']) && $_FILES['option_d_image_upload']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['option_d_image_upload']['tmp_name'];
        $imageName = $_FILES['option_d_image_upload']['name'];
        $imagePath = $upload_dir . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $uploaded_images['option_d_image'] = $imagePath;
    }

    if (isset($_FILES['option_e_image_upload']) && $_FILES['option_e_image_upload']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['option_e_image_upload']['tmp_name'];
        $imageName = $_FILES['option_e_image_upload']['name'];
        $imagePath = $upload_dir . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $uploaded_images['option_e_image'] = $imagePath;
    }

    // Update the images in the database if they were uploaded
    foreach ($uploaded_images as $field => $path) {
        $sql = "UPDATE questions SET $field = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $path, $question_id);
        $stmt->execute();
    }

    echo "Question updated successfully.";
}
?>
