<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim((string) ($_POST['id'] ?? ''));
    $name = trim((string) ($_POST['name'] ?? ''));

    if ($id === '' || $name === '') {
        $error = 'Please enter both student ID and name.';
    } else {
        $sql = 'SELECT * FROM useres WHERE id = ? AND name = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $id, $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit();
        }

        $error = 'Invalid student ID or name.';
        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        :root {
            --entry-bg: #eef4f8;
            --entry-card: rgba(255, 255, 255, 0.95);
            --entry-ink: #10233c;
            --entry-muted: #607087;
            --entry-line: #d8e4ef;
            --entry-primary: #1f7ae0;
            --entry-primary-soft: #eaf4ff;
            --entry-danger: #c94b61;
            --entry-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            --entry-radius: 26px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(31, 122, 224, 0.16), transparent 24%),
                radial-gradient(circle at bottom right, rgba(86, 164, 255, 0.14), transparent 30%),
                linear-gradient(180deg, #f7fbff 0%, var(--entry-bg) 100%);
            color: var(--entry-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .entry-shell {
            width: min(1040px, 100%);
            display: grid;
            grid-template-columns: minmax(280px, 0.95fr) minmax(320px, 0.9fr);
            background: var(--entry-card);
            border: 1px solid rgba(216, 228, 239, 0.95);
            border-radius: 32px;
            box-shadow: var(--entry-shadow);
            overflow: hidden;
            backdrop-filter: blur(14px);
        }

        .entry-aside {
            padding: 42px 38px;
            background:
                linear-gradient(160deg, rgba(31, 122, 224, 0.98), rgba(99, 177, 255, 0.94)),
                #1f7ae0;
            color: #fff;
            display: grid;
            align-content: space-between;
            gap: 28px;
        }

        .entry-badge {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.26);
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .entry-aside h1,
        .entry-form-card h2,
        .info-card h3 {
            margin: 0;
        }

        .entry-aside h1 {
            font-size: 2rem;
            line-height: 1.05;
            margin-top: 18px;
        }

        .entry-aside p {
            margin: 12px 0 0;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.6;
        }

        .info-card {
            padding: 18px 20px;
            border-radius: var(--entry-radius);
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-card h3 {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .info-card ul {
            margin: 0;
            padding-left: 18px;
            display: grid;
            gap: 8px;
            color: rgba(255, 255, 255, 0.88);
        }

        .entry-main {
            padding: 42px 38px;
            display: grid;
            align-content: center;
        }

        .entry-form-card {
            display: grid;
            gap: 18px;
        }

        .entry-form-card p {
            margin: 8px 0 0;
            color: var(--entry-muted);
            line-height: 1.6;
        }

        .form-grid {
            display: grid;
            gap: 16px;
            margin-top: 6px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            color: var(--entry-muted);
            font-weight: 600;
        }

        .field input {
            width: 100%;
            border: 1px solid var(--entry-line);
            border-radius: 16px;
            padding: 14px 15px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            background: #fff;
        }

        .field input:focus {
            border-color: rgba(31, 122, 224, 0.35);
            box-shadow: 0 0 0 4px rgba(31, 122, 224, 0.09);
            background: #fcfeff;
        }

        .error-box {
            padding: 14px 16px;
            border-radius: 16px;
            background: #fff0f2;
            color: var(--entry-danger);
            border: 1px solid rgba(201, 75, 97, 0.16);
            font-size: 0.95rem;
        }

        .action-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 4px;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1f7ae0, #58a7ff);
            color: #fff;
            box-shadow: 0 14px 30px rgba(31, 122, 224, 0.18);
        }

        .btn-secondary {
            background: var(--entry-primary-soft);
            color: var(--entry-primary);
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-1px);
        }

        .entry-links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
            color: var(--entry-muted);
            font-size: 0.94rem;
        }

        .entry-links a {
            color: var(--entry-primary);
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 860px) {
            .entry-shell {
                grid-template-columns: 1fr;
            }

            .entry-aside,
            .entry-main {
                padding: 30px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="entry-shell">
        <aside class="entry-aside">
            <div>
                <div class="entry-badge">PAF</div>
                <h1>Student Login</h1>
                <p>Sign in with your student ID and registered name to start or continue your test smoothly.</p>
            </div>

            <div class="info-card">
                <h3>Before you continue</h3>
                <ul>
                    <li>Use the same name that was entered during registration.</li>
                    <li>Keep your student ID ready before starting the test.</li>
                    <li>If you are new, complete registration first.</li>
                </ul>
            </div>
        </aside>

        <main class="entry-main">
            <div class="entry-form-card">
                <div>
                    <h2>Welcome back</h2>
                    <p>Enter your details below. The system will take you directly to the test area after a successful login.</p>
                </div>

                <?php if ($error !== ''): ?>
                    <div class="error-box"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="field">
                            <label for="id">Student ID</label>
                            <input type="text" id="id" name="id" value="<?= htmlspecialchars((string) ($_POST['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="field">
                            <label for="name">Student Name</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars((string) ($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                    </div>

                    <div class="action-row">
                        <button type="submit" class="btn-primary">Login To Test</button>
                        <a class="btn-secondary" href="register.php">New Registration</a>
                    </div>
                </form>

                <div class="entry-links">
                    <span>Need a new student account?</span>
                    <a href="register.php">Open Registration</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
