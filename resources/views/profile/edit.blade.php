@extends('layouts.front')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12 px-4 mt-10">
        <div class="max-w-5xl mx-auto">
            <!-- SweetAlert2 CDN (add if not already in layout) -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <!-- Session Messages will be handled by SweetAlert2 -->
            @if(session('success'))
                <div id="success-message" data-message="{{ session('success') }}"></div>
            @endif

            @if(session('password_success'))
                <div id="password-success-message" data-message="{{ session('password_success') }}"></div>
            @endif

            @if($errors->any())
                <div id="error-messages" data-messages="{{ json_encode($errors->all()) }}"></div>
            @endif

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('messages.edit_profile') }}</h1>
                <p class="text-gray-600">{{ __('messages.update_personal_info') }}</p>
            </div>

            <!-- Form Container -->
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-200">
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col lg:flex-row gap-8">
                    @csrf
                    @method('PUT')

                    <!-- Left Column - Form Fields -->
                    <div class="flex-1 space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="block font-semibold text-gray-900">{{ __('messages.full_name') }} *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                            @error('name')
                                <p class="text-red-600 text-sm font-medium flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email (readonly or with verification) -->
                        <div class="space-y-2">
                            <label class="block font-semibold text-gray-900">{{ __('messages.email_address') }}</label>
                            <div class="flex items-center space-x-2">
                                <input type="email" value="{{ $user->email }}" readonly
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            </div>
                            <p class="text-gray-500 text-sm">
                                {{ __('messages.change_email_info') }}
                            </p>
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="block font-semibold text-gray-900">{{ __('messages.phone_number') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                placeholder="+855 (12) 345-678">
                            @error('phone')
                                <p class="text-red-600 text-sm font-medium flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Date of Birth & Gender Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date of Birth -->
                            <div class="space-y-2">
                                <label class="block font-semibold text-gray-900">{{ __('messages.date_of_birth') }}</label>
                                <input type="date" name="dob" value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                                @error('dob')
                                    <p class="text-red-600 text-sm font-medium flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="space-y-2">
                                <label class="block font-semibold text-gray-900">{{ __('messages.gender') }}</label>
                                <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                                    <option value="">{{ __('messages.select_gender') }}</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                        {{ __('messages.male') }}
                                    </option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                        {{ __('messages.female') }}
                                    </option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>
                                        {{ __('messages.other') }}
                                    </option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-sm font-medium flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Newsletter Subscription -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="newsletter_opt_in" id="newsletter_opt_in" 
                                    value="1" {{ old('newsletter_opt_in', $user->newsletter_opt_in) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="newsletter_opt_in" class="ml-3 text-gray-900 font-medium">
                                    {{ __('messages.subscribe_newsletter') }}
                                </label>
                            </div>
                            <p class="text-gray-500 text-sm">{{ __('messages.newsletter_description') }}</p>
                        </div>
                    </div>

                    <!-- Right Column - Profile Picture -->
                    <div class="lg:w-80 flex flex-col items-center">
                        <div class="space-y-4 w-full">
                            <!-- Profile Picture Preview -->
                            <div class="relative group">
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ __('messages.profile_picture') }}"
                                        id="profilePreview"
                                        class="w-48 h-48 rounded-full object-cover border-4 border-white shadow-lg mx-auto transition-all duration-300 group-hover:brightness-75">
                                @else
                                    <div id="profilePreview"
                                        class="w-48 h-48 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 border-4 border-white mx-auto flex items-center justify-center shadow-lg transition-all duration-300 group-hover:brightness-75">
                                        <span class="text-4xl font-bold text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Camera Icon Overlay -->
                                <div
                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                                    <label for="profilePictureInput" class="cursor-pointer">
                                        <div
                                            class="bg-black bg-opacity-70 rounded-full p-4 text-white hover:bg-opacity-80 transition-all duration-200">
                                            <i class="fas fa-camera text-2xl"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Hidden File Input -->
                            <input type="file" id="profilePictureInput" name="profile_picture" accept="image/*"
                                class="hidden" onchange="previewImage(this)">

                            <!-- Choose Photo Button -->
                            <div class="text-center">
                                <label for="profilePictureInput"
                                    class="inline-flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition cursor-pointer">
                                    <i class="fas fa-camera"></i>
                                    {{ __('messages.change_photo') }}
                                </label>
                                <p class="text-gray-500 text-sm mt-2">{{ __('messages.max_file_size') }}</p>
                                @error('profile_picture')
                                    <p class="text-red-600 text-sm font-medium flex items-center gap-1 justify-center">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center pt-8 mt-8 border-t border-gray-200 space-y-4 sm:space-y-0">
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('messages.back_to_profile') }}
                    </a>
                    <div class="flex space-x-3">
                        <!-- Change Password Button - Opens Modal -->
                        <button type="button" onclick="openPasswordModal()"
                            class="flex items-center gap-2 px-6 py-3 border border-blue-500 text-blue-600 font-medium hover:bg-blue-50 rounded-lg transition hover:shadow-md">
                            <i class="fas fa-lock"></i>
                            {{ __('messages.change_password') }}
                        </button>
                        <button type="submit" form="profileForm"
                            class="flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition update-btn hover:shadow-lg">
                            <i class="fas fa-save"></i>
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95"
                id="passwordModalContent">
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-lock text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ __('messages.change_password') }}</h3>
                                <p class="text-gray-500 text-sm">{{ __('messages.update_password') }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="closePasswordModal()" 
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Password Form -->
                    <form id="passwordForm" action="{{ route('profile.update.password') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <!-- Current Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">{{ __('messages.current_password') }} *</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="currentPassword" required
                                    class="w-full px-4 py-3 pl-4 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                    placeholder="{{ __('messages.enter_current_password') }}">
                                <button type="button" onclick="togglePassword('currentPassword')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="currentPasswordEye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- New Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">{{ __('messages.new_password') }} *</label>
                            <div class="relative">
                                <input type="password" name="password" id="newPassword" required
                                    class="w-full px-4 py-3 pl-4 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                    placeholder="{{ __('messages.enter_new_password') }}">
                                <button type="button" onclick="togglePassword('newPassword')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="newPasswordEye"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full" id="lengthCheck"></div>
                                    <span class="text-xs text-gray-500">{{ __('messages.characters_8') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full" id="numberCheck"></div>
                                    <span class="text-xs text-gray-500">{{ __('messages.one_number') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full" id="lowerCheck"></div>
                                    <span class="text-xs text-gray-500">{{ __('messages.lowercase') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full" id="upperCheck"></div>
                                    <span class="text-xs text-gray-500">{{ __('messages.uppercase') }}</span>
                                </div>
                            </div>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Confirm New Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">{{ __('messages.confirm_new_password') }} *</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirmPassword" required
                                    class="w-full px-4 py-3 pl-4 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                    placeholder="{{ __('messages.confirm_password_placeholder') }}">
                                <button type="button" onclick="togglePassword('confirmPassword')"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="confirmPasswordEye"></i>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2 mt-2">
                                <div class="w-2 h-2 rounded-full" id="matchCheck"></div>
                                <span class="text-xs text-gray-500" id="matchText">{{ __('messages.passwords_match') }}</span>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                            <button type="button" onclick="closePasswordModal()" 
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition hover:shadow-sm">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit" id="passwordSubmitBtn"
                                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fas fa-lock"></i>
                                    <span>{{ __('messages.update_password_btn') }}</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('profilePreview');
            const file = input.files[0];

            if (file) {
                // Validate file type
                if (!file.type.match('image.*')) {
                    showAlert('{{ __("messages.error") }}', '{{ __("messages.invalid_image") }}', 'error');
                    input.value = '';
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showAlert('{{ __("messages.error") }}', '{{ __("messages.image_too_large") }}', 'error');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    // If it's the initial avatar, replace the entire div with an img
                    if (preview.tagName === 'DIV') {
                        const newPreview = document.createElement('img');
                        newPreview.id = 'profilePreview';
                        newPreview.className =
                            'w-48 h-48 rounded-full object-cover border-4 border-white mx-auto shadow-lg transition-all duration-300 group-hover:brightness-75';
                        newPreview.src = e.target.result;
                        newPreview.alt = '{{ __("messages.profile_picture") }}';

                        preview.parentNode.replaceChild(newPreview, preview);
                    } else {
                        // If it's already an image, just update the src
                        preview.src = e.target.result;
                    }
                    
                    showAlert('{{ __("messages.success") }}', '{{ __("messages.photo_selected") }}', 'success');
                };

                reader.onerror = function() {
                    showAlert('{{ __("messages.error") }}', '{{ __("messages.error_occurred") }}', 'error');
                    input.value = '';
                };

                reader.readAsDataURL(file);
            }
        }

        // SweetAlert2 helper function
        function showAlert(title, text, icon, callback = null) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '{{ __("messages.ok") }}',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg'
                },
                buttonsStyling: false
            }).then((result) => {
                if (callback && typeof callback === 'function') {
                    callback(result);
                }
            });
        }

        // Password visibility toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(inputId + 'Eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            const checks = {
                length: password.length >= 8,
                number: /\d/.test(password),
                lower: /[a-z]/.test(password),
                upper: /[A-Z]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            // Update visual indicators
            document.getElementById('lengthCheck').style.backgroundColor = checks.length ? '#10b981' : '#9ca3af';
            document.getElementById('numberCheck').style.backgroundColor = checks.number ? '#10b981' : '#9ca3af';
            document.getElementById('lowerCheck').style.backgroundColor = checks.lower ? '#10b981' : '#9ca3af';
            document.getElementById('upperCheck').style.backgroundColor = checks.upper ? '#10b981' : '#9ca3af';
            
            // Calculate strength
            const strength = Object.values(checks).filter(Boolean).length;
            return strength;
        }

        // Password match checker
        function checkPasswordMatch() {
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchCheck = document.getElementById('matchCheck');
            const matchText = document.getElementById('matchText');
            
            if (confirmPassword === '') {
                matchCheck.style.backgroundColor = '#9ca3af';
                matchText.textContent = '{{ __("messages.passwords_match") }}';
                matchText.className = 'text-xs text-gray-500';
                return false;
            }
            
            if (password === confirmPassword) {
                matchCheck.style.backgroundColor = '#10b981';
                matchText.textContent = '{{ __("messages.passwords_match") }} ✓';
                matchText.className = 'text-xs text-green-600';
                return true;
            } else {
                matchCheck.style.backgroundColor = '#ef4444';
                matchText.textContent = '{{ __("messages.passwords_not_match") }}';
                matchText.className = 'text-xs text-red-600';
                return false;
            }
        }

        // Validate form before submission
        function validatePasswordForm() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const submitBtn = document.getElementById('passwordSubmitBtn');
            
            const strength = checkPasswordStrength(newPassword);
            const match = checkPasswordMatch();
            
            const isValid = currentPassword && newPassword && confirmPassword && 
                           strength >= 3 && match && newPassword.length >= 8;
            
            submitBtn.disabled = !isValid;
            return isValid;
        }

        // Show session messages on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Show success message for profile update
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                showAlert('{{ __("messages.success") }}', successMessage.dataset.message, 'success');
            }

            // Show success message for password update
            const passwordSuccessMessage = document.getElementById('password-success-message');
            if (passwordSuccessMessage) {
                showAlert('{{ __("messages.success") }}', passwordSuccessMessage.dataset.message, 'success', function() {
                    closePasswordModal();
                });
            }

            // Show error messages
            const errorMessages = document.getElementById('error-messages');
            if (errorMessages) {
                const messages = JSON.parse(errorMessages.dataset.messages);
                if (messages.length > 0) {
                    const errorText = messages.join('<br>');
                    showAlert('{{ __("messages.error") }}', errorText, 'error');
                }
            }

            // Form submission handling with loading state
            const form = document.getElementById('profileForm');
            const updateBtn = document.querySelector('.update-btn');

            if (form && updateBtn) {
                form.addEventListener('submit', function(e) {
                    // Show loading state on button
                    updateBtn.disabled = true;
                    updateBtn.innerHTML = `
                        <i class="fas fa-spinner fa-spin"></i>
                        {{ __("messages.saving") }}
                    `;
                    
                    // Submit the form
                    return true;
                });
            }

            // Password form handling with AJAX
            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                // Real-time password validation
                document.getElementById('newPassword').addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                    checkPasswordMatch();
                    validatePasswordForm();
                });
                
                document.getElementById('confirmPassword').addEventListener('input', function() {
                    checkPasswordMatch();
                    validatePasswordForm();
                });
                
                document.getElementById('currentPassword').addEventListener('input', function() {
                    validatePasswordForm();
                });

                passwordForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    if (!validatePasswordForm()) {
                        showAlert('{{ __("messages.error") }}', '{{ __("messages.fix_errors") }}', 'error');
                        return;
                    }
                    
                    const submitBtn = document.getElementById('passwordSubmitBtn');
                    const originalText = submitBtn.innerHTML;
                    const formData = new FormData(this);
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <i class="fas fa-spinner fa-spin"></i>
                        {{ __("messages.updating") }}
                    `;

                    try {
                        const response = await fetch(this.action, {
                            method: 'PUT',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Success
                            showAlert('{{ __("messages.success") }}', data.message || '{{ __("messages.password_updated") }}', 'success', function() {
                                closePasswordModal();
                                passwordForm.reset();
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                                // Reset visual indicators
                                ['lengthCheck', 'numberCheck', 'lowerCheck', 'upperCheck', 'matchCheck'].forEach(id => {
                                    document.getElementById(id).style.backgroundColor = '#9ca3af';
                                });
                                document.getElementById('matchText').textContent = '{{ __("messages.passwords_match") }}';
                                document.getElementById('matchText').className = 'text-xs text-gray-500';
                            });
                        } else {
                            // Error
                            let errorMessage = '{{ __("messages.error_occurred") }}';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('<br>');
                            } else if (data.message) {
                                errorMessage = data.message;
                            }
                            
                            showAlert('{{ __("messages.error") }}', errorMessage, 'error');
                            
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    } catch (error) {
                        showAlert('{{ __("messages.error") }}', '{{ __("messages.network_error") }}', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        });

        // Password modal functions
        function openPasswordModal() {
            const modal = document.getElementById('passwordModal');
            const modalContent = document.getElementById('passwordModalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            // Reset form and focus
            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                passwordForm.reset();
                document.getElementById('currentPassword').focus();
                
                // Clear validation errors
                const errorMessages = passwordForm.querySelectorAll('.text-red-600');
                errorMessages.forEach(msg => msg.remove());
                
                // Reset visual indicators
                ['lengthCheck', 'numberCheck', 'lowerCheck', 'upperCheck', 'matchCheck'].forEach(id => {
                    document.getElementById(id).style.backgroundColor = '#9ca3af';
                });
                document.getElementById('matchText').textContent = '{{ __("messages.passwords_match") }}';
                document.getElementById('matchText').className = 'text-xs text-gray-500';
                
                // Reset eye icons
                ['currentPasswordEye', 'newPasswordEye', 'confirmPasswordEye'].forEach(id => {
                    const eye = document.getElementById(id);
                    if (eye) {
                        eye.classList.remove('fa-eye-slash');
                        eye.classList.add('fa-eye');
                    }
                });
                
                // Reset password fields to password type
                ['currentPassword', 'newPassword', 'confirmPassword'].forEach(id => {
                    const input = document.getElementById(id);
                    if (input) {
                        input.type = 'password';
                    }
                });
            }
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            const modalContent = document.getElementById('passwordModalContent');
            
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            modal.classList.add('opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('passwordModal');
            if (e.target === modal) {
                closePasswordModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePasswordModal();
            }
        });
    </script>

    <style>
        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            border-radius: 1rem !important;
            font-family: inherit !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }
        
        .swal2-confirm {
            padding: 0.75rem 2rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        
        .swal2-title {
            color: #1f2937 !important;
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem !important;
        }
        
        .swal2-html-container {
            color: #6b7280 !important;
            font-size: 1.05rem !important;
            line-height: 1.5 !important;
        }
        
        .swal2-icon.swal2-success {
            border-color: #10b981 !important;
            color: #10b981 !important;
        }
        
        .swal2-icon.swal2-error {
            border-color: #ef4444 !important;
            color: #ef4444 !important;
        }
        
        /* Password Modal Animations */
        #passwordModal {
            transition: opacity 0.3s ease;
        }
        
        #passwordModalContent {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        /* Password strength indicators */
        #lengthCheck, #numberCheck, #lowerCheck, #upperCheck, #matchCheck {
            transition: background-color 0.3s ease;
        }
        
        /* Form focus states */
        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        /* Button hover effects */
        .update-btn:hover, button[type="submit"]:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .update-btn:active, button[type="submit"]:active:not(:disabled) {
            transform: translateY(0);
        }
        
        /* Loading spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Gradient text for success */
        .text-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
@endpush