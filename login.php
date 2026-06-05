<?php
header('Location: /highbrows_software/login');
exit;
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
     <title>HighBrows Pre-Cadet School</title>
    <meta name="author" content="Vecuro">
    <meta name="description" content="Educino - Online Courses and Education HTML Template">
    <meta name="keywords" content="academic, artist, center, club, coach, college, drive, driving, education, entertainment, gambling, golf, jackpot, knowledge, money, multipurpose, music, song, student">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="assets/img/mylogo.png" type="image/x-icon">
    <link rel="icon" href="assets/img/mylogo.png" type="image/x-icon">


    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">


    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/app.min.css"> -->
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
 <link rel="stylesheet" href="styles/index.css">
 <link rel="stylesheet" href="assets/Login-regiration/login-reg.css">
</head>


<body>


    <!--[if lte IE 9]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->



    <!--********************************
   		Code Start From Here 
	******************************** -->




    <!--==============================
     Preloader
    ==============================-->
    <div class="preloader  ">
        <button class="vs-btn preloaderCls">Cancel Preloader </button>
        <div class="preloader-inner">
            <div class="loader"></div>
        </div>
    </div>

<!-- Where you want the navbar to appear -->
 <!-- PHP Include Navbar -->
  <?php include 'Includes/Navbar.php'; ?>

    <!--==============================
    Breadcumb
    ============================== -->
<div class="breadcumb-wrapper" data-bg-src="assets/img/breadcumb/breadcumb-bg.png">
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Login</h1>
            <p class="mt-2 text-white mt-5">Enter your credentials to access your account.</p>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li>Login</li>
                </ul>
            </div>
        </div>
    </div>
</div>

    <!--==============================
    Login & Register
    ==============================-->
<section class="space-top space-extra-bottom py-4 py-sm-5 px-3 px-sm-4 px-md-5">
  <div class="title-area3 text-center mb-4 wow fadeInUp" data-wow-delay="0.3s">
    <span class="sec-subtitle style1 d-block mb-2 fs-6">Welcome Back</span>
    <h2 class="sec-title fs-3 fs-sm-2 fs-md-1">Login to Account</h2>
  </div>

  <div class="container">
    <div class="row gx-3 gx-sm-4 gx-md-5 justify-content-center align-items-center">
      
      <!-- Left Side Image -->
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <div class="img-box5 mega-hover wow fadeInUp" data-wow-delay="0.3s">
          <div class="img-1 rounded overflow-hidden" style="height: 100%; max-height: 450px;">
            <img class="w-100 h-100 object-fit-cover" src="assets/img/course/pexels-photo-5475752.jpeg" alt="Login Illustration">
          </div>
        </div>
      </div>

      <!-- Right Side Login Form -->
      <div class="col-12 col-lg-6">
        <form class="form-style4 signup p-3 p-sm-4 p-md-5 rounded  wow fadeInUp" method="post" action=""  data-wow-delay="0.4s" data-bg-src="assets/img/bg/course-bg-pattern.jpg">
          <h2 class="form-title mb-4 text-start fs-4">LOG IN</h2>

          <!-- Email/Username -->
          <div class="form-group mb-3 position-relative">
            <input type="text" autocomplete="off" name="email" id="email" class="form-control login-input ps-5 py-2"
              placeholder="Username or email address" required>
            <i class="fas fa-user position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); color: gray;"></i>
          </div>

          <!-- Password -->
          <div class="form-group mb-3 position-relative">
            <input type="password" autocomplete="off" name="password" id="password" class="form-control login-input ps-5 py-2"
              placeholder="Password" required>
            <i class="fas fa-lock position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); color: gray;"></i>
          </div>

         <!-- Remember Me & Forgot Password -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-1">
  <div class="form-check mb-1 mb-sm-0">
    <input type="checkbox" class="form-check-input" name="rememberlogin" id="rememberlogin">
    <label class="form-check-label small" for="rememberlogin">Remember me</label>
  </div>
  <a class="forget-link text-decoration-none small text-sm-end" href="#">Forget a password?</a>
</div>


          <!-- Login Button -->
          <div class="d-flex justify-content-end mb-3">
 <button type="submit" class="vs-btn d-flex align-items-center gap-2" style="width: 120px;">
              Login <i class="fas fa-sign-in-alt"></i>
            </button>
          </div>

          <!-- Registration Link -->
          <div class="d-flex justify-content-start align-items-center">
            <p class="mb-0 small">Don't have an account?</p>
            <a class="forget-link text-decoration-none ms-2 small" href="registration.php">Register here</a>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>





    <!--==============================

    Footer Area
    ==============================-->
   <?php include 'Includes/footer.php'; ?>    <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>

    <!--********************************
			Code End  Here 
	******************************** -->

    <!--==============================
        All Js File
    ============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Slick Slider -->
    <script src="assets/js/slick.min.js"></script>
    <!-- <script src="assets/js/app.min.js"></script> -->
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Wow.js Animation -->
    <script src="assets/js/wow.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>


</body>

</html>
