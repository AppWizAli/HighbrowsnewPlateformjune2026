<?php
session_start();

$studentLoggedIn = isset($_SESSION['user']);
$adminLoggedIn = isset($_SESSION['admin_id']);
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
    <style>
        :root {
            --bg: #f4f8fc;
            --ink: #122033;
            --muted: #62748b;
            --primary: #0d6efd;
            --primary-deep: #0a4fb6;
            --card: rgba(255, 255, 255, 0.92);
            --border: rgba(208, 220, 235, 0.85);
            --shadow: 0 28px 65px rgba(17, 37, 63, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.18), transparent 26%),
                radial-gradient(circle at bottom right, rgba(40, 167, 69, 0.12), transparent 25%),
                linear-gradient(180deg, #eff5fb 0%, #f8fbfe 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1260px, calc(100% - 32px));
            margin: 24px auto 42px;
        }

        .topbar,
        .hero,
        .panel,
        .info-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 20px 26px;
            margin-bottom: 26px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, #0d6efd, #57abff);
            font-size: 1.2rem;
        }

        .brand h1,
        .hero h2,
        .panel h3,
        .info-card h4 {
            margin: 0;
        }

        .muted {
            color: var(--muted);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 16px;
            padding: 13px 18px;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #59a8ff);
            box-shadow: 0 18px 30px rgba(13, 110, 253, 0.2);
        }

        .btn-secondary {
            color: var(--ink);
            background: #eef4fb;
        }

        .btn-dark {
            color: #fff;
            background: linear-gradient(135deg, #15263c, #233c61);
        }

        .hero {
            padding: 34px;
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 26px;
            align-items: stretch;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            background: #e9f3ff;
            color: var(--primary-deep);
            padding: 8px 12px;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .hero h2 {
            font-size: clamp(2rem, 4vw, 3.4rem);
            line-height: 1.08;
            margin: 18px 0 16px;
            max-width: 720px;
        }

        .hero p {
            line-height: 1.75;
            max-width: 720px;
            font-size: 1.02rem;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-top: 28px;
        }

        .info-card {
            padding: 20px;
        }

        .info-card span {
            display: block;
            color: var(--muted);
            font-size: 0.92rem;
            margin-bottom: 8px;
        }

        .right-panel {
            display: grid;
            gap: 16px;
        }

        .panel {
            padding: 24px;
        }

        .portal-list {
            display: grid;
            gap: 12px;
            margin-top: 18px;
        }

        .portal-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: linear-gradient(180deg, #fff, #f8fbff);
        }

        .portal-item strong {
            display: block;
            margin-bottom: 6px;
        }

        .sections {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 22px;
        }

        .mini-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 16px 38px rgba(18, 32, 51, 0.07);
        }

        .mini-card i {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: #edf4ff;
            color: var(--primary);
            margin-bottom: 14px;
        }

        .mini-card p {
            color: var(--muted);
            line-height: 1.7;
            margin: 10px 0 0;
        }

        @media (max-width: 980px) {
            .hero,
            .sections {
                grid-template-columns: 1fr;
            }

            .hero-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .shell {
                width: min(100% - 16px, 100%);
                margin-top: 16px;
            }

            .topbar,
            .hero,
            .panel,
            .mini-card,
            .info-card {
                border-radius: 22px;
                padding: 20px;
            }

            .nav-actions,
            .hero-actions {
                width: 100%;
            }

            .nav-actions .btn,
            .hero-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="topbar">
            <div class="brand">
                <div class="brand-mark"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <h1>PAF Software</h1>
                    <div class="muted">Professional testing, student registration, and result management</div>
                </div>
            </div>
            <div class="nav-actions">
                <a class="btn btn-secondary" href="register.php"><i class="fa-solid fa-user-plus"></i> Register Student</a>
                <a class="btn btn-secondary" href="userlogin.php"><i class="fa-solid fa-user-check"></i> Student Login</a>
                <a class="btn btn-dark" href="login.php"><i class="fa-solid fa-user-shield"></i> Admin Login</a>
            </div>
        </div>

        <div class="hero">
            <div>
                <span class="eyebrow"><i class="fa-solid fa-star"></i> Exam Platform Ready</span>
                <h2>Launch clean student testing with subject-wise review, results, and admin visibility.</h2>
                <p class="muted">
                    This portal now supports student registration, student login, subject-level result saving, overall
                    test summaries, and admin review of student performance with question-by-question detail.
                </p>
                <div class="hero-actions">
                    <?php if ($studentLoggedIn): ?>
                        <a class="btn btn-primary" href="exam.php"><i class="fa-solid fa-play"></i> Continue Student Test</a>
                    <?php else: ?>
                        <a class="btn btn-primary" href="userlogin.php"><i class="fa-solid fa-arrow-right"></i> Start Student Portal</a>
                    <?php endif; ?>
                    <?php if ($adminLoggedIn): ?>
                        <a class="btn btn-secondary" href="admin1_pannel.php"><i class="fa-solid fa-chart-line"></i> Open Admin Dashboard</a>
                    <?php else: ?>
                        <a class="btn btn-secondary" href="login.php"><i class="fa-solid fa-chart-line"></i> Open Admin Dashboard</a>
                    <?php endif; ?>
                </div>

                <div class="hero-grid">
                    <div class="info-card">
                        <span>Student Flow</span>
                        <strong>Register -> Login -> Select Test -> Attempt Subjects -> View Final Result</strong>
                    </div>
                    <div class="info-card">
                        <span>Admin Flow</span>
                        <strong>Manage tests, subjects, MCQs, students, and full result detail per test</strong>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="panel">
                    <h3>Quick Portals</h3>
                    <div class="portal-list">
                        <a class="portal-item" href="register.php">
                            <div>
                                <strong>Student Registration</strong>
                                <div class="muted">Create a new student profile with registration key and photo.</div>
                            </div>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a class="portal-item" href="userlogin.php">
                            <div>
                                <strong>Student Login</strong>
                                <div class="muted">Students can sign in and continue available tests.</div>
                            </div>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a class="portal-item" href="login.php">
                            <div>
                                <strong>Admin Panel</strong>
                                <div class="muted">Upload tests, add subjects, manage MCQs, and inspect results.</div>
                            </div>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="sections">
            <div class="mini-card">
                <i class="fa-solid fa-flag"></i>
                <h3>Review Tracking</h3>
                <p>Questions marked for review are reflected in the navigator so students and admins can spot them quickly.</p>
            </div>
            <div class="mini-card">
                <i class="fa-solid fa-chart-column"></i>
                <h3>Subject + Overall Results</h3>
                <p>Every subject result is saved correctly, and the final overall result is grouped test-wise for each student.</p>
            </div>
            <div class="mini-card">
                <i class="fa-solid fa-users-gear"></i>
                <h3>Admin Visibility</h3>
                <p>Admins can review student-level performance, correct vs wrong answers, skipped questions, and full detail.</p>
            </div>
        </div>
    </div>
</body>
</html>
