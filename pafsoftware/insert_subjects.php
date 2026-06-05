<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/config.php';

pafAdminRequireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    pafAdminSetFlash('error', 'Invalid request for adding subjects.');
    header('Location: show-subject.php');
    exit();
}

$testId = (int) ($_POST['test_id'] ?? 0);
$subjects = $_POST['subjects'] ?? [];
$times = $_POST['times'] ?? [];

if ($testId <= 0 || !is_array($subjects) || !is_array($times) || $subjects === [] || count($subjects) !== count($times)) {
    pafAdminSetFlash('error', 'Please choose a test and provide valid subject details.');
    header('Location: show-subject.php?open=add');
    exit();
}

$statement = $conn->prepare('INSERT INTO subjects (test_id, name, time_in_minutes) VALUES (?, ?, ?)');
$inserted = 0;

foreach ($subjects as $index => $subjectName) {
    $subjectName = trim((string) $subjectName);
    $time = isset($times[$index]) ? (int) $times[$index] : 0;

    if ($subjectName === '' || $time <= 0) {
        continue;
    }

    $statement->bind_param('isi', $testId, $subjectName, $time);
    if ($statement->execute()) {
        $inserted++;
    }
}

$statement->close();
$conn->close();

if ($inserted > 0) {
    pafAdminSetFlash('success', $inserted . ' subject(s) added successfully.');
} else {
    pafAdminSetFlash('warning', 'No subjects were saved. Please check the values and try again.');
}

header('Location: show-subject.php?test_id=' . $testId);
exit();
