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
     <link rel="stylesheet" href="assets/indexcss/gallery.css">
     <link rel="stylesheet" href="assets/indexcss/heroarea.css">
    <link rel="stylesheet" href="styles/index.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

</head>


<body>




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







<div class="breadcumb-wrapper wow fadeInUp" data-wow-delay="0.3s" data-bg-src="assets/img/breadcumb/breadcumb-bg.png">
  <div class="container z-index-common">
    <div class="breadcumb-content text-center">
      <h1 class="breadcumb-title">Lifestyle & Discipline</h1>
      <p class="mt-2 text-white mt-5">
        At Pre Force Academy & Highbrows Cadet School, we nurture a lifestyle of discipline, <br> leadership, and self-confidence —  achievers today.
      </p>
      <div class="breadcumb-menu-wrap">
        <ul class="breadcumb-menu">
          <li><a href="index.php">Home</a></li>
          <li>Lifestyle</li>
        </ul>
      </div>
    </div>
  </div>
</div>


<style>
    /* General spacing */
.space-top {
  padding-top: 60px;
}

.space-extra-bottom {
  padding-bottom: 60px;
}

/* Section title styles */
.sec-subtitle {
  font-size: 16px;
  color: #888;
  font-weight: 500;
  margin-bottom: 10px;
}

.sec-title {
  font-size: 36px;
  font-weight: 700;
  color: #222;
  margin-bottom: 40px;
}

/* Circle appearance (animation removed) */
.vs-circle {
  border: 2px dashed #007bff;
  border-radius: 50%;
  padding: 10px;
  display: inline-block;
}.process-style1 {
  display: flex;
  align-items: flex-start;
  gap: 15px; /* Adds space between number and text */
  margin-bottom: 20px;
}

.process-number {
  font-size: 24px;
  font-weight: bold;
  color: #fff;
  background-color: #007bff;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.process-content {
  flex: 1;
}



/* Removed unnecessary animations */
@keyframes rotate-slow {}
@keyframes text-rotate-clockwise {}

/* Entrance animation for process block */
@keyframes slide-in-clockwise {
  0% {
    opacity: 0;
    transform: translate(-60px, -60px) rotate(-10deg);
  }
  100% {
    opacity: 1;
    transform: translate(0, 0) rotate(0);
  }
}

/* Remove animated vertical line */
.process-style1::before {
  content: none;
  display: none;
}

/* Removed line-fill animation */
@keyframes line-fill {}

/* Process content */
.process-title {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 10px;
}

.process-text {
  color: #555;
}

/* Image styling */
.img-box1 img {
  max-width: 100%;
  border-radius: 12px;
}

/* Mobile responsiveness */
@media (max-width: 767px) {
  .process-style1 {
    padding-left: 50px;
  }

  .process-number {
    left: -25px;
    width: 40px;
    height: 40px;
    line-height: 40px;
  }
}


</style>
<?php
include 'backend/Database/config.php';

// Fetch all life_style records
$lifestyleResult = $conn->query("SELECT * FROM life_style ORDER BY id ASC");

$steps = [];
$sectionTitle = "Lifestyle Section";
$sectionSubTitle = "Our Day";
$mainImagePath = "assets/img/default.jpg"; // fallback

if ($lifestyleResult && $lifestyleResult->num_rows > 0) {
    $i = 0;
    while ($row = $lifestyleResult->fetch_assoc()) {
        if ($i === 0) {
            $sectionTitle = htmlspecialchars($row['title']);
            $sectionSubTitle = htmlspecialchars($row['title2']);
            $mainImagePath = 'backend/Index/LifeStyle/' . $row['main_image'];
        } else {
            $steps[] = [
                'title2' => htmlspecialchars($row['title2']),
                'description' => htmlspecialchars($row['description']),
            ];
        }
        $i++;
    }
}
$totalSteps = count($steps);
$middleIndex = ceil($totalSteps / 2);
?>

<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="title-area text-center">
      <div class="sec-icon">
        <div class="vs-circle"></div>
      </div>
      <span class="sec-subtitle"><?= $sectionSubTitle ?></span>
      <h2 class="sec-title h1"><?= $sectionTitle ?></h2>
    </div>

    <div class="row align-items-center">

      <!-- Left Side Process -->
      <div class="col-md-6 col-lg process-inner1 order-2 order-lg-1">
        <?php for ($i = 0; $i < $middleIndex; $i++): ?>
          <div class="process-style1" style="animation-delay: <?= 0.2 + ($i * 0.2) ?>s;">
            <span class="process-number"><?= $i + 1 ?></span>
            <div class="process-content">
              <h3 class="process-title"><?= $steps[$i]['title2'] ?></h3>
              <p class="process-text"><?= $steps[$i]['description'] ?></p>
            </div>
          </div>
        <?php endfor; ?>
      </div>

      <!-- Center Image -->
      <div class="col-lg-5 order-1 order-lg-2 mb-30 mb-md-5 mb-lg-0 text-center">
        <div class="img-box1 style2">
          <div class="vs-circle">
            <div class="mega-hover">
              <img src="<?= $mainImagePath ?>" style="width: 300px; height: 300px;" alt="daily routine">
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side Process -->
      <div class="col-md-6 col-lg process-inner2 order-3">
        <?php for ($i = $middleIndex; $i < $totalSteps; $i++): ?>
          <div class="process-style1" style="animation-delay: <?= 0.6 + ($i * 0.2) ?>s;">
            <span class="process-number"><?= $i + 1 ?></span>
            <div class="process-content">
              <h3 class="process-title"><?= $steps[$i]['title2'] ?></h3>
              <p class="process-text"><?= $steps[$i]['description'] ?></p>
            </div>
          </div>
        <?php endfor; ?>
      </div>

    </div>
  </div>
