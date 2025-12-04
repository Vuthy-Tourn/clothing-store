<aside id="sidebar" class="w-80 bg-white min-h-screen fixed flex flex-col justify-between shadow-lg transition-all duration-300 z-40 lg:translate-x-0 -translate-x-full">
    <div class="flex-1 overflow-y-auto">
        <!-- Logo Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-Ocean rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-crown text-white text-lg"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 fashion-font">Moeww</h1>
                    <p class="text-gray-600 text-sm font-medium">STUDIO</p>
                </div>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="px-3 py-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-chart-pie text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <!-- Content Management -->
            <div class="mt-6 mb-2">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider">
                    Content
                </div>
            </div>

            <a href="{{ route('admin.carousels.index') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.carousels.*') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-images text-lg"></i>
                <span class="font-medium">Carousels</span>
            </a>
            
            <a href="{{ route('admin.new-arrivals.index') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.new-arrivals.*') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-gem text-lg"></i>
                <span class="font-medium">New Arrivals</span>
            </a>

            <!-- Catalog Management -->
            <div class="mt-6 mb-2">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider">
                    Catalog
                </div>
            </div>

            <a href="{{ route('admin.categories.index') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tags text-lg"></i>
                <span class="font-medium">Categories</span>
            </a>
            
            <a href="{{ route('admin.products.index') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-box text-lg"></i>
                <span class="font-medium">Products</span>
            </a>

            <!-- Customers -->
            <div class="mt-6 mb-2">
                <div class="px-4 py-2 text-gray-500 uppercase text-xs font-semibold tracking-wider">
                    Customers
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.orders.*') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-shopping-bag text-lg"></i>
                <span class="font-medium">Orders</span>
            </a>
            
            <a href="{{ route('admin.emails.index') }}" 
               class="nav-item flex items-center space-x-4 py-3 px-4 rounded-xl {{ request()->routeIs('admin.emails.*') ? 'bg-Ocean/10 text-Ocean' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-envelope text-lg"></i>
                <span class="font-medium">Subscribers</span>
            </a>
        </nav>
    </div>
    
    <!-- User Section -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="card flex items-center space-x-3 mb-4 p-3">
            <div class="w-10 h-10 bg-Ocean rounded-xl flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 text-sm truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-gray-600 text-xs truncate">Administrator</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full card flex items-center space-x-3 py-3 px-4 text-gray-900 hover:bg-gray-50">
                <i class="fas fa-sign-out-alt"></i>
                <span class="font-medium text-sm">Logout</span>
            </button>
        </form>
    </div>
</aside>

<style>
.nav-item {
    position: relative;
    transition: all 0.3s ease;
}

.nav-item.bg-Ocean\/10::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    background: #586879;
    border-radius: 0 4px 4px 0;
}
</style>