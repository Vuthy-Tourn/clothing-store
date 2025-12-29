
<aside id="sidebar"
    class="w-80 bg-gradient-to-b from-white to-gray-50 min-h-screen fixed flex flex-col justify-between shadow-lg transition-all duration-500 ease-out z-40 lg:translate-x-0 -translate-x-full transform-gpu">
    <!-- Overlay for mobile -->
                       @include('components.language-switcher')

    <div id="sidebarOverlay"
        class="lg:hidden fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity duration-300">
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <!-- Logo Header with gradient -->
        <div
            class="px-6 py-6 border-b border-gray-200/50 bg-gradient-to-r from-white to-Ocean/5 relative overflow-hidden">
            <!-- Background accent -->
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-Ocean/5 rounded-full blur-xl"></div>

            <div class="flex items-center space-x-4 relative">
                <!-- Animated logo container -->
                <div
                    class="w-12 h-12 bg-gradient-to-br from-Ocean to-Ocean/80 rounded-xl flex items-center justify-center shadow-lg group hover:shadow-xl hover:scale-105 transition-all duration-300">
                    <i
                        class="fas fa-crown text-white text-lg group-hover:scale-110 transition-transform duration-300"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Moeww</h1>
                    <p class="text-gray-600 text-sm font-medium tracking-wide">STUDIO</p>
                </div>
                <!-- Close button with hover animation -->
                <button id="closeSidebar"
                    class="lg:hidden text-gray-500 hover:text-Ocean p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                    <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="px-3 py-6 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-chart-pie text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.dashboard') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span
                    class="font-medium tracking-wide">{{ __('admin.sidebar.dashboard') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            <!-- Content Management -->
            <div class="mt-8 mb-3 relative">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider flex items-center">
                    <span class="bg-gradient-to-r from-gray-300 to-transparent h-px w-4 mr-3"></span>
                    {{ __('admin.sidebar.content') }}
                    <span class="bg-gradient-to-l from-gray-300 to-transparent h-px w-4 ml-3 flex-1"></span>
                </div>
            </div>

            <a href="{{ route('admin.carousels.index') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.carousels.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-images text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.carousels.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span
                    class="font-medium tracking-wide">{{ __('admin.sidebar.carousels') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-user text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.users.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span class="font-medium tracking-wide">{{ __('admin.sidebar.users') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            <!-- Catalog Management -->
            <div class="mt-8 mb-3 relative">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider flex items-center">
                    <span class="bg-gradient-to-r from-gray-300 to-transparent h-px w-4 mr-3"></span>
                    {{ __('admin.sidebar.catalog') }}
                    <span class="bg-gradient-to-l from-gray-300 to-transparent h-px w-4 ml-3 flex-1"></span>
                </div>
            </div>

            <a href="{{ route('admin.categories.index') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-tags text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.categories.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span
                    class="font-medium tracking-wide">{{ __('admin.sidebar.categories') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-box text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.products.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span
                    class="font-medium tracking-wide">{{ __('admin.sidebar.products') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            <!-- Customers -->
            <div class="mt-8 mb-3 relative">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider flex items-center">
                    <span class="bg-gradient-to-r from-gray-300 to-transparent h-px w-4 mr-3"></span>
                    {{ __('admin.sidebar.customers') }}
                    <span class="bg-gradient-to-l from-gray-300 to-transparent h-px w-4 ml-3 flex-1"></span>
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-shopping-bag text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.orders.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span
                    class="font-medium tracking-wide">{{ __('admin.sidebar.orders') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            <a href="{{ route('admin.emails.index') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.emails.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i
                        class="fas fa-envelope text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.emails.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span
                    class="font-medium tracking-wide">{{ __('admin.sidebar.subscribers') }}</span>
                <div class="absolute right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </a>

            {{-- <!-- Settings Section -->
            <div class="mt-8 mb-3 relative">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider flex items-center">
                    <span class="bg-gradient-to-r from-gray-300 to-transparent h-px w-4 mr-3"></span>
                    Settings
                    <span class="bg-gradient-to-l from-gray-300 to-transparent h-px w-4 ml-3 flex-1"></span>
                </div>
            </div>

            <!-- Admin Profile Link -->
            <a href="{{ route('admin.profile.show') }}"
                class="nav-item group flex items-center space-x-4 py-3.5 px-4 rounded-xl relative overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.profile.*') ? 'bg-gradient-to-r from-Ocean/10 to-Ocean/5 text-Ocean shadow-md' : 'text-gray-700 hover:bg-gray-50/80 hover:shadow-sm hover:translate-x-1' }}">
                <div class="w-8 flex justify-center">
                    <i class="fas fa-user-cog text-lg group-hover:scale-110 transition-transform duration-300 {{ request()->routeIs('admin.profile.*') ? 'text-Ocean' : 'text-gray-500 group-hover:text-Ocean' }}"></i>
                </div>
                <span class="font-medium tracking-wide">{{ request()->routeIs('admin.profile.*') ? '• ' : '' }}My Profile</span>
            </a> --}}
        </nav>
    </div>

    <!-- User Section with enhanced design and dropdown -->
    <div class="p-4 border-t border-gray-200/50 bg-gradient-to-t from-gray-50 to-white">
        <!-- User Profile Card with Enhanced Dropdown -->
        <div class="relative group" x-data="{ open: false }" @click.outside="open = false">
            <!-- Profile Card Trigger -->
            <div @click="open = !open"
                class="card group cursor-pointer flex items-center space-x-3 mb-4 p-3.5 rounded-xl bg-white/80 backdrop-blur-sm border border-gray-200/50 hover:shadow-lg hover:border-Ocean/20 transition-all duration-300">
                <!-- User Avatar -->
                <div class="relative">
                    @if (auth()->user()->profile_picture)
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-Ocean to-Ocean/80 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300 overflow-hidden">
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-Ocean to-Ocean/80 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300">
                            <i
                                class="fas fa-user text-white text-sm group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                    @endif
                    <!-- Online Status -->
                    <span
                        class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></span>
                </div>

                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 text-sm truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-gray-600 text-xs truncate flex items-center">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-Ocean/10 text-Ocean mr-2">
                            {{ ucfirst(auth()->user()->account_type ?? 'Admin') }}
                        </span>
                        Administrator
                    </p>
                </div>

                <!-- Dropdown Arrow -->
                <i
                    class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300 transform group-hover:rotate-180"></i>
            </div>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-lg border border-gray-200/50 backdrop-blur-sm z-50 overflow-hidden"
                style="display: none;">

                <!-- Dropdown Header -->
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-Ocean/5 to-transparent">
                    <p class="text-sm font-semibold text-gray-900">{{ __('admin.sidebar.account_menu') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('admin.sidebar.manage_account') }}</p>
                </div>

                <!-- Dropdown Items -->
                <div class="py-2">
                    <!-- View Profile -->
                    <a href="{{ route('admin.profile.show') }}"
                        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-Ocean transition-colors duration-200 group">
                        <i class="fas fa-user text-gray-400 group-hover:text-Ocean text-sm w-5"></i>
                        <span class="text-sm font-medium">{{ __('admin.sidebar.view_profile') }}</span>
                    </a>
                </div>

                <!-- Logout in Dropdown -->
                <div class="border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200 group">
                            <i class="fas fa-sign-out-alt text-red-400 group-hover:text-red-600 text-sm w-5"></i>
                            <span class="text-sm font-medium">{{ __('admin.sidebar.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Logout Button (visible on mobile) -->
        <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        <button type="button" id="logoutButton"
            class="lg:hidden w-full card flex items-center justify-center space-x-3 py-3.5 px-4 rounded-xl text-gray-700 hover:text-Ocean bg-white/80 backdrop-blur-sm border border-gray-200/50 hover:border-Ocean/30 hover:shadow-lg hover:bg-gradient-to-r hover:from-red-50/50 hover:to-transparent transition-all duration-300 group">
            <i
                class="fas fa-sign-out-alt group-hover:scale-110 group-hover:-translate-x-0.5 transition-transform duration-300"></i>
            <span class="font-medium text-sm tracking-wide">{{ __('admin.sidebar.logout') }}</span>
        </button>
    </div>
</aside>

<style>
    .nav-item {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-item.bg-gradient-to-r::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background: linear-gradient(to bottom, #586879, #586879cc);
        border-radius: 0 4px 4px 0;
        animation: slideIn 0.3s ease-out;
    }

    /* Custom scrollbar */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(88, 104, 121, 0.3) transparent;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(88, 104, 121, 0.3);
        border-radius: 20px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(88, 104, 121, 0.5);
    }

    /* Animation for active state */
    @keyframes slideIn {
        from {
            transform: translateY(-50%) scaleX(0);
            opacity: 0;
        }

        to {
            transform: translateY(-50%) scaleX(1);
            opacity: 1;
        }
    }

    /* Mobile sidebar animation */
    #sidebar {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* Subtle hover effects for icons */
    .nav-item i {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Dropdown animations */
    .dropdown-enter {
        animation: dropdownEnter 0.2s ease-out forwards;
    }

    .dropdown-exit {
        animation: dropdownExit 0.15s ease-in forwards;
    }

    @keyframes dropdownEnter {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dropdownExit {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    /* Profile picture ring animation */
    @keyframes pulse-ring {
        0% {
            transform: scale(0.8);
            opacity: 0.8;
        }

        80%,
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }

    .pulse-ring::before {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border: 2px solid #10b981;
        border-radius: 50%;
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load Alpine.js if not already loaded
        if (typeof Alpine === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';
            script.defer = true;
            document.head.appendChild(script);
        }

        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuItems = document.querySelectorAll('.nav-item');
        const logoutButton = document.getElementById('logoutButton');

        // Mobile sidebar overlay
        if (overlay) {
            // Show sidebar on menu button click
            document.querySelector('[data-toggle="sidebar"]')?.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
            });

            // Close sidebar
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            });

            // Close sidebar when clicking overlay
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            });
        }

        // Add ripple effect to menu items
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Remove any existing ripple
                const existingRipple = this.querySelector('.ripple');
                if (existingRipple) {
                    existingRipple.remove();
                }

                // Don't create ripple for quick edit button clicks
                if (e.target.closest('a[href*="edit"]')) {
                    return;
                }

                // Create ripple
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(88, 104, 121, 0.2);
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
            `;
                ripple.classList.add('ripple');

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                // Remove ripple after animation
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Logout confirmation with SweetAlert2
        if (logoutButton) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Check if SweetAlert2 is loaded
                if (typeof Swal === 'undefined') {
                    // Fallback to browser confirm
                    if (confirm('Are you sure you want to logout?')) {
                        document.getElementById('logoutForm').submit();
                    }
                    return;
                }

                Swal.fire({
                    title: 'Logout Confirmation',
                    text: 'Are you sure you want to logout from your admin account?',
                    icon: 'question',
                    iconColor: '#586879',
                    showCancelButton: true,
                    confirmButtonColor: '#586879',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!',
                    cancelButtonText: 'Cancel',
                    background: '#ffffff',
                    backdrop: 'rgba(0, 0, 0, 0.4)',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    customClass: {
                        title: 'text-gray-900 font-semibold',
                        htmlContainer: 'text-gray-700',
                        confirmButton: 'px-6 py-2 rounded-lg font-medium hover:bg-Ocean/90 transition-colors',
                        cancelButton: 'px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors'
                    },
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading animation
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait while we secure your session',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit logout form after delay
                        setTimeout(() => {
                            document.getElementById('logoutForm').submit();
                        }, 1000);
                    }
                });
            });
        }

        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            // Escape to close sidebar
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) {
                    overlay.classList.remove('opacity-100', 'pointer-events-auto');
                    overlay.classList.add('opacity-0', 'pointer-events-none');
                }
            }

            // Ctrl+P to go to profile
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.location.href = "{{ route('admin.profile.show') }}";
            }

            // Ctrl+E to edit profile
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                window.location.href = "{{ route('admin.profile.edit') }}";
            }

            // Ctrl+L to logout
            if (e.ctrlKey && e.key === 'l') {
                e.preventDefault();
                document.getElementById('logoutButton')?.click();
            }
        });

        // Profile dropdown keyboard navigation
        document.addEventListener('keydown', function(e) {
            const dropdownOpen = document.querySelector('[x-data]')?.__x.$data.open;
            if (dropdownOpen && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                e.preventDefault();
                const items = document.querySelectorAll(
                '[x-data] [x-show] a, [x-data] [x-show] button');
                const current = document.activeElement;
                let index = Array.from(items).indexOf(current);

                if (e.key === 'ArrowDown') {
                    index = (index + 1) % items.length;
                } else if (e.key === 'ArrowUp') {
                    index = (index - 1 + items.length) % items.length;
                }

                items[index]?.focus();
            }
        });

        // Add active menu highlight on page load
        function highlightActiveMenu() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                const link = item.getAttribute('href');
                if (link && currentPath.includes(link.replace(route('admin.dashboard'), '').replace('/',
                        ''))) {
                    item.classList.add('bg-gradient-to-r', 'from-Ocean/10', 'to-Ocean/5', 'text-Ocean',
                        'shadow-md');
                    // Add active indicator
                    const indicator = document.createElement('span');
                    indicator.className =
                        'absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-8 bg-Ocean rounded-r';
                    item.appendChild(indicator);
                }
            });
        }

        // Initialize on load
        highlightActiveMenu();
    });

    // Add ripple animation to CSS
    const style = document.createElement('style');
    style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Quick edit button animation */
    .quick-edit-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .quick-edit-btn:hover {
        transform: scale(1.1) rotate(5deg);
    }

    /* Profile picture shimmer effect */
    .profile-shimmer {
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.2) 50%, 
            rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
`;
    document.head.appendChild(style);
</script>
