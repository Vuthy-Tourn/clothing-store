@php
    use Carbon\Carbon;
@endphp

<div class="space-y-6">
    <!-- Order Summary -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.orders.details.order_summary') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-3">{{ __('admin.orders.details.customer_info') }}</h4>
                <div class="space-y-2">
                    <p><span class="text-gray-600">{{ __('admin.orders.details.customer_name') }}:</span> <span class="font-medium">{{ $order->user->name ?? 'Guest' }}</span></p>
                    <p><span class="text-gray-600">{{ __('admin.orders.details.customer_email') }}:</span> <span class="font-medium">{{ $order->user->email ?? 'No email' }}</span></p>
                    <p><span class="text-gray-600">{{ __('admin.orders.details.customer_phone') }}:</span> <span class="font-medium">{{ $order->shippingAddress->phone ?? 'N/A' }}</span></p>
                    <p><span class="text-gray-600">{{ __('admin.orders.details.order_number') }}:</span> <span class="font-medium font-mono">{{ $order->order_number }}</span></p>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-3">{{ __('admin.orders.details.order_info') }}</h4>
                <div class="space-y-2">
                    <p>
                        <span class="text-gray-600">{{ __('admin.orders.details.order_status') }}:</span> 
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                            {{ $order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($order->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                               ($order->order_status === 'processing' ? 'bg-purple-100 text-purple-800' :
                               ($order->order_status === 'shipped' ? 'bg-indigo-100 text-indigo-800' :
                               ($order->order_status === 'delivered' ? 'bg-green-100 text-green-800' :
                               ($order->order_status === 'cancelled' ? 'bg-red-100 text-red-800' :
                               'bg-gray-100 text-gray-800'))))) }}">
                            {{ ucfirst(__('admin.orders.status.' . $order->order_status)) }}
                        </span>
                    </p>
                    <p>
                        <span class="text-gray-600">{{ __('admin.orders.details.payment_status') }}:</span> 
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                            {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' :
                               'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst(__('admin.orders.payment_status.' . $order->payment_status)) }}
                        </span>
                    </p>
                    <p><span class="text-gray-600">{{ __('admin.orders.details.payment_method') }}:</span> <span class="font-medium">{{ $order->payment_method ?? 'N/A' }}</span></p>
                    @if($order->tracking_number)
                    <p><span class="text-gray-600">{{ __('admin.orders.details.tracking_number') }}:</span> <span class="font-medium">{{ $order->tracking_number }}</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.orders.details.order_items') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 text-left text-gray-700 font-medium">{{ __('admin.orders.details.items_table.product') }}</th>
                        <th class="py-3 px-4 text-left text-gray-700 font-medium">{{ __('admin.orders.details.items_table.quantity') }}</th>
                        <th class="py-3 px-4 text-left text-gray-700 font-medium">{{ __('admin.orders.details.items_table.unit_price') }}</th>
                        <th class="py-3 px-4 text-left text-gray-700 font-medium">{{ __('admin.orders.details.items_table.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800">{{ $item->product_name }}</div>
                            @if($item->variant_details)
                            <div class="text-gray-600 text-sm">{{ $item->variant_details }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $item->quantity }}</td>
                        <td class="py-3 px-4">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 px-4 font-semibold">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right text-gray-700">{{ __('admin.orders.details.totals.subtotal') }}:</td>
                        <td class="py-3 px-4 font-semibold">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->tax_amount > 0)
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right text-gray-700">{{ __('admin.orders.details.totals.tax') }}:</td>
                        <td class="py-3 px-4">${{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping_amount > 0)
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right text-gray-700">{{ __('admin.orders.details.totals.shipping') }}:</td>
                        <td class="py-3 px-4">${{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right text-gray-700">{{ __('admin.orders.details.totals.discount') }}:</td>
                        <td class="py-3 px-4 text-red-600">-${{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="bg-gray-50">
                        <td colspan="3" class="py-3 px-4 text-right font-bold text-gray-800">{{ __('admin.orders.details.totals.total') }}:</td>
                        <td class="py-3 px-4 font-bold text-lg text-blue-600">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Shipping & Billing -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($order->shippingAddress)
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.orders.details.address.shipping_address') }}</h3>
            <div class="space-y-2">
                <p class="font-medium">{{ $order->shippingAddress->address_line_1 }}</p>
                @if($order->shippingAddress->address_line_2)
                <p>{{ $order->shippingAddress->address_line_2 }}</p>
                @endif
                <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                <p>{{ $order->shippingAddress->country }}</p>
                @if($order->shippingAddress->phone)
                <p class="text-gray-600">{{ __('admin.orders.details.customer_phone') }}: {{ $order->shippingAddress->phone }}</p>
                @endif
            </div>
        </div>
        @endif
        
        @if($order->billingAddress)
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.orders.details.address.billing_address') }}</h3>
            <div class="space-y-2">
                <p class="font-medium">{{ $order->billingAddress->address_line_1 }}</p>
                @if($order->billingAddress->address_line_2)
                <p>{{ $order->billingAddress->address_line_2 }}</p>
                @endif
                <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</p>
                <p>{{ $order->billingAddress->country }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Order Timeline -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.orders.details.order_timeline') }}</h3>
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-green-600 text-sm"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ __('admin.orders.details.timeline.order_placed') }}</p>
                    <p class="text-gray-600 text-sm">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            
            @if($order->payment_date)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-credit-card text-green-600 text-sm"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ __('admin.orders.details.timeline.payment_processed', ['status' => ucfirst(__('admin.orders.payment_status.' . $order->payment_status))]) }}</p>
                    <p class="text-gray-600 text-sm">{{ Carbon::parse($order->payment_date)->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
            
            @if($order->delivered_at)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-truck text-green-600 text-sm"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ __('admin.orders.details.timeline.order_delivered') }}</p>
                    <p class="text-gray-600 text-sm">{{ Carbon::parse($order->delivered_at)->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Order Notes -->
    @if($order->customer_notes || $order->admin_notes)
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.orders.details.order_notes') }}</h3>
        @if($order->customer_notes)
        <div class="mb-4">
            <p class="text-sm font-medium text-gray-700 mb-1">{{ __('admin.orders.details.notes.customer_notes') }}:</p>
            <p class="text-gray-700 bg-white p-3 rounded-lg">{{ $order->customer_notes }}</p>
        </div>
        @endif
        @if($order->admin_notes)
        <div>
            <p class="text-sm font-medium text-gray-700 mb-1">{{ __('admin.orders.details.notes.admin_notes') }}:</p>
            <p class="text-gray-700 bg-blue-50 p-3 rounded-lg">{{ $order->admin_notes }}</p>
        </div>
        @endif
    </div>
    @endif
</div>