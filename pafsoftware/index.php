<?php
session_start();

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();
pafEnsureResultTables($pdo);

if (!isset($_SESSION['user'])) {
    header('Location: userlogin.php');
    exit();
}

$user = $_SESSION['user'];
$uid = pafNormaliseUserId($user['id'] ?? '');

function pafEsc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['selected_test_id'])) {
    $tests = pafFetchTests($pdo);

    if ($tests === []) {
        echo 'No tests are available right now.';
        exit();
    }

    if (isset($_POST['test_id']) && (int) $_POST['test_id'] > 0) {
        $_SESSION['selected_test_id'] = (int) $_POST['test_id'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Select a Test</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="modal show" id="testSelectionModal" tabindex="-1" role="dialog" aria-labelledby="testModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="testModalLabel">Select a Test</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="test_id">Choose a test:</label>
                                <select name="test_id" id="test_id" class="form-control" required>';
    foreach ($tests as $test) {
        echo '<option value="' . (int) $test['id'] . '">' . pafEsc($test['test_name']) . '</option>';
    }
    echo '              </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Select Test</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("#testSelectionModal").modal("show");
            });
        </script>
    </body>
    </html>';
    exit();
}

$selected_test_id = (int) ($_SESSION['selected_test_id'] ?? 0);
$subjects = pafFetchTestSubjects($pdo, $selected_test_id);

if ($subjects === []) {
    unset($_SESSION['selected_test_id']);
    echo 'No subjects are configured for the selected test yet.';
    exit();
}

$subjectIds = array_map(static fn(array $subject): int => (int) $subject['id'], $subjects);
$completedSubjectIds = pafCompletedSubjectIds($pdo, $uid, $subjectIds);
$completedLookup = array_fill_keys($completedSubjectIds, true);

if (count($completedSubjectIds) >= count($subjectIds)) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test Already Completed</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-warning" role="alert">
                You have already completed all subjects in this test.
            </div>
            <button class="btn btn-warning" id="clearTestButton" data-user-id="' . pafEsc($uid) . '" data-test-id="' . $selected_test_id . '">Clear Test</button>
            <a class="btn btn-secondary ml-2" href="reset_test.php">Choose Another Test</a>
        </div>
        <script>
            $(document).ready(function() {
                $("#clearTestButton").click(function() {
                    var userId = $(this).data("user-id");
                    var testId = $(this).data("test-id");
                    if (confirm("Are you sure you want to clear this test? This will allow you to take it again.")) {
                        $.ajax({
                            url: "reschedule_test.php",
                            method: "GET",
                            data: { user_id: userId, test_id: testId },
                            success: function(response) {
                                if (response === "success") {
                                    window.location.href = "reset_test.php";
                                } else {
                                    alert("Error clearing test: " + response);
                                }
                            },
                            error: function() {
                                alert("Error clearing test.");
                            }
                        });
                    }
                });
            });
        </script>
    </body>
    </html>';
    exit();
}

$requestedSubjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
$firstPendingSubjectId = 0;
foreach ($subjects as $subject) {
    if (!isset($completedLookup[(int) $subject['id']])) {
        $firstPendingSubjectId = (int) $subject['id'];
        break;
    }
}

$validSubjectLookup = array_fill_keys($subjectIds, true);
$subject_id = $requestedSubjectId > 0
    && isset($validSubjectLookup[$requestedSubjectId])
    && !isset($completedLookup[$requestedSubjectId])
    ? $requestedSubjectId
    : $firstPendingSubjectId;

$currentSubjectIndex = 0;
foreach ($subjects as $index => $subject) {
    if ((int) $subject['id'] === $subject_id) {
        $currentSubjectIndex = $index;
        break;
    }
}

$nextSubjectId = null;
for ($i = $currentSubjectIndex + 1; $i < count($subjects); $i++) {
    $candidateId = (int) $subjects[$i]['id'];
    if (!isset($completedLookup[$candidateId])) {
        $nextSubjectId = $candidateId;
        break;
    }
}

$isLastSubject = $nextSubjectId === null;
$activeSubject = $subjects[$currentSubjectIndex];
$nextSubjectName = '';
foreach ($subjects as $subject) {
    if ($nextSubjectId !== null && (int) $subject['id'] === $nextSubjectId) {
        $nextSubjectName = (string) $subject['name'];
        break;
    }
}
$timeLimit = (int) ($activeSubject['time_in_minutes'] ?? 0);
if ($timeLimit <= 0) {
    $timeLimit = 1;
}

$query = $pdo->prepare('SELECT * FROM questions WHERE subject_id = :subject_id ORDER BY sequence_number ASC, id ASC');
$query->bindParam(':subject_id', $subject_id);
$query->execute();
$questions = $query->fetchAll(PDO::FETCH_ASSOC);
$totalQuestions = count($questions);

