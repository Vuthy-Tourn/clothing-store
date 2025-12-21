<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-600 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-1" id="revenueCount">$0</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="flex items-center justify-center gap-1 text-xs font-semibold text-green-700 bg-green-100 px-3 py-1.5 rounded-full shadow-sm">
                        <i class="fas fa-arrow-up text-xs"></i>
                        <span id="revenueGrowth">0%</span>
                    </span>
                    {{-- <span class="text-blue-500 text-xs"></span> --}}
                </div>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                <i class="fas fa-dollar-sign text-white text-lg"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-200">
            <p class="text-blue-600 text-xs flex items-center">
                <i class="fas fa-chart-line mr-2"></i>
                Total lifetime earnings
            </p>
        </div>
    </div>

    <!-- Orders -->
    <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300" data-aos="fade-up" data-aos-delay="150">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-600 text-sm font-medium">Active Orders</p>
                <p class="text-3xl font-bold text-gray-900 mt-1" id="ordersCount">0</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="flex items-center gap-1 text-xs font-semibold text-gray-700 bg-gradient-to-r from-gray-100 to-gray-200 px-3 py-1.5 rounded-full shadow-sm">
                        <i class="fas fa-shopping-bag text-xs"></i>
                        <span id="ordersToday">0 today</span>
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-md">
                <i class="fas fa-shopping-cart text-white text-lg"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-green-200">
            <p class="text-green-600 text-xs flex items-center">
                <i class="fas fa-clock mr-2"></i>
                Currently being processed
            </p>
        </div>
    </div>

    <!-- Products -->
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-600 text-sm font-medium">Total Products</p>
                <p class="text-3xl font-bold text-gray-900 mt-1" id="productsCount">0</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-gradient-to-r from-yellow-100 to-yellow-200 px-3 py-1.5 rounded-full shadow-sm">
                        <i class="fas fa-exclamation-triangle text-xs"></i>
                        <span id="lowStock">0 low stock</span>
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-md">
                <i class="fas fa-box text-white text-lg"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-purple-200">
            <p class="text-purple-600 text-xs flex items-center">
                <i class="fas fa-layer-group mr-2"></i>
                All products in catalog
            </p>
        </div>
    </div>

    <!-- Customers -->
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300" data-aos="fade-up" data-aos-delay="250">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-600 text-sm font-medium">Customers</p>
                <p class="text-3xl font-bold text-gray-900 mt-1" id="customersCount">0</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="flex items-center gap-1 text-xs font-semibold text-gray-700 bg-gradient-to-r from-gray-100 to-gray-200 px-3 py-1.5 rounded-full shadow-sm">
                        <i class="fas fa-user-plus text-xs"></i>
                        <span id="newCustomers">0 this week</span>
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-md">
                <i class="fas fa-users text-white text-lg"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-orange-200">
            <p class="text-orange-600 text-xs flex items-center">
                <i class="fas fa-user-friends mr-2"></i>
                Registered customers
            </p>
        </div>
    </div>
</div>