<?php
include 'config.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: admin1_pannel.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) $_POST['username']);
    $password = (string) $_POST['password'];

    $sql = "SELECT id, username, password_hash FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: admin1_pannel.php');
            exit();
        }
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.18), transparent 28%),
                linear-gradient(180deg, #eef5fb 0%, #f8fbfe 100%);
        }

        .card {
            width: min(980px, calc(100% - 24px));
            display: grid;
            grid-template-columns: 0.95fr 1fr;
            background: rgba(255,255,255,0.94);
            border: 1px solid #d8e3ee;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 70px rgba(18, 32, 51, 0.12);
        }

        .visual {
            background: linear-gradient(150deg, #14253c, #24486f);
            color: #fff;
            padding: 34px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .content {
            padding: 34px;
        }

        h1, h2, p {
            margin: 0;
        }

        .visual p {
            margin-top: 18px;
            line-height: 1.75;
            color: rgba(255,255,255,0.9);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #24384f;
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
            line-height: 1.7;
            margin-top: 10px;
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
        <div class="visual">
            <div>
                <span class="badge"><i class="fa-solid fa-user-shield"></i> Admin Access</span>
                <h1 style="margin-top:18px;">Manage tests, MCQs, students, and results from one place.</h1>
                <p>Admins can upload tests and subjects, manage MCQs, inspect student performance, and review answers test-wise and subject-wise.</p>
            </div>
            <div style="font-weight:700;">PAF Software Administration</div>
        </div>
        <div class="content">
            <h2>Admin Login</h2>
            <p class="muted">Use your admin username and password to open the dashboard.</p>

            <?php if (isset($error)) : ?>
                <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit"><i class="fa-solid fa-arrow-right"></i> Login To Admin</button>
            </form>
            <a class="link-btn" href="index.php"><i class="fa-solid fa-house"></i> Back To Main Page</a>
        </div>
    </div>
</body>
</html>
