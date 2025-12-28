@extends('layouts.auth')

@section('title', __('messages.login_title') . ' - Outfit 818')

@section('left-content')
    <!-- Welcome Quote -->
    <div class="mt-12 fade-in">
        <div class="border-l-4 border-white pl-4 py-2">
            <p class="text-gray-300 italic text-sm">
                {{ __('messages.welcome_quote') }}
            </p>
        </div>
    </div>
@endsection

@section('right-content')
    <!-- Form Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-primary-black mb-2 slide-in slide-in-1">
            {{ __('messages.welcome_back') }}
        </h1>
        <p class="text-gray-medium slide-in slide-in-2">
            {{ __('messages.sign_in_to_account') }}
        </p>
    </div>
    
    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        
        <!-- Email -->
        <div class="slide-in slide-in-2">
            <label class="block text-sm font-medium text-secondary-black mb-2">
                {{ __('messages.email_address') }}
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
        </div>
        
        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between slide-in slide-in-3">
            <label class="flex items-center text-sm text-gray-medium">
                <input type="checkbox" name="remember" class="custom-checkbox mr-2">
                {{ __('messages.remember_me') }}
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-secondary-black hover:underline font-medium">
                {{ __('messages.forgot_password') }}
            </a>
        </div>
        
        <!-- Submit Button -->
        <div class="slide-in slide-in-3">
            <button type="submit" 
                    class="w-full py-3 btn-primary font-semibold rounded-lg transition-all duration-300 group">
                <span class="flex items-center justify-center">
                    {{ __('messages.sign_in') }}
                    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </span>
            </button>
        </div>
        
        <!-- Register Link -->
        <div class="text-center mt-6 slide-in slide-in-3">
            <p class="text-sm text-gray-medium">
                {{ __('messages.no_account') }}
                <a href="{{ route('register') }}" class="text-secondary-black font-medium hover:underline ml-1">
                    {{ __('messages.create_account') }}
                </a>
            </p>
        </div>
    </form>
@endsection