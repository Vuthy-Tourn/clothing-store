<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - Outfit 818</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
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
        
        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .slide-in-1 { animation-delay: 0.1s; }
        .slide-in-2 { animation-delay: 0.2s; }
        .slide-in-3 { animation-delay: 0.3s; }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes success {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .success-animation {
            animation: success 0.5s ease-in-out;
        }
        
        /* Text Colors */
        .text-primary-black { color: #111111; }
        .text-secondary-black { color: #333333; }
        .text-gray-medium { color: #666666; }
        .text-gray-light { color: #999999; }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex flex-col">

    @include('partials.navbar')

    <main class="flex-grow flex items-center justify-center p-4">
        <!-- Main Container -->
        <div class="max-w-md w-full">
            <div class="main-container rounded-2xl overflow-hidden">
                <div class="bg-white p-8 lg:p-10">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-black rounded-full flex items-center justify-center slide-in slide-in-1">
                            <i class="fas fa-key text-white text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-primary-black mb-2 slide-in slide-in-1">RESET YOUR PASSWORD</h1>
                        <p class="text-gray-medium slide-in slide-in-2">Enter your email address and we'll send you a password reset link</p>
                    </div>
                    
                    <!-- Messages -->
                    @if(session('status'))
                        <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg slide-in slide-in-2 success-animation">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                <span class="text-green-700 text-sm">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg slide-in slide-in-2 shake" id="error-message">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                <div class="text-red-700 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Reset Form -->
                    <form method="POST" action="{{ route('password.email') }}" id="resetForm" class="space-y-6">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="slide-in slide-in-2">
                            <label for="email" class="block text-sm font-medium text-secondary-black mb-2">
                                <i class="fas fa-envelope mr-1"></i> Email Address
                            </label>
                            <div class="relative">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="you@example.com"
                                    class="w-full px-4 py-3 form-input rounded-lg text-secondary-black"
                                    autocomplete="email"
                                    autofocus />
                                <div class="absolute right-3 top-3">
                                    @error('email')
                                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                                    @else
                                        <i class="fas fa-check text-green-500 opacity-0" id="email-check"></i>
                                    @enderror
                                </div>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="slide-in slide-in-3">
                            <button 
                                type="submit" 
                                class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300"
                                id="resetBtn">
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    SEND RESET LINK
                                </span>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Additional Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200 slide-in slide-in-3">
                        <div class="text-center">
                            <p class="text-sm text-gray-medium mb-3">
                                <i class="fas fa-info-circle mr-1"></i> The password reset link will expire in 60 minutes
                            </p>
                        </div>
                    </div>
                    
                    <!-- Back to Login -->
                    <div class="mt-6 text-center slide-in slide-in-3">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center text-secondary-black font-medium hover:underline transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Login
                        </a>
                    </div>
                    
                    <!-- Support Section -->
                    <div class="mt-8 slide-in slide-in-3">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-start">
                                <div class="mr-3 mt-1">
                                    <i class="fas fa-life-ring text-gray-medium"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-secondary-black mb-1">Need help?</h4>
                                    <p class="text-xs text-gray-medium">
                                        Contact our support team at 
                                        <a href="mailto:support@outfit818.com" class="text-secondary-black hover:underline">
                                            support@outfit818.com
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script>
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add slide-in animations
            document.querySelectorAll('.slide-in').forEach((el, index) => {
                el.style.animationDelay = `${(index + 1) * 0.1}s`;
                el.style.animationFillMode = 'forwards';
            });
            
            // Email validation
            const emailInput = document.getElementById('email');
            const emailCheck = document.getElementById('email-check');
            
            if (emailInput && emailCheck) {
                emailInput.addEventListener('input', function() {
                    if (this.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                        emailCheck.classList.remove('opacity-0');
                        emailCheck.classList.add('opacity-100');
                    } else {
                        emailCheck.classList.remove('opacity-100');
                        emailCheck.classList.add('opacity-0');
                    }
                });
            }
            
            // Form submission animation
            const resetForm = document.getElementById('resetForm');
            if (resetForm) {
                resetForm.addEventListener('submit', function(e) {
                    const resetBtn = document.getElementById('resetBtn');
                    const btnText = resetBtn.querySelector('span');
                    const btnIcon = resetBtn.querySelector('i');
                    
                    // Show loading state
                    btnText.innerHTML = 'SENDING LINK...';
                    btnIcon.className = 'fas fa-spinner fa-spin mr-2';
                    resetBtn.disabled = true;
                    resetBtn.classList.add('opacity-75');
                    
                    // Re-enable after 5 seconds in case of error
                    setTimeout(() => {
                        resetBtn.disabled = false;
                        resetBtn.classList.remove('opacity-75');
                        btnText.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> SEND RESET LINK';
                    }, 5000);
                });
            }
            
            // Error message shake animation
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.classList.remove('shake');
                    void errorMessage.offsetWidth; // Trigger reflow
                    errorMessage.classList.add('shake');
                }, 100);
            }
            
            // Auto-hide success message after 5 seconds
            const successMessage = document.querySelector('.success-animation');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    successMessage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 5000);
            }
        });
        
        // Add smooth focus transition
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-black', 'ring-opacity-10');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-black', 'ring-opacity-10');
            });
        });
    </script>
</body>
</html>