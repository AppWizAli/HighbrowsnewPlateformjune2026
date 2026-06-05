<header class="header">
    <div class="logo">
        <img src="highbroimage/Logo final.png" alt="Logo" height="50px">
        <h1 class="heading">Highbrows</h1>
        <script>
            // Generate stars for background effect
            const starsContainer = document.querySelector('.stars');
            const numberOfStars = 100;
    
            for (let i = 0; i < numberOfStars; i++) {
                const star = document.createElement('div');
                star.classList.add('star');
    
                // Randomize position and animation delay
                star.style.top = Math.random() * 100 + '%';
                star.style.left = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 3 + 's';
    
                starsContainer.appendChild(star);
            }
        </script>
    </div>
    
    <nav class="nav-links">
        <a href="index.html#about" class="navlink">About Us</a>
        <a href="index.html" class="navlink">Programs</a>
        <a href="index.html" class="navlink">Courses</a>
        <a href="blogs.html" class="navlink">Blogs</a>
        <a href="{{route('admissions.create')}}" class="navlink">Admission</a>
        <a href="houses.html" class="navlink">Houses</a>
    </nav>

    <!-- Check if the user is logged in and user_type is 'user' -->
    @auth
        @if(Auth::user()->user_type == 'user')
            <div class="profile">
                <a href="{{ route('user.profile') }}" class="profile-link">
                    <!-- Circular Profile Image -->
                    <img src="\storage\passportPics\1738219005-laravel.jpg" alt="Profile" class="profile-image">
                    <span class="profile-name">{{ Auth::user()->name }}</span>
                </a>
            </div>
        @else
            <div class="buttons">
                <a href="{{route('signup.form')}}" class="signup">Sign Up</a>
                <a href="{{route('login.form')}}" class="login">Login</a>
            </div>
        @endif
    @else
        <div class="buttons">
            <a href="{{route('signup.form')}}" class="signup">Sign Up</a>
            <a href="{{route('login.form')}}" class="login">Login</a>
        </div>
    @endauth

    <div class="menulinks">
        <i id="menuicon" class="fa-solid fa-bars" data-bs-target="#navbarContent"></i>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav">
                <li><a class="navlink" href="index.html">About Us</a></li>
                <li><a class="navlink" href="#Programs">Programs</a></li>
                <li><a class="navlink" href="#Courses">Courses</a></li>
                <li><a class="navlink" href="admission.html">Admission</a></li>
                <li><a class="navlink" href="blogs.html">Blogs</a></li>
                <li><a class="navlink" href="houses.html">Houses</a></li>
            </ul>
            <div class="menubuttons">
                <a href="{{ route('signup.form') }}" class="signup">Sign Up</a>
                <a href="{{ route('login.form') }}" class="login">Login</a>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to Toggle Menu
        const menuIcon = document.getElementById('menuicon');
        const navbarContent = document.getElementById('navbarContent');

        menuIcon.addEventListener('click', () => {
            navbarContent.classList.toggle('show');

            if (navbarContent.classList.contains('show')) {
                menuIcon.style.color = '#ff5722';
            } else {
                menuIcon.style.color = 'black';
            }
        });
    </script>
</header>
