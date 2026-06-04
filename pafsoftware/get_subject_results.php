<?php
header('Content-Type: application/json');

require_once 'db_config.php';
require_once 'result_service.php';

$pdo = getPDOConnection();

if (isset($_POST['user_id'], $_POST['subject_id'])) {
    $user_id = pafNormaliseUserId($_POST['user_id']);
    $subject_id = (int) $_POST['subject_id'];

    try {
        $summary = pafUpsertSubjectResult($pdo, $user_id, $subject_id);
        $summary['success'] = true;
        echo json_encode($summary);
    } catch (Throwable $exception) {
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $exception->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required POST data',
    ]);
}
?>
