<?php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
    $delete_id = pafNormaliseUserId($_GET['user_id']); 

    $pdo->beginTransaction();

    try {
        $deleteStatements = [
            'DELETE FROM answers WHERE user_id = ?',
            'DELETE FROM results WHERE user_id = ?',
            'DELETE FROM question_result_details WHERE user_id = ?',
            'DELETE FROM subject_result_summaries WHERE user_id = ?',
            'DELETE FROM overall_test_results WHERE user_id = ?',
            'DELETE FROM useres WHERE id = ?',
        ];

        foreach ($deleteStatements as $sql) {
            $statement = $pdo->prepare($sql);
            $statement->execute([$delete_id]);
        }

        $pdo->commit();
        echo 'success';
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'invalid_request';
}
