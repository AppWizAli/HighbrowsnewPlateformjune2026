<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/config.php';

pafAdminRequireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    pafAdminSetFlash('error', 'Invalid request for adding a test.');
    header('Location: show_test.php');
    exit();
}

$testId = trim((string) ($_POST['test_id'] ?? ''));
$testName = trim((string) ($_POST['test_name'] ?? ''));
$dateAdded = trim((string) ($_POST['date_added'] ?? ''));

if ($testId === '' || $testName === '' || $dateAdded === '') {
    pafAdminSetFlash('error', 'Please fill in all test fields.');
    header('Location: show_test.php?open=add');
    exit();
}

$checkStatement = $conn->prepare('SELECT id FROM tests WHERE test_name = ? OR test_id = ? LIMIT 1');
$checkStatement->bind_param('ss', $testName, $testId);
$checkStatement->execute();
$existing = $checkStatement->get_result()->fetch_assoc();
$checkStatement->close();

if ($existing) {
    pafAdminSetFlash('warning', 'A test with the same code or name already exists.');
    header('Location: show_test.php?open=add');
    exit();
}

$statement = $conn->prepare('INSERT INTO tests (test_id, test_name, date_added) VALUES (?, ?, ?)');
$statement->bind_param('sss', $testId, $testName, $dateAdded);

if ($statement->execute()) {
    pafAdminSetFlash('success', 'Test added successfully.');
} else {
    pafAdminSetFlash('error', 'Unable to add the new test right now.');
}

$statement->close();
$conn->close();

header('Location: show_test.php');
exit();
