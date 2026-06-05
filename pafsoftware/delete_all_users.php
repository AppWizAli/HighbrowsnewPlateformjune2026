<?php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo->beginTransaction();

    try {
        $deleteStatements = [
            'DELETE FROM answers',
            'DELETE FROM results',
            'DELETE FROM question_result_details',
            'DELETE FROM subject_result_summaries',
            'DELETE FROM overall_test_results',
            'DELETE FROM useres',
        ];

        foreach ($deleteStatements as $sql) {
            if ($pdo->exec($sql) === false) {
                throw new RuntimeException('Failed to execute bulk delete.');
            }
        }

        $pdo->commit();
        echo 'success';
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo 'error: ' . $e->getMessage();
    }
}
?>
