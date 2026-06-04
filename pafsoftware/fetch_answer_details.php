<?php
include "config.php";

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $sql = "SELECT 
                q.id as question_id,
                q.question_text,
                q.option_a, q.option_b, q.option_c, q.option_d, q.option_e,
                q.correct_answer,
                a.answer as user_answer,
                s.name as subject_name,
                s.id as subject_id
            FROM answers a
            JOIN questions q ON a.question_id = q.id
            JOIN subjects s ON q.subject_id = s.id
            WHERE a.user_id = ?
            ORDER BY s.name, q.sequence_number";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $answers = [];
    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $answers[] = $row;
        if (!in_array($row['subject_name'], $subjects)) {
            $subjects[] = $row['subject_name'];
        }
    }

    if (count($answers) > 0) {
        echo "<div style='max-height: 800px; height: 85vh !important; overflow-y: auto;'>";
        echo "<h3>Your Answers Review</h3>";

        echo "<div style='margin-bottom: 20px;'>";
        echo "<label for='subjectFilter' style='font-size: 1.2rem; font-weight: bold; margin-right: 10px;'>Select Subject:</label>";
        echo "<select id='subjectFilter' style='font-size: 1.1rem; padding: 8px 12px; border: 2px solid #1ba7fd; border-radius: 5px; min-width: 200px;' onchange='filterBySubject()'>";

        foreach ($subjects as $index => $subject) {
            $selected = ($index === 0) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($subject) . "' $selected>" . htmlspecialchars($subject) . "</option>";
        }

        echo "</select>";
        echo "</div>";

        $currentSubject = '';
        $firstSubject = true;
        foreach ($subjects as $subject) {
            $displayStyle = $firstSubject ? 'block' : 'none';
            echo "<div id='subject-" . htmlspecialchars($subject) . "' class='subject-section' style='display: $displayStyle;'>";
            echo "<h4 style='color: #1ba7fd; border-bottom: 2px solid #1ba7fd; padding-bottom: 5px; font-size: 1.5rem;'>" . htmlspecialchars($subject) . "</h4>";

            foreach ($answers as $answer) {
                if ($answer['subject_name'] == $subject) {
                    $isCorrect = ($answer['user_answer'] == $answer['correct_answer']);
                    $questionClass = $isCorrect ? 'correct-answer' : 'incorrect-answer';

                    echo "<div class='question-review " . $questionClass . "' style='margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #f8f9fa;'>";
                    echo "<p style='font-size: 1.3rem; margin-bottom: 15px;'><strong>Question:</strong> " . htmlspecialchars($answer['question_text']) . "</p>";

                    $options = ['A' => $answer['option_a'], 'B' => $answer['option_b'], 'C' => $answer['option_c'], 'D' => $answer['option_d'], 'E' => $answer['option_e']];

                    echo "<div class='options' style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px; margin-bottom: 15px;'>";
                    foreach ($options as $option => $text) {
                        if (!empty($text)) {
                            $optionClass = '';
                            if ($option == $answer['user_answer']) {
                                $optionClass = $isCorrect ? 'user-correct' : 'user-incorrect';
                            } elseif ($option == $answer['correct_answer']) {
                                $optionClass = 'correct-answer';
                            }

                            echo "<div class='option " . $optionClass . "' style='padding: 12px 15px; border: 2px solid #dee2e6; border-radius: 6px; font-size: 1.1rem;'>";
                            echo "<strong>" . $option . ":</strong> " . htmlspecialchars($text);
                            if ($option == $answer['user_answer']) {
                                echo " <span style='color: #ff6b6b; font-weight: bold;'>(Your Answer)</span>";
                            }
                            if ($option == $answer['correct_answer']) {
                                echo " <span style='color: #51cf66; font-weight: bold;'>(Correct Answer)</span>";
                            }
                            echo "</div>";
                        }
                    }
                    echo "</div>";

                    if (!$isCorrect) {
                        echo "<div class='result-indicator incorrect' style='background-color: #f8d7da; color: #721c24; border: 2px solid #dc3545; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 1.2rem; font-weight: bold;'>❌ Incorrect</div>";
                    } else {
                        echo "<div class='result-indicator correct' style='background-color: #d4edda; color: #155724; border: 2px solid #28a745; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 1.2rem; font-weight: bold;'>✅ Correct</div>";
                    }

                    echo "</div>";
                }
            }
            echo "</div>";
            $firstSubject = false;
        }

        echo "</div>";
    } else {
        echo "<p>No answers found for this user.</p>";
    }
} else {
    echo "<p>User ID not provided.</p>";
}
