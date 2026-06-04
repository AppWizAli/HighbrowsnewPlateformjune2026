<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">User Registration</h2>
        <form id="registrationForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" required>
            </div>
            <div class="form-group">
                <label for="picture">Upload Picture</label>
                <input type="file" class="form-control" id="picture" name="picture" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="group">Select Group</label>
                <select class="form-control" id="group" name="group_name" required>
                    <option value="" disabled selected>Select a group</option>
                    <option value="Group1">Group A</option>
                    <option value="Group2">Group B</option>
                    <option value="Group3">Group C</option>
                </select>
            </div>
            <div class="form-group">
                <label for="registration_key">Registration Key</label>
                <input type="text" class="form-control" id="registration_key" name="registration_key" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script>
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'register_process.php',  // PHP file for processing the registration
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else if (response.status === 'success') {
                    Swal.fire({
                        title: 'Registration Successful',
                        text: 'Your ID: ' + response.user_id,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to user login page
                            window.location.href = 'userlogin.php';
                        }
                    });
                }
            }
        });
    });
    </script>

</body>
</html>
