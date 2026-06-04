<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php'; // Include your DB connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include PhpSpreadsheet Autoload
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle the request based on whether it’s an import or manual entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['import'])) {
        handleExcelImport($conn);
    } else {
        handleManualSubmission($conn);
    }
}

function handleExcelImport($conn) {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == 0) {
        $fileTmpPath = $_FILES['excelFile']['tmp_name'];
        $fileExtension = pathinfo($_FILES['excelFile']['name'], PATHINFO_EXTENSION);

        if (in_array($fileExtension, ['xls', 'xlsx'])) {
            try {
                $spreadsheet = IOFactory::load($fileTmpPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                array_shift($rows); // Skip header row

                $testId = intval($_POST['test_id']);
                $subjectId = intval($_POST['subject_id']);

                $stmt = $conn->prepare("
                    INSERT INTO questions (
                        subject_id, question_text, question_image, 
                        option_a, option_a_image, 
                        option_b, option_b_image, 
                        option_c, option_c_image, 
                        option_d, option_d_image, 
                        option_e, option_e_image, 
                        correct_answer, sequence_number
                    ) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                foreach ($rows as $row) {
                    if (count($row) < 13) {
                        continue; // Skip if row does not have enough data
                    }
                    insertQuestion($stmt, $subjectId, $row);
                }

                $stmt->close();
                echo "Questions and images imported successfully.";

            } catch (Exception $e) {
                echo 'Error loading file: ' . $e->getMessage();
            }
        } else {
            echo "Invalid file format. Please upload an Excel file.";
        }
    } else {
        echo "Please upload a file.";
    }
}

function handleManualSubmission($conn) {
    $subjectId = intval($_POST['subject_id']);
    $questions = $_POST['questions'] ?? [];
    
    if (empty($questions) || !is_array($questions)) {
        die("No questions to insert.");
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("
        INSERT INTO questions (
            subject_id, question_text, question_image, 
            option_a, option_a_image, 
            option_b, option_b_image, 
            option_c, option_c_image, 
            option_d, option_d_image, 
            option_e, option_e_image, 
            correct_answer, sequence_number
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $sequenceNumber = getNextSequenceNumber($conn, $subjectId);

    foreach ($questions as $question) {
        insertManualQuestion($stmt, $question, $subjectId, $sequenceNumber);
        $sequenceNumber++;
    }

    $stmt->close();
    echo "Questions inserted successfully.";
}

function insertQuestion($stmt, $subjectId, $row) {
    $questionText = $row[0];
    $optionAText = $row[1];
    $optionBText = $row[2];
    $optionCText = $row[3]; 
    $optionDText = $row[4];
    $optionEText = $row[5]; 
    $correctAnswer = $row[6]; 

    // Optional Image Paths
    $questionImage = !empty($row[7]) ? $row[7] : NULL; 
    $optionAImage = !empty($row[8]) ? $row[8] : NULL; 
    $optionBImage = !empty($row[9]) ? $row[9] : NULL; 
    $optionCImage = !empty($row[10]) ? $row[10] : NULL; 
    $optionDImage = !empty($row[11]) ? $row[11] : NULL; 
    $optionEImage = !empty($row[12]) ? $row[12] : NULL; 

    $sequenceNumber = getNextSequenceNumber($GLOBALS['conn'], $subjectId);

    $stmt->bind_param(
        "isssssssssssssi", 
        $subjectId,
        $questionText,
        $questionImage,
        $optionAText,
        $optionAImage,
        $optionBText,
        $optionBImage,
        $optionCText,
        $optionCImage,
        $optionDText,
        $optionDImage,
        $optionEText,
        $optionEImage,
        $correctAnswer,
        $sequenceNumber
    );

    if (!$stmt->execute()) {
        echo "Error inserting question: " . $stmt->error . "<br>";
    }
}

function insertManualQuestion($stmt, $question, $subjectId, $sequenceNumber) {
    $questionText = $question['question_text'] ?? '';
    $correctAnswer = $question['correct_answer'] ?? '';

    // Handle file uploads
    $questionImage = handleFileUpload('question_image', $question);
    $optionAImage = handleFileUpload('option_a_image', $question);
    $optionBImage = handleFileUpload('option_b_image', $question);
    $optionCImage = handleFileUpload('option_c_image', $question);
    $optionDImage = handleFileUpload('option_d_image', $question);
    $optionEImage = handleFileUpload('option_e_image', $question);

    $optionAText = $question['option_a_text'] ?? '';
    $optionBText = $question['option_b_text'] ?? '';
    $optionCText = $question['option_c_text'] ?? '';
    $optionDText = $question['option_d_text'] ?? '';
    $optionEText = $question['option_e_text'] ?? '';

    $stmt->bind_param(
        "isssssssssssssi",
        $subjectId,
        $questionText, $questionImage,
        $optionAText, $optionAImage,
        $optionBText, $optionBImage,
        $optionCText, $optionCImage,
        $optionDText, $optionDImage,
        $optionEText, $optionEImage,
        $correctAnswer,
        $sequenceNumber
    );

    if (!$stmt->execute()) {
        echo "Error inserting question: " . $stmt->error . "<br>";
    }
}

function getNextSequenceNumber($conn, $subjectId) {
    $query = "SELECT MAX(sequence_number) as max_sequence FROM questions WHERE subject_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $subjectId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['max_sequence'] ? $row['max_sequence'] + 1 : 1;
}

function handleFileUpload($field, $question) {
    if (isset($question[$field]) && !empty($question[$field]['name'])) {
        $targetDir = "uploads/";
        $fileName = $question[$field]['name'];
        $fileTmpName = $question[$field]['tmp_name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $targetFile = $targetDir . uniqid() . '.' . $fileExtension;

        if (move_uploaded_file($fileTmpName, $targetFile)) {
            return $targetFile;
        } else {
            echo "Failed to upload file: $fileName<br>";
            return null;
        }
    }
    return null;
}

$conn->close();
?>
