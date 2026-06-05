<?php
require_once __DIR__ . '/admin_helpers.php';
require_once __DIR__ . '/config.php';

pafAdminRequireLogin();

$testId = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;
if ($testId <= 0) {
    echo '<option value="">Select a test first</option>';
    exit();
}

$statement = $conn->prepare(
    'SELECT id, name, time_in_minutes
     FROM subjects
     WHERE test_id = ?
     ORDER BY name ASC, id ASC'
);
$statement->bind_param('i', $testId);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows === 0) {
    echo '<option value="">No subjects found</option>';
    $statement->close();
    $conn->close();
    exit();
}

echo '<option value="">Select a subject</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . (int) $row['id'] . '">' .
        htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') .
        ' (' . (int) $row['time_in_minutes'] . ' min)</option>';
}

$statement->close();
$conn->close();
