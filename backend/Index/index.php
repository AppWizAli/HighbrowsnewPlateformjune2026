<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Includes/sidebar.css"> 

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: block;
        }

        @media (min-width: 768px) {
            body {
                display: flex;
                flex-direction: row;
            }
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            color: #fff;
        }

        .main {
            flex: 1;
            padding: 20px;
            background-color: #f1f1f1;
            min-height: 100vh;
            margin-top: 100px;
        }

        .dashboard-section-shadow {
            background: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        .data-box {
    transition: all 0.3s ease;
    cursor: pointer;
}

.data-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    opacity: 0.95;
}

.data-box i {
    transition: transform 0.3s ease;
}

.data-box:hover i {
    transform: scale(1.2);
}

        .dashboard-section-shadow {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .top-header .h4 {
            color: #212529;
        }
        .breadcrumb-item a {
            color: #28a745 !important;
        }
     

    </style>
</head>
<body>
<?php include '../Includes/sidebar.php'; ?>



<div class="main">
    <section class="container-fluid flex-grow-1 d-flex flex-column">
      <header class="top-header p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="d-flex align-items-start">
            <i class="fas fa-cogs fa-2x text-primary me-3 mt-1"></i> <!-- Changed icon to settings -->
            <div>
                <h1 class="h4 fw-bold text-dark mb-1">Dashboard Management</h1> <!-- Updated title -->
                <small class="text-muted">Manage all your admin modules and monitor system activities efficiently.</small> <!-- Updated subtitle -->
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none fw-semibold">Home</a></li>
                <li class="breadcrumb-item active fw-semibold" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
</header>


        <!-- 8 Colorful Cards Section -->
      <div class="row g-4 mb-5">
<?php
include '../Database/config.php';

// Dashboard sections
$sections = [
    [
        'title' => 'Blogs',
        'query' => "SELECT COUNT(id) as count FROM blogs_content",
        'icon' => 'fa-blog',
        'bg' => '#ff7f50',
        'link' => '/backend/Index/BlogsContent/view_blogs.php',
    ],
    [
        'title' => 'Reviews',
        'query' => "SELECT COUNT(id) as count FROM review_content",
        'icon' => 'fa-star',
        'bg' => '#20c997',
        'link' => '/backend/Index/ReviewsContent/show_reviews.php',
    ],
    [
        'title' => 'Pricing',
        'query' => "SELECT COUNT(id) as count FROM pricing_manage",
        'icon' => 'fa-dollar-sign',
        'bg' => '#ff6b81',
        'link' => '/backend/PricingManage/show_pricing.php',
    ],
    [
        'title' => 'Proud Moments',
        'query' => "SELECT COUNT(id) as count FROM proud_moment",
        'icon' => 'fa-image',
        'bg' => '#17a2b8',
        'link' => '/backend/Index/GalleryProudMoment/show_proud.php',
    ],
    [
        'title' => 'Admin Users',
        'query' => "SELECT COUNT(id) as count FROM admin",
        'icon' => 'fa-user-shield',
        'bg' => '#6f42c1',
        'link' => '/backend/LoginReg/Showadmin.php',
    ],
    [
        'title' => 'Explorers',
        'query' => "SELECT COUNT(id) as count FROM explore_highbrows",
        'icon' => 'fa-compass',
        'bg' => '#e83e8c',
        'link' => '/backend/ExploreHighbrows/show_explore.php',
    ],
    [
        'title' => 'Our Services',
        'query' => "SELECT COUNT(id) as count FROM our_services",
        'icon' => 'fa-concierge-bell',
        'bg' => '#0d6efd',
        'link' => '/backend/Index/OurSevices/show_services.php',
    ],
    [
        'title' => 'Contact Users',
        'query' => "SELECT COUNT(id) as count FROM contact_users", // ✅ Make sure table name exists
        'icon' => 'fa-envelope-open',
        'bg' => '#6c757d',
        'link' => '/backend/ContactUs/view_contacts.php',
    ]
];


// Render dashboard cards
foreach ($sections as $sec) {
    $result = mysqli_query($conn, $sec['query']);

    if (!$result) {
        echo "<div class='col-md-3 col-sm-6 text-danger'>SQL Error in <strong>{$sec['title']}</strong>: " . mysqli_error($conn) . "</div>";
        continue;
    }

    $row = mysqli_fetch_assoc($result);
    $displayCount = $row['count'];

    echo "
    <div class='col-md-3 col-sm-6 mb-4'>
        <a href='{$sec['link']}' class='text-decoration-none'>
            <div class='data-box text-white p-4 rounded-4 shadow-sm' style='background-color: {$sec['bg']}'>
                <div class='d-flex justify-content-between align-items-center mb-2'>
                    <h4 class='mb-0'>{$displayCount}</h4>
                    <i class='fas {$sec['icon']} fs-3'></i>
                </div>
                <p class='mb-1 fw-semibold'>{$sec['title']}</p>
                <small><i class='far fa-clock me-1'></i> Updated now</small>
            </div>
        </a>
    </div>";
}
?>


</div>


   
    </section>
</div>


<div class="sidebar-overlay d-none" id="sidebarOverlay"></div> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Includes/sidebar.js"></script> 


</body>
</html>
