<?php
// PHP security check: If this file is accessed directly, redirect to login page.
// This path is relative to sidebar.php (highbrowsnewwebsite//backend/includes/sidebar.php)
// so ../ goes to highbrowsnewwebsite//backend/, then LoginReg/login.php is correct.
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header("Location: ../LoginReg/login.php");
    exit();
}
?>

<div id="sidebarOverlay" class="sidebar-overlay d-none"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header text-center py-4">
        <img src="/backend/Includes/images/mylogo.png" alt="Profile"
            class="rounded-circle mb-3 shadow"
            style="border: 3px solid #007bff; width: 90px; height: 90px;">
        <h2 class="fw-bold" style="font-size: 20px;">Highbrows</h2>
        <hr class="w-75 m-auto" style="border: 2px solid white;">
    </div>

    <div class="sidebar-nav-container">
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center"
                    href="/backend/Index/index.php?page=dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <!-- Blogs Heading -->
            <h4><i class="bi bi-journals"></i> Blogs</h4>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#blogsMenu" data-bs-toggle="collapse">
                    <i class="bi bi-journals me-2"></i> <span class="sidebar-text">Blogs</span>
                </a>
                <div class="collapse" id="blogsMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/BlogsContent/add_blog.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Blogs</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/BlogsContent/view_blogs.php"><i
                                    class="bi bi-card-list me-2 dropdown-icon"></i> Show Blogs</a></li>
                    </ul>
                </div>
            </li>
<li class="nav-item">
    <a class="nav-link dropdown-toggle" href="#pricingMenu" data-bs-toggle="collapse">
        <i class="bi bi-currency-dollar me-2"></i> <span class="sidebar-text">Pricing</span>
    </a>
    <div class="collapse" id="pricingMenu">
        <ul class="nav flex-column ms-4">
            <li class="nav-item">
                <a class="nav-link" href="/backend/PricingManage/add_pricing.php">
                    <i class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Pricing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/backend/PricingManage/show_pricing.php">
                    <i class="bi bi-card-list me-2 dropdown-icon"></i> Show Pricing
                </a>
            </li>
        </ul>
    </div>
</li>
<li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#aboutMenu" data-bs-toggle="collapse">
    <i class="bi bi-file-earmark-person me-2"></i> <span class="sidebar-text">About Areas</span>
  </a>
  <div class="collapse" id="aboutMenu">
    <ul class="nav flex-column ms-4">
      <li class="nav-item">
        <a class="nav-link" href="/backend/Index/AboutSection/show_about.php">
          <i class="bi bi-eye me-2 dropdown-icon"></i> View  Image
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/backend/Index/AboutSection/add_about.php">
          <i class="bi bi-plus-circle me-2 dropdown-icon"></i> Add  Image
        </a>
      </li>
    </ul>
  </div>
</li>
<!-- ✅ Lifestyle Section Dropdown -->
<li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#lifestyleMenu" data-bs-toggle="collapse">
    <i class="bi bi-heart-pulse me-2"></i> <span class="sidebar-text">Lifestyle Section</span>
  </a>
  <div class="collapse" id="lifestyleMenu">
    <ul class="nav flex-column ms-4">
      <li class="nav-item">
        <a class="nav-link" href="/backend/Index/LifeStyle/show_lifestyle.php">
          <i class="bi bi-eye me-2 dropdown-icon"></i> View Entries
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/backend/Index/LifeStyle/add_lifestyle.php">
          <i class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Entry
        </a>
      </li>
    </ul>
  </div>
