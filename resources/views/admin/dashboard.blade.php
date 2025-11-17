@extends('admin.layout')

@section('content')
    <div data-aos="fade-down" class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2 ">Welcome to Moeww </h1>
        <p class="text-white text-lg">Elegant fashion management dashboard</p>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Stats -->
        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift shadow-sm" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-Wave text-sm font-medium uppercase tracking-wide">Total Revenue</h3>
                            <p class="text-3xl font-bold text-Ocean mt-2">$24,580</p>
                            <div class="flex items-center text-green-600 text-sm mt-3">
                                <i class="fas fa-arrow-up mr-1"></i>
                                <span>18.2% increase</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-Ocean/10 text-Ocean">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift shadow-sm" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-Wave text-sm font-medium uppercase tracking-wide">Active Orders</h3>
                            <p class="text-3xl font-bold text-Ocean mt-2">156</p>
                            <div class="flex items-center text-green-600 text-sm mt-3">
                                <i class="fas fa-shopping-bag mr-1"></i>
                                <span>12 new today</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-Ocean/10 text-Ocean">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift shadow-sm" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-Wave text-sm font-medium uppercase tracking-wide">Products</h3>
                            <p class="text-3xl font-bold text-Ocean mt-2">342</p>
                            <div class="flex items-center text-Wave text-sm mt-3">
                                <i class="fas fa-boxes mr-1"></i>
                                <span>45 in stock</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-Ocean/10 text-Ocean">
                            <i class="fas fa-tshirt text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift shadow-sm" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-Wave text-sm font-medium uppercase tracking-wide">Customers</h3>
                            <p class="text-3xl font-bold text-Ocean mt-2">2,847</p>
                            <div class="flex items-center text-green-600 text-sm mt-3">
                                <i class="fas fa-user-plus mr-1"></i>
                                <span>28 new this week</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-Ocean/10 text-Ocean">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Status -->
        <div class="bg-Pearl border border-Silk p-6 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="500">
            <h2 class="text-xl font-bold text-Ocean mb-6 ">Store Status</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 rounded-lg bg-Lace border border-Silk">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-Ocean font-medium">Online Store</p>
                            <p class="text-Wave text-sm">Active</p>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Live</span>
                </div>

                <div class="flex items-center justify-between p-4 rounded-lg bg-Lace border border-Silk">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-Ocean font-medium">Inventory</p>
                            <p class="text-Wave text-sm">85% stocked</p>
                        </div>
                    </div>
                    <span class="text-blue-600 text-sm font-medium">Good</span>
                </div>

                <div class="flex items-center justify-between p-4 rounded-lg bg-Lace border border-Silk">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-Ocean font-medium">Shipping</p>
                            <p class="text-Wave text-sm">2 pending</p>
                        </div>
                    </div>
                    <span class="text-yellow-600 text-sm font-medium">Processing</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-6 ">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="#" data-url="{{ url('admin/products') }}" onclick="loadAdminPage(event, this)" 
               class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift transition group shadow-sm" data-aos="zoom-in">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-Ocean text-Pearl">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-Ocean ml-4">Add Product</h3>
                </div>
                <p class="text-Wave text-sm">Create new fashion items</p>
                <div class="mt-4 flex items-center text-Ocean group-hover:text-Wave transition-colors">
                    <span class="text-sm font-medium">Get Started</span>
                    <i class="fas fa-arrow-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <a href="#" data-url="{{ url('admin/orders') }}" onclick="loadAdminPage(event, this)"
               class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift transition group shadow-sm" data-aos="zoom-in" data-aos-delay="100">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-Ocean text-Pearl">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-Ocean ml-4">View Orders</h3>
                </div>
                <p class="text-Wave text-sm">Manage customer orders</p>
                <div class="mt-4 flex items-center text-Ocean group-hover:text-Wave transition-colors">
                    <span class="text-sm font-medium">View All</span>
                    <i class="fas fa-arrow-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <a href="#" data-url="{{ url('admin/carousels') }}" onclick="loadAdminPage(event, this)" 
               class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift transition group shadow-sm" data-aos="zoom-in" data-aos-delay="200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-Ocean text-Pearl">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-Ocean ml-4">Carousels</h3>
                </div>
                <p class="text-Wave text-sm">Update homepage banners</p>
                <div class="mt-4 flex items-center text-Ocean group-hover:text-Wave transition-colors">
                    <span class="text-sm font-medium">Manage</span>
                    <i class="fas fa-arrow-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <a href="#" data-url="{{ url('admin/featured-product') }}" onclick="loadAdminPage(event, this)" 
               class="bg-Pearl border border-Silk p-6 rounded-lg hover-lift transition group shadow-sm" data-aos="zoom-in" data-aos-delay="300">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-Ocean text-Pearl">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-Ocean ml-4">Featured</h3>
                </div>
                <p class="text-Wave text-sm">Set featured products</p>
                <div class="mt-4 flex items-center text-Ocean group-hover:text-Wave transition-colors">
                    <span class="text-sm font-medium">Select</span>
                    <i class="fas fa-arrow-right ml-2 text-xs transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-Pearl border border-Silk p-6 rounded-lg shadow-sm" data-aos="fade-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-Ocean ">Recent Activity</h2>
                <a href="#" class="text-Ocean hover:text-Wave text-sm font-medium transition-colors">View All</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">New Order Received</p>
                        <p class="text-Wave text-sm">Order #MW-7842 • $245.00</p>
                    </div>
                    <span class="text-Wave text-sm group-hover:text-Ocean transition-colors">2 min ago</span>
                </div>

                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">New Customer</p>
                        <p class="text-Wave text-sm">Sarah Johnson registered</p>
                    </div>
                    <span class="text-Wave text-sm group-hover:text-Ocean transition-colors">15 min ago</span>
                </div>

                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                        <i class="fas fa-gem"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">Product Added</p>
                        <p class="text-Wave text-sm">Summer Collection Dress</p>
                    </div>
                    <span class="text-Wave text-sm group-hover:text-Ocean transition-colors">1 hour ago</span>
                </div>

                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">Order Shipped</p>
                        <p class="text-Wave text-sm">Order #MW-7839 shipped</p>
                    </div>
                    <span class="text-Wave text-sm group-hover:text-Ocean transition-colors">3 hours ago</span>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-Pearl border border-Silk p-6 rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-Ocean  ">Top Products</h2>
                <a href="#" class="text-Ocean hover:text-Wave text-sm font-medium transition-colors">View All</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center text-Ocean">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">Silk Evening Dress</p>
                        <p class="text-Wave text-sm">$189 • 42 sold</p>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Popular</span>
                </div>

                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center text-Ocean">
                        <i class="fas fa-vest"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">Wool Blazer</p>
                        <p class="text-Wave text-sm">$245 • 28 sold</p>
                    </div>
                    <span class="text-blue-600 text-sm font-medium">Trending</span>
                </div>

                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center text-Ocean">
                        <i class="fas fa-shoe-prints"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">Leather Boots</p>
                        <p class="text-Wave text-sm">$156 • 35 sold</p>
                    </div>
                    <span class="text-purple-600 text-sm font-medium">New</span>
                </div>

                <div class="flex items-center p-4 rounded-lg bg-Lace border border-Silk hover:border-Ocean/30 transition group">
                    <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center text-Ocean">
                        <i class="fas fa-ring"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-Ocean font-medium">Pearl Necklace</p>
                        <p class="text-Wave text-sm">$89 • 51 sold</p>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Best Seller</span>
                </div>
            </div>
        </div>
    </div>
@endsection