@forelse ($orders as $order)
    <tr class="hover:bg-Lace/50 transition-all duration-200 cursor-pointer" 
        onclick="viewOrderDetails({{ $order->id }})"
        data-aos="fade-in" data-aos-delay="{{ $loop->index * 50 }}">
        <td class="py-4 px-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-Ocean/20 to-Ocean/10 flex items-center justify-center">
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
                <div class="flex items-center gap-2">
                    <p class="font-semibold text-Ocean">{{ $order->user->name ?? 'Guest' }}</p>
                    @if($order->user)
                        <span class="text-xs px-2 py-0.5 bg-Ocean/10 text-Ocean rounded-full">
                            <i class="fas fa-user mr-1"></i>Registered
                        </span>
                    @else
                        <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                            <i class="fas fa-user-slash mr-1"></i>Guest
                        </span>
                    @endif
                </div>
                <p class="text-Wave text-sm truncate">{{ $order->user->email ?? 'No email' }}</p>
                @if($order->shippingAddress)
                <div class="flex items-center text-Wave text-xs mt-1">
                    <i class="fas fa-map-marker-alt mr-1.5"></i>
                    <span class="truncate">{{ $order->shippingAddress->city ?? '' }}, {{ $order->shippingAddress->country ?? '' }}</span>
                </div>
                @endif
            </div>
        </td>
        <td class="py-4 px-6">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-Ocean/10 flex items-center justify-center mr-2">
                    <span class="text-Ocean font-semibold text-sm">{{ $order->items_count ?? 0 }}</span>
                </div>
                <span class="font-semibold text-Ocean">{{ $order->items_count ?? 0 }} items</span>
            </div>
        </td>
        <td class="py-4 px-6">
            <div>
                <span class="font-bold text-Ocean text-lg">${{ number_format($order->total_amount, 2) }}</span>
                <div class="text-Wave text-xs mt-1 space-y-0.5">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount:</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </td>
        <td class="py-4 px-6">
            <div class="flex flex-col gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 
                    {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                       ($order->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' :
                       ($order->order_status === 'processing' ? 'bg-purple-100 text-purple-800 hover:bg-purple-200' :
                       ($order->order_status === 'shipped' ? 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' :
                       ($order->order_status === 'delivered' ? 'bg-green-100 text-green-800 hover:bg-green-200' :
                       ($order->order_status === 'cancelled' ? 'bg-red-100 text-red-800 hover:bg-red-200' :
                       'bg-gray-100 text-gray-800 hover:bg-gray-200'))))) }}">
                    <i class="fas fa-circle text-[6px] mr-1.5 {{ 
                        $order->order_status === 'pending' ? 'animate-pulse' : '' }}"></i>
                    {{ ucfirst($order->order_status) }}
                </span>
                @if($order->tracking_number)
                <div class="flex items-center text-Wave text-xs">
                    <i class="fas fa-shipping-fast mr-1.5"></i>
                    <span class="truncate">{{ $order->tracking_number }}</span>
                </div>
                @endif
            </div>
        </td>
        <td class="py-4 px-6">
            <div class="flex flex-col gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200
                    {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                       ($order->payment_status === 'paid' ? 'bg-green-100 text-green-800 hover:bg-green-200' :
                       ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800 hover:bg-red-200' :
                       ($order->payment_status === 'refunded' ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' :
                       'bg-gray-100 text-gray-800 hover:bg-gray-200'))) }}">
                    <i class="fas fa-circle text-[6px] mr-1.5"></i>
                    {{ ucfirst($order->payment_status) }}
                </span>
                @if($order->payment_method)
                <div class="flex items-center text-Wave text-xs">
                    <i class="fas fa-credit-card mr-1.5"></i>
                    <span>{{ ucfirst($order->payment_method) }}</span>
                </div>
                @endif
            </div>
        </td>
        <td class="py-4 px-6">
            <div class="text-Wave text-sm">
                <div class="font-medium">{{ $order->created_at->format('d M Y') }}</div>
                <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                @if($order->estimated_delivery)
                <div class="text-xs mt-1 flex items-center text-green-600">
                    <i class="fas fa-truck mr-1.5"></i>
                    <span>Est: {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d') }}</span>
                </div>
                @endif
            </div>
        </td>
        <td class="py-4 px-6" onclick="event.stopPropagation();">
            <div class="flex items-center gap-2">
                <button onclick="viewOrderDetails({{ $order->id }})"
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-Ocean to-Ocean/90 text-Pearl hover:from-Ocean/90 hover:to-Ocean flex items-center justify-center transition-all duration-200 group shadow-sm hover:shadow"
                    title="View Details">
                    <i class="fas fa-eye group-hover:scale-110 transition-transform duration-300"></i>
                </button>
                <a href="{{ route('admin.orders.invoice', $order->id) }}"
                    class="w-10 h-10 rounded-lg bg-white border border-Silk text-Wave hover:bg-Lace hover:text-Ocean flex items-center justify-center transition-all duration-200 group shadow-sm hover:shadow"
                    title="Invoice" target="_blank">
                    <i class="fas fa-file-invoice group-hover:rotate-12 transition-transform duration-300"></i>
                </a>
                <div class="relative">
                    <button onclick="event.stopPropagation(); showOrderActions({{ $order->id }}, '{{ $order->order_number }}')"
                        class="w-10 h-10 rounded-lg bg-white border border-Silk text-Wave hover:bg-Lace hover:text-Ocean flex items-center justify-center transition-all duration-200 group shadow-sm hover:shadow"
                        title="More Actions">
                        <i class="fas fa-ellipsis-h group-hover:rotate-90 transition-transform duration-300"></i>
                    </button>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="py-16 text-center">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-Lace to-Silk flex items-center justify-center mx-auto mb-6 border-4 border-Ocean/20">
                <i class="fas fa-shopping-bag text-Ocean text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-Ocean mb-3">
                @if(request()->anyFilled(['search', 'status', 'payment_status', 'date_range']))
                    No Orders Found
                @else
                    No Orders Yet
                @endif
            </h3>
            <p class="text-Wave mb-6 max-w-md mx-auto">
                @if(request()->anyFilled(['search', 'status', 'payment_status', 'date_range']))
                    No orders match your current filters. Try adjusting your search criteria.
                @else
                    Orders will appear here when customers make purchases from your store.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'status', 'payment_status', 'date_range']))
            <button onclick="window.location.href='{{ route('admin.orders.index') }}'" 
                    class="bg-gradient-to-r from-Ocean to-Ocean/90 text-Pearl hover:from-Ocean/90 hover:to-Ocean px-6 py-3 rounded-lg font-medium transition-all duration-200 inline-flex items-center shadow-sm hover:shadow">
                <i class="fas fa-times mr-2"></i> Clear Filters
            </button>
            @endif
        </td>
    </tr>
@endforelse