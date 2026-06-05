

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
 {{-- @include('includes.nav') --}}
 <div class="form-container">
   
     @if(session('message'))
    <div class="alert alert-danger">
        {{ session('message') }}
    </div>
@endif
   <div class="preloader  ">
        <button class="vs-btn preloaderCls">Cancel Preloader </button>
        <div class="preloader-inner">
            <div class="loader"></div>
        </div>
    </div>
  <section class="space-top space-extra-bottom py-4 py-sm-5 px-3 px-sm-4 px-md-5">
  <div class="title-area3 text-center mb-4 wow fadeInUp" data-wow-delay="0.3s">
    <span class="sec-subtitle style1 d-block mb-2 fs-6">Welcome Back</span>
    <h2 class="sec-title fs-3 fs-sm-2 fs-md-1">Login to Account</h2>
  </div>

  <div class="container">
    <div class="row gx-3 gx-sm-4 gx-md-5 justify-content-center align-items-center">
      
      <!-- Left Side Image -->
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <div class="img-box5 mega-hover wow fadeInUp" data-wow-delay="0.3s">
          <div class="img-1 rounded overflow-hidden" style="height: 100%; max-height: 450px;">
            <img class="w-100 h-100 object-fit-cover" src="{{ asset('assets/img/course/pexels-photo-5475752.jpeg') }}" alt="Login Illustration">
          </div>
        </div>
      </div>

      <!-- Right Side Login Form -->
      <div class="col-12 col-lg-6">
        <form id="loginForm" class="form-style4 p-3 p-sm-4 p-md-5 rounded wow fadeInUp" 
              method="POST" action="{{ route('login') }}" data-wow-delay="0.4s"
              data-bg-src="{{ asset('assets/img/bg/course-bg-pattern.jpg') }}">
          @csrf
          <h2 class="form-title mb-4 text-start fs-4">LOG IN</h2>

          <!-- Email -->
         <!-- Email -->
<div class="form-group mb-3 position-relative">
    <input type="email" name="email" id="email" 
           class="form-control login-input ps-5 py-2"
           placeholder="Email address" 
           value="{{ old('email') }}" required>
    <i class="fas fa-user position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    @error('email')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

<!-- Password -->
<div class="form-group mb-3 position-relative">
    <input type="password" name="password" id="password" 
           class="form-control login-input ps-5 py-2"
           placeholder="Password" required>
    <i class="fas fa-lock position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>

    <!-- Toggle Password -->
    <span id="togglePassword" 
          style="position:absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: gray;">
        👁️
    </span>
    @error('password')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

<!-- Remember Me & Forgot Password -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-1">
    <div class="form-check mb-1 mb-sm-0">
        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label small" for="remember">Remember me</label>
    </div>
    <a class="forget-link text-decoration-none small text-sm-end" href="{{ route('password.request') }}">Forgot Password?</a>
</div>

<!-- Login Button -->
<div class="d-flex justify-content-end mb-3">
    <button type="submit" class="vs-btn d-flex align-items-center gap-2" style="width: 120px;">
        Login <i class="fas fa-sign-in-alt"></i>
    </button>
</div>

<!-- Registration Link -->
<div class="d-flex justify-content-start align-items-center">
    <p class="mb-0 small">Don't have an account?</p>
    <a class="forget-link text-decoration-none ms-2 small" href="{{ route('signup.form') }}">Register here</a>
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

