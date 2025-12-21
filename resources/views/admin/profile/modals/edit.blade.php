<!-- Profile Edit Modal -->
<div id="profileEditModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div
        class="modal-content bg-white rounded-2xl shadow-lg w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Edit Profile</h3>
                    <p class="text-gray-600 mt-1">Update your personal information</p>
                </div>
                <button onclick="hideProfileModal()"
                    class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="profileUpdateForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="name" name="name" value="{{ $user->name }}" required
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="flex items-center p-3 bg-gray-100 rounded-xl">
                        <i class="fas fa-envelope text-gray-400 mr-3"></i>
                        <span class="font-medium text-gray-900">{{ $user->email }}</span>
                        @if ($user->email_verified_at)
                            <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                Verified
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="phone" name="phone" value="{{ $user->phone ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                            placeholder="+1 (555) 123-4567">
                    </div>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="dob" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <div class="relative">
                        <i
                            class="fas fa-birthday-cake absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="date" id="dob" name="dob"
                            value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                            max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <div class="relative">
                        <i
                            class="fas fa-venus-mars absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="gender" name="gender"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                            <option value="">Select Gender</option>
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-2">
                        Address Line 1
                    </label>
                    <div class="relative">
                        <i
                            class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="address_line1" name="address_line1"
                            value="{{ $primaryAddress->address_line1 ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                            placeholder="Street address, P.O. box, etc.">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-2">
                        Address Line 2
                    </label>
                    <div class="relative">
                        <i
                            class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="address_line2" name="address_line2"
                            value="{{ $primaryAddress->address_line2 ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                            placeholder="Apartment, suite, unit, building, floor, etc.">
                    </div>
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <div class="relative">
                        <i class="fas fa-city absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="city" name="city" value="{{ $primaryAddress->city ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <div class="relative">
                        <i class="fas fa-flag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="state" name="state" value="{{ $primaryAddress->state ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                    <div class="relative">
                        <i
                            class="fas fa-mail-bulk absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="zip_code" name="zip_code"
                            value="{{ $primaryAddress->zip_code ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <div class="relative">
                        <i class="fas fa-globe absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="country" name="country"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                            <option value="">Select Country</option>
                            <option value="Cambodia"
                                {{ ($primaryAddress->country ?? '') == 'Cambodia' ? 'selected' : '' }}>Cambodia
                            </option>
                            <option value="United States"
                                {{ ($primaryAddress->country ?? '') == 'United States' ? 'selected' : '' }}>United
                                States</option>
                            <option value="Canada"
                                {{ ($primaryAddress->country ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="United Kingdom"
                                {{ ($primaryAddress->country ?? '') == 'United Kingdom' ? 'selected' : '' }}>United
                                Kingdom</option>
                            <option value="Australia"
                                {{ ($primaryAddress->country ?? '') == 'Australia' ? 'selected' : '' }}>Australia
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Profile Picture -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                    <div class="flex items-center space-x-6">
                        <!-- Current Picture -->
                        <div class="relative">
                            @if ($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                                    class="w-24 h-24 rounded-xl object-cover border-2 border-gray-300">
                            @else
                                <div
                                    class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center border-2 border-gray-300">
                                    <i class="fas fa-user text-gray-500 text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Upload Controls -->
                        <div class="flex-1">
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                class="hidden" onchange="previewProfilePicture(this)">
                            <label for="profile_picture"
                                class="inline-block px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-lg cursor-pointer hover:shadow-lg transition-all duration-300 mb-2">
                                <i class="fas fa-upload mr-2"></i>Upload New Photo
                            </label>
                            @if ($user->profile_picture)
                                <div class="mt-2">
                                    <input type="checkbox" id="remove_profile_picture" name="remove_profile_picture"
                                        class="w-4 h-4 text-red-600 rounded focus:ring-red-500">
                                    <label for="remove_profile_picture"
                                        class="ml-2 text-sm text-red-600 cursor-pointer">
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
                    <div id="profilePicturePreview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <div class="w-32 h-32 rounded-xl overflow-hidden border-2 border-Ocean">
                            <img id="profilePreviewImage" class="w-full h-full object-cover" alt="Preview">
                        </div>
                    </div>
                </div>

                <!-- Newsletter Subscription -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="newsletter_opt_in" name="newsletter_opt_in"
                            class="w-4 h-4 text-Ocean rounded focus:ring-Ocean focus:ring-2"
                            {{ $user->newsletter_opt_in ? 'checked' : '' }}>
                        <label for="newsletter_opt_in" class="ml-2 text-sm font-medium text-gray-700">
                            Subscribe to newsletter updates
                        </label>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="hideProfileModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewProfilePicture(input) {
        const preview = document.getElementById('profilePicturePreview');
        const previewImage = document.getElementById('profilePreviewImage');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Show modal when clicking edit button
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for edit profile button
        document.querySelectorAll('[onclick*="showProfileModal"]').forEach(button => {
            button.addEventListener('click', showProfileModal);
        });
    });
</script>
