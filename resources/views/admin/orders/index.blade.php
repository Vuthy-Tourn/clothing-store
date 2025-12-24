@extends('admin.layouts.app')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Order Management</h1>
                <p class="text-gray-600">Track and manage customer orders in real-time</p>
            </div>
            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <button onclick="showExportModal()"
                    class="bg-blue-600 text-white hover:bg-blue-700 px-5 py-3 rounded-xl font-medium transition-all duration-300 flex items-center group shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2 group-hover:rotate-180 transition-transform duration-500"></i>
                    <span class="group-hover:scale-105 transition-transform duration-300">Export Orders</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $totalOrders = \App\Models\Order::count();
            $totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total_amount');
            $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
            $pendingOrders = \App\Models\Order::where('order_status', 'pending')->count();
        @endphp

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalOrders }}</p>
                    <p class="text-blue-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-shopping-bag mr-1"></i> All time orders
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($totalRevenue, 2) }}</p>
                    <p class="text-green-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-dollar-sign mr-1"></i> From completed orders
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Avg. Order Value</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($avgOrderValue, 2) }}</p>
                    <p class="text-purple-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-chart-line mr-1"></i> Average order size
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Pending Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $pendingOrders }}</p>
                    <p class="text-yellow-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-clock mr-1"></i> Needs attention
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-500 flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up"
        data-aos-delay="150">
        <!-- Filters -->
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" id="orderSearch" placeholder="Search by order number, customer name or email..."
                        onkeyup="filterOrders()"
                        class="pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>

                <!-- Status Filter -->
                <div>
                    <select id="statusFilter" onchange="filterOrders()"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
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

                <!-- Payment Filter -->
                <div>
                    <select id="paymentFilter" onchange="filterOrders()"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
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
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="clearFilters()"
                    class="text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i> Clear All Filters
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Order ID</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Customer</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Amount</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Payment</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Date</th>
                        <th class="py-4 px-6 text-left text-gray-700 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="ordersTableBody">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 order-row"
                            data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}"
                            data-customer="{{ strtolower($order->user->name ?? 'Guest') }}"
                            data-customer-email="{{ strtolower($order->user->email ?? '') }}"
                            data-status="{{ $order->order_status }}" data-payment="{{ $order->payment_status }}"
                            data-date="{{ $order->created_at->format('Y-m-d') }}"
                            data-timestamp="{{ $order->created_at->timestamp }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-receipt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <span
                                            class="font-mono font-bold text-gray-800 block">{{ $order->order_number }}</span>
                                        <span
                                            class="text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $order->user->name ?? 'Guest' }}</p>
                                    <p class="text-gray-500 text-sm">{{ $order->user->email ?? 'No email' }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <span
                                        class="font-bold text-gray-800 text-lg">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                {{ $order->order_status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : ($order->order_status === 'confirmed'
                                        ? 'bg-blue-100 text-blue-800'
                                        : ($order->order_status === 'processing'
                                            ? 'bg-purple-100 text-purple-800'
                                            : ($order->order_status === 'shipped'
                                                ? 'bg-indigo-100 text-indigo-800'
                                                : ($order->order_status === 'delivered'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($order->order_status === 'cancelled'
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-gray-100 text-gray-800'))))) }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                {{ $order->payment_status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : ($order->payment_status === 'paid'
                                        ? 'bg-green-100 text-green-800'
                                        : ($order->payment_status === 'failed'
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-gray-600 text-sm">
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="viewOrderDetails({{ $order->id }})"
                                        class="w-10 h-10 rounded-lg bg-blue-600 text-white hover:bg-blue-700 flex items-center justify-center transition-all duration-300 group"
                                        title="View Details">
                                        <i class="fas fa-eye group-hover:scale-110 transition-transform duration-300"></i>
                                    </button>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                                        class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-blue-600 flex items-center justify-center transition-all duration-300 group"
                                        title="Download Invoice">
                                        <i
                                            class="fas fa-file-invoice group-hover:rotate-12 transition-transform duration-300"></i>
                                    </a>
                                    <div class="relative">
                                        <button onclick="toggleActionsMenu({{ $order->id }})"
                                            class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-blue-600 flex items-center justify-center transition-all duration-300 group"
                                            title="More Actions">
                                            <i
                                                class="fas fa-ellipsis-h group-hover:rotate-90 transition-transform duration-300"></i>
                                        </button>
                                        <div id="actionsMenu-{{ $order->id }}"
                                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-50 hidden">
                                            <div class="py-2">
                                                <button onclick="updateStatus({{ $order->id }})"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 flex items-center">
                                                    <i class="fas fa-sync-alt mr-3 text-gray-400"></i> Update Status
                                                </button>
                                                <button onclick="updatePayment({{ $order->id }})"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 flex items-center">
                                                    <i class="fas fa-credit-card mr-3 text-gray-400"></i> Update Payment
                                                </button>
                                                <button onclick="addTracking({{ $order->id }})"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 flex items-center">
                                                    <i class="fas fa-truck mr-3 text-gray-400"></i> Add Tracking
                                                </button>
                                                <div class="border-t border-gray-100 my-2"></div>
                                                <button
                                                    onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}')"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 flex items-center">
                                                    <i class="fas fa-trash-alt mr-3"></i> Delete Order
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noOrdersRow">
                            <td colspan="7" class="py-12 text-center">
                                <div
                                    class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-700 mb-3">No Orders Found</h3>
                                <p class="text-gray-500">Start by adding some products to your store</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <!-- Pagination -->
        @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
            <div class="p-6 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        @if ($orders->total() > 0)
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                            results
                        @else
                            No results found
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Previous Button --}}
                        @if ($orders->onFirstPage())
                            <span
                                class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg font-medium text-sm cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium text-sm transition-colors duration-200">
                                Previous
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        <div class="flex items-center gap-1">
                            @php
                                $current = $orders->currentPage();
                                $last = $orders->lastPage();
                                $pages = [];

                                // Always show first page
                                if ($current > 2) {
                                    $pages[] = 1;
                                    if ($current > 3) {
                                        $pages[] = '...';
                                    }
                                }

                                // Show pages around current
                                for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++) {
                                    $pages[] = $i;
                                }

                                // Always show last page
                                if ($current < $last - 1) {
                                    if ($current < $last - 2) {
                                        $pages[] = '...';
                                    }
                                    $pages[] = $last;
                                }
                            @endphp

                            @foreach ($pages as $page)
                                @if ($page === '...')
                                    <span class="px-2 text-gray-400">...</span>
                                @elseif ($page == $orders->currentPage())
                                    <span
                                        class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center font-semibold">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $orders->url($page) }}"
                                        class="w-10 h-10 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg flex items-center justify-center font-medium transition-colors duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        {{-- Next Button --}}
                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium text-sm transition-colors duration-200">
                                Next
                            </a>
                        @else
                            <span
                                class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg font-medium text-sm cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-slideIn">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-receipt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 id="modalTitle" class="text-xl font-bold text-gray-800">Order Details</h2>
                            <p id="modalSubtitle" class="text-gray-600 text-sm"></p>
                        </div>
                    </div>
                    <button onclick="closeModal('orderDetailsModal')"
                        class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-times text-gray-500 text-lg"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto max-h-[60vh]" id="modalContent">
                <!-- Content loaded via AJAX -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-600">Loading order details...</p>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-end gap-3">
                    <button onclick="closeModal('orderDetailsModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg font-medium transition-colors duration-200">
                        Close
                    </button>
                    <button onclick="printOrder()"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideIn">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Update Order Status</h3>
                    <button onclick="closeModal('statusModal')"
                        class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-times text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form id="statusForm" onsubmit="submitStatusUpdate(event)">
                <input type="hidden" id="statusOrderId">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Status</label>
                            <select name="order_status" id="newStatus"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="admin_notes" id="statusNotes" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Add any notes about this status change..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('statusModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Update Modal -->
    <div id="paymentModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideIn">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Update Payment Status</h3>
                    <button onclick="closeModal('paymentModal')"
                        class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-times text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form id="paymentForm" onsubmit="submitPaymentUpdate(event)">
                <input type="hidden" id="paymentOrderId">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                            <select name="payment_status" id="paymentStatus"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment ID/Reference</label>
                            <input type="text" name="payment_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Transaction ID or reference">
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('paymentModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium">
                        Update Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tracking Modal -->
    <div id="trackingModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideIn">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Add Tracking Information</h3>
                    <button onclick="closeModal('trackingModal')"
                        class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-times text-gray-500"></i>
                    </button>
                </div>
            </div>
            <form id="trackingForm" onsubmit="submitTrackingInfo(event)">
                <input type="hidden" id="trackingOrderId">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number*</label>
                            <input type="text" name="tracking_number" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter tracking number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Courier/Shipping Method</label>
                            <input type="text" name="shipping_method"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g., UPS, FedEx, DHL">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery Date</label>
                            <input type="date" name="estimated_delivery"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('trackingModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium">
                        Save Tracking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideIn">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Export Orders</h3>
            </div>
            <form id="exportForm" action="{{ route('admin.orders.export') }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="format" value="csv" checked class="sr-only peer">
                                    <div
                                        class="p-3 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-file-csv text-green-600 text-lg mb-1"></i>
                                        <p class="font-medium text-sm">CSV</p>
                                        <p class="text-gray-500 text-xs">Excel compatible</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="format" value="pdf" class="sr-only peer">
                                    <div
                                        class="p-3 border-2 border-gray-200 rounded-lg text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-file-pdf text-red-600 text-lg mb-1"></i>
                                        <p class="font-medium text-sm">PDF</p>
                                        <p class="text-gray-500 text-xs">Printable format</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Date Range</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="date" name="from_date" required
                                    value="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m-d') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <input type="date" name="to_date" required
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('exportModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Real-time filtering
        function filterOrders() {
            const searchTerm = document.getElementById('orderSearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const paymentFilter = document.getElementById('paymentFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            const rows = document.querySelectorAll('.order-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const customer = row.getAttribute('data-customer');
                const customerEmail = row.getAttribute('data-customer-email');
                const orderNumber = row.getAttribute('data-order-number').toLowerCase();
                const status = row.getAttribute('data-status');
                const payment = row.getAttribute('data-payment');
                const date = row.getAttribute('data-date');

                const matchesSearch = !searchTerm ||
                    customer.includes(searchTerm) ||
                    customerEmail.includes(searchTerm) ||
                    orderNumber.includes(searchTerm);

                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesPayment = !paymentFilter || payment === paymentFilter;

                let matchesDate = true;
                if (dateFilter) {
                    const now = new Date();
                    const rowDate = new Date(date);

                    switch (dateFilter) {
                        case 'today':
                            const today = new Date().toISOString().split('T')[0];
                            matchesDate = date === today;
                            break;
                        case 'yesterday':
                            const yesterday = new Date();
                            yesterday.setDate(yesterday.getDate() - 1);
                            matchesDate = date === yesterday.toISOString().split('T')[0];
                            break;
                        case 'week':
                            const weekAgo = new Date();
                            weekAgo.setDate(weekAgo.getDate() - 7);
                            matchesDate = rowDate >= weekAgo;
                            break;
                        case 'month':
                            const monthAgo = new Date();
                            monthAgo.setMonth(monthAgo.getMonth() - 1);
                            matchesDate = rowDate >= monthAgo;
                            break;
                        case 'year':
                            const yearAgo = new Date();
                            yearAgo.setFullYear(yearAgo.getFullYear() - 1);
                            matchesDate = rowDate >= yearAgo;
                            break;
                    }
                }

                if (matchesSearch && matchesStatus && matchesPayment && matchesDate) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle no results message
            const noOrdersRow = document.getElementById('noOrdersRow');
            const tableBody = document.getElementById('ordersTableBody');

            // Remove any existing filter message
            const existingFilterMessage = document.getElementById('filterNoOrders');
            if (existingFilterMessage) {
                existingFilterMessage.remove();
            }

            // If no rows are visible, show appropriate message
            if (visibleCount === 0) {
                // Check if there are actual orders (not just filtered out)
                if (rows.length > 0) {
                    // Create filter no results message
                    const filterMessage = document.createElement('tr');
                    filterMessage.id = 'filterNoOrders';
                    filterMessage.innerHTML = `
                <td colspan="7" class="py-12 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-3">No Orders Match Your Filters</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search or filters</p>
                    <button onclick="clearFilters()" 
                            class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium transition-colors duration-200">
                        Clear All Filters
                    </button>
                </td>
            `;
                    tableBody.appendChild(filterMessage);
                }
                // Always hide the original "no orders" row when filtering
                if (noOrdersRow) {
                    noOrdersRow.style.display = 'none';
                }
            } else {
                // Show the original "no orders" row only if there are truly no orders
                if (noOrdersRow && rows.length === 0) {
                    noOrdersRow.style.display = '';
                } else if (noOrdersRow) {
                    noOrdersRow.style.display = 'none';
                }
            }
        }

        function clearFilters() {
            document.getElementById('orderSearch').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('paymentFilter').value = '';
            document.getElementById('dateFilter').value = '';
            filterOrders();
        }

        // Initialize filters on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterOrders(); // Apply any existing filters from page load

            // Add debounce to search input for better performance
            let searchTimeout;
            const searchInput = document.getElementById('orderSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(filterOrders, 300);
                });
            }

            // Also add event listeners to filters
            const filters = ['statusFilter', 'paymentFilter', 'dateFilter'];
            filters.forEach(filterId => {
                const filterElement = document.getElementById(filterId);
                if (filterElement) {
                    filterElement.addEventListener('change', filterOrders);
                }
            });
        });

        function clearFilters() {
            document.getElementById('orderSearch').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('paymentFilter').value = '';
            document.getElementById('dateFilter').value = '';
            filterOrders();
        }

        // View Order Details with AJAX
        async function viewOrderDetails(orderId) {
            const modal = document.getElementById('orderDetailsModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            try {
                const response = await fetch(`/admin/orders/${orderId}/details`);
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Failed to load order details');
                }

                document.getElementById('modalContent').innerHTML = data.html;
                document.getElementById('modalTitle').textContent = `Order ${data.order_number}`;
                document.getElementById('modalSubtitle').textContent = `Order Details`;

            } catch (error) {
                document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Failed to Load Order</h3>
                    <p class="text-gray-600 mb-4">${error.message || 'An error occurred'}</p>
                    <button onclick="viewOrderDetails(${orderId})"
                            class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium">
                        Try Again
                    </button>
                </div>
            `;
            }
        }

        // Toggle Actions Menu
        function toggleActionsMenu(orderId) {
            const menuId = `actionsMenu-${orderId}`;
            const menu = document.getElementById(menuId);

            // Close all other menus
            document.querySelectorAll('[id^="actionsMenu-"]').forEach(otherMenu => {
                if (otherMenu.id !== menuId) {
                    otherMenu.classList.add('hidden');
                }
            });

            menu.classList.toggle('hidden');

            // Close menu when clicking outside
            setTimeout(() => {
                const closeMenuHandler = (e) => {
                    if (!menu.contains(e.target) && !e.target.closest(
                            `[onclick="toggleActionsMenu(${orderId})"]`)) {
                        menu.classList.add('hidden');
                        document.removeEventListener('click', closeMenuHandler);
                    }
                };
                document.addEventListener('click', closeMenuHandler);
            }, 0);
        }

        // Update Status
        function updateStatus(orderId) {
            document.getElementById('statusOrderId').value = orderId;
            document.getElementById('statusModal').classList.remove('hidden');
        }

        async function submitStatusUpdate(event) {
            event.preventDefault();
            const orderId = document.getElementById('statusOrderId').value;
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(`/admin/orders/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    text: data.message,
                    confirmButtonColor: '#2563eb',
                    timer: 2000
                });

                // Update UI
                const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
                if (row) {
                    const statusBadge = row.querySelector('span.inline-flex');
                    if (statusBadge) {
                        const newStatus = formData.get('order_status');
                        const statusColors = {
                            pending: 'bg-yellow-100 text-yellow-800',
                            confirmed: 'bg-blue-100 text-blue-800',
                            processing: 'bg-purple-100 text-purple-800',
                            shipped: 'bg-indigo-100 text-indigo-800',
                            delivered: 'bg-green-100 text-green-800',
                            cancelled: 'bg-red-100 text-red-800',
                            refunded: 'bg-gray-100 text-gray-800'
                        };

                        statusBadge.className =
                            `inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold ${statusColors[newStatus]}`;
                        statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                        // Update data attribute
                        row.setAttribute('data-status', newStatus);
                    }
                }

                closeModal('statusModal');
                form.reset();

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: error.message || 'Failed to update order status',
                    confirmButtonColor: '#dc2626'
                });
            }
        }

        // Update Payment
        function updatePayment(orderId) {
            document.getElementById('paymentOrderId').value = orderId;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        async function submitPaymentUpdate(event) {
            event.preventDefault();
            const orderId = document.getElementById('paymentOrderId').value;
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(`/admin/orders/${orderId}/payment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Payment Updated',
                    text: data.message,
                    confirmButtonColor: '#2563eb',
                    timer: 2000
                });

                // Update UI
                const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
                if (row) {
                    const paymentBadges = row.querySelectorAll('span.inline-flex');
                    const paymentBadge = paymentBadges[1] || paymentBadges[0];

                    if (paymentBadge) {
                        const newPayment = formData.get('payment_status');
                        const paymentColors = {
                            pending: 'bg-yellow-100 text-yellow-800',
                            paid: 'bg-green-100 text-green-800',
                            failed: 'bg-red-100 text-red-800',
                            refunded: 'bg-gray-100 text-gray-800'
                        };

                        paymentBadge.className =
                            `inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold ${paymentColors[newPayment]}`;
                        paymentBadge.textContent = newPayment.charAt(0).toUpperCase() + newPayment.slice(1);

                        // Update data attribute
                        row.setAttribute('data-payment', newPayment);
                    }
                }

                closeModal('paymentModal');
                form.reset();

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: error.message || 'Failed to update payment status',
                    confirmButtonColor: '#dc2626'
                });
            }
        }

        // Add Tracking
        function addTracking(orderId) {
            document.getElementById('trackingOrderId').value = orderId;
            document.getElementById('trackingModal').classList.remove('hidden');
        }

        async function submitTrackingInfo(event) {
            event.preventDefault();
            const orderId = document.getElementById('trackingOrderId').value;
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(`/admin/orders/${orderId}/tracking`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Tracking Added',
                    text: data.message,
                    confirmButtonColor: '#2563eb',
                    timer: 2000
                });

                closeModal('trackingModal');
                form.reset();

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: error.message || 'Failed to add tracking information',
                    confirmButtonColor: '#dc2626'
                });
            }
        }

        // Delete Order
        function deleteOrder(orderId, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Are you sure you want to delete order <strong>${orderNumber}</strong>?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete Order',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form data
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'));
                    formData.append('_method', 'DELETE');

                    // Make API call to delete
                    fetch(`/admin/orders/${orderId}`, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove row from table
                                const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
                                if (row) {
                                    row.remove();
                                }

                                Swal.fire('Deleted!', data.message, 'success');
                            } else {
                                throw new Error(data.error);
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', error.message || 'Failed to delete order.', 'error');
                        });
                }
            });
        }

        // Export functions
        function showExportModal() {
            document.getElementById('exportModal').classList.remove('hidden');
        }

        // Modal functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Print order
        function printOrder() {
            const modalContent = document.getElementById('modalContent').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Order Details</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .section { margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f5f5f5; }
                    .total { font-weight: bold; font-size: 1.2em; }
                </style>
            </head>
            <body>
                ${modalContent}
            </body>
            </html>
        `);
            printWindow.document.close();
            printWindow.print();
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Close modals on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal('orderDetailsModal');
                    closeModal('statusModal');
                    closeModal('paymentModal');
                    closeModal('trackingModal');
                    closeModal('exportModal');
                }
            });

            // Close modals on background click
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            // Initialize radio buttons in export modal
            document.querySelectorAll('input[name="format"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('label.relative > div').forEach(div => {
                        div.classList.remove('peer-checked:border-blue-500',
                            'peer-checked:bg-blue-50');
                    });
                    if (this.nextElementSibling) {
                        this.nextElementSibling.classList.add('peer-checked:border-blue-500',
                            'peer-checked:bg-blue-50');
                    }
                });
                if (radio.checked && radio.nextElementSibling) {
                    radio.nextElementSibling.classList.add('peer-checked:border-blue-500',
                        'peer-checked:bg-blue-50');
                }
            });
        });

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
    `;
        document.head.appendChild(style);
    </script>
@endpush
