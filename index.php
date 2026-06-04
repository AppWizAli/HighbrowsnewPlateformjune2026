<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>HighBrows Pre-Cadet School</title>
<meta name="author" content="Highbrows Pre-Cadet School">
<meta name="description" content="Highbrows Pre-Cadet School - Preparing students for cadet colleges with a strong foundation in academics, discipline, physical training, and Islamic values.">
<meta name="keywords" content="Highbrows, pre-cadet school, cadet preparation, military school, student training, discipline, education, physical fitness, Islamic education, Pakistan, school for boys, academy">
<meta name="robots" content="INDEX,FOLLOW">


    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="assets/img/hero/mylogo.png" type="image/x-icon">
    <link rel="icon" href="assets/img/hero/mylogo.png" type="image/x-icon">

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
<style>
    /* Default (lg and xl) - no change needed, keep min-height: 400px or full height */
.carousel-inner {
  height: 100%;
  min-height: 400px;
 
}

/* Medium devices (md) and below - adjust height */
@media (max-width: 991.98px) {
  .carousel-inner {
    min-height: 320px !important;
     margin-bottom: 20px;
  }
  .carousel-item {
    height: 320px !important;
  }
  .category-style1 {
    height: 100%;
  }
  .category-img img {
    object-fit: cover;
    height: 100%;
  }
}

</style>
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


    
    <!--==============================
    Hero Area
    ==============================-->
     <?php include 'Includes/Navbar.php'; ?>
<?php
include 'backend/Database/config.php';

// Fetch ALL rows (not just latest)
$sql = "SELECT main_image, video_content FROM heroareas ORDER BY id DESC";
$result = $conn->query($sql);

$mediaItems = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['main_image'])) {
            $images = array_filter(array_map('trim', explode(',', $row['main_image'])));
            foreach ($images as $img) {
                $mediaItems[] = ['type' => 'image', 'src' => $img];
            }
        }
        if (!empty($row['video_content'])) {
            $videos = array_filter(array_map('trim', explode(',', $row['video_content'])));
            foreach ($videos as $vid) {
                $mediaItems[] = ['type' => 'video', 'src' => $vid];
            }
        }
    }
}
?>

<section>
  <div class="vs-carousel hero-layout1 style2">
    <div>
      <div class="hero-inner container-fluid c">
        <div class="hero-bg-wrapper">
          <?php
          if (!empty($mediaItems)) {
              foreach ($mediaItems as $index => $item) {
                  if ($item['type'] === 'image') {
                      echo '<div class="hero-bg' . ($index === 0 ? ' active' : '') . '" style="background-image: url(\'' . htmlspecialchars($item['src']) . '\');"></div>';
                  } else {
                      echo '<video class="' . ($index === 0 ? 'active' : '') . '" preload="metadata" muted>
                              <source src="' . htmlspecialchars($item['src']) . '" type="video/mp4">
                            </video>';
                  }
              }
          } else {
              echo '<div class="hero-bg active" style="background-image: url(\'assets/img/default.jpg\');"></div>';
          }
          ?>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">
              <div class="hero-content">
                <h1 class="hero-title"><span style="color: #fec624;">Highbrows </span><br>Pre-Cadet School & Academy</h1>
                <p class="hero-text">A Legacy of Trust, Growth and Leadership Since 2000.</p>
                <div class="hero-btns">
                  <a href="AdmissionFarm.php" class="vs-btn style5">
                    <i class="far fa-angle-right"></i> Apply for Admission
                  </a>
                </div>
              </div>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-bg-wrapper > div, .hero-bg-wrapper > video");
    let index = 0;

    function changeSlide() {
      slides.forEach(slide => {
        slide.classList.remove("active");

        if (slide.tagName === 'VIDEO') {
          slide.pause();
          slide.currentTime = 0;
        }
      });

      const current = slides[index];
      current.classList.add("active");

      if (current.tagName === 'VIDEO') {
        current.play();
      }

      index = (index + 1) % slides.length;
    }

    if (slides.length > 1) {
      slides[0].classList.add("active");
      if (slides[0].tagName === 'VIDEO') {
        slides[0].play();
      }
      setInterval(changeSlide, 4000); // 4 seconds per slide
    }
  });
