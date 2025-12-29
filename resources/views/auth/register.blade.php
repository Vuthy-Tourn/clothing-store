@extends('layouts.auth')

@section('title', __('messages.create_account') . ' - Outfit 818')

@section('left-content')
    <!-- Welcome Quote -->
    <div class="mt-12 fade-in">
        <div class="border-l-4 border-white pl-4 py-2">
            <p class="text-gray-300 italic text-sm">
                {{ __('messages.style_quote') }}
            </p>
        </div>
    </div>
@endsection

@section('right-content')
    <!-- Form Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-primary-black mb-2 slide-in slide-in-1">
            {{ __('messages.create_account') }}
        </h1>
        <p class="text-gray-medium slide-in slide-in-2">
            {{ __('messages.join_community') }}
        </p>
    </div>
    
    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Profile Picture -->
        <div class="text-center slide-in slide-in-1">
            <div class="relative inline-block">
                <div class="w-20 h-20 rounded-full border-3 border-white shadow-md overflow-hidden bg-gray-100 flex items-center justify-center">
                    <div id="profile-preview" class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                </div>
                <label for="profile_picture" class="absolute bottom-0 right-0 w-8 h-8 bg-black rounded-full flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-300 shadow-md">
                    <i class="fas fa-camera text-white text-xs"></i>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden" onchange="previewProfilePicture(event)">
                </label>
            </div>
        </div>
        
        <!-- Name & Email Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Name -->
            <div class="slide-in slide-in-2">
                <label class="block text-sm font-medium text-secondary-black mb-2">
                    {{ __('messages.full_name') }}
                </label>
                <div class="relative">
                    <input type="text" name="name" value="{{ old('name') }}" 
                        class="form-input w-full px-4 py-3 pl-10 rounded-lg @error('name') border-red-500 @enderror"
                        placeholder="{{ __('messages.name_placeholder') }}"
                        required>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-user text-gray-light"></i>
                    </div>
                </div>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div class="slide-in slide-in-2">
                <label class="block text-sm font-medium text-secondary-black mb-2">
                    {{ __('messages.email') }}
                </label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" 
                        class="form-input w-full px-4 py-3 pl-10 rounded-lg @error('email') border-red-500 @enderror"
                        placeholder="{{ __('messages.email_placeholder') }}"
                        required>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-envelope text-gray-light"></i>
                    </div>
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Phone -->
        <div class="slide-in slide-in-3">
            <label class="block text-sm font-medium text-secondary-black mb-2">
                {{ __('messages.phone_number') }}
            </label>
            <div class="relative">
                <input type="tel" name="phone" value="{{ old('phone') }}" 
                    class="form-input w-full px-4 py-3 pl-10 rounded-lg @error('phone') border-red-500 @enderror"
                    placeholder="{{ __('messages.phone_placeholder') }}"
                    required>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fas fa-phone text-gray-light"></i>
                </div>
            </div>
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Password Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div class="slide-in slide-in-3">
                <label class="block text-sm font-medium text-secondary-black mb-2">
                    {{ __('messages.password') }}
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="form-input w-full px-4 py-3 pl-10 pr-10 rounded-lg @error('password') border-red-500 @enderror"
                        placeholder="{{ __('messages.password_placeholder') }}"
                        required>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-lock text-gray-light"></i>
                    </div>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('password')">
                        <i class="fas fa-eye text-gray-light hover:text-secondary-black transition-colors"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">{{ __('messages.password_requirements') }}</p>
            </div>
            
            <!-- Confirm Password -->
            <div class="slide-in slide-in-4">
                <label class="block text-sm font-medium text-secondary-black mb-2">
                    {{ __('messages.confirm_password') }}
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-input w-full px-4 py-3 pl-10 rounded-lg"
                        placeholder="{{ __('messages.password_placeholder') }}"
                        required>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-lock text-gray-light"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Info Button -->
        <div class="slide-in slide-in-4">
            <button type="button" onclick="toggleAdditionalInfo()" 
                    class="w-full flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-300">
                <span class="font-medium text-secondary-black text-sm">
                    <i class="fas fa-plus mr-2"></i>{{ __('messages.additional_info') }}
                </span>
                <i id="additional-arrow" class="fas fa-chevron-down text-gray-medium transition-transform duration-300"></i>
            </button>
            
            <!-- Additional Info Fields -->
            <div id="additional-info" class="hidden space-y-4 mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-secondary-black mb-1">
                            {{ __('messages.date_of_birth') }}
                        </label>
                        <input type="date" name="dob" value="{{ old('dob') }}" 
                            class="form-input w-full px-3 py-2 text-sm rounded">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-secondary-black mb-1">
                            {{ __('messages.gender') }}
                        </label>
                        <select name="gender" 
                            class="form-input w-full px-3 py-2 text-sm rounded">
                            <option value="">{{ __('messages.select_gender') }}</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                {{ __('messages.male') }}
                            </option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                {{ __('messages.female') }}
                            </option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                {{ __('messages.other') }}
                            </option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-secondary-black mb-1">
                        {{ __('messages.address') }}
                    </label>
                    <textarea name="address" rows="2" 
                        class="form-input w-full px-3 py-2 text-sm rounded"
                        placeholder="{{ __('messages.address_placeholder') }}">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Terms Agreement -->
        <div class="slide-in slide-in-4">
            <label class="flex items-start text-sm text-gray-medium">
                <input type="checkbox" name="terms" 
                    class="custom-checkbox mr-2 mt-0.5" required>
                <span>{{ __('messages.terms_agreement') }}</span>
            </label>
        </div>
        
        <!-- Submit Button -->
        <div class="slide-in slide-in-5">
            <button type="submit" 
                    class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300 group">
                <span class="flex items-center justify-center">
                    {{ __('messages.create_account') }}
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </span>
            </button>
        </div>
        
        <!-- Login Link -->
        <div class="text-center mt-6 slide-in slide-in-5">
            <p class="text-sm text-gray-medium">
                {{ __('messages.have_account') }}
                <a href="{{ route('login') }}" class="text-secondary-black font-medium hover:underline ml-1">
                    {{ __('messages.sign_in') }}
                </a>
            </p>
        </div>
    </form>
@endsection