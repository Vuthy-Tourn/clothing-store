<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Column 1: Brand -->
            <div class="space-y-6 md:col-span-1">
                <div
                    class="px-6 py-6 border-b border-gray-200/50 bg-gradient-to-r from-white to-Ocean/5 relative overflow-hidden">
                    <!-- Background accent -->
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-Ocean/5 rounded-full blur-xl"></div>

                    <div class="flex items-center space-x-4 relative">
                        <!-- logo container -->
                        <div class="logo-container relative group">
                            <!-- Logo with multiple animations -->
                            <div class="logo-glow relative overflow-hidden rounded-lg p-1.5">
                                <div class="logo-glow relative overflow-hidden rounded-lg p-1.5">
                        <a href="/">
                        <img src="{{ asset('assets/images/logo1.png') }}" alt="Nova Studio"
                            class="h-7 w-auto object-contain transition-all duration-500 group-hover:scale-105"
                            style="filter: drop-shadow(0 4px 8px rgba(88, 104, 121, 0.15));" />
                            </a>
    
                    </div>
                            </div>

                            <!-- "STUDIO" text with animation -->
                            <div class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 w-full">
                                <div
                                    class="studio-text text-center text-[10px] font-semibold uppercase tracking-[0.15em] text-gray-500/80 opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:translate-y-0 translate-y-1">
                                    STUDIO
                                    <!-- Underline animation -->
                                    <div
                                        class="h-px bg-gradient-to-r from-transparent via-Ocean/30 to-transparent w-0 group-hover:w-full transition-all duration-700 mx-auto mt-0.5">
                                    </div>
                                </div>
                            </div>

                            <!-- Floating particles animation -->
                            <div
                                class="absolute -inset-2 -z-10 opacity-0 group-hover:opacity-30 transition-opacity duration-700">
                                <div class="absolute top-1/4 left-1/4 w-1 h-1 bg-Ocean/30 rounded-full animate-float-1">
                                </div>
                                <div
                                    class="absolute top-1/3 right-1/4 w-0.5 h-0.5 bg-Ocean/20 rounded-full animate-float-2">
                                </div>
                            </div>
                        </div>
                        <!-- Close button with hover animation -->
                        <button id="closeSidebar"
                            class="lg:hidden text-gray-500 hover:text-Ocean p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                            <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                        </button>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('messages.footer_tagline') }}
                </p>
                <div class="flex space-x-4">
                    <a href="https://github.com/Nisarg-Vekariya" target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-900 hover:text-white transition-all duration-300">
                        <i class="fab fa-github text-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/not_real_gladiator" target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-900 hover:text-white transition-all duration-300">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="https://x.com/NiradPatel0303" target="_blank"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-900 hover:text-white transition-all duration-300">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2: Shop -->
            <div class="space-y-6">
                <h4 class="text-lg font-bold text-gray-900 uppercase tracking-wide">{{ __('messages.shop') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="/men"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.men') }}
                        </a>
                    </li>
                    <li>
                        <a href="/women"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.women') }}
                        </a>
                    </li>
                    <li>
                        <a href="/kids"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.kids') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.all') }}"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.all_products') }}
                        </a>
                    </li>

                </ul>
            </div>

            <!-- Column 3: Support -->
            <div class="space-y-6">
                <h4 class="text-lg font-bold text-gray-900 uppercase tracking-wide">{{ __('messages.support') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('contact') }}"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.contact_us') }}
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.shipping_info') }}
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.returns') }}
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-gray-600 hover:text-gray-900 hover:translate-x-1 inline-block transition-all duration-200 text-sm">
                            {{ __('messages.faq') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Newsletter -->
            <div class="space-y-6">
                <h4 class="text-lg font-bold text-gray-900 uppercase tracking-wide">{{ __('messages.stay_connected') }}
                </h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('messages.newsletter_description') }}
                </p>

                @auth
                    @php
                        $isSubscribed = \App\Models\NewsletterSubscription::where(
                            'email',
                            auth()->user()->email,
                        )->exists();
                    @endphp

                    @if ($isSubscribed)
                        <div class="bg-gray-50 border-2 border-gray-900 rounded-lg p-4 space-y-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-900 font-semibold text-sm">{{ __('messages.subscribed') }}</span>
                            </div>
                            <button onclick="showUnsubscribeModal()"
                                class="text-gray-600 hover:text-gray-900 text-xs underline">
                                {{ __('messages.unsubscribe') }}
                            </button>
                        </div>
                    @else
                        <div class="space-y-3">
                            <input type="email" name="email" value="{{ auth()->user()->email }}" readonly
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 text-sm focus:outline-none focus:border-gray-900 transition-colors"
                                required>
                            <button onclick="showSubscribeModal()"
                                class="w-full bg-gray-900 text-white px-4 py-3 rounded-lg hover:bg-gray-800 transition-all duration-300 text-sm font-semibold uppercase tracking-wide shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                {{ __('messages.subscribe') }}
                            </button>
                        </div>
                    @endif
                @else
                    <div class="space-y-3">
                        <input type="email" placeholder="{{ __('messages.enter_email') }}" disabled
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-400 text-sm focus:outline-none">
                        <a href="{{ route('login') }}"
                            class="block w-full bg-gray-900 text-center text-white px-4 py-3 rounded-lg hover:bg-gray-800 transition-all duration-300 text-sm font-semibold uppercase tracking-wide shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            {{ __('messages.login_to_subscribe') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-200 mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} <span class="font-semibold text-gray-900">Nova Studio</span>.
                    {{ __('messages.all_rights_reserved') }}
                </p>
                <div class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">
                        {{ __('messages.privacy_policy') }}
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">
                        {{ __('messages.terms_of_service') }}
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors font-medium">
                        {{ __('messages.cookie_policy') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
