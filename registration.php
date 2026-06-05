
<?php
header('Location: /highbrows_software/public/register');
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
    <!-- <link rel="stylesheet" href="..assets/img/app.min.css"> -->
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
   <div class="breadcumb-wrapper wow fadeInUp" data-wow-delay="0.3s"" data-bg-src="assets/img/breadcumb/breadcumb-bg.png">
    <div class="container z-index-common">
        <div class="breadcumb-content text-center">
            <h1 class="breadcumb-title">Register</h1>
            <p class="mt-2 text-white mt-5">Create your account to access exclusive features, updates, and more.</p>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li>Register</li>
                </ul>
            </div>
        </div>
    </div>
</div>

    <!--==============================
    Login & Register
    ==============================-->
<section class="space-top space-extra-bottom">
  <div class="title-area3 text-center wow fadeInUp" data-wow-delay="0.3s">
    <span class="sec-subtitle style1">Join Us Today</span>
    <h2 class="sec-title">Create Your Account</h2>
  </div>

  <div class="container">
    <div class="row gx-60 d-flex justify-content-center align-items-center">

      <!-- Left Side Image -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="img-box5 mega-hover wow fadeInUp" data-wow-delay="0.3s">
          <div class="img-1">
            <img class="w-100" src="assets/img/course/pexels-photo-5475752.jpeg" alt="Signup Illustration"
              style="width: 100%; height: 350px; object-fit: cover;">
          </div>
        </div>
      </div>

      <!-- Sign Up Form -->
      <div class="col-lg-6" id="signup">
    <form class="form-style4 signup p-4 wow fadeInUp" method="#" action="#"
          data-wow-delay="0.4s" data-bg-src="assets/img/bg/course-bg-pattern.jpg">
          <h2 class="form-title mb-4">SIGN UP</h2>

          <div class="form-group position-relative mb-3">
            <input type="text" autocomplete="off" name="signupname" id="signupname"
              class="form-control ps-5" placeholder="Complete Name" required>
            <i class="fas fa-user position-absolute"
              style="top: 50%; left: 15px; transform: translateY(-50%); color: gray;"></i>
          </div>

          <div class="form-group position-relative mb-3">
            <input type="email" autocomplete="off" name="signupemail" id="signupemail"
              class="form-control ps-5" placeholder="Email address" required>
            <i class="fas fa-envelope position-absolute"
              style="top: 50%; left: 15px; transform: translateY(-50%); color: gray;"></i>
          </div>

          <div class="form-group position-relative mb-3">
            <input type="password" autocomplete="off" name="signuppassword" id="signuppassword"
              class="form-control ps-5" placeholder="Password" required>
            <i class="fas fa-lock position-absolute"
              style="top: 50%; left: 15px; transform: translateY(-50%); color: gray;"></i>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="vs-btn d-flex align-items-center gap-2" style="width: 120px;">
              Register
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>


    <!--==============================

    Footer Area
    ==============================-->
  <?php include 'Includes/footer.php'; ?>
    <!-- Scroll To Top -->
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
