<?php
include "config.php"; // Ensure your DB connection is set up here
require 'vendor/autoload.php'; // Assuming TCPDF is installed via Composer

// Initialize TCPDF
$pdf = new TCPDF();

// Function to calculate and store results
function calculateAndStoreResults($conn)
{
    // Fetch all answers
    $answers = [];
    $answerResult = $conn->query("SELECT user_id, question_id, answer, is_correct FROM answers");

    while ($row = $answerResult->fetch_assoc()) {
        $answers[] = $row;
    }

    // Fetch all questions with their subject IDs and correct answers
    $questions = [];
    $questionResult = $conn->query("SELECT id AS question_id, subject_id, correct_answer FROM questions");

    while ($row = $questionResult->fetch_assoc()) {
        $questions[] = $row;
    }

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
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM results WHERE user_id = ? AND subject_id = ?");
            $stmt->bind_param("si", $userId, $subjectId); // Bind user_id as string
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $existingResult = $row['count'];
            $stmt->close();

            // If result doesn't exist, insert it
            if ($existingResult == 0) {
                $correctAnswers = $subjectData['correct_answers'];
                $totalQuestions = $subjectData['total_questions'];
                $percentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

                $stmt = $conn->prepare("INSERT INTO results (user_id, subject_id, correct_answers, total_questions, percentage) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siidd", $userId, $subjectId, $correctAnswers, $totalQuestions, $percentage); // Bind user_id as string
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// Call the function if user_id is set
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    

    // Usage example
    calculateAndStoreResults($conn);

    // Fetch user information
    $userSql = "SELECT id, name, father_name, picture FROM useres WHERE id = ?";
    $stmt = $conn->prepare($userSql);
    $stmt->bind_param("s", $userId); // Bind user_id as string
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();

    if ($user) {
        // Fetch user results and join with subjects table to get subject name
        $resultSql = "SELECT s.name AS subject_name, r.correct_answers, r.total_questions, r.percentage 
                      FROM results r
                      JOIN subjects s ON r.subject_id = s.id 
                      WHERE r.user_id = ?";
        $stmt = $conn->prepare($resultSql);
        $stmt->bind_param("s", $userId); // Bind user_id as string
        $stmt->execute();
        $resultData = $stmt->get_result();

        // Initialize total variables
        $subjectResults = [];
        $totalCorrect = 0;
        $totalQuestions = 0;

        // Collect results per subject
        while ($row = $resultData->fetch_assoc()) {
            $subjectResults[$row['subject_name']] = [
                'correct_answers' => $row['correct_answers'],
                'total_questions' => $row['total_questions'],
                'percentage' => $row['percentage']
            ];

            // Calculate overall totals
            $totalCorrect += $row['correct_answers'];
            $totalQuestions += $row['total_questions'];
        }

        // Calculate overall percentage
        $overallPercentage = ($totalQuestions > 0) ? ($totalCorrect / $totalQuestions) * 100 : 0;

        // Create HTML for result display
        $resultHtml = "<div class='container'>
                       <p><strong>User ID:</strong> {$user['id']}</p>
                       <p><strong>Name:</strong> {$user['name']}</p>
                       <p><strong>Father's Name:</strong> {$user['father_name']}</p>";
        if (!empty($user['picture'])) {
            $resultHtml .= "<img src='{$user['picture']}' width='100' height='100' alt='User Image' class='img-thumbnail'>";
        }
        $resultHtml .= "<h3>Results:</h3>
                        <table class='table table-bordered'>
                        <thead><tr><th>Subject</th><th>Correct Answers</th><th>Total Questions</th><th>Percentage</th></tr></thead>
                        <tbody>";

        // Populate results for each subject
        foreach ($subjectResults as $subject => $results) {
            $resultHtml .= "<tr>
                            <td>{$subject}</td>
                            <td>{$results['correct_answers']}</td>
                            <td>{$results['total_questions']}</td>
                            <td>{$results['percentage']}%</td>
                            </tr>";
        }
        $resultHtml .= "</tbody></table>";

        // Append total result information to the HTML
        $resultHtml .= "<h4>Total Result:</h4>
                        <p><strong>Total Correct Answers:</strong> $totalCorrect</p>
                        <p><strong>Total Questions:</strong> $totalQuestions</p>
                        <p><strong>Overall Percentage:</strong> " . round($overallPercentage, 2) . "%</p></div>";

        // Output result table for AJAX
        echo $resultHtml;

        // Generate PDF with TCPDF
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        // Set the width for the left column (text)
        $leftColumnWidth = 100; // Adjust this width as needed
        $pdf->Cell($leftColumnWidth, 10, "User Result", 0, 1, 'C');
        $pdf->Ln();

        // User ID, Name, Father's Name on the left
        $pdf->Cell($leftColumnWidth, 10, "User ID: " . $user['id'], 0, 1);
        $pdf->Cell($leftColumnWidth, 10, "Name: " . $user['name'], 0, 1);
        $pdf->Cell($leftColumnWidth, 10, "Father's Name: " . $user['father_name'], 0, 1);

        // Move to the right for the image
        $pdf->Cell(0, 10, '', 0, 1); // Empty cell to create space
        if (!empty($user['picture'])) {
            // Add Picture to PDF on the right side
            $pdf->Image($user['picture'], $pdf->getX() + 2, $pdf->getY() - 10, 30); // Adjust position as needed
        }

        $pdf->Ln(20);

        // Add result table to PDF
        $pdf->writeHTML($resultHtml, true, false, true, false, '');

        // Ensure the directory exists for saving PDFs
        $resultsDir = __DIR__ . '/results'; // Full path to the results directory
        if (!is_dir($resultsDir)) {
            mkdir($resultsDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Output the PDF to a file
        $fileName = "$resultsDir/user_result_" . $user['id'] . ".pdf";
        $pdf->Output($fileName, 'F'); // Save the file

        // Provide a download link and Check Answers button
        echo "<div class='mt-3'>
                <a href='results/user_result_" . $user['id'] . ".pdf' class='btn btn-primary' style='padding: 10px 20px; background-color: green; color: white; border-radius: 5px; text-decoration:none; margin-right: 10px;'>Download PDF</a>
                <button type='button' onclick='window.parent.showAnswerDetails(\"" . $user['id'] . "\")' class='btn btn-info' style='padding: 10px 20px; margin-top:2rem; background-color: #17a2b8; color: white; border: none; border-radius: 5px; cursor: pointer;'>Check Answers</button>
              </div>";

    } else {
        echo "<p>User not found.</p>";
    }
}
?>