</script>


<!-- ✅ Optional CSS (keep behavior consistent) -->
<style>
.hero-bg-wrapper > div,
.hero-bg-wrapper > video {
  display: none;
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
}
.hero-bg-wrapper > .active {
  display: block;
  z-index: 1;
}
</style>



    <!--==============================
    About Area
    ==============================-->
    <section class="space-top about-layout1 space-bottom">
        <div class="container">
            <div class="row align-items-center align-items-xxl-start">
                 <?php
include 'backend/Database/config.php';
$result = $conn->query("SELECT * FROM about_section ORDER BY id ASC LIMIT 3");
$index = 1;
?>

<div class="col-lg-5 wow fadeInUp" data-wow-delay="0.3s">
    <div class="picture-box2 style1">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="picture-<?= $index ?> mega-hover">
                <img src="backend/Index/AboutSection/<?= htmlspecialchars($row['main_image']) ?>" alt="About Img">
            </div>
            <?php $index++; ?>
        <?php endwhile; ?>
        <div class="vs-circle"></div>
    </div> 
</div>
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="about-box2">
                        <div class="title-area">
                            <span class="sec-subtitle">WELCOME TO HIGHBROWS SCHOOL & ACADEMY</span>
                            <h2 class="about-title h1">Trusted by Hundreds of Parents Across Pakistan.</h2>
                        </div>
                        <div class="about-content style2">
                            <p class="fs-md">Since 2000, Highbrows Forces School & Academy has been shaping future leaders with a focus on academic excellence, Islamic values, discipline, physical training, and strong moral character.</p>

                            <div class="media-inner">
                                <div class="call-media">
                                    <div class="media-icon"><i class="fas fa-phone-alt"></i></div>
                                    <div class="media-body">
                                        <span class="media-label">Call Anytime 24/7</span>
                                        <p class="media-info"><a href="+92335115229" class="text-inherit">+923335115229</a></p>
                                    </div>
                                </div>
                                <div class="list-style1 vs-list ">
                                    <ul>
                                        <li>Trusted by Students</li>
                                        <li>Expert Instructor</li>
                                    </ul>
                                </div>
                                <a href="about.php" class="vs-btn style8 mt-2">
                                    <i class="far fa-angle-right"></i> Get More Info
                                  </a>
                                  
                        <!-- <div class="about-content">
                            <p class="fs-md">Ducamb welcomed every pain avoided but in certa mstances owing to the claims of igation that off bu will frequently occuthe obligations of business it will ently ofcurs that pleasures.</p>

                            <div class="call-media">
                                <div class="media-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="media-body">
                                    <span class="media-label">Call Anytime 24/7</span>
                                    <p class="media-info"><a href="tel:+26921562148" class="text-inherit">+269 2156 2148</a></p>
                                </div>
                            </div>

                            <a href="about.php" class="vs-btn style3 mt-2"><i class="far fa-angle-right"></i>Get More Info</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--==============================
      Category Area
    ==============================-->
 <section class="space-bottom">
  <div class="container">
<div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
  <div class="sec-icon"><div class="vs-circle"></div></div>
  <span class="sec-subtitle">Highbrows Academy</span>
  <h2 class="sec-title">Cadet Grooming Blog & Insights</h2>
