
<form id="editUserForm" method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data"
    class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.users.modal.basic_info') }}</h4>
        </div>

        <!-- Name -->
        <div class="md:col-span-2">
            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.full_name') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_name" name="name" value="{{ $user->name }}" required
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.email') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="email" id="edit_email" name="email" value="{{ $user->email }}" required
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Phone -->
        <div>
            <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.phone') }}</label>
            <div class="relative">
                <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_phone" name="phone" value="{{ $user->phone ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.phone_placeholder') }}">
            </div>
        </div>

        <!-- Date of Birth -->
        <div>
            <label for="edit_dob" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.date_of_birth') }}</label>
            <div class="relative">
                <i class="fas fa-birthday-cake absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="date" id="edit_dob" name="dob"
                    value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    max="{{ date('Y-m-d') }}">
            </div>
        </div>

        <!-- Gender -->
        <div>
            <label for="edit_gender" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.gender') }}</label>
            <div class="relative">
                <i class="fas fa-venus-mars absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="edit_gender" name="gender"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">{{ __('admin.users.modal.gender_select') }}</option>
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>{{ __('admin.users.modal.gender_male') }}</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>{{ __('admin.users.modal.gender_female') }}</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>{{ __('admin.users.modal.gender_other') }}</option>
                </select>
            </div>
        </div>

        <!-- Account Type -->
        <div>
            <label for="edit_account_type" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.account_type') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-user-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="edit_account_type" name="account_type" required
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    @foreach ($accountTypes as $type)
                        <option value="{{ $type }}" {{ $user->account_type == $type ? 'selected' : '' }}>
                            {{ __('admin.users.account_types.' . $type) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Address Information -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.users.modal.address_info') }}</h4>
        </div>

        <!-- Address Type -->
        <div>
            <label for="edit_address_type" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.address_type') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="edit_address_type" name="address_type"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="shipping"
                        {{ $primaryAddress && $primaryAddress->type == 'shipping' ? 'selected' : 'selected' }}>
                        {{ __('admin.users.modal.address_type_shipping') }}
                    </option>
                    <option value="billing"
                        {{ $primaryAddress && $primaryAddress->type == 'billing' ? 'selected' : '' }}>
                        {{ __('admin.users.modal.address_type_billing') }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Address Name -->
        <div>
            <label for="edit_address_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.address_name') }}</label>
            <div class="relative">
                <i class="fas fa-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_address_name" name="address_name"
                    value="{{ $primaryAddress->address_name ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.address_name_placeholder') }}">
            </div>
        </div>

        <!-- Address Line 1 -->
        <div class="md:col-span-2">
            <label for="edit_address_line1" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.address_line1') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-road absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_address_line1" name="address_line1"
                    value="{{ $primaryAddress->address_line1 ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.address_line1_placeholder') }}">
            </div>
        </div>

        <!-- Address Line 2 -->
        <div class="md:col-span-2">
            <label for="edit_address_line2" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.address_line2') }}</label>
            <div class="relative">
                <i class="fas fa-building absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_address_line2" name="address_line2"
                    value="{{ $primaryAddress->address_line2 ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.address_line2_placeholder') }}">
            </div>
        </div>

        <!-- City -->
        <div>
            <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.city') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-city absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_city" name="city" value="{{ $primaryAddress->city ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- State -->
        <div>
            <label for="edit_state" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.state') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-landmark absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_state" name="state" value="{{ $primaryAddress->state ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Zip Code -->
        <div>
            <label for="edit_zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.zip_code') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-mail-bulk absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_zip_code" name="zip_code"
                    value="{{ $primaryAddress->zip_code ?? '' }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Country -->
        <div>
            <label for="edit_country" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.country') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-globe absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="edit_country" name="country"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="United States"
                        {{ ($primaryAddress->country ?? 'United States') == 'United States' ? 'selected' : 'selected' }}>
                        United States
                    </option>
                    <option value="Canada" {{ ($primaryAddress->country ?? '') == 'Canada' ? 'selected' : '' }}>Canada
                    </option>
                    <option value="United Kingdom"
                        {{ ($primaryAddress->country ?? '') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom
                    </option>
                    <option value="Australia" {{ ($primaryAddress->country ?? '') == 'Australia' ? 'selected' : '' }}>
                        Australia</option>
                    <option value="Germany" {{ ($primaryAddress->country ?? '') == 'Germany' ? 'selected' : '' }}>
                        Germany</option>
                    <option value="France" {{ ($primaryAddress->country ?? '') == 'France' ? 'selected' : '' }}>France
                    </option>
                    <option value="Japan" {{ ($primaryAddress->country ?? '') == 'Japan' ? 'selected' : '' }}>Japan
                    </option>
                    <option value="India" {{ ($primaryAddress->country ?? '') == 'India' ? 'selected' : '' }}>India
                    </option>
                    <option value="Other">{{ __('admin.users.modal.other') }}</option>
                </select>
            </div>
        </div>

        <!-- Is Default Address -->
        <div class="md:col-span-2 flex items-center">
            <input type="hidden" name="is_default_address" value="0">
            <input type="checkbox" id="edit_is_default_address" name="is_default_address" value="1"
                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                {{ $primaryAddress && $primaryAddress->is_default ? 'checked' : 'checked' }}>
            <label for="edit_is_default_address" class="ml-2 text-sm font-medium text-gray-700">
                {{ __('admin.users.modal.is_default_address') }}
            </label>
        </div>

        <!-- Password -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.users.modal.password_info_edit') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.password') }}</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="edit_password" name="password"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                            placeholder="{{ __('admin.users.modal.password_placeholder') }}">
                    </div>
                </div>
                <div>
                    <label for="edit_password_confirmation"
                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.password_confirm') }}</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="edit_password_confirmation" name="password_confirmation"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Picture -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.profile_picture') }}</label>
            <div class="flex items-center space-x-6">
                <!-- Current Picture -->
                <div class="relative">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ __('admin.users.modal.current_picture') }}"
                            class="w-20 h-20 rounded-xl object-cover border-2 border-gray-300">
                        <!-- Hidden current picture reference for preview -->
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                             id="currentProfilePicture" 
                             class="hidden" 
                             alt="{{ __('admin.users.modal.current_picture') }}">
                    @else
                        <div id="currentProfilePicturePlaceholder"
                            class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-300">
                            <i class="fas fa-user text-gray-500 text-xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Upload Controls -->
                <div class="flex-1">
                    <input type="file" id="edit_profile_picture" name="profile_picture" accept="image/*" 
                           class="hidden" onchange="previewEditProfilePicture(this)">
                    <label for="edit_profile_picture"
                           class="inline-block px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-lg cursor-pointer hover:shadow-lg transition-all duration-300 mb-2">
                        <i class="fas fa-upload mr-2"></i>{{ __('admin.users.modal.upload_photo') }}
                    </label>
                    @if ($user->profile_picture)
                        <div class="mt-2">
                            <input type="checkbox" id="edit_remove_profile_picture" name="remove_profile_picture" 
                                   class="w-4 h-4 text-red-600 rounded focus:ring-red-500">
                            <label for="edit_remove_profile_picture" class="ml-2 text-sm text-red-600 cursor-pointer">
                                {{ __('admin.users.modal.remove_photo') }}
                            </label>
                        </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">
                        {{ __('admin.users.modal.photo_formats') }}
                    </p>
                </div>
            </div>
            <!-- Preview Container -->
            <div id="editProfilePicturePreview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.preview') }}</p>
                <div class="w-32 h-32 rounded-xl overflow-hidden border-2 border-Ocean">
                    <img id="editProfilePreviewImage" class="w-full h-full object-cover" alt="{{ __('admin.users.modal.preview') }}">
                </div>
            </div>
        </div>

        <!-- Status Options -->
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="edit_is_active" name="is_active" value="1"
                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                    {{ $user->is_active ? 'checked' : '' }}>
                <label for="edit_is_active" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.active_account') }}
                </label>
            </div>

            <div class="flex items-center">
                <input type="hidden" name="email_verified" value="0">
                <input type="checkbox" id="edit_email_verified" name="email_verified" value="1"
                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                    {{ $user->email_verified_at ? 'checked' : '' }}>
                <label for="edit_email_verified" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.email_verified') }}
                </label>
            </div>

            <div class="flex items-center">
                <input type="hidden" name="newsletter_opt_in" value="0">
                <input type="checkbox" id="edit_newsletter_opt_in" name="newsletter_opt_in" value="1"
                    class="w-4 h-4 text-Ocean rounded focus:ring-Ocean"
                    {{ $user->newsletter_opt_in ? 'checked' : '' }}>
                <label for="edit_newsletter_opt_in" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.newsletter_subscriber') }}
                </label>
            </div>
        </div>

        <!-- Hidden field for is_verified -->
        <input type="hidden" name="is_verified" value="{{ $user->is_verified }}">

        <!-- Loyalty Points -->
        <div>
            <label for="edit_loyalty_points" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.loyalty_points') }}
            </label>
            <div class="relative">
                <i class="fas fa-star absolute left-4 top-1/2 transform -translate-y-1/2 text-yellow-500"></i>
                <input type="number" id="edit_loyalty_points" name="loyalty_points"
                    value="{{ $user->loyalty_points }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="mt-8 flex justify-end space-x-3">
        <button type="button" onclick="hideEditModal()"
            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
            {{ __('admin.users.modal.cancel') }}
        </button>
        <button type="submit"
            class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
            <i class="fas fa-save mr-2"></i>{{ __('admin.users.modal.update') }}
        </button>
    </div>
