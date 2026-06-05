<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/config.php';

pafAdminRequireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    pafAdminSetFlash('error', 'Invalid question update request.');
    header('Location: show-question.php');
    exit();
}

$questionId = (int) $_POST['id'];
$returnTestId = (int) ($_POST['return_test_id'] ?? 0);
$returnSubjectId = (int) ($_POST['return_subject_id'] ?? 0);

if ($questionId <= 0) {
    pafAdminSetFlash('error', 'Question not found.');
    header('Location: show-question.php');
    exit();
}

$existingStatement = $conn->prepare(
    'SELECT subject_id, question_image, option_a_image, option_b_image, option_c_image, option_d_image, option_e_image
     FROM questions
     WHERE id = ?
     LIMIT 1'
);
$existingStatement->bind_param('i', $questionId);
$existingStatement->execute();
$existing = $existingStatement->get_result()->fetch_assoc();
$existingStatement->close();

if (!$existing) {
    pafAdminSetFlash('error', 'Question not found.');
    header('Location: show-question.php');
    exit();
}

if ($returnSubjectId <= 0) {
    $returnSubjectId = (int) $existing['subject_id'];
}

function pafAdminUploadFile(string $field, ?string $currentPath = null): ?string
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK || $_FILES[$field]['name'] === '') {
        return $currentPath;
    }

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $extension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
    $safeFile = uniqid($field . '_', true) . ($extension !== '' ? '.' . strtolower($extension) : '');
    $targetPath = $uploadDir . $safeFile;

    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
        return $currentPath;
    }

    return 'uploads/' . $safeFile;
}

$questionText = trim((string) ($_POST['question_text'] ?? ''));
$optionA = trim((string) ($_POST['option_a'] ?? ''));
$optionB = trim((string) ($_POST['option_b'] ?? ''));
$optionC = trim((string) ($_POST['option_c'] ?? ''));
$optionD = trim((string) ($_POST['option_d'] ?? ''));
$optionE = trim((string) ($_POST['option_e'] ?? ''));
$correctAnswer = strtoupper(trim((string) ($_POST['correct_answer'] ?? 'A')));

$questionImage = pafAdminUploadFile('question_image', (string) ($existing['question_image'] ?? ''));
$optionAImage = pafAdminUploadFile('option_a_image', (string) ($existing['option_a_image'] ?? ''));
$optionBImage = pafAdminUploadFile('option_b_image', (string) ($existing['option_b_image'] ?? ''));
$optionCImage = pafAdminUploadFile('option_c_image', (string) ($existing['option_c_image'] ?? ''));
$optionDImage = pafAdminUploadFile('option_d_image', (string) ($existing['option_d_image'] ?? ''));
$optionEImage = pafAdminUploadFile('option_e_image', (string) ($existing['option_e_image'] ?? ''));

$statement = $conn->prepare(
    'UPDATE questions SET
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
        option_e_image = ?,
        correct_answer = ?
     WHERE id = ?'
);

$statement->bind_param(
    'sssssssssssssi',
    $questionText,
    $questionImage,
    $optionA,
    $optionAImage,
    $optionB,
    $optionBImage,
    $optionC,
    $optionCImage,
    $optionD,
    $optionDImage,
    $optionE,
    $optionEImage,
    $correctAnswer,
    $questionId
);

if ($statement->execute()) {
    pafAdminSetFlash('success', 'Question updated successfully.');
} else {
    pafAdminSetFlash('error', 'Unable to update the selected question.');
}

$statement->close();
$conn->close();

$redirect = 'show-question.php';
$query = [];
if ($returnTestId > 0) {
    $query['test_id'] = $returnTestId;
}
if ($returnSubjectId > 0) {
    $query['subject_id'] = $returnSubjectId;
}

header('Location: ' . $redirect . ($query ? '?' . http_build_query($query) : ''));
exit();
