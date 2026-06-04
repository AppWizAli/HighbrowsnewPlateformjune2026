
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">

  
</head>
<body>
  <div class="login-container">
    <div class="left-section">
      <div>
<img src="images/mylogo.png" alt="logo" width="80">


        <h4 class="mt-3">Highbrows</h4>
        <h2 class="mt-4">Welcome Back!</h2>
        <p>To stay connected with us<br>please login with your personal info</p>
        <div class="mt-4">
         
        </div>
      </div>
    </div>

    <div class="right-section">
      <h2>HighBrows School & Pre Force Academy</h2>
      <p>Login to your account to continue</p>
      <form action="Login-cruds/loginsubmit.php" method="POST">


  <div class="input-wrapper">
    <i class="fas fa-user input-icon"></i>
    <input type="text" name="username" class="form-control" placeholder="Username" required />
  </div>
  <div class="input-wrapper">
    <i class="fas fa-lock input-icon"></i>
    <input type="password" name="password" class="form-control" placeholder="Password" required />
  </div>
  <div class="text-end">
    <a href="#" class="text-muted" style="font-size: 14px;">Forgot your password?</a>
  </div>
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-login mt-3" style="width: 150px;">
      <i class="fas fa-sign-in-alt me-2"></i> LOGIN
    </button>
  </div>
 
</form>

    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
