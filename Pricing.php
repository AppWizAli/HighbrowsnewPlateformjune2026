<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HighBrows Pre-Cadet School</title>
    
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="assets/img//hero/mylogo.png" type="image/x-icon">
    <link rel="icon" href="assets/img//hero/mylogo.png" type="image/x-icon">

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
    <link rel="stylesheet" href="assets/css/pricing.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <style>
        
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


 
  <!-- PHP Include Navbar -->
  <?php include 'Includes/Navbar.php'; ?>


    <!--==============================
    Breadcumb
    ============================== -->

    <div class="breadcumb-wrapper" data-bg-src="assets/img/breadcumb/breadcumb-bg.png">
        <div class="container z-index-common">
           <div class="breadcumb-content text-center text-white px-3 px-sm-4 px-md-5">
              <h1 class="breadcumb-title  ">Pricing</h1>

            <p class="  text-white mt-5  mb-4 fs-6 fs-md-5">
        Highbrows Pre-Cadet School & Academy provides quality leadership education at competitive rates, including training, 
               <br class="d-none d-sm-block">

        uniforms, and activities to develop disciplined, exceptional cadets.
      </p>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="index.php">Home</a></li>
                        <li>Pricing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

     
    <section class="pricing-section">
    
    <div class="title-area3 text-center wow fadeInUp" data-wow-delay="0.3s">
    <span class="sec-subtitle style1 px-3">Welcome</span>
    <h2 class="sec-title fs-4 fs-md-3 fs-lg-2  mb mb-lg-2 text-center">Pricing</h2>
    <p class="fs-md w-75 text-center mx-auto" >Highbrows Pre-Cadet School & Academy offers top-tier leadership and character-building education at affordable rates, featuring comprehensive training, uniforms, and engaging activities designed to shape confident, well-disciplined cadets.</p>
  </div> 
    

      <?php
include 'backend/Database/config.php';

// Fetch all pricing plans
$result = $conn->query("SELECT * FROM pricing_manage ORDER BY id DESC");
?>

<!-- Pricing Section Start -->
<div class="pricing-container">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()):
            $features = explode("\n", trim($row['description']));
            $limitedFeatures = array_slice($features, 0, 4);
            $remainingFeatures = array_slice($features, 4);
        ?>
        <div class="pricing-box">
            <h3 class="pricing-title"><?= htmlspecialchars($row['title']) ?></h3>
            <h3 class="price">Rs<?= htmlspecialchars($row['price']) ?></h3>
            <div class="seperator"></div>
            <div class="program-features">
                <ul>
                    <?php foreach ($limitedFeatures as $feature): ?>
                        <li><?= htmlspecialchars($feature) ?></li>
                    <?php endforeach; ?>
                    <?php if (!empty($remainingFeatures)): ?>
                        <p class="features-info readMoreBtn" data-full='<?= json_encode($features) ?>'>Read More</p>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="pricing-btns">
                <a href="AdmissionFarm.php">
                    <button class="enroll">Enroll Now</button>
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted text-center">No pricing plans available.</p>
    <?php endif; ?>
</div>
<!-- Pricing Cards -->


<!-- Pricing Features Info Popup -->
<div class="pricing-info-popup" style="display: none;">
    <div class="popup-inner">
        <div class="popup-header">
            <div class="popup-close-btn">
                <button class="closePopupBtn">Close</button>
            </div>
            <h2>Program Features</h2>
        </div>
        <div class="features-desc">
            <ul id="fullFeaturesList">
                <!-- Filled by JS -->
            </ul>
        </div>
    </div>
</div>

<!-- JS Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const popup = document.querySelector(".pricing-info-popup");
    const closeBtn = document.querySelector(".closePopupBtn");
    const featuresList = document.getElementById("fullFeaturesList");

    // Trigger popup on Read More click
    document.querySelectorAll(".readMoreBtn").forEach(button => {
        button.addEventListener("click", () => {
            const features = JSON.parse(button.getAttribute("data-full"));
            featuresList.innerHTML = "";

            features.forEach(text => {
                const li = document.createElement("li");
                li.textContent = text;
                featuresList.appendChild(li);
            });

            popup.style.display = "flex";
        });
    });

    // Close popup
    closeBtn.addEventListener("click", () => {
        popup.style.display = "none";
    });

    // Close popup when clicking outside inner box
    popup.addEventListener("click", e => {
        if (e.target === popup) popup.style.display = "none";
    });
});
</script>


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

<!-- popup show and hide code -->
    <script>
        const openBtns = document.getElementsByClassName("features-info");
        const closeBtn = document.getElementsByClassName("closePopupBtn")[0];
        const popup = document.getElementsByClassName("pricing-info-popup")[0];

        // Attach click to each open button
        for (let i = 0; i < openBtns.length; i++) {
            openBtns[i].addEventListener("click", () => {
            popup.style.display = "flex";
            });
        }

        // Close button
        closeBtn.addEventListener("click", () => {
            popup.style.display = "none";
        });

        // Close when clicking outside the inner popup
        window.addEventListener("click", (e) => {
            if (e.target === popup) {
            popup.style.display = "none";
            }
        });
    </script>






</body>
</html>