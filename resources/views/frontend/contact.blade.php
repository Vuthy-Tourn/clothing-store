@extends('layouts.front')
@section('title', __('messages.contact_us') . ' - Nova Studio')
@section('content')

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    {{ __('messages.get_in_touch') }}
                </h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    {{ __('messages.contact_subtitle') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Contact Info & Hours Grid -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-12">
                <!-- Contact Information Card -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-gray-900 p-3 rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 ml-4">{{ __('messages.contact_information') }}</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start group">
                            <div class="bg-gray-100 p-3 rounded-lg group-hover:bg-gray-900 transition-colors duration-300">
                                <svg class="h-6 w-6 text-gray-900 group-hover:text-white transition-colors duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 mb-1">{{ __('messages.email') }}</p>
                                <a href="mailto:support@novastudio.com"
                                    class="text-gray-900 font-medium hover:text-gray-600 transition-colors">
                                    support@novastudio.com
                                </a>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="bg-gray-100 p-3 rounded-lg group-hover:bg-gray-900 transition-colors duration-300">
                                <svg class="h-6 w-6 text-gray-900 group-hover:text-white transition-colors duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 mb-1">{{ __('messages.phone') }}</p>
                                <a href="tel:+15551234567"
                                    class="text-gray-900 font-medium hover:text-gray-600 transition-colors">
                                    +1 (555) 123-4567
                                </a>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="bg-gray-100 p-3 rounded-lg group-hover:bg-gray-900 transition-colors duration-300">
                                <svg class="h-6 w-6 text-gray-900 group-hover:text-white transition-colors duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 mb-1">{{ __('messages.address') }}</p>
                                <p class="text-gray-900 font-medium">123 Fashion St, Style City, SC 12345</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Hours Card -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="bg-gray-900 p-3 rounded-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 ml-4">{{ __('messages.store_hours') }}</h3>
                    </div>
                    <div class="space-y-4">
                        <div
                            class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-gray-700 font-medium">{{ __('messages.monday_friday') }}</span>
                            <span class="text-gray-900 font-bold">9:00 AM - 6:00 PM</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-gray-700 font-medium">{{ __('messages.saturday') }}</span>
                            <span class="text-gray-900 font-bold">10:00 AM - 4:00 PM</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="text-gray-700 font-medium">{{ __('messages.sunday') }}</span>
                            <span class="text-red-600 font-bold">{{ __('messages.closed') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 md:p-12 text-center shadow-xl mb-12">
                <h3 class="text-3xl font-bold text-white mb-4">{{ __('messages.connect_with_us') }}</h3>
                <p class="text-gray-300 text-lg mb-8">{{ __('messages.social_subtitle') }}</p>
                <div class="flex justify-center space-x-6">
                    <a href="https://instagram.com/novastudio" target="_blank"
                        class="bg-white/10 backdrop-blur-sm p-4 rounded-full hover:bg-white hover:scale-110 transition-all duration-300 group">
                        <svg class="h-7 w-7 text-white group-hover:text-gray-900 transition-colors" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.326 3.608 1.301.975.975 1.24 2.242 1.301 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.326 2.633-1.301 3.608-.975.975-2.242 1.24-3.608 1.301-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.326-3.608-1.301-.975-.975-1.24-2.242-1.301-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.326-2.633 1.301-3.608.975-.975 2.242-1.24 3.608-1.301 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-1.632.074-3.082.414-4.242 1.574-1.16 1.16-1.5 2.61-1.574 4.242-.058 1.28-.072 1.688-.072 4.947s.014 3.667.072 4.947c.074 1.632.414 3.082 1.574 4.242 1.16 1.16 2.61 1.5 4.242 1.574 1.28.058 1.688.072 4.947.072s3.667-.014 4.947-.072c1.632-.074 3.082-.414 4.242-1.574 1.16-1.16 1.5-2.61 1.574-4.242.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.074-1.632-.414-3.082-1.574-4.242-1.16-1.16-2.61-1.5-4.242-1.574-1.28-.058-1.688-.072-4.947-.072zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z" />
                        </svg>
                    </a>
                    <a href="https://x.com/novastudio" target="_blank"
                        class="bg-white/10 backdrop-blur-sm p-4 rounded-full hover:bg-white hover:scale-110 transition-all duration-300 group">
                        <svg class="h-7 w-7 text-white group-hover:text-gray-900 transition-colors" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </a>
                    <a href="https://pinterest.com/novastudio" target="_blank"
                        class="bg-white/10 backdrop-blur-sm p-4 rounded-full hover:bg-white hover:scale-110 transition-all duration-300 group">
                        <svg class="h-7 w-7 text-white group-hover:text-gray-900 transition-colors" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 0C5.373 0 0 5.373 0 12c0 5.085 3.333 9.406 8 10.922.37.066.495-.16.495-.356 0-.176-.006-.72-.01-1.414-3.243.706-3.928-1.563-3.928-1.563-.53-1.346-1.296-1.704-1.296-1.704-1.058-.723.08-.708.08-.708 1.17.082 1.784 1.2 1.784 1.2 1.038 1.778 2.724 1.264 3.39.968.105-.753.406-1.264.742-1.553-2.595-.295-5.322-1.297-5.322-5.774 0-1.276.456-2.32 1.203-3.137-.12-.296-.523-1.486.115-3.098 0 0 .982-.313 3.22 1.203.934-.26 1.934-.39 2.93-.395 1 .005 2.003.135 2.938.395 2.235-1.516 3.215-1.203 3.215-1.203.64 1.612.237 2.802.117 3.098.75.817 1.2 1.861 1.2 3.137 0 4.488-2.73 5.474-5.33 5.764.42.362.795 1.078.795 2.173 0 1.568-.015 2.834-.015 3.22 0 .197.123.426.498.355C20.667 21.406 24 17.085 24 12c0-6.627-5.373-12-12-12z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Newsletter CTA -->
            <div class="bg-white rounded-2xl p-8 md:p-12 text-center shadow-lg border border-gray-100 mb-12">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-gray-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('messages.stay_updated') }}</h3>
                    <p class="text-gray-600 text-lg mb-8">{{ __('messages.newsletter_subtitle') }}</p>
                    <a href="{{ url('/') }}#emails"
                        class="inline-flex items-center px-8 py-4 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        {{ __('messages.sign_up_now') }}
                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Brand Mission -->
            <div
                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8 md:p-12 text-center border border-gray-200">
                <div class="max-w-3xl mx-auto">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-900 rounded-full mb-6">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('messages.our_mission') }}</h3>
                    <p class="text-gray-700 text-lg leading-relaxed">
                        {{ __('messages.mission_text') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

@endsection
