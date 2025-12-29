<!-- Store Health -->
<div class="card p-6" data-aos="fade-left">
    <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('admin.store_health.title') }}</h2>
    <div class="space-y-4">
        <div class="p-4 rounded-lg bg-green-50 border border-green-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health_alt.online_store') }}</p>
                        <p class="text-xs text-gray-600">All systems active</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-lg bg-gray-100 border border-gray-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-Ocean flex items-center justify-center">
                    <i class="fas fa-box text-white"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health_alt.inventory') }}</p>
                    <p class="text-xs text-gray-600"><span id="inventoryPercent">0</span>% stocked</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-Ocean h-2 rounded-full transition-all" style="width: 0%" id="inventoryBar"></div>
            </div>
        </div>

        <div class="p-4 rounded-lg bg-gray-100 border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-Ocean flex items-center justify-center">
                    <i class="fas fa-truck text-white"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ __('admin.store_health_alt.shipping') }}</p>
                    <p class="text-xs text-gray-600"><span id="pendingShipments">0</span> pending</p>
                </div>
            </div>
        </div>
    </div>
</div>