<?php
session_start();

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/result_service.php';

$pdo = getPDOConnection();

if (!isset($_SESSION['user'])) {
    header('Location: userlogin.php');
    exit();
}

$user = $_SESSION['user'];
$userId = pafNormaliseUserId($user['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_id'])) {
    $_SESSION['selected_test_id'] = (int) $_POST['test_id'];
    header('Location: exam.php');
    exit();
}

$tests = pafFetchTests($pdo);
$selectedTestId = isset($_SESSION['selected_test_id']) ? (int) $_SESSION['selected_test_id'] : 0;
$selectedTestName = '';
$subjects = [];
$pendingSubjects = [];
$completedSubjectIds = [];
$activeSubject = null;
$activeSubjectId = 0;
$totalQuestions = 0;
$questions = [];
$questionStatuses = [];
$currentQuestionIndex = 0;
$currentQuestion = null;
$currentStatus = ['answer' => '', 'mark_for_review' => 0, 'is_skipped' => 0];
$isTestFullyCompleted = false;
$nextPendingSubjectId = null;
$currentSubjectCompleted = false;
$subjectNoMcqs = false;

if ($selectedTestId > 0) {
    $subjects = pafFetchTestSubjects($pdo, $selectedTestId);
    if ($subjects !== []) {
        $selectedTestName = (string) $subjects[0]['test_name'];
        $subjectIds = array_map(static fn($subject) => (int) $subject['id'], $subjects);
        $completedSubjectIds = pafCompletedSubjectIds($pdo, $userId, $subjectIds);

        foreach ($subjects as $subject) {
            if (!in_array((int) $subject['id'], $completedSubjectIds, true)) {
                $pendingSubjects[] = $subject;
            }
        }

        $isTestFullyCompleted = count($pendingSubjects) === 0;

        if (!$isTestFullyCompleted) {
            $requestedSubjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
            $firstPendingSubject = $pendingSubjects[0];
            $activeSubject = $firstPendingSubject;

            foreach ($pendingSubjects as $subject) {
                if ((int) $subject['id'] === $requestedSubjectId) {
                    $activeSubject = $subject;
                    break;
                }
            }

            $activeSubjectId = (int) $activeSubject['id'];
            $nextPendingSubjectId = null;
            foreach ($pendingSubjects as $index => $subject) {
                if ((int) $subject['id'] === $activeSubjectId && isset($pendingSubjects[$index + 1])) {
                    $nextPendingSubjectId = (int) $pendingSubjects[$index + 1]['id'];
                    break;
                }
            }

            $currentSubjectCompleted = in_array($activeSubjectId, $completedSubjectIds, true);
            $questionStatement = $pdo->prepare(
                'SELECT * FROM questions WHERE subject_id = ? ORDER BY sequence_number ASC, id ASC'
            );
            $questionStatement->execute([$activeSubjectId]);
            $questions = $questionStatement->fetchAll();
            $totalQuestions = count($questions);
            $subjectNoMcqs = $totalQuestions === 0;

            if (!$subjectNoMcqs) {
                $questionStatuses = pafFetchQuestionStatuses(
                    $pdo,
                    $userId,
                    array_map(static fn($question) => (int) $question['id'], $questions)
                );

                $currentQuestionIndex = isset($_GET['q']) ? (int) $_GET['q'] : 0;
                if ($currentQuestionIndex < 0) {
                    $currentQuestionIndex = 0;
                }
                if ($currentQuestionIndex >= $totalQuestions) {
                    $currentQuestionIndex = $totalQuestions - 1;
                }

                $currentQuestion = $questions[$currentQuestionIndex];
                $currentStatus = $questionStatuses[(int) $currentQuestion['id']] ?? $currentStatus;
            }
        }
    }
}

