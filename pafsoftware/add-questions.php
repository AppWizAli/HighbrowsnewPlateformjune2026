<?php
session_start();

include 'config.php'; // Make sure to include your DB connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch tests from the database
$query = "SELECT id, test_name FROM tests";
$result = mysqli_query($conn, $query);

$testOptions = "";
while ($row = mysqli_fetch_assoc($result)) {
    $testOptions .= "<option value='{$row['id']}'>{$row['test_name']}</option>";
}

// Check if test_id is set and valid
$testId = isset($_GET['test_id']) ? intval($_GET['test_id']) : 0;

$subjectOptions = "";
if ($testId > 0) {
    // Fetch subjects based on the selected test
    $query = "SELECT id, name, time_in_minutes FROM subjects WHERE test_id = $testId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $subjectOptions .= "<option value='{$row['id']}'>{$row['name']} (Time: {$row['time_in_minutes']} mins)</option>";
            }
        } else {
            $subjectOptions = "<option value=''>No subjects found</option>";
        }
    } else {
        $subjectOptions = "<option value=''>Error fetching subjects</option>";
    }
} else {
    $subjectOptions = "<option value=''>Select a test first</option>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
    <style>
        .form-group-inline {
            display: flex;
            align-items: center;
        }

        .form-group-inline label {
            margin-right: 10px;
            flex: 0 0 120px;
        }

        .form-group-inline .form-control {
            flex: 1;
        }

        .options-group {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .option-container {
            width: 48%;
            margin-bottom: 10px;
        }

        .option-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .option-container label {
            flex: 0 0 122px;
            /* Adjust as needed */
            margin-right: 5px;
        }

        .option-container .form-control,
        .option-container .form-control-file {
            flex: 1;
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
            <section class="main-session">
                <h2>Add Questions for Test and Subject</h2>
            </section>
            <div class="main2">
                <div class="container mt-4">
                    <form id="question-form" method="post" action="insert_questions.php" enctype="multipart/form-data">
                        <div style="display:flex; justify-content:space-between; width:70%;">
                            <div class="form-group-inline mb-2" style="width:50%">
                                <label for="test">Select Test:</label>
                                <select id="test" name="test_id" class="form-control" required>
                                    <?php echo $testOptions; ?>
                                </select>
                            </div>

                            <div class="form-group-inline mb-2" style="width:45%">
                                <label for="subject">Select Subject:</label>
                                <select id="subject" name="subject_id" class="form-control" required>
                                    <?php echo $subjectOptions; ?>
                                </select>
                            </div>
                        </div>
                        <h3>Import Excel file</h3>
                        <div class="form-group mb-2">

                            <label for="excelFile">Choose Excel file:</label>
                            <input type="file" name="excelFile" id="excelFile">
                        </div>

                        <button type="submit" name="import" class="btn btn-success mt-2">Import Questions</button>
                        <h3>Manually Add Questions</h3>
                        <div id="questionInputs"></div>
                        <button type="button" id="addQuestion" class="btn btn-primary mt-2">Add Another
                            Question</button>
                            <button type="submit" class="btn btn-success mt-2">Submit Questions</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            // Fetch tests from the database on page load
            $.ajax({
                url: 'get_tests.php',
                method: 'GET',
                success: function (data) {
                    $('#test').html(data);

                    // Trigger the change event once the tests are loaded to load the subjects for the first test
                    $('#test').trigger('change');
                },
                error: function () {
                    $('#test').html('<option value="">Error fetching tests</option>');
                }
            });

            // When a test is selected, fetch corresponding subjects
            $('#test').change(function () {
                const testId = $(this).val();
                if (testId) {
                    $.ajax({
                        url: 'get_subjects_by_test.php',
                        method: 'GET',
                        data: { test_id: testId },
                        success: function (data) {
                            $('#subject').html(data);
                        },
                        error: function () {
                            $('#subject').html('<option value="">Error fetching subjects</option>');
                        }
                    });
                } else {
                    $('#subject').html('<option value="">Select a test first</option>');
                }
            });

            // Function to add question fields dynamically
            function addQuestionFields() {
                const questionNumber = $('#questionInputs .question-group').length + 1;
                const inputGroup = $(`
            <div class="question-group mb-4" id="questionGroup${questionNumber}">
                <h3>Question ${questionNumber}</h3>
                <div class="form-group-inline mb-2">
                    <label for="question${questionNumber}">Question ${questionNumber} Text:</label>
                    <input type="text" id="question${questionNumber}" name="questions[${questionNumber}][question_text]" class="form-control mb-2" >
                </div>
                <div class="form-group-inline mb-2">
                    <label for="questionImage${questionNumber}">Question ${questionNumber} Image:</label>
                    <input type="file" id="questionImage${questionNumber}" name="questions[${questionNumber}][question_image]" class="form-control-file mb-2">
                </div>
                <div class="options-group">
                    ${createOptionFields('A', questionNumber)}
                    ${createOptionFields('B', questionNumber)}
                    ${createOptionFields('C', questionNumber)}
                    ${createOptionFields('D', questionNumber)}
                    ${createOptionFields('E', questionNumber)}
                </div>
                <div class="form-group-inline">
                    <label for="correctAnswer${questionNumber}">Correct Answer:</label>
                    <select id="correctAnswer${questionNumber}" name="questions[${questionNumber}][correct_answer]" class="form-control mb-2" >
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                        <option value="E">Option E</option>
                    </select>
                </div>
                <!-- Add Remove Question Button -->
                <button type="button" class="btn btn-danger remove-question" data-id="${questionNumber}">Remove Question</button>
            </div>
        `);
                $('#questionInputs').append(inputGroup);
            }

            // Helper function to create option fields
            function createOptionFields(option, questionNumber) {
                return `
            <div class="option-container">
                <label for="option${option}${questionNumber}">Option ${option} Text:</label>
                <input type="text" id="option${option}${questionNumber}" name="questions[${questionNumber}][option_${option.toLowerCase()}_text]" class="form-control mb-2">
                <label for="option${option}Image${questionNumber}">Option ${option} Image:</label>
                <input type="file" id="option${option}Image${questionNumber}" name="questions[${questionNumber}][option_${option.toLowerCase()}_image]" class="form-control-file mb-2">
            </div>
        `;
            }

            // Add the first question set on page load
            addQuestionFields();

            // Add a new question set when the "Add Another Question" button is clicked
            $('#addQuestion').click(function () {
                addQuestionFields();
            });

            // Event delegation to handle removing a question
            $(document).on('click', '.remove-question', function () {
                const questionId = $(this).data('id');
                $(`#questionGroup${questionId}`).remove();

                // Update the question numbers after removal
                updateQuestionNumbers();
            });

            // Function to update the question numbers after a question is removed
            function updateQuestionNumbers() {
                $('#questionInputs .question-group').each(function (index) {
                    const questionNumber = index + 1;
                    $(this).find('h3').text(`Question ${questionNumber}`);
                    $(this).attr('id', `questionGroup${questionNumber}`);
                    $(this).find('.remove-question').data('id', questionNumber);
                });
            }
        });
    </script>

</body>

</html>