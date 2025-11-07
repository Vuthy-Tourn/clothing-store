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
                @guest
                    <a href="{{ route('register') }}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Sign Up</a>
                    <a href="{{ route('login') }}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Log In</a>
                @endguest

                @auth
                    <a href="{{ route('orders.index') }}"
                        class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Orders</a>

                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link text-gray-700 hover:text-gray-900 font-medium transition-colors duration-200">Admin</a>
                    @endif
                @endauth
            </div>

            <!-- Icons Section -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="userDropdownButton"
                            class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                            <i class="fas fa-user text-lg"></i>
                        </button>
                        <div id="userDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <!-- Show icon for guests -->
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">
                        <i class="fas fa-user text-lg"></i>
                    </a>
                @endguest

                <!-- Cart -->
                <a href="{{ route('cart') }}" class="text-gray-700 hover:text-gray-900 relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ \App\Models\CartItem::where('user_id', auth()->id())->count() }}
                    </span>
                </a>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobileMenuButton" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-gray-200 py-4">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('men') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Men</a>
                <a href="{{ url('women') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Women</a>
                <a href="{{ url('kids') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Kids</a>
                {{-- <a href="{{ route('contact') }}"
                     class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Contact</a> --}}
                {{-- <a href="{{ route('products.all') }}"
                     class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Products</a> --}}

                @guest
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Sign Up</a>
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Log In</a>
                @endguest

                @auth
                    <a href="{{ route('orders.index') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Orders</a>

                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Admin</a>
                    @endif

                    <a href="{{ route('profile.show') }}"
                        class="block px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 text-gray-700 hover:text-gray-900 font-medium">Logout</button>
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

        if (userDropdownButton) {
            userDropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            if (userDropdown) {
                userDropdown.classList.add('hidden');
            }
        });

        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
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

        // Navbar scroll effect - hidden initially, slides down from top when scrolling
        const navbar = document.getElementById('mainNavbar');
        let lastScrollY = window.scrollY;
        const excludedPages = ['/orders', '/admin', '/products', '/product','/cart', '/checkout','thank-you']; // Add pages where you want normal behavior

        function updateNavbarOnScroll() {
            const currentPath = window.location.pathname;
            const isExcludedPage = excludedPages.some(page => currentPath.includes(page));

            if (isExcludedPage) {
                // Normal behavior for excluded pages - always visible
                navbar.style.transform = 'translateY(0)';
                if (window.scrollY > 50) {
                    navbar.classList.remove('bg-transparent', 'shadow-sm');
                    navbar.classList.add('bg-white', 'shadow-lg');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-lg');
                    navbar.classList.add('bg-transparent', 'shadow-sm');
                }
                return;
            }

            // For other pages - hidden initially, slides down when scrolling
            if (window.scrollY > 100) {
                // Show navbar when scrolled down
                navbar.style.transform = 'translateY(0)';

                // Change background when scrolled
                if (window.scrollY > 50) {
                    navbar.classList.remove('bg-transparent', 'shadow-sm');
                    navbar.classList.add('bg-white', 'shadow-lg');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-lg');
                    navbar.classList.add('bg-transparent', 'shadow-sm');
                }
            } else {
                // Hide navbar when at top
                navbar.style.transform = 'translateY(-100%)';
                navbar.classList.remove('bg-white', 'shadow-lg');
                navbar.classList.add('bg-transparent', 'shadow-sm');
            }

            lastScrollY = window.scrollY;
        }

        // Initial check
        updateNavbarOnScroll();

        // Listen for scroll events with throttle
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateNavbarOnScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    });
</script>
