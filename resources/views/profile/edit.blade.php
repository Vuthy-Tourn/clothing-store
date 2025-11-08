@extends('layouts.front')

@section('content')
    <div class="min-h-screen py-12 px-6 mt-10">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Your Profile</h1>
                <p class="text-gray-900">Update your personal information</p>
            </div>

            <!-- Form Container -->
            <div class="p-8 rounded-2xl ">
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col lg:flex-row gap-8">
                    @csrf

                    <!-- Left Column - Form Fields -->
                    <div class="flex-1 space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="block font-semibold text-gray-900">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 focus:outline-none transition">
                            @error('name')
                                <p class="text-gray-900 text-sm font-medium flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="block font-semibold text-gray-900">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 focus:outline-none transition">
                            @error('phone')
                                <p class="text-gray-900 text-sm font-medium flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label class="block font-semibold text-gray-900">Address</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 focus:outline-none transition">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-gray-900 text-sm font-medium flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Date of Birth & Gender Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div x-data="datePicker('{{ old('dob', $user->dob) }}')" x-init="init()" class="space-y-2 relative w-[280px]">
                                <label class="block font-semibold text-gray-900">Date of Birth</label>

                                <!-- Input Field -->
                                <div class="relative">
                                    <input type="text" x-model="displayDate" @click="show = true" readonly
                                        placeholder="DD/MM/YYYY"
                                        class="w-full text-left px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-medium cursor-pointer focus:outline-none focus:border-gray-900">

                                    <div
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-900 pointer-events-none">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>

                                <!-- Calendar Dropdown -->
                                <div x-ref="calendar" x-show="show" @click.away="show = false"
                                    class="absolute mt-1 border border-gray-300 bg-white shadow-lg rounded-xl p-4 z-50 w-full"
                                    style="display: none;">
                                    <!-- Header -->
                                    <div class="flex justify-between items-center mb-3">
                                        <button type="button" @click="prevMonth()"
                                            class="p-1 hover:bg-gray-100 rounded-md">
                                            <i class="fas fa-chevron-left text-gray-700"></i>
                                        </button>
                                        <span class="font-semibold text-gray-900"
                                            x-text="monthNames[month] + ' ' + year"></span>
                                        <button type="button" @click="nextMonth()"
                                            class="p-1 hover:bg-gray-100 rounded-md">
                                            <i class="fas fa-chevron-right text-gray-700"></i>
                                        </button>
                                    </div>

                                    <!-- Weekdays -->
                                    <div class="grid grid-cols-7 text-center text-sm text-gray-600 mb-2 font-semibold">
                                        <template x-for="day in ['Su','Mo','Tu','We','Th','Fr','Sa']">
                                            <div x-text="day"></div>
                                        </template>
                                    </div>

                                    <!-- Dates -->
                                    <div class="grid grid-cols-7 text-center text-sm gap-1">
                                        <template x-for="blank in blanks">
                                            <div></div>
                                        </template>
                                        <template x-for="d in daysInMonth" :key="d">
                                            <button type="button" x-text="d" @click="selectDate(d)"
                                                :class="{
                                                    'bg-gray-900 text-white font-semibold rounded-md': isSelected(
                                                        d),
                                                    'hover:bg-gray-100 rounded-md': !isSelected(d)
                                                }"
                                                class="py-1 transition"></button>
                                        </template>
                                    </div>

                                    <!-- Today Button -->
                                    <div class="flex justify-end mt-2">
                                        <button type="button" @click="selectToday()"
                                            class="bg-gray-900 px-2 py-1 text-white rounded-sm text-xs hover:bg-gray-700">
                                            Today
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="dob" :value="selectedDate">
                            </div>



                            <!-- Custom Gender Dropdown -->
                            <div x-data="{ open: false, selected: '{{ old('gender', $user->gender) }}' }" class="space-y-2 relative">
                                <label class="block font-semibold text-gray-900">Gender</label>
                                <button type="button" @click="open = !open"
                                    class="w-full text-left px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-medium
                   focus:ring-1 focus:ring-gray-900 focus:border-gray-900 flex justify-between items-center">
                                    <span
                                        x-text="selected ? selected.charAt(0).toUpperCase() + selected.slice(1) : 'Select Gender'"></span>
                                    <i class="fas fa-chevron-down text-gray-900"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-md">
                                    <div @click="selected='male'; open=false"
                                        class="px-4 py-2 hover:bg-gray-100 text-gray-900 cursor-pointer">Male</div>
                                    <div @click="selected='female'; open=false"
                                        class="px-4 py-2 hover:bg-gray-100 text-gray-900 cursor-pointer">Female</div>
                                    <div @click="selected='other'; open=false"
                                        class="px-4 py-2 hover:bg-gray-100 text-gray-900 cursor-pointer">Other</div>
                                </div>

                                <input type="hidden" name="gender" :value="selected">
                                @error('gender')
                                    <p class="text-gray-900 text-sm font-medium flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>


                    </div>

                    <!-- Right Column - Profile Picture -->
                    <div class="lg:w-80 flex flex-col items-center">
                        <div class="space-y-4 w-full">
                            <!-- Profile Picture Preview -->
                            <div class="relative group">
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                                        id="profilePreview"
                                        class="w-48 h-48 rounded-full object-cover border-2 border-gray-900 mx-auto shadow-lg transition-all duration-300 group-hover:brightness-75">
                                @else
                                    <div id="profilePreview"
                                        class="w-48 h-48 rounded-full bg-gray-200 border-2 border-gray-900 mx-auto flex items-center justify-center shadow-lg transition-all duration-300 group-hover:brightness-75">
                                        <span class="text-4xl font-bold text-gray-900">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Camera Icon Overlay -->
                                <div
                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                                    <label for="profilePictureInput" class="cursor-pointer">
                                        <div
                                            class="bg-gray-900 bg-opacity-80 rounded-full p-4 text-white hover:bg-opacity-90 transition-all duration-200">
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
                                    Choose Photo
                                </label>
                            </div>

                            @error('profile_picture')
                                <p class="text-gray-900 text-sm font-medium flex items-center gap-1 justify-center">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </form>

                <!-- Buttons - Outside form but connected via form attribute -->
                <div class="flex justify-between items-center pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center gap-2 px-6 py-3 text-gray-900 font-medium hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-arrow-left"></i>
                        Cancel
                    </a>
                    <button type="submit" form="profileForm"
                        class="flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition update-btn">
                        <i class="fas fa-save"></i>
                        Update Profile
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* --- Custom Date of Birth Field --- */
        .custom-date-field {
            position: relative;
            font-size: 0.95rem;
            background-color: #fff;
        }

        /* Hide browser default calendar icon */
        .custom-date-field::-webkit-calendar-picker-indicator {
            background: transparent;
            color: transparent;
            position: absolute;
            right: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            opacity: 0;
        }

        /* Remove spin buttons and extra space */
        .custom-date-field::-webkit-inner-spin-button,
        .custom-date-field::-webkit-clear-button {
            display: none;
        }

        .custom-date-field::-webkit-datetime-edit {
            padding: 0;
            color: #111827;
            letter-spacing: 0.3px;
        }

        /* --- Custom Gender Select --- */
        .custom-gender-select {
            background-color: #fff;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-image: none;
        }

        .custom-gender-select option {
            color: #111827;
            background-color: #fff;
        }

        .custom-gender-select:hover {
            border-color: #111827;
        }

        .custom-gender-select:focus {
            border-color: #111827;
            box-shadow: 0 0 0 2px #11182733;
        }

        /* Remove the default dropdown arrow (for browsers like IE/Edge) */
        .custom-gender-select::-ms-expand {
            display: none;
        }

        /* Slight animation on focus */
        .custom-date-field:focus,
        .custom-gender-select:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }


        /* Enhanced button animations */
        .update-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .update-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .update-btn:hover:before {
            left: 100%;
        }

        /* Enhanced form field focus states */
        input:focus,
        select:focus,
        textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Profile picture hover enhancement */
        .group:hover .group-hover\:brightness-75 {
            filter: brightness(0.85);
        }

        /* Loading animation */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Ensure proper spacing for custom inputs */
        .custom-date-input,
        .custom-select-input {
            padding-right: 3rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('profilePreview');
            const file = input.files[0];

            if (file) {
                // Validate file type
                if (!file.type.match('image.*')) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast('Please select a valid image file');
                    }
                    return;
                }

                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast('Image size should be less than 5MB');
                    }
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    // If it's the initial avatar, replace the entire div with an img
                    if (preview.tagName === 'DIV') {
                        const newPreview = document.createElement('img');
                        newPreview.id = 'profilePreview';
                        newPreview.className =
                            'w-48 h-48 rounded-full object-cover border border-gray-900 mx-auto shadow-lg transition-all duration-300 group-hover:brightness-75';
                        newPreview.src = e.target.result;
                        newPreview.alt = 'Profile Preview';

                        preview.parentNode.replaceChild(newPreview, preview);
                    } else {
                        // If it's already an image, just update the src
                        preview.src = e.target.result;
                    }

                    // Show success toast
                    if (typeof showSuccessToast === 'function') {
                        showSuccessToast('Profile picture selected successfully');
                    }
                };

                reader.onerror = function() {
                    if (typeof showErrorToast === 'function') {
                        showErrorToast('Error reading the image file');
                    }
                };

                reader.readAsDataURL(file);
            }
        }

        // Form submission handling with loading state
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profileForm');
            const updateBtn = document.querySelector('.update-btn');

            if (form && updateBtn) {
                form.addEventListener('submit', function(e) {
                    // Show loading state on button
                    updateBtn.disabled = true;
                    updateBtn.innerHTML = `
                        <i class="fas fa-spinner fa-spin"></i>
                        Updating...
                    `;

                    // The form will submit normally and redirect to profile.show
                    // The success message will be shown on the profile page
                });
            }
        });

        // Format date display for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('.custom-date-input');

            // Format the date display if empty
            if (dateInput && !dateInput.value) {
                // You can add custom placeholder logic here if needed
            }
        });
    </script>
@endpush
