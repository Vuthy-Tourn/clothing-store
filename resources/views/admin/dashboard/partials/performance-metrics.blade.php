<div class="card p-6" data-aos="fade-up">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Performance Metrics</h2>
    
    <div class="space-y-6">
        <!-- Average Order Value -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Avg. Order Value</span>
                <span class="text-sm font-bold text-gray-900" id="avgOrderValue">₹0</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%" id="avgOrderBar"></div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Conversion Rate</span>
                <span class="text-sm font-bold text-gray-900" id="conversionRate">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full transition-all" style="width: 0%" id="conversionBar"></div>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Customer Satisfaction</span>
                <span class="text-sm font-bold text-gray-900" id="satisfaction">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full transition-all" style="width: 0%" id="satisfactionBar"></div>
            </div>
        </div>

        <!-- Order Fulfillment -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Order Fulfillment</span>
                <span class="text-sm font-bold text-gray-900" id="fulfillment">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-orange-600 h-2 rounded-full transition-all" style="width: 0%" id="fulfillmentBar"></div>
            </div>
        </div>
    </div>
</div>