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
    <div class="breadcumb-content text-center text-white">
      <h1 class="breadcumb-title">Our Blog</h1>
   <p class="mt-2 text-white mt-5">
  Discover helpful resources, learning tips, and updates from our academic community.<br>
  Stay informed about school activities, educational 
</p>

      <div class="breadcumb-menu-wrap mt-3">
        <ul class="breadcumb-menu d-inline-flex gap-2 justify-content-center">
          <li><a href="index.php">Home</a></li>
          <li>Our Blog</li>
        </ul>
      </div>
    </div>
  </div>
</div>

    <!--==============================
      Blog Area
    ==============================-->
    <section class="vs-blog-wrapper space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-40">
      <?php
include 'backend/Database/config.php';

// Pagination setup
$limit = 4;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Check if category is selected
$categoryFilter = isset($_GET['category']) ? trim($_GET['category']) : '';

// Count total blogs based on category
$countSql = "SELECT COUNT(*) AS total FROM blogs_content";
if (!empty($categoryFilter)) {
    $escapedCategory = $conn->real_escape_string($categoryFilter);
    $countSql .= " WHERE category_name = '$escapedCategory'";
}
$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch blogs based on category
$sql = "SELECT id, title, description, main_image, created_at 
        FROM blogs_content";
if (!empty($categoryFilter)) {
    $sql .= " WHERE category_name = '$escapedCategory'";
}
$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>

<!-- ✅ Blog Cards Layout (Vertical 1-by-1) -->
<div class="col-lg-8">
  <div class="row">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $title = htmlspecialchars($row['title']);
            $desc = htmlspecialchars(mb_strimwidth(strip_tags($row['description']), 0, 150, '...'));
            $image = 'backend/uploads/blogs/' . htmlspecialchars($row['main_image']);
            $created = date("d M, Y", strtotime($row['created_at']));
            ?>
            <div class="col-12 mb-4">
              <div class="vs-blog blog-single">
                <div class="blog-img mega-hover">
                  <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" style="height: 250px; object-fit: cover; width: 100%;">
                </div>
                <div class="blog-content">
                  <h2 class="blog-title">
                    <a href="blog-details.php?id=<?php echo $id; ?>"><?php echo $title; ?></a>
                  </h2>
                  <div class="blog-meta">
                    <a href="#"><i class="far fa-user"></i> By Admin</a>
                    <a href="#"><i class="far fa-calendar"></i> <?php echo $created; ?></a>
                  </div>
                  <p><?php echo $desc; ?></p>
                  <a href="blog-details.php?id=<?php echo $id; ?>" class="vs-btn style3">
                    <i class="far fa-angle-right"></i> Read More
                  </a>
                </div>
              </div>
            </div>
        <?php }
    } else {
        echo "<p>No blogs found for this category.</p>";
    }
    ?>
  </div>

<?php if ($totalPages >= 2): ?>
  <div class="vs-pagination">
    <ul>
      <!-- Previous -->
      <li class="prev">
        <a href="?<?php echo http_build_query(['page' => max(1, $page - 1), 'category' => $categoryFilter ?? null]); ?>">Previous</a>
      </li>

      <?php
      $maxPagesToShow = 3;
      $start = max(1, $page - 1);
      $end = min($totalPages, $start + $maxPagesToShow - 1);

      if ($end - $start < $maxPagesToShow - 1) {
        $start = max(1, $end - $maxPagesToShow + 1);
      }

      for ($i = $start; $i <= $end; $i++): ?>
        <li>
          <a href="?<?php echo http_build_query(['page' => $i, 'category' => $categoryFilter ?? null]); ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>

      <?php if ($end < $totalPages): ?>
        <li><a href="#">.....</a></li>
        <li>
          <a href="?<?php echo http_build_query(['page' => $totalPages, 'category' => $categoryFilter ?? null]); ?>">
            <?php echo $totalPages; ?>
          </a>
        </li>
      <?php endif; ?>

      <!-- Next -->
      <li class="next">
        <a href="?<?php echo http_build_query(['page' => min($totalPages, $page + 1), 'category' => $categoryFilter ?? null]); ?>">Next</a>
      </li>
    </ul>
  </div>
<?php endif; ?>

</div>


                <div class="col-lg-4">
                    <aside class="sidebar-area">
                        <div class="widget widget_search  ">
                            <h3 class="widget_title">Search</h3>
                            <form class="search-form">
                                <input type="text" placeholder="Search...">
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