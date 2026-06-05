<?php

require_once __DIR__ . '/db_config.php';

const PAF_PASS_PERCENTAGE = 50.0;

function pafNormaliseUserId($userId): string
{
    return trim((string) $userId);
}

function pafEnsureResultTables(PDO $pdo): void
{
    static $bootstrapped = false;

    if ($bootstrapped) {
        return;
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS question_result_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(255) NOT NULL,
            student_name VARCHAR(255) NOT NULL DEFAULT \'\',
            test_id INT NOT NULL,
            subject_id INT NOT NULL,
            question_id INT NOT NULL,
            question_sequence INT NOT NULL DEFAULT 0,
            selected_answer VARCHAR(10) NOT NULL DEFAULT \'\',
            correct_answer VARCHAR(10) NOT NULL DEFAULT \'\',
            result_status VARCHAR(20) NOT NULL DEFAULT \'Not Answered\',
            mark_for_review TINYINT(1) NOT NULL DEFAULT 0,
            is_skipped TINYINT(1) NOT NULL DEFAULT 0,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_question_result_details_user_question (user_id, question_id),
            KEY idx_question_result_details_test_subject (test_id, subject_id),
            KEY idx_question_result_details_user_test (user_id, test_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS subject_result_summaries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(255) NOT NULL,
            student_name VARCHAR(255) NOT NULL DEFAULT \'\',
            test_id INT NOT NULL,
            subject_id INT NOT NULL,
            total_questions INT NOT NULL DEFAULT 0,
            attempted_questions INT NOT NULL DEFAULT 0,
            correct_answers INT NOT NULL DEFAULT 0,
            wrong_answers INT NOT NULL DEFAULT 0,
            not_answered_questions INT NOT NULL DEFAULT 0,
            review_marked_questions INT NOT NULL DEFAULT 0,
            percentage DECIMAL(6,2) NOT NULL DEFAULT 0.00,
            result_status VARCHAR(20) NOT NULL DEFAULT \'Fail\',
            completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_subject_result_summaries_user_subject (user_id, subject_id),
            KEY idx_subject_result_summaries_user_test (user_id, test_id),
            KEY idx_subject_result_summaries_test_subject (test_id, subject_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS overall_test_results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(255) NOT NULL,
            student_name VARCHAR(255) NOT NULL DEFAULT \'\',
            test_id INT NOT NULL,
            total_subjects INT NOT NULL DEFAULT 0,
            total_questions INT NOT NULL DEFAULT 0,
            total_correct INT NOT NULL DEFAULT 0,
            total_wrong INT NOT NULL DEFAULT 0,
            total_not_answered INT NOT NULL DEFAULT 0,
            overall_percentage DECIMAL(6,2) NOT NULL DEFAULT 0.00,
            result_status VARCHAR(20) NOT NULL DEFAULT \'Fail\',
            merit_position INT DEFAULT NULL,
            completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_overall_test_results_user_test (user_id, test_id),
            KEY idx_overall_test_results_test (test_id),
            KEY idx_overall_test_results_test_percentage (test_id, overall_percentage)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci'
    );

    $bootstrapped = true;
}

function pafPassStatus(float $percentage): string
{
    return $percentage >= PAF_PASS_PERCENTAGE ? 'Pass' : 'Fail';
}

function pafFetchStudentInfo(PDO $pdo, string $userId): array
{
    $statement = $pdo->prepare(
        'SELECT id, name, father_name, picture, group_name
         FROM useres
         WHERE id = ?
         LIMIT 1'
    );
    $statement->execute([$userId]);

    return $statement->fetch(PDO::FETCH_ASSOC) ?: [
        'id' => $userId,
        'name' => '',
        'father_name' => '',
        'picture' => '',
        'group_name' => '',
    ];
}

function pafFetchTests(PDO $pdo): array
{
    $statement = $pdo->query('SELECT id, test_name FROM tests ORDER BY test_name ASC, id ASC');
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function pafFetchTestSubjects(PDO $pdo, int $testId): array
{
    $statement = $pdo->prepare(
        'SELECT s.id, s.test_id, s.name, s.time_in_minutes, t.test_name
         FROM subjects s
         INNER JOIN tests t ON t.id = s.test_id
         WHERE s.test_id = ?
         ORDER BY s.id ASC'
    );
    $statement->execute([$testId]);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function pafFetchQuestionStatuses(PDO $pdo, string $userId, array $questionIds): array
{
    $questionIds = array_values(array_filter(array_map('intval', $questionIds)));
    if ($questionIds === []) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
    $statement = $pdo->prepare(
        "SELECT question_id, answer, mark_for_review, is_skipped
         FROM answers
         WHERE user_id = ? AND question_id IN ($placeholders)"
    );
    $statement->execute(array_merge([$userId], $questionIds));

    $statuses = [];
    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $statuses[(int) $row['question_id']] = [
            'answer' => (string) ($row['answer'] ?? ''),
            'mark_for_review' => (int) ($row['mark_for_review'] ?? 0),
            'is_skipped' => (int) ($row['is_skipped'] ?? 0),
        ];
    }

    return $statuses;
}

function pafQuestionStatusName(array $status): string
{
    if ((int) ($status['mark_for_review'] ?? 0) === 1) {
        return 'review';
    }

    $answer = trim((string) ($status['answer'] ?? ''));
    if ($answer !== '' && $answer !== 'F') {
        return 'answered';
    }

    return 'not_answered';
}

function pafCompletedSubjectIds(PDO $pdo, string $userId, array $subjectIds): array
{
    pafEnsureResultTables($pdo);

    $subjectIds = array_values(array_filter(array_map('intval', $subjectIds)));
    if ($subjectIds === []) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
    $statement = $pdo->prepare(
        "SELECT subject_id
         FROM subject_result_summaries
         WHERE user_id = ? AND subject_id IN ($placeholders)"
    );
    $statement->execute(array_merge([$userId], $subjectIds));

    return array_map('intval', array_column($statement->fetchAll(PDO::FETCH_ASSOC), 'subject_id'));
}

function pafAnswerLabel(string $answer, array $row): string
{
    $options = [
        'A' => $row['option_a'] ?? '',
        'B' => $row['option_b'] ?? '',
        'C' => $row['option_c'] ?? '',
        'D' => $row['option_d'] ?? '',
        'E' => $row['option_e'] ?? '',
    ];

    if ($answer === '' || $answer === 'F') {
        return 'No Selection';
    }

    $optionText = trim((string) ($options[$answer] ?? ''));
    return $optionText === '' ? $answer : $answer . ' - ' . $optionText;
}

function pafBuildSubjectResult(PDO $pdo, string $userId, int $subjectId): array
{
    $subjectStatement = $pdo->prepare(
        'SELECT s.id, s.name AS subject_name, s.test_id, t.test_name
         FROM subjects s
         INNER JOIN tests t ON t.id = s.test_id
         WHERE s.id = ?
         LIMIT 1'
    );
    $subjectStatement->execute([$subjectId]);
    $subjectMeta = $subjectStatement->fetch(PDO::FETCH_ASSOC);

    if (!$subjectMeta) {
        throw new RuntimeException('Subject not found.');
    }

    $student = pafFetchStudentInfo($pdo, $userId);

    $questionStatement = $pdo->prepare(
        'SELECT
            q.id,
            q.sequence_number,
            q.question_text,
            q.option_a,
            q.option_b,
            q.option_c,
            q.option_d,
            q.option_e,
            q.correct_answer,
            a.answer AS selected_answer,
            a.mark_for_review,
            a.is_skipped
         FROM questions q
         LEFT JOIN answers a
            ON a.question_id = q.id
           AND a.user_id = ?
         WHERE q.subject_id = ?
         ORDER BY q.sequence_number ASC, q.id ASC'
    );
    $questionStatement->execute([$userId, $subjectId]);
    $questionRows = $questionStatement->fetchAll(PDO::FETCH_ASSOC);

    $details = [];
    $attemptedQuestions = 0;
    $correctAnswers = 0;
    $wrongAnswers = 0;
    $notAnsweredQuestions = 0;
    $reviewMarkedQuestions = 0;

    foreach ($questionRows as $row) {
        $selectedAnswer = trim((string) ($row['selected_answer'] ?? ''));
        $correctAnswer = trim((string) ($row['correct_answer'] ?? ''));
        $markForReview = (int) ($row['mark_for_review'] ?? 0);
        $isSkipped = (int) ($row['is_skipped'] ?? 0);
        $isAttempted = $selectedAnswer !== '' && $selectedAnswer !== 'F';
        $isCorrect = $isAttempted && $selectedAnswer === $correctAnswer;

        if ($markForReview === 1) {
            $reviewMarkedQuestions++;
        }

        if ($isAttempted) {
            $attemptedQuestions++;
        }

        if ($isCorrect) {
            $correctAnswers++;
            $resultStatus = 'Correct';
        } elseif ($isAttempted) {
            $wrongAnswers++;
            $resultStatus = 'Wrong';
        } else {
            $notAnsweredQuestions++;
            $resultStatus = 'Not Answered';
            $isSkipped = 1;
        }

        $details[] = [
            'user_id' => $userId,
            'student_name' => (string) ($student['name'] ?? ''),
            'test_id' => (int) $subjectMeta['test_id'],
            'test_name' => (string) $subjectMeta['test_name'],
            'subject_id' => (int) $subjectMeta['id'],
            'subject_name' => (string) $subjectMeta['subject_name'],
            'question_id' => (int) $row['id'],
            'question_sequence' => (int) ($row['sequence_number'] ?? 0),
            'question_text' => (string) ($row['question_text'] ?? ''),
            'selected_answer' => $selectedAnswer,
            'selected_answer_label' => pafAnswerLabel($selectedAnswer, $row),
            'correct_answer' => $correctAnswer,
            'correct_answer_label' => pafAnswerLabel($correctAnswer, $row),
            'result_status' => $resultStatus,
            'mark_for_review' => $markForReview,
            'is_skipped' => $isSkipped,
        ];
    }

    $totalQuestions = count($details);
    $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0.0;

    return [
        'student' => $student,
        'test_id' => (int) $subjectMeta['test_id'],
        'test_name' => (string) $subjectMeta['test_name'],
        'subject_id' => (int) $subjectMeta['id'],
        'subject_name' => (string) $subjectMeta['subject_name'],
        'total_questions' => $totalQuestions,
        'attempted_questions' => $attemptedQuestions,
        'correct_answers' => $correctAnswers,
        'wrong_answers' => $wrongAnswers,
        'not_answered_questions' => $notAnsweredQuestions,
        'review_questions' => $reviewMarkedQuestions,
        'percentage' => $percentage,
        'subject_result' => pafPassStatus($percentage),
        'details' => $details,
    ];
}

function pafUpsertQuestionResultDetails(PDO $pdo, array $summary): void
{
    $statement = $pdo->prepare(
        'INSERT INTO question_result_details (
            user_id,
            student_name,
            test_id,
            subject_id,
            question_id,
            question_sequence,
            selected_answer,
            correct_answer,
            result_status,
            mark_for_review,
            is_skipped
        ) VALUES (
            :user_id,
            :student_name,
            :test_id,
            :subject_id,
            :question_id,
            :question_sequence,
            :selected_answer,
            :correct_answer,
            :result_status,
            :mark_for_review,
            :is_skipped
        )
        ON DUPLICATE KEY UPDATE
            student_name = VALUES(student_name),
            test_id = VALUES(test_id),
            subject_id = VALUES(subject_id),
            question_sequence = VALUES(question_sequence),
            selected_answer = VALUES(selected_answer),
            correct_answer = VALUES(correct_answer),
            result_status = VALUES(result_status),
            mark_for_review = VALUES(mark_for_review),
            is_skipped = VALUES(is_skipped),
            updated_at = CURRENT_TIMESTAMP'
    );

    foreach ($summary['details'] as $detail) {
        $statement->execute([
            ':user_id' => $detail['user_id'],
            ':student_name' => $detail['student_name'],
            ':test_id' => $detail['test_id'],
            ':subject_id' => $detail['subject_id'],
            ':question_id' => $detail['question_id'],
            ':question_sequence' => $detail['question_sequence'],
            ':selected_answer' => $detail['selected_answer'],
            ':correct_answer' => $detail['correct_answer'],
            ':result_status' => $detail['result_status'],
            ':mark_for_review' => $detail['mark_for_review'],
            ':is_skipped' => $detail['is_skipped'],
        ]);
    }
}

function pafUpsertLegacyResult(PDO $pdo, string $userId, int $subjectId, int $correctAnswers, int $totalQuestions, float $percentage): void
{
    try {
        $selectStatement = $pdo->prepare('SELECT id FROM results WHERE user_id = ? AND subject_id = ? LIMIT 1');
        $selectStatement->execute([$userId, $subjectId]);
        $existingId = $selectStatement->fetchColumn();

        if ($existingId) {
            $updateStatement = $pdo->prepare(
                'UPDATE results
                 SET correct_answers = ?, total_questions = ?, percentage = ?
                 WHERE id = ?'
            );
            $updateStatement->execute([$correctAnswers, $totalQuestions, $percentage, $existingId]);
        } else {
            $insertStatement = $pdo->prepare(
                'INSERT INTO results (user_id, subject_id, correct_answers, total_questions, percentage)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $insertStatement->execute([$userId, $subjectId, $correctAnswers, $totalQuestions, $percentage]);
        }
    } catch (Throwable $exception) {
        // Legacy `results` syncing should not block the new reporting flow.
    }
}

function pafUpsertSubjectResult(PDO $pdo, string $userId, int $subjectId): array
{
    pafEnsureResultTables($pdo);

    $summary = pafBuildSubjectResult($pdo, $userId, $subjectId);
    pafUpsertQuestionResultDetails($pdo, $summary);

    $statement = $pdo->prepare(
        'INSERT INTO subject_result_summaries (
            user_id,
            student_name,
            test_id,
            subject_id,
            total_questions,
            attempted_questions,
            correct_answers,
            wrong_answers,
            not_answered_questions,
            review_marked_questions,
            percentage,
            result_status
        ) VALUES (
            :user_id,
            :student_name,
            :test_id,
            :subject_id,
            :total_questions,
            :attempted_questions,
            :correct_answers,
            :wrong_answers,
            :not_answered_questions,
            :review_marked_questions,
            :percentage,
            :result_status
        )
        ON DUPLICATE KEY UPDATE
            student_name = VALUES(student_name),
            test_id = VALUES(test_id),
            total_questions = VALUES(total_questions),
            attempted_questions = VALUES(attempted_questions),
            correct_answers = VALUES(correct_answers),
            wrong_answers = VALUES(wrong_answers),
            not_answered_questions = VALUES(not_answered_questions),
            review_marked_questions = VALUES(review_marked_questions),
            percentage = VALUES(percentage),
            result_status = VALUES(result_status),
            completed_at = CURRENT_TIMESTAMP'
    );

    $statement->execute([
        ':user_id' => $userId,
        ':student_name' => (string) ($summary['student']['name'] ?? ''),
        ':test_id' => $summary['test_id'],
        ':subject_id' => $summary['subject_id'],
        ':total_questions' => $summary['total_questions'],
        ':attempted_questions' => $summary['attempted_questions'],
        ':correct_answers' => $summary['correct_answers'],
        ':wrong_answers' => $summary['wrong_answers'],
        ':not_answered_questions' => $summary['not_answered_questions'],
        ':review_marked_questions' => $summary['review_questions'],
        ':percentage' => $summary['percentage'],
        ':result_status' => $summary['subject_result'],
    ]);

    pafUpsertLegacyResult(
        $pdo,
        $userId,
        $summary['subject_id'],
        $summary['correct_answers'],
        $summary['total_questions'],
        $summary['percentage']
    );

    return $summary;
}

function pafRefreshMeritPositions(PDO $pdo, int $testId): void
{
    pafEnsureResultTables($pdo);

    $statement = $pdo->prepare(
        'SELECT id
         FROM overall_test_results
         WHERE test_id = ?
         ORDER BY overall_percentage DESC, total_correct DESC, total_wrong ASC, completed_at ASC, id ASC'
    );
    $statement->execute([$testId]);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    $updateStatement = $pdo->prepare('UPDATE overall_test_results SET merit_position = ? WHERE id = ?');

    foreach ($rows as $index => $row) {
        $updateStatement->execute([$index + 1, $row['id']]);
    }
}

function pafUpsertOverallResult(PDO $pdo, string $userId, int $testId): ?array
{
    pafEnsureResultTables($pdo);

    $subjects = pafFetchTestSubjects($pdo, $testId);
    if ($subjects === []) {
        return null;
    }

    $subjectIds = array_map(static fn(array $subject): int => (int) $subject['id'], $subjects);
    $completedSubjectIds = pafCompletedSubjectIds($pdo, $userId, $subjectIds);

    if (count($completedSubjectIds) < count($subjectIds)) {
        return null;
    }

    $student = pafFetchStudentInfo($pdo, $userId);

    $statement = $pdo->prepare(
        'SELECT
            COUNT(*) AS total_subjects,
            SUM(total_questions) AS total_questions,
            SUM(correct_answers) AS total_correct,
            SUM(wrong_answers) AS total_wrong,
            SUM(not_answered_questions) AS total_not_answered
         FROM subject_result_summaries
         WHERE user_id = ? AND test_id = ?'
    );
    $statement->execute([$userId, $testId]);
    $aggregate = $statement->fetch(PDO::FETCH_ASSOC) ?: [];

    $testNameStatement = $pdo->prepare('SELECT test_name FROM tests WHERE id = ? LIMIT 1');
    $testNameStatement->execute([$testId]);
    $testName = (string) ($testNameStatement->fetchColumn() ?: '');

    $totalQuestions = (int) ($aggregate['total_questions'] ?? 0);
    $totalCorrect = (int) ($aggregate['total_correct'] ?? 0);
    $totalWrong = (int) ($aggregate['total_wrong'] ?? 0);
    $totalNotAnswered = (int) ($aggregate['total_not_answered'] ?? 0);
    $overallPercentage = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0.0;
    $overallStatus = pafPassStatus($overallPercentage);

    $upsertStatement = $pdo->prepare(
        'INSERT INTO overall_test_results (
            user_id,
            student_name,
            test_id,
            total_subjects,
            total_questions,
            total_correct,
            total_wrong,
            total_not_answered,
            overall_percentage,
            result_status
        ) VALUES (
            :user_id,
            :student_name,
            :test_id,
            :total_subjects,
            :total_questions,
            :total_correct,
            :total_wrong,
            :total_not_answered,
            :overall_percentage,
            :result_status
        )
        ON DUPLICATE KEY UPDATE
            student_name = VALUES(student_name),
            total_subjects = VALUES(total_subjects),
            total_questions = VALUES(total_questions),
            total_correct = VALUES(total_correct),
            total_wrong = VALUES(total_wrong),
            total_not_answered = VALUES(total_not_answered),
            overall_percentage = VALUES(overall_percentage),
            result_status = VALUES(result_status),
            completed_at = CURRENT_TIMESTAMP'
    );

    $upsertStatement->execute([
        ':user_id' => $userId,
        ':student_name' => (string) ($student['name'] ?? ''),
        ':test_id' => $testId,
        ':total_subjects' => (int) ($aggregate['total_subjects'] ?? 0),
        ':total_questions' => $totalQuestions,
        ':total_correct' => $totalCorrect,
        ':total_wrong' => $totalWrong,
        ':total_not_answered' => $totalNotAnswered,
        ':overall_percentage' => $overallPercentage,
        ':result_status' => $overallStatus,
    ]);

    pafRefreshMeritPositions($pdo, $testId);

    $resultStatement = $pdo->prepare(
        'SELECT total_subjects, total_questions, total_correct, total_wrong, total_not_answered,
                overall_percentage, result_status, merit_position
         FROM overall_test_results
         WHERE user_id = ? AND test_id = ?
         LIMIT 1'
    );
    $resultStatement->execute([$userId, $testId]);
    $overall = $resultStatement->fetch(PDO::FETCH_ASSOC) ?: [];

    return [
        'user_id' => $userId,
        'student_name' => (string) ($student['name'] ?? ''),
        'test_id' => $testId,
        'test_name' => $testName,
        'total_subjects' => (int) ($overall['total_subjects'] ?? 0),
        'total_questions' => (int) ($overall['total_questions'] ?? 0),
        'total_correct' => (int) ($overall['total_correct'] ?? 0),
        'total_wrong' => (int) ($overall['total_wrong'] ?? 0),
        'total_not_answered' => (int) ($overall['total_not_answered'] ?? 0),
        'overall_percentage' => (float) ($overall['overall_percentage'] ?? 0),
        'overall_result' => (string) ($overall['result_status'] ?? $overallStatus),
        'merit_position' => isset($overall['merit_position']) ? (int) $overall['merit_position'] : null,
    ];
}

function pafGetUserResultTree(PDO $pdo, string $userId, ?int $testId = null): array
{
    pafEnsureResultTables($pdo);

    $student = pafFetchStudentInfo($pdo, $userId);
    $params = [$userId];
    $sql = 'SELECT o.test_id, t.test_name, o.total_subjects, o.total_questions, o.total_correct, o.total_wrong,
                   o.total_not_answered, o.overall_percentage, o.result_status, o.merit_position
            FROM overall_test_results o
            INNER JOIN tests t ON t.id = o.test_id
            WHERE o.user_id = ?';

    if ($testId !== null) {
        $sql .= ' AND o.test_id = ?';
        $params[] = $testId;
    }

    $sql .= ' ORDER BY t.test_name ASC, o.test_id ASC';

    $overallStatement = $pdo->prepare($sql);
    $overallStatement->execute($params);
    $overallRows = $overallStatement->fetchAll(PDO::FETCH_ASSOC);

    $tests = [];
    $totalSubjects = 0;
    $totalQuestions = 0;
    $totalCorrect = 0;
    $totalWrong = 0;
    $totalNotAnswered = 0;

    $subjectStatement = $pdo->prepare(
        'SELECT s.subject_id, sub.name AS subject_name, s.total_questions, s.attempted_questions,
                s.correct_answers, s.wrong_answers, s.not_answered_questions, s.review_marked_questions,
                s.percentage, s.result_status
         FROM subject_result_summaries s
         INNER JOIN subjects sub ON sub.id = s.subject_id
         WHERE s.user_id = ? AND s.test_id = ?
         ORDER BY sub.id ASC'
    );

    $detailStatement = $pdo->prepare(
        'SELECT q.question_id, q.question_sequence, q.selected_answer, q.correct_answer, q.result_status,
                q.mark_for_review, q.is_skipped, qu.question_text, qu.option_a, qu.option_b, qu.option_c, qu.option_d, qu.option_e
         FROM question_result_details q
         INNER JOIN questions qu ON qu.id = q.question_id
         WHERE q.user_id = ? AND q.subject_id = ?
         ORDER BY q.question_sequence ASC, q.question_id ASC'
    );

    foreach ($overallRows as $overallRow) {
        $subjectStatement->execute([$userId, (int) $overallRow['test_id']]);
        $subjectRows = $subjectStatement->fetchAll(PDO::FETCH_ASSOC);
        $subjects = [];

        foreach ($subjectRows as $subjectRow) {
            $detailStatement->execute([$userId, (int) $subjectRow['subject_id']]);
            $detailRows = [];

            foreach ($detailStatement->fetchAll(PDO::FETCH_ASSOC) as $detailRow) {
                $detailRows[] = [
                    'question_id' => (int) $detailRow['question_id'],
                    'sequence_number' => (int) $detailRow['question_sequence'],
                    'question_text' => (string) $detailRow['question_text'],
                    'selected_answer' => (string) $detailRow['selected_answer'],
                    'selected_answer_label' => pafAnswerLabel((string) $detailRow['selected_answer'], $detailRow),
                    'correct_answer' => (string) $detailRow['correct_answer'],
                    'correct_answer_label' => pafAnswerLabel((string) $detailRow['correct_answer'], $detailRow),
                    'result_status' => (string) $detailRow['result_status'],
                    'mark_for_review' => (int) $detailRow['mark_for_review'],
                    'is_skipped' => (int) $detailRow['is_skipped'],
                ];
            }

            $subjects[] = [
                'subject_id' => (int) $subjectRow['subject_id'],
                'subject_name' => (string) $subjectRow['subject_name'],
                'total_questions' => (int) $subjectRow['total_questions'],
                'attempted_questions' => (int) $subjectRow['attempted_questions'],
                'correct_answers' => (int) $subjectRow['correct_answers'],
                'wrong_answers' => (int) $subjectRow['wrong_answers'],
                'not_answered_questions' => (int) $subjectRow['not_answered_questions'],
                'review_marked_questions' => (int) $subjectRow['review_marked_questions'],
                'percentage' => (float) $subjectRow['percentage'],
                'result_status' => (string) $subjectRow['result_status'],
                'questions' => $detailRows,
            ];
        }

        $tests[] = [
            'test_id' => (int) $overallRow['test_id'],
            'test_name' => (string) $overallRow['test_name'],
            'total_subjects' => (int) $overallRow['total_subjects'],
            'total_questions' => (int) $overallRow['total_questions'],
            'total_correct' => (int) $overallRow['total_correct'],
            'total_wrong' => (int) $overallRow['total_wrong'],
            'total_not_answered' => (int) $overallRow['total_not_answered'],
            'overall_percentage' => (float) $overallRow['overall_percentage'],
            'overall_result' => (string) $overallRow['result_status'],
            'merit_position' => isset($overallRow['merit_position']) ? (int) $overallRow['merit_position'] : null,
            'subjects' => $subjects,
        ];

        $totalSubjects += (int) $overallRow['total_subjects'];
        $totalQuestions += (int) $overallRow['total_questions'];
        $totalCorrect += (int) $overallRow['total_correct'];
        $totalWrong += (int) $overallRow['total_wrong'];
        $totalNotAnswered += (int) $overallRow['total_not_answered'];
    }

    $overallPercentage = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0.0;

    return [
        'student' => $student,
        'tests' => $tests,
        'overall' => [
            'total_subjects' => $totalSubjects,
            'total_questions' => $totalQuestions,
            'total_correct' => $totalCorrect,
            'total_wrong' => $totalWrong,
            'total_not_answered' => $totalNotAnswered,
            'percentage' => $overallPercentage,
            'result_status' => pafPassStatus($overallPercentage),
        ],
    ];
}
