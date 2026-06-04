<?php
require_once 'db_config.php';

$pdo = getPDOConnection();
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Function to delete a result
if (isset($_POST['delete_result'])) {
    $resultId = $_POST['result_id'];
    $pdo->prepare("DELETE FROM results WHERE id = ?")->execute([$resultId]);
}

// Function to calculate and display the results
function calculateAndStoreResults($pdo) {
    // Fetch all answers
    $answers = $pdo->query("
        SELECT user_id, question_id, answer, is_correct
        FROM answers
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all questions with their subject IDs and correct answers
    $questions = $pdo->query("
        SELECT id AS question_id, subject_id, correct_answer
        FROM questions
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data structures for calculating results
    $resultsData = [];
    $questionsMap = [];

    // Map questions to their subject IDs and correct answers
    foreach ($questions as $question) {
        $questionsMap[$question['question_id']] = [
            'subject_id' => $question['subject_id'],
            'correct_answer' => $question['correct_answer']
        ];
    }

    // Calculate results
    foreach ($answers as $answer) {
        $userId = $answer['user_id'];
        $questionId = $answer['question_id'];
        $userAnswer = $answer['answer'];

        if (!isset($questionsMap[$questionId])) {
            continue; // Skip if question ID not found
        }

        $subjectId = $questionsMap[$questionId]['subject_id'];
        $correctAnswer = $questionsMap[$questionId]['correct_answer'];

        if (!isset($resultsData[$userId])) {
            $resultsData[$userId] = [
                'total_correct_answers' => 0,
                'total_questions' => 0,
                'subject_data' => []
            ];
        }

        if (!isset($resultsData[$userId]['subject_data'][$subjectId])) {
            $resultsData[$userId]['subject_data'][$subjectId] = [
                'correct_answers' => 0,
                'total_questions' => 0
            ];
        }

        // Check if the user's answer matches the correct answer
        if ($userAnswer === $correctAnswer) {
            $resultsData[$userId]['total_correct_answers'] += 1;
            $resultsData[$userId]['subject_data'][$subjectId]['correct_answers'] += 1;
        }

        $resultsData[$userId]['total_questions'] += 1;
        $resultsData[$userId]['subject_data'][$subjectId]['total_questions'] += 1;
    }

    // Insert or update results only if they don't already exist
    foreach ($resultsData as $userId => $data) {
        foreach ($data['subject_data'] as $subjectId => $subjectData) {
            // Check if result already exists for this user and subject
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM results WHERE user_id = ? AND subject_id = ?
            ");
            $stmt->execute([$userId, $subjectId]);
            $existingResult = $stmt->fetchColumn();

            // If result doesn't exist, insert it
            if ($existingResult == 0) {
                $correctAnswers = $subjectData['correct_answers'];
                $totalQuestions = $subjectData['total_questions'];
                $percentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

                $stmt = $pdo->prepare("
                    INSERT INTO results (user_id, subject_id, correct_answers, total_questions, percentage)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$userId, $subjectId, $correctAnswers, $totalQuestions, $percentage]);
            }
        }
    }
}



function displayResults($pdo) {
    // Fetch all results, grouped by user_id and subject_id
    $results = $pdo->query("
        SELECT r.user_id, s.name AS subject_name, 
               r.correct_answers, r.total_questions, 
               r.percentage
        FROM results r
        JOIN subjects s ON r.subject_id = s.id
        ORDER BY r.user_id, r.subject_id
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Count occurrences of each user ID to calculate rowspan
    $userCounts = [];
    foreach ($results as $result) {
        if (!isset($userCounts[$result['user_id']])) {
            $userCounts[$result['user_id']] = 0;
        }
        $userCounts[$result['user_id']]++;
    }

    // Initialize variables for cumulative results
    $cumulativeResults = [];
    $lastUserId = null;

    echo '<div class="container mt-4">';
    echo '<div class="row">';
    echo '<div class="col">';
    echo '<table class="table table-striped table-bordered">';
    echo '<thead>';
    echo '<tr><th>User ID</th><th>Subject</th><th>Correct Answers</th><th>Total Questions</th><th>Percentage</th></tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($results as $result) {
        // Display user ID only once, with rowspan
        if ($lastUserId !== $result['user_id']) {
            // Display cumulative result for the previous user
            if ($lastUserId !== null) {
                $totalCorrectAnswers = $cumulativeResults[$lastUserId]['total_correct_answers'];
                $totalQuestions = $cumulativeResults[$lastUserId]['total_questions'];
                $cumulativePercentage = ($totalQuestions > 0) ? ($totalCorrectAnswers / $totalQuestions) * 100 : 0;

                echo '<tr>';
                echo '<td colspan="2"><strong>Cumulative</strong></td>';
                echo '<td>' . htmlspecialchars($totalCorrectAnswers) . '</td>';
                echo '<td>' . htmlspecialchars($totalQuestions) . '</td>';
                echo '<td>' . number_format($cumulativePercentage, 2) . '%</td>';
                echo '</tr>';
            }

            // Reset cumulative results for the new user
            $lastUserId = $result['user_id'];
            $cumulativeResults[$lastUserId] = [
                'total_correct_answers' => 0,
                'total_questions' => 0,
            ];

            // Display the user ID with proper rowspan
            echo '<tr>';
            echo '<td rowspan="' . $userCounts[$lastUserId] . '">' . htmlspecialchars($lastUserId) . '</td>';
            echo '<td>' . htmlspecialchars($result['subject_name']) . '</td>';
            echo '<td>' . htmlspecialchars($result['correct_answers']) . '</td>';
            echo '<td>' . htmlspecialchars($result['total_questions']) . '</td>';
            echo '<td>' . number_format($result['percentage'], 2) . '%</td>';
            echo '</tr>';
        } else {
            // Display subject-specific results for the same user
            echo '<tr>';
            echo '<td>' . htmlspecialchars($result['subject_name']) . '</td>';
            echo '<td>' . htmlspecialchars($result['correct_answers']) . '</td>';
            echo '<td>' . htmlspecialchars($result['total_questions']) . '</td>';
            echo '<td>' . number_format($result['percentage'], 2) . '%</td>';
            echo '</tr>';
        }

        // Add to cumulative results
        $cumulativeResults[$lastUserId]['total_correct_answers'] += $result['correct_answers'];
        $cumulativeResults[$lastUserId]['total_questions'] += $result['total_questions'];
    }

    // Display cumulative result for the last user
    if ($lastUserId !== null) {
        $totalCorrectAnswers = $cumulativeResults[$lastUserId]['total_correct_answers'];
        $totalQuestions = $cumulativeResults[$lastUserId]['total_questions'];
        $cumulativePercentage = ($totalQuestions > 0) ? ($totalCorrectAnswers / $totalQuestions) * 100 : 0;

        echo '<tr>';
        echo '<td colspan="2"><strong>Cumulative</strong></td>';
        echo '<td>' . htmlspecialchars($totalCorrectAnswers) . '</td>';
        echo '<td>' . htmlspecialchars($totalQuestions) . '</td>';
        echo '<td>' . number_format($cumulativePercentage, 2) . '%</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
if (isset($_POST['calculate_results'])) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM results");
    $resultCount = $stmt->fetchColumn();
    
    if ($resultCount > 0) {
        echo "<div class='alert alert-warning'>Results have already been calculated. Please view them.</div>";
    } else {
        calculateAndStoreResults($pdo);
        echo "<div class='alert alert-success'>Results calculated successfully!</div>";
    }
}

if (isset($_POST['show_results'])) {
    displayResults($pdo);
}

// Call the displayResults function
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
    <style>
        /* Custom CSS to improve table and section layout */
        section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        button {
            margin-left: 15px;
        }

        .main-content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            margin-top: 20px;
        }

        td, th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Welcome to the Admin Panel</h1>
            </header>
            <section>
                <h2>All Users</h2>
                <form method="POST">
                    <button class="btn btn-primary" type="submit" name="show_results">Show All Results</button>
                    <button class="btn btn-primary" type="submit" name="calculate_results">Calculate Results</button>
                </form>
            </section>

            <div style="width:100%;">
                <?php
               if (isset($_POST['show_results'])) {
                    displayResults($pdo);
               }
                ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
