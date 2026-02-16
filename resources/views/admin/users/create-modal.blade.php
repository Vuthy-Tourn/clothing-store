<!-- Create User Form -->
<form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data"
    class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.users.modal.basic_info') }}</h4>
        </div>

        <!-- Name -->
        <div class="md:col-span-2">
            <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.full_name') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_name" name="name" value="{{ old('name') }}" required
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="create_email" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.email') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="email" id="create_email" name="email" value="{{ old('email') }}" required
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Phone -->
        <div>
            <label for="create_phone"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.phone') }}</label>
            <div class="relative">
                <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_phone" name="phone" value="{{ old('phone') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.phone_placeholder') }}">
            </div>
        </div>

        <!-- Date of Birth -->
        <div>
            <label for="create_dob"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.date_of_birth') }}</label>
            <div class="relative">
                <i class="fas fa-birthday-cake absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="date" id="create_dob" name="dob" value="{{ old('dob') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    max="{{ date('Y-m-d') }}">
            </div>
        </div>

        <!-- Gender -->
        <div>
            <label for="create_gender"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.gender') }}</label>
            <div class="relative">
                <i class="fas fa-venus-mars absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="create_gender" name="gender"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">{{ __('admin.users.modal.gender_select') }}</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                        {{ __('admin.users.modal.gender_male') }}</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                        {{ __('admin.users.modal.gender_female') }}</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                        {{ __('admin.users.modal.gender_other') }}</option>
                </select>
            </div>
        </div>

        <!-- Account Type -->
        <div>
            <label for="create_account_type" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.account_type') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-user-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="create_account_type" name="account_type" required
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">{{ __('admin.users.modal.account_type_select') }}</option>
                    @foreach ($accountTypes as $type)
                        <option value="{{ $type }}" {{ old('account_type') == $type ? 'selected' : '' }}>
                            {{ __('admin.users.account_types.' . $type) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.users.modal.password_info') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="create_password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('admin.users.modal.password') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="create_password" name="password" required
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                            placeholder="{{ __('admin.users.modal.password_placeholder') }}">
                    </div>
                </div>
                <div>
                    <label for="create_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('admin.users.modal.password_confirm') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="create_password_confirmation" name="password_confirmation" required
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.users.modal.primary_address') }}</h4>
        </div>

        <!-- Address Type -->
        <div>
            <label for="create_address_type"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.address_type') }}</label>
            <div class="relative">
                <i class="fas fa-home absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="create_address_type" name="address_type"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="shipping" {{ old('address_type') == 'shipping' ? 'selected' : 'selected' }}>
                        {{ __('admin.users.modal.address_type_shipping') }}</option>
                    <option value="billing" {{ old('address_type') == 'billing' ? 'selected' : '' }}>
                        {{ __('admin.users.modal.address_type_billing') }}</option>
                </select>
            </div>
        </div>

        <!-- Address Name -->
        <div>
            <label for="create_address_name"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.address_name') }}</label>
            <div class="relative">
                <i class="fas fa-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_address_name" name="address_name"
                    value="{{ old('address_name', 'Primary Address') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.address_name_placeholder') }}">
            </div>
        </div>

        <!-- Address Line 1 -->
        <div class="md:col-span-2">
            <label for="create_address_line1"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.address_line1') }}</label>
            <div class="relative">
                <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_address_line1" name="address_line1"
                    value="{{ old('address_line1') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.address_line1_placeholder') }}">
            </div>
        </div>

        <!-- Address Line 2 -->
        <div class="md:col-span-2">
            <label for="create_address_line2"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.address_line2') }}</label>
            <div class="relative">
                <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_address_line2" name="address_line2"
                    value="{{ old('address_line2') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                    placeholder="{{ __('admin.users.modal.address_line2_placeholder') }}">
            </div>
        </div>

        <!-- City -->
        <div>
            <label for="create_city"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.city') }}</label>
            <div class="relative">
                <i class="fas fa-city absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_city" name="city" value="{{ old('city') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- State -->
        <div>
            <label for="create_state"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.state') }}</label>
            <div class="relative">
                <i class="fas fa-map absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_state" name="state" value="{{ old('state') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- ZIP Code -->
        <div>
            <label for="create_zip_code"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.zip_code') }}</label>
            <div class="relative">
                <i class="fas fa-mail-bulk absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_zip_code" name="zip_code" value="{{ old('zip_code') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Country -->
        <div>
            <label for="create_country"
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.country') }}</label>
            <div class="relative">
                <i class="fas fa-globe absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="create_country" name="country"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="United States" {{ old('country') == 'United States' ? 'selected' : 'selected' }}>
                        United States</option>
                    <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United
                        Kingdom</option>
                    <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                    <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                    <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                    <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                </select>
            </div>
        </div>

        <!-- Make Default Address -->
        <div class="md:col-span-2">
            <div class="flex items-center">
                <input type="checkbox" id="create_is_default_address" name="is_default_address" value="1"
                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" checked>
                <label for="create_is_default_address" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.is_default_address') }}
                </label>
            </div>
        </div>

        <!-- Profile Picture -->
        <div class="md:col-span-2">
            <label
                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.profile_picture') }}</label>
            <div class="flex items-center space-x-6">
                <!-- Placeholder Picture -->
                <div class="relative">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-300">
                        <i class="fas fa-user text-gray-500 text-xl"></i>
                    </div>
                </div>

                <!-- Upload Controls -->
                <div class="flex-1">
                    <input type="file" id="create_profile_picture" name="profile_picture" accept="image/*"
                        class="hidden" onchange="previewCreateProfilePicture(this)">
                    <label for="create_profile_picture"
                        class="inline-block px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-lg cursor-pointer hover:shadow-lg transition-all duration-300 mb-2">
                        <i class="fas fa-upload mr-2"></i>{{ __('admin.users.modal.upload_photo_create') }}
                    </label>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ __('admin.users.modal.photo_formats') }}
                    </p>
                </div>
            </div>
            <!-- Preview Container -->
            <div id="createProfilePicturePreview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.modal.preview') }}</p>
                <div class="w-32 h-32 rounded-xl overflow-hidden border-2 border-Ocean">
                    <img id="createProfilePreviewImage" class="w-full h-full object-cover"
                        alt="{{ __('admin.users.modal.preview') }}">
                </div>
            </div>
        </div>

        <!-- Status Options -->
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center">
                <input type="checkbox" id="create_is_active" name="is_active" value="1"
                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500" checked>
                <label for="create_is_active" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.active_account') }}
                </label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="create_email_verified" name="email_verified" value="1"
                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" checked>
                <label for="create_email_verified" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.email_verified') }}
                </label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="create_newsletter_opt_in" name="newsletter_opt_in" value="1"
                    class="w-4 h-4 text-Ocean rounded focus:ring-Ocean" checked>
                <label for="create_newsletter_opt_in" class="ml-2 text-sm font-medium text-gray-700">
                    {{ __('admin.users.modal.newsletter_subscriber') }}
                </label>
            </div>
        </div>

        <!-- Loyalty Points -->
        <div>
            <label for="create_loyalty_points" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('admin.users.modal.initial_loyalty_points') }}
            </label>
            <div class="relative">
                <i class="fas fa-star absolute left-4 top-1/2 transform -translate-y-1/2 text-yellow-500"></i>
                <input type="number" id="create_loyalty_points" name="loyalty_points" value="0"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="mt-8 flex justify-end space-x-3">
        <button type="button" onclick="hideCreateModal()"
            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
            {{ __('admin.users.modal.cancel') }}
        </button>
        <button type="submit"
            class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
            <i class="fas fa-plus mr-2"></i>{{ __('admin.users.modal.create') }}
        </button>
    </div>
</form>

<script>
    function previewCreateProfilePicture(input) {
        const preview = document.getElementById('createProfilePicturePreview');
        const previewImage = document.getElementById('createProfilePreviewImage');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Handle create form submission
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __('admin.users.messages.creating') }}';
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
                    window.location.reload();
                } else {
                    // Error
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // Show validation errors if any
                    if (data.errors) {
                        // Clear previous errors
                        form.querySelectorAll('.text-red-500').forEach(el => el.remove());
                        form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove(
                            'border-red-500'));

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
                alert('{{ __('admin.users.messages.error_occurred') }}');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });
</script>
