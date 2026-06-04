<?php
include 'config.php'; // Ensure this file correctly sets up $conn and other configurations
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tests for the filter
$tests_query = "SELECT DISTINCT id, test_name FROM tests"; // Ensure the query includes the necessary columns
$tests_result = $conn->query($tests_query);

// Check if the query was successful
if ($tests_result === false) {
    echo "Error: " . $conn->error; // Output error if query fails
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Welcome to the Admin Panel</h1>
            </header>
            <section>
                <h2>Show All Questions</h2>

                <!-- Filters for Test and Subject -->
                <div class="filters mb-3">
                    <label for="testSelect" onchange="loadSubjects()">Select Test:</label>
                    <select id="testSelect" class="form-control" onchange="loadSubjects()">
                        <option value="">Select Test</option>
                        <?php
                        if ($tests_result->num_rows > 0) {
                            while ($test_row = $tests_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($test_row['id']) . "'>" . htmlspecialchars($test_row['test_name']) . "</option>";  // test_id as value
                            }
                        }
                        ?>
                    </select>

                    <label for="subjectSelect">Select Subject:</label>
                    <select id="subjectSelect" class="form-control" onchange="loadQuestions()">
                        <option value="">Select Subject</option>
                    </select>

                    <button class="btn btn-danger mt-2" onclick="deleteAllQuestions()">Delete All Questions</button>
                </div>
            </section>

            <div class="main2">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Question Number</th>
                                <th>Question Text</th>
                                <th>Question Image</th>
                                <th>Options A</th>
                                <th>Options B</th>
                                <th>Options C</th>
                                <th>Options D</th>
                                <th>Options E</th>
                                <th>Correct Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="questionsTable">
                            <!-- Questions will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function loadSubjects() {
            var test_id = $('#testSelect').val();  // Fetch the test_id from the select box
            console.log('Selected test_id:', test_id);  // Log test_id to the console for debugging

            if (test_id) {  // Ensure that test_id is not empty
                $('#subjectSelect').html('<option value="">Loading subjects...</option>');
                $('#questionsTable').html('');  // Clear questions when switching subjects

                $.ajax({
                    url: 'load_subjects.php',
                    type: 'POST',
                    data: { test_id: test_id },  // Send test_id to the server
                    dataType: 'html',
                    success: function(data) {
                        console.log('Subjects data loaded:', data);  

                        if ($.trim(data) !== '') {
                            $('#subjectSelect').html('<option value="">Select a subject</option>' + data);

                            var subjectCount = $('#subjectSelect option').length;  

                            if (subjectCount === 2) {
                                var singleSubjectId = $('#subjectSelect option:eq(1)').val();  
                                $('#subjectSelect').val(singleSubjectId);  
                                loadQuestions(singleSubjectId);  
                            } else if (subjectCount > 2) {
                                $('#subjectSelect').val($('#subjectSelect option:eq(1)').val()).change(); 
                            }
                        } else {
                            console.warn('No subjects found or invalid data returned.');
                            $('#subjectSelect').html('<option value="">No subjects available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error - Status:', status, 'Error:', error);
                        $('#subjectSelect').html('<option value="">Failed to load subjects</option>');
                        $('#questionsTable').html('');
                    }
                });
            } else {
                console.log('No test selected, resetting subject dropdown.');  
                $('#subjectSelect').html('<option value="">Select a subject</option>');
                $('#questionsTable').html('');
            }
        }

        // Function to load questions based on selected subject
        function loadQuestions(subject_id) {
            var subject_id = $('#subjectSelect').val();  
            console.log('Selected subject_id:', subject_id);  

            if (subject_id) {
                $('#questionsTable').html('<tr><td>Loading questions...</td></tr>');

                $.ajax({
                    url: 'load_questions.php',  
                    type: 'POST',
                    data: { subject_id: subject_id },  
                    dataType: 'html',
                    success: function(data) {
                        console.log('Questions data loaded:', data);  
                        
                        if ($.trim(data) !== '') {
                            $('#questionsTable').html(data);  
                        } else {
                            console.warn('No questions found or invalid data returned.');
                            $('#questionsTable').html('<tr><td>No questions available</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error - Status:', status, 'Error:', error);
                        $('#questionsTable').html('<tr><td>Failed to load questions</td></tr>');
                    }
                });
            } else {
                console.log('No subject selected, resetting questions table.');
                $('#questionsTable').html('<tr><td>Select a subject to view questions</td></tr>');
            }
        }

        // Function to delete all questions for the selected subject
        function deleteAllQuestions() {
            var subject_id = $('#subjectSelect').val();  
            if (subject_id && confirm("Are you sure you want to delete all questions for this subject?")) {
                $.ajax({
                    url: 'delete_all_questions.php',
                    type: 'POST',
                    data: { subject_id: subject_id },
                    success: function(response) {
                        if (response == 'success') {
                            alert('All questions deleted successfully.');
                            $('#questionsTable').html('');
                        } else {
                            alert('Error deleting questions.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error - Status:', status, 'Error:', error);
                        alert('Failed to delete questions.');
                    }
                });
            }
        }

        // Enable editing for a question
        function enableEdit(questionId) {
            document.getElementById('question_text_' + questionId).removeAttribute('readonly');
            document.getElementById('option_a_' + questionId).removeAttribute('readonly');
            document.getElementById('option_b_' + questionId).removeAttribute('readonly');
            document.getElementById('option_c_' + questionId).removeAttribute('readonly');
            document.getElementById('option_d_' + questionId).removeAttribute('readonly');
            document.getElementById('option_e_' + questionId).removeAttribute('readonly'); // Enable Option E
            document.getElementById('saveBtn_' + questionId).style.display = 'inline';
        }

        // Save edited question data
        function saveQuestion(questionId) {
            const questionText = document.getElementById('question_text_' + questionId).value;
            const optionA = document.getElementById('option_a_' + questionId).value;
            const optionB = document.getElementById('option_b_' + questionId).value;
            const optionC = document.getElementById('option_c_' + questionId).value;
            const optionD = document.getElementById('option_d_' + questionId).value;
            const optionE = document.getElementById('option_e_' + questionId).value; // Get Option E value
            const correctAnswer = document.getElementById('correct_answer_' + questionId).value;

            $.ajax({
                url: 'save_question.php',
                type: 'POST',
                data: {
                    id: questionId,
                    question_text: questionText,
                    option_a: optionA,
                    option_b: optionB,
                    option_c: optionC,
                    option_d: optionD,
                    option_e: optionE, // Include Option E in the data
                    correct_answer: correctAnswer
                },
                success: function(response) {
                    if (response == 'success') {
                        alert('Question updated successfully.');
                        document.getElementById('question_text_' + questionId).setAttribute('readonly', 'readonly');
                        document.getElementById('option_a_' + questionId).setAttribute('readonly', 'readonly');
                        document.getElementById('option_b_' + questionId).setAttribute('readonly', 'readonly');
                        document.getElementById('option_c_' + questionId).setAttribute('readonly', 'readonly');
                        document.getElementById('option_d_' + questionId).setAttribute('readonly', 'readonly');
                        document.getElementById('option_e_' + questionId).setAttribute('readonly', 'readonly'); // Disable Option E
                        document.getElementById('saveBtn_' + questionId).style.display = 'none';
                    } else {
                        alert('Error updating question.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error - Status:', status, 'Error:', error);
                    alert('Failed to update question.');
                }
            });
        }
    </script>
</body>

</html>
