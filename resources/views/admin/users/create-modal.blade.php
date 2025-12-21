<form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Basic Information</h4>
        </div>

        <!-- Name -->
        <div class="md:col-span-2">
            <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">
                Full Name <span class="text-red-500">*</span>
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
                Email Address <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="email" id="create_email" name="email" value="{{ old('email') }}" required
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Phone -->
        <div>
            <label for="create_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <div class="relative">
                <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="create_phone" name="phone" value="{{ old('phone') }}"
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                       placeholder="+1 (555) 123-4567">
            </div>
        </div>

        <!-- Date of Birth -->
        <div>
            <label for="create_dob" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
            <div class="relative">
                <i class="fas fa-birthday-cake absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="date" id="create_dob" name="dob" value="{{ old('dob') }}"
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                       max="{{ date('Y-m-d') }}">
            </div>
        </div>

        <!-- Gender -->
        <div>
            <label for="create_gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
            <div class="relative">
                <i class="fas fa-venus-mars absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="create_gender" name="gender"
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Account Type -->
        <div>
            <label for="create_account_type" class="block text-sm font-medium text-gray-700 mb-2">
                Account Type <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-user-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="create_account_type" name="account_type" required
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">Select Type</option>
                    @foreach($accountTypes as $type)
                        <option value="{{ $type }}" {{ old('account_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Password</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="create_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="create_password" name="password" required
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                               placeholder="Minimum 8 characters">
                    </div>
                </div>
                <div>
                    <label for="create_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="create_password_confirmation" name="password_confirmation" required
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Picture -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
            <div class="flex items-center space-x-6">
                <!-- Placeholder Picture -->
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-300">
                        <i class="fas fa-user text-gray-500 text-xl"></i>
                    </div>
                </div>

                <!-- Upload Controls -->
                <div class="flex-1">
                    <input type="file" id="create_profile_picture" name="profile_picture" accept="image/*" 
                           class="hidden" onchange="previewCreateProfilePicture(this)">
                    <label for="create_profile_picture"
                           class="inline-block px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-lg cursor-pointer hover:shadow-lg transition-all duration-300 mb-2">
                        <i class="fas fa-upload mr-2"></i>Upload Photo
                    </label>
                    <p class="text-xs text-gray-500 mt-2">
                        Max file size: 5MB. Allowed: JPG, PNG, GIF, WEBP
                    </p>
                </div>
            </div>
            <!-- Preview Container -->
            <div id="createProfilePicturePreview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                <div class="w-32 h-32 rounded-xl overflow-hidden border-2 border-Ocean">
                    <img id="createProfilePreviewImage" class="w-full h-full object-cover" alt="Preview">
                </div>
            </div>
        </div>

        <!-- Status Options -->
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center">
                <input type="checkbox" id="create_is_active" name="is_active" value="1"
                       class="w-4 h-4 text-green-600 rounded focus:ring-green-500" checked>
                <label for="create_is_active" class="ml-2 text-sm font-medium text-gray-700">
                    Active Account
                </label>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="create_email_verified" name="email_verified" value="1"
                       class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" checked>
                <label for="create_email_verified" class="ml-2 text-sm font-medium text-gray-700">
                    Email Verified
                </label>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="create_newsletter_opt_in" name="newsletter_opt_in" value="1"
                       class="w-4 h-4 text-Ocean rounded focus:ring-Ocean" checked>
                <label for="create_newsletter_opt_in" class="ml-2 text-sm font-medium text-gray-700">
                    Newsletter Subscriber
                </label>
            </div>
        </div>

        <!-- Loyalty Points -->
        <div>
            <label for="create_loyalty_points" class="block text-sm font-medium text-gray-700 mb-2">
                Initial Loyalty Points
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
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
            <i class="fas fa-plus mr-2"></i>Create User
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
</script>