</section>








<section class="space-bottom image-content-gallery">
  <div class="container">
    <div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
      <div class="sec-icon"><div class="vs-circle"></div></div>
      <span class="sec-subtitle">HIGHLIGHTS & MEMORIES</span>
      <h2 class="sec-title">Captured Cadet Moments</h2>
    </div>

    <div class="row align-items-center wow fadeInUp" data-wow-delay="0.4s">
      <?php
      include 'backend/Database/config.php';

      // 1. Get latest full entry (used as static title/description for all images)
      $contentQuery = "SELECT * FROM cadet_moments WHERE type = 'full' ORDER BY id DESC LIMIT 1";
      $contentResult = mysqli_query($conn, $contentQuery);
      $contentRow = mysqli_fetch_assoc($contentResult);

      $staticTitle = $contentRow ? $contentRow['title'] : '';
      $staticDescription = $contentRow ? $contentRow['description'] : '';

      // 2. Get all images for carousel
      $carouselQuery = "SELECT * FROM cadet_moments ORDER BY id DESC";
      $carouselResult = mysqli_query($conn, $carouselQuery);
      ?>

      <!-- ✅ Carousel Section -->
      <div class="col-lg-6">
        <div id="cardStyleCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
          <div class="carousel-inner">
            <?php
            $isActive = true;
            while ($row = mysqli_fetch_assoc($carouselResult)) {
              $images = [$row['main_image'], $row['main_image2']];
              foreach ($images as $image) {
                if (!empty($image)) {
                  ?>
                  <div class="carousel-item <?= $isActive ? 'active' : '' ?>">
                    <?php $isActive = false; ?>
                    <a href="#" class="category-link">
                      <div class="category-style1">
                        <div class="category-img">
                          <img class="w-100" src="backend/uploads/cadet_moments/<?= htmlspecialchars($image) ?>" style="height: 300px;" alt="Gallery">
                        </div>
                        <div class="category-content">
                          <span class="subtitle truncate-2-lines">
                            <?= htmlspecialchars(mb_strimwidth($staticDescription, 0, 100, '...')) ?>
                          </span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php
                }
              }
            }
            ?>
          </div>
        </div>
      </div>

      <!-- ✅ Side Content Section -->
      <div class="col-12 col-lg-6 px-4">
        <div class="content-container">
          <h2 class="section-heading"><?= htmlspecialchars($staticTitle) ?></h2>
          <p class="section-paragraph">
            <?= nl2br(htmlspecialchars($staticDescription)) ?>
          </p>
        </div>
      </div>
    </div> <!-- ✅ .row ends -->
  </div> <!-- ✅ container ends -->
</section>


<!-- NEXT SECTION -->
<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="row gx-50 align-items-center">
      <!-- Left Info Box -->
      <div class="col-lg-4">
        <div class="row">
          <div class="col-md-7 col-lg-12">
            <div class="img-box5 mega-hover wow fadeInUp" data-wow-delay="0.3s">
              <div class="img-1"><img class="w-100" src="assets/img/about/bg.png" alt="About Img"></div>
              <div class="box-content">
                <h3 class="img-title">Cadet Registration</h3>
                <p class="img-text">We Stand among top 5 Pre Cadet Schools since 2000</p>
                <a href="AdmissionFarm.php" class="vs-btn">Get Admission</a>
              </div>
            </div>
          </div>
          <div class="col-md-5 col-lg-12">
            <div class="media-style5 wow fadeInUp" data-wow-delay="0.4s">
              <span class="icon"><i class="fas fa-user-headset"></i></span>
              <h5 class="media-title">Admission Process</h5>
              <a class="phone" href="+8156415000"><i class="fal fa-phone-alt"></i>+92333115229</a>
              <a class="mail" href="mailto:support@education.com"><i class="fal fa-envelope"></i>support@highbrows.com</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Carousel -->
     <div class="col-lg-8">
  <div id="cardStyleCarousel" class="carousel slide wow fadeInUp" data-wow-delay="0.2s" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner" style="height: 100%; min-height: 400px;">
      <?php
      include 'backend/Database/config.php';

      // ✅ Fetch all moments
      $query = "SELECT * FROM cadet_moments ORDER BY id DESC";
      $result = mysqli_query($conn, $query);

      $isActive = true;
      while ($row = mysqli_fetch_assoc($result)) {
        $images = [$row['main_image'], $row['main_image2']];
        foreach ($images as $image) {
          if (!empty($image)) {
            ?>
            <div class="carousel-item <?= $isActive ? 'active' : '' ?>" style="height: 100%;">
              <?php $isActive = false; ?>
              <a href="#" class="category-link d-block h-100">
                <div class="category-style1 d-flex flex-column h-100">
                  <div class="category-img imageschange flex-grow-1 overflow-hidden">
                    <img src="backend/uploads/cadet_moments/<?= htmlspecialchars($image) ?>" class="w-100 h-100 object-fit-cover" alt="Gallery">
                  </div>
                  <div class="category-content p-3">
                    <!-- ✅ Title -->
                    <h5 class="category-title"><?= htmlspecialchars($row['title']) ?></h5>

                    <!-- ✅ Description (2 lines only) -->
                    <span class="subtitle truncate-2-lines">
                      <?= htmlspecialchars(mb_strimwidth($row['description'], 0, 120, '...')) ?>
                    </span>
                  </div>
                </div>
              </a>
            </div>
            <?php
          }
        }
      }
      ?>
    </div>
  </div>
</div>


    </div> <!-- row -->
  </div> <!-- container -->
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

    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


</body>

</html>