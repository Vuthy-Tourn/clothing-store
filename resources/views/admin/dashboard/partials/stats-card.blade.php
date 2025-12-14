<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue -->
    <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium mb-1">Total Revenue</p>
                <h3 class="text-3xl font-bold text-gray-900" id="revenueCount">$0</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-1 rounded">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span id="revenueGrowth">0%</span>
                    </span>
                    <span class="text-gray-500 text-xs">vs last month</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-blue-700"></i>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="card p-6" data-aos="fade-up" data-aos-delay="150">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium mb-1">Active Orders</p>
                <h3 class="text-3xl font-bold text-gray-900" id="ordersCount">0</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded">
                        <i class="fas fa-shopping-bag mr-1"></i>
                        <span id="ordersToday">0 today</span>
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-green-700"></i>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="card p-6" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium mb-1">Total Products</p>
                <h3 class="text-3xl font-bold text-gray-900" id="productsCount">0</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs font-medium text-yellow-700 bg-yellow-100 px-2 py-1 rounded">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span id="lowStock">0 low stock</span>
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-purple-700"></i>
            </div>
        </div>
    </div>

    <!-- Customers -->
    <div class="card p-6" data-aos="fade-up" data-aos-delay="250">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium mb-1">Customers</p>
                <h3 class="text-3xl font-bold text-gray-900" id="customersCount">0</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded">
                        <i class="fas fa-user-plus mr-1"></i>
                        <span id="newCustomers">0 this week</span>
                    </span>
                </div>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-orange-700"></i>
            </div>
        </div>
    </div>
</div>