</div>


    <div class="row vs-carousel wow fadeInUp" data-wow-delay="0.4s" data-slide-show="4">
      <?php
      include 'backend/Database/config.php';
      $sql = "SELECT id, main_image, title, description FROM blogs_content ORDER BY id DESC ";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $id = $row['id'];
          $imagePath = 'backend/uploads/blogs/' . basename($row['main_image']); // ✅ fixed path
          $title = htmlspecialchars($row['title']);
          $desc = htmlspecialchars(mb_strimwidth($row['description'], 0, 50, "..."));

          echo '
          <div class="col-6 col-lg-4 col-xl-3">
            <a href="blog-details.php?id=' . $id . '" class="category-link">
              <div class="category-style1">
                <div class="category-img">
                  <img class="w-100" src="' . $imagePath . '" alt="' . $title . '">
                </div>
                <div class="category-content">
                  <h5 class="category-title">' . $title . '</h5>
                  <span class="subtitle">' . $desc . '</span>
                </div>
              </div>
            </a>
          </div>';
        }
      } else {
        echo '<p class="text-center">No blog content available.</p>';
      }
      ?>
    </div>
  </div>
</section>

    
    <!--==============================
    CTA Area
    ==============================-->
   <section class="space-top space-bottom px-3">
        <dbyv class="container ">
            <div class="cta-style2">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="cta-content">
                            <p class="cta-text">STUDENT TESTIMONIAL VIDEO</p>
                            <h2 class="cta-title h1">My Experience at Highbrows</h2>
                            <a href="team.php" class="vs-btn style2"><i class="far fa-angle-right"></i>Watch All Reviews</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="cta-img">
                            <img src="assets/img/about/Picture.png" alt="About Img">
                            <a href="https://www.youtube.com/watch?v=Qvf5itlVSPs" class="play-btn popup-video position-center"><i class="fas fa-play"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </dbyv>
    </section>

    <!--==============================

< Gallery Section -->

<section class="space-bottom gallery-section">
    <div class="container">
    <div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
    <div class="sec-icon"><div class="vs-circle"></div></div>
    <span class="sec-subtitle">CADET LIFE GALLERY</span>
    <h2 class="sec-title">Proud Moments Captured</h2>
</div>


  <?php
include 'backend/Database/config.php';

// 🔧 Correct server-side and web paths based on your comment
$uploadPath = 'backend/Index/GalleryProudMoment/uploads/proud_moments/';       // Server-side path (for file_exists)
$uploadWebPath = 'backend/Index/GalleryProudMoment/uploads/proud_moments/';    // Web-accessible path (for <img src>)

$query = "SELECT * FROM proud_moment ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="row wow fadeInUp" data-wow-delay="0.4s">
    <?php while ($row = mysqli_fetch_assoc($result)):
        $title = htmlspecialchars($row['title']);
        $description = nl2br(htmlspecialchars($row['description']));
        $imageName = $row['main_image'];

        $imageFile = $uploadPath . $imageName;
        $imageSrc = (!empty($imageName) && file_exists($imageFile))
            ? $uploadWebPath . $imageName . '?v=' . filemtime($imageFile)  // cache busting
            : 'assets/img/course/default.png';
    ?>
    <div class="col-6 col-lg-4">
        <a href="#" class="category-link">
            <div class="category-style1">
                <div class="category-img">
                    <img class="w-100" src="<?= $imageSrc ?>" alt="<?= $title ?>" style="object-fit: cover; height: 220px;">
                </div>
                <div class="category-content">
                    <h5 class="category-title"><?= $title ?></h5>
                    <span class="subtitle"><?= $description ?></span>
                </div>
            </div>
        </a>
    </div>
    <?php endwhile; ?>
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

    <div class="row  wow fadeInUp" data-wow-delay="0.4s">
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
      <div class="col-12 col-lg-6 px-5">
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


<section class="space-top space-extra-bottom">
  <div class="container">
    <div class="row gx-50">
      <!-- ✅ Full Width Carousel -->
      <div class="col-12">
        <div id="cardStyleCarousel" class="carousel slide wow fadeInUp" data-wow-delay="0.2s" data-bs-ride="carousel" data-bs-interval="3000">
          <div class="carousel-inner" style="height: 100%; min-height: 400px;">
            <?php
            include 'backend/Database/config.php';

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
                          <h5 class="category-title"><?= htmlspecialchars($row['title']) ?></h5>
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
    </div>
  </div>
</section>