</li>
            <!-- Galleries Heading -->
            <h4><i class="bi bi-images"></i> Galleries</h4>

            <!-- Hero Area -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#heroAreaMenu" data-bs-toggle="collapse">
                    <i class="bi bi-images me-2"></i> <span class="sidebar-text">Hero Areas</span>
                </a>
                <div class="collapse" id="heroAreaMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/HeroAreas/insert_heroareas.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Images</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/HeroAreas/show_heroareas.php"><i
                                    class="bi bi-card-image me-2 dropdown-icon"></i> Show Images</a></li>
                    </ul>
                </div>
            </li>

            <!-- Gallery -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#galleryMenu" data-bs-toggle="collapse">
                    <i class="bi bi-images me-2"></i> <span class="sidebar-text">Gallery</span>
                </a>
                <div class="collapse" id="galleryMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/GalleryProudMoment/show_proud.php"><i
                                    class="bi bi-eye me-2 dropdown-icon"></i> View Images</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/GalleryProudMoment/insert_proud.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Image</a></li>
                    </ul>
                </div>
            </li>

            <!-- Cadet Gallery -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#cadetGalleryMenu" data-bs-toggle="collapse">
                    <i class="bi bi-person-badge me-2"></i> <span class="sidebar-text">Cadet Gallery</span>
                </a>
                <div class="collapse" id="cadetGalleryMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/CadetMoments/view_cadet_moments.php"><i
                                    class="bi bi-eye me-2 dropdown-icon"></i> Show Images</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/CadetMoments/add_cadet_moment.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Image</a></li>
                    </ul>
                </div>
            </li>

            <!-- Military Gallery -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#militaryGalleryMenu" data-bs-toggle="collapse">
                    <i class="bi bi-person-badge me-2"></i> <span class="sidebar-text">Military Gallery</span>
                </a>
                <div class="collapse" id="militaryGalleryMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/MilitaryMoments/show_military_moments.php"><i
                                    class="bi bi-eye me-2 dropdown-icon"></i> Show Images</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/MilitaryMoments/add_military_moments.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Image</a></li>
                    </ul>
                </div>
            </li>

            <!-- Reviews Heading -->
            <h4><i class="bi bi-chat-right-text"></i> All Reviews</h4>

            <!-- Admin Reviews -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#reviewMenuAdmin" data-bs-toggle="collapse">
                    <i class="bi bi-chat-right-text me-2"></i> <span class="sidebar-text">Reviews</span>
                </a>
                <div class="collapse" id="reviewMenuAdmin">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/ReviewsContent/add_review.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Review</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/ReviewsContent/show_reviews.php"><i
                                    class="bi bi-card-list me-2 dropdown-icon"></i> Show Reviews</a></li>
                    </ul>
                </div>
            </li>

            <!-- User Reviews -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#reviewMenu" data-bs-toggle="collapse">
                    <i class="bi bi-chat-right-text me-2"></i> <span class="sidebar-text">User Reviews</span>
                </a>
                <div class="collapse" id="reviewMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/UserReviews/add_userreviews.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Review</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/UserReviews/show_userreviews.php"><i
                                    class="bi bi-card-list me-2 dropdown-icon"></i> Show Reviews</a></li>
                    </ul>
                </div>
            </li>

<li class="nav-item">
    <a class="nav-link dropdown-toggle" href="#exploreMenu" data-bs-toggle="collapse">
        <i class="fas fa-compass me-2"></i> <span class="sidebar-text">Pre-Cadet-School</span>
    </a>
    <div class="collapse" id="exploreMenu">
        <ul class="nav flex-column ms-4">
            <li class="nav-item">
                <a class="nav-link" href="/backend/ExploreHighbrows/add_explore.php">
                    <i class="fas fa-plus-circle me-2 dropdown-icon"></i> Add Pre-Cadet
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/backend/ExploreHighbrows/show_explore.php">
                    <i class="fas fa-list-alt me-2 dropdown-icon"></i> Show Pre-Cadet
                </a>
            </li>
        </ul>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link dropdown-toggle" href="#exploreMenu2" data-bs-toggle="collapse" aria-expanded="false" aria-controls="exploreMenu2">
        <i class="fas fa-compass me-2"></i>
        <span class="sidebar-text">Force-Academy</span>
    </a>
    <div class="collapse" id="exploreMenu2">
        <ul class="nav flex-column ms-4">
            <li class="nav-item">
                <a class="nav-link" href="/backend/ExploreHighbrows2/add_explore2.php">
                    <i class="fas fa-plus-circle me-2 dropdown-icon"></i> Add Force-Acad.
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/backend/ExploreHighbrows2/show_explore2.php">
                    <i class="fas fa-list-alt me-2 dropdown-icon"></i> Show Force-Acad.
                </a>
            </li>
        </ul>
    </div>
