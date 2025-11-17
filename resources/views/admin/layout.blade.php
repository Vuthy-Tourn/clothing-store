<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moeww - Fashion Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'Ocean': '#586879',
                        'Wave': '#92a4ac',
                        'Foam': '#f9fbfd',
                        'Ciel': '#c6d1db',
                        'Pearl': '#f8f6f4',
                        'Silk': '#e8e2dc',
                        'Lace': '#f5f1ed',
                        'Navy': '#2d3748',
                        'Beige': '#f3e9d5',
                        'glass': 'rgba(255, 255, 255, 0.1)'
                    },
                    backdropBlur: {
                        xs: '2px',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-in': 'slideIn 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #586879 0%, #92a4ac 100%);
            min-height: 100vh;
        }

        .fashion-font {
            font-family: 'Playfair Display', serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        .sidebar-elegant {
            background: linear-gradient(180deg, #f8f6f4 0%, #f5f1ed 100%);
            border-right: 1px solid rgba(230, 230, 230, 0.8);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
        }
        
        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            margin: 2px 8px;
            border-radius: 12px;
        }
        
        .nav-item:hover {
            background: linear-gradient(90deg, rgba(136, 149, 162, 0.08) 0%, transparent 100%);
            border-left-color: #586879;
            transform: translateX(4px);
        }
        
        .nav-item.active {
            background: linear-gradient(90deg, rgba(136, 149, 162, 0.12) 0%, transparent 100%);
            border-left-color: #586879;
            box-shadow: 0 4px 12px rgba(88, 104, 121, 0.1);
        }
        
        /* .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: #586879;
            border-radius: 0 2px 2px 0;
        } */
        
        .logo-elegant {
            background: linear-gradient(135deg, #586879 0%, #92a4ac 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.05em;
        }
        
        .icon-elegant {
            transition: all 0.3s ease;
            color: #586879;
            width: 20px;
            text-align: center;
        }
        
        .nav-item:hover .icon-elegant {
            transform: scale(1.15);
            color: #92a4ac;
        }
        
        .nav-item.active .icon-elegant {
            color: #586879;
            transform: scale(1.1);
        }
        
        .user-avatar {
            background: linear-gradient(135deg, #586879 0%, #92a4ac 100%);
            box-shadow: 0 4px 12px rgba(88, 104, 121, 0.2);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #586879 0%, #92a4ac 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(88, 104, 121, 0.3);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(88, 104, 121, 0.3); }
            to { box-shadow: 0 0 25px rgba(88, 104, 121, 0.5); }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateX(-20px);
            }
            to { 
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Elegant scrollbar for sidebar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: rgba(136, 149, 162, 0.1);
            border-radius: 2px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(136, 149, 162, 0.3);
            border-radius: 2px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(136, 149, 162, 0.5);
        }

        /* Badge styles */
        .nav-badge {
            background: linear-gradient(135deg, #586879 0%, #92a4ac 100%);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 8px;
            min-width: 18px;
            text-align: center;
        }

        /* Collapsible sections */
        .nav-section {
            transition: all 0.3s ease;
        }

        .nav-section-header {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-section-header:hover {
            background: rgba(136, 149, 162, 0.05);
        }

        .nav-section-content {
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .nav-section.collapsed .nav-section-content {
            max-height: 0;
            opacity: 0;
        }

        .nav-section.expanded .nav-section-content {
            max-height: 500px;
            opacity: 1;
        }

        /* Status indicator */
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
        }

        .status-indicator.offline {
            background: #6b7280;
        }

        .status-indicator.away {
            background: #f59e0b;
        }
    </style>
</head>

<body class="min-h-screen flex">
    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-6 left-6 z-50">
        <button id="mobileMenuButton" class="glass-card text-Ocean p-3 rounded-lg shadow-lg hover-lift">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-80 sidebar-elegant text-Ocean min-h-screen fixed flex flex-col justify-between transition-all duration-500 ease-in-out z-40 lg:translate-x-0 -translate-x-full sidebar-scrollbar">
        <div class="flex-1 overflow-y-auto">
            <!-- Logo Header -->
            <div class="px-6 py-6 border-b border-Silk/50">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-Ocean to-Wave rounded-xl flex items-center justify-center shadow-lg hover-lift">
                            <i class="fas fa-crown text-Pearl text-lg"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold logo-elegant fashion-font">Moeww</h1>
                        <p class="text-Wave text-sm font-medium tracking-wide">FASHION STUDIO</p>
                    </div>
                    <div class="lg:hidden">
                        <button id="closeSidebar" class="text-Wave hover:text-Ocean p-2 rounded-lg transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            

            <!-- Navigation -->
            <nav class="px-3 py-4 space-y-1">
                <!-- Dashboard -->
                <a href="#" data-url="{{ url('admin/dashboard') }}" onclick="loadAdminPage(event, this)" 
                   class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-chart-pie icon-elegant text-lg"></i>
                    </div>
                    <span class="font-medium text-[15px] tracking-wide flex-1">Dashboard</span>
                    <div class="status-indicator online"></div>
                </a>
                
                <!-- Content Management Section -->
                <div class="nav-section expanded mt-6 mb-2">
                    <div class="nav-section-header flex items-center justify-between py-2 px-4 text-Wave uppercase text-xs font-semibold tracking-wider">
                        <span>CONTENT MANAGEMENT</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                    <div class="nav-section-content mt-2 space-y-1">
                        <a href="#" data-url="{{ url('admin/carousels') }}" onclick="loadAdminPage(event, this)" 
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-images icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">Carousels</span>
                            <span class="nav-badge" id="carouselsCount">0</span>
                        </a>
                        
                        <a href="#" data-url="{{ url('admin/featured-product') }}" onclick="loadAdminPage(event, this)" 
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-star icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">Featured</span>
                            <span class="nav-badge" id="featuredCount">0</span>
                        </a>
                        
                        <a href="#" data-url="{{ url('admin/new-arrivals') }}" onclick="loadAdminPage(event, this)" 
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-gem icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">New Arrivals</span>
                            <span class="nav-badge" id="arrivalsCount">0</span>
                        </a>
                    </div>
                </div>

                <!-- Catalog Management Section -->
                <div class="nav-section expanded mt-4 mb-2">
                    <div class="nav-section-header flex items-center justify-between py-2 px-4 text-Wave uppercase text-xs font-semibold tracking-wider">
                        <span>CATALOG MANAGEMENT</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                    <div class="nav-section-content mt-2 space-y-1">
                        <a href="#" data-url="{{ url('admin/categories') }}" onclick="loadAdminPage(event, this)" 
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-tags icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">Categories</span>
                            <span class="nav-badge" id="categoriesCount">0</span>
                        </a>
                        
                        <a href="#" data-url="{{ url('admin/products') }}" onclick="loadAdminPage(event, this)" 
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-box icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">Products</span>
                            <span class="nav-badge" id="productsCount">0</span>
                        </a>
                    </div>
                </div>

                <!-- Customer Management Section -->
                <div class="nav-section expanded mt-4 mb-2">
                    <div class="nav-section-header flex items-center justify-between py-2 px-4 text-Wave uppercase text-xs font-semibold tracking-wider">
                        <span>CUSTOMER MANAGEMENT</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                    <div class="nav-section-content mt-2 space-y-1">
                        <a href="#" data-url="{{ url('admin/orders') }}" onclick="loadAdminPage(event, this)"
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-shopping-bag icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">Orders</span>
                            <span class="nav-badge" id="ordersCount">0</span>
                        </a>
                        
                        <a href="#" data-url="{{ url('admin/emails') }}" onclick="loadAdminPage(event, this)" 
                           class="nav-item flex items-center space-x-4 py-3 px-4 text-Ocean hover:text-Ocean group transition-all duration-300">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-envelope icon-elegant text-lg"></i>
                            </div>
                            <span class="font-medium text-[15px] tracking-wide flex-1">Subscribers</span>
                            <span class="nav-badge" id="subscribersCount">0</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- User Section & Logout -->
        <div class="p-4 border-t border-Silk/50 bg-Lace/30">
            <div class="flex items-center space-x-3 mb-4 p-3 rounded-lg bg-white/50 hover:bg-white/70 transition-all duration-300 cursor-pointer">
                <div class="user-avatar w-10 h-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-Pearl text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-Ocean text-[14px] truncate">Elena Fashion</p>
                    <p class="text-Wave text-xs truncate">Admin • Online</p>
                </div>
                <div class="status-indicator online"></div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 py-3 px-4 text-Ocean hover:bg-red-50 hover:text-red-600 rounded-lg transition-all duration-300 group border border-Silk hover:border-red-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-Wave group-hover:text-red-500 transition-colors duration-300"></i>
                    </div>
                    <span class="font-medium text-[14px] tracking-wide">Logout</span>
                    <i class="fas fa-chevron-right ml-auto text-Wave text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div id="main" class="lg:ml-80 flex-1 transition-all duration-500 ease-in-out min-h-screen p-6">
        <!-- Page Content -->
        <main class="min-h-screen">
            <div id="admin-content" class="min-h-screen">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Initialize AOS animations
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 50
            });

            // Set initial active state for dashboard
            const dashboardLink = document.querySelector('a[data-url*="dashboard"]');
            if (dashboardLink) {
                dashboardLink.classList.add('active');
            }

            // Initialize collapsible sections
            initializeCollapsibleSections();

            // Load initial stats
            loadSidebarStats();
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar on mobile
        document.getElementById('closeSidebar')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.add('-translate-x-full');
        });

        // Collapsible sections functionality
        function initializeCollapsibleSections() {
            const sectionHeaders = document.querySelectorAll('.nav-section-header');
            
            sectionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const section = this.parentElement;
                    const isExpanded = section.classList.contains('expanded');
                    const icon = this.querySelector('i');
                    
                    // Toggle section
                    section.classList.toggle('expanded');
                    section.classList.toggle('collapsed');
                    
                    // Rotate icon
                    if (isExpanded) {
                        icon.style.transform = 'rotate(-90deg)';
                    } else {
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        }

        // Load sidebar statistics
        function loadSidebarStats() {
            // Fetch stats from API endpoint or use default values
            fetch('/admin/api/sidebar-stats')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Stats endpoint not available');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update counts with real data
                    document.getElementById('pendingOrdersCount').textContent = data.pending_orders || 0;
                    document.getElementById('activeProductsCount').textContent = data.active_products || 0;
                    document.getElementById('carouselsCount').textContent = data.carousels || 0;
                    document.getElementById('featuredCount').textContent = data.featured || 0;
                    document.getElementById('arrivalsCount').textContent = data.arrivals || 0;
                    document.getElementById('categoriesCount').textContent = data.categories || 0;
                    document.getElementById('productsCount').textContent = data.products || 0;
                    document.getElementById('ordersCount').textContent = data.orders || 0;
                    document.getElementById('subscribersCount').textContent = data.subscribers || 0;
                })
                .catch(error => {
                    console.log('Using default sidebar stats');
                    // Set default values if API is not available
                    setDefaultStats();
                });
        }

        // Set default statistics
        function setDefaultStats() {
            const elements = {
                'pendingOrdersCount': 0,
                'activeProductsCount': 0,
                'carouselsCount': 0,
                'featuredCount': 0,
                'arrivalsCount': 0,
                'categoriesCount': 0,
                'productsCount': 0,
                'ordersCount': 0,
                'subscribersCount': 0
            };

            Object.keys(elements).forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = elements[id];
                }
            });
        }

        // Load admin page function
        function loadAdminPage(event, el) {
            event.preventDefault();
            const url = el.getAttribute('data-url');
            const contentDiv = document.getElementById('admin-content');

            // Update active state
            document.querySelectorAll('.nav-item').forEach(link => {
                link.classList.remove('active');
            });
            el.classList.add('active');

            // Close mobile menu on selection
            if (window.innerWidth < 1024) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.add('-translate-x-full');
            }

            // Show loading state
            contentDiv.innerHTML = `
                <div class="flex items-center justify-center min-h-screen">
                    <div class="glass-card p-8 rounded-2xl text-center">
                        <div class="w-16 h-16 border-4 border-Wave border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-Foam font-medium">Loading...</p>
                    </div>
                </div>
            `;

            // Update URL and fetch content
            history.pushState({}, '', url);
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('#admin-content');
                    contentDiv.innerHTML = newContent ? newContent.innerHTML : 'Page not found.';
                    
                    // Re-init animations
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }

                    // Refresh sidebar stats after page load
                    loadSidebarStats();
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    contentDiv.innerHTML = `
                        <div class="glass-card p-8 rounded-2xl text-center">
                            <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                            <p class="text-Foam font-medium">Error loading page content</p>
                        </div>
                    `;
                });
        }

        // Handle browser back/forward
        window.addEventListener('popstate', function() {
            const url = location.href;
            document.querySelectorAll('.nav-item').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-url') === url) {
                    link.classList.add('active');
                }
            });
            
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector('#admin-content');
                    document.getElementById('admin-content').innerHTML = newContent ? newContent.innerHTML : 'Page not found.';
                    
                    // Refresh sidebar stats
                    loadSidebarStats();
                });
        });

        // Add hover effects for touch devices
        document.addEventListener('touchstart', function() {}, { passive: true });
    </script>
</body>
</html>