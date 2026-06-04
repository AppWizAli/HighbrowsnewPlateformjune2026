<?php

require_once __DIR__ . '/db_config.php';

function pafNormaliseUserId($userId): string
{
    return trim((string) $userId);
}

function pafFetchTests(PDO $pdo): array
{
    $statement = $pdo->query('SELECT id, test_name, date_added FROM tests ORDER BY test_name ASC');
    return $statement->fetchAll();
}

function pafFetchTestSubjects(PDO $pdo, int $testId): array
{
    $statement = $pdo->prepare(
        'SELECT s.id, s.name, s.time_in_minutes, t.test_name,
                (SELECT COUNT(*) FROM questions q WHERE q.subject_id = s.id) AS question_count
         FROM subjects s
         INNER JOIN tests t ON t.id = s.test_id
         WHERE s.test_id = ?
         ORDER BY s.id ASC'
    );
    $statement->execute([$testId]);

    return $statement->fetchAll();
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
    foreach ($statement->fetchAll() as $row) {
        $statuses[(int) $row['question_id']] = [
            'answer' => (string) ($row['answer'] ?? ''),
            'mark_for_review' => (int) ($row['mark_for_review'] ?? 0),
            'is_skipped' => (int) ($row['is_skipped'] ?? 0),
        ];
    }

    return $statuses;
}

function pafStatusName(array $status): string
{
    if (($status['mark_for_review'] ?? 0) === 1) {
        return 'review';
    }

    $answer = (string) ($status['answer'] ?? '');
    if ($answer !== '' && $answer !== 'F') {
        return 'answered';
    }

    if (($status['is_skipped'] ?? 0) === 1 || $answer === 'F') {
        return 'skipped';
    }

    return 'pending';
}

function pafStatusLabel(array $status): string
{
    $statusName = pafStatusName($status);

    if ($statusName === 'review') {
        return 'Review';
    }

    if ($statusName === 'answered') {
        return 'Answered';
    }

    if ($statusName === 'skipped') {
        return 'Skipped';
    }

    return 'Pending';
}

function pafCalculateSubjectResult(PDO $pdo, string $userId, int $subjectId): array
{
    $metaStatement = $pdo->prepare(
        'SELECT s.id, s.name AS subject_name, s.test_id, t.test_name
         FROM subjects s
         INNER JOIN tests t ON t.id = s.test_id
         WHERE s.id = ?'
    );
    $metaStatement->execute([$subjectId]);
    $meta = $metaStatement->fetch();

    if (!$meta) {
        return [
            'subject_id' => $subjectId,
            'subject_name' => 'Unknown Subject',
            'test_id' => null,
            'test_name' => 'Unknown Test',
            'total_questions' => 0,
            'attempted_questions' => 0,
            'correct_answers' => 0,
            'wrong_answers' => 0,
            'skipped_questions' => 0,
            'review_questions' => 0,
            'percentage' => 0.0,
        ];
    }

    $summaryStatement = $pdo->prepare(
        'SELECT
            COUNT(q.id) AS total_questions,
            SUM(CASE WHEN a.question_id IS NOT NULL THEN 1 ELSE 0 END) AS attempted_questions,
            SUM(CASE WHEN a.answer = q.correct_answer THEN 1 ELSE 0 END) AS correct_answers,
            SUM(CASE
                    WHEN a.question_id IS NOT NULL
                     AND a.answer IS NOT NULL
                     AND a.answer <> \'\'
                     AND a.answer <> \'F\'
                     AND a.answer <> q.correct_answer
                    THEN 1 ELSE 0
                END) AS wrong_answers,
            SUM(CASE
                    WHEN a.question_id IS NULL
                     OR a.is_skipped = 1
                     OR a.answer = \'F\'
                     OR a.answer = \'\'
                    THEN 1 ELSE 0
                END) AS skipped_questions,
            SUM(CASE WHEN a.mark_for_review = 1 THEN 1 ELSE 0 END) AS review_questions
         FROM questions q
         LEFT JOIN answers a
            ON a.question_id = q.id
           AND a.user_id = ?
         WHERE q.subject_id = ?'
    );
    $summaryStatement->execute([$userId, $subjectId]);
    $summary = $summaryStatement->fetch();

    $totalQuestions = (int) ($summary['total_questions'] ?? 0);
    $correctAnswers = (int) ($summary['correct_answers'] ?? 0);
    $percentage = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0.0;

    return [
        'subject_id' => (int) $meta['id'],
        'subject_name' => (string) $meta['subject_name'],
        'test_id' => (int) $meta['test_id'],
        'test_name' => (string) $meta['test_name'],
        'total_questions' => $totalQuestions,
        'attempted_questions' => (int) ($summary['attempted_questions'] ?? 0),
        'correct_answers' => $correctAnswers,
        'wrong_answers' => (int) ($summary['wrong_answers'] ?? 0),
        'skipped_questions' => (int) ($summary['skipped_questions'] ?? 0),
        'review_questions' => (int) ($summary['review_questions'] ?? 0),
        'percentage' => $percentage,
    ];
}

