<!-- Store Health -->
<div class="card p-6" data-aos="fade-left">
    <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('admin.store_health.title') }}</h2>
    <div class="space-y-4">
        <!-- Pending Orders -->
        <div class="p-4 rounded-lg bg-amber-50 border border-amber-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health.pending_orders') }}</p>
                        <p class="text-xs text-gray-600">Require attention</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-amber-700" id="pendingOrdersCount">0</span>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="p-4 rounded-lg bg-green-50 border border-green-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health.today_revenue') }}</p>
                        <p class="text-xs text-gray-600">Today's earnings</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-green-700" id="todayRevenue">$0</span>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="p-4 rounded-lg bg-red-50 border border-red-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-600 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health.low_stock') }}</p>
                        <p class="text-xs text-gray-600">Items running low</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-red-700" id="lowStockCount">0</span>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="p-4 rounded-lg bg-gray-100 border border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-600 flex items-center justify-center">
                        <i class="fas fa-ban text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health.out_of_stock') }}</p>
                        <p class="text-xs text-gray-600">Restock needed</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-gray-700" id="outOfStockCount">0</span>
            </div>
        </div>
    </div>
</div>

<script>
// Fetch store health data
function fetchStoreHealthData() {
    // Get from sidebar stats endpoint
    fetch('/admin/sidebar-stats')
        .then(response => response.json())
        .then(data => {
            // Update pending orders
            document.getElementById('pendingOrdersCount').textContent = data.pending_orders || 0;
            
            // Update today's revenue
            const todayRevenue = data.today_revenue || 0;
            document.getElementById('todayRevenue').textContent = '$' + todayRevenue.toFixed(2);
            
            // Get from dashboard stats for low/out of stock
            fetch('/admin/dashboard-stats')
                .then(response => response.json())
                .then(statsData => {
                    // Update low stock
                    document.getElementById('lowStockCount').textContent = statsData.low_stock || 0;
                    
                    // Update out of stock
                    document.getElementById('outOfStockCount').textContent = statsData.out_of_stock || 0;
                    
                    // Add color indicators based on urgency
                    updateUrgencyIndicators(statsData);
                })
                .catch(error => {
                    console.error('Error fetching dashboard stats:', error);
                });
        })
        .catch(error => {
            console.error('Error fetching sidebar stats:', error);
        });
}

// Update color indicators based on urgency
function updateUrgencyIndicators(data) {
    const pendingOrdersEl = document.querySelector('[id*="pendingOrdersCount"]').closest('.p-4');
    const lowStockEl = document.querySelector('[id*="lowStockCount"]').closest('.p-4');
    const outOfStockEl = document.querySelector('[id*="outOfStockCount"]').closest('.p-4');
    
    // Pending orders urgency
    if (data.pending_orders > 10) {
        pendingOrdersEl.classList.add('bg-red-50', 'border-red-200');
        pendingOrdersEl.classList.remove('bg-amber-50', 'border-amber-200');
    } else if (data.pending_orders === 0) {
        pendingOrdersEl.classList.add('bg-green-50', 'border-green-200');
        pendingOrdersEl.classList.remove('bg-amber-50', 'border-amber-200');
    }
    
    // Low stock urgency
    if (data.low_stock > 20) {
        lowStockEl.classList.add('bg-red-50', 'border-red-200');
        lowStockEl.classList.remove('bg-red-50', 'border-red-200');
    } else if (data.low_stock === 0) {
        lowStockEl.classList.add('bg-green-50', 'border-green-200');
        lowStockEl.classList.remove('bg-red-50', 'border-red-200');
    }
    
    // Out of stock urgency
    if (data.out_of_stock > 50) {
        outOfStockEl.classList.add('bg-red-50', 'border-red-200');
        outOfStockEl.classList.remove('bg-gray-100', 'border-gray-200');
    } else if (data.out_of_stock === 0) {
        outOfStockEl.classList.add('bg-green-50', 'border-green-200');
        outOfStockEl.classList.remove('bg-gray-100', 'border-gray-200');
    }
}

// Fetch data on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchStoreHealthData();
    
    // Refresh every 30 seconds
    setInterval(fetchStoreHealthData, 30000);
});
</script>