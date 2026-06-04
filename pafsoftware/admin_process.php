<?php
include "config.php";

$subject = $_POST['subject'];
$questions = $_POST['questions'];

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['file'];
    $filePath = 'uploads/' . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $filePath);

    // Read the file and process it
    // This part depends on the file format and needs further implementation
}

$sql = "INSERT INTO questions (subject, question_text) VALUES ('$subject', '$questions')";

if ($conn->query($sql) === TRUE) {
    echo "Questions added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
