<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $sql = "SELECT * FROM useres WHERE id = ? AND name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        header("Location: index.php");
    } else {
       $error="Invalid ID or Name.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="card-title text-center mb-4">User Login</h4>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" name="id" id="id" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>