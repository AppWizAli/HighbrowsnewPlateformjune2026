
    <style>
        /* Preloader full-page overlay */
        .custom-loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.6s ease;
        }

        /* Circular solid loader */
        .custom-loader {
            width: 70px;
            height: 70px;
            border: 7px solid #dee2e6;
            border-top: 7px solid #007bff;
            border-radius: 50%;
            animation: spin 1s ease-in-out infinite;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
        }

        /* Spin animation */
        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>


<!-- ✅ Step 1: Preloader -->
<div class="custom-loader-wrapper" id="preloader">
    <div class="custom-loader"></div>
</div>



<script>
    window.addEventListener('load', function () {
        const preloader = document.getElementById('preloader');
        setTimeout(() => {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 600); // Match transition
        }, 300); // 2-second delay before fade-out
    });
</script>