<!-- ==============================-->
<section class="feature-layout1 space-top space-bottom" data-bg-src="assets/img/bg/service-bg-pattern.jpg">
    <div class="container">
        <div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
            <div class="sec-icon">
                <div class="vs-circle"></div>
            </div>
            <span class="sec-subtitle">HIGHBROWS CADET ACADEMY</span>
            <h2 class="sec-title h1">Our Services</h2>
        </div>
 <div class="row wow fadeInUp" data-wow-delay="0.4s">
<?php
include 'backend/Database/config.php';

$uploadPath = 'backend/Index/OurSevices/uploads/services/'; // ✅ Corrected path
$query = "SELECT * FROM our_services ORDER BY id DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)):
    $title = htmlspecialchars($row['title']);
    $description = htmlspecialchars($row['description']);
    $image = !empty($row['main_image']) ? $uploadPath . $row['main_image'] : 'assets/img/icons/default.png';
?>
    <div class="col-sm-6 col-xl-4">
        <div class="feature-style3">
            <div class="feature-icon icon">
                <img src="<?= $image ?>" alt="<?= $title ?>" style="width: 50px; height: 50px;">
            </div>
            <h4 class="feature-title"><?= $title ?></h4>
            <p class="feature-text"><?= $description ?></p>
        </div>
    </div>
<?php endwhile; ?>
</div>


    </div>
</section>

<section class="image-content-gallery mt-5 px-2 px-sm-3 px-md-4 px-lg-0 px-xl-0 pb-4 pb-sm-5">
    <div class="container-fluid">
        <!-- Section Title Area -->
        <div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
            <div class="sec-icon">
                <div class="vs-circle"></div>
            </div>
            <span class="sec-subtitle">CADET GALLERY</span>
<h2 class="sec-title">Moments That Define Highbrows</h2>

        </div>

<div class="row gx-3 gy-4 align-items-start">
<?php
include 'backend/Database/config.php';

// 1. Get latest full content record (non-empty title or description)
$contentQuery = "SELECT * FROM military_moment 
                 WHERE TRIM(title) <> '' OR TRIM(description) <> '' 
                 ORDER BY id DESC LIMIT 1";
$contentResult = mysqli_query($conn, $contentQuery);
$contentRow = mysqli_fetch_assoc($contentResult);

$staticTitle = $contentRow ? $contentRow['title'] : 'Military Training Excellence';
$staticDescription = $contentRow ? $contentRow['description'] : 'Details about military training.';

// 2. Get all records for images
$carouselQuery = "SELECT * FROM military_moment ORDER BY id DESC";
$carouselResult = mysqli_query($conn, $carouselQuery);

// Optional helper to shorten description (2 lines)
function shortenText($text, $lineCount = 2) {
    $maxChars = $lineCount * 80;
    return mb_strlen($text) > $maxChars ? mb_substr($text, 0, $maxChars) . '...' : $text;
}
?>

<!-- 🔹 Left Side: Carousel -->
<div class="col-lg-7">
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
                                    <div class="category-img overflow-hidden rounded" style="height: 550px;">
                                        <img src="backend/uploads/military_moments/<?= htmlspecialchars($image) ?>"
                                             class="img-fluid w-100" alt="Military Gallery"
                                             style="object-fit: cover; height: 100%;">
                                    </div>
                                    <div class="category-content mt-3">
                                        <span class="subtitle">
                                            <?= nl2br(htmlspecialchars(shortenText($staticDescription, 2))) ?>
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

<!-- 🔹 Right Side: Static Title and Description -->
<div class="col-lg-5 px-4 px-lg-5 px-xl-5">

    <h3><?= htmlspecialchars($staticTitle) ?></h3>
    <p><?= nl2br(htmlspecialchars($staticDescription)) ?></p>
</div>
</div>

    </div>
