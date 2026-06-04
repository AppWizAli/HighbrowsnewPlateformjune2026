<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="login.css">
   <link rel="stylesheet" href="../Includes/sidebar.css">
    <link rel="stylesheet" href="../Index/HeroAreas/css/index.css"> 
    <link rel="stylesheet" href="../Index/HeroAreas/css/heroareas.css">
</head>
<body>
  <?php include '../Includes/sidebar.php'; ?>
 <div class="login-container main">
  <div class="left-section">
    <div>
<img src="images/mylogo.png" alt="logo" width="80">

      <h4 class="mt-3">Highbrows</h4>
      <h2 class="mt-4">Welcome!</h2>
      <p>Create your account<br>and start your journey with us</p>
      <div class="mt-4">

      </div>
    </div>
  </div>

  <div class="right-section">
    <h2>HighBrows School & Pre Force Academy</h2>
    <p>Sign up to create your account</p>

<form action="Login-cruds/signupsubmit.php" method="POST">


  <div class="input-wrapper">
    <i class="fas fa-user-plus input-icon"></i>
    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
  </div>
  <div class="input-wrapper">
    <i class="fas fa-user input-icon"></i>
    <input type="text" name="username" class="form-control" placeholder="Username" required>
  </div>
  <div class="input-wrapper">
    <i class="fas fa-lock input-icon"></i>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
  </div>

  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-login mt-3" style="width: 160px;">
      <i class="fas fa-user-plus me-2"></i> SIGN UP
    </button>
  </div>

 
</form>

  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../Includes/sidebar.js"></script>
</body>
</html>
