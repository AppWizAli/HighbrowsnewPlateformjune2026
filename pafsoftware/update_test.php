<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/config.php';

pafAdminRequireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['edit_id'], $_POST['edit_test_name'], $_POST['edit_date_added'])) {
    pafAdminSetFlash('error', 'Invalid test update request.');
    header('Location: show_test.php');
    exit();
}

$editId = (int) $_POST['edit_id'];
$testName = trim((string) $_POST['edit_test_name']);
$dateAdded = trim((string) $_POST['edit_date_added']);

if ($editId <= 0 || $testName === '' || $dateAdded === '') {
    pafAdminSetFlash('error', 'Please fill in all edit fields.');
    header('Location: show_test.php?edit=' . $editId);
    exit();
}

$statement = $conn->prepare('UPDATE tests SET test_name = ?, date_added = ? WHERE id = ?');
$statement->bind_param('ssi', $testName, $dateAdded, $editId);

if ($statement->execute()) {
    pafAdminSetFlash('success', 'Test updated successfully.');
} else {
    pafAdminSetFlash('error', 'Failed to update the selected test.');
}

$statement->close();
$conn->close();

header('Location: show_test.php');
exit();
