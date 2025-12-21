<form id="editUserForm" method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Basic Information</h4>
        </div>

        <!-- Name -->
        <div class="md:col-span-2">
            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                Full Name <span class="text-red-500">*</span>
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
                Email Address <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="email" id="edit_email" name="email" value="{{ $user->email }}" required
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
            </div>
        </div>

        <!-- Phone -->
        <div>
            <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <div class="relative">
                <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="edit_phone" name="phone" value="{{ $user->phone ?? '' }}"
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                       placeholder="+1 (555) 123-4567">
            </div>
        </div>

        <!-- Date of Birth -->
        <div>
            <label for="edit_dob" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
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
            <label for="edit_gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
            <div class="relative">
                <i class="fas fa-venus-mars absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="edit_gender" name="gender"
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">Select Gender</option>
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Account Type -->
        <div>
            <label for="edit_account_type" class="block text-sm font-medium text-gray-700 mb-2">
                Account Type <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <i class="fas fa-user-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select id="edit_account_type" name="account_type" required
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    @foreach($accountTypes as $type)
                        <option value="{{ $type }}" {{ $user->account_type == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="md:col-span-2">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Password (Leave blank to keep current)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="password" id="edit_password" name="password"
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                               placeholder="Minimum 8 characters">
                    </div>
                </div>
                <div>
                    <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
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
            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
            <div class="flex items-center space-x-6">
                <!-- Current Picture -->
                <div class="relative">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                             alt="Profile Picture"
                             class="w-20 h-20 rounded-xl object-cover border-2 border-gray-300">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-300">
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
                        <i class="fas fa-upload mr-2"></i>Upload New Photo
                    </label>
                    @if($user->profile_picture)
                    <div class="mt-2">
                        <input type="checkbox" id="edit_remove_profile_picture" name="remove_profile_picture" 
                               class="w-4 h-4 text-red-600 rounded focus:ring-red-500">
                        <label for="edit_remove_profile_picture" class="ml-2 text-sm text-red-600 cursor-pointer">
                            Remove current photo
                        </label>
                    </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">
                        Max file size: 5MB. Allowed: JPG, PNG, GIF, WEBP
                    </p>
                </div>
            </div>
            <!-- Preview Container -->
            <div id="editProfilePicturePreview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                <div class="w-32 h-32 rounded-xl overflow-hidden border-2 border-Ocean">
                    <img id="editProfilePreviewImage" class="w-full h-full object-cover" alt="Preview">
                </div>
            </div>
        </div>

        <!-- Status Options -->
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center">
                <input type="checkbox" id="edit_is_active" name="is_active" 
                       class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                       {{ $user->is_active ? 'checked' : '' }}>
                <label for="edit_is_active" class="ml-2 text-sm font-medium text-gray-700">
                    Active Account
                </label>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="edit_email_verified" name="email_verified" 
                       class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                       {{ $user->email_verified_at ? 'checked' : '' }}>
                <label for="edit_email_verified" class="ml-2 text-sm font-medium text-gray-700">
                    Email Verified
                </label>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="edit_newsletter_opt_in" name="newsletter_opt_in" 
                       class="w-4 h-4 text-Ocean rounded focus:ring-Ocean"
                       {{ $user->newsletter_opt_in ? 'checked' : '' }}>
                <label for="edit_newsletter_opt_in" class="ml-2 text-sm font-medium text-gray-700">
                    Newsletter Subscriber
                </label>
            </div>
        </div>

        <!-- Loyalty Points -->
        <div>
            <label for="edit_loyalty_points" class="block text-sm font-medium text-gray-700 mb-2">
                Loyalty Points
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
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
            <i class="fas fa-save mr-2"></i>Save Changes
        </button>
    </div>
</form>

<script>
function previewEditProfilePicture(input) {
    const preview = document.getElementById('editProfilePicturePreview');
    const previewImage = document.getElementById('editProfilePreviewImage');
    
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