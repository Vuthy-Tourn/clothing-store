<nav class="navbar fixed top-0 left-0 right-0 z-50 transition-all duration-500 transform -translate-y-full"
    id="mainNavbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="shrink-0">
                <a href="/" class="text-2xl font-bold text-gray-900 tracking-tight">OUTFIT 818</a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <!-- Main Categories with Mega Dropdowns -->
                <!-- Men Category -->
                @php
                    $menCategories = $categories->where('gender', 'men')->sortBy('sort_order');
                @endphp
                @if ($menCategories->count() > 0)
                    <div class="relative group" id="menCategory" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <a href="{{ url('men') }}"
                            class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 relative z-10 {{ request()->is('men') || request()->is('men/*') ? 'text-gray-900 font-semibold border-b-2 border-gray-900' : '' }}">
                            {{ __('messages.men') }}
                        </a>
                        <!-- Mega Dropdown with invisible spacer -->
                        <div class="absolute left-0 w-full h-4" style="top: 100%;"></div>
                        <!-- Mega Dropdown -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-4 w-screen max-w-6xl bg-white shadow-lg rounded-lg z-50 border border-gray-100"
                            style="left: 50%; transform: translateX(-40%);">
                            <div class="px-8 py-6 grid grid-cols-3 gap-8">
                                <!-- Clothing Section -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                        {{ __('messages.clothing') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach ($menCategories->whereIn('name', ['Shirts', 'T-Shirts', 'Pants']) as $category)
                                            <li>
                                                <a href="{{ url('men/?category=' . $category->slug) }}"
                                                    class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>

                                <!-- Additional categories for Men -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                        {{ __('messages.more_collections') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach ($menCategories->whereNotIn('name', ['Shirts', 'T-Shirts', 'Pants'])->take(8) as $category)
                                            <li>
                                                <a href="{{ url('category/' . $category->slug) }}"
                                                    class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <!-- Add more default items if needed -->
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.jeans') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.shorts') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.jackets') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.suits') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.activewear') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Featured Section -->
                                <div class="bg-blue-50 rounded-lg p-4 hover:bg-blue-100 transition-colors">
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">
                                        {{ __('messages.mens_featured') }}
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-3">
                                        {{ __('messages.check_out_mens_collection') }}
                                    </p>
                                    <a href="{{ url('men') }}"
                                        class="inline-block bg-gray-900 text-white px-4 py-2 text-xs font-medium rounded hover:bg-gray-800 transition-colors">
                                        {{ __('messages.shop_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Women Category -->
                @php
                    $womenCategories = $categories->where('gender', 'women')->sortBy('sort_order');
                @endphp
                @if ($womenCategories->count() > 0)
                    <div class="relative group" id="womenCategory" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <a href="{{ url('women') }}"
                            class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 relative z-10 {{ request()->is('women') || request()->is('women/*') ? 'text-gray-900 font-semibold border-b-2 border-gray-900' : '' }}">
                            {{ __('messages.women') }}
                        </a>
                        <!-- Invisible spacer to bridge the gap -->
                        <div class="absolute left-0 w-full h-4" style="top: 100%;"></div>
                        <!-- Mega Dropdown -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-4 w-screen max-w-6xl bg-white shadow-lg rounded-lg z-50 border border-gray-100"
                            style="left: 50%; transform: translateX(-40%);">
                            <div class="px-8 py-6 grid grid-cols-3 gap-8">
                                <!-- Clothing Section -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                        {{ __('messages.clothing') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach ($womenCategories->whereIn('name', ['Dresses', 'Tops', 'T-Shirts']) as $category)
                                            <li>
                                                <a href="{{ url('women/?category=' . $category->slug) }}"
                                                    class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>

                                <!-- Additional categories for Women -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                        {{ __('messages.more_collections') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach ($womenCategories->whereNotIn('name', ['Dresses', 'Tops', 'T-Shirts'])->take(8) as $category)
                                            <li>
                                                <a href="{{ url('category/' . $category->slug) }}"
                                                    class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <!-- Add more default items if needed -->
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.jeans') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.skirts') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.activewear') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.sweaters') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.jumpsuits') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Featured Section -->
                                <div class="bg-pink-50 rounded-lg p-4 hover:bg-pink-100 transition-colors">
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">
                                        {{ __('messages.womens_new_arrivals') }}
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-3">
                                        {{ __('messages.discover_latest_trends_women') }}
                                    </p>
                                    <a href="{{ url('women') }}"
                                        class="inline-block bg-gray-900 text-white px-4 py-2 text-xs font-medium rounded hover:bg-gray-800 transition-colors">
                                        {{ __('messages.explore') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Kids Category -->
                @php
                    $kidsCategories = $categories->where('gender', 'unisex')->sortBy('sort_order');
                @endphp
                @if ($kidsCategories->count() > 0)
                    <div class="relative group" id="kidsCategory" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <a href="{{ url('kids') }}"
                            class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 relative z-10 {{ request()->is('kids') || request()->is('kids/*') ? 'text-gray-900 font-semibold border-b-2 border-gray-900' : '' }}">
                            {{ __('messages.kids') }}
                        </a>
                        <!-- Invisible spacer to bridge the gap -->
                        <div class="absolute left-0 w-full h-4" style="top: 100%;"></div>
                        <!-- Mega Dropdown -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-4 w-screen max-w-6xl bg-white shadow-lg rounded-lg z-50 border border-gray-100"
                            style="left: 50%; transform: translateX(-40%);">
                            <div class="px-8 py-6 grid grid-cols-3 gap-8">
                                <!-- Boys Section -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                        {{ __('messages.boys') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach ($kidsCategories->where('name', 'T-Shirts') as $category)
                                            <li>
                                                <a href="{{ url('kids/?category=' . $category->slug) }}"
                                                    class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.pants_jeans') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.jackets') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.shoes') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Girls Section -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                        {{ __('messages.girls') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.dresses') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.tops') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.leggings') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="text-gray-600 hover:text-gray-900 text-sm block py-1 hover:bg-gray-50 rounded px-2 transition-colors">
                                                {{ __('messages.shoes') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Featured Section -->
                                <div class="bg-purple-50 rounded-lg p-4 hover:bg-purple-100 transition-colors">
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">
                                        {{ __('messages.kids_collection') }}
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-3">
                                        {{ __('messages.get_ready_for_school') }}
                                    </p>
                                    <a href="{{ url('kids') }}"
                                        class="inline-block bg-gray-900 text-white px-4 py-2 text-xs font-medium rounded hover:bg-gray-800 transition-colors">
                                        {{ __('messages.shop_collection') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @auth
                    <a href="{{ route('orders.index') }}"
                        class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 {{ request()->routeIs('orders.*') ? 'text-gray-900 font-semibold border-b-2 border-gray-900' : '' }}">
                        {{ __('messages.orders') }}
                    </a>

                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200 {{ request()->routeIs('admin.*') ? 'text-gray-900 font-semibold border-b-2 border-gray-900' : '' }}">
                            {{ __('messages.admin') }}
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Icons Section -->
            <div class="flex items-center space-x-4">
                @include('components.language-switcher')
                <!-- Search Icon -->
                <button id="searchButton" class="p-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Cart Icon -->
                <a href="{{ route('cart') }}"
                    class="p-2 text-gray-600 hover:text-gray-900 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @php
                        $cartCount = \App\Models\CartItem::where(
                            'user_id',
                            auth()->id() ?? session()->get('cart_id'),
                        )->count();
                    @endphp
                    @if ($cartCount > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>


                @auth
                    <!-- User Dropdown with Profile Picture -->
                    <div class="relative">
                        <button id="userDropdownButton"
                            class="flex items-center focus:outline-none transition-all duration-200 hover:scale-105">
                            @if (auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile"
                                    class="w-8 h-8 rounded-full object-cover border-2 border-gray-300 hover:border-gray-400 transition-all duration-200">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center border-2 border-gray-300 hover:border-gray-400 transition-all duration-200">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </button>
                        <div id="userDropdown"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-md py-2 z-50 hidden border border-gray-100">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Dropdown Items -->
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group {{ request()->routeIs('profile.show') ? 'bg-gray-50 text-gray-900' : '' }}">
                                <i
                                    class="fas fa-user mr-3 text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                {{ __('messages.my_profile') }}
                            </a>
                            <a href="{{ route('orders.index') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group {{ request()->routeIs('orders.*') ? 'bg-gray-50 text-gray-900' : '' }}">
                                <i
                                    class="fas fa-shopping-bag mr-3 text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                {{ __('messages.my_orders') }}
                            </a>
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group">
                                        <i
                                            class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                        {{ __('messages.sign_out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login/Signup for mobile -->
                    <div class="md:hidden">
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">
                            {{ __('messages.login') }}
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton"
                    class="md:hidden p-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 py-4">
            <div class="space-y-2 px-4">
                <a href="{{ url('men') }}" class="block py-2 text-gray-700 hover:text-gray-900">
                    {{ __('messages.men') }}
                </a>
                <a href="{{ url('women') }}" class="block py-2 text-gray-700 hover:text-gray-900">
                    {{ __('messages.women') }}
                </a>
                <a href="{{ url('kids') }}" class="block py-2 text-gray-700 hover:text-gray-900">
                    {{ __('messages.kids') }}
                </a>

                <!-- Mobile Search -->
                <div class="py-2 border-t border-gray-200 mt-2 pt-4">
                    <form action="{{ route('products.search') }}" method="GET" class="relative">
                        <input type="text" name="q" placeholder="{{ __('messages.search_products') }}"
                            class="w-full px-4 py-2 pl-10 pr-4 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <button type="submit"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                @auth
                    <a href="{{ route('orders.index') }}" class="block py-2 text-gray-700 hover:text-gray-900">
                        {{ __('messages.orders') }}
                    </a>
                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-gray-700 hover:text-gray-900">
                            {{ __('messages.admin') }}
                        </a>
                    @endif

                    <!-- Mobile User Info -->
                    <div class="px-3 py-2 border-t border-gray-200 mt-2 pt-3">
                        <div class="flex items-center space-x-3 mb-3">
                            @if (auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center border-2 border-gray-300">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ route('profile.show') }}"
                                class="block text-sm text-gray-700 hover:text-gray-900 py-1">
                                <i class="fas fa-user mr-2 text-gray-400"></i> {{ __('messages.my_profile') }}
                            </a>
                            <a href="{{ route('orders.index') }}"
                                class="block text-sm text-gray-700 hover:text-gray-900 py-1">
                                <i class="fas fa-shopping-bag mr-2 text-gray-400"></i> {{ __('messages.my_orders') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left text-sm text-gray-700 hover:text-gray-900 py-1">
                                    <i class="fas fa-sign-out-alt mr-2 text-gray-400"></i> {{ __('messages.sign_out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Mobile Login/Signup -->
                    <div class="border-t border-gray-200 mt-2 pt-4 space-y-2">
                        <a href="{{ route('login') }}" class="block text-gray-700 hover:text-gray-900 py-2">
                            {{ __('messages.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="block text-gray-700 hover:text-gray-900 py-2">
                            {{ __('messages.register') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Enhanced Search Modal -->
<div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300">
    <div class="flex items-start justify-center min-h-screen p-4 pt-20">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[80vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0"
            id="searchModalContent">
            <div class="h-full flex flex-col">
                <!-- Header -->
                <div class="flex-shrink-0 p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.search_products') }}</h2>
                        <button id="closeSearchModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Search Form -->
                    <form id="searchForm" class="relative">
                        <input type="text" name="q" id="searchInput"
                            placeholder="{{ __('messages.search_placeholder') }}"
                            class="w-full px-6 py-4 text-lg bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-gray-900 focus:ring-2 focus:ring-gray-900 focus:ring-opacity-50 transition-all duration-200"
                            autocomplete="off">
                        <button type="submit"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white p-3 rounded-lg hover:bg-gray-800 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Quick Filters -->
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">{{ __('messages.quick_filters') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" data-filter="new_arrivals"
                                class="quick-filter px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                {{ __('messages.new_arrivals') }}
                            </button>
                            <button type="button" data-filter="featured"
                                class="quick-filter px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium hover:bg-green-200 transition-colors">
                                {{ __('messages.featured') }}
                            </button>
                            <button type="button" data-filter="men"
                                class="quick-filter px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">
                                {{ __('messages.men') }}
                            </button>
                            <button type="button" data-filter="women"
                                class="quick-filter px-4 py-2 bg-pink-100 text-pink-700 rounded-full text-sm font-medium hover:bg-pink-200 transition-colors">
                                {{ __('messages.women') }}
                            </button>
                            <button type="button" data-filter="kids"
                                class="quick-filter px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium hover:bg-purple-200 transition-colors">
                                {{ __('messages.kids') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results Container -->
                <div class="flex-grow overflow-hidden">
                    <div class="h-full flex flex-col">
                        <!-- Recent Searches (Initial State) -->
                        <div id="recentSearchesContainer" class="p-6 overflow-y-auto">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">{{ __('messages.recent_searches') }}
                            </h3>
                            <div class="flex flex-wrap gap-2 mb-6" id="recentSearchesList">
                                <!-- Recent searches will be populated here -->
                            </div>

                            <!-- Popular Searches -->
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                                {{ __('messages.popular_searches') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" data-query="T-Shirts"
                                    class="popular-search px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                    T-Shirts
                                </button>
                                <button type="button" data-query="Jeans"
                                    class="popular-search px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                    Jeans
                                </button>
                                <button type="button" data-query="Dresses"
                                    class="popular-search px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                    Dresses
                                </button>
                                <button type="button" data-query="Jackets"
                                    class="popular-search px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                    Jackets
                                </button>
                                <button type="button" data-query="Shoes"
                                    class="popular-search px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                    Shoes
                                </button>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div id="searchLoading" class="hidden p-8 flex-col items-center justify-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mb-4"></div>
                            <p class="text-gray-600">{{ __('messages.searching_products') }}</p>
                        </div>

                        <!-- Empty State -->
                        <div id="searchEmpty" class="hidden p-8 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ __('messages.no_products_found') }}</h3>
                            <p class="text-gray-600">{{ __('messages.try_different_keywords') }}</p>
                        </div>

                        <!-- Error State -->
                        <div id="searchError" class="hidden p-8 text-center">
                            <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.search_error') }}
                            </h3>
                            <p class="text-gray-600">{{ __('messages.please_try_again') }}</p>
                        </div>

                        <!-- Search Results -->
                        <div id="searchResultsContainer" class="hidden flex-grow overflow-hidden">
                            <!-- Results Header -->
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ __('messages.search_results') }}
                                        </h3>
                                        <p class="text-sm text-gray-600" id="resultsCount">0
                                            {{ __('messages.results') }}</p>
                                    </div>
                                    <a href="{{ route('products.search') }}" id="seeAllResults"
                                        class="text-sm font-medium text-gray-900 hover:text-gray-700 transition-colors hidden">
                                        {{ __('messages.see_all_results') }} →
                                    </a>
                                </div>
                            </div>

                            <!-- Products Grid -->
                            <div id="searchResults" class="p-6 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Products will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden">
    <!-- Quick view content will be loaded here -->
</div>

<!-- Add Alpine.js CDN in your layout if not already included -->
<script src="//unpkg.com/alpinejs" defer></script>

<script>
    // User dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const userDropdownButton = document.getElementById('userDropdownButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userDropdownButton && userDropdown) {
            userDropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
                userDropdown.classList.toggle('animate-fadeIn');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userDropdownButton.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                    userDropdown.classList.remove('animate-fadeIn');
                }
            });

            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !userDropdown.classList.contains('hidden')) {
                    userDropdown.classList.add('hidden');
                    userDropdown.classList.remove('animate-fadeIn');
                }
            });
        }

        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('animate-slideDown');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target) && !
                    mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('animate-slideDown');
                }
            });
        }

        // Enhanced Search Modal functionality
        const searchButton = document.getElementById('searchButton');
        const searchModal = document.getElementById('searchModal');
        const closeSearchModal = document.getElementById('closeSearchModal');
        const searchModalContent = document.getElementById('searchModalContent');
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        const quickFilters = document.querySelectorAll('.quick-filter');
        const popularSearches = document.querySelectorAll('.popular-search');

        // State containers
        const recentSearchesContainer = document.getElementById('recentSearchesContainer');
        const searchLoading = document.getElementById('searchLoading');
        const searchEmpty = document.getElementById('searchEmpty');
        const searchError = document.getElementById('searchError');
        const searchResultsContainer = document.getElementById('searchResultsContainer');
        const searchResults = document.getElementById('searchResults');
        const resultsCount = document.getElementById('resultsCount');
        const seeAllResults = document.getElementById('seeAllResults');

        // Current search state
        let currentSearchQuery = '';
        let currentSearchUrl = '';

        // Open search modal
        if (searchButton && searchModal) {
            searchButton.addEventListener('click', function() {
                openSearchModal();
            });
        }

        // Close search modal
        if (closeSearchModal && searchModal) {
            closeSearchModal.addEventListener('click', function() {
                closeSearchModalFunc();
            });
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                closeSearchModalFunc();
            }
        });

        // Close modal when clicking outside
        searchModal.addEventListener('click', function(e) {
            if (e.target === searchModal) {
                closeSearchModalFunc();
            }
        });

        function openSearchModal() {
            searchModal.classList.remove('hidden');
            setTimeout(() => {
                searchModal.classList.remove('opacity-0');
                searchModalContent.classList.remove('scale-95', 'opacity-0');
                searchModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            searchInput.focus();

            // Reset to initial state
            resetSearchState();

            // Load recent searches from localStorage
            loadRecentSearches();
        }

        function closeSearchModalFunc() {
            searchModalContent.classList.remove('scale-100', 'opacity-100');
            searchModalContent.classList.add('scale-95', 'opacity-0');
            searchModal.classList.add('opacity-0');

            setTimeout(() => {
                searchModal.classList.add('hidden');
                resetSearchState();
            }, 300);
        }

        function resetSearchState() {
            recentSearchesContainer.classList.remove('hidden');
            searchLoading.classList.add('hidden');
            searchEmpty.classList.add('hidden');
            searchError.classList.add('hidden');
            searchResultsContainer.classList.add('hidden');
            searchResults.innerHTML = '';
            resultsCount.textContent = '0 {{ __('messages.result_plural') }}';
            seeAllResults.classList.add('hidden');
            searchInput.value = '';
            currentSearchQuery = '';
            currentSearchUrl = '';
        }

        // Quick filter buttons
        quickFilters.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                let query = '';

                switch (filter) {
                    case 'new_arrivals':
                        query = '{{ __('messages.new_arrivals') }}';
                        break;
                    case 'featured':
                        query = '{{ __('messages.featured') }}';
                        break;
                    case 'men':
                        query = '{{ __('messages.men') }}';
                        break;
                    case 'women':
                        query = '{{ __('messages.women') }}';
                        break;
                    case 'kids':
                        query = '{{ __('messages.kids') }}';
                        break;
                }

                searchInput.value = query;
                performSearch(query);
            });
        });

        // Popular searches
        popularSearches.forEach(button => {
            button.addEventListener('click', function() {
                const query = this.getAttribute('data-query');
                searchInput.value = query;
                performSearch(query);
            });
        });

        // Form submission
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                performSearch(query);
            }
        });

        // Real-time search with debouncing
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();

            if (query.length < 2) {
                if (currentSearchQuery) {
                    resetSearchState();
                }
                return;
            }

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Set new timeout for debouncing
            searchTimeout = setTimeout(() => {
                if (query !== currentSearchQuery) {
                    performSearch(query);
                }
            }, 500);
        });

        async function performSearch(query) {
            if (!query || query === currentSearchQuery) return;

            currentSearchQuery = query;
            currentSearchUrl = `{{ route('products.search') }}?q=${encodeURIComponent(query)}`;

            // Show loading state
            recentSearchesContainer.classList.add('hidden');
            searchLoading.classList.remove('hidden');
            searchEmpty.classList.add('hidden');
            searchError.classList.add('hidden');
            searchResultsContainer.classList.add('hidden');

            try {
                // AJAX request to search endpoint
                const response = await fetch(`${currentSearchUrl}&ajax=true&limit=6`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // If not JSON, get the text to see what's returned
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    throw new Error('Server returned non-JSON response');
                }

                const data = await response.json();

                // Hide loading
                searchLoading.classList.add('hidden');

                if (data.success && data.products && data.products.length > 0) {
                    // Show results
                    displaySearchResults(data.products, data.total || data.products.length);

                    // Save to recent searches
                    saveRecentSearch(query);
                } else {
                    // Show empty state
                    searchEmpty.classList.remove('hidden');
                }

            } catch (error) {
                console.error('Search error:', error);
                searchLoading.classList.add('hidden');
                searchError.classList.remove('hidden');

                // Show error message
                const errorMessage = error.message || '{{ __('messages.please_try_again') }}';
                searchError.innerHTML = `
            <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.search_error') }}</h3>
            <p class="text-gray-600">${errorMessage}</p>
        `;
            }
        }

        function displaySearchResults(products, totalResults) {
            // Update results count
            const resultText = totalResults === 1 ?
                `${totalResults} {{ __('messages.result_singular') }}` :
                `${totalResults} {{ __('messages.result_plural') }}`;
            resultsCount.textContent = resultText;

            // Show/hide "See All Results" button
            if (totalResults > 6) {
                seeAllResults.href = currentSearchUrl;
                seeAllResults.classList.remove('hidden');
            } else {
                seeAllResults.classList.add('hidden');
            }

            // Generate compact products HTML for modal
            let html = '';
            products.forEach(product => {
                // Get primary image or first image
                let imageUrl = 'https://via.placeholder.com/150x150?text=No+Image';
                if (product.images && product.images.length > 0) {
                    const primaryImage = product.images.find(img => img.is_primary) || product.images[
                        0];
                    imageUrl = `{{ asset('storage/') }}/${primaryImage.image_path}`;
                }

                // Get min price
                let minPrice = 0;
                let maxPrice = 0;
                let hasDiscount = false;
                let discountPrice = null;

                if (product.variants && product.variants.length > 0) {
                    const prices = product.variants.map(v => v.price).filter(p => p > 0);
                    minPrice = prices.length > 0 ? Math.min(...prices) : 0;
                    maxPrice = prices.length > 0 ? Math.max(...prices) : 0;

                    // Check for discounts
                    const discountVariants = product.variants.filter(v => v.discount_price !== null && v
                        .discount_price > 0);
                    hasDiscount = discountVariants.length > 0;
                    if (hasDiscount) {
                        discountPrice = Math.min(...discountVariants.map(v => v.discount_price));
                    }
                }

                // Format price
                const formattedPrice = minPrice > 0 ? `$${minPrice.toFixed(2)}` :
                    '{{ __('messages.price_unavailable') }}';
                const formattedMaxPrice = maxPrice > minPrice ? `$${maxPrice.toFixed(2)}` : '';

                html += `
            <div class="group bg-white rounded-lg overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-200">
                <!-- Compact Product Card -->
                <div class="flex p-3">
                    <!-- Image Container (Smaller) -->
                    <div class="w-20 h-20 flex-shrink-0 relative">
                        <a href="/product/${product.slug}" class="block relative overflow-hidden bg-gray-100 rounded-md">
                            <img src="${imageUrl}" alt="${product.name}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Badges (Tiny) -->
                            <div class="absolute top-1 left-1 flex flex-col gap-0.5">
                                ${product.is_new ? '<span class="px-1 py-0.5 bg-blue-600 text-white text-[10px] font-semibold rounded">NEW</span>' : ''}
                                ${product.is_featured ? '<span class="px-1 py-0.5 bg-purple-600 text-white text-[10px] font-semibold rounded">FEAT</span>' : ''}
                            </div>
                        </a>
                    </div>
                    
                    <!-- Product Info (Compact) -->
                    <div class="ml-3 flex-1 min-w-0">
                        <!-- Product Name -->
                        <a href="/product/${product.slug}" class="block">
                            <h4 class="font-medium text-gray-900 group-hover:text-gray-700 line-clamp-2 text-sm leading-tight">
                                ${product.name}
                            </h4>
                        </a>
                        
                        <!-- Category -->
                        ${product.category ? 
                            `<p class="text-xs text-gray-500 mt-0.5">${product.category.name}</p>` : 
                            ''}
                        
                        <!-- Price Row -->
                        <div class="mt-1 flex items-center flex-wrap gap-1">
                            ${hasDiscount && discountPrice ? 
                                `<span class="font-bold text-gray-900 text-sm">$${discountPrice.toFixed(2)}</span>
                                 <span class="text-xs text-gray-500 line-through">$${minPrice.toFixed(2)}</span>
                                 <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-[10px] font-semibold rounded">SALE</span>` :
                                (maxPrice > minPrice ? 
                                    `<span class="font-bold text-gray-900 text-sm">$${minPrice.toFixed(2)} - $${maxPrice.toFixed(2)}</span>` :
                                    `<span class="font-bold text-gray-900 text-sm">${formattedPrice}</span>`
                                )
                            }
                        </div>
                        
                        <!-- Rating (Tiny) -->
                        ${product.rating_cache > 0 ? 
                            `<div class="flex items-center mt-1">
                                <div class="flex text-yellow-400 text-xs">
                                    ${'★'.repeat(Math.floor(product.rating_cache))}${'☆'.repeat(5 - Math.floor(product.rating_cache))}
                                </div>
                                <span class="ml-1 text-xs text-gray-500">(${product.review_count || 0})</span>
                            </div>` : 
                            ''
                        }
                        
                        <!-- Action Buttons (Compact) -->
                        <div class="mt-2 flex items-center space-x-2">
                            <button onclick="addToCartFromSearch('${product.slug}', event)"
                                    class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded hover:bg-gray-800 transition-colors">
                                {{ __('messages.add_to_cart') }}
                            </button>
                            <button onclick="quickView('${product.slug}', event)"
                                    class="px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition-colors">
                                {{ __('messages.quick_view') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            });

            searchResults.innerHTML = html;
            searchResultsContainer.classList.remove('hidden');
        }

        // Save search to localStorage
        function saveRecentSearch(query) {
            let recentSearches = JSON.parse(localStorage.getItem('recentSearches') || '[]');

            // Remove if already exists
            recentSearches = recentSearches.filter(item => item !== query);

            // Add to beginning
            recentSearches.unshift(query);

            // Keep only last 5
            if (recentSearches.length > 5) {
                recentSearches = recentSearches.slice(0, 5);
            }

            localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
        }

        function loadRecentSearches() {
            const recentSearches = JSON.parse(localStorage.getItem('recentSearches') || '[]');
            const container = document.getElementById('recentSearchesList');

            if (recentSearches.length > 0) {
                container.innerHTML = recentSearches.map(query => `
                    <button type="button" onclick="setSearchQuery('${query}')"
                        class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ${query}
                    </button>
                `).join('');
            } else {
                container.innerHTML =
                    '<p class="text-gray-500 text-sm">{{ __('messages.no_recent_searches') }}</p>';
            }
        }

        window.setSearchQuery = function(query) {
            searchInput.value = query;
            performSearch(query);
        }

        // Quick View Function
        window.quickView = async function(slug, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const modal = document.getElementById('quickViewModal');

            // Show loading
            modal.innerHTML = `
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
                        <div class="flex items-center justify-center h-64">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                        </div>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');

            try {
                const response = await fetch(`/product/${slug}/quick-view`);
                const html = await response.text();

                modal.innerHTML = html;

                // Initialize quick view modal
                const closeBtn = modal.querySelector('.quick-view-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        modal.classList.add('hidden');
                    });
                }

                // Close modal on escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });

                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });

            } catch (error) {
                console.error('Quick view error:', error);
                modal.innerHTML = `
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden p-8 text-center">
                            <p class="text-gray-600">{{ __('messages.error_loading_product') }}</p>
                        </div>
                    </div>
                `;
            }
        }

        // Add to Cart from Search Results
        window.addToCartFromSearch = function(slug, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            // You'll need to implement this based on your cart functionality
            console.log('Add to cart:', slug);
            // For now, show a success message
            showToast('{{ __('messages.product_added_to_cart') }}', 'success');
        }

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-[70] transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Enhanced Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        let lastScrollY = window.scrollY;
        const excludedPages = ['/orders', '/admin', '/products', '/product', '/cart', '/checkout', '/thank-you',
            '/profile'
        ];

        function updateNavbarOnScroll() {
            const currentPath = window.location.pathname;
            const isExcludedPage = excludedPages.some(page => currentPath.includes(page));

            if (isExcludedPage) {
                navbar.style.transform = 'translateY(0)';
                if (window.scrollY > 20) {
                    navbar.classList.remove('bg-white/95', 'backdrop-blur-sm');
                    navbar.classList.add('bg-white', 'shadow-md', 'border-b', 'border-gray-100');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-md', 'border-b', 'border-gray-100');
                    navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
                }
                return;
            }

            if (window.scrollY > 100) {
                navbar.style.transform = 'translateY(0)';
                if (window.scrollY > 50) {
                    navbar.classList.remove('bg-white/95', 'backdrop-blur-sm');
                    navbar.classList.add('bg-white', 'shadow-sm', 'border-b', 'border-gray-100');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-sm', 'border-b', 'border-gray-100');
                    navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
                }
            } else {
                navbar.style.transform = 'translateY(-100%)';
                navbar.classList.remove('bg-white', 'shadow-sm', 'border-b', 'border-gray-100');
                navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
            }

            lastScrollY = window.scrollY;
        }

        // Initial check
        updateNavbarOnScroll();

        // Enhanced scroll event listener
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateNavbarOnScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }, {
            passive: true
        });

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fadeIn {
                animation: fadeIn 0.2s ease-out;
            }
            .animate-slideDown {
                animation: slideDown 0.3s ease-out;
            }
            
            /* Active link indicator for main nav */
            .nav-link.text-gray-900.font-semibold {
                position: relative;
            }
            
            /* Improved dropdown styles */
            .relative.group > .absolute {
                left: 50%;
                transform: translateX(-50%);
            }
            
            /* Make dropdown items more clickable */
            .relative.group ul li a {
                display: block;
                padding: 0.25rem 0.5rem;
                margin: 0 -0.5rem;
                border-radius: 0.25rem;
            }
            
            .relative.group ul li a:hover {
                background-color: rgba(0, 0, 0, 0.05);
            }
            
            /* Ensure dropdown stays open when hovering over it */
            .relative.group:hover > div[x-show] {
                display: block !important;
            }
            
            /* Search modal animations */
            #searchModal {
                backdrop-filter: blur(4px);
            }
            
            #searchModalContent {
                transform-origin: center;
            }
            
            /* Line clamp utility */
            .line-clamp-1 {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            /* Aspect ratio */
            .aspect-square {
                aspect-ratio: 1 / 1;
            }
            
            /* Responsive improvements */
            @media (max-width: 768px) {
                #mobileMenu {
                    position: absolute;
                    left: 0;
                    right: 0;
                    background: white;
                    z-index: 40;
                }
                
                .navbar {
                    background: white !important;
                }
                
                #searchModalContent {
                    max-height: 90vh;
                }
                
                #searchResults {
                    grid-template-columns: 1fr;
                }
            }
            
            /* Smooth transitions */
            .transition-all {
                transition-property: all;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Custom scrollbar */
            #searchResults {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e0 #f7fafc;
            }
            
            #searchResults::-webkit-scrollbar {
                width: 6px;
            }
            
            #searchResults::-webkit-scrollbar-track {
                background: #f7fafc;
                border-radius: 3px;
            }
            
            #searchResults::-webkit-scrollbar-thumb {
                background: #cbd5e0;
                border-radius: 3px;
            }
            
            #searchResults::-webkit-scrollbar-thumb:hover {
                background: #a0aec0;
            }
                /* Make search results more compact */
            #searchResults {
                display: flex;
                flex-direction: column;
                gap: 8px; /* Smaller gap between items */
                max-height: 400px; /* Limit height */
                overflow-y: auto;
            }

            /* Compact product card hover effects */
            #searchResults .group:hover {
                background-color: #f9fafb;
            }

            /* Ensure text doesn't overflow */
            #searchResults .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                line-height: 1.3;
            }

            /* Smaller image container */
            #searchResults .w-20 {
                width: 80px;
            }
            #searchResults .h-20 {
                height: 80px;
            }

            /* Adjust modal container height */
            #searchModalContent {
                max-height: 85vh;
            }

            /* Make the results section scrollable */
            #searchResultsContainer {
                display: flex;
                flex-direction: column;
                min-height: 0;
            }

            #searchResults {
                flex: 1;
                overflow-y: auto;
            }
        `;
        document.head.appendChild(style);
    });
</script>
