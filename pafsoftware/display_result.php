<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'db_config.php';

$pdo = getPDOConnection();


// Fetch all users
$users = $pdo->query("SELECT DISTINCT user_id FROM answers")->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $userId = $user['user_id'];

    // Get all answers of the user
    $answers = $pdo->prepare("SELECT * FROM answers WHERE user_id = ?");
    $answers->execute([$userId]);

    $correctCount = 0;
    $totalQuestions = 0;

    // Check each answer against the correct answer
    while ($answer = $answers->fetch(PDO::FETCH_ASSOC)) {
        $questionId = $answer['question_id'];
        $userAnswer = $answer['answer'];

        $question = $pdo->prepare("SELECT correct_answer FROM questions WHERE id = ?");
        $question->execute([$questionId]);
        $correctAnswer = $question->fetchColumn();

        $totalQuestions++;
        if ($userAnswer == $correctAnswer) {
            $correctCount++;
        }
    }

    // Calculate percentage
    $percentage = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;

    // Insert into results table or update if already exists
    $resultExists = $pdo->prepare("SELECT id FROM results WHERE user_id = ?");
    $resultExists->execute([$userId]);

    if ($resultExists->rowCount() > 0) {
        $pdo->prepare("UPDATE results SET correct_answers = ?, total_questions = ?, percentage = ? WHERE user_id = ?")
            ->execute([$correctCount, $totalQuestions, $percentage, $userId]);
    } else {
        $pdo->prepare("INSERT INTO results (user_id, correct_answers, total_questions, percentage) VALUES (?, ?, ?, ?)")
            ->execute([$userId, $correctCount, $totalQuestions, $percentage]);
    }
}

// Function to display the results
function displayResults($pdo) {
    $results = $pdo->query("SELECT * FROM results")->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1'>";
    echo "<tr><th>User ID</th><th>Correct Answers</th><th>Total Questions</th><th>Percentage</th></tr>";

    foreach ($results as $result) {
        echo "<tr>";
        echo "<td>" . $result['user_id'] . "</td>";
        echo "<td>" . $result['correct_answers'] . "</td>";
        echo "<td>" . $result['total_questions'] . "</td>";
        echo "<td>" . $result['percentage'] . "%</td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Button to display results
if (isset($_POST['show_results'])) {
    displayResults($pdo);
}
?>

<form method="POST">
    <button type="submit" name="show_results">Show All Results</button>
</form>