function pafUpsertSubjectResult(PDO $pdo, string $userId, int $subjectId): array
{
    $summary = pafCalculateSubjectResult($pdo, $userId, $subjectId);

    $existingStatement = $pdo->prepare('SELECT id FROM results WHERE user_id = ? AND subject_id = ? LIMIT 1');
    $existingStatement->execute([$userId, $subjectId]);
    $existingId = $existingStatement->fetchColumn();

    if ($existingId) {
        $updateStatement = $pdo->prepare(
            'UPDATE results
             SET correct_answers = ?, total_questions = ?, percentage = ?
             WHERE id = ?'
        );
        $updateStatement->execute([
            $summary['correct_answers'],
            $summary['total_questions'],
            $summary['percentage'],
            $existingId,
        ]);
    } else {
        $insertStatement = $pdo->prepare(
            'INSERT INTO results (user_id, subject_id, correct_answers, total_questions, percentage)
             VALUES (?, ?, ?, ?, ?)'
        );
        $insertStatement->execute([
            $userId,
            $subjectId,
            $summary['correct_answers'],
            $summary['total_questions'],
            $summary['percentage'],
        ]);
    }

    return $summary;
}

function pafCompletedSubjectIds(PDO $pdo, string $userId, array $subjectIds): array
{
    $subjectIds = array_values(array_filter(array_map('intval', $subjectIds)));
    if ($subjectIds === []) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
    $statement = $pdo->prepare(
        "SELECT subject_id
         FROM results
         WHERE user_id = ? AND subject_id IN ($placeholders)"
    );
    $statement->execute(array_merge([$userId], $subjectIds));

    return array_map('intval', array_column($statement->fetchAll(), 'subject_id'));
}

function pafGetUserResultTree(PDO $pdo, string $userId, ?int $testId = null): array
{
    $params = [$userId];
    $sql = 'SELECT DISTINCT t.id, t.test_name
            FROM results r
            INNER JOIN subjects s ON s.id = r.subject_id
            INNER JOIN tests t ON t.id = s.test_id
            WHERE r.user_id = ?';

    if ($testId !== null) {
        $sql .= ' AND t.id = ?';
        $params[] = $testId;
    }

    $sql .= ' ORDER BY t.test_name ASC';

    $testsStatement = $pdo->prepare($sql);
    $testsStatement->execute($params);
    $tests = $testsStatement->fetchAll();

    $tree = [
        'tests' => [],
        'overall' => [
            'correct_answers' => 0,
            'total_questions' => 0,
            'percentage' => 0.0,
            'subjects' => 0,
        ],
    ];

    foreach ($tests as $test) {
        $subjects = pafFetchTestSubjects($pdo, (int) $test['id']);
        $subjectSummaries = [];
        $testCorrect = 0;
        $testTotal = 0;

        foreach ($subjects as $subject) {
            $completedIds = pafCompletedSubjectIds($pdo, $userId, [(int) $subject['id']]);
            if ($completedIds === []) {
                continue;
            }

            $summary = pafUpsertSubjectResult($pdo, $userId, (int) $subject['id']);
            $subjectSummaries[] = $summary;
            $testCorrect += $summary['correct_answers'];
            $testTotal += $summary['total_questions'];
        }

        if ($subjectSummaries === []) {
            continue;
        }

        $testPercentage = $testTotal > 0 ? round(($testCorrect / $testTotal) * 100, 2) : 0.0;
        $tree['tests'][] = [
            'test_id' => (int) $test['id'],
            'test_name' => (string) $test['test_name'],
            'correct_answers' => $testCorrect,
            'total_questions' => $testTotal,
            'percentage' => $testPercentage,
            'subjects' => $subjectSummaries,
        ];

        $tree['overall']['correct_answers'] += $testCorrect;
        $tree['overall']['total_questions'] += $testTotal;
        $tree['overall']['subjects'] += count($subjectSummaries);
    }

    if ($tree['overall']['total_questions'] > 0) {
        $tree['overall']['percentage'] = round(
            ($tree['overall']['correct_answers'] / $tree['overall']['total_questions']) * 100,
            2
        );
    }

    return $tree;
}

