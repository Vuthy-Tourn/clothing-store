<!-- Sales Chart -->
<div class="lg:col-span-2 card p-6" data-aos="fade-right">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h2 class="text-xl font-bold text-gray-900">Sales Overview</h2>
        <div class="flex space-x-2">
            <button onclick="changePeriod('week')" class="period-btn active px-4 py-2 rounded-lg text-sm font-medium">Week</button>
            <button onclick="changePeriod('month')" class="period-btn px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 text-sm">Month</button>
            <button onclick="changePeriod('year')" class="period-btn px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 text-sm">Year</button>
        </div>
    </div>
    <canvas id="salesChart" class="w-full" style="max-height: 300px;"></canvas>
</div>