if ($totalQuestions === 0) {
    $emptySummary = pafUpsertSubjectResult($pdo, $uid, $subject_id);
    pafUpsertOverallResult($pdo, $uid, $selected_test_id);

    $continueButton = $nextSubjectId !== null
        ? '<a class="btn btn-primary" href="?subject_id=' . $nextSubjectId . '&q=0">Continue To Next Subject</a>'
        : '<a class="btn btn-primary" href="reset_test.php">Choose Another Test</a>';

    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>No MCQs Available</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body style="background:#f7f9fc;">
        <div class="container py-5">
            <div class="card shadow-sm border-0" style="max-width:720px; margin:0 auto;">
                <div class="card-body p-4 p-md-5 text-center">
                    <h2 class="mb-3">' . pafEsc($activeSubject['name']) . '</h2>
                    <p class="lead mb-2">No MCQs are available for this subject.</p>
                    <p class="text-muted mb-4">This subject has been recorded and the test flow can continue safely.</p>
                    <div class="d-flex justify-content-center flex-wrap" style="gap:12px;">
                        ' . $continueButton . '
                        <a class="btn btn-outline-secondary" href="userlogout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    exit();
}

$questionIds = array_map(static fn(array $question): int => (int) $question['id'], $questions);
$questionStatuses = pafFetchQuestionStatuses($pdo, $uid, $questionIds);

$currentQuestionIndex = isset($_GET['q']) ? (int) $_GET['q'] : 0;
if ($currentQuestionIndex < 0) {
    $currentQuestionIndex = 0;
} elseif ($currentQuestionIndex >= $totalQuestions) {
    $currentQuestionIndex = $totalQuestions - 1;
}

$currentQuestion = $questions[$currentQuestionIndex] ?? null;
$currentQuestionId = $currentQuestion['id'] ?? null;
$currentStatus = $currentQuestionId !== null && isset($questionStatuses[(int) $currentQuestionId])
    ? $questionStatuses[(int) $currentQuestionId]
    : ['answer' => '', 'mark_for_review' => 0, 'is_skipped' => 0];

