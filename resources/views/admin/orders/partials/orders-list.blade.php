@forelse ($orders as $order)
<tr class="hover:bg-gray-50 transition-colors duration-200" data-order-id="{{ $order->id }}">
    <td class="py-4 px-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-receipt text-blue-600"></i>
            </div>
            <div>
                <span class="font-mono font-bold text-gray-800 block">{{ $order->order_number }}</span>
                <span class="text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</span>
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
            <span class="font-bold text-gray-800 text-lg">${{ number_format($order->total_amount, 2) }}</span>
        </div>
    </td>
    <td class="py-4 px-6">
        <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
            {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
               ($order->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
               ($order->order_status === 'processing' ? 'bg-purple-100 text-purple-800' :
               ($order->order_status === 'shipped' ? 'bg-indigo-100 text-indigo-800' :
               ($order->order_status === 'delivered' ? 'bg-green-100 text-green-800' :
               ($order->order_status === 'cancelled' ? 'bg-red-100 text-red-800' :
               'bg-gray-100 text-gray-800'))))) }}">
            {{ ucfirst($order->order_status) }}
        </span>
    </td>
    <td class="py-4 px-6">
        <span class="payment-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
            {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
               ($order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
               ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' :
               'bg-gray-100 text-gray-800')) }}">
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
            @if(route('admin.orders.invoice', $order->id))
            <a href="{{ route('admin.orders.invoice', $order->id) }}"
                    class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-blue-600 flex items-center justify-center transition-all duration-300 group"
                    title="Invoice">
                <i class="fas fa-file-invoice group-hover:rotate-12 transition-transform duration-300"></i>
            </a>
            @endif
            <div class="relative">
                <button onclick="toggleActionsMenu({{ $order->id }})"
                        class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-blue-600 flex items-center justify-center transition-all duration-300 group"
                        title="More Actions">
                    <i class="fas fa-ellipsis-h group-hover:rotate-90 transition-transform duration-300"></i>
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
                        <button onclick="sendNotification({{ $order->id }})"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 flex items-center">
                            <i class="fas fa-bell mr-3 text-gray-400"></i> Send Notification
                        </button>
                        <div class="border-t border-gray-100 my-2"></div>
                        <button onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}')"
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
<tr>
    <td colspan="7" class="py-12 text-center">
        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-3">No Orders Found</h3>
        <p class="text-gray-500">Try adjusting your search or filters</p>
    </td>
</tr>
@endforelse