function pafAnswerLabel(string $answer, array $row): string
{
    $map = [
        'A' => $row['option_a'] ?? '',
        'B' => $row['option_b'] ?? '',
        'C' => $row['option_c'] ?? '',
        'D' => $row['option_d'] ?? '',
        'E' => $row['option_e'] ?? '',
    ];

    if ($answer === 'F' || $answer === '') {
        return 'No selection';
    }

    $text = trim((string) ($map[$answer] ?? ''));
    if ($text === '') {
        return $answer;
    }

    return $answer . '. ' . $text;
}

function pafFetchAnswerReviewData(PDO $pdo, string $userId, ?int $testId = null, ?int $subjectId = null): array
{
    $params = [$userId];
    $sql = 'SELECT
                t.id AS test_id,
                t.test_name,
                s.id AS subject_id,
                s.name AS subject_name,
                q.id AS question_id,
                q.sequence_number,
                q.question_text,
                q.option_a,
                q.option_b,
                q.option_c,
                q.option_d,
                q.option_e,
                q.correct_answer,
                a.answer AS user_answer,
                a.mark_for_review,
                a.is_skipped
            FROM questions q
            INNER JOIN subjects s ON s.id = q.subject_id
            INNER JOIN tests t ON t.id = s.test_id
            LEFT JOIN answers a
                ON a.question_id = q.id
               AND a.user_id = ?';

    $conditions = [];
    if ($testId !== null) {
        $conditions[] = 't.id = ?';
        $params[] = $testId;
    }
    if ($subjectId !== null) {
        $conditions[] = 's.id = ?';
        $params[] = $subjectId;
    }

    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY t.test_name ASC, s.name ASC, q.sequence_number ASC, q.id ASC';

    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    $grouped = [];
    foreach ($statement->fetchAll() as $row) {
        $testKey = (int) $row['test_id'];
        $subjectKey = (int) $row['subject_id'];

        if (!isset($grouped[$testKey])) {
            $grouped[$testKey] = [
                'test_id' => $testKey,
                'test_name' => (string) $row['test_name'],
                'subjects' => [],
            ];
        }

        if (!isset($grouped[$testKey]['subjects'][$subjectKey])) {
            $grouped[$testKey]['subjects'][$subjectKey] = [
                'subject_id' => $subjectKey,
                'subject_name' => (string) $row['subject_name'],
                'questions' => [],
            ];
        }

        $userAnswer = (string) ($row['user_answer'] ?? '');
        $correctAnswer = (string) ($row['correct_answer'] ?? '');
        $isCorrect = $userAnswer !== '' && $userAnswer === $correctAnswer;

        $grouped[$testKey]['subjects'][$subjectKey]['questions'][] = [
            'question_id' => (int) $row['question_id'],
            'sequence_number' => (int) ($row['sequence_number'] ?? 0),
            'question_text' => (string) $row['question_text'],
            'user_answer' => $userAnswer,
            'user_answer_label' => pafAnswerLabel($userAnswer, $row),
            'correct_answer' => $correctAnswer,
            'correct_answer_label' => pafAnswerLabel($correctAnswer, $row),
            'is_correct' => $isCorrect,
            'mark_for_review' => (int) ($row['mark_for_review'] ?? 0),
            'is_skipped' => (int) ($row['is_skipped'] ?? 0),
        ];
    }

    foreach ($grouped as &$test) {
        $test['subjects'] = array_values($test['subjects']);
    }
    unset($test);

    return array_values($grouped);
}
