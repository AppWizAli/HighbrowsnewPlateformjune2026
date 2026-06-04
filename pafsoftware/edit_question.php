<?php
include 'config.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get the question ID from the URL
if (!isset($_GET['id'])) {
    header('Location: admin_panel.php');
    exit();
}

$question_id = $_GET['id'];

// Fetch the question from the database, including image columns
$sql = "SELECT subject_id, question_text, question_image, option_a, option_a_image, option_b, option_b_image, option_c, option_c_image, option_d, option_d_image, correct_answer FROM questions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: admin_panel.php');
    exit();
}

$question = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
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
                <h2>Edit Question</h2>
            </section>
    <div class="container mt-4">
        <form action="update_question.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $question_id; ?>">
            
            <div class="form-group">
                <label for="subject_id">Subject ID</label>
                <input type="text" name="subject_id" class="form-control" value="<?php echo htmlspecialchars($question['subject_id']); ?>" required>
            </div>

            <div class="form-group">
                <label for="question_text">Question Text</label>
                <textarea name="question_text" class="form-control" rows="3" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="question_image">Question Image</label>
                <?php if (!empty($question['question_image'])): ?>
                    <img src="<?php echo htmlspecialchars($question['question_image']); ?>" alt="Question Image" style="max-width: 100px;">
                <?php endif; ?>
                <input type="file" name="question_image" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="option_a">Option A</label>
                <input type="text" name="option_a" class="form-control" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
                <label for="option_a_image">Option A Image</label>
                <?php if (!empty($question['option_a_image'])): ?>
                    <img src="<?php echo htmlspecialchars($question['option_a_image']); ?>" alt="Option A Image" style="max-width: 50px;">
                <?php endif; ?>
                <input type="file" name="option_a_image" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="option_b">Option B</label>
                <input type="text" name="option_b" class="form-control" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
                <label for="option_b_image">Option B Image</label>
                <?php if (!empty($question['option_b_image'])): ?>
                    <img src="<?php echo htmlspecialchars($question['option_b_image']); ?>" alt="Option B Image" style="max-width: 50px;">
                <?php endif; ?>
                <input type="file" name="option_b_image" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="option_c">Option C</label>
                <input type="text" name="option_c" class="form-control" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
                <label for="option_c_image">Option C Image</label>
                <?php if (!empty($question['option_c_image'])): ?>
                    <img src="<?php echo htmlspecialchars($question['option_c_image']); ?>" alt="Option C Image" style="max-width: 50px;">
                <?php endif; ?>
                <input type="file" name="option_c_image" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="option_d">Option D</label>
                <input type="text" name="option_d" class="form-control" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
                <label for="option_d_image">Option D Image</label>
                <?php if (!empty($question['option_d_image'])): ?>
                    <img src="<?php echo htmlspecialchars($question['option_d_image']); ?>" alt="Option D Image" style="max-width: 50px;">
                <?php endif; ?>
                <input type="file" name="option_d_image" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="correct_answer">Correct Answer</label>
                <input type="text" name="correct_answer" class="form-control" value="<?php echo htmlspecialchars($question['correct_answer']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Question</button>
        </form>
    </div>
    </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
