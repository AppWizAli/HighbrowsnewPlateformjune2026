<!--==============================
    Mobile Menu
============================== -->
<div class="vs-menu-wrapper">
    <div class="vs-menu-area text-center">
        <button class="vs-menu-toggle"><i class="fas fa-times"></i></button>
        <div class="mobile-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/mylogo.png') }}" alt="Educino">
            </a>
        </div>
        <div class="vs-mobile-menu">
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/about') }}">About Us</a></li>
                <li class="menu-item-has-children">
                    <a href="#">Academic</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/lifestyle') }}">LifeStyle</a></li>
                        <li><a href="{{ url('/blog') }}">Blogs</a></li>
                        <li><a href="{{ url('/academic-program') }}">Academic Program</a></li>
                        <li><a href="{{ url('/program-details') }}">Program Details</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="{{ url('/admission-form') }}">Admission</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/admission') }}">Overview</a></li>
                        <li><a href="{{ url('/apply-now') }}">Apply Now</a></li>
                        <li><a href="{{ url('/admission-process') }}">Admission Process</a></li>
                        <li><a href="{{ url('/fees-structure') }}">Fees Structure</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
            </ul>
        </div>
    </div>
</div>

<!--==============================
    Header Area
==============================-->
<header class="vs-header header-layout2" style="background-color: #2c6b43; padding: 10px 0; height: 90px; position: fixed; width: 100%;">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="vs-logo d-none d-lg-block">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/mylogo.png') }}" alt="logo" style="width: 60px; height: auto;">
            </a>
        </div>
        <button class="vs-menu-toggle d-inline-block d-lg-none">
            <i class="fal fa-bars"></i>
        </button>

        <!-- Navigation -->
        <nav class="main-menu menu-style3 d-none d-lg-block flex-grow-1 main-nav">
            <ul class="d-flex justify-content-center gap-2 m-0 align-items-center ms-5">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/about') }}">About Us</a></li>
                <li class="menu-item-has-children">
                    <a href="#">Academic</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/lifestyle') }}">LifeStyle</a></li>
                        <li><a href="{{ url('/blog') }}">Blogs</a></li>
                        <li><a href="{{ url('/academic-program') }}">Academic Program</a></li>
                        <li><a href="{{ url('/program-details') }}">Program Details</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children">
                    <a href="{{ url('/admission-form') }}">Admission</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('/admission') }}">Overview</a></li>
                        <li><a href="{{ url('/apply-now') }}">Apply Now</a></li>
                        <li><a href="{{ url('/admission-process') }}">Admission Process</a></li>
                        <li><a href="{{ url('/fees-structure') }}">Fees Structure</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                <li><a href="{{ url('/contact') }}">Contact Us</a></li>
            </ul>
        </nav>

        <!-- Search and Login Button -->
        <div style="display: flex; justify-content: center; align-items: center; margin-left: 50px;">
            <button type="button" class="searchBoxTggler btn d-none d-lg-inline-block">
                <i class="far fa-search"></i>
            </button>
            <a href="{{ url('/login') }}" class="vs-btn custom-btn">
                <i class="fal fa-user"></i> Login & Register
            </a>
        </div>
    </div>
</header>
