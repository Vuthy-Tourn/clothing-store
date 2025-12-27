<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - Outfit 818</title>
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
        
        /* OTP Input Style */
        .otp-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 8px;
        }
        
        @media (max-width: 640px) {
            .otp-input {
                width: 40px;
                height: 40px;
                margin: 0 6px;
            }
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

    <main class="flex-grow flex items-center justify-center p-4">
        <!-- Main Container -->
        <div class="max-w-2xl w-full">
            <div class="main-container rounded-2xl overflow-hidden">
                <div class="">
                    
               
                    <!-- RIGHT SIDE: OTP Form -->
                    <div class="bg-white p-8 lg:p-12">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-black rounded-full flex items-center justify-center slide-in slide-in-1">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-primary-black mb-2 slide-in slide-in-1">EMAIL VERIFICATION</h1>
                            <p class="text-gray-medium slide-in slide-in-2">Enter the 6-digit code sent to</p>
                            <div class="mt-1 slide-in slide-in-2">
                                <span class="font-medium text-secondary-black">{{ $email }}</span>
                            </div>
                        </div>
                        
                        <!-- Messages -->
                        @if(session('success'))
                            <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg slide-in slide-in-2">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="text-green-700 text-sm">{{ session('success') }}</span>
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
                        
                        <!-- OTP Form -->
                        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <!-- OTP Input Boxes -->
                            <div class="mb-8 slide-in slide-in-2">
                                <label class="block text-sm font-medium text-secondary-black mb-3 text-center">
                                    Enter 6-digit code
                                </label>
                                <div class="flex justify-center">
                                    <input type="text" maxlength="1" 
                                           class="otp-input form-input rounded-lg first:ml-0"
                                           data-index="1"
                                           oninput="moveToNext(this, 2)"
                                           onkeydown="handleBackspace(this, event)">
                                    <input type="text" maxlength="1" 
                                           class="otp-input form-input rounded-lg"
                                           data-index="2"
                                           oninput="moveToNext(this, 3)"
                                           onkeydown="handleBackspace(this, event)">
                                    <input type="text" maxlength="1" 
                                           class="otp-input form-input rounded-lg"
                                           data-index="3"
                                           oninput="moveToNext(this, 4)"
                                           onkeydown="handleBackspace(this, event)">
                                    <div class="w-4"></div>
                                    <input type="text" maxlength="1" 
                                           class="otp-input form-input rounded-lg"
                                           data-index="4"
                                           oninput="moveToNext(this, 5)"
                                           onkeydown="handleBackspace(this, event)">
                                    <input type="text" maxlength="1" 
                                           class="otp-input form-input rounded-lg"
                                           data-index="5"
                                           oninput="moveToNext(this, 6)"
                                           onkeydown="handleBackspace(this, event)">
                                    <input type="text" maxlength="1" 
                                           class="otp-input form-input rounded-lg"
                                           data-index="6"
                                           oninput="updateHiddenOTP()"
                                           onkeydown="handleBackspace(this, event)">
                                </div>
                                <input type="hidden" name="otp" id="hiddenOTP">
                                <p class="text-xs text-gray-medium text-center mt-3">Type the 6-digit verification code</p>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mb-6 slide-in slide-in-3">
                                <button type="submit" 
                                        class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300"
                                        id="verifyBtn">
                                    <span class="flex items-center justify-center">
                                        VERIFY OTP
                                        <i class="fas fa-check-circle ml-2"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Resend OTP -->
                        <div class="text-center slide-in slide-in-3">
                            <p class="text-sm text-gray-medium mb-2">Didn't receive the code?</p>
                            <a href="{{ route('otp.resend', ['email' => $email]) }}" 
                               class="inline-flex items-center text-secondary-black font-medium hover:underline"
                               id="resendLink">
                                <i class="fas fa-redo mr-2"></i>
                                <span>Resend OTP</span>
                                <span id="resendTimer" class="ml-2 text-gray-medium hidden">(60s)</span>
                            </a>
                        </div>
                        
                        <!-- Alternative Options -->
                        <div class="mt-8 pt-6 border-t border-gray-200 slide-in slide-in-3">
                            <div class="text-center">
                                <p class="text-sm text-gray-medium mb-3">Having trouble?</p>
                                <div class="flex justify-center space-x-4">
                                    <a href="mailto:support@outfit818.com" class="text-sm text-secondary-black hover:underline">
                                        <i class="fas fa-envelope mr-1"></i> Contact Support
                                    </a>
                                    <a href="{{ route('register') }}" class="text-sm text-secondary-black hover:underline">
                                        <i class="fas fa-user-plus mr-1"></i> Register Again
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Placeholder -->
    <footer class="bg-white py-4 text-center border-t border-gray-200">
        <p class="text-gray-600 text-sm">&copy; 2024 Outfit 818. All rights reserved.</p>
    </footer>

    <script>
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add slide-in animations
            document.querySelectorAll('.slide-in').forEach((el, index) => {
                el.style.animationDelay = `${(index + 1) * 0.1}s`;
                el.style.animationFillMode = 'forwards';
            });
            
            // Start countdown timer
            startCountdown(600); // 10 minutes
            
            // Setup OTP input handling
            setupOTPInputs();
            
            // Setup resend timer
            setupResendTimer();
        });
        
        // Countdown Timer
        function startCountdown(seconds) {
            const countdownElement = document.getElementById('countdown');
            const resendTimer = document.getElementById('resendTimer');
            const resendLink = document.getElementById('resendLink');
            
            let timer = seconds;
            
            const interval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const secs = timer % 60;
                
                countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                
                if (timer <= 60) {
                    countdownElement.classList.add('text-red-500');
                }
                
                if (timer <= 0) {
                    clearInterval(interval);
                    countdownElement.textContent = "Expired";
                    countdownElement.classList.add('text-red-600');
                    
                    // Show resend timer
                    if (resendTimer) {
                        resendTimer.classList.remove('hidden');
                        startResendCountdown(60);
                    }
                }
                
                timer--;
            }, 1000);
        }
        
        // OTP Input Handling
        function setupOTPInputs() {
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    updateHiddenOTP();
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                    const chars = pasteData.split('');
                    
                    otpInputs.forEach((input, index) => {
                        if (chars[index]) {
                            input.value = chars[index];
                        }
                    });
                    
                    updateHiddenOTP();
                    otpInputs[5].focus();
                });
            });
        }
        
        function moveToNext(current, nextIndex) {
            if (current.value.length >= 1) {
                const nextInput = document.querySelector(`.otp-input[data-index="${nextIndex}"]`);
                if (nextInput) {
                    nextInput.focus();
                }
            }
            updateHiddenOTP();
        }
        
        function handleBackspace(current, event) {
            if (event.key === 'Backspace' && current.value === '') {
                const index = parseInt(current.dataset.index);
                if (index > 1) {
                    const prevInput = document.querySelector(`.otp-input[data-index="${index - 1}"]`);
                    if (prevInput) {
                        prevInput.focus();
                        prevInput.value = '';
                    }
                }
            }
            updateHiddenOTP();
        }
        
        function updateHiddenOTP() {
            const otpInputs = document.querySelectorAll('.otp-input');
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            document.getElementById('hiddenOTP').value = otp;
            
            // Enable/disable verify button
            const verifyBtn = document.getElementById('verifyBtn');
            if (otp.length === 6) {
                verifyBtn.disabled = false;
                verifyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                verifyBtn.disabled = true;
                verifyBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
        
        // Resend Timer
        function setupResendTimer() {
            let canResend = false;
            const resendLink = document.getElementById('resendLink');
            const resendTimer = document.getElementById('resendTimer');
            
            if (resendLink) {
                resendLink.addEventListener('click', function(e) {
                    if (!canResend) {
                        e.preventDefault();
                        return;
                    }
                });
            }
        }
        
        function startResendCountdown(seconds) {
            const resendLink = document.getElementById('resendLink');
            const resendTimer = document.getElementById('resendTimer');
            
            if (!resendLink || !resendTimer) return;
            
            let timer = seconds;
            resendTimer.classList.remove('hidden');
            resendLink.style.pointerEvents = 'none';
            resendLink.classList.add('text-gray-light');
            
            const interval = setInterval(() => {
                resendTimer.textContent = `(${timer}s)`;
                
                if (timer <= 0) {
                    clearInterval(interval);
                    resendTimer.classList.add('hidden');
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.classList.remove('text-gray-light');
                    resendLink.classList.add('text-secondary-black');
                }
                
                timer--;
            }, 1000);
        }
        
        // Form Submission Animation
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            const verifyBtn = document.getElementById('verifyBtn');
            const btnText = verifyBtn.querySelector('span');
            const btnIcon = verifyBtn.querySelector('i');
            
            // Show loading state
            btnText.innerHTML = 'VERIFYING...';
            btnIcon.className = 'fas fa-spinner fa-spin ml-2';
            verifyBtn.disabled = true;
            verifyBtn.classList.add('opacity-75');
            
            // Optional: Add success animation on successful verification
            // This would be handled by the backend response
        });
        
        // Error message shake animation
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.classList.remove('shake');
                void errorMessage.offsetWidth; // Trigger reflow
                errorMessage.classList.add('shake');
            }, 100);
        }
    </script>
</body>
</html>