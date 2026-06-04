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
                <h1 class="breadcumb-title">Contact Us</h1>
                 <p class="contact-intro-text mt-4 mt-sm-5 text-white fs-6 fs-sm-5">
                We'd love to hear from you! Whether you have a question, feedback, or a business inquiry,  
                <br class="d-none d-sm-block">feel free to reach out. Our team is always here to help.
            </p>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="index.php">Home</a></li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!--==============================
    Contact Area
    ==============================-->
    <section class=" space-top space-extra-bottom">
        <div class="container">
            <div class="row gy-5 gx-5">
              <div class="col-lg-6 col-xl-6 mb-30 mb-lg-0 px-sm-5 px-md-5">
    <h2 class="h1 mt-n2">Get in Touch</h2>
    <p class="fs-md mb-4 pb-2">Become a partner school, or discover more about our work.</p>

    <h3 class="border-title2 h5">Regional Office</h3>

    <p class="contact-info mb-2">
        <i class="fas fa-clock"></i>
        Office hours are 9am – 5pm <br> Monday–Thursday and 9am – 4.30pm on Friday.
    </p>

    <p class="contact-info mb-4">
        <i class="fas fa-map-marker-alt"></i>
        GT Rd, Rawalpindi, 46000
    </p>

    <p class="contact-info mb-4">
        <i class="fas fa-envelope"></i>
        <a class="text-inherit" href="mailto:support@highbrows.com">support@highbrows.com</a>
    </p>

    <!-- Keep map unchanged as you instructed -->
    <div class="contact-map overflow-hidden rounded-20">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3325.3577097435887!2d73.10355257440628!3d33.54408124439395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38df931905b958bd%3A0xf021e348aaa9b825!2sHighbrows%20Pre%20Cadet%20School%20%26%20Academy!5e0!3m2!1sen!2s!4v1747224557962!5m2!1sen!2s" 
        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

             <div class="col-lg-6 col-xl-6">
<form action="backend/ContactUs/insert_contact.php" method="POST" class="form-style5">
    <div class="vs-circle"></div>
    <h3 class="form-title">any questions?</h3>
    <p class="form-text">become a partner school, or discover more about our work.</p>

    <div class="form-group">
        <input type="text" name="name" id="name" placeholder="Your Name" required>
    </div>

    <div class="form-group">
        <input type="email" name="email" id="email" placeholder="Email Address" required>
    </div>

 <div class="form-group" style="margin-bottom: 20px;">


  <div style="
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    max-width: 400px;
    width: 100%;
  ">
    <!-- Country Code -->
    <select id="country_code" name="country_code" required style="
      border: none;
      padding: 12px;
      background: #f8f8f8;
      outline: none;
      font-weight: bold;
      font-size: 14px;
      cursor: pointer;
      width: 110px;
      text-align-last: center;
    ">
      <option value="+92">🇵🇰 +92</option>
      <option value="+1">🇺🇸 +1</option>
      <option value="+44">🇬🇧 +44</option>
      <option value="+91">🇮🇳 +91</option>
      <option value="+61">🇦🇺 +61</option>
      <option value="+971">🇦🇪 +971</option>
    </select>

    <!-- Phone Number -->
    <input type="tel" id="phone_number" name="phone_number" placeholder="3001234567" required style="
      flex: 1;
      padding: 12px;
      border: none;
      outline: none;
      font-size: 14px;
      background: #fff;
    ">
  </div>
</div>
   
    <div class="form-group">
        <textarea name="message" id="message" placeholder="Write your message" required></textarea>
    </div>

    <button type="submit" class="vs-btn style8 mt-2">
        <i class="far fa-angle-right"></i>send message
    </button>

    <p class="form-messages"><span class="sr-only">For message will display here</span></p>
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