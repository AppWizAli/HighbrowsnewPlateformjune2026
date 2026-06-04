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

if (isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];

    $image_base_url = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/pafsoftware')), '/') . '/';

    $sql = "SELECT * FROM questions WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = '';
    while ($row = $result->fetch_assoc()) {
        $output .= '<tr id="question_row_' . htmlspecialchars($row['id']) . '">';
        $output .= '<td>' . htmlspecialchars($row['sequence_number']) . '</td>';
        $output .= '<td><input type="text" value="' . htmlspecialchars($row['question_text']) . '" id="question_text_' . htmlspecialchars($row['id']) . '"></td>';
        $output .= '<td><img src="' . $image_base_url . htmlspecialchars($row['question_image']) . '" class="question-image"><br>';
        $output .= '<input type="file" id="question_image_upload_' . htmlspecialchars($row['id']) . '" name="question_image_upload_' . htmlspecialchars($row['id']) . '"></td>';
        $output .= '<td class="options-container">';
        $output .= '<div style="border:1px solid black; display: flex;">';
        $output .= 'Option A: <input type="text" value="' . htmlspecialchars($row['option_a']) . '" id="option_a_' . htmlspecialchars($row['id']) . '">';
        if ($row['option_a_image']) $output .= '<img src="' . $image_base_url . htmlspecialchars($row['option_a_image']) . '" class="option-image">';
        $output .= '<input type="file" id="option_a_image_upload_' . htmlspecialchars($row['id']) . '" name="option_a_image_upload_' . htmlspecialchars($row['id']) . '"><br>';
        $output .= '</div>';
        $output .= '<div style="border:1px solid black; display: flex;">';
        $output .= 'Option B: <input type="text" value="' . htmlspecialchars($row['option_b']) . '" id="option_b_' . htmlspecialchars($row['id']) . '">';
        if ($row['option_b_image']) $output .= '<img src="' . $image_base_url . htmlspecialchars($row['option_b_image']) . '" class="option-image">';
        $output .= '<input type="file" id="option_b_image_upload_' . htmlspecialchars($row['id']) . '" name="option_b_image_upload_' . htmlspecialchars($row['id']) . '"><br>';
        $output .= '</div>';
        $output .= '<div style="border:1px solid black; display: flex;">';
        $output .= 'Option C: <input type="text" value="' . htmlspecialchars($row['option_c']) . '" id="option_c_' . htmlspecialchars($row['id']) . '">';
        if ($row['option_c_image']) $output .= '<img src="' . $image_base_url . htmlspecialchars($row['option_c_image']) . '" class="option-image">';
        $output .= '<input type="file" id="option_c_image_upload_' . htmlspecialchars($row['id']) . '" name="option_c_image_upload_' . htmlspecialchars($row['id']) . '"><br>';
        $output .= '</div>';
        $output .= '<div style="border:1px solid black; display: flex;">';
        $output .= 'Option D: <input type="text" value="' . htmlspecialchars($row['option_d']) . '" id="option_d_' . htmlspecialchars($row['id']) . '">';
        if ($row['option_d_image']) $output .= '<img src="' . $image_base_url . htmlspecialchars($row['option_d_image']) . '" class="option-image">';
        $output .= '<input type="file" id="option_d_image_upload_' . htmlspecialchars($row['id']) . '" name="option_d_image_upload_' . htmlspecialchars($row['id']) . '"><br>';
        $output .= '</div>';
        $output .= '<div style="border:1px solid black; display: flex;">';
        $output .= 'Option E: <input type="text" value="' . htmlspecialchars($row['option_e']) . '" id="option_e_' . htmlspecialchars($row['id']) . '">';
        if ($row['option_e_image']) $output .= '<img src="' . $image_base_url . htmlspecialchars($row['option_e_image']) . '" class="option-image">';
        $output .= '<input type="file" id="option_e_image_upload_' . htmlspecialchars($row['id']) . '" name="option_e_image_upload_' . htmlspecialchars($row['id']) . '"><br>';
        $output .= '</div>'; 
        $output .= '</div>';
        $output .= '<td><input type="text" value="' . htmlspecialchars($row['correct_answer']) . '" id="correct_answer_' . htmlspecialchars($row['id']) . '"></td>';
        $output .= '<td>';
        $output .= '<button onclick="enableEdit(' . htmlspecialchars($row['id']) . ')">Edit</button>';
        $output .= '<button onclick="saveQuestion(' . htmlspecialchars($row['id']) . ')" style="display:none;" id="saveBtn_' . htmlspecialchars($row['id']) . '">Save</button>';
        $output .= '<button onclick="deleteQuestion(' . htmlspecialchars($row['id']) . ')">Delete</button>';
        $output .= '</td>';
        $output .= '</tr>';
    }
    
    echo $output;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>.options-container {
    display: flex;
    flex-direction: column;
    gap: 10px; /* Add space between rows */
}

.options-container input[type="text"] {
    display: inline-block;
    width: 150px;
    margin-right: 10px;
}

.option-image, .question-image {
    width: 40px;
    height: auto; /* Maintain aspect ratio */
    margin-left: 10px;
}
</style>
</head>
<body>
    
</body>
</html>
