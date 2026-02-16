<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('messages.reset_password') }} - Nova Studio</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body {
            background: #f8f9fa;
        }

        .main-container {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 50%, #ffffff 100%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

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

        /* Toast styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-left: 4px solid;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-success {
            border-left-color: #10b981;
        }

        .toast-error {
            border-left-color: #ef4444;
        }

        .toast-info {
            border-left-color: #3b82f6;
        }

        .toast-warning {
            border-left-color: #f59e0b;
        }

        .toast-content {
            padding: 16px;
        }

        .toast-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
        }

        .toast-close:hover {
            color: #666;
        }

        .toast-progress {
            height: 3px;
            width: 100%;
            background: rgba(0, 0, 0, 0.1);
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .toast-progress-bar {
            height: 100%;
            width: 100%;
            transform-origin: left;
            animation: progress 5s linear forwards;
        }

        .toast-success .toast-progress-bar {
            background: #10b981;
        }

        .toast-error .toast-progress-bar {
            background: #ef4444;
        }

        .toast-info .toast-progress-bar {
            background: #3b82f6;
        }

        .toast-warning .toast-progress-bar {
            background: #f59e0b;
        }

        @keyframes progress {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex flex-col">
    <main class="flex-grow flex items-center justify-center p-4">
        <!-- Toast Container -->
        <div id="toast-container"></div>

        <!-- Main Container -->
        <div class="max-w-md w-full">
            <div class="main-container rounded-2xl overflow-hidden">
                <div class="bg-white p-8 lg:p-10">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-black rounded-full flex items-center justify-center slide-in slide-in-1">
                            <i class="fas fa-key text-white text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-primary-black mb-2 slide-in slide-in-1">
                            {{ __('messages.reset_your_password') }}
                        </h1>
                        <p class="text-gray-medium slide-in slide-in-2">
                            {{ __('messages.reset_instructions') }}
                        </p>
                    </div>

                    <!-- Reset Form -->
                    <form method="POST" action="{{ route('password.email') }}" id="resetForm" class="space-y-6">
                        @csrf

                        <!-- Email Input -->
                        <div class="slide-in slide-in-2">
                            <label for="email" class="block text-sm font-medium text-secondary-black mb-2">
                                <i class="fas fa-envelope mr-1"></i>
                                {{ __('messages.email_address') }}
                            </label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    placeholder="{{ __('messages.email_placeholder') }}"
                                    class="w-full px-4 py-3 form-input rounded-lg text-secondary-black"
                                    autocomplete="email" autofocus />
                                <div class="absolute right-3 top-3">
                                    <i class="fas fa-check text-green-500 opacity-0" id="email-check"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="slide-in slide-in-3">
                            <button type="submit"
                                class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300 relative overflow-hidden group"
                                id="resetBtn">
                                <span class="flex items-center justify-center relative z-10">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    {{ __('messages.send_reset_link') }}
                                </span>
                                <div
                                    class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300">
                                </div>
                            </button>
                        </div>
                    </form>

                    <!-- Additional Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200 slide-in slide-in-3">
                        <div class="text-center">
                            <p class="text-sm text-gray-medium mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ __('messages.link_expiry') }}
                            </p>
                        </div>
                    </div>

                    <!-- Back to Login -->
                    <div class="mt-6 text-center slide-in slide-in-3">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center text-secondary-black font-medium hover:underline transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ __('messages.back_to_login') }}
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
                                    <h4 class="text-sm font-medium text-secondary-black mb-1">
                                        {{ __('messages.need_help') }}
                                    </h4>
                                    <p class="text-xs text-gray-medium">
                                        {{ __('messages.contact_support') }}
                                        <a href="mailto:support@novastudio.com"
                                            class="text-secondary-black hover:underline">
                                            {{ __('messages.support_email') }}
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
        // Toast Notification System
        function showToast(type, message, duration = 5000) {
            const container = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();

            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                info: 'fas fa-info-circle',
                warning: 'fas fa-exclamation-triangle'
            };

            const titles = {
                success: '{{ __('messages.success') }}',
                error: '{{ __('messages.error') }}',
                info: '{{ __('messages.info') }}',
                warning: '{{ __('messages.warning') }}'
            };

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast toast-${type}`;

            toast.innerHTML = `
                <div class="toast-content">
                    <button class="toast-close" onclick="removeToast('${toastId}')">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <i class="${icons[type]} text-lg ${type === 'success' ? 'text-green-500' : type === 'error' ? 'text-red-500' : type === 'warning' ? 'text-yellow-500' : 'text-blue-500'}"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 mb-1">${titles[type]}</h4>
                            <p class="text-sm text-gray-600">${message}</p>
                        </div>
                    </div>
                    <div class="toast-progress">
                        <div class="toast-progress-bar"></div>
                    </div>
                </div>
            `;

            container.appendChild(toast);

            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Auto remove
            const timer = setTimeout(() => {
                removeToast(toastId);
            }, duration);

            // Store timer reference
            toast.dataset.timer = timer;
        }

        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('show');
                clearTimeout(toast.dataset.timer);
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }

        // Email validation
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const emailCheck = document.getElementById('email-check');
            const resetForm = document.getElementById('resetForm');
            const resetBtn = document.getElementById('resetBtn');

            // Real-time email validation
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

                // Check initial value
                if (emailInput.value && emailInput.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    emailCheck.classList.remove('opacity-0');
                    emailCheck.classList.add('opacity-100');
                }
            }

            // Form submission
            if (resetForm) {
                resetForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const email = emailInput.value;
                    const submitBtnText = resetBtn.querySelector('span');
                    const originalHTML = submitBtnText.innerHTML;

                    // Validate email format
                    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                        showToast('error', '{{ __('messages.invalid_email') }}');
                        emailInput.focus();
                        return;
                    }

                    // Show loading state
                    resetBtn.disabled = true;
                    submitBtnText.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        {{ __('messages.sending_link') }}
                    `;
                    resetBtn.classList.add('opacity-75');

                    // Create form data
                    const formData = new FormData(this);

                    // Submit form using fetch (AJAX)
                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message ||
                                    '{{ __('messages.reset_success') }}');
                                resetForm.reset();
                                emailCheck.classList.remove('opacity-100');
                                emailCheck.classList.add('opacity-0');

                                // Show reminder after 2 seconds
                                setTimeout(() => {
                                    showToast('info', '{{ __('messages.check_email') }}');
                                }, 2000);
                            } else {
                                showToast('error', data.message || '{{ __('messages.reset_error') }}');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Fallback: Submit form normally for non-AJAX requests
                            this.submit();
                        })
                        .finally(() => {
                            // Re-enable button after 2 seconds
                            setTimeout(() => {
                                resetBtn.disabled = false;
                                submitBtnText.innerHTML = originalHTML;
                                resetBtn.classList.remove('opacity-75');
                            }, 2000);
                        });
                });
            }

            // Show existing session messages as toasts
            @if (session('status'))
                showToast('success', '{{ session('status') }}');
            @endif

            @if (session('success'))
                showToast('success', '{{ session('success') }}');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast('error', '{{ $error }}');
                @endforeach
            @endif

            // Add animations
            document.querySelectorAll('.slide-in').forEach((el, index) => {
                el.style.animationDelay = `${(index + 1) * 0.1}s`;
                el.style.animationFillMode = 'forwards';
            });
        });
    </script>
</body>

</html>