$userAnswer = (string) ($currentStatus['answer'] ?? '');
$markForReview = (int) ($currentStatus['mark_for_review'] ?? 0);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineTest</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: "Poppins", system-ui;
            font-size: 13px;
        }

        .question-select option.review-question {
            background-color: #fff3b0;
            font-weight: 700;
        }

        .question-select option.answered-question {
            background-color: #dcfce7;
            font-weight: 700;
        }

        .question-select option.not-answered-question {
            background-color: #f1f5f9;
            color: #334155;
        }

        .hidden {
            display: none;
        }

        .dropdownlist {
            margin: 20px;
        }

        .dropdownlist select {
            width: 150px;
            /* padding: 10px; */
            border: 2px solid black;
            border-radius: 5px;
            font-size: 16px;
        }

        .dropdownlist option {
            padding: 15px;
        }

        .popup-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .popup-content {
            background-color: rgb(27, 167, 253);
            color: white;
            margin: 15% auto;
            padding: 28px;
            border: 1px solid #888;
            width: 15%;
            text-align: center;
            border-radius: 10px;
        }

        .close-btn {
            color: black;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    </styl>
</head>

<div>
    <div class="main">
        <div class="head">
            <div style="font-size: 2em; color: white" class="subject-name">Subject : <?php echo $subjects[array_search($subject_id, array_column($subjects, 'id'))]['name']; ?></div>
            <div style="font-size: 1.5em; color: #000000;" class="version">Version : 1.0092</div>
        </div>
        <div class="main-content">
            <div class="right-content">
                <div class="personal_content">
                    <div style="display: flex; width: 100%; justify-content: flex-start; gap: 100px; padding: 20px;">
                        <div style="display: flex; align-items: center;;">
                            <h3 style="font-size: 1.4rem;">NAME : </h3>
                            <p style="font-size: 1.4rem;">
                                <?php echo $user['name']; ?>
                            </p>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <h3 style="font-size: 1.4rem;">ID : </h3>
                            <p style="font-size: 1.4rem;">
                                <?php echo $user['id']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; border: 2px solid black;">
                    <div
                        style="display: flex; width: 100%; justify-content: flex-start; align-items: center; gap: 100px; padding: 20px;">
                        <div style="display: flex; align-items: center;">
                            <h3 style="font-size: 1.4rem;">Question NO: </h3>
                            <p style="font-size: 1.4rem;">
                                 <?php echo $currentQuestionIndex + 1; ?> 
                            </p>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <h3 style="font-size: 1.4rem;">Total Questions: </h3>
                            <p style="font-size: 1.4rem;">
                                <?php echo $totalQuestions; ?> 
                            </p>
                        </div>
                    </div>
                </div>

                <div class="scrollable-div" style="display:flex; align-items:center;">
                    <p style="font-size: 1.4rem;">
                         <?php echo pafEsc($currentQuestion['question_text']); ?> 
                    </p>
                    <?php if (!empty($currentQuestion['question_image'])) { ?>
                        <img src="<?php echo pafEsc($currentQuestion['question_image']); ?>" alt="Question Image"
                            style="width: 70px; height: 70px; padding-left:20px;">
                    <?php } ?>
                </div>

                <div class="mcqs_choice">
                    <form id="answerForm" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <input type="hidden" name="question_id"
                            value="<?php echo htmlspecialchars($currentQuestion['id']); ?>">
                        <input type="hidden" name="question_visited" value="1">
                        <input type="hidden" name="no_answer_selected" id="no_answer_selected" value="<?php echo ($userAnswer !== '' && $userAnswer !== 'F') ? '0' : '1'; ?>">

                        <div class="options-container">
                            <?php
                            $options = ['A', 'B', 'C', 'D', 'E'];
                            foreach ($options as $option) {
                                $optionText = isset($currentQuestion['option_' . strtolower($option)]) ? htmlspecialchars($currentQuestion['option_' . strtolower($option)]) : '';
                                $optionImage = isset($currentQuestion['option_' . strtolower($option) . '_image']) ? htmlspecialchars($currentQuestion['option_' . strtolower($option) . '_image']) : '';
                            ?>
                                <div class="option-row">
                                    <div class="option-letter"><?php echo $option; ?></div>
                                    <div class="option-content">
                                        <input type="radio" name="answer" value="<?php echo $option; ?>"
                                            id="answer_<?php echo $option; ?>" <?php if (isset($userAnswer) && $userAnswer == $option)
                                                                            echo 'checked'; ?>>
                                        <div style="font-size: 1.3rem;" class="option-text-image">
                                            <?php if (!empty($optionText)) { ?>
                                                <p><?php echo $optionText; ?></p>
                                            <?php } ?>
                                            <?php if (!empty($optionImage)) { ?>
                                                <img src="<?php echo $optionImage; ?>" alt="Option <?php echo $option; ?> Image">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="bottom-options">
                                <div class="no-selection">
                                    <input type="radio" name="answer" value="F" id="selection" <?php if (
                                                                                                    isset($userAnswer) &&
                                                                                                    $userAnswer == 'F'
                                                                                                ) echo 'checked'; ?>>
                                    <label for="selection">No Selection</label>
                                </div>
                                <div class="mark-review">
                                    <input type="checkbox" id="mark_for_review" name="mark_for_review" value="1" <?php if (isset($markForReview) && $markForReview) echo 'checked'; ?>>
                                    <label for="mark_for_review">MARK THE QUESTION FOR REVIEW</label>
                                </div>
                            </div>


                            <!-- Confirmation Modal -->
                            <div id="confirmationModal"
                                style="width: 400px; text-align: center; background-color: white; display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px 30px; border-radius: 10px; z-index: 1001;">
                                <h3 style="margin-bottom: 20px;">Are you sure you want to end test?</h3>
                                <div style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
                                    <button type="button" onclick="confirmEndTest()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Yes</button>
                                    <button type="button" onclick="closeConfirmationModal()" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">No</button>
                                </div>
                            </div>

                            <!-- Subject Results Modal -->
                            <div id="subjectResultsModal"
                                style="width: 500px; text-align: center; background-color: white; display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 30px; border-radius: 10px; z-index: 1002;">
                                <span class="close-btn" onclick="closeSubjectResultsModal()" style="position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: #333;">&times;</span>
                                <h2 style="margin-bottom: 30px; color: #333;">Results:</h2>
                                
                                <!-- Subject Results -->
                                <div id="subjectResults" style="margin-bottom: 30px; text-align: left;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                                        <div><strong>Subject:</strong></div>
                                        <div id="modalSubjectName"></div>
                                        <div><strong>Correct Answers:</strong></div>
                                        <div id="modalCorrectAnswers"></div>
                                        <div><strong>Total Questions:</strong></div>
                                        <div id="modalTotalQuestions"></div>
                                        <div><strong>Percentage:</strong></div>
                                        <div id="modalPercentage"></div>
                                    </div>
                                </div>

                                <!-- Pass/Fail Message -->
                                <div id="modalPassFailMessage" style="margin-bottom: 30px; font-weight: bold; font-size: 18px;"></div>

                                <!-- Next Test Button -->
                                <div id="modalNextTestButton" style="margin-top: 30px;">
                                    <a href="#" id="modalNextTestLink" style="text-decoration: none; padding: 12px 25px; background-color: #4CAF50; color: white; border-radius: 5px; display: inline-block;">Start Next Test</a>
                                </div>
                            </div>

                            <div id="nextSubjectPopup"
                                style="width: 400px; max-height: 90vh; text-align: center; background-color: white; display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px 30px; border-radius: 10px; overflow-y: auto; z-index: 1000;">
                                <span class="close-btn" onclick="closePopup()">&times;</span>

                                <!-- This text and button will be hidden after result is shown -->
                                <h2>You Have Completed the Test</h2>
                                <?php if (!$isLastSubject) { ?>
                                    <div style="text-align: center; margin-top: 50px;">
                                        <a href="?subject_id=<?php echo htmlspecialchars($nextSubjectId); ?>&q=0"
                                            style="text-decoration: none; padding: 10px 20px; background-color: #4CAF50; color: white; border-radius: 5px;"
                                            onclick="hidePopup()">Start
                                            <?php echo htmlspecialchars($subjects[$currentSubjectIndex + 1]['name']); ?>
                                            Test
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div style="text-align: center; margin-top: 50px;">
                                        <a href="#"
                                            style="text-decoration: none; padding: 10px 20px; background-color: green; color: white; border-radius: 5px;"
                                            onclick="endtask(event, '<?php echo $user['id']; ?>')">Show Result</a>
                                    </div>
                                    <div style="margin-top: 40px;" class="nexttest">
                                        <a href="reset_test.php"
                                            style="padding: 10px 20px; background-color: red; color: white; border-radius: 5px; text-decoration:none;">
                                            Attempt Next Test
                                        </a>
                                    </div>
                                <?php } ?>

                                <!-- Section to show the results after the user clicks "End Task" -->
                                <div id="resultSection" style="margin-top: 30px; display: none;">
                                    <h3>Your Test Results</h3>
                                    <div id="resultContent">Loading results...</div> <!-- Make this scrollable if content exceeds height -->


                                    <!-- Button to logout and redirect to the home page -->
                                    <div style="margin-top: 40px;">
                                        <a href="userlogin.php"
                                            style="padding: 10px 20px; background-color: red; color: white; border-radius: 5px; text-decoration:none;"
                                            onclick="endalltest()">End Task</a>
                                    </div>
                                                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>

                 <div id="answerReviewModal" class="answer-review-modal">
                     <div class="answer-review-content">
                         <span class="answer-review-close" onclick="closeAnswerReviewModal()">&times;</span>
                         <div id="answerReviewContent">Loading...</div>
                     </div>
                 </div>




            </div>
            <div class="left-content">
                <div class="profile-section">
                    <div class="profile-picture">
                        <?php
                        $picturePath = trim((string) ($user['picture'] ?? ''));
                        if ($picturePath === '') {
                            $picturePath = 'data:image/svg+xml;utf8,' . rawurlencode(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="110" height="110" viewBox="0 0 110 110">
                                    <rect width="110" height="110" rx="18" fill="#eef3f8"/>
                                    <circle cx="55" cy="40" r="20" fill="#a6b7ca"/>
                                    <path d="M24 92c5-16 18-26 31-26s26 10 31 26" fill="#a6b7ca"/>
                                </svg>'
                            );
                        } elseif (strpos($picturePath, 'uploads/') === 0) {
                            $picturePath = substr($picturePath, strlen('uploads/'));
                        }
                        ?>
                        <img src="<?php echo strpos($picturePath, 'data:image') === 0 ? $picturePath : 'uploads/' . htmlspecialchars($picturePath); ?>" alt="User Picture" class="user-picture">
                    </div>
                    <div class="time-section">
                        <div class="time-box">
                            <h3 style="font-size: 1.1rem;">Total TIME</h3>
                            <p><?php echo htmlspecialchars($timeLimit); ?>:00</p>
                        </div>
                        <div class="time-box">
                            <h3 style="font-size: 1.1rem;">TIME REMAINING</h3>
                            <p id="time_remaining"><?php echo htmlspecialchars($timeLimit); ?>:00</p>
                        </div>
                    </div>
                </div>

                <div class="top-section">
                    <input type="hidden" id="timeLimit" value="<?php echo $timeLimit; ?>">
                    <div class="btn-container">
                        <div class="btn">
                            <a href="#" onclick="startTest()">START TEST</a>
                        </div>
                    </div>
                </div>

                <select class="question-select" name="move_to_question" id="move_to_question" onchange="navigateToQuestionIndex(this.value)">
                    <?php foreach ($questions as $index => $question) {
                        $questionId = $question['id'];

                        // Fetch review and answer status for each question
                        $stmt = $pdo->prepare("SELECT mark_for_review, answer, is_skipped FROM answers WHERE question_id = ? AND user_id = ?");
                        $stmt->execute([$questionId, $user['id']]);
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        $isMarkedForReview = $result['mark_for_review'] ?? 0;
                        $userAnswer = $result['answer'] ?? '';
                        $isSkipped = $result['is_skipped'] ?? 0;
                        $questionLabel = 'Question ' . ($index + 1);
                        

                        $highlightStyle = 'class="not-answered-question"';
                        if ($isMarkedForReview == 1) {
                            $highlightStyle = 'class="review-question"';
                        } elseif ($userAnswer !== '' && $userAnswer !== 'F') {
                            $highlightStyle = 'class="answered-question"';
                        }
                        
                        $icon = '&#10005;';
                        if ($isMarkedForReview == 1) {
                            $icon = '&#9888;';
                        } elseif ($userAnswer !== '' && $userAnswer !== 'F') {
                            $icon = ' ✎';
                        } elseif ($isSkipped == 1) {
                            $icon = ' ⚠';
                        }

                        // Check if the question is the current one
                        $isSelected = ($currentQuestionIndex == $index) ? 'selected="selected"' : '';
                    ?>
                        <option value="<?php echo (int) $index; ?>"
                            data-base-label="<?php echo pafEsc($questionLabel); ?>"
                            data-status-icon="<?php echo pafEsc(html_entity_decode($isMarkedForReview == 1 ? '&#9888;' : (($userAnswer !== '' && $userAnswer !== 'F') ? '&#10003;' : '&#10005;'), ENT_QUOTES, 'UTF-8')); ?>"
                            <?php echo $isSelected; ?> <?php echo $highlightStyle; ?>>
                            <?php echo $isSelected ? $questionLabel : $questionLabel . ' ' . html_entity_decode($isMarkedForReview == 1 ? '&#9888;' : (($userAnswer !== '' && $userAnswer !== 'F') ? '&#10003;' : '&#10005;'), ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php } ?>
                </select>

                <div class="navigation-icons">
                    <div class="icon_img">
                        <a href="?subject_id=<?php echo $subject_id; ?>&q=0" onclick="saveAnswerAndNavigate(0); return false;"><i class="fas fa-fast-backward"></i></a>
                    </div>
                    <div class="icon_img">
                        <a href="?subject_id=<?php echo $subject_id; ?>&q=<?php echo max(0, $currentQuestionIndex - 1); ?>" onclick="saveAnswerAndNavigate(<?php echo max(0, $currentQuestionIndex - 1); ?>); return false;"><i class="fas fa-step-backward"></i></a>
                    </div>
                    <div class="icon_img">
                        <a href="?subject_id=<?php echo $subject_id; ?>&q=<?php echo min($totalQuestions - 1, $currentQuestionIndex + 1); ?>" onclick="handleNext(<?php echo $totalQuestions; ?>, <?php echo $currentQuestionIndex; ?>); return false;"><i class="fas fa-step-forward"></i></a>
                    </div>
                    <div class="icon_img">
                        <a href="?subject_id=<?php echo $subject_id; ?>&q=<?php echo $totalQuestions - 1; ?>" onclick="saveAnswerAndNavigate(<?php echo $totalQuestions - 1; ?>); return false;"><i class="fas fa-fast-forward"></i></a>
                    </div>
                </div>

                <div id="popupModal" class="popup-modal">
                    <div class="popup-content">
                        <span class="close-btn" onclick="closePopup()">&times;</span>
                        <p>You have completed all the questions!</p>
                        <a class="end-test-link" href="#" type="button" onclick="return endTest()">END TEST</a>
                    </div>
                </div>

                <div class="navigation-text">
                    <div style="font-size: 1rem;">First</div>
                    <div style="font-size: 1rem;">Previous</div>
                    <div style="font-size: 1rem;">Next</div>
                    <div style="font-size: 1rem;">Last</div>
                </div>

                <div class="end-btn">
                    <div class="btn">
                        <a href="#" type="button" onclick="return endTest()">END TEST</a>
                    </div>
                </div>

            </div>



        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script>
    let countdown;

    // Function to start the timer
    function startTimer(duration, display) {
        let timer = duration,
            minutes, seconds;
        countdown = setInterval(function() {
            minutes = Math.floor(timer / 60);
            seconds = timer % 60;

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            display.textContent = minutes + ':' + seconds;

            if (--timer < 0) {
                clearInterval(countdown); // Stop the timer
                display.textContent = "Time's up!";
                document.getElementById('endTestForm').submit(); // Auto-submit the form when time runs out
                showSubjectResultsModal(); // Show the subject results modal
            }
        }, 1000);
    }

    function enableFormInputs() {
        document.querySelectorAll('#answerForm').forEach(function(input) {
            input.disabled = false;
        });
    }

    function showNextSubjectPopup() {
        document.getElementById('nextSubjectPopup').style.display = 'block';
    }
</script>

<script>
    function syncNoAnswerSelection() {
        const selectedAnswer = document.querySelector('input[name="answer"]:checked');
        const noAnswerField = document.getElementById('no_answer_selected');

        if (!noAnswerField) {
            return;
        }

        if (!selectedAnswer || selectedAnswer.value === 'F') {
            noAnswerField.value = '1';
        } else {
            noAnswerField.value = '0';
        }
    }

    function saveAnswerAndNavigate(nextQuestionIndex) {
        syncNoAnswerSelection();
        var formData = $('#answerForm').serialize();

        $.ajax({
            url: 'save_answer.php',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function() {
                window.location.href = '?subject_id=<?php echo $subject_id; ?>&q=' + nextQuestionIndex;
            },
            error: function() {
                window.location.href = '?subject_id=<?php echo $subject_id; ?>&q=' + nextQuestionIndex;
            }
        });
    }

    function navigateToQuestionIndex(questionIndex) {
        if (questionIndex === '' || questionIndex === null) {
            return;
        }

        saveAnswerAndNavigate(questionIndex);
    }

    function setQuestionDropdownDisplay(showIcons) {
        const dropdown = document.getElementById('move_to_question');
        if (!dropdown) {
            return;
        }

        Array.from(dropdown.options).forEach(function(option) {
            const baseLabel = option.getAttribute('data-base-label') || option.text;
            const statusIcon = option.getAttribute('data-status-icon') || '';

            if (showIcons) {
                option.text = statusIcon ? (baseLabel + ' ' + statusIcon) : baseLabel;
                return;
            }

            option.text = option.selected ? baseLabel : (statusIcon ? (baseLabel + ' ' + statusIcon) : baseLabel);
        });
    }

    function handleNext(totalQuestions, currentQuestionIndex) {
        if (currentQuestionIndex + 1 >= totalQuestions) {
            const popupModal = document.getElementById('popupModal');
            popupModal.style.display = 'block';
            setTimeout(function() {
                popupModal.style.display = 'none';
            }, 3000);
        } else {
            saveAnswerAndNavigate(currentQuestionIndex + 1);
        }
    }

    $(document).ready(function() {
        setQuestionDropdownDisplay(false);

        $('#move_to_question').on('focus mousedown', function() {
            setQuestionDropdownDisplay(true);
        });

        $('#move_to_question').on('change blur', function() {
            setTimeout(function() {
                setQuestionDropdownDisplay(false);
            }, 0);
        });

        $('input[name="answer"], input[name="mark_for_review"]').on('change', function() {
            syncNoAnswerSelection();
            var formData = $('#answerForm').serialize();

            $.ajax({
                url: 'save_answer.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function() {},
                error: function() {}
            });
        });

        syncNoAnswerSelection();
    });
</script>
<script>
    // Function to start the test and show the options
    function startTest() {
        let timeLimitInMinutes = <?php echo $timeLimit; ?>; // Get the dynamic time limit from PHP
        let timeLimitInSeconds = timeLimitInMinutes * 60; // Convert minutes to seconds
        let currentTime = Date.now();
        let endTime = currentTime + timeLimitInSeconds * 1000; // Calculate the end time

        // Store the end time and current subject ID in session storage
        sessionStorage.setItem('endTime', endTime);
        sessionStorage.setItem('testStarted', 'true');
        sessionStorage.setItem('currentSubjectId', '<?php echo $subject_id; ?>');

        // Start the timer
        startTimer(timeLimitInSeconds, document.querySelector('#time_remaining'));

        // Show the options and hide the start button
        document.querySelector('.options-container').classList.remove('hidden');
        document.querySelector('.btn').style.display = 'none';
    }

    // Function to check if the test has already started on page load
    function checkTestStarted() {
        let endTime = sessionStorage.getItem('endTime');
        let testStarted = sessionStorage.getItem('testStarted');

        if (testStarted === 'true' && endTime) {
            // Check if this is the same subject (by checking URL parameters)
            const urlParams = new URLSearchParams(window.location.search);
            const currentSubjectId = urlParams.get('subject_id');
            const storedSubjectId = sessionStorage.getItem('currentSubjectId');
            
            if (currentSubjectId === storedSubjectId) {
                // Same subject, continue with existing timer
                document.querySelector('.options-container').classList.remove('hidden');
                document.querySelector('.btn').style.display = 'none';

                // Calculate remaining time
                let currentTime = Date.now();
                let remainingTime = Math.floor((endTime - currentTime) / 1000);

                // If time is still left, start the timer with remaining time
                if (remainingTime > 0) {
                    startTimer(remainingTime, document.querySelector('#time_remaining'));
                } else {
                    document.querySelector('#time_remaining').textContent = "Time's up!";
                    showSubjectResultsModal(); // Show the subject results modal when time is up
                }
            } else {
                // New subject, reset everything
                sessionStorage.removeItem('endTime');
                sessionStorage.removeItem('testStarted');
                document.querySelector('.options-container').classList.add('hidden');
                document.querySelector('.btn').style.display = 'block';
                document.querySelector('#time_remaining').textContent = document.getElementById('timeLimit').value + ":00";
            }
        } else {
            // Test hasn't started yet, show the start button
            document.querySelector('.options-container').classList.add('hidden');
            document.querySelector('.btn').style.display = 'block';
            document.querySelector('#time_remaining').textContent = document.getElementById('timeLimit').value + ":00";
        }
    }

    // Function to pause the timer and store the remaining time
    function pauseTimer() {
        let endTime = sessionStorage.getItem('endTime');
        let currentTime = Date.now();
        let remainingTime = Math.floor((endTime - currentTime) / 1000);
        sessionStorage.setItem('remainingTime', remainingTime);
        clearInterval(window.timerInterval); // Clear the timer interval
    }

    // Function to start the countdown timer
    function startTimer(duration, display) {
        let timer = duration,
            minutes, seconds;
        window.timerInterval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(window.timerInterval);
                display.textContent = "Time's up!";
                showSubjectResultsModal(); // Show the subject results modal when time is up
            }
        }, 1000);
    }

    // Call this function when navigating to the next question
    function loadNextQuestion() {
        pauseTimer(); // Pause the timer before the page reloads
        // Simulate loading the next question (e.g., via an AJAX request or page reload)
        // After loading, you may want to refresh the page or change content dynamically
        // For example: location.reload(); // Uncomment to reload the page
    }

    window.onload = function() {
        console.log('Page loaded with subject_id: <?php echo $subject_id; ?>');
        console.log('Total questions: <?php echo $totalQuestions; ?>');
        console.log('Current subject index: <?php echo $currentSubjectIndex; ?>');
        console.log('Next subject ID: <?php echo $nextSubjectId; ?>');
        checkTestStarted();
    };
</script>


<script>
    // Function to show confirmation modal before ending test
    function endTest() {
        event.preventDefault();
        document.getElementById('confirmationModal').style.display = 'block';
        return false; // Prevent any default behavior
    }

    // Function to confirm ending the test
    function confirmEndTest() {
        event.preventDefault();
        closeConfirmationModal();
        clearInterval(countdown); // Stop the timer
        showSubjectResultsModal(); // Show the subject results modal
        return false; // Prevent any default behavior
    }

    // Function to close confirmation modal
    function closeConfirmationModal() {
        event.preventDefault();
        document.getElementById('confirmationModal').style.display = 'none';
        return false; // Prevent any default behavior
    }

    // Function to close subject results modal
    function closeSubjectResultsModal() {
        document.getElementById('subjectResultsModal').style.display = 'none';
    }

    // Function to show the next subject popup
    function showNextSubjectPopup() {
        document.getElementById('nextSubjectPopup').style.display = 'block';
    }

    // Function to show subject results modal
    function showSubjectResultsModal() {
        // Get current subject information
        const subjectName = '<?php echo htmlspecialchars($subjects[array_search($subject_id, array_column($subjects, "id"))]["name"]); ?>';
        const totalQuestions = <?php echo count($questions); ?>;
        
        // Make AJAX call to get correct answers count
        $.ajax({
            url: 'get_subject_results.php',
            type: 'POST',
            dataType: 'json',
            data: {
                user_id: '<?php echo $user["id"]; ?>',
                subject_id: '<?php echo $subject_id; ?>'
            },
            success: function(response) {
                const data = (typeof response === 'string') ? JSON.parse(response) : response;

                if (data.success) {
                    const correctAnswers = data.correct_answers || 0;
                    const percentage = data.percentage || 0;
                    
                    // Update modal content
                    document.getElementById('modalSubjectName').textContent = data.subject_name;
                    document.getElementById('modalCorrectAnswers').textContent = correctAnswers;
                    document.getElementById('modalTotalQuestions').textContent = data.total_questions;
                    document.getElementById('modalPercentage').textContent = percentage + '%';
                    
                    // Show pass/fail message
                    const passFailMessage = document.getElementById('modalPassFailMessage');
                    if (percentage >= 50) {
                        passFailMessage.textContent = 'Congratulations! You have passed the test.';
                        passFailMessage.style.color = '#4CAF50';
                    } else {
                        passFailMessage.textContent = 'You have failed the test.';
                        passFailMessage.style.color = '#f44336';
                    }
                    
                    // Check if this is the last subject
                    <?php if (!$isLastSubject) { ?>
                        // Show next test button for next subject
                        const nextTestLink = document.getElementById('modalNextTestLink');
                        nextTestLink.href = '#';
                        nextTestLink.textContent = 'Start <?php echo pafEsc($nextSubjectName); ?> Test';
                        nextTestLink.onclick = function() {
                            console.log('Navigating to next subject: <?php echo htmlspecialchars($nextSubjectId); ?>');
                            // Close the subject results modal
                            document.getElementById('subjectResultsModal').style.display = 'none';
                            // Clear session storage for new subject
                            sessionStorage.removeItem('endTime');
                            sessionStorage.removeItem('testStarted');
                            sessionStorage.removeItem('currentSubjectId');
                            // Navigate to next subject
                            window.location.href = '?subject_id=<?php echo htmlspecialchars($nextSubjectId); ?>&q=0';
                            return false;
                        };
                        document.getElementById('modalNextTestButton').style.display = 'block';
                    <?php } else { ?>
                        // Show overall results button for last subject
                        const overallResultsLink = document.getElementById('modalNextTestLink');
                        overallResultsLink.href = '#';
                        overallResultsLink.textContent = 'Show Overall Results';
                        overallResultsLink.onclick = function() {
                            document.getElementById('subjectResultsModal').style.display = 'none';
                            showOverallResults();
                            return false;
                        };
                        document.getElementById('modalNextTestButton').style.display = 'block';
                    <?php } ?>
                    
                    // Show the subject results modal
                    document.getElementById('subjectResultsModal').style.display = 'block';
                    
                } else {
                    console.error('Error in response:', data.error);
                    alert(data.error || 'Error loading results. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching results:', error, xhr.responseText);
                alert('Error loading results. Please try again.');
            }
        });
    }

    // Function to show overall results
    function showOverallResults() {
        console.log('showOverallResults called');
        
        // Close subject results modal first
        document.getElementById('subjectResultsModal').style.display = 'none';
        
        // Show the nextSubjectPopup with overall results
        document.getElementById('nextSubjectPopup').style.display = 'block';
        
        // Hide the initial content and show the results section
        document.querySelector('#nextSubjectPopup h2').style.display = 'none';
        document.querySelector('#nextSubjectPopup a').style.display = 'none';
        document.querySelector('#nextSubjectPopup .nexttest').style.display = 'none';
        
        // Show the results section
        document.getElementById('resultSection').style.display = 'block';
        
        // Load the overall results
        loadOverallResults();
        
        // Ensure the modal is visible and properly positioned
        document.getElementById('nextSubjectPopup').style.zIndex = '1003';
        
        console.log('Overall results modal should be visible now');
    }

    // Function to load overall results
    function loadOverallResults() {
        $.ajax({
            url: 'fetch_results.php',
            type: 'GET',
            data: {
                user_id: '<?php echo $user["id"]; ?>'
            },
            success: function(response) {
                document.getElementById('resultContent').innerHTML = response;
            },
            error: function() {
                document.getElementById('resultContent').innerHTML = '<p>Error fetching results.</p>';
            }
        });
    }

    // Function to hide the popup and reset the test for the next subject
    function hidePopup() {
        document.getElementById('nextSubjectPopup').style.display = 'none';
        
        // Reset the test for the next subject
        sessionStorage.removeItem('endTime'); // Clear the previous timer
        sessionStorage.removeItem('testStarted'); // Clear the previous state
        
        // Stop any running timer
        if (window.countdown) {
            clearInterval(window.countdown);
        }
        if (window.timerInterval) {
            clearInterval(window.timerInterval);
        }

        // Reset the timer display to show the time limit
        const timeLimit = document.getElementById('timeLimit').value;
        document.querySelector('#time_remaining').textContent = timeLimit + ":00";

        // Hide options and show start button
        document.querySelector('.options-container').classList.add('hidden'); // Hide options
        document.querySelector('.btn').style.display = 'block'; // Show start button
    }
</script>

<script>
    function saveAnswerAndNavigateLegacy(questionIndex) {
        var formData = $('#answerForm').serialize();
        console.log('Navigation button - Form data being sent:', formData);

        $.ajax({
            url: 'save_answer.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                window.location.href = "?subject_id=<?php echo $subject_id; ?>&q=" + questionIndex;
            },
            error: function(xhr, status, error) {
                console.log('Error saving answer: ' + error);
                window.location.href = "?subject_id=<?php echo $subject_id; ?>&q=" + questionIndex;
            }
        });
    }

    // Function to close the pop-up
    function closePopup() {
        document.getElementById('popupModal').style.display = 'none';
        document.getElementById('nextSubjectPopup').style.display = 'none';
        document.getElementById('confirmationModal').style.display = 'none';
        document.getElementById('subjectResultsModal').style.display = 'none';
    }

    // Function to handle ending the test (custom logic can be added here)
    function endTest() {
        event.preventDefault();
        document.getElementById('confirmationModal').style.display = 'block';
        return false; // Prevent any default behavior
    }
</script>
<script>
    function endalltest() {
        // Reset the test for the next subject
        sessionStorage.removeItem('endTime'); // Clear the previous timer
        sessionStorage.removeItem('testStarted'); // Clear the previous state

        // Call the server to destroy the session
        fetch('destroy_session.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Session destroyed successfully.');
                    // Optionally, redirect the user to the login or another page
                    window.location.href = 'userlogin.php';
                } else {
                    console.error('Failed to destroy the session.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>


<script>
    // Function to handle keyboard events for navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowLeft') {
            // Navigate to previous question
            if (<?php echo $currentQuestionIndex; ?> > 0) {
                let prevIndex = <?php echo max(0, $currentQuestionIndex - 1); ?>;
                saveAnswerAndNavigate(prevIndex);
            }
        } else if (event.key === 'ArrowRight') {
            // Navigate to next question
            if (<?php echo $currentQuestionIndex; ?> < <?php echo $totalQuestions - 1; ?>) {
                let nextIndex = <?php echo min($totalQuestions - 1, $currentQuestionIndex + 1); ?>;
                saveAnswerAndNavigate(nextIndex);
            }
        }
    });

    // Function to save the answer and navigate to a specific question
    function saveAnswerAndNavigateFromKeyboard(index) {
        var formData = $('#answerForm').serialize();
        console.log('Keyboard navigation - Form data being sent:', formData);

        $.ajax({
            url: 'save_answer.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                window.location.href = `?subject_id=<?php echo $subject_id; ?>&q=${index}`;
            },
            error: function(xhr, status, error) {
                console.log('Error saving answer: ' + error);
                window.location.href = `?subject_id=<?php echo $subject_id; ?>&q=${index}`;
            }
        });
    }
