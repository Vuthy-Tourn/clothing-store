<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Outfit 818</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
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
        
        .slide-in-1 { animation-delay: 0.1s; }
        .slide-in-2 { animation-delay: 0.2s; }
        .slide-in-3 { animation-delay: 0.3s; }
        
        /* Smooth Fade */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Text Colors */
        .text-primary-black { color: #111111; }
        .text-secondary-black { color: #333333; }
        .text-gray-medium { color: #666666; }
        .text-gray-light { color: #999999; }
        
        /* Borders */
        .border-light { border-color: #e8e8e8; }
        
        /* Hover Effects */
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    
    <!-- Main Container -->
    <div class="max-w-6xl w-full">
        <div class="main-container rounded-2xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                
                <!-- LEFT SIDE: Image Slider (Black Dominant) -->
                <div class="left-panel relative min-h-[600px]">
                    <!-- Image Overlay -->
                    <div class="absolute inset-0 image-overlay"></div>
                    
                    <!-- Slider Image -->
                    <img id="sliderImage" 
                         src="https://i.pinimg.com/736x/82/96/3d/82963dc01313dd8f866e39a868fc62d0.jpg" 
                         alt="Fashion Slide"
                         class="absolute inset-0 w-full h-full object-cover opacity-100 transition-all duration-[1500ms] ease-[cubic-bezier(0.7, 0, 0.3, 1)]">
                    <!-- Content Overlay -->
                    <div class="relative z-20 h-full min-h-[600px] flex items-center justify-center p-8">
                        <div class="text-center max-w-md">
                            <!-- Welcome Quote -->
                            <div class="mt-12 fade-in">
                                <div class="border-l-4 border-white pl-4 py-2">
                                    <p class="text-gray-300 italic text-sm">
                                        "Welcome back to your style sanctuary."
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT SIDE: Login Form (White Dominant) -->
                <div class="right-panel p-8 lg:p-12">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        
                        <h1 class="text-3xl font-bold text-primary-black mb-2 slide-in slide-in-1">WELCOME BACK</h1>
                        <p class="text-gray-medium slide-in slide-in-2">Sign in to your account</p>
                    </div>
                    
                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email -->
                        <div class="slide-in slide-in-2">
                            <label class="block text-sm font-medium text-secondary-black mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email') }}" 
                                    class="form-input w-full px-4 py-3 pl-10 rounded-lg @error('email') border-red-500 @enderror"
                                    placeholder="you@example.com"
                                    required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <i class="fas fa-envelope text-gray-light"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="slide-in slide-in-3">
                            <label class="block text-sm font-medium text-secondary-black mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password" 
                                    class="form-input w-full px-4 py-3 pl-10 pr-10 rounded-lg @error('password') border-red-500 @enderror"
                                    placeholder="••••••••"
                                    required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <i class="fas fa-lock text-gray-light"></i>
                                </div>
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                                    <i class="fas fa-eye text-gray-light hover:text-secondary-black transition-colors"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between slide-in slide-in-3">
                            <label class="flex items-center text-sm text-gray-medium">
                                <input type="checkbox" name="remember" class="mr-2 w-4 h-4 text-black rounded border-gray-light focus:ring-black">
                                Remember me
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-secondary-black hover:underline font-medium">
                                Forgot password?
                            </a>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="slide-in slide-in-3">
                            <button type="submit" 
                                    class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300">
                                <span class="flex items-center justify-center">
                                    SIGN IN
                                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                </span>
                            </button>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center mt-6 slide-in slide-in-3">
                            <p class="text-sm text-gray-medium">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-secondary-black font-medium hover:underline ml-1">CREATE ACCOUNT</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Slider Script -->
    <script>
        const images = [
            "https://i.pinimg.com/736x/f9/66/88/f96688e3f57b009ca0caf9bf560de2e3.jpg",
           
            "https://i.pinimg.com/1200x/84/40/9a/84409ae0093a8096fc9a5412b5d362c6.jpg",
                         "https://i.pinimg.com/736x/57/e6/c8/57e6c8d2a33eed475832bafff1159c3e.jpg",

            "https://i.pinimg.com/736x/aa/23/07/aa2307e5d187f5585298e44f21dc6750.jpg",
        ];


        const sliderImage = document.getElementById('sliderImage');
        let current = 0;

        // Initialize slider
        setInterval(() => {
            sliderImage.style.opacity = 0;
            setTimeout(() => {
                current = (current + 1) % images.length;
                sliderImage.src = images[current];
                sliderImage.style.opacity = 1;
            }, 600);
        }, 4000);

        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.querySelector('input[name="password"]');
            const icon = document.querySelector('button[onclick="togglePassword()"] i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.className = 'fas fa-eye-slash text-secondary-black';
            } else {
                passwordField.type = 'password';
                icon.className = 'fas fa-eye text-gray-light';
            }
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
</body>
</html>