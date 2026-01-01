<!-- Sales Chart -->
<div class="lg:col-span-2 card p-6" data-aos="fade-right">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h2 class="text-xl font-bold text-gray-900">{{ __('admin.sales_chart.title') }}</h2>
      <div class="flex space-x-2">
    <button onclick="changePeriod('week')" 
            data-period="week"
            class="period-btn active px-4 py-2 rounded-lg text-sm font-medium bg-blue-500 text-white">
        {{ __('admin.charts.periods.week') }}
    </button>
    <button onclick="changePeriod('month')" 
            data-period="month"
            class="period-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-50 text-gray-700 hover:bg-gray-100">
        {{ __('admin.charts.periods.month') }}
    </button>
    <button onclick="changePeriod('year')" 
            data-period="year"
            class="period-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-50 text-gray-700 hover:bg-gray-100">
        {{ __('admin.charts.periods.year') }}
    </button>
</div>
    </div>
    <canvas id="salesChart" class="w-full" style="max-height: 300px;"></canvas>
</div>