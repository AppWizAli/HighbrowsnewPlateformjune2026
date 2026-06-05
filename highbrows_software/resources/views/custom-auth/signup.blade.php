

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

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/img/mylogo.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/mylogo.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- All CSS Files -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Fontawesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.min.css') }}">
    <!-- Animate.css (for WOW.js animations) -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <!-- Theme Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/styles/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Login-regiration/login-reg.css') }}">
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


    <!--==============================
    Breadcumb
    ============================== -->

    <!--==============================
    Login & Register
    ==============================-->
    
<section class="space-top space-extra-bottom">
    <div class="title-area3 text-center wow fadeInUp" data-wow-delay="0.3s">
        <span class="sec-subtitle style1">Join Us Today</span>
        <h2 class="sec-title">Create Your Account</h2>
    </div>

    <div class="container">
        <div class="row gx-60 d-flex justify-content-center align-items-center">

            <!-- Left Side Image -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="img-box5 mega-hover wow fadeInUp" data-wow-delay="0.3s">
                    <div class="img-1">
                        <img class="w-100" src="{{ asset('assets/img/course/pexels-photo-5475752.jpeg') }}" 
                             alt="Signup Illustration" style="width: 100%; height: 350px; object-fit: cover;">
                    </div>
                </div>
            </div>

            <!-- Sign Up Form -->
            <div class="col-lg-6" id="signup">
                <form class="form-style4 signup p-4 wow fadeInUp needs-validation" 
                      method="POST" 
                      action="{{ route('signup') }}" 
                      data-wow-delay="0.4s"
                      data-bg-src="{{ asset('assets/img/bg/course-bg-pattern.jpg') }}" 
                      novalidate>
                    @csrf
                    <h2 class="form-title mb-4">SIGN UP</h2>

                 <!-- Username -->
<div class="form-group position-relative mb-3">
    <input type="text" autocomplete="off" name="name" id="username" 
           class="form-control ps-5" placeholder="Enter your username" 
           value="{{ old('name') }}" required>
    <i class="fas fa-user position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('name')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<!-- Contact Number -->
<div class="form-group position-relative mb-3">
    <input type="text" autocomplete="off" name="contact" id="contact" 
           class="form-control ps-5" placeholder="Enter contact number" 
           value="{{ old('contact') }}" required>
    <i class="fas fa-phone position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('contact')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<!-- Email -->
<div class="form-group position-relative mb-3">
    <input type="email" autocomplete="off" name="email" id="email" 
           class="form-control ps-5" placeholder="Enter your email" 
           value="{{ old('email') }}" required>
    <i class="fas fa-envelope position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('email')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<!-- Grade Selector -->
<div class="form-group position-relative mb-3">
    <select name="grade" id="grade" class="form-select ps-5" required>
        <option value="" disabled {{ old('grade') ? '' : 'selected' }}>Select Grade or Course</option>
        <option value="Grade 5" {{ old('grade') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
        <option value="Grade 6" {{ old('grade') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
        <option value="Grade 7" {{ old('grade') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
        <option value="Grade 8" {{ old('grade') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
        <option value="Grade 9" {{ old('grade') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
        <option value="Grade 10" {{ old('grade') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
        <option value="Grade 11" {{ old('grade') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
        <option value="Grade 12" {{ old('grade') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
        <option value="Grade Issb" {{ old('grade') == 'Grade Issb' ? 'selected' : '' }}> Grade Issb</option>
    </select>
    <i class="fas fa-graduation-cap position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('grade')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<!-- Category Selector -->
<div class="form-group position-relative mb-3">
    <select name="category" id="category" class="form-select ps-5" required>
        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select category</option>
        <option value="Online" {{ old('category') == 'Online' ? 'selected' : '' }}>Online</option>
        <option value="DayScholar" {{ old('category') == 'DayScholar' ? 'selected' : '' }}>DayScholar</option>
        <option value="Hostel" {{ old('category') == 'Hostel' ? 'selected' : '' }}>Hostel</option>
    </select>
    <i class="fas fa-list position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('category')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<!-- Password -->
<div class="form-group position-relative mb-3">
    <input type="password" autocomplete="off" name="password" id="password" 
           class="form-control ps-5" placeholder="Enter password" required>
    <i class="fas fa-lock position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('password')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<!-- Submit Button -->
<div class="d-flex justify-content-end">
    <button type="submit" class="vs-btn d-flex align-items-center gap-2" style="width: 120px;">
        Register
    </button>
</div>

<!-- Login Redirect -->
<div class="d-flex justify-content-start align-items-center">
    <p class="mb-0 small">Already have an account?</p>
    <a class="forget-link text-decoration-none ms-2 small" href="{{ route('login.form') }}">Login here</a>
</div>

                </form>
            </div>

        </div>
    </div>
</section>


    <!--==============================

    Footer Area
    ==============================-->

    <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>

    <!--********************************
			Code End  Here 
	******************************** -->

    <!--==============================
        All Js File
    ============================== -->
<!-- Jquery --><!-- jQuery -->
<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>

<!-- Slick Slider -->
<script src="{{ asset('assets/js/slick.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<!-- Wow.js Animation -->
<script src="{{ asset('assets/js/wow.min.js') }}"></script>

<!-- Magnific Popup -->
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>


</body>

</html>