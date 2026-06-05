

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
    <link rel="shortcut icon" href="<?php echo e(asset('assets/img/mylogo.png')); ?>" type="image/x-icon">
    <link rel="icon" href="<?php echo e(asset('assets/img/mylogo.png')); ?>" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- All CSS Files -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">
    <!-- Fontawesome Icons -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/fontawesome.min.css')); ?>">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/magnific-popup.min.css')); ?>">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/slick.min.css')); ?>">
    <!-- Animate.css (for WOW.js animations) -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/animate.min.css')); ?>">
    <!-- Theme Main CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/index.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/Login-regiration/login-reg.css')); ?>">
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
                        <img class="w-100" src="<?php echo e(asset('assets/img/course/pexels-photo-5475752.jpeg')); ?>" 
                             alt="Signup Illustration" style="width: 100%; height: 350px; object-fit: cover;">
                    </div>
                </div>
            </div>

            <!-- Sign Up Form -->
            <div class="col-lg-6" id="signup">
                <form class="form-style4 signup p-4 wow fadeInUp needs-validation" 
                      method="POST" 
                      action="<?php echo e(route('signup')); ?>" 
                      data-wow-delay="0.4s"
                      data-bg-src="<?php echo e(asset('assets/img/bg/course-bg-pattern.jpg')); ?>" 
                      novalidate>
                    <?php echo csrf_field(); ?>
                    <h2 class="form-title mb-4">SIGN UP</h2>

                 <!-- Username -->
<div class="form-group position-relative mb-3">
    <input type="text" autocomplete="off" name="name" id="username" 
           class="form-control ps-5" placeholder="Enter your username" 
           value="<?php echo e(old('name')); ?>" required>
    <i class="fas fa-user position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span style="color:red;"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<!-- Contact Number -->
<div class="form-group position-relative mb-3">
    <input type="text" autocomplete="off" name="contact" id="contact" 
           class="form-control ps-5" placeholder="Enter contact number" 
           value="<?php echo e(old('contact')); ?>" required>
    <i class="fas fa-phone position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span style="color:red;"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<!-- Email -->
<div class="form-group position-relative mb-3">
    <input type="email" autocomplete="off" name="email" id="email" 
           class="form-control ps-5" placeholder="Enter your email" 
           value="<?php echo e(old('email')); ?>" required>
    <i class="fas fa-envelope position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span style="color:red;"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<!-- Grade Selector -->
<div class="form-group position-relative mb-3">
    <select name="grade" id="grade" class="form-select ps-5" required>
        <option value="" disabled <?php echo e(old('grade') ? '' : 'selected'); ?>>Select Grade or Course</option>
        <option value="Grade 5" <?php echo e(old('grade') == 'Grade 5' ? 'selected' : ''); ?>>Grade 5</option>
        <option value="Grade 6" <?php echo e(old('grade') == 'Grade 6' ? 'selected' : ''); ?>>Grade 6</option>
        <option value="Grade 7" <?php echo e(old('grade') == 'Grade 7' ? 'selected' : ''); ?>>Grade 7</option>
        <option value="Grade 8" <?php echo e(old('grade') == 'Grade 8' ? 'selected' : ''); ?>>Grade 8</option>
        <option value="Grade 9" <?php echo e(old('grade') == 'Grade 9' ? 'selected' : ''); ?>>Grade 9</option>
        <option value="Grade 10" <?php echo e(old('grade') == 'Grade 10' ? 'selected' : ''); ?>>Grade 10</option>
        <option value="Grade 11" <?php echo e(old('grade') == 'Grade 11' ? 'selected' : ''); ?>>Grade 11</option>
        <option value="Grade 12" <?php echo e(old('grade') == 'Grade 12' ? 'selected' : ''); ?>>Grade 12</option>
        <option value="Grade Issb" <?php echo e(old('grade') == 'Grade Issb' ? 'selected' : ''); ?>> Grade Issb</option>
    </select>
    <i class="fas fa-graduation-cap position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span style="color:red;"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<!-- Category Selector -->
<div class="form-group position-relative mb-3">
    <select name="category" id="category" class="form-select ps-5" required>
        <option value="" disabled <?php echo e(old('category') ? '' : 'selected'); ?>>Select category</option>
        <option value="Online" <?php echo e(old('category') == 'Online' ? 'selected' : ''); ?>>Online</option>
        <option value="DayScholar" <?php echo e(old('category') == 'DayScholar' ? 'selected' : ''); ?>>DayScholar</option>
        <option value="Hostel" <?php echo e(old('category') == 'Hostel' ? 'selected' : ''); ?>>Hostel</option>
    </select>
    <i class="fas fa-list position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span style="color:red;"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<!-- Password -->
<div class="form-group position-relative mb-3">
    <input type="password" autocomplete="off" name="password" id="password" 
           class="form-control ps-5" placeholder="Enter password" required>
    <i class="fas fa-lock position-absolute" 
       style="top: 50%; left: 15px; transform: translateY(-50%); color: gray; pointer-events: none;"></i>
    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span style="color:red;"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
    <a class="forget-link text-decoration-none ms-2 small" href="<?php echo e(route('login.form')); ?>">Login here</a>
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
<script src="<?php echo e(asset('assets/js/vendor/jquery-3.6.0.min.js')); ?>"></script>

<!-- Slick Slider -->
<script src="<?php echo e(asset('assets/js/slick.min.js')); ?>"></script>

<!-- Bootstrap -->
<script src="<?php echo e(asset('assets/js/bootstrap.min.js')); ?>"></script>

<!-- Wow.js Animation -->
<script src="<?php echo e(asset('assets/js/wow.min.js')); ?>"></script>

<!-- Magnific Popup -->
<script src="<?php echo e(asset('assets/js/jquery.magnific-popup.min.js')); ?>"></script>

<!-- Main JS File -->
<script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>


</body>

</html><?php /**PATH /home/u379508397/domains/highbrowsian.com/public_html/highbrows_software/resources/views/custom-auth/signup.blade.php ENDPATH**/ ?>