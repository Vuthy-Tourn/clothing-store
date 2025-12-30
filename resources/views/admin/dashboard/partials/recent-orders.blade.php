<div class="card p-6" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">{{ __('admin.recent_orders.title') }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-Ocean hover:text-blue-700 font-medium">
            {{ __('admin.recent_orders.view_all') }}<i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="space-y-4" id="recentOrdersList">
        <!-- Orders will be loaded dynamically -->
        <div class="flex items-center justify-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
        </div>
    </div>
</div>