</form>

<script>
// Function for edit form profile preview (same as create form)
function previewEditProfilePicture(input) {
    const preview = document.getElementById('editProfilePicturePreview');
    const previewImage = document.getElementById('editProfilePreviewImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
            
            // Hide current picture placeholder if it exists
            const currentPlaceholder = document.getElementById('currentProfilePicturePlaceholder');
            if (currentPlaceholder) {
                currentPlaceholder.classList.add('hidden');
            }
            
            // Hide current picture if it exists
            const currentPicture = document.querySelector('.relative img:not(#editProfilePreviewImage)');
            if (currentPicture && !currentPicture.id.includes('currentProfilePicture')) {
                currentPicture.classList.add('hidden');
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Handle remove profile picture checkbox
const removeCheckbox = document.getElementById('edit_remove_profile_picture');
if (removeCheckbox) {
    removeCheckbox.addEventListener('change', function() {
        const preview = document.getElementById('editProfilePicturePreview');
        const currentPicture = document.querySelector('.relative img:not(#editProfilePreviewImage)');
        const currentPlaceholder = document.getElementById('currentProfilePicturePlaceholder');
        
        if (this.checked) {
            // Hide preview and current picture when removing
            if (preview) preview.classList.add('hidden');
            if (currentPicture) currentPicture.classList.add('hidden');
            if (currentPlaceholder) currentPlaceholder.classList.add('hidden');
        } else {
            // Show current picture when not removing
            if (currentPicture) currentPicture.classList.remove('hidden');
            if (currentPlaceholder) currentPlaceholder.classList.remove('hidden');
        }
    });
}

// Handle form submission via AJAX
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("admin.users.messages.saving") }}';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success - show message and reload
            alert(data.message || '{{ __("admin.users.messages.success_updated") }}');
            window.location.reload();
        } else {
            // Error
            alert(data.message || '{{ __("admin.users.messages.error_failed_message") }}');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Show validation errors if any
            if (data.errors) {
                // Clear previous errors
                form.querySelectorAll('.text-red-500').forEach(el => el.remove());
                form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
                
                Object.keys(data.errors).forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('border-red-500');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-red-500 text-sm mt-1';
                        errorDiv.textContent = data.errors[field][0];
                        input.parentNode.appendChild(errorDiv);
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("admin.users.messages.error_occurred") }}');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>

