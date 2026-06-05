<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

pafAdminRequireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    pafAdminSetFlash('error', 'Invalid question submission request.');
    header('Location: show-question.php');
    exit();
}

$testId = (int) ($_POST['test_id'] ?? 0);
$subjectId = (int) ($_POST['subject_id'] ?? 0);

if ($testId <= 0 || $subjectId <= 0) {
    pafAdminSetFlash('error', 'Please select both test and subject before saving questions.');
    header('Location: add-questions.php');
    exit();
}

function pafQuestionRedirect(int $testId, int $subjectId, bool $backToForm = false): void
{
    $target = $backToForm ? 'add-questions.php' : 'show-question.php';
    header('Location: ' . $target . '?test_id=' . $testId . '&subject_id=' . $subjectId);
    exit();
}

function pafNextSequenceNumber(mysqli $conn, int $subjectId): int
{
    $statement = $conn->prepare('SELECT COALESCE(MAX(sequence_number), 0) AS max_sequence FROM questions WHERE subject_id = ?');
    $statement->bind_param('i', $subjectId);
    $statement->execute();
    $result = $statement->get_result()->fetch_assoc();
    $statement->close();

    return ((int) ($result['max_sequence'] ?? 0)) + 1;
}

function pafUploadNestedFile(string $field, $index): ?string
{
    if (!isset($_FILES['questions']['name'][$index][$field])) {
        return null;
    }

    $fileName = $_FILES['questions']['name'][$index][$field];
    $tmpName = $_FILES['questions']['tmp_name'][$index][$field];
    $error = $_FILES['questions']['error'][$index][$field];

    if ($error !== UPLOAD_ERR_OK || $fileName === '') {
        return null;
    }

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $safeFile = uniqid('q_', true) . ($extension !== '' ? '.' . strtolower($extension) : '');
    $targetPath = $uploadDir . $safeFile;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        return null;
    }

    return 'uploads/' . $safeFile;
}

function pafInsertQuestion(mysqli_stmt $statement, array $payload): bool
{
    $statement->bind_param(
        'isssssssssssssi',
        $payload['subject_id'],
        $payload['question_text'],
        $payload['question_image'],
        $payload['option_a'],
        $payload['option_a_image'],
        $payload['option_b'],
        $payload['option_b_image'],
        $payload['option_c'],
        $payload['option_c_image'],
        $payload['option_d'],
        $payload['option_d_image'],
        $payload['option_e'],
        $payload['option_e_image'],
        $payload['correct_answer'],
        $payload['sequence_number']
    );

    return $statement->execute();
}

$statement = $conn->prepare(
    'INSERT INTO questions (
        subject_id,
        question_text,
        question_image,
        option_a,
        option_a_image,
        option_b,
        option_b_image,
        option_c,
        option_c_image,
        option_d,
        option_d_image,
        option_e,
        option_e_image,
        correct_answer,
        sequence_number
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

if (!$statement) {
    pafAdminSetFlash('error', 'Unable to prepare question insert statement.');
    pafQuestionRedirect($testId, $subjectId, true);
}

$inserted = 0;
$sequenceNumber = pafNextSequenceNumber($conn, $subjectId);

if (isset($_POST['import'])) {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
        $statement->close();
        pafAdminSetFlash('error', 'Please upload a valid Excel file.');
        pafQuestionRedirect($testId, $subjectId, true);
    }

    $extension = strtolower(pathinfo($_FILES['excelFile']['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['xls', 'xlsx'], true)) {
        $statement->close();
        pafAdminSetFlash('error', 'Only .xls and .xlsx files are supported.');
        pafQuestionRedirect($testId, $subjectId, true);
    }

    try {
        $spreadsheet = IOFactory::load($_FILES['excelFile']['tmp_name']);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        array_shift($rows);

        foreach ($rows as $row) {
            if (!isset($row[0]) || trim((string) $row[0]) === '') {
                continue;
            }

            $payload = [
                'subject_id' => $subjectId,
                'question_text' => trim((string) ($row[0] ?? '')),
                'question_image' => trim((string) ($row[7] ?? '')),
                'option_a' => trim((string) ($row[1] ?? '')),
                'option_a_image' => trim((string) ($row[8] ?? '')),
                'option_b' => trim((string) ($row[2] ?? '')),
                'option_b_image' => trim((string) ($row[9] ?? '')),
                'option_c' => trim((string) ($row[3] ?? '')),
                'option_c_image' => trim((string) ($row[10] ?? '')),
                'option_d' => trim((string) ($row[4] ?? '')),
                'option_d_image' => trim((string) ($row[11] ?? '')),
                'option_e' => trim((string) ($row[5] ?? '')),
                'option_e_image' => trim((string) ($row[12] ?? '')),
                'correct_answer' => strtoupper(trim((string) ($row[6] ?? 'A'))),
                'sequence_number' => $sequenceNumber++,
            ];

            if (pafInsertQuestion($statement, $payload)) {
                $inserted++;
            }
        }
    } catch (Throwable $exception) {
        $statement->close();
        pafAdminSetFlash('error', 'Unable to read the uploaded Excel file.');
        pafQuestionRedirect($testId, $subjectId, true);
    }
} else {
    $questions = $_POST['questions'] ?? [];

    foreach ($questions as $index => $question) {
        $questionText = trim((string) ($question['question_text'] ?? ''));
        if ($questionText === '') {
            continue;
        }

        $payload = [
            'subject_id' => $subjectId,
            'question_text' => $questionText,
            'question_image' => pafUploadNestedFile('question_image', $index),
            'option_a' => trim((string) ($question['option_a_text'] ?? '')),
            'option_a_image' => pafUploadNestedFile('option_a_image', $index),
            'option_b' => trim((string) ($question['option_b_text'] ?? '')),
            'option_b_image' => pafUploadNestedFile('option_b_image', $index),
            'option_c' => trim((string) ($question['option_c_text'] ?? '')),
            'option_c_image' => pafUploadNestedFile('option_c_image', $index),
            'option_d' => trim((string) ($question['option_d_text'] ?? '')),
            'option_d_image' => pafUploadNestedFile('option_d_image', $index),
            'option_e' => trim((string) ($question['option_e_text'] ?? '')),
            'option_e_image' => pafUploadNestedFile('option_e_image', $index),
            'correct_answer' => strtoupper(trim((string) ($question['correct_answer'] ?? 'A'))),
            'sequence_number' => $sequenceNumber++,
        ];

        if (pafInsertQuestion($statement, $payload)) {
            $inserted++;
        }
    }
}

$statement->close();
$conn->close();

if ($inserted > 0) {
    pafAdminSetFlash('success', $inserted . ' question(s) saved successfully.');
    pafQuestionRedirect($testId, $subjectId);
}

pafAdminSetFlash('warning', 'No questions were saved. Please check the submitted data.');
pafQuestionRedirect($testId, $subjectId, true);
