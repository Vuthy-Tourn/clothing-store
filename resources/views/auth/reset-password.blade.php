<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('messages.set_new_password') }} - Outfit 818</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Include Toast CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/toast.css') }}">
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
        
        .slide-in-1 { animation-delay: 0.1s; }
        .slide-in-2 { animation-delay: 0.2s; }
        .slide-in-3 { animation-delay: 0.3s; }
        .slide-in-4 { animation-delay: 0.4s; }
        
        .text-primary-black { color: #111111; }
        .text-secondary-black { color: #333333; }
        .text-gray-medium { color: #666666; }
        .text-gray-light { color: #999999; }
    </style>
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <!-- Toast container will be created by toast.js -->
    
    <!-- Main Container -->
    <div class="max-w-md w-full">
        <div class="main-container rounded-2xl overflow-hidden">
            <div class="bg-white p-8 lg:p-10">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-black rounded-full flex items-center justify-center slide-in slide-in-1">
                        <i class="fas fa-lock text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-primary-black mb-2 slide-in slide-in-1">
                        {{ __('messages.set_new_password') }}
                    </h1>
                    <p class="text-gray-medium slide-in slide-in-2">
                        {{ __('messages.create_strong_password') }}
                    </p>
                </div>
                
                <!-- Password Reset Form -->
                <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm" class="space-y-6">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <!-- Email (hidden) -->
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                    
                    @if ($email)
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user mr-2"></i>
                                {{ __('messages.resetting_for') }}: <strong>{{ $email }}</strong>
                            </p>
                        </div>
                    @endif
                    
                    <!-- New Password -->
                    <div class="slide-in slide-in-2">
                        <label class="block text-sm font-medium text-secondary-black mb-2">
                            <i class="fas fa-lock mr-1"></i>
                            {{ __('messages.new_password') }}
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="newPassword" required
                                class="form-input w-full px-4 py-3 pl-10 pr-10 rounded-lg"
                                placeholder="{{ __('messages.new_password_placeholder') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-key text-gray-light"></i>
                            </div>
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye text-gray-light hover:text-secondary-black transition-colors"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="slide-in slide-in-3">
                        <label class="block text-sm font-medium text-secondary-black mb-2">
                            <i class="fas fa-lock mr-1"></i>
                            {{ __('messages.confirm_password') }}
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirmPassword" required
                                class="form-input w-full px-4 py-3 pl-10 rounded-lg"
                                placeholder="{{ __('messages.confirm_password_placeholder') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-key text-gray-light"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="slide-in slide-in-3">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-medium text-secondary-black mb-2">
                                <i class="fas fa-shield-alt mr-1"></i>
                                {{ __('messages.password_requirements') }}
                            </h4>
                            <ul class="text-xs text-gray-600 space-y-1">
                                <li id="length" class="flex items-center">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ __('messages.min_8_characters') }}
                                </li>
                                <li id="uppercase" class="flex items-center">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ __('messages.one_uppercase') }}
                                </li>
                                <li id="lowercase" class="flex items-center">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ __('messages.one_lowercase') }}
                                </li>
                                <li id="number" class="flex items-center">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ __('messages.one_number') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="slide-in slide-in-4">
                        <button type="submit"
                            class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300 relative overflow-hidden"
                            id="resetBtn">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i>
                                {{ __('messages.reset_password') }}
                            </span>
                        </button>
                    </div>
                </form>
                
                <!-- Back to Login -->
                <div class="mt-6 text-center slide-in slide-in-4">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center text-secondary-black font-medium hover:underline transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('messages.back_to_login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Toast JS -->
    <script src="{{ asset('assets/js/toast.js') }}"></script>
    
    <script>
        // Set translated titles for toast
        window.toastTitles = {
            success: '{{ __("messages.success") }}',
            error: '{{ __("messages.error") }}',
            info: '{{ __("messages.info") }}',
            warning: '{{ __("messages.warning") }}'
        };
        
        // Password validation logic
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password)
            };
            
            // Update UI
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(req);
                if (element) {
                    const icon = element.querySelector('i');
                    if (requirements[req]) {
                        icon.className = 'fas fa-check-circle text-green-500 text-xs mr-2';
                        element.classList.add('text-green-600');
                        element.classList.remove('text-gray-600');
                    } else {
                        icon.className = 'fas fa-circle text-xs mr-2 text-gray-400';
                        element.classList.remove('text-green-600');
                        element.classList.add('text-gray-600');
                    }
                }
            });
            
            return Object.values(requirements).every(req => req);
        }
        
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.querySelector(`button[onclick="togglePassword('${fieldId}')"] i`);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash text-secondary-black';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye text-gray-light';
            }
        }
        
        // AJAX Form Submission with Toast
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('newPassword');
            const resetForm = document.getElementById('resetPasswordForm');
            const resetBtn = document.getElementById('resetBtn');
            
            // Real-time password validation
            if (newPassword) {
                newPassword.addEventListener('input', function() {
                    validatePassword(this.value);
                });
            }
            
            // Form submission
            if (resetForm) {
                resetForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const password = document.getElementById('newPassword').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;
                    const submitBtnText = resetBtn.querySelector('span');
                    const originalHTML = submitBtnText.innerHTML;
                    
                    // Validate password requirements
                    if (!validatePassword(password)) {
                        showError('{{ __("messages.password_requirements_not_met") }}');
                        return;
                    }
                    
                    // Validate password match
                    if (password !== confirmPassword) {
                        showError('{{ __("messages.passwords_do_not_match") }}');
                        return;
                    }
                    
                    // Show loading state
                    resetBtn.disabled = true;
                    submitBtnText.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        {{ __("messages.resetting_password") }}
                    `;
                    resetBtn.classList.add('opacity-75');
                    
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
                                showSuccess(data.message || '{{ __("messages.password_reset_success") }}');
                                
                                // Redirect to login after 2 seconds
                                setTimeout(() => {
                                    window.location.href = '{{ route("login") }}';
                                }, 2000);
                            } else {
                                showError(data.message || '{{ __("messages.reset_error") }}');
                            }
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                Object.values(data.errors).forEach(error => {
                                    showError(error[0]);
                                });
                            } else {
                                showError(data.message || '{{ __("messages.reset_error") }}');
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showError('{{ __("messages.network_error") }}');
                        
                        // Fallback to normal form submission
                        setTimeout(() => {
                            resetForm.submit();
                        }, 1000);
                    } finally {
                        // Re-enable button after 3 seconds
                        setTimeout(() => {
                            resetBtn.disabled = false;
                            submitBtnText.innerHTML = originalHTML;
                            resetBtn.classList.remove('opacity-75');
                        }, 3000);
                    }
                });
            }
            
            // Show session messages as toasts
            @if(session('status'))
                showSuccess('{{ session('status') }}');
            @endif
            
            @if(session('success'))
                showSuccess('{{ session('success') }}');
            @endif
            
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    showError('{{ $error }}');
                @endforeach
            @endif
            
            // Initialize animations
            document.querySelectorAll('.slide-in').forEach((el, index) => {
                el.style.animationDelay = `${(index + 1) * 0.1}s`;
                el.style.animationFillMode = 'forwards';
            });
        });
    </script>
</body>
</html>