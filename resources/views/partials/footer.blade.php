<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Column 1: Brand -->
            <div class="space-y-6 md:col-span-1">
                <a href="{{ url('/') }}"
                    class="text-3xl font-bold text-gray-900 tracking-tight hover:text-gray-800 transition-colors inline-block">
                    OUTFIT 818
                </a>
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
                <h4 class="text-lg font-bold text-gray-900 uppercase tracking-wide">{{ __('messages.stay_connected') }}</h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('messages.newsletter_description') }}
                </p>

                @auth
                    @php
                        $isSubscribed = \App\Models\NewsletterSubscription::where('email', auth()->user()->email)->exists();
                    @endphp

                    @if ($isSubscribed)
                        <div class="bg-gray-50 border-2 border-gray-900 rounded-lg p-4 space-y-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
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
                    © {{ date('Y') }} <span class="font-semibold text-gray-900">OUTFIT 818</span>. {{ __('messages.all_rights_reserved') }}
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