<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.18), transparent 26%),
                linear-gradient(180deg, #eef5fb 0%, #f8fbfe 100%);
            color: #122033;
        }

        .shell {
            width: min(1180px, calc(100% - 24px));
            margin: 28px auto;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 22px;
        }

        .panel {
            background: rgba(255,255,255,0.94);
            border: 1px solid #d8e3ee;
            border-radius: 28px;
            box-shadow: 0 28px 70px rgba(18, 32, 51, 0.1);
            overflow: hidden;
        }

        .intro {
            background: linear-gradient(145deg, #0d6efd, #69b5ff);
            color: #fff;
            padding: 34px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
        }

        .content {
            padding: 32px;
        }

        h1, h2, p {
            margin: 0;
        }

        .intro p {
            margin-top: 18px;
            line-height: 1.75;
            color: rgba(255,255,255,0.92);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.18);
            font-size: 0.9rem;
            font-weight: 700;
        }

        form {
            margin-top: 22px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #24384f;
        }

        input,
        select {
            width: 100%;
            border: 1px solid #d3deeb;
            border-radius: 16px;
            padding: 14px 16px;
            font: inherit;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        button,
        .link-btn {
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
            background: #eef4fb;
            color: #122033;
        }

        .muted {
            color: #64758b;
            line-height: 1.7;
            margin-top: 10px;
        }

        .checklist {
            display: grid;
            gap: 12px;
            margin-top: 24px;
        }

        .check-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px;
            border-radius: 18px;
            background: rgba(255,255,255,0.14);
        }

        @media (max-width: 900px) {
            .shell {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="panel intro">
            <div>
                <span class="badge"><i class="fa-solid fa-user-plus"></i> Student Registration</span>
                <h1 style="margin-top:18px;">Create a clean student profile before test login.</h1>
                <p>Register the student once, assign a registration key, upload a picture, and then the student can log in using ID and name.</p>

                <div class="checklist">
                    <div class="check-item"><i class="fa-solid fa-check"></i><div>Registration key becomes the student login ID.</div></div>
                    <div class="check-item"><i class="fa-solid fa-check"></i><div>Photo is saved with the student profile for result review.</div></div>
                    <div class="check-item"><i class="fa-solid fa-check"></i><div>Students can later attempt multiple tests and results stay grouped test-wise.</div></div>
                </div>
            </div>
            <div style="font-weight:700;">PAF Software Student Onboarding</div>
        </div>

        <div class="panel content">
            <h2>Register Student</h2>
            <p class="muted">Fill in the student record carefully. After successful registration, the student can log in immediately.</p>

            <form id="registrationForm" method="POST" enctype="multipart/form-data">
                <div class="grid">
                    <div class="field">
                        <label for="name">Student Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="field">
                        <label for="father_name">Father Name</label>
                        <input type="text" id="father_name" name="father_name" required>
                    </div>
                    <div class="field">
                        <label for="group">Group</label>
                        <select id="group" name="group_name" required>
                            <option value="" disabled selected>Select a group</option>
                            <option value="Group A">Group A</option>
                            <option value="Group B">Group B</option>
                            <option value="Group C">Group C</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="registration_key">Registration Key / ID</label>
                        <input type="text" id="registration_key" name="registration_key" required>
                    </div>
                    <div class="field full">
                        <label for="picture">Student Picture</label>
                        <input type="file" id="picture" name="picture" accept="image/*" required>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit"><i class="fa-solid fa-arrow-right"></i> Register Student</button>
                    <a class="link-btn" href="userlogin.php"><i class="fa-solid fa-user-check"></i> Go To Student Login</a>
                    <a class="link-btn" href="index.php"><i class="fa-solid fa-house"></i> Back To Main Page</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('#registrationForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: 'register_process.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'error') {
                        Swal.fire({
                            title: 'Registration Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Registration Successful',
                        text: 'Student ID: ' + response.user_id,
                        icon: 'success',
                        confirmButtonText: 'Go To Login'
                    }).then(() => {
                        window.location.href = 'userlogin.php';
                    });
                },
                error: function() {
                    Swal.fire({
                        title: 'Registration Error',
                        text: 'The request could not be completed. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
</body>
</html>
