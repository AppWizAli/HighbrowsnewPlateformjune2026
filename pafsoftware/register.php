<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        :root {
            --entry-bg: #eef4f8;
            --entry-card: rgba(255, 255, 255, 0.96);
            --entry-soft: #f7fbff;
            --entry-ink: #10233c;
            --entry-muted: #607087;
            --entry-line: #d8e4ef;
            --entry-primary: #1f7ae0;
            --entry-primary-soft: #eaf4ff;
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
                radial-gradient(circle at top right, rgba(31, 122, 224, 0.15), transparent 24%),
                radial-gradient(circle at bottom left, rgba(118, 196, 255, 0.14), transparent 26%),
                linear-gradient(180deg, #f8fbff 0%, var(--entry-bg) 100%);
            color: var(--entry-ink);
            padding: 28px;
        }

        .entry-shell {
            width: min(1140px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(280px, 0.86fr) minmax(360px, 1.14fr);
            background: var(--entry-card);
            border: 1px solid rgba(216, 228, 239, 0.95);
            border-radius: 32px;
            box-shadow: var(--entry-shadow);
            overflow: hidden;
            backdrop-filter: blur(14px);
        }

        .entry-aside {
            padding: 40px 36px;
            background:
                linear-gradient(160deg, rgba(31, 122, 224, 0.98), rgba(99, 177, 255, 0.94)),
                #1f7ae0;
            color: #fff;
            display: grid;
            gap: 28px;
            align-content: space-between;
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
            padding: 40px 36px;
            display: grid;
            align-content: center;
        }

        .entry-form-card {
            display: grid;
            gap: 20px;
        }

        .entry-form-card p {
            margin: 8px 0 0;
            color: var(--entry-muted);
            line-height: 1.6;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            color: var(--entry-muted);
            font-weight: 600;
        }

        .field input,
        .field select {
            width: 100%;
            border: 1px solid var(--entry-line);
            border-radius: 16px;
            padding: 14px 15px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            background: #fff;
        }

        .field input[type="file"] {
            padding: 12px 14px;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(31, 122, 224, 0.35);
            box-shadow: 0 0 0 4px rgba(31, 122, 224, 0.09);
            background: #fcfeff;
        }

        .field-wide {
            grid-column: 1 / -1;
        }

        .action-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
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
            color: var(--entry-muted);
            font-size: 0.94rem;
        }

        .entry-links a {
            color: var(--entry-primary);
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 920px) {
            .entry-shell {
                grid-template-columns: 1fr;
            }

            .entry-aside,
            .entry-main {
                padding: 30px 24px;
            }
        }

        @media (max-width: 620px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="entry-shell">
        <aside class="entry-aside">
            <div>
                <div class="entry-badge">PAF</div>
                <h1>Student Registration</h1>
                <p>Create a student record with ID key, profile details, and group selection before test login.</p>
            </div>

            <div class="info-card">
                <h3>Registration guide</h3>
                <ul>
                    <li>Use the official registration key provided for the student.</li>
                    <li>Upload a clear profile picture for the student record.</li>
                    <li>After successful registration, the student can log in immediately.</li>
                </ul>
            </div>
        </aside>

        <main class="entry-main">
            <div class="entry-form-card">
                <div>
                    <h2>Create student account</h2>
                    <p>Fill in the student profile details carefully. The registration key will become the student ID used during login.</p>
                </div>

                <form id="registrationForm" method="POST" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="field">
                            <label for="name">Student Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="field">
                            <label for="father_name">Father's Name</label>
                            <input type="text" id="father_name" name="father_name" required>
                        </div>
                        <div class="field">
                            <label for="group">Group</label>
                            <select id="group" name="group_name" required>
                                <option value="" disabled selected>Select a group</option>
                                <option value="Group1">Group A</option>
                                <option value="Group2">Group B</option>
                                <option value="Group3">Group C</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="registration_key">Registration Key / Student ID</label>
                            <input type="text" id="registration_key" name="registration_key" required>
                        </div>
                        <div class="field field-wide">
                            <label for="picture">Profile Picture</label>
                            <input type="file" id="picture" name="picture" accept="image/*" required>
                        </div>
                    </div>

                    <div class="action-row" style="margin-top:18px;">
                        <button type="submit" class="btn-primary">Complete Registration</button>
                        <a class="btn-secondary" href="userlogin.php">Open Student Login</a>
                    </div>
                </form>

                <div class="entry-links">
                    <span>Already registered?</span>
                    <a href="userlogin.php">Go to login</a>
                </div>
            </div>
        </main>
    </div>

    <script>
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'register_process.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire({
                        title: 'Registration Failed',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Registration Successful',
                        text: 'Student ID: ' + response.user_id,
                        icon: 'success',
                        confirmButtonText: 'Continue'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'userlogin.php';
                        }
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Unable to submit the registration right now.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    </script>
</body>
</html>