function pafEsc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAF Software</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --ink: #122033;
            --muted: #5f7188;
            --primary: #0d6efd;
            --primary-soft: #dcebff;
            --success: #198754;
            --warning: #f4b400;
            --warning-soft: #fff4bf;
            --danger: #dc3545;
            --danger-soft: #ffe0e4;
            --border: #d8e0eb;
            --shadow: 0 24px 60px rgba(16, 35, 61, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.14), transparent 30%),
                linear-gradient(180deg, #eef4fb 0%, #f6f9fc 100%);
            color: var(--ink);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page-shell {
            width: min(1400px, calc(100% - 32px));
            margin: 24px auto 40px;
        }

        .topbar,
        .card,
        .hero-card,
        .empty-card,
        .question-card,
        .sidebar-card,
        .modal-card {
            background: var(--card);
            border: 1px solid rgba(216, 224, 235, 0.82);
            box-shadow: var(--shadow);
            border-radius: 24px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-badge {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0d6efd, #62b0ff);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
        }

        .brand h1,
        .hero-card h2,
        .section-title,
        .question-card h2 {
            margin: 0;
        }

        .muted {
            color: var(--muted);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 10px 14px;
            background: #f3f7fc;
            color: var(--muted);
            font-weight: 600;
        }

        .btn,
        button.btn,
        input[type="submit"].btn {
            border: 0;
            border-radius: 14px;
            padding: 12px 18px;
            font-family: inherit;
            font-size: 0.96rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #4f9cff);
            color: #fff;
            box-shadow: 0 16px 30px rgba(13, 110, 253, 0.2);
        }

        .btn-secondary {
            background: #eef4fb;
            color: var(--ink);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f8be18, #ffd95d);
            color: #5e4300;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #f26c78);
            color: #fff;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754, #49b97b);
            color: #fff;
        }

        .hero-card,
        .empty-card {
            padding: 32px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: start;
        }

        .hero-card p {
            line-height: 1.7;
            max-width: 700px;
        }

        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .test-option {
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 18px;
            background: linear-gradient(180deg, #ffffff, #f7fbff);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .test-option strong {
            font-size: 1.02rem;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.8fr) minmax(320px, 0.9fr);
            gap: 24px;
            align-items: start;
        }

        .question-card,
        .sidebar-card {
            padding: 24px;
        }

        .subject-banner {
            background: linear-gradient(135deg, #0d6efd, #72bcff);
            color: #fff;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .meta-box {
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px;
            background: #f8fbff;
        }

        .meta-box span {
            display: block;
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .question-card h2 {
            font-size: 1.4rem;
            line-height: 1.55;
            margin-bottom: 18px;
        }

        .question-media img,
        .profile-panel img,
        .option-image {
            max-width: 100%;
            border-radius: 18px;
        }

        .option-list {
            display: grid;
            gap: 12px;
            margin-top: 22px;
        }

        .option-row {
            display: grid;
            grid-template-columns: 56px 28px minmax(0, 1fr);
            gap: 14px;
            align-items: start;
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .option-letter {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #ebf4ff;
            color: #0d6efd;
            display: grid;
            place-items: center;
            font-weight: 800;
        }

        .option-row input[type="radio"] {
            margin-top: 8px;
            transform: scale(1.15);
            cursor: pointer;
        }

        .option-row.selected {
            border-color: #7fb5ff;
            background: #f4f9ff;
        }

        .option-text {
            font-size: 1rem;
            line-height: 1.6;
        }

        .option-text p {
            margin: 0;
        }

        .bottom-controls {
            margin-top: 18px;
            display: grid;
            gap: 14px;
        }

        .control-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #fbfdff;
        }

        .control-row input {
            transform: scale(1.15);
            cursor: pointer;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 22px;
            flex-wrap: wrap;
        }

        .nav-actions {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-top: 18px;
        }

        .nav-actions .btn {
            width: 100%;
        }

        .sidebar-card {
            position: sticky;
            top: 18px;
        }

        .profile-panel {
            display: grid;
            gap: 16px;
        }

        .profile-box {
            display: grid;
            grid-template-columns: 78px minmax(0, 1fr);
            gap: 14px;
            align-items: center;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #f8fbff;
        }

        .profile-box img {
            width: 78px;
            height: 78px;
            object-fit: cover;
        }

        .timer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .timer-card {
            border-radius: 18px;
            padding: 16px;
            text-align: center;
            background: #f8fbff;
            border: 1px solid var(--border);
        }

        .timer-card strong {
            display: block;
            font-size: 1.4rem;
            margin-top: 8px;
        }

        .sidebar-card select {
            width: 100%;
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 12px 14px;
            font-family: inherit;
            margin-top: 16px;
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 16px 0 12px;
        }

        .legend span,
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .status-pill.pending,
        .legend .pending {
            background: #edf2f8;
            color: #4d6076;
        }

        .status-pill.answered,
        .legend .answered {
            background: #dbf8e8;
            color: #0b6b3f;
        }

        .status-pill.skipped,
        .legend .skipped {
            background: var(--danger-soft);
            color: #952531;
        }

        .status-pill.review,
        .legend .review {
            background: var(--warning-soft);
            color: #7d5b00;
        }

        .question-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .question-pill {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fff;
            padding: 10px 12px;
            text-align: left;
            font-family: inherit;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .question-pill.active {
            border-color: #0d6efd;
            box-shadow: inset 0 0 0 1px #0d6efd;
        }

        .question-pill small {
            display: block;
            margin-top: 6px;
            font-size: 0.78rem;
        }

        .modal-shell {
            position: fixed;
            inset: 0;
            background: rgba(12, 23, 37, 0.62);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 50;
        }

        .modal-shell.open {
            display: flex;
        }

        .modal-card {
            width: min(960px, 100%);
            max-height: 90vh;
            overflow-y: auto;
            padding: 28px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .close-modal {
            border: 0;
            background: #edf3fb;
            color: var(--ink);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            cursor: pointer;
        }

        .result-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin: 18px 0 20px;
        }

        .result-tile {
            border-radius: 18px;
            padding: 16px;
            border: 1px solid var(--border);
            background: #f8fbff;
        }

        .result-tile span {
            display: block;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .no-mcq-note {
            margin-top: 18px;
            padding: 18px;
            border-radius: 18px;
            background: #fdf5db;
            border: 1px solid #f7e39d;
            color: #6b5500;
        }

        .loader {
            padding: 28px;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 1024px) {
            .hero-grid,
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .sidebar-card {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .page-shell {
                width: min(100% - 16px, 100%);
                margin-top: 16px;
            }

            .topbar,
            .question-card,
            .sidebar-card,
            .hero-card,
            .empty-card {
                border-radius: 20px;
                padding: 20px;
            }

            .option-row {
                grid-template-columns: 44px 24px minmax(0, 1fr);
                padding: 14px;
            }

            .timer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="brand">
                <div class="brand-badge"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <h1>PAF Software</h1>
                    <div class="muted">Student testing portal and subject-wise result tracking</div>
                </div>
            </div>
            <div class="topbar-actions">
                <span class="chip"><i class="fa-regular fa-id-card"></i> <?= pafEsc($user['name']) ?> (<?= pafEsc($user['id']) ?>)</span>
                <a class="btn btn-secondary" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
                <a class="btn btn-secondary" href="reset_test.php"><i class="fa-solid fa-rotate"></i> Change Test</a>
                <a class="btn btn-danger" href="userlogout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <?php if ($selectedTestId === 0): ?>
            <div class="hero-grid">
                <div class="hero-card">
                    <h2>Start Your Test Journey</h2>
                    <p class="muted">
                        Select a test to begin. Your subject progress, review marks, and final results will be saved
                        subject by subject so you can continue an unfinished test cleanly.
                    </p>

                    <?php if ($tests === []): ?>
                        <div class="no-mcq-note">
                            There are no tests available right now. Please ask the administrator to add a test first.
                        </div>
                    <?php else: ?>
                        <form method="post">
                            <div class="test-grid">
                                <?php foreach ($tests as $test): ?>
                                    <label class="test-option">
                                        <input type="radio" name="test_id" value="<?= (int) $test['id'] ?>" required>
                                        <strong><?= pafEsc($test['test_name']) ?></strong>
                                        <span class="muted">Date added: <?= pafEsc($test['date_added']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="action-bar">
                                <div class="muted">Choose one test and continue to the subject screen.</div>
                                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-arrow-right"></i> Continue</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="hero-card">
                    <h2>Quick Access</h2>
                    <div class="test-grid" style="grid-template-columns:1fr;">
                        <a class="test-option" href="register.php">
                            <strong><i class="fa-solid fa-user-plus"></i> Register Student</strong>
                            <span class="muted">Create a new student profile with registration key and photo.</span>
                        </a>
                        <a class="test-option" href="userlogin.php">
                            <strong><i class="fa-solid fa-user-check"></i> Student Login</strong>
                            <span class="muted">Open the student login page.</span>
                        </a>
                        <a class="test-option" href="login.php">
                            <strong><i class="fa-solid fa-user-shield"></i> Admin Login</strong>
                            <span class="muted">Manage tests, subjects, questions, and student results.</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php elseif ($subjects === []): ?>
            <div class="empty-card">
                <h2>No Subjects Found</h2>
                <p class="muted">This test does not have any subjects yet. Ask the administrator to create subjects before students attempt it.</p>
                <div class="action-bar">
                    <a class="btn btn-secondary" href="reset_test.php"><i class="fa-solid fa-rotate"></i> Choose Another Test</a>
                </div>
            </div>
        <?php elseif ($isTestFullyCompleted): ?>
            <div class="empty-card">
                <h2><?= pafEsc($selectedTestName) ?> Already Completed</h2>
                <p class="muted">
                    This student has already completed all subjects for this test. You can view the saved results below
                    or reset only this test to allow a fresh attempt.
                </p>
                <div class="action-bar">
                    <button class="btn btn-primary" type="button" onclick="openOverallResultsModal(<?= (int) $selectedTestId ?>)">
                        <i class="fa-solid fa-chart-column"></i> View Results
                    </button>
                    <a class="btn btn-warning" href="reschedule_test.php?user_id=<?= urlencode($userId) ?>&test_id=<?= (int) $selectedTestId ?>&redirect=1">
                        <i class="fa-solid fa-rotate-right"></i> Reset This Test
                    </a>
                    <a class="btn btn-secondary" href="reset_test.php"><i class="fa-solid fa-list-check"></i> Choose Another Test</a>
                </div>
            </div>
        <?php elseif ($subjectNoMcqs && $activeSubject !== null): ?>
            <?php $emptySummary = pafUpsertSubjectResult($pdo, $userId, (int) $activeSubject['id']); ?>
            <div class="empty-card">
                <h2>No MCQs In This Subject</h2>
                <p class="muted">
                    There are no MCQs in this subject: <strong><?= pafEsc($activeSubject['name']) ?></strong>.
                    This subject has been marked complete with zero questions so the student can continue.
                </p>
                <div class="result-summary-grid">
                    <div class="result-tile"><span>Test</span><strong><?= pafEsc($emptySummary['test_name']) ?></strong></div>
                    <div class="result-tile"><span>Subject</span><strong><?= pafEsc($emptySummary['subject_name']) ?></strong></div>
                    <div class="result-tile"><span>Total Questions</span><strong>0</strong></div>
                    <div class="result-tile"><span>Status</span><strong>Skipped cleanly</strong></div>
                </div>
                <div class="action-bar">
                    <?php if ($nextPendingSubjectId !== null): ?>
                        <a class="btn btn-primary" href="exam.php?subject_id=<?= $nextPendingSubjectId ?>&q=0">
                            <i class="fa-solid fa-arrow-right"></i> Continue To Next Subject
                        </a>
                    <?php else: ?>
                        <button class="btn btn-primary" type="button" onclick="openOverallResultsModal(<?= (int) $selectedTestId ?>)">
                            <i class="fa-solid fa-chart-column"></i> Show Overall Result
                        </button>
                    <?php endif; ?>
                    <a class="btn btn-secondary" href="reset_test.php"><i class="fa-solid fa-rotate"></i> Change Test</a>
                </div>
            </div>
        <?php elseif ($activeSubject !== null && $currentQuestion !== null): ?>
            <div class="dashboard-layout">
                <div class="question-card">
                    <div class="subject-banner">
                        <div>
                            <div class="muted" style="color: rgba(255,255,255,0.82);">Current test</div>
                            <h2><?= pafEsc($selectedTestName) ?></h2>
                            <div style="margin-top:8px;">Subject: <?= pafEsc($activeSubject['name']) ?></div>
                        </div>
                        <div class="chip" style="background: rgba(255,255,255,0.14); color: #fff;">
                            <i class="fa-solid fa-layer-group"></i>
                            Subject <?= array_search($activeSubjectId, array_column($subjects, 'id'), true) + 1 ?> of <?= count($subjects) ?>
                        </div>
                    </div>

                    <div class="meta-grid">
                        <div class="meta-box">
                            <span>Student Name</span>
                            <strong><?= pafEsc($user['name']) ?></strong>
                        </div>
                        <div class="meta-box">
                            <span>Student ID</span>
                            <strong><?= pafEsc($user['id']) ?></strong>
                        </div>
                        <div class="meta-box">
                            <span>Current Question</span>
                            <strong><?= $currentQuestionIndex + 1 ?> / <?= $totalQuestions ?></strong>
                        </div>
                        <div class="meta-box">
                            <span>Question Status</span>
                            <strong id="currentStatusText"><?= pafEsc(pafStatusLabel($currentStatus)) ?></strong>
                        </div>
                    </div>

                    <div class="question-media">
                        <h2><?= pafEsc($currentQuestion['question_text']) ?></h2>
                        <?php if (!empty($currentQuestion['question_image'])): ?>
                            <img src="<?= pafEsc($currentQuestion['question_image']) ?>" alt="Question image">
                        <?php endif; ?>
                    </div>

                    <form id="answerForm">
                        <input type="hidden" name="user_id" value="<?= pafEsc($userId) ?>">
                        <input type="hidden" name="question_id" value="<?= (int) $currentQuestion['id'] ?>">
                        <input type="hidden" name="no_answer_selected" id="noAnswerSelected" value="<?= (($currentStatus['answer'] ?? '') === 'F' || ($currentStatus['answer'] ?? '') === '') ? '1' : '0' ?>">

                        <div class="option-list">
                            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $option): ?>
                                <?php
                                $optionText = trim((string) ($currentQuestion['option_' . strtolower($option)] ?? ''));
                                $optionImage = trim((string) ($currentQuestion['option_' . strtolower($option) . '_image'] ?? ''));
                                if ($optionText === '' && $optionImage === '') {
                                    continue;
                                }
                                $isSelected = ($currentStatus['answer'] ?? '') === $option;
                                ?>
                                <div class="option-row<?= $isSelected ? ' selected' : '' ?>" data-option-row="<?= $option ?>">
                                    <div class="option-letter"><?= $option ?></div>
                                    <input
                                        type="radio"
                                        name="answer"
                                        value="<?= $option ?>"
                                        id="answer_<?= $option ?>"
                                        <?= $isSelected ? 'checked' : '' ?>
                                    >
                                    <div class="option-text">
                                        <?php if ($optionText !== ''): ?><p><?= pafEsc($optionText) ?></p><?php endif; ?>
                                        <?php if ($optionImage !== ''): ?><img class="option-image" src="<?= pafEsc($optionImage) ?>" alt="Option <?= $option ?> image"><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="bottom-controls">
                            <div class="control-row">
                                <input type="radio" name="answer" value="F" id="answer_none" <?= (($currentStatus['answer'] ?? '') === 'F') ? 'checked' : '' ?>>
                                <strong>No Selection</strong>
                                <span class="muted">Use this when the student wants to leave the question unanswered.</span>
                            </div>
                            <div class="control-row">
                                <input type="checkbox" name="mark_for_review" value="1" id="mark_for_review" <?= (($currentStatus['mark_for_review'] ?? 0) === 1) ? 'checked' : '' ?>>
                                <strong>Mark This Question For Review</strong>
                                <span class="muted">Review takes priority and highlights the question in yellow.</span>
                            </div>
                        </div>
                    </form>

                    <div class="action-bar">
                        <button class="btn btn-success" type="button" id="startTestButton"><i class="fa-solid fa-play"></i> Start Subject</button>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <button class="btn btn-secondary" type="button" onclick="navigateQuestion(<?= max(0, $currentQuestionIndex - 1) ?>)" <?= $currentQuestionIndex === 0 ? 'disabled' : '' ?>>
                                <i class="fa-solid fa-arrow-left"></i> Previous
                            </button>
                            <button class="btn btn-secondary" type="button" onclick="navigateQuestion(<?= min($totalQuestions - 1, $currentQuestionIndex + 1) ?>)" <?= $currentQuestionIndex >= $totalQuestions - 1 ? 'disabled' : '' ?>>
                                Next <i class="fa-solid fa-arrow-right"></i>
                            </button>
                            <button class="btn btn-primary" type="button" onclick="finishCurrentSubject()">
                                <i class="fa-solid fa-flag-checkered"></i> Finish Subject
                            </button>
                        </div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <div class="profile-panel">
                        <div class="profile-box">
                            <?php
                            $picturePath = trim((string) ($user['picture'] ?? ''));
                            $userPicture = $picturePath !== '' ? $picturePath : 'images/default-user.png';
                            ?>
                            <img src="<?= pafEsc($userPicture) ?>" alt="Student picture">
                            <div>
                                <strong><?= pafEsc($user['name']) ?></strong>
                                <div class="muted" style="margin-top:6px;">ID: <?= pafEsc($user['id']) ?></div>
                            </div>
                        </div>

                        <div class="timer-grid">
                            <div class="timer-card">
                                <div class="muted">Total Time</div>
                                <strong><?= (int) $activeSubject['time_in_minutes'] ?>:00</strong>
                            </div>
                            <div class="timer-card">
                                <div class="muted">Time Remaining</div>
                                <strong id="time_remaining"><?= (int) $activeSubject['time_in_minutes'] ?>:00</strong>
                            </div>
                        </div>

                        <div>
                            <div class="section-title">Question Navigator</div>
                            <select id="move_to_question" onchange="navigateToSelectedQuestion()">
                                <?php foreach ($questions as $index => $question): ?>
                                    <?php
                                    $status = $questionStatuses[(int) $question['id']] ?? ['answer' => '', 'mark_for_review' => 0, 'is_skipped' => 0];
                                    $statusName = pafStatusName($status);
                                    $label = 'Question ' . ($index + 1) . ' - ' . pafStatusLabel($status);
                                    $style = '';
                                    if ($statusName === 'review') {
                                        $style = 'background:#fff4bf;font-weight:700;';
                                    } elseif ($statusName === 'skipped') {
                                        $style = 'background:#ffe0e4;';
                                    } elseif ($statusName === 'answered') {
                                        $style = 'background:#dbf8e8;';
                                    }
                                    ?>
                                    <option
                                        value="<?= $index ?>"
                                        <?= $index === $currentQuestionIndex ? 'selected' : '' ?>
                                        style="<?= $style ?>"
                                        data-question-label="<?= pafEsc($label) ?>"
                                    ><?= pafEsc($label) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <div class="legend">
                                <span class="pending"><i class="fa-regular fa-circle"></i> Pending</span>
                                <span class="answered"><i class="fa-solid fa-check"></i> Answered</span>
                                <span class="skipped"><i class="fa-solid fa-forward"></i> Skipped</span>
                                <span class="review"><i class="fa-solid fa-flag"></i> Review</span>
                            </div>

                            <div class="question-grid">
                                <?php foreach ($questions as $index => $question): ?>
                                    <?php
                                    $status = $questionStatuses[(int) $question['id']] ?? ['answer' => '', 'mark_for_review' => 0, 'is_skipped' => 0];
                                    $statusName = pafStatusName($status);
                                    ?>
                                    <button
                                        type="button"
                                        class="question-pill <?= $statusName ?> <?= $index === $currentQuestionIndex ? 'active' : '' ?>"
                                        data-question-index="<?= $index ?>"
                                        onclick="navigateQuestion(<?= $index ?>)"
                                    >
                                        Q<?= $index + 1 ?>
                                        <small>
                                            <?php if ($statusName === 'review'): ?>
                                                <i class="fa-solid fa-flag"></i> Review
                                            <?php elseif ($statusName === 'answered'): ?>
                                                <i class="fa-solid fa-check"></i> Answered
                                            <?php elseif ($statusName === 'skipped'): ?>
                                                <i class="fa-solid fa-forward"></i> Skipped
                                            <?php else: ?>
                                                <i class="fa-regular fa-circle"></i> Pending
                                            <?php endif; ?>
                                        </small>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal-shell" id="resultsModal">
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <h2 id="resultsModalTitle">Subject Result</h2>
                    <div class="muted" id="resultsModalSubtitle">Saved summary and next action</div>
                </div>
                <button class="close-modal" type="button" onclick="closeResultsModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="resultsModalContent" class="loader">Loading...</div>
        </div>
    </div>

    <?php if ($activeSubject !== null && !$subjectNoMcqs && $currentQuestion !== null): ?>
        <script>
            const currentQuestionIndex = <?= $currentQuestionIndex ?>;
            const totalQuestions = <?= $totalQuestions ?>;
            const activeSubjectId = <?= $activeSubjectId ?>;
            const selectedTestId = <?= (int) $selectedTestId ?>;
            const userId = <?= json_encode($userId) ?>;
            const nextPendingSubjectId = <?= $nextPendingSubjectId === null ? 'null' : (int) $nextPendingSubjectId ?>;
            const subjectTimeMinutes = <?= (int) $activeSubject['time_in_minutes'] ?>;
            const timerStorageKey = `paf-timer-${userId}-${selectedTestId}-${activeSubjectId}`;

            function updateOptionSelectionUI() {
                document.querySelectorAll('[data-option-row]').forEach((row) => {
                    const answerValue = row.getAttribute('data-option-row');
                    const radio = document.getElementById(`answer_${answerValue}`);
                    row.classList.toggle('selected', Boolean(radio && radio.checked));
                });
            }

            function currentStatusName() {
                const reviewChecked = document.getElementById('mark_for_review').checked;
                const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                if (reviewChecked) {
                    return 'review';
                }
                if (selectedAnswer && selectedAnswer.value && selectedAnswer.value !== 'F') {
                    return 'answered';
                }
                if (selectedAnswer && selectedAnswer.value === 'F') {
                    return 'skipped';
                }
                return 'pending';
            }

            function currentStatusLabel(statusName) {
                if (statusName === 'review') {
                    return 'Review';
                }
                if (statusName === 'answered') {
                    return 'Answered';
                }
                if (statusName === 'skipped') {
                    return 'Skipped';
                }
                return 'Pending';
            }

            function applyCurrentQuestionStatus() {
                const statusName = currentStatusName();
                const select = document.getElementById('move_to_question');
                const option = select.options[currentQuestionIndex];
                const pill = document.querySelector(`.question-pill[data-question-index="${currentQuestionIndex}"]`);
                const label = `Question ${currentQuestionIndex + 1} - ${currentStatusLabel(statusName)}`;

                option.text = label;
                option.style.background = '';
                option.style.fontWeight = '';
                if (statusName === 'review') {
                    option.style.background = '#fff4bf';
                    option.style.fontWeight = '700';
                } else if (statusName === 'skipped') {
                    option.style.background = '#ffe0e4';
                } else if (statusName === 'answered') {
                    option.style.background = '#dbf8e8';
                }

                if (pill) {
                    pill.classList.remove('pending', 'answered', 'skipped', 'review');
                    pill.classList.add(statusName);
                    let inner = `Q${currentQuestionIndex + 1}`;
                    if (statusName === 'review') {
                        inner += '<small><i class="fa-solid fa-flag"></i> Review</small>';
                    } else if (statusName === 'answered') {
                        inner += '<small><i class="fa-solid fa-check"></i> Answered</small>';
                    } else if (statusName === 'skipped') {
                        inner += '<small><i class="fa-solid fa-forward"></i> Skipped</small>';
                    } else {
                        inner += '<small><i class="fa-regular fa-circle"></i> Pending</small>';
                    }
                    pill.innerHTML = inner;
                }

                document.getElementById('currentStatusText').textContent = currentStatusLabel(statusName);
            }

            function saveCurrentAnswer() {
                const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                document.getElementById('noAnswerSelected').value = selectedAnswer ? (selectedAnswer.value === 'F' ? '1' : '0') : '1';

                return $.ajax({
                    url: 'save_answer.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#answerForm').serialize()
                }).always(function() {
                    updateOptionSelectionUI();
                    applyCurrentQuestionStatus();
                });
            }

            function navigateQuestion(index) {
                saveCurrentAnswer().always(function() {
                    window.location.href = `exam.php?subject_id=${activeSubjectId}&q=${index}`;
                });
            }

            function navigateToSelectedQuestion() {
                const index = document.getElementById('move_to_question').value;
                navigateQuestion(index);
            }

            function openResultsModal(title, subtitle, content) {
                document.getElementById('resultsModalTitle').textContent = title;
                document.getElementById('resultsModalSubtitle').textContent = subtitle;
                document.getElementById('resultsModalContent').innerHTML = content;
                document.getElementById('resultsModal').classList.add('open');
            }

            function closeResultsModal() {
                document.getElementById('resultsModal').classList.remove('open');
            }

            function renderSubjectSummary(data) {
                const actionButton = nextPendingSubjectId !== null
                    ? `<a class="btn btn-primary" href="exam.php?subject_id=${nextPendingSubjectId}&q=0"><i class="fa-solid fa-arrow-right"></i> Continue To Next Subject</a>`
                    : `<button class="btn btn-primary" type="button" onclick="openOverallResultsModal(selectedTestId)"><i class="fa-solid fa-chart-column"></i> Show Overall Result</button>`;

                return `
                    <div class="result-summary-grid">
                        <div class="result-tile"><span>Subject</span><strong>${data.subject_name}</strong></div>
                        <div class="result-tile"><span>Correct Answers</span><strong>${data.correct_answers}</strong></div>
                        <div class="result-tile"><span>Total Questions</span><strong>${data.total_questions}</strong></div>
                        <div class="result-tile"><span>Percentage</span><strong>${data.percentage}%</strong></div>
                        <div class="result-tile"><span>Wrong Answers</span><strong>${data.wrong_answers}</strong></div>
                        <div class="result-tile"><span>Review Marked</span><strong>${data.review_questions}</strong></div>
                    </div>
                    <div class="action-bar">
                        ${actionButton}
                        <a class="btn btn-secondary" href="reset_test.php"><i class="fa-solid fa-rotate"></i> Change Test</a>
                    </div>
                `;
            }

            function finishCurrentSubject() {
                saveCurrentAnswer().always(function() {
                    $.ajax({
                        url: 'get_subject_results.php',
                        type: 'POST',
                        dataType: 'json',
                        data: { user_id: userId, subject_id: activeSubjectId },
                        success: function(response) {
                            if (!response.success) {
                                openResultsModal('Result Error', 'We could not save the subject result.', `<p>${response.error || 'Unknown error.'}</p>`);
                                return;
                            }

                            clearSubjectTimer();
                            openResultsModal(
                                'Subject Result Saved',
                                response.test_name + ' / ' + response.subject_name,
                                renderSubjectSummary(response)
                            );
                        },
                        error: function() {
                            openResultsModal('Result Error', 'We could not save the subject result.', '<p>Please try again.</p>');
                        }
                    });
                });
            }

            function openOverallResultsModal(testId) {
                document.getElementById('resultsModal').classList.add('open');
                document.getElementById('resultsModalTitle').textContent = 'Overall Result';
                document.getElementById('resultsModalSubtitle').textContent = 'Test-wise and subject-wise performance';
                document.getElementById('resultsModalContent').innerHTML = '<div class="loader">Loading overall result...</div>';

                $.ajax({
                    url: 'fetch_results.php',
                    type: 'GET',
                    data: { user_id: userId, test_id: testId },
                    success: function(html) {
                        document.getElementById('resultsModalContent').innerHTML = html;
                    },
                    error: function() {
                        document.getElementById('resultsModalContent').innerHTML = '<p>Unable to load the overall result right now.</p>';
                    }
                });
            }

            function startSubject() {
                const endTime = Date.now() + subjectTimeMinutes * 60 * 1000;
                sessionStorage.setItem(timerStorageKey, String(endTime));
                document.getElementById('startTestButton').setAttribute('data-started', '1');
                runTimer();
                document.getElementById('startTestButton').innerHTML = '<i class="fa-solid fa-hourglass-half"></i> Subject Running';
            }

            function clearSubjectTimer() {
                sessionStorage.removeItem(timerStorageKey);
                if (window.pafTimerInterval) {
                    clearInterval(window.pafTimerInterval);
                }
            }

            function runTimer() {
                if (window.pafTimerInterval) {
                    clearInterval(window.pafTimerInterval);
                }

                function tick() {
                    const endTime = parseInt(sessionStorage.getItem(timerStorageKey) || '0', 10);
                    if (!endTime) {
                        document.getElementById('time_remaining').textContent = `${subjectTimeMinutes}:00`;
                        return;
                    }

                    const remaining = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
                    const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
                    const seconds = String(remaining % 60).padStart(2, '0');
                    document.getElementById('time_remaining').textContent = `${minutes}:${seconds}`;

                    if (remaining <= 0) {
                        clearSubjectTimer();
                        finishCurrentSubject();
                    }
                }

                tick();
                window.pafTimerInterval = setInterval(tick, 1000);
            }

            document.querySelectorAll('input[name="answer"]').forEach((input) => {
                input.addEventListener('change', function() {
                    document.getElementById('noAnswerSelected').value = this.value === 'F' ? '1' : '0';
                    saveCurrentAnswer();
                });
            });

            document.getElementById('mark_for_review').addEventListener('change', function() {
                saveCurrentAnswer();
            });

            document.getElementById('startTestButton').addEventListener('click', function() {
                startSubject();
            });

            window.addEventListener('load', function() {
                updateOptionSelectionUI();
                applyCurrentQuestionStatus();
                if (sessionStorage.getItem(timerStorageKey)) {
                    document.getElementById('startTestButton').innerHTML = '<i class="fa-solid fa-hourglass-half"></i> Subject Running';
                    runTimer();
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
