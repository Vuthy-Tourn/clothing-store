@extends('layouts.front')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 py-12 mt-10">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Success Header -->
            <div class="text-center mb-12">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Order Confirmed!</h1>
                <p class="text-xl text-gray-600 mb-2">Thank you for your purchase</p>
                <p class="text-gray-500">Your order is being processed and you'll receive a confirmation email shortly.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Order Overview -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Details Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-receipt text-green-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Order Details</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Order ID</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $order->order_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Order Date</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Total Amount</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        ${{ number_format($order->total_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Confirmed
                                    </span>
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
                                @php $product = $item->product; @endphp
                                <div
                                    class="flex items-center gap-6 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <img src="{{ Str::startsWith($product->image, ['http://', 'https://'])
                                        ? $product->image
                                        : asset('storage/' . $product->image) }}"
                                        alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ $item->product_name }}</h3>
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span class="bg-white px-3 py-1 rounded-full">Size: {{ $item->size }}</span>
                                            <span class="bg-white px-3 py-1 rounded-full">Qty: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">
                                            ${{ number_format($item->price * $item->quantity, 2) }}</p>
                                        <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Sidebar -->
                <div class="space-y-6">
                    <!-- Download Invoice Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-file-invoice text-purple-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Download Invoice</h3>
                            <p class="text-sm text-gray-500 mb-4">Get your order receipt for records</p>
                        </div>
                        <a href="{{ route('order.invoice.download', ['orderId' => $order->order_id]) }}"
                            class="w-full bg-gray-900 text-white py-3 px-4 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i>
                            Download Invoice
                        </a>
                    </div>

                    <!-- Next Steps Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">What's Next?</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-envelope text-green-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Confirmation Email</p>
                                    <p class="text-sm text-gray-500">Check your inbox for order details</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-shipping-fast text-blue-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Shipping Updates</p>
                                    <p class="text-sm text-gray-500">We'll notify you when your order ships</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-headset text-purple-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Need Help?</p>
                                    <p class="text-sm text-gray-500">Contact our support team anytime</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Card -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
                        <div class="text-center">
                            <i class="fas fa-gift text-2xl mb-3"></i>
                            <h3 class="font-semibold mb-2">Loved Your Experience?</h3>
                            <p class="text-sm text-green-100 mb-4">Share your feedback and help us improve</p>
                            <button
                                class="w-full bg-white text-green-600 py-2 px-4 rounded-xl font-semibold hover:bg-green-50 transition-all">
                                Leave Feedback
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Shopping -->
            <div class="text-center">
                <a href="{{ route('products.all') }}"
                    class="inline-flex items-center gap-2 bg-white text-gray-900 border border-gray-300 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
                <p class="text-gray-500 mt-4">Discover more amazing products in our collection</p>
            </div>
        </div>
    </div>

    <style>
        .hover\:scale-105 {
            transition: transform 0.2s ease-in-out;
        }

        .hover\:scale-105:hover {
            transform: scale(1.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add confetti effect on page load
            setTimeout(() => {
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: {
                            y: 0.6
                        }
                    });
                }
            }, 500);

            // Smooth scroll to top on page load
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
@endsection
