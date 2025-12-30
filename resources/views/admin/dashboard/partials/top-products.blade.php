<div class="card p-6" data-aos="fade-up">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">{{ __('admin.top_products.title') }}</h2>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-Ocean hover:text-blue-700 font-medium">
            {{ __('admin.top_products.view_all') }}<i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <canvas id="topProductsChart" style="max-height: 300px;"></canvas>
</div>