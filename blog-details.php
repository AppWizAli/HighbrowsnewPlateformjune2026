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
    
   
 <?php
include 'backend/Database/config.php';

$blogTitle = "Blog Not Found";
$blogDescription = "";
$bgImage = "assets/img/breadcumb/breadcumb-bg.png"; // default image

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT title, description, main_image FROM blogs_content WHERE id = $id LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $blogTitle = htmlspecialchars($row['title']);
        $blogDescription = nl2br(htmlspecialchars($row['description']));
        $bgImage = htmlspecialchars($row['main_image']) ?: $bgImage;
    }
}
?>

<div class="breadcumb-wrapper" data-bg-src="<?php echo $bgImage; ?>">
  <div class="container z-index-common">
    <div class="breadcumb-content text-center text-white">
      <h1 class="breadcumb-title"><?php echo $blogTitle; ?></h1>
      
      <?php if ($blogDescription): ?>
        <p class="mt-2 text-white mt-5">
          <?php echo $blogDescription; ?>
        </p>
      <?php else: ?>
        <p class="mt-2 text-white mt-5">
          Dive into in-depth insights, student journeys, and educational highlights.<br>
          Explore what’s happening across our school.
        </p>
      <?php endif; ?>

      <div class="breadcumb-menu-wrap mt-3">
        <ul class="breadcumb-menu d-inline-flex gap-2 justify-content-center">
          <li><a href="index.php">Home</a></li>
          <li>Blog Details</li>
        </ul>
      </div>
    </div>
  </div>
</div>

    <!--==============================
      Blog Area
    ==============================-->
    <section class="vs-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-40">
             <?php
include 'backend/Database/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo '<p>Invalid blog ID.</p>';
  exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM blogs_content WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
  echo '<p>Blog not found.</p>';
  exit;
}

$blog = $result->fetch_assoc();

// Define path prefix
$uploadPath = "backend/uploads/blogs/";
?>

<div class="col-lg-8">
  <div class="vs-blog blog-single">
    <div class="blog-img">
      <img src="<?php echo $uploadPath . htmlspecialchars($blog['main_image']); ?>" alt="Blog Image">
    </div>
    <div class="blog-content">
      <h2 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
      <p><?php echo nl2br(htmlspecialchars($blog['description'])); ?></p>
      <p><?php echo nl2br($blog['blog_content']); ?></p>

      <blockquote class="vs-quote">
        <p>I had the opportunity to meet with the dynamic & distinguished faculties track course.</p>
        <span>Jessica Moniqa</span>
      </blockquote>

      <div class="row">
        <?php if (!empty($blog['side_image'])): ?>
        <div class="col-sm-6">
          <div class="blog-inner-img">
            <img class="w-100" src="<?php echo $uploadPath . htmlspecialchars($blog['side_image']); ?>" alt="Side Image 1">
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($blog['side_image2'])): ?>
        <div class="col-sm-6">
          <div class="blog-inner-img">
            <img class="w-100" src="<?php echo $uploadPath . htmlspecialchars($blog['side_image2']); ?>" alt="Side Image 2">
          </div>
        </div>
        <?php endif; ?>
      </div>

   
      

   <?php
include 'backend/Database/config.php';
$currentId = intval($_GET['id'] ?? 0); // Current blog ID

// Fetch Previous Blog
$prevQuery = "SELECT id FROM blogs_content WHERE id < ? ORDER BY id DESC LIMIT 1";
$prevStmt = $conn->prepare($prevQuery);
$prevStmt->bind_param("i", $currentId);
$prevStmt->execute();
$prevResult = $prevStmt->get_result();
$prevBlog = $prevResult->fetch_assoc();
$prevStmt->close();

// Fetch Next Blog
$nextQuery = "SELECT id FROM blogs_content WHERE id > ? ORDER BY id ASC LIMIT 1";
$nextStmt = $conn->prepare($nextQuery);
$nextStmt->bind_param("i", $currentId);
$nextStmt->execute();
$nextResult = $nextStmt->get_result();
$nextBlog = $nextResult->fetch_assoc();
$nextStmt->close();
?>

<!-- Pagination HTML -->
<div class="post-pagination">
  <div class="row justify-content-between align-items-md-center">
    <div class="col">
      <?php if ($prevBlog): ?>
        <div class="post-pagi-box prev">
          <a href="blog-details.php?id=<?= $prevBlog['id'] ?>"><i class="fas fa-chevron-left"></i></a>
          <h4 class="pagi-title"><a href="blog-details.php?id=<?= $prevBlog['id'] ?>">Previous Blog</a></h4>
        </div>
      <?php endif; ?>
    </div>
    <div class="col text-end">
      <?php if ($nextBlog): ?>
        <div class="post-pagi-box next">
          <a href="blog-details.php?id=<?= $nextBlog['id'] ?>"><i class="fas fa-chevron-right"></i></a>
          <h4 class="pagi-title"><a href="blog-details.php?id=<?= $nextBlog['id'] ?>">Next Blog</a></h4>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>



    </div>
  </div>
