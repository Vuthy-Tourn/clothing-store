<!-- Header -->
<div class="mb-8" data-aos="fade-down">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin.header.title') }}</h1>
            <p class="text-gray-600">{{ __('admin.header.subtitle') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="refreshDashboard()" class="card px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm font-medium">
                <i class="fas fa-sync-alt mr-2"></i>
                {{ __('admin.header.refresh') }}
            </button>
            <div class="card px-4 py-2 text-gray-700 text-sm font-medium">
                <i class="fas fa-calendar mr-2"></i>
                <span id="currentDate"></span>
            </div>
        </div>
    </div>
</div>