</li>


            <!-- Our Services Heading -->
            <h4><i class="fas fa-hand-holding-usd"></i> Our Services</h4>

            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#ourServicesMenu" data-bs-toggle="collapse">
                    <i class="fas fa-hand-holding-usd me-2"></i> <span class="sidebar-text">Our Services</span>
                </a>
                <div class="collapse" id="ourServicesMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/OurSevices/show_services.php"><i
                                    class="bi bi-eye me-2 dropdown-icon"></i> Show Services</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/OurSevices/add_service.php"><i
                                    class="bi bi-plus-circle me-2 dropdown-icon"></i> Add Service</a></li>
                    </ul>
                </div>
            </li>

           

            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#academicFaqsMenu" data-bs-toggle="collapse">
                    <i class="fas fa-graduation-cap me-2"></i> <span class="sidebar-text">Academic FAQs</span>
                </a>
                <div class="collapse" id="academicFaqsMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/AcademicFaqs/add_academicfaqs.php"><i
                                    class="fas fa-plus-circle me-2 dropdown-icon"></i> Add FAQ</a></li>
                        <li class="nav-item"><a class="nav-link"
                                href="/backend/Index/AcademicFaqs/show_academicfaqs.php"><i
                                    class="fas fa-list-alt me-2 dropdown-icon"></i> Show FAQs</a></li>
                    </ul>
                </div>
            </li>

    

            <!-- Authentication Heading -->
            <h4><i class="fas fa-user-shield"></i>Admin</h4>

            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#authenticationMenu" data-bs-toggle="collapse">
                    <i class="fas fa-user-shield me-2"></i> <span class="sidebar-text">Admin</span>
                </a>
                <div class="collapse" id="authenticationMenu">
                    <ul class="nav flex-column ms-4">
                        <li class="nav-item">
    <a class="nav-link" href="/backend/ContactUs/view_contacts.php">
        <i class="fas fa-envelope me-2"></i> Contact Us
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="/backend/LoginReg/Signup.php">
        <i class="fas fa-user-plus me-2 dropdown-icon"></i> Signup
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="/backend/LoginReg/Showadmin.php">
        <i class="fas fa-users me-2 dropdown-icon"></i> Admins
    </a>
</li>

<!--                         
                                    <li class="nav-item"><a class="nav-link"
                                href="/backend/LoginSignupusers/show_login_users.php"><i
                                    class="fas fa-user-check me-2 dropdown-icon"></i> Login Accounts</a></li> -->
                       <li class="nav-item"><a class="nav-link"
                                href="/backend/LoginReg/Login-cruds/logout.php"><i
                                    class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</div>

<div class="topbar d-flex align-items-center justify-content-between px-3 py-2 ">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-light me-3" id="toggleSidebar">&#9776;</button>
        <h5 class="mb-0 text-white" id="pageTitle">Dashboard</h5>
    </div>

    <div class="d-flex align-items-center">
        <div class="position-relative me-4">
            <i class="bi bi-bell-fill text-white fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3
                <span class="visually-hidden">unread messages</span>
            </span>
        </div>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/backend/Includes/images/aboutworkcircle.jpeg" alt="Profile" width="50" height="50" class="rounded-circle me-2">
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="/backend/Index.php?page=settings"><i class="fas fa-cog me-2"></i>Settings</a></li>
                <li><a class="dropdown-item" href="/backend/Index.php?page=profile"><i class="fas fa-user me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/backend/LoginReg/Login-cruds/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="content" id="content"></div>