</section>



      <!-- Course Area -->
    <!-- ==============================-->
        <section class="space-bottom">
            <div class="container">
                <div class="title-area text-center wow fadeInUp" data-wow-delay="0.3s">
                    <div class="sec-icon">
                        <div class="vs-circle"></div>
                    </div>
                    <span class="sec-subtitle">WELCOME TO YOUR CADET JOURNEY</span>
                    <h2 class="sec-title">Explore Courses</h2>
                </div>
                <div class="row vs-carousel" data-slide-show="3" data-lg-slide-show="3" data-md-slide-show="2">
                    <div class="col-sm-6 col-xl-4">
                        <div class="course-style2">
                            <div class="course-img">
                                <a href="course-details.php"><img class="w-100" src="assets/img/course/course3.jpeg" alt="Course Img"></a>
                                <span class="course-price">$778</span>
                            </div>
                            <div class="course-content">
                                <h3 class="h5 course-name"><a href="course-details.php" class="text-inherit">📘 3-Month Cadet Entry Prep Crash Course</a></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-user-tie"></i>775 Students</span>
                                    <a href="course-details.php"><i class="far fa-tv"></i>45 Leson</a>
                                    <span><i class="far fa-clock"></i>78h 15m</span>
                                </div>
                                <div class="course-footer">
                                    <div class="course-teacher"><a href="team-details.php" class="text-inherit"><img src="assets/img/course/imran.jpeg" alt="Course">By Imran Waheed</a></div>
                                    <div class="course-review"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="course-style2">
                            <div class="course-img">
                                <a href="course-details.php"><img class="w-100" src="assets/img/course/course4.jpg" alt="Course Img"></a>
                                <span class="course-price">$963</span>
                            </div>
                            <div class="course-content">
                                <h3 class="h5 course-name"><a href="course-details.php" class="text-inherit">📘 6-Month Entry Test Foundation Program</a></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-user-tie"></i>779 Students</span>
                                    <a href="course-details.php"><i class="far fa-tv"></i>79 Leson</a>
                                    <span><i class="far fa-clock"></i>6h 36m</span>
                                </div>
                                <div class="course-footer">
                                    <div class="course-teacher"><a href="team-details.php" class="text-inherit"><img src="assets/img/course/imran.jpeg" alt="Course">By Ali Hamza</a></div>
                                    <div class="course-review"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <div class="course-style2">
                            <div class="course-img">
                                <a href="course-details.php"><img class="w-100" src="assets/img/course/course5.avif" alt="Course Img"></a>
                                <span class="course-price">$445</span>
                            </div>
                            <div class="course-content">
                                <h3 class="h5 course-name"><a href="course-details.php" class="text-inherit">📘 9-Month Cadet Colleges Elite Prep</a></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-user-tie"></i>75 Students</span>
                                    <a href="course-details.php"><i class="far fa-tv"></i>78 Leson</a>
                                    <span><i class="far fa-clock"></i>17h 11m</span>
                                </div>
                                <div class="course-footer">
                                    <div class="course-teacher"><a href="team-details.php" class="text-inherit"><img src="assets/img/course/imran.jpeg" alt="Course">By Ibrahim</a></div>
                                    <div class="course-review"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row vs-carousel" data-slide-show="2" data-lg-slide-show="3" data-md-slide-show="2">
                    <div class="col-md-6">
                        <div class="course-style2 layout2">
                            <div class="course-img">
                                <a href="course-details.php"><img src="assets/img/course/course6.jpg" alt="Course Img"></a>
                                <span class="course-price">$556</span>
                            </div>
                            <div class="course-content">
                                <h3 class="h5 course-name"><a href="course-details.php" class="text-inherit">📘 12-Month Full-Year Pre-Cadet Training</a></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-user-tie"></i>631 Students</span>
                                    <a href="course-details.php"><i class="far fa-tv"></i>41 Leson</a>
                                    <span><i class="far fa-clock"></i>9h 11m</span>
                                </div>
                                <div class="course-footer">
                                    <div class="course-teacher"><a href="team-details.php" class="text-inherit"><img src="assets/img/course/imran.jpeg" alt="Course">By Junaid Riaz</a></div>
                                    <div class="course-review"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="course-style2 layout2">
                            <div class="course-img">
                                <a href="course-details.php"><img src="assets/img/course/course6.jpg" alt="Course Img"></a>
                                <span class="course-price">$442</span>
                            </div>
                            <div class="course-content">
                                <h3 class="h5 course-name"><a href="course-details.php" class="text-inherit">📘 Physical & Interview Training Bootcamp</a></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-user-tie"></i>775 Students</span>
                                    <a href="course-details.php"><i class="far fa-tv"></i>78 Leson</a>
                                    <span><i class="far fa-clock"></i>6h 11m</span>
                                </div>
                                <div class="course-footer">
                                    <div class="course-teacher"><a href="team-details.php" class="text-inherit"><img src="assets/img/course/imran.jpeg" alt="Course">By Ahmad</a></div>
                                    <div class="course-review"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--==============================
    Features Area
    ==============================-->




    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-7 text-center text-xl-start">
                    <div class="title-area">
                        <span class="sec-subtitle">TRAINING AND LEADERSHIP PROGRAMME</span>
                        <h2 class="sec-title h1">Cadet Training Program</h2>
                    </div>
                    <div class="row gx-80 gy-xl-4 mb-4 mb-xl-0">
                        <div class="col-md-6 col-xl-6 wow fadeInUp" data-wow-delay="0.2s">
                            <div class="media-style4">
                                <div class="media-icon"><img src="assets/img/icon/training-icon-1-1.svg" alt=""></div>
                                <h5 class="media-title">📘 Expert Teachers</h5>
                                <p>Learn from retired officers & seasoned educators.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="media-style4">
                                <div class="media-icon"><img src="assets/img/icon/training-icon-1-2.svg" alt=""></div>
                                <h5 class="media-title">📖 Islamic & Modern Education</h5>
                                <p>Balanced focus on academics and values.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="media-style4">
                                <div class="media-icon"><img src="assets/img/icon/training-icon-1-3.svg" alt=""></div>
                                <h5 class="media-title">🧠 Leadership & Ethics</h5>
                                <p>Daily drills & character-building tasks.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-6 wow fadeInUp" data-wow-delay="0.4s">
                            <div class="media-style4">
                                <div class="media-icon"><img src="assets/img/icon/training-icon-1-4.svg" alt=""></div>
                                <h5 class="media-title">🏃 Fitness & Sports</h5>
                                <p>Physical training to boost stamina & discipline.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="position-relative">
                        <form action="#" class="form-style2">
                            <div class="form-inner">
                                <h3 class="form-title h5">Enroll Now <span class="text-theme">Join 1000+ successful cadets.</span> Don’t miss out,Seats are limited – apply today!</h3>
                                <div class="row">
                                    <div class="col-md-6 col-xl-12">
                                        <div class="form-group">
                                            <input type="text" name="name" id="name" placeholder="Full Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-12">
                                        <div class="form-group">
                                            <input type="text" name="email" id="email" placeholder="Email Address">
                                        </div>
                                    </div>
                             
                                    <div class="col-md-6 col-xl-12">
                                        <div class="form-group">
                                            <input type="text" name="email" id="phone" placeholder="Phone No">
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="button" class="vs-btn">Apply Today</button>
                                        <a class="form-link" href="about.php">Frequently Asked Questions</a>
                                    </div>
                                </div>
                            </div>
                            <div class="vs-circle color2"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--==============================






    Work Process Area 
    ==============================-->
  

   
      

    <!-- ============================== -->

    <section class="space-top space-extra-bottom">
        <div class="container ">
            <div class="title-area text-center">
                <div class="sec-icon">
                    <div class="vs-circle"></div>
                </div>
                <span class="sec-subtitle">YOUR JOURNEY STARTS HERE</span>
                <h2 class="sec-title h1">How it works</h2>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6 col-lg process-inner1 order-2 order-lg-1">
                    <div class="process-style1 px-3">
                        <span class="process-number">1</span>
                        <div class="process-content ">
                            <h3 class="process-title">Sign Up</h3>
                            <p class="process-text">Create your account in minutes with basic info.</p>
                        </div>
                    </div>
                    <div class="process-style1 px-3">
                        <span class="process-number">2</span>
                        <div class="process-content">
                            <h3 class="process-title">Admin Verification</h3>
                            <p class="process-text">Our team will review your details and approve your access</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-1 order-lg-2 mb-30 mb-md-5 mb-lg-0">
                    <div class="img-box1 style2">
                        <div class="vs-circle">
                            <div class="mega-hover">
                                <img src="assets/img/about/111.png" alt="banner">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg process-inner2 order-3">
                    <div class="process-style1 px-3">
                        <span class="process-number">3</span>
                        <div class="process-content">
                            <h3 class="process-title">Fill Admission Form & Pay Fee</h3>
                            <p class="process-text">Complete the admission form and securely submit the fee.</p>
                        </div>
                    </div>
                    <div class="process-style1 px-3">
                        <span class="process-number">4</span>
                        <div class="process-content">
                            <h3 class="process-title">Start Your Training
                            </h3>
                            <p class="process-text">Begin your classes and training right away!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--==============================
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
               <h2 class="sec-title h1">Hear from Our Cadets</h2>
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
    FAQ Area
    ==============================-->
    <section class=" space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-50">
                <div class="col-lg-7 col-xl-8 mb-40 mb-lg-0">
                    <div class="title-area wow fadeInUp text-center text-lg-start" data-wow-delay="0.1s">
                        <span class="sec-subtitle">FREQUENTLY ASKED QUESTIONS</span>
                        <h2 class="sec-title h1">Academic Faq's</h2>
                    </div>
                    <div class="accordion-style1 wow fadeInUp" data-wow-delay="0.2s">
    <div class="accordion" id="faqVersion1">
        <?php
        include 'backend/Database/config.php';

        $query = "SELECT * FROM academic_faqs ORDER BY id ASC";
        $result = mysqli_query($conn, $query);
        $index = 0;

        while ($row = mysqli_fetch_assoc($result)):
            $id = $row['id'];
            $title = htmlspecialchars($row['title']);
            $desc = nl2br(htmlspecialchars($row['description']));
            $collapsed = ($index !== 0) ? 'collapsed' : '';
            $show = ($index === 0) ? 'show' : '';
            $ariaExpanded = ($index === 0) ? 'true' : 'false';
        ?>
        <div class="accordion-item <?= $show ? 'active' : '' ?>">
            <div class="accordion-header" id="heading<?= $id ?>">
                <button class="accordion-button <?= $collapsed ?>" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse<?= $id ?>"
                        aria-expanded="<?= $ariaExpanded ?>"
                        aria-controls="collapse<?= $id ?>">
                    <?= $title ?>
                </button>
            </div>
            <div id="collapse<?= $id ?>" class="accordion-collapse collapse <?= $show ?>"
                 aria-labelledby="heading<?= $id ?>" data-bs-parent="#faqVersion1">
                <div class="accordion-body">
                    <p><?= $desc ?></p>
                </div>
            </div>
        </div>
        <?php
        $index++;
        endwhile;
        ?>
    </div>

    <span class="support-link px-4 text-start">
        Have more questions? Check our <a href="contact.php">Help center</a> or contact our <a href="contact.php">support team</a>
    </span>
</div>

                </div>
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
            </div>
        </div>
    </section> 
    <!--==============================
      Brand Area
    ==============================-->







 
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
<script>
  $(document).ready(function(){
    $('.hero-bg-slider').slick({
      autoplay: true,
      autoplaySpeed: 4000,
      arrows: false,
      dots: false,
      fade: true,
      speed: 1000,
      infinite: true
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-bg");
    let index = 0;

    function showSlide(i) {
      slides.forEach(slide => slide.classList.remove("active"));
      slides[i].classList.add("active");
    }

    if (slides.length > 1) {
      showSlide(index);
      setInterval(() => {
        index = (index + 1) % slides.length;
        showSlide(index);
      }, 4000); // change every 4 seconds
    }
  });
</script>



</body>

</html>