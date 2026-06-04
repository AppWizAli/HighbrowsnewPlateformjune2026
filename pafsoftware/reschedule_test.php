<?php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();

if (isset($_GET['user_id'])) {
    $userId = pafNormaliseUserId($_GET['user_id']);
    $testId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : null;
    $shouldRedirect = isset($_GET['redirect']) && $_GET['redirect'] === '1';

    try {
        $pdo->beginTransaction();

        if ($testId !== null) {
            $subjects = pafFetchTestSubjects($pdo, $testId);
            $subjectIds = array_map(static fn($subject) => (int) $subject['id'], $subjects);

            if ($subjectIds !== []) {
                $questionStatement = $pdo->prepare(
                    'SELECT id FROM questions WHERE subject_id IN (' . implode(',', array_fill(0, count($subjectIds), '?')) . ')'
                );
                $questionStatement->execute($subjectIds);
                $questionIds = array_map('intval', array_column($questionStatement->fetchAll(), 'id'));

                if ($questionIds !== []) {
                    $answerDelete = $pdo->prepare(
                        'DELETE FROM answers WHERE user_id = ? AND question_id IN (' . implode(',', array_fill(0, count($questionIds), '?')) . ')'
                    );
                    $answerDelete->execute(array_merge([$userId], $questionIds));
                }

                $resultDelete = $pdo->prepare(
                    'DELETE FROM results WHERE user_id = ? AND subject_id IN (' . implode(',', array_fill(0, count($subjectIds), '?')) . ')'
                );
                $resultDelete->execute(array_merge([$userId], $subjectIds));
            }
        } else {
            $answerDelete = $pdo->prepare('DELETE FROM answers WHERE user_id = ?');
            $answerDelete->execute([$userId]);

            $resultDelete = $pdo->prepare('DELETE FROM results WHERE user_id = ?');
            $resultDelete->execute([$userId]);
        }

        $pdo->commit();

        if ($shouldRedirect) {
            header('Location: reset_test.php');
            exit();
        }

        echo 'success';
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        if ($shouldRedirect) {
            header('Location: reset_test.php');
            exit();
        }

        echo 'error: ' . $exception->getMessage();
    }
} else {
    echo 'invalid_request';
}
?>