</div>

                <div class="col-lg-4">
                    <aside class="sidebar-area">
                        <div class="widget widget_search  ">
                            <h3 class="widget_title">Search</h3>
                            <form class="search-form">
                                <input type="text" placeholder="Search Keyword">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                        </div>
                      <div class="widget widget_categories">
  <h3 class="widget_title">Categories</h3>
  <ul>
    <?php
    include 'backend/Database/config.php'; // adjust path if needed

    $sql = "SELECT category_name, COUNT(*) as total 
            FROM blogs_content 
            WHERE category_name IS NOT NULL AND category_name != ''
            GROUP BY category_name
            ORDER BY total DESC";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $category = htmlspecialchars($row['category_name']);
            $count = $row['total'];
            echo '<li><a href="blog.php?category=' . urlencode($category) . '">' . $category . '</a> <span>' . $count . '</span></li>';
        }
    } else {
        echo '<li><em>No categories found.</em></li>';
    }
    ?>
  </ul>
</div>

                   <div class="widget">
  <h3 class="widget_title">Recent News</h3>
  <div class="recent-post-wrap">
    <?php
    include 'backend/Database/config.php';

    $sql = "SELECT id, title, main_image, created_at 
            FROM blogs_content 
            ORDER BY created_at DESC 
            LIMIT 3";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // ✅ Ensure image path is correct
            $img = !empty($row['main_image']) 
                   ? 'backend/uploads/blogs/' . basename($row['main_image']) 
                   : 'assets/img/default.jpg';

            $title = htmlspecialchars($row['title']);
            $date = date("d F, Y", strtotime($row['created_at']));
            $id = $row['id'];

            echo '
            <div class="recent-post">
              <div class="media-img"><img src="' . $img . '" alt="Blog Image"></div>
              <div class="media-body">
                <h4 class="post-title">
                  <a class="text-inherit" href="blog-details.php?id=' . $id . '">' . $title . '</a>
                </h4>
                <div class="recent-post-meta">
                  <a href="blog-details.php?id=' . $id . '">' . $date . '</a>
                </div>
              </div>
            </div>';
        }
    } else {
        echo '<p>No recent posts found.</p>';
    }
    ?>
  </div>
</div>


                   <div class="widget">
  <h4 class="widget_title">Gallery Photos</h4>
  <div class="sidebar-gallery">
    <?php
    include 'backend/Database/config.php';

    $sql = "SELECT main_image, side_image, side_image2 FROM blogs_content ORDER BY created_at DESC LIMIT 6";
    $result = $conn->query($sql);

    $galleryImages = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach (['main_image', 'side_image', 'side_image2'] as $field) {
                if (!empty($row[$field])) {
                    // ✅ Prepend the correct folder path
                    $galleryImages[] = 'backend/uploads/blogs/' . basename($row[$field]);
                }
            }
        }
    }

    // Show only 6 images max
    $galleryImages = array_slice($galleryImages, 0, 6);

    foreach ($galleryImages as $img) {
        echo '
        <div class="gallery-thumb">
          <img src="' . $img . '" alt="Gallery Image" class="w-100">
          <a href="' . $img . '" class="popup-image gal-btn"><i class="fal fa-plus"></i></a>
        </div>';
    }

    if (empty($galleryImages)) {
        echo '<p>No gallery images found.</p>';
    }
    ?>
  </div>
</div>

                        <div class="widget widget_meta   ">
                            <h3 class="widget_title">Meta Links</h3>
                            <ul>
                                <li><a href="#">Log in</a></li>
                                <li><a href="#">Entries RSS</a></li>
                                <li><a href="#">Comments RSS</a></li>
                                <li><a href="#">WordPress.org</a></li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!--==============================
    CTA Area
    ==============================-->
    <section class="space-extra" data-bg-src="assets/img/bg/blog-single-divider-bg-1-1.jpg">
        <div class="container">
            <div class="row justify-content-between text-center text-lg-start">
                <div class="col-lg-6 mb-40 mb-lg-0">
                    <h2 class="mt-n2 h2 mb-3">Future Learn’s Purpose is to transform access to education.</h2>
                    <p class=" mb-4 pb-2 fs-md col-xl-11">Sign up to our newsletter and we'll send fresh new courses and special offers direct to your inbox, once a week.</p>
                    <a href="contact.php" class="vs-btn style2"><i class="far fa-angle-right"></i>Get a Quote</a>
                </div>
                <div class="col-auto d-none d-lg-block">
                    <div class="sec-line2"></div>
                </div>
                <div class="col-lg-auto">
                    <h6 class="mt-n1">Academic Leadership Team</h6>
                    <div class="mini-avater">
                        <a href="team-details.php"><img src="assets/img/team/team-s-1-1.png" alt="avater"></a>
                        <a href="team-details.php"><img src="assets/img/team/team-s-1-2.png" alt="avater"></a>
                        <a href="team-details.php"><img src="assets/img/team/team-s-1-3.png" alt="avater"></a>
                        <a href="team-details.php"><img src="assets/img/team/team-s-1-4.png" alt="avater"></a>
                    </div>
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