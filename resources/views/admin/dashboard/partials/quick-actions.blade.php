<div class="card p-6" data-aos="fade-up" data-aos-delay="200">
    <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('admin.quick_actions.title') }}</h2>
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center">
                <i class="fas fa-plus text-white"></i>
            </div>
            <span class="font-medium text-gray-900 text-sm">{{ __('admin.quick_actions.add_product') }}</span>
        </a>
        
        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-green-50 hover:bg-green-100 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center">
                <i class="fas fa-shopping-bag text-white"></i>
            </div>
            <span class="font-medium text-gray-900 text-sm">{{ __('admin.quick_actions.view_orders') }}</span>
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-purple-600 flex items-center justify-center">
                <i class="fas fa-users text-white"></i>
            </div>
            <span class="font-medium text-gray-900 text-sm">{{ __('admin.quick_actions.view_customers') }}</span>
        </a>
        
        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-orange-50 hover:bg-orange-100 transition-colors">
            <div class="w-10 h-10 rounded-lg bg-orange-600 flex items-center justify-center">
                <i class="fas fa-chart-bar text-white"></i>
            </div>
            <span class="font-medium text-gray-900 text-sm">{{ __('admin.quick_actions.view_reports') }}</span>
        </a>
    </div>
</div>