</script>
<script>
    function endtask(event, userId) {
        event.preventDefault(); // Prevent default behavior of the link
        console.log(userId);
        // Hide the "End Task" button and "You Have Completed the Test" text
        document.querySelector('#nextSubjectPopup h2').style.display = 'none';
        document.querySelector('#nextSubjectPopup a').style.display = 'none';
        document.querySelector('#nextSubjectPopup .nexttest').style.display = 'none';
        // Show the result section
        document.getElementById('resultSection').style.display = 'block';

        // Fetch and display the result via AJAX
        $.ajax({
            url: 'fetch_results.php', // Backend script to fetch the results
            method: 'GET',
            data: {
                user_id: userId
            },
            success: function(response) {
                document.getElementById('resultContent').innerHTML = response; // Inject the result into the modal
            },
            error: function() {
                document.getElementById('resultContent').innerHTML = '<p>Error fetching results.</p>'; // Error handling
            }
        });
    }


    function closePopup() {
        document.getElementById('nextSubjectPopup').style.display = 'none';
    }

    // Function to logout the user and redirect to home page
     function logoutAndRedirect() {
         window.location.href = 'userlogout.php';
     }

     function showAnswerDetails(userId) {
         document.getElementById('answerReviewModal').style.display = 'block';
         document.getElementById('answerReviewContent').innerHTML = 'Loading...';
         
         $.ajax({
             url: 'fetch_answer_details.php',
             type: 'GET',
             data: { user_id: userId },
             success: function(response) {
                 document.getElementById('answerReviewContent').innerHTML = response;
             },
             error: function() {
                 document.getElementById('answerReviewContent').innerHTML = '<p>Error loading answer details.</p>';
             }
         });
     }

          function closeAnswerReviewModal() {
         document.getElementById('answerReviewModal').style.display = 'none';
     }

     function selectOption(optionValue) {
         const targetOption = document.getElementById('answer_' + optionValue);
         if (!targetOption) {
             return;
         }

         targetOption.checked = true;
         document.querySelector('input[name="no_answer_selected"]').value = '0';
         
         var formData = $('#answerForm').serialize();

         $.ajax({
             url: 'save_answer.php',
             type: 'POST',
             dataType: 'json',
             data: formData,
             success: function() {},
             error: function() {}
         });
     }

     function filterBySubject() {
         const selectedSubject = document.getElementById('subjectFilter').value;
         const subjectSections = document.querySelectorAll('.subject-section');
         
         subjectSections.forEach(section => {
             if (section.id === 'subject-' + selectedSubject) {
                 section.style.display = 'block';
             } else {
                 section.style.display = 'none';
             }
         });
     }


  </script>
 </body>
 
 </html>
