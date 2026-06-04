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
<style>
/* Optional: transparent popup background */
.mfp-bg {
  background: rgba(0, 0, 0, 0.85); /* dark backdrop */
}

/* Prevent white box flash */
.mfp-content, .mfp-iframe-holder .mfp-content {
  background: transparent !important;
  box-shadow: none !important;
}

.mfp-iframe-holder .mfp-close {
  color: white; /* Optional close button color */
}
</style>


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
                <h1 class="breadcumb-title">What Our Readers Say</h1>
                <p class="text-white mt-5">Read heartfelt reviews and reflections from our community members<br>who have found inspiration and guidance through our Islamic blog.</p>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="index.php">Home</a></li>
                        <li>Reviews</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    
    <!--==============================
    About Area
    ==============================-->
    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row flex-row-reverse gx-80">
                <div class="col-lg-6">
                    <div class="picture-box6">
                        <div class="img-1 mega-hover">
                            <img src="assets/img/about/pngwing.com.png" alt="Online Learning">
                        </div>
                        <div class="vs-circle"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="sec-subtitle">What Learners Say</span>
                    <h2 class="cta-text mb-2 mb-xl-4">Empowering Education  Real Experiences</h2>
                    <p class="fs-md me-xxl-5 col-xl-11">Hear from our global community of students and educators who are transforming their futures through accessible and high-quality online learning.</p>
                    <div class="list-style1 vs-list mb-4">
                        <ul>
                            <li>Student-centered learning approach</li>
                            <li>Top-rated instructor feedback</li>
                            <li>Diverse academic and professional courses</li>
                            <li>Trusted by thousands of learners worldwide</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
  
    <!--==============================
      Team Area
  ==============================-->
  <section class="space-bottom">
    <div class="container">
        <div class="title-area text-center">
            <div class="sec-icon">
                <div class="vs-circle"></div>
            </div>
            <span class="sec-subtitle">What Learners Are Saying</span>
            <h2 class="sec-title h1">Trusted Reviews </h2>
            
        </div>
<div class="row gy-30 justify-content-center">
<?php
include 'backend/Database/config.php';

$webUploadDir = 'backend/Index/ReviewsContent/uploads/reviews/';
$query = "SELECT * FROM review_content ORDER BY id DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)):
    $title  = htmlspecialchars($row['title']);
    $desc1  = htmlspecialchars($row['description']);
    $desc2  = htmlspecialchars($row['description2']);
    $thumb  = !empty($row['thumbnail']) ? $webUploadDir . $row['thumbnail'] : 'assets/img/about/Picture.png';

    $videoFile = $row['main_video'];
    $videoUrl  = $row['video_url'];
    $popupLink = '';

    // ✅ Just use original URL or uploaded file
    if (!empty($videoUrl)) {
        $popupLink = $videoUrl;
    } elseif (!empty($videoFile)) {
        $popupLink = $webUploadDir . $videoFile;
    }
?>
  <div class="col-sm-6 col-lg-4 col-xxl-3">
    <div class="team-style2 has-border">
      <div class="team-content">
        <h4 class="team-name h5"><a href="#"><?= $title ?></a></h4>
        <p class="team-degi"><?= $desc1 ?></p>

        <div class="team-img position-relative">
          <img src="<?= $thumb ?>" alt="thumbnail" style="width: 285px; height: 140px; border-radius: 10px;">
          <?php if (!empty($popupLink)): ?>
            <a href="<?= htmlspecialchars($popupLink) ?>" class="play-btn popup-video position-center">
              <i class="fas fa-play" style="width:60px; height:60px; line-height:60px;"></i>
            </a>
          <?php endif; ?>
        </div>

        <p class="team-degi"><?= $desc2 ?></p>

        <?php if (!empty($popupLink)): ?>
          <span class="d-block mt-2">
            <a href="<?= htmlspecialchars($popupLink) ?>" class="popup-video team-courses">Watch Full Video</a>
          </span>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>


        
        <div class="text-center pt-lg-4">
            <p class="font-body fs-md fw-medium mb-2">Call us now and transform your career today</p>
            <a class="call-number1 h5" href="+4402076897888">+92333115229</a>
        </div>
    </div>
</section><!--==============================
      Testimonial Area
  ==============================-->
   <section class="overflow-hidden bgc-f6 space-top space-extra-bottom">
        <div class="shape-mockup jump d-none d-xxl-block" data-left="-14%">
            <div class="vs-border-circle"></div>
        </div>
        <div class="shape-mockup jump-img d-none d-xxl-block" data-right="-15%" data-top="170px">
            <div class="vs-circle color2"></div>
        </div>
        <div class="shape-mockup jump-img d-none d-xxl-block" data-left="8%" data-bottom="10%">
            <div class="shape-dotted style2"></div>
        </div>
        <div class="container">
            <div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
                <div class="sec-icon">
                    <div class="vs-circle"></div>
                </div>
                <span class="sec-subtitle">WHAT PEOPLE SAY</span>
                <h2 class="sec-title h1">Our Alumni Words</h2>
            </div>
       <div class="row vs-carousel" data-dots="true" data-slide-show="3" data-lg-slide-show="2" data-md-slide-show="2" data-sm-slide-show="1">
<?php
include 'backend/Database/config.php';

$uploadPath = 'backend/uploads/user_reviews/'; // ✅ Correct relative path to folder

$query = "SELECT * FROM user_reviews ORDER BY id DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)):
    $title       = htmlspecialchars($row['title']);
    $description = htmlspecialchars($row['description']);
    $imageName   = $row['main_image'];
    $image       = (!empty($imageName) && file_exists($uploadPath . $imageName)) 
                    ? $uploadPath . $imageName 
                    : 'assets/img/testimonial/default.png'; // fallback image
?>
    <div class="col-sm-6 col-xl-4">
        <div class="testi-style1">
            <div class="testi-content">
                <p class="testi-text">“ <?= $description ?> ”</p>
            </div>
            <div class="testi-client">
                <img src="<?= $image ?>" alt="<?= $title ?>" style="width: 65px; height: 65px; object-fit: cover; border-radius: 50%;">
                <h3 class="testi-name h5"><?= $title ?></h3>
                <span class="testi-degi">Highbrows Review</span>
                <div class="testi-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
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
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/magnific-popup.css">
<!-- jQuery + Magnific JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/jquery.magnific-popup.min.js"></script>

<script>
$(document).ready(function () {
  $('.popup-video').magnificPopup({
    type: 'iframe',
    preloader: true,
    removalDelay: 300,
    mainClass: 'mfp-fade',
    iframe: {
      patterns: {
        youtube: {
          index: 'youtube.com/',
          id: function (url) {
            var match = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
            return match && match[1] ? match[1] : null;
          },
          src: 'https://www.youtube.com/embed/%id%?autoplay=1'
        },
        youtu_be: {
          index: 'youtu.be/',
          id: function (url) {
            return url.split('youtu.be/')[1].split('?')[0];
          },
          src: 'https://www.youtube.com/embed/%id%?autoplay=1'
        }
      }
    },
    callbacks: {
      open: function () {
        $('.mfp-container').addClass('loading'); // Add loading state
      },
      markupParse: function (template, values, item) {
        // Delay iframe injection slightly to avoid white flash
        setTimeout(function () {
          $('.mfp-container').removeClass('loading'); // Remove loading state
        }, 300); // Delay helps smooth loading
      }
    }
  });
});
</script>




</body>

</html>