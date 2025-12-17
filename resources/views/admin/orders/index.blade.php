@extends('admin.layouts.app')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-Ocean mb-2">Order Management</h1>
                <p class="text-Wave">Track and manage customer orders in real-time</p>
            </div>
            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <button onclick="exportOrders()" 
                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-5 py-3 rounded-xl font-medium transition-all duration-200 flex items-center group">
                    <i class="fas fa-download mr-2 group-hover:rotate-180 transition-transform duration-300"></i> Export Orders
                </button>
            </div>
        </div>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
        <div class="bg-white border border-Silk rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium mb-1">Total Orders</p>
                    <h3 class="text-2xl font-bold text-Ocean">{{ $orders->count() }}</h3>
                    <p class="text-Wave text-xs mt-1">All time orders</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-Ocean text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-Silk rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium mb-1">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-green-600">
                        ${{ number_format($orders->whereIn('payment_status', ['paid'])->sum('total_amount'), 2) }}
                    </h3>
                    <p class="text-Wave text-xs mt-1">From completed orders</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-Silk rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium mb-1">Avg. Order Value</p>
                    <h3 class="text-2xl font-bold text-purple-600">
                        ${{ $orders->count() > 0 ? number_format($orders->avg('total_amount'), 2) : '0.00' }}
                    </h3>
                    <p class="text-Wave text-xs mt-1">Average order size</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-Silk rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium mb-1">Pending Orders</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ $orders->where('order_status', 'pending')->count() }}</h3>
                    <p class="text-Wave text-xs mt-1">Needs attention</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table with Filters -->
    <div class="bg-white border border-Silk rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up" data-aos-delay="150">
        <!-- Search and Filters Header -->
        <div class="p-6 border-b border-Lace">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" id="orderSearch" placeholder="Search orders by ID, customer..." 
                           class="pl-10 pr-4 py-3 border border-Silk rounded-xl focus:outline-none focus:ring-2 focus:ring-Ocean focus:border-Ocean transition-all duration-200 w-full"
                           onkeyup="filterOrders()">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-Wave"></i>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <select id="statusFilter" onchange="filterOrders()"
                            class="w-full border border-Silk rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean focus:border-Ocean transition-all duration-200 appearance-none bg-white">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                
                <!-- Payment Status Filter -->
                <div>
                    <select id="paymentFilter" onchange="filterOrders()"
                            class="w-full border border-Silk rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean focus:border-Ocean transition-all duration-200 appearance-none bg-white">
                        <option value="">All Payment Status</option>
                        <option value="pending">Payment Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Payment Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div>
                    <select id="dateFilter" onchange="filterOrders()"
                            class="w-full border border-Silk rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean focus:border-Ocean transition-all duration-200 appearance-none bg-white">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            
            <!-- Filter Results Info -->
            <div id="filterResults" class="mt-4 hidden">
                <div class="flex items-center justify-between bg-Lace/50 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-filter text-Ocean mr-3"></i>
                        <span class="text-Wave text-sm">
                            Showing <span id="filteredCount" class="font-semibold text-Ocean"></span> of 
                            <span class="font-semibold text-Ocean">{{ $orders->count() }}</span> orders
                        </span>
                    </div>
                    <button onclick="clearFilters()" 
                            class="text-sm text-Wave hover:text-Ocean font-medium flex items-center transition-colors duration-200">
                        <i class="fas fa-times mr-1"></i> Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Header -->
        <div class="flex items-center justify-between p-6 border-b border-Lace">
            <div>
                <h2 class="text-xl font-bold text-Ocean">Recent Orders</h2>
                <p class="text-Wave text-sm mt-1" id="tableInfo">Latest customer orders</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-Wave">
                <i class="fas fa-info-circle mr-1"></i>
                Click on order for details
            </div>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
            <table class="w-full" id="ordersTable">
                <thead class="bg-Lace">
                    <tr>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm cursor-pointer" onclick="sortTable('order_number')">
                            Order ID <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm cursor-pointer" onclick="sortTable('customer')">
                            Customer <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm cursor-pointer" onclick="sortTable('items')">
                            Items <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm cursor-pointer" onclick="sortTable('amount')">
                            Amount <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Payment</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm cursor-pointer" onclick="sortTable('date')">
                            Date <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-Lace" id="ordersTableBody">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-Lace/50 transition-colors duration-200 order-row" 
                            data-order-id="{{ $order->order_number }}"
                            data-customer="{{ strtolower($order->user->name ?? 'guest') }}"
                            data-items="{{ $order->items_count ?? 0 }}"
                            data-amount="{{ $order->total_amount }}"
                            data-status="{{ $order->order_status }}"
                            data-payment="{{ $order->payment_status }}"
                            data-date="{{ $order->created_at->timestamp }}"
                            data-aos="fade-in" data-aos-delay="{{ $loop->index * 50 }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-Ocean/10 flex items-center justify-center">
                                        <i class="fas fa-receipt text-Ocean"></i>
                                    </div>
                                    <div>
                                        <span class="font-mono font-bold text-Ocean block">{{ $order->order_number }}</span>
                                        <span class="text-Wave text-xs">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-semibold text-Ocean">{{ $order->user->name ?? 'Guest' }}</p>
                                    <p class="text-Wave text-sm">{{ $order->user->email ?? 'No email' }}</p>
                                    @if($order->shippingAddress)
                                    <p class="text-Wave text-xs mt-1 truncate max-w-xs">{{ $order->shippingAddress->city ?? '' }}, {{ $order->shippingAddress->country ?? '' }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <span class="font-semibold text-Ocean">{{ $order->items_count ?? 0 }} items</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <span class="font-bold text-Ocean text-lg">${{ number_format($order->total_amount, 2) }}</span>
                                    <div class="text-Wave text-xs mt-1">
                                        <span class="mr-2">Sub: ${{ number_format($order->subtotal, 2) }}</span>
                                        @if($order->discount_amount > 0)
                                        <span class="text-green-600">Disc: -${{ number_format($order->discount_amount, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                        {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($order->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                           ($order->order_status === 'processing' ? 'bg-purple-100 text-purple-800' :
                                           ($order->order_status === 'shipped' ? 'bg-indigo-100 text-indigo-800' :
                                           ($order->order_status === 'delivered' ? 'bg-green-100 text-green-800' :
                                           ($order->order_status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                           'bg-gray-100 text-gray-800'))))) }}">
                                        <i class="fas fa-circle text-[6px] mr-1.5 {{ 
                                            $order->order_status === 'pending' ? 'animate-pulse' : '' }}"></i>
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    @if($order->tracking_number)
                                    <span class="text-Wave text-xs">Track: {{ $order->tracking_number }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                        {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                           ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' :
                                           ($order->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800' :
                                           'bg-gray-100 text-gray-800'))) }}">
                                        <i class="fas fa-circle text-[6px] mr-1.5"></i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    @if($order->payment_method)
                                    <span class="text-Wave text-xs">{{ ucfirst($order->payment_method) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-Wave text-sm">
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                                    @if($order->estimated_delivery)
                                    <div class="text-xs mt-1">
                                        <i class="fas fa-truck mr-1"></i>Est: {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d') }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="viewOrderDetails({{ $order->id }})"
                                        class="w-10 h-10 rounded-lg bg-Ocean text-Pearl hover:bg-Ocean/90 flex items-center justify-center transition-all duration-200 group"
                                        title="View Details">
                                        <i class="fas fa-eye group-hover:scale-110 transition-transform duration-300"></i>
                                    </button>
                                    <a href="{{ route('admin.orders.invoice', ['order' => $order->id]) }}"
                                        class="w-10 h-10 rounded-lg bg-white border border-Silk text-Wave hover:bg-Lace hover:text-Ocean flex items-center justify-center transition-all duration-200 group"
                                        title="Invoice" target="_blank">
                                        <i class="fas fa-file-invoice group-hover:rotate-12 transition-transform duration-300"></i>
                                    </a>
                                    <button onclick="showOrderActions({{ $order->id }}, '{{ $order->order_number }}')"
                                        class="w-10 h-10 rounded-lg bg-white border border-Silk text-Wave hover:bg-Lace hover:text-Ocean flex items-center justify-center transition-all duration-200 group"
                                        title="More Actions">
                                        <i class="fas fa-ellipsis-h group-hover:rotate-90 transition-transform duration-300"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noOrdersRow">
                            <td colspan="8" class="py-16 text-center">
                                <div class="w-24 h-24 rounded-full bg-Lace flex items-center justify-center mx-auto mb-6 border-4 border-Silk">
                                    <i class="fas fa-shopping-bag text-Ocean text-3xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-Ocean mb-3">No Orders Yet</h3>
                                <p class="text-Wave mb-6 max-w-md mx-auto">Orders will appear here when customers make purchases from your store</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        {{-- @if ($orders->hasPages())
        <div class="p-6 border-t border-Lace bg-Lace/50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Page Info -->
                <div class="text-sm text-Wave">
                    Showing <span class="font-semibold text-Ocean">{{ $orders->firstItem() ?? 0 }}</span> to 
                    <span class="font-semibold text-Ocean">{{ $orders->lastItem() ?? 0 }}</span> of 
                    <span class="font-semibold text-Ocean">{{ $orders->total() }}</span> results
                </div>
                
                <!-- Pagination Controls -->
                <div class="flex items-center gap-2">
                    <!-- Previous Button -->
                    @if ($orders->onFirstPage())
                        <span class="px-4 py-2 bg-white border border-Silk text-Wave rounded-lg font-medium text-sm cursor-not-allowed opacity-50 flex items-center">
                            <i class="fas fa-chevron-left mr-2"></i> Previous
                        </span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" 
                           class="px-4 py-2 bg-white border border-Silk text-Wave hover:text-Ocean hover:border-Ocean rounded-lg font-medium text-sm transition-all duration-200 flex items-center group">
                            <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform duration-200"></i> Previous
                        </a>
                    @endif
                    
                    <!-- Page Numbers -->
                    <div class="flex items-center gap-1">
                        <!-- First Page -->
                        @if ($orders->currentPage() > 3)
                            <a href="{{ $orders->url(1) }}" 
                               class="w-10 h-10 bg-white border border-Silk text-Wave hover:text-Ocean rounded-lg flex items-center justify-center font-medium hover:bg-Lace transition-all duration-200"
                               title="First Page">
                                1
                            </a>
                            @if ($orders->currentPage() > 4)
                                <span class="w-10 h-10 flex items-center justify-center text-Wave">...</span>
                            @endif
                        @endif
                        
                        <!-- Middle Pages -->
                        @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                            @if($page == $orders->currentPage())
                                <span class="w-10 h-10 bg-Ocean text-Pearl rounded-lg flex items-center justify-center font-semibold shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="w-10 h-10 bg-white border border-Silk text-Wave hover:text-Ocean rounded-lg flex items-center justify-center font-medium hover:bg-Lace transition-all duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        
                        <!-- Last Page -->
                        @if ($orders->currentPage() < $orders->lastPage() - 2)
                            @if ($orders->currentPage() < $orders->lastPage() - 3)
                                <span class="w-10 h-10 flex items-center justify-center text-Wave">...</span>
                            @endif
                            <a href="{{ $orders->url($orders->lastPage()) }}" 
                               class="w-10 h-10 bg-white border border-Silk text-Wave hover:text-Ocean rounded-lg flex items-center justify-center font-medium hover:bg-Lace transition-all duration-200"
                               title="Last Page">
                                {{ $orders->lastPage() }}
                            </a>
                        @endif
                    </div>
                    
                    <!-- Next Button -->
                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" 
                           class="px-4 py-2 bg-white border border-Silk text-Wave hover:text-Ocean hover:border-Ocean rounded-lg font-medium text-sm transition-all duration-200 flex items-center group">
                            Next <i class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 bg-white border border-Silk text-Wave rounded-lg font-medium text-sm cursor-not-allowed opacity-50 flex items-center">
                            Next <i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    @endif
                </div>
                
                <!-- Results Per Page -->
                <div class="flex items-center gap-2 text-sm text-Wave">
                    <span>Show:</span>
                    <select onchange="changePerPage(this.value)" 
                            class="border border-Silk rounded-lg px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-Ocean focus:border-Ocean bg-white">
                        <option value="10" {{ $orders->perPage() == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $orders->perPage() == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $orders->perPage() == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $orders->perPage() == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>per page</span>
                </div>
            </div>
        </div>
        @endif --}}
    </div>

    <!-- Order Details Modal (Keep from previous) -->
    <div id="orderDetailsModal" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
        <!-- Modal content remains the same as before -->
    </div>

    <!-- Order Actions Modal (Keep from previous) -->
    <div id="orderActionsModal" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
        <!-- Modal content remains the same as before -->
    </div>

    <style>
        /* Custom Animations */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            75% { transform: translateX(2px); }
        }
        
        .group-hover\:shake:hover i {
            animation: shake 0.5s ease-in-out;
        }
        
        /* Hide scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }
        
        .overflow-y-auto {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Sort indicator */
        .sort-asc i.fa-sort::before {
            content: "\f0de";
        }
        
        .sort-desc i.fa-sort::before {
            content: "\f0dd";
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Real-time filtering functionality
        function filterOrders() {
            const searchTerm = document.getElementById('orderSearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const paymentFilter = document.getElementById('paymentFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            const rows = document.querySelectorAll('.order-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const orderId = row.getAttribute('data-order-id').toLowerCase();
                const customer = row.getAttribute('data-customer');
                const status = row.getAttribute('data-status');
                const payment = row.getAttribute('data-payment');
                const date = parseInt(row.getAttribute('data-date'));
                
                // Check search term
                const matchesSearch = !searchTerm || 
                    orderId.includes(searchTerm) || 
                    customer.includes(searchTerm);
                
                // Check status filter
                const matchesStatus = !statusFilter || status === statusFilter;
                
                // Check payment filter
                const matchesPayment = !paymentFilter || payment === paymentFilter;
                
                // Check date filter
                let matchesDate = true;
                if (dateFilter) {
                    const now = new Date();
                    const rowDate = new Date(date * 1000);
                    
                    switch(dateFilter) {
                        case 'today':
                            matchesDate = rowDate.toDateString() === now.toDateString();
                            break;
                        case 'yesterday':
                            const yesterday = new Date(now);
                            yesterday.setDate(now.getDate() - 1);
                            matchesDate = rowDate.toDateString() === yesterday.toDateString();
                            break;
                        case 'week':
                            const weekAgo = new Date(now);
                            weekAgo.setDate(now.getDate() - 7);
                            matchesDate = rowDate >= weekAgo;
                            break;
                        case 'month':
                            const monthAgo = new Date(now);
                            monthAgo.setMonth(now.getMonth() - 1);
                            matchesDate = rowDate >= monthAgo;
                            break;
                        case 'year':
                            const yearAgo = new Date(now);
                            yearAgo.setFullYear(now.getFullYear() - 1);
                            matchesDate = rowDate >= yearAgo;
                            break;
                    }
                }
                
                // Show/hide row based on all filters
                if (matchesSearch && matchesStatus && matchesPayment && matchesDate) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update filter results info
            const filterResults = document.getElementById('filterResults');
            const tableInfo = document.getElementById('tableInfo');
            
            if (searchTerm || statusFilter || paymentFilter || dateFilter) {
                filterResults.classList.remove('hidden');
                document.getElementById('filteredCount').textContent = visibleCount;
                
                // Build filter description
                let description = '';
                if (searchTerm) description += `Search: "${searchTerm}" `;
                if (statusFilter) description += `Status: ${statusFilter} `;
                if (paymentFilter) description += `Payment: ${paymentFilter} `;
                if (dateFilter) description += `Date: ${dateFilter}`;
                
                tableInfo.textContent = description.trim() || 'Filtered results';
            } else {
                filterResults.classList.add('hidden');
                tableInfo.textContent = 'Latest customer orders';
            }
            
            // Show/hide no results message
            const noOrdersRow = document.getElementById('noOrdersRow');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noOrdersRow) {
                    const tbody = document.getElementById('ordersTableBody');
                    tbody.innerHTML = `
                        <tr id="noOrdersRow">
                            <td colspan="8" class="py-16 text-center">
                                <div class="w-24 h-24 rounded-full bg-Lace flex items-center justify-center mx-auto mb-6 border-4 border-Silk">
                                    <i class="fas fa-search text-Ocean text-3xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-Ocean mb-3">No Orders Found</h3>
                                <p class="text-Wave mb-4">No orders match your current filters</p>
                                <button onclick="clearFilters()" 
                                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-6 py-3 rounded-lg font-medium transition-all duration-200">
                                    Clear Filters
                                </button>
                            </td>
                        </tr>
                    `;
                }
            } else if (noOrdersRow && rows.length > 0) {
                noOrdersRow.remove();
            }
        }
        
        function clearFilters() {
            document.getElementById('orderSearch').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('paymentFilter').value = '';
            document.getElementById('dateFilter').value = '';
            filterOrders();
        }
        
        // Sorting functionality
        let currentSort = { column: null, direction: 'asc' };
        
        function sortTable(column) {
            const rows = Array.from(document.querySelectorAll('.order-row'));
            if (rows.length === 0) return;
            
            // Update sort indicator
            const headers = document.querySelectorAll('th[onclick*="sortTable"]');
            headers.forEach(header => {
                header.classList.remove('sort-asc', 'sort-desc');
                const icon = header.querySelector('i');
                if (icon) icon.className = 'fas fa-sort ml-1';
            });
            
            const currentHeader = document.querySelector(`th[onclick="sortTable('${column}')"]`);
            
            // Toggle direction if clicking same column
            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.column = column;
                currentSort.direction = 'asc';
            }
            
            // Update header styling
            currentHeader.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');
            
            // Sort rows
            rows.sort((a, b) => {
                let aValue, bValue;
                
                switch(column) {
                    case 'order_number':
                        aValue = a.getAttribute('data-order-id');
                        bValue = b.getAttribute('data-order-id');
                        break;
                    case 'customer':
                        aValue = a.getAttribute('data-customer');
                        bValue = b.getAttribute('data-customer');
                        break;
                    case 'items':
                        aValue = parseInt(a.getAttribute('data-items'));
                        bValue = parseInt(b.getAttribute('data-items'));
                        break;
                    case 'amount':
                        aValue = parseFloat(a.getAttribute('data-amount'));
                        bValue = parseFloat(b.getAttribute('data-amount'));
                        break;
                    case 'date':
                        aValue = parseInt(a.getAttribute('data-date'));
                        bValue = parseInt(b.getAttribute('data-date'));
                        break;
                    default:
                        return 0;
                }
                
                // Compare values
                let comparison = 0;
                if (typeof aValue === 'string') {
                    comparison = aValue.localeCompare(bValue);
                } else {
                    comparison = aValue - bValue;
                }
                
                return currentSort.direction === 'asc' ? comparison : -comparison;
            });
            
            // Reorder rows in table
            const tbody = document.getElementById('ordersTableBody');
            rows.forEach(row => tbody.appendChild(row));
        }
        
        // Change results per page
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.set('page', '1'); // Reset to first page
            window.location.href = url.toString();
        }
        
        // Enhanced export functionality with file download
        function exportOrders() {
            Swal.fire({
                title: 'Export Orders',
                html: `<div class="text-left">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-xl bg-Ocean/10 flex items-center justify-center">
                            <i class="fas fa-file-export text-Ocean text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-Ocean text-lg">Export Order Data</h4>
                            <p class="text-Wave text-sm">Download filtered orders in your preferred format</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-Wave text-sm font-medium mb-3">Export Format</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="exportFormat" value="csv" checked class="sr-only peer">
                                    <div class="p-4 border-2 border-Silk rounded-xl text-center peer-checked:border-Ocean peer-checked:bg-Ocean/5 transition-all duration-200 hover:bg-Lace">
                                        <i class="fas fa-file-csv text-green-600 text-xl mb-2"></i>
                                        <p class="font-medium text-Ocean">CSV</p>
                                        <p class="text-Wave text-xs">Excel compatible</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="exportFormat" value="excel" class="sr-only peer">
                                    <div class="p-4 border-2 border-Silk rounded-xl text-center peer-checked:border-Ocean peer-checked:bg-Ocean/5 transition-all duration-200 hover:bg-Lace">
                                        <i class="fas fa-file-excel text-green-700 text-xl mb-2"></i>
                                        <p class="font-medium text-Ocean">Excel</p>
                                        <p class="text-Wave text-xs">.xlsx format</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="exportFormat" value="pdf" class="sr-only peer">
                                    <div class="p-4 border-2 border-Silk rounded-xl text-center peer-checked:border-Ocean peer-checked:bg-Ocean/5 transition-all duration-200 hover:bg-Lace">
                                        <i class="fas fa-file-pdf text-red-600 text-xl mb-2"></i>
                                        <p class="font-medium text-Ocean">PDF</p>
                                        <p class="text-Wave text-xs">Printable format</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-Wave text-sm font-medium mb-2">From Date</label>
                                <input type="date" id="exportFrom" class="w-full border border-Silk rounded-lg px-4 py-3 focus:border-Ocean focus:ring-Ocean">
                            </div>
                            <div>
                                <label class="block text-Wave text-sm font-medium mb-2">To Date</label>
                                <input type="date" id="exportTo" class="w-full border border-Silk rounded-lg px-4 py-3 focus:border-Ocean focus:ring-Ocean">
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mr-3 mt-0.5"></i>
                                <div>
                                    <p class="text-blue-800 text-sm font-medium mb-1">Export includes:</p>
                                    <ul class="text-blue-700 text-sm space-y-1">
                                        <li>• Order details and customer information</li>
                                        <li>• Product items and quantities</li>
                                        <li>• Payment and shipping details</li>
                                        <li>• Current filter settings</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`,
                showCancelButton: true,
                confirmButtonColor: '#0066cc',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Download Export',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                width: '600px',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-3 rounded-lg font-semibold',
                    cancelButton: 'px-6 py-3 rounded-lg font-semibold'
                },
                didOpen: () => {
                    // Set default dates
                    const today = new Date().toISOString().split('T')[0];
                    const lastMonth = new Date();
                    lastMonth.setMonth(lastMonth.getMonth() - 1);
                    const lastMonthStr = lastMonth.toISOString().split('T')[0];
                    
                    document.getElementById('exportFrom').value = lastMonthStr;
                    document.getElementById('exportTo').value = today;
                    
                    // Initialize radio button styling
                    document.querySelectorAll('input[name="exportFormat"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            document.querySelectorAll('label.relative > div').forEach(div => {
                                div.classList.remove('peer-checked:border-Ocean', 'peer-checked:bg-Ocean/5');
                            });
                            this.nextElementSibling.classList.add('peer-checked:border-Ocean', 'peer-checked:bg-Ocean/5');
                        });
                        if (radio.checked) {
                            radio.nextElementSibling.classList.add('peer-checked:border-Ocean', 'peer-checked:bg-Ocean/5');
                        }
                    });
                },
                preConfirm: () => {
                    const format = document.querySelector('input[name="exportFormat"]:checked').value;
                    const from = document.getElementById('exportFrom').value;
                    const to = document.getElementById('exportTo').value;
                    
                    if (!from || !to) {
                        Swal.showValidationMessage('Please select both date ranges');
                        return false;
                    }
                    
                    return new Promise((resolve) => {
                        // Show loading state
                        Swal.showLoading();
                        
                        // Simulate API call with progress
                        let progress = 0;
                        const progressInterval = setInterval(() => {
                            progress += 20;
                            Swal.update({
                                html: `<div class="text-center">
                                    <div class="w-16 h-16 rounded-full bg-Ocean/10 flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-spinner fa-spin text-Ocean text-2xl"></i>
                                    </div>
                                    <h4 class="font-semibold text-Ocean mb-2">Preparing Export</h4>
                                    <p class="text-Wave text-sm mb-4">Gathering order data and generating file...</p>
                                    <div class="w-full bg-Lace rounded-full h-2">
                                        <div class="bg-Ocean h-2 rounded-full transition-all duration-300" style="width: ${progress}%"></div>
                                    </div>
                                    <p class="text-Wave text-xs mt-2">${progress}% complete</p>
                                </div>`
                            });
                            
                            if (progress >= 100) {
                                clearInterval(progressInterval);
                                setTimeout(() => {
                                    resolve({ format, from, to });
                                }, 500);
                            }
                        }, 300);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and trigger download
                    const filename = `orders_export_${result.value.from}_to_${result.value.to}.${result.value.format}`;
                    const blob = new Blob(['Simulated export data'], { type: 'text/plain' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Export Downloaded!',
                        html: `<div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-check text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-Wave mb-4">Your export file has been downloaded as:</p>
                            <div class="bg-Lace rounded-xl p-4 mb-4">
                                <code class="text-Ocean font-mono text-sm">${filename}</code>
                            </div>
                            <div class="text-sm text-Wave">
                                <p><i class="fas fa-info-circle mr-2"></i>Check your browser's downloads folder</p>
                            </div>
                        </div>`,
                        confirmButtonColor: '#0066cc',
                        confirmButtonText: 'Open Folder',
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // In a real app, this would open the downloads folder
                            // For now, we'll just show a message
                            Swal.fire({
                                icon: 'info',
                                title: 'Downloads Folder',
                                text: 'Please check your browser\'s downloads folder for the exported file.',
                                confirmButtonColor: '#0066cc',
                                timer: 2000,
                                timerProgressBar: true
                            });
                        }
                    });
                }
            });
        }
        
        // View order details function (keep from previous)
        function viewOrderDetails(orderId) {
            // Implementation from previous code
        }
        
        // Order actions function (keep from previous)
        function showOrderActions(orderId, orderNumber) {
            // Implementation from previous code
        }
        
        // Initialize filter results on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial values from URL parameters if they exist
            const urlParams = new URLSearchParams(window.location.search);
            
            if (urlParams.has('status')) {
                document.getElementById('statusFilter').value = urlParams.get('status');
            }
            
            if (urlParams.has('payment_status')) {
                document.getElementById('paymentFilter').value = urlParams.get('payment_status');
            }
            
            if (urlParams.has('date_range')) {
                document.getElementById('dateFilter').value = urlParams.get('date_range');
            }
            
            if (urlParams.has('search')) {
                document.getElementById('orderSearch').value = urlParams.get('search');
            }
            
            // Apply initial filters
            filterOrders();
            
            // Add real-time search with debounce
            let searchTimeout;
            document.getElementById('orderSearch').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterOrders, 300);
            });
        });
    </script>
    @endpush
@endsection