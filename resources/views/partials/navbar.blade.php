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
                <div class="relative group" id="menCategory">
                    <a href="{{ url('men') }}"
                        class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 relative z-10">MEN</a>
                    <!-- Mega Dropdown -->
                    <div
                        class="absolute left-1/2 transform -translate-x-1/3 mt-4 w-screen max-w-6xl bg-white shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="px-8 py-6 grid grid-cols-4 gap-8">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Clothing
                                </h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">T-Shirts</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Shirts</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Jeans</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Shorts</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Jackets</a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Footwear
                                </h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Sneakers</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Boots</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Sandals</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Formal
                                            Shoes</a></li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                    Accessories</h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Bags</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Watches</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-gray-600 hover:text-gray-900 text-sm">Sunglasses</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Belts</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">Featured
                                </h3>
                                <p class="text-xs text-gray-600 mb-3">Check out our new summer collection</p>
                                <a href="#"
                                    class="inline-block bg-gray-900 text-white px-4 py-2 text-xs font-medium rounded hover:bg-gray-800 transition-colors">Shop
                                    Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative group" id="womenCategory">
                    <a href="{{ url('women') }}"
                        class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 relative z-10">WOMEN</a>
                    <!-- Mega Dropdown -->
                    <div
                        class="absolute left-1/2 transform -translate-x-1/3 mt-4 w-screen max-w-6xl bg-white shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="px-8 py-6 grid grid-cols-4 gap-8">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Clothing
                                </h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Dresses</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Tops</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Jeans</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Skirts</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-gray-600 hover:text-gray-900 text-sm">Activewear</a></li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Footwear
                                </h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Heels</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Flats</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Boots</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Sandals</a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                                    Accessories</h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Bags</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Jewelry</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Scarves</a>
                                    </li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Hats</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">New
                                    Arrivals</h3>
                                <p class="text-xs text-gray-600 mb-3">Discover the latest trends</p>
                                <a href="#"
                                    class="inline-block bg-gray-900 text-white px-4 py-2 text-xs font-medium rounded hover:bg-gray-800 transition-colors">Explore</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative group" id="kidsCategory">
                    <a href="{{ url('kids') }}"
                        class="nav-link text-gray-700 hover:text-gray-900 font-medium py-2 transition-colors duration-200 relative z-10">KIDS</a>
                    <!-- Mega Dropdown -->
                    <div
                        class="absolute left-1/2 transform -translate-x-1/3 mt-4 w-screen max-w-6xl bg-white shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="px-8 py-6 grid grid-cols-3 gap-8">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Boys</h3>
                                <ul class="space-y-3">
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">T-Shirts
                                            & Tops</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Pants &
                                            Jeans</a></li>
                                    <li><a href="#"
                                            class="text-gray-600 hover:text-gray-900 text-sm">Jackets</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Shoes</a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Girls
                                </h3>
                                <ul class="space-y-3">
                                    <li><a href="#"
                                            class="text-gray-600 hover:text-gray-900 text-sm">Dresses</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Tops</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-gray-600 hover:text-gray-900 text-sm">Leggings</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Shoes</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-2">Back to
                                    School</h3>
                                <p class="text-xs text-gray-600 mb-3">Get ready for the new school year</p>
                                <a href="#"
                                    class="inline-block bg-gray-900 text-white px-4 py-2 text-xs font-medium rounded hover:bg-gray-800 transition-colors">Shop
                                    Collection</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Links -->
                {{-- <a href="{{ route('contact') }}"
                     class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Contact</a> --}}
                {{-- <a href="{{ route('products.all') }}"
                     class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Products</a> --}}

                <!-- Conditional Auth Links -->
                {{-- @guest
                    <a href="{{ route('register') }}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Sign Up</a>
                    <a href="{{ route('login') }}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Log In</a>
                @endguest --}}

                @auth
                    <a href="{{ route('orders.index') }}"
                        class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">ORDERS</a>

                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">ADMIN</a>
                    @endif
                @endauth
            </div>

            <!-- Icons Section -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Dropdown with Profile Picture -->
                    <div class="relative">
                        <button id="userDropdownButton"
                            class="flex items-center focus:outline-none transition-all duration-200 hover:scale-105">
                            @if (auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full object-cover border-2 border-gray-300 hover:border-gray-400 transition-all duration-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center border-2 border-gray-300 hover:border-gray-400 transition-all duration-200">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </button>
                        <div id="userDropdown"
                            class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 z-50 hidden border border-gray-100">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <!-- Dropdown Items -->
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group">
                                <i class="fas fa-user mr-3 text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                My Profile
                            </a>
                            <a href="{{ route('orders.index') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group">
                                <i class="fas fa-shopping-bag mr-3 text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                My Orders
                            </a>
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group">
                                        <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                @guest
                    <!-- Show icon for guests -->
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 transition-colors duration-200">
                        <i class="fas fa-user text-lg hover:scale-110 transition-transform duration-200"></i>
                    </a>
                @endguest

                <!-- Cart -->
                <a href="{{ route('cart') }}" class="text-gray-700 hover:text-gray-900 relative transition-colors duration-200 group">
                    <i class="fas fa-shopping-cart text-lg group-hover:scale-110 transition-transform duration-200"></i>
                    @php
                        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
                    @endphp
                    @if($cartCount > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-200">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobileMenuButton" class="text-gray-700 hover:text-gray-900 focus:outline-none transition-colors duration-200">
                        <i class="fas fa-bars text-xl hover:scale-110 transition-transform duration-200"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-gray-200 py-4 shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('men') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">MEN</a>
                <a href="{{ url('women') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">WOMEN</a>
                <a href="{{ url('kids') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">KIDS</a>

                @guest
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">Sign Up</a>
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">Log In</a>
                @endguest

                @auth
                    <!-- Mobile User Info -->
                    <div class="px-3 py-2 border-t border-gray-200 mt-2 pt-3">
                        <div class="flex items-center space-x-3 mb-3">
                            @if (auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                     alt="Profile" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center border-2 border-gray-300">
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
                    </div>

                    <a href="{{ route('orders.index') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">My Orders</a>

                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">Admin Dashboard</a>
                    @endif

                    <a href="{{ route('profile.show') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 text-gray-700 hover:text-gray-900 font-medium hover:bg-gray-50 rounded transition-colors duration-200">Sign Out</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

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
                if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('animate-slideDown');
                }
            });
        }

        // Set active nav link based on current page
        function setActiveNavLink() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('text-gray-900', 'font-semibold');
                    link.classList.remove('text-gray-700');
                }
            });
        }

        setActiveNavLink();

        // Enhanced Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        let lastScrollY = window.scrollY;
        const excludedPages = ['/orders', '/admin', '/products', '/product','/cart', '/checkout','/thank-you','/profile'];

        function updateNavbarOnScroll() {
            const currentPath = window.location.pathname;
            const isExcludedPage = excludedPages.some(page => currentPath.includes(page));

            if (isExcludedPage) {
                // Normal behavior for excluded pages - always visible with enhanced styling
                navbar.style.transform = 'translateY(0)';
                if (window.scrollY > 20) {
                    navbar.classList.remove('bg-white/95', 'backdrop-blur-sm');
                    navbar.classList.add('bg-white', 'shadow-xl', 'border-b', 'border-gray-100');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-xl', 'border-b', 'border-gray-100');
                    navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
                }
                return;
            }

            // For other pages - enhanced hide/show behavior
            if (window.scrollY > 100) {
                // Show navbar when scrolled down with smooth animation
                navbar.style.transform = 'translateY(0)';

                // Enhanced background changes
                if (window.scrollY > 50) {
                    navbar.classList.remove('bg-white/95', 'backdrop-blur-sm');
                    navbar.classList.add('bg-white', 'shadow-xl', 'border-b', 'border-gray-100');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-xl', 'border-b', 'border-gray-100');
                    navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
                }
            } else {
                // Hide navbar when at top with smooth animation
                navbar.style.transform = 'translateY(-100%)';
                navbar.classList.remove('bg-white', 'shadow-xl', 'border-b', 'border-gray-100');
                navbar.classList.add('bg-white/95', 'backdrop-blur-sm');
            }

            lastScrollY = window.scrollY;
        }

        // Initial check
        updateNavbarOnScroll();

        // Enhanced scroll event listener with better performance
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateNavbarOnScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

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
        `;
        document.head.appendChild(style);
    });
</script>