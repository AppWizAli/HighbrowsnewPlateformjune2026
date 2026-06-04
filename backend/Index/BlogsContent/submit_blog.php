<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../../LoginReg/login.php');
    exit();
}
include '../../Database/config.php';

// Upload directory
$uploadDir = '../../uploads/blogs/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Helper function to upload file
function uploadFile($fileInputName, $uploadDir) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES[$fileInputName]['name']);
        $targetPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath);
        return $fileName;
    }
    return null;
}

// Upload files
$main_image = uploadFile('main_image', $uploadDir);
$side_image = uploadFile('side_image', $uploadDir);
$side_image2 = uploadFile('side_image2', $uploadDir);

// Text inputs
$title = $conn->real_escape_string($_POST['title']);
$description = $conn->real_escape_string($_POST['description']);
$blog_content = $conn->real_escape_string($_POST['blog_content']);
$category_name = $conn->real_escape_string($_POST['category_name']);

// Insert query
$sql = "INSERT INTO blogs_content 
        (main_image, title, description, blog_content, side_image, side_image2, category_name) 
        VALUES 
        ('$main_image', '$title', '$description', '$blog_content', '$side_image', '$side_image2', '$category_name')";

if ($conn->query($sql) === TRUE) {
    header("Location: view_blogs.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
