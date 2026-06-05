<?php

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

if (!isset($_GET['user_id'])) {
    echo 'invalid_request';
    exit();
}

$userId = pafNormaliseUserId($_GET['user_id']);
$testId = isset($_GET['test_id']) && (int) $_GET['test_id'] > 0 ? (int) $_GET['test_id'] : null;
$shouldRedirect = isset($_GET['redirect']) && $_GET['redirect'] === '1';

try {
    $pdo->beginTransaction();

    if ($testId !== null) {
        $subjects = pafFetchTestSubjects($pdo, $testId);
        $subjectIds = array_map(static fn(array $subject): int => (int) $subject['id'], $subjects);

        if ($subjectIds !== []) {
            $subjectPlaceholders = implode(',', array_fill(0, count($subjectIds), '?'));

            $questionStatement = $pdo->prepare(
                "SELECT id FROM questions WHERE subject_id IN ($subjectPlaceholders)"
            );
            $questionStatement->execute($subjectIds);
            $questionIds = array_map('intval', array_column($questionStatement->fetchAll(PDO::FETCH_ASSOC), 'id'));

            if ($questionIds !== []) {
                $questionPlaceholders = implode(',', array_fill(0, count($questionIds), '?'));

                $deleteAnswers = $pdo->prepare(
                    "DELETE FROM answers WHERE user_id = ? AND question_id IN ($questionPlaceholders)"
                );
                $deleteAnswers->execute(array_merge([$userId], $questionIds));

                $deleteQuestionDetails = $pdo->prepare(
                    "DELETE FROM question_result_details WHERE user_id = ? AND question_id IN ($questionPlaceholders)"
                );
                $deleteQuestionDetails->execute(array_merge([$userId], $questionIds));
            }

            $deleteLegacyResults = $pdo->prepare(
                "DELETE FROM results WHERE user_id = ? AND subject_id IN ($subjectPlaceholders)"
            );
            $deleteLegacyResults->execute(array_merge([$userId], $subjectIds));

            $deleteSubjectSummaries = $pdo->prepare(
                "DELETE FROM subject_result_summaries WHERE user_id = ? AND subject_id IN ($subjectPlaceholders)"
            );
            $deleteSubjectSummaries->execute(array_merge([$userId], $subjectIds));
        }

        $deleteOverall = $pdo->prepare('DELETE FROM overall_test_results WHERE user_id = ? AND test_id = ?');
        $deleteOverall->execute([$userId, $testId]);
    } else {
        $deleteAnswers = $pdo->prepare('DELETE FROM answers WHERE user_id = ?');
        $deleteAnswers->execute([$userId]);

        $deleteLegacyResults = $pdo->prepare('DELETE FROM results WHERE user_id = ?');
        $deleteLegacyResults->execute([$userId]);

        $deleteQuestionDetails = $pdo->prepare('DELETE FROM question_result_details WHERE user_id = ?');
        $deleteQuestionDetails->execute([$userId]);

        $deleteSubjectSummaries = $pdo->prepare('DELETE FROM subject_result_summaries WHERE user_id = ?');
        $deleteSubjectSummaries->execute([$userId]);

        $deleteOverall = $pdo->prepare('DELETE FROM overall_test_results WHERE user_id = ?');
        $deleteOverall->execute([$userId]);
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
