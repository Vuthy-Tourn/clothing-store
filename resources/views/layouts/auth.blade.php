<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nova Studio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body {
            background: #f8f9fa;
        }

        /* Main Container */
        .main-container {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 50%, #ffffff 100%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        /* Left Panel - Darker */
        .left-panel {
            background: #111111;
        }

        /* Right Panel - Clean White */
        .right-panel {
            background: #ffffff;
        }

        /* Form Elements */
        .form-input {
            transition: all 0.3s ease;
            border: 1.5px solid #e0e0e0;
            background: #ffffff;
        }

        .form-input:hover {
            border-color: #b0b0b0;
        }

        .form-input:focus {
            border-color: #000000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
            outline: none;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(to right, #111111, #333333);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #000000, #222222);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #f0f0f0;
            border-color: #d0d0d0;
        }

        /* Back Button */
        .back-btn {
            transition: all 0.3s ease;
        }

        .back-btn:hover i {
            transform: translateX(-3px);
        }

        /* Overlay */
        .image-overlay {
            background: linear-gradient(180deg,
                    rgba(0, 0, 0, 0.9) 0%,
                    rgba(0, 0, 0, 0.7) 50%,
                    rgba(0, 0, 0, 0.9) 100%);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .slide-in-1 {
            animation-delay: 0.1s;
        }

        .slide-in-2 {
            animation-delay: 0.2s;
        }

        .slide-in-3 {
            animation-delay: 0.3s;
        }

        .slide-in-4 {
            animation-delay: 0.4s;
        }

        .slide-in-5 {
            animation-delay: 0.5s;
        }

        /* Smooth Fade */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Text Colors */
        .text-primary-black {
            color: #111111;
        }

        .text-secondary-black {
            color: #333333;
        }

        .text-gray-medium {
            color: #666666;
        }

        .text-gray-light {
            color: #999999;
        }

        /* Hover Effects */
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
        }

        .custom-checkbox:checked {
            background-color: #000;
            border-color: #000;
        }

        .custom-checkbox:checked::after {
            content: '✓';
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
        }
    </style>
    @yield('styles')
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">

    <!-- Main Container -->
    <div class="max-w-6xl w-full">
        <div class="main-container rounded-2xl overflow-hidden relative">

            <!-- Back Button Inside Container -->
            <div class="absolute top-4 left-4 z-40">
                <a href="{{ url()->previous() != url()->current() ? url()->previous() : '/' }}"
                    class="flex items-center gap-2 backdrop-blur-sm rounded-lg px-3 py-2 transition-all duration-300 back-btn group">
                    <i class="fas fa-arrow-left text-gray-700 group-hover:text-black transition-colors text-sm"></i>
                    <span
                        class="text-sm font-medium text-gray-700 group-hover:text-black transition-colors hidden sm:inline">
                        {{ __('messages.back') }}
                    </span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">

                <!-- LEFT SIDE: Image Slider (Black Dominant) -->
                <div class="left-panel relative min-h-[600px]">
                    <!-- Image Overlay -->
                    <div class="absolute inset-0 image-overlay"></div>

                    <!-- Slider Image -->
                    <img id="sliderImage" src="https://i.pinimg.com/736x/82/96/3d/82963dc01313dd8f866e39a868fc62d0.jpg"
                        alt="Fashion Slide"
                        class="absolute inset-0 w-full h-full object-cover opacity-100 transition-all duration-[1500ms] ease-[cubic-bezier(0.7, 0, 0.3, 1)]">

                    <!-- Content Overlay -->
                    <div class="relative z-20 h-full min-h-[600px] flex items-center justify-center p-8">
                        <div class="text-center max-w-md">
                            @yield('left-content')
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE: Form Content -->
                <div class="right-panel p-8 lg:p-12">
                    @yield('right-content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Slider Images
        const images = [
            "https://i.pinimg.com/736x/f9/66/88/f96688e3f57b009ca0caf9bf560de2e3.jpg",
            "https://i.pinimg.com/1200x/84/40/9a/84409ae0093a8096fc9a5412b5d362c6.jpg",
            "https://i.pinimg.com/736x/57/e6/c8/57e6c8d2a33eed475832bafff1159c3e.jpg",
            "https://i.pinimg.com/736x/aa/23/07/aa2307e5d187f5585298e44f21dc6750.jpg",
        ];

        // Initialize slider if needed
        @if (!request()->is('password/*'))
            const sliderImage = document.getElementById('sliderImage');
            let current = 0;

            if (sliderImage) {
                setInterval(() => {
                    sliderImage.style.opacity = 0;
                    setTimeout(() => {
                        current = (current + 1) % images.length;
                        sliderImage.src = images[current];
                        sliderImage.style.opacity = 1;
                    }, 600);
                }, 4000);
            }
        @endif

        // Toggle password visibility
        function togglePassword(fieldId = null) {
            let passwordField;
            let icon;

            if (fieldId) {
                passwordField = document.getElementById(fieldId);
                icon = document.querySelector(`button[onclick="togglePassword('${fieldId}')"] i`);
            } else {
                passwordField = document.querySelector('input[type="password"]');
                icon = document.querySelector('button[onclick="togglePassword()"] i');
            }

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                if (icon) icon.className = 'fas fa-eye-slash text-secondary-black';
            } else {
                passwordField.type = 'password';
                if (icon) icon.className = 'fas fa-eye text-gray-light';
            }
        }

        // Toggle additional info
        function toggleAdditionalInfo() {
            const info = document.getElementById('additional-info');
            const arrow = document.getElementById('additional-arrow');

            if (info.classList.contains('hidden')) {
                info.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                info.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Preview profile picture
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('profile-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full" alt="Profile preview">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // Change language
        function changeLanguage(lang) {
            fetch('/change-language', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    locale: lang
                })
            }).then(() => {
                location.reload();
            });
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add slide-in animations
            document.querySelectorAll('.slide-in').forEach((el, index) => {
                el.style.animationDelay = `${(index + 1) * 0.1}s`;
                el.style.animationFillMode = 'forwards';
            });

            // Add hover effects to form inputs
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('hover-lift');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('hover-lift');
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
