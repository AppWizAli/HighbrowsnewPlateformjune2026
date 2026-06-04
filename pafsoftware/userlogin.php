<?php
session_start();
require 'config.php';

if (isset($_SESSION['user'])) {
    header("Location: exam.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim((string) $_POST['id']);
    $name = trim((string) $_POST['name']);

    $sql = "SELECT * FROM useres WHERE id = ? AND name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id, $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        header("Location: exam.php");
        exit();
    }

    $error = "Invalid ID or name.";
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.18), transparent 28%),
                linear-gradient(180deg, #eff5fb 0%, #f8fbfe 100%);
        }

        .card {
            width: min(960px, calc(100% - 24px));
            display: grid;
            grid-template-columns: 1fr 0.95fr;
            background: rgba(255,255,255,0.94);
            border: 1px solid #d8e3ee;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 70px rgba(18, 32, 51, 0.12);
        }

        .panel {
            padding: 34px;
        }

        .hero {
            background: linear-gradient(145deg, #0d6efd, #68b5ff);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        h1, h2, p {
            margin: 0;
        }

        .hero p {
            margin-top: 16px;
            line-height: 1.75;
            color: rgba(255,255,255,0.9);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.16);
            font-size: 0.9rem;
            font-weight: 700;
        }

        label {
            display: block;
            margin: 0 0 8px;
            font-weight: 700;
            color: #24384f;
        }

        .field {
            margin-bottom: 18px;
        }

        input {
            width: 100%;
            border: 1px solid #d3deeb;
            border-radius: 16px;
            padding: 14px 16px;
            font: inherit;
        }

        button,
        .link-btn {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 14px 18px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        button {
            background: linear-gradient(135deg, #0d6efd, #58a7ff);
            color: #fff;
            box-shadow: 0 16px 28px rgba(13, 110, 253, 0.18);
        }

        .link-btn {
            margin-top: 12px;
            background: #eef4fb;
            color: #122033;
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #ffe4e8;
            color: #a12635;
            border: 1px solid #f4c8cf;
        }

        .muted {
            color: #64758b;
            margin-top: 10px;
            line-height: 1.7;
        }

        @media (max-width: 860px) {
            .card {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="panel hero">
            <div>
                <span class="badge"><i class="fa-solid fa-user-check"></i> Student Portal</span>
                <h1 style="margin-top:18px;">Login to continue your test.</h1>
                <p>Enter the registration ID and exact student name to continue the assigned test flow and saved progress.</p>
            </div>
            <div style="font-weight:700;">PAF Software Student Access</div>
        </div>
        <div class="panel">
            <h2>Student Login</h2>
            <p class="muted">Students can continue available tests, mark questions for review, and see subject-wise results at the end.</p>

            <?php if (isset($error)) : ?>
                <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="field">
                    <label for="id">Registration ID</label>
                    <input type="text" name="id" id="id" required>
                </div>
                <div class="field">
                    <label for="name">Student Name</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <button type="submit"><i class="fa-solid fa-arrow-right"></i> Login</button>
            </form>
            <a class="link-btn" href="register.php"><i class="fa-solid fa-user-plus"></i> Register New Student</a>
            <a class="link-btn" href="index.php"><i class="fa-solid fa-house"></i> Back to Main Page</a>
        </div>
    </div>
</body>
</html>
