<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.verify_otp') }} - Nova Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/toast.css') }}">
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

        .slide-in-1 {
            animation-delay: 0.1s;
        }

        .slide-in-2 {
            animation-delay: 0.2s;
        }

        .slide-in-3 {
            animation-delay: 0.3s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
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
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex flex-col">

    <main class="flex-grow flex items-center justify-center p-4">
        <!-- Main Container -->
        <div class="max-w-xl w-full">
            <div class="main-container rounded-2xl overflow-hidden">
                <!-- OTP Form -->
                <div class="bg-white p-8 lg:p-12">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-black rounded-full flex items-center justify-center slide-in slide-in-1">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-primary-black mb-2 slide-in slide-in-1">
                            {{ __('messages.email_verification') }}
                        </h1>
                        <p class="text-gray-medium slide-in slide-in-2">
                            {{ __('messages.enter_6_digit_code') }}
                        </p>
                        <div class="mt-1 slide-in slide-in-2">
                            <span class="font-medium text-secondary-black">{{ $email }}</span>
                        </div>
                    </div>

                    <!-- OTP Form -->
                    <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- OTP Input Boxes -->
                        <div class="mb-8 slide-in slide-in-2">
                            <label class="block text-sm font-medium text-secondary-black mb-3 text-center">
                                {{ __('messages.enter_6_digit_code') }}
                            </label>
                            <div class="flex justify-center">
                                <input type="text" maxlength="1" class="otp-input form-input rounded-lg first:ml-0"
                                    data-index="1" oninput="moveToNext(this, 2)"
                                    onkeydown="handleBackspace(this, event)" aria-label="OTP digit 1">
                                <input type="text" maxlength="1" class="otp-input form-input rounded-lg"
                                    data-index="2" oninput="moveToNext(this, 3)"
                                    onkeydown="handleBackspace(this, event)" aria-label="OTP digit 2">
                                <input type="text" maxlength="1" class="otp-input form-input rounded-lg"
                                    data-index="3" oninput="moveToNext(this, 4)"
                                    onkeydown="handleBackspace(this, event)" aria-label="OTP digit 3">
                                <div class="w-4"></div>
                                <input type="text" maxlength="1" class="otp-input form-input rounded-lg"
                                    data-index="4" oninput="moveToNext(this, 5)"
                                    onkeydown="handleBackspace(this, event)" aria-label="OTP digit 4">
                                <input type="text" maxlength="1" class="otp-input form-input rounded-lg"
                                    data-index="5" oninput="moveToNext(this, 6)"
                                    onkeydown="handleBackspace(this, event)" aria-label="OTP digit 5">
                                <input type="text" maxlength="1" class="otp-input form-input rounded-lg"
                                    data-index="6" oninput="updateHiddenOTP()" onkeydown="handleBackspace(this, event)"
                                    aria-label="OTP digit 6">
                            </div>
                            <input type="hidden" name="otp" id="hiddenOTP">
                            <p class="text-xs text-gray-medium text-center mt-3">
                                {{ __('messages.type_6_digit_code') }}
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-6 slide-in slide-in-3">
                            <button type="submit"
                                class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300"
                                id="verifyBtn" disabled>
                                <span class="flex items-center justify-center">
                                    {{ __('messages.verify_otp') }}
                                    <i class="fas fa-check-circle ml-2"></i>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Resend OTP -->
                    <div class="text-center slide-in slide-in-3">
                        <p class="text-sm text-gray-medium mb-2">
                            {{ __('messages.didnt_receive_code') }}
                        </p>
                        <a href="{{ route('otp.resend', ['email' => $email]) }}"
                            class="inline-flex items-center text-secondary-black font-medium hover:underline"
                            id="resendLink">
                            <i class="fas fa-redo mr-2"></i>
                            <span>{{ __('messages.resend_otp') }}</span>
                            <span id="resendTimer" class="ml-2 text-gray-medium hidden"></span>
                        </a>
                    </div>

                    <!-- Alternative Options -->
                    <div class="mt-8 pt-6 border-t border-gray-200 slide-in slide-in-3">
                        <div class="text-center">
                            <p class="text-sm text-gray-medium mb-3">
                                {{ __('messages.having_trouble') }}
                            </p>
                            <div class="flex justify-center space-x-4">
                                <a href="mailto:support@novastudio.com"
                                    class="text-sm text-secondary-black hover:underline">
                                    <i class="fas fa-envelope mr-1"></i>
                                    {{ __('messages.contact_support') }}
                                </a>
                                <a href="{{ route('register') }}" class="text-sm text-secondary-black hover:underline">
                                    <i class="fas fa-user-plus mr-1"></i>
                                    {{ __('messages.register_again') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include Toast JS -->
    <script src="{{ asset('assets/js/toast.js') }}"></script>

    <script>
        // Set translated titles for toast
        window.toastTitles = {
            success: '{{ __('messages.success') }}',
            error: '{{ __('messages.error') }}',
            info: '{{ __('messages.info') }}',
            warning: '{{ __('messages.warning') }}'
        };

        // Initialize on DOM loaded
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

            // Setup form submission
            setupFormSubmission();

            // Show session messages as toasts
            @if (session('success'))
                showSuccess('{{ session('success') }}');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showError('{{ $error }}');
                @endforeach
            @endif
        });

        // Countdown Timer
        function startCountdown(seconds) {
            const resendTimer = document.getElementById('resendTimer');
            const resendLink = document.getElementById('resendLink');

            if (!resendTimer || !resendLink) return;

            let timer = seconds;

            const interval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const secs = timer % 60;

                resendTimer.textContent =
                    `(${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')})`;
                resendTimer.classList.remove('hidden');

                // Disable resend link during countdown
                resendLink.style.pointerEvents = 'none';
                resendLink.classList.add('text-gray-400');
                resendLink.classList.remove('text-secondary-black', 'hover:underline');

                if (timer <= 0) {
                    clearInterval(interval);
                    resendTimer.classList.add('hidden');
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.classList.remove('text-gray-400');
                    resendLink.classList.add('text-secondary-black', 'hover:underline');
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

                input.addEventListener('focus', function() {
                    this.select();
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

        // Form Submission with AJAX
        function setupFormSubmission() {
            const otpForm = document.getElementById('otpForm');
            if (!otpForm) return;

            otpForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const verifyBtn = document.getElementById('verifyBtn');
                const btnText = verifyBtn.querySelector('span');
                const originalHTML = btnText.innerHTML;

                // Show loading state
                btnText.innerHTML = `
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    {{ __('messages.verifying') }}
                `;
                verifyBtn.disabled = true;
                verifyBtn.classList.add('opacity-75');

                try {
                    const formData = new FormData(this);

                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (data.success) {
                            // Show success toast
                            showSuccess(data.message || '{{ __('messages.verification_success') }}');

                            // Redirect to home after 1.5 seconds
                            setTimeout(() => {
                                window.location.href = data.redirect || '/';
                            }, 1500);
                        } else {
                            showError(data.message || '{{ __('messages.verification_failed') }}');
                        }
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.values(data.errors).forEach(error => {
                                showError(error[0]);
                            });
                        } else {
                            showError(data.message || '{{ __('messages.verification_error') }}');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('{{ __('messages.network_error') }}');

                    // Fallback to normal form submission
                    setTimeout(() => {
                        otpForm.submit();
                    }, 1000);
                } finally {
                    // Re-enable button after 2 seconds
                    setTimeout(() => {
                        verifyBtn.disabled = false;
                        btnText.innerHTML = originalHTML;
                        verifyBtn.classList.remove('opacity-75');
                    }, 2000);
                }
            });
        }

        // Resend OTP with AJAX
        const resendLink = document.getElementById('resendLink');
        if (resendLink) {
            resendLink.addEventListener('click', async function(e) {
                e.preventDefault();

                const link = this.href;
                const timerElement = document.getElementById('resendTimer');
                const linkText = this.querySelector('span');
                const originalText = linkText.textContent;

                // Show loading
                linkText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __('messages.sending') }}';
                this.style.pointerEvents = 'none';

                try {
                    const response = await fetch(link, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (data.success) {
                            showSuccess(data.message || '{{ __('messages.otp_resent') }}');

                            // Start new countdown
                            startCountdown(60);
                        } else {
                            showError(data.message || '{{ __('messages.resend_failed') }}');
                        }
                    } else {
                        showError(data.message || '{{ __('messages.resend_error') }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('{{ __('messages.network_error') }}');
                } finally {
                    // Restore link text
                    setTimeout(() => {
                        linkText.textContent = originalText;
                    }, 1000);
                }
            });
        }
    </script>
</body>

</html>
