<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Column 1: Brand -->
            <div class="space-y-4">
                <a href="{{ url('/') }}"
                    class="text-2xl font-bold text-gray-900 tracking-tight hover:text-gray-700 transition-colors">OUTFIT
                    818</a>
                <p class="text-gray-600 text-sm max-w-xs">Your everyday fashion destination. Discover the latest trends
                    and timeless styles for every occasion.</p>
                <div class="flex space-x-4">
                    <a href="https://github.com/Nisarg-Vekariya" target="_blank"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fab fa-github text-xl"></i>
                    </a>
                    <a href="https://www.instagram.com/not_real_gladiator" target="_blank"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="https://x.com/NiradPatel0303" target="_blank"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2: Navigation -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-900">Quick Links</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="/men"
                            class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">Men</a>
                    </li>
                    <li>
                        <a href="/women"
                            class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">Women</a>
                    </li>
                    <li>
                        <a href="{{ route('products.all') }}"
                            class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">Products</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}"
                            class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Contact/Newsletter -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-900">Stay Updated</h4>
                <p class="text-gray-600 text-sm">Subscribe to get notified about our latest collections and exclusive
                    offers.</p>

                @auth
                    @php
                        $isSubscribed = \App\Models\Email::where('email', auth()->user()->email)->exists();
                    @endphp

                    @if ($isSubscribed)
                        <!-- Already subscribed -->
                        <div
                            class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm flex justify-between items-center">
                            <span class="text-green-700">You're already subscribed!</span>
                            <button onclick="showUnsubscribeModal()"
                                class="text-red-600 hover:underline">Unsubscribe</button>

                        </div>
                    @else
                        <input type="email" name="email" value="{{ auth()->user()->email }}" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed text-sm focus:outline-none"
                            required>
                        <button onclick="showSubscribeModal()"
                            class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">
                            Subscribe
                        </button>
                    @endif
                @else
                    <!-- Guest state -->
                    <form class="space-y-2">
                        <input type="email" placeholder="Enter your email" required disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed text-sm focus:outline-none">
                        <a href="{{ route('login') }}"
                            class="block w-full bg-gray-900 text-center text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium">
                            Login to Subscribe
                        </a>
                    </form>
                @endauth
            </div>

        </div>

        <!-- Bottom Bar -->
        <div
            class="border-t border-gray-200 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p class="text-gray-600 text-sm">© {{ date('Y') }} OUTFIT 818. All rights reserved.</p>
            <div class="flex space-x-6 text-sm">
                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Privacy Policy</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Terms of Service</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Returns</a>
            </div>
        </div>
    </div>
</footer>
