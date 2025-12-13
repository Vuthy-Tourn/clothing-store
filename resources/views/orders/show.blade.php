<title>Order #{{ $order->order_number }} - Outfit 818</title>
@extends('layouts.front')
@section('content')
    <div class="min-h-screen py-12 mt-10">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-receipt text-green-600 text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Order Details</h1>
                <p class="text-xl text-gray-600">Order #{{ $order->order_number }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Status Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Order Summary</h2>
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium capitalize 
                            @if ($order->payment_status == 'paid') bg-green-100 text-green-800 border border-green-200
                            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                            @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800 border border-red-200
                            @else bg-blue-100 text-blue-800 border border-blue-200 @endif">
                                <i
                                    class="fas 
                                @if ($order->payment_status == 'paid') fa-check-circle 
                                @elseif($order->payment_status == 'pending') fa-clock
                                @elseif($order->order_status == 'cancelled') fa-times-circle
                                @else fa-info-circle @endif mr-2">
                                </i>
                                @if($order->payment_status == 'paid')
                                    Paid
                                @elseif($order->order_status == 'cancelled')
                                    Cancelled
                                @else
                                    {{ ucfirst($order->order_status) }}
                                @endif
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Order Number</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Order Date</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Payment Method</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ ucfirst($order->payment_method) }}
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Total Amount</p>
                                    <p class="text-3xl font-bold text-green-600">
                                        ${{ number_format($order->total_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Items</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $order->items->count() }} items</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Payment Status</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ ucfirst($order->payment_status) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-blue-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Order Items</h2>
                        </div>

                        <div class="space-y-6">
                            @foreach ($order->items as $item)
                                @php
                                    $variantDetails = json_decode($item->variant_details, true) ?? [];
                                    $size = $variantDetails['size'] ?? 'N/A';
                                    $color = $variantDetails['color'] ?? 'N/A';
                                    $sku = $variantDetails['sku'] ?? 'N/A';
                                    $productImage = null;
                                    
                                    // Get product image from variant relationship if available
                                    if ($item->variant && $item->variant->product && $item->variant->product->images) {
                                        $productImage = $item->variant->product->images->first();
                                    }
                                @endphp
                                <div
                                    class="flex items-center gap-6 p-6 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-300">
                                    @if($productImage && $productImage->image_path)
                                        <img src="{{ Str::startsWith($productImage->image_path, ['http://', 'https://'])
                                            ? $productImage->image_path
                                            : asset('storage/' . $productImage->image_path) }}"
                                            alt="{{ $item->product_name }}" 
                                            class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tshirt text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item->product_name }}</h3>
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span class="bg-white px-3 py-1 rounded-full border">Size: {{ $size }}</span>
                                            <span class="bg-white px-3 py-1 rounded-full border">Color: {{ $color }}</span>
                                            <span class="bg-white px-3 py-1 rounded-full border">Qty: {{ $item->quantity }}</span>
                                            <span class="bg-white px-3 py-1 rounded-full border">SKU: {{ $sku }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-bold text-gray-900 mb-1">
                                            ${{ number_format($item->total_price, 2) }}</p>
                                        <p class="text-sm text-gray-500">${{ number_format($item->unit_price, 2) }} each</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping & Billing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Shipping Address -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-truck text-green-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900">Shipping Address</h3>
                            </div>
                            @if($order->shippingAddress)
                                <div class="space-y-2">
                                    <p class="font-medium text-gray-900">{{ $order->shippingAddress->full_name }}</p>
                                    <p class="text-gray-600">{{ $order->shippingAddress->address_line1 }}</p>
                                    @if($order->shippingAddress->address_line2)
                                        <p class="text-gray-600">{{ $order->shippingAddress->address_line2 }}</p>
                                    @endif
                                    <p class="text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip_code }}</p>
                                    <p class="text-gray-600">{{ $order->shippingAddress->country }}</p>
                                    <p class="text-gray-600">{{ $order->shippingAddress->phone }}</p>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No shipping address saved</p>
                            @endif
                        </div>

                        <!-- Billing Address -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-credit-card text-blue-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900">Billing Address</h3>
                            </div>
                            @if($order->billingAddress && $order->billingAddress->id !== $order->shippingAddress->id)
                                <div class="space-y-2">
                                    <p class="font-medium text-gray-900">{{ $order->billingAddress->full_name }}</p>
                                    <p class="text-gray-600">{{ $order->billingAddress->address_line1 }}</p>
                                    @if($order->billingAddress->address_line2)
                                        <p class="text-gray-600">{{ $order->billingAddress->address_line2 }}</p>
                                    @endif
                                    <p class="text-gray-600">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zip_code }}</p>
                                    <p class="text-gray-600">{{ $order->billingAddress->country }}</p>
                                    <p class="text-gray-600">{{ $order->billingAddress->phone }}</p>
                                </div>
                            @else
                                <p class="text-gray-600">Same as shipping address</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="space-y-6">
                    <!-- Order Totals -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Order Totals</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span>${{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span>${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Invoice Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-file-invoice text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Download Invoice</h3>
                            <p class="text-sm text-gray-500">Get your official order receipt</p>
                        </div>
                        <a href="{{ route('orders.invoice', $order->order_number) }}"
                            class="w-full bg-gray-900 text-white py-3 px-4 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-3">
                            <i class="fas fa-download"></i>
                            Download Invoice
                        </a>
                    </div>

                    <!-- Order Timeline -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Order Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Order Placed</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            @if($order->order_status !== 'pending')
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-cog text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Processing</p>
                                    <p class="text-sm text-gray-500">Preparing your order</p>
                                </div>
                            </div>
                            @endif
                            @if(in_array($order->order_status, ['shipped', 'delivered']))
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-shipping-fast text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Shipped</p>
                                    @if($order->tracking_number)
                                        <p class="text-sm text-gray-500">Tracking: {{ $order->tracking_number }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Shipped on {{ $order->updated_at->format('d M Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($order->order_status === 'delivered')
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-home text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Delivered</p>
                                    <p class="text-sm text-gray-500">{{ $order->delivered_at?->format('d M Y, h:i A') ?? 'Delivered' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Support Card -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
                        <div class="text-center">
                            <i class="fas fa-headset text-2xl mb-3"></i>
                            <h3 class="font-semibold mb-2">Need Help?</h3>
                            <p class="text-sm text-green-100 mb-4">Our support team is here to assist you</p>
                            <a href="mailto:support@outfit818.com"
                                class="w-full inline-block bg-white text-green-600 py-2 px-4 rounded-xl font-semibold hover:bg-green-50 transition-all text-center">
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="text-center mt-12">
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center gap-3 bg-white text-gray-900 border border-gray-300 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    Back to Order History
                </a>
            </div>
        </div>
    </div>

    <!-- Order Confirmation Popup - Only shows once -->
    @if(session('show_order_success'))
    <div id="orderConfirmationPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 mx-4 max-w-md w-full text-center transform transition-all duration-300 scale-95 opacity-0">
            <!-- Bigger Success Icon -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-green-600 text-4xl"></i>
            </div>
            
            <!-- Bigger Thank You Text -->
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Thank You!</h2>
            <p class="text-xl text-gray-600 mb-2">Your order has been placed successfully</p>
            <p class="text-lg text-gray-500 mb-6">We're preparing your items</p>
            
            <!-- Order ID -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-500 mb-1">Order Reference</p>
                                <p class="font-semibold text-gray-900 text-lg">{{ $order->order_number }}</p>
            </div>
            
            <!-- Action Button -->
            <button onclick="closePopup()" 
                    class="w-full bg-gray-900 text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition-colors text-lg">
                Continue Shopping
            </button>
        </div>
    </div>
    @endif

    <style>
        .hover\:scale-105 {
            transition: transform 0.2s ease-in-out;
        }

        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .scale-95 {
            transform: scale(0.95);
        }
        
        .scale-100 {
            transform: scale(1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to order items
            const orderItems = document.querySelectorAll('.bg-gray-50.rounded-xl');
            orderItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.5s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 100);
            });

            // Show popup if it exists
            const popup = document.getElementById('orderConfirmationPopup');
            if (popup) {
                const popupContent = popup.querySelector('.bg-white');
                
                // Show popup with animation
                setTimeout(() => {
                    popupContent.classList.remove('scale-95', 'opacity-0');
                    popupContent.classList.add('scale-100', 'opacity-100');
                }, 500);

                // Add confetti effect
                setTimeout(() => {
                    if (typeof confetti === 'function') {
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                    }
                }, 800);

                // Auto-close after 8 seconds
                setTimeout(() => {
                    if (popup) closePopup();
                }, 8000);
            }
        });

        function closePopup() {
            const popup = document.getElementById('orderConfirmationPopup');
            if (!popup) return;
            
            const popupContent = popup.querySelector('.bg-white');
            popupContent.classList.remove('scale-100', 'opacity-100');
            popupContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                popup.remove();
                // Clear the session flag via AJAX so it doesn't show again
                fetch('{{ route("clear.order.success") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
            }, 300);
        }

        // Close on click outside or Escape
        document.addEventListener('click', function(e) {
            const popup = document.getElementById('orderConfirmationPopup');
            if (popup && e.target === popup) {
                closePopup();
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopup();
            }
        });
    </script>
@endsection