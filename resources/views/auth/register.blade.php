<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account - Outfit 818</title>
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
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .btn-secondary:hover {
            background: #e8e8e8;
        }
        
        /* Stats Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        .slide-in-4 { animation-delay: 0.4s; }
        .slide-in-5 { animation-delay: 0.5s; }
        
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
        .border-medium { border-color: #d0d0d0; }
        
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
                    <div class="absolute inset-0 image-overlay "></div>
                    
                    <!-- Slider Image -->
                    <img id="sliderImage" 
                         src="https://i.pinimg.com/736x/82/96/3d/82963dc01313dd8f866e39a868fc62d0.jpg" 
                         alt="Fashion Slide"
                         class="absolute inset-0 w-full h-full object-cover opacity-100 transition-all duration-[1500ms] ease-[cubic-bezier(0.7, 0, 0.3, 1)]">
                    
                    <!-- Content Overlay -->
                    <div class="relative z-20 h-full min-h-[600px] flex items-center justify-center p-8">
                        <div class="text-center max-w-md">
                            <!-- Logo/Brand -->
                            <!-- <div class="mb-8 fade-in">
                                <div class="w-14 h-14 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-crown text-black text-xl"></i>
                                </div>
                                <h2 class="text-3xl font-bold text-white mb-3">OUTFIT 818</h2>
                                
                            </div> -->
                            
                            
                            
                            <!-- Quote -->
                            <div class="mt-12 fade-in">
                                <div class="border-l-4 border-white pl-4 py-2">
                                    <p class="text-gray-300 italic text-sm">
                                        "Style is a way to say who you are without having to speak."
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT SIDE: Create Account Form (White Dominant) -->
                <div class="right-panel p-8 lg:p-12">
                    <!-- Form Header -->
                    
                    
                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Profile Picture -->
                        <div class="text-center slide-in slide-in-1">
                            <div class="relative inline-block">
                                <div class="w-20 h-20 rounded-full border-3 border-white shadow-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                    <div id="profile-preview" class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                </div>
                                <label for="profile_picture" class="absolute bottom-0 right-0 w-8 h-8 bg-black rounded-full flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-300 shadow-md">
                                    <i class="fas fa-camera text-white text-xs"></i>
                                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden" onchange="previewProfilePicture(event)">
                                </label>
                            </div>
                        </div>
                        <div class="text-center mb-8">
                       
                        <h1 class="text-3xl font-bold text-primary-black mb-2 slide-in slide-in-1">CREATE ACCOUNT</h1>
                        <p class="text-gray-medium slide-in slide-in-2">Join our fashion community</p>
                    </div>
                        <!-- Name & Email Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="slide-in slide-in-2">
                                <label class="block text-sm font-medium text-secondary-black mb-2">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name') }}" 
                                        class="form-input w-full px-4 py-3 pl-10 rounded-lg"
                                        placeholder="John Doe"
                                        required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <i class="fas fa-user text-gray-light"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="slide-in slide-in-2">
                                <label class="block text-sm font-medium text-secondary-black mb-2">
                                    Email
                                </label>
                                <div class="relative">
                                    <input type="email" name="email" value="{{ old('email') }}" 
                                        class="form-input w-full px-4 py-3 pl-10 rounded-lg"
                                        placeholder="john@example.com"
                                        required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <i class="fas fa-envelope text-gray-light"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="slide-in slide-in-3">
                            <label class="block text-sm font-medium text-secondary-black mb-2">
                                Phone Number
                            </label>
                            <div class="relative">
                                <input type="tel" name="phone" value="{{ old('phone') }}" 
                                    class="form-input w-full px-4 py-3 pl-10 rounded-lg"
                                    placeholder="+855 12 345 678"
                                    required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <i class="fas fa-phone text-gray-light"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div class="slide-in slide-in-3">
                                <label class="block text-sm font-medium text-secondary-black mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" 
                                        class="form-input w-full px-4 py-3 pl-10 pr-10 rounded-lg"
                                        placeholder="••••••••"
                                        required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <i class="fas fa-lock text-gray-light"></i>
                                    </div>
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                                        <i class="fas fa-eye text-gray-light hover:text-secondary-black transition-colors"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="slide-in slide-in-4">
                                <label class="block text-sm font-medium text-secondary-black mb-2">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" 
                                        class="form-input w-full px-4 py-3 pl-10 rounded-lg"
                                        placeholder="••••••••"
                                        required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <i class="fas fa-lock text-gray-light"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Info Button -->
                        <div class="slide-in slide-in-4">
                            <button type="button" onclick="toggleAdditionalInfo()" 
                                    class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-light hover:bg-gray-100 transition-all duration-300">
                                <span class="font-medium text-secondary-black text-sm">
                                    <i class="fas fa-plus mr-2"></i>Additional Information
                                </span>
                                <i id="additional-arrow" class="fas fa-chevron-down text-gray-medium transition-transform duration-300"></i>
                            </button>
                            
                            <!-- Additional Info Fields -->
                            <div id="additional-info" class="hidden space-y-4 mt-4 p-4 bg-gray-50 rounded-lg border border-light">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-secondary-black mb-1">Date of Birth</label>
                                        <input type="date" name="dob" value="{{ old('dob') }}" 
                                            class="form-input w-full px-3 py-2 text-sm rounded">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-secondary-black mb-1">Gender</label>
                                        <select name="gender" 
                                            class="form-input w-full px-3 py-2 text-sm rounded">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-secondary-black mb-1">Address</label>
                                    <textarea name="address" rows="2" 
                                        class="form-input w-full px-3 py-2 text-sm rounded"
                                        placeholder="Your address">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        
                            
                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300">
                                <span class="flex items-center justify-center">
                                    CREATE ACCOUNT
                                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                                </span>
                            </button>
                            <!-- Login Link -->
                        <div class="text-center mt-6 slide-in slide-in-5">
                            <p class="text-sm text-gray-medium">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-secondary-black font-medium hover:underline ml-1">SIGN IN</a>
                            </p>
                        </div>
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

        // Form Interactions
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
        
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('profile-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full" alt="Profile preview">`;
                };
                reader.readAsDataURL(file);
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