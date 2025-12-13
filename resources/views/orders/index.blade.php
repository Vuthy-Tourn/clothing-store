@extends('layouts.front')
@section('content')
    <div class="min-h-screen py-12 mt-10">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-history text-green-600 text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Order History</h1>
                <p class="text-xl text-gray-600">Track and manage your purchases</p>
            </div>

            @if ($orders->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-gray-400 text-5xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">No Orders Yet</h2>
                        <p class="text-gray-600 mb-8">Start shopping to see your order history here</p>
                        <a href="{{ route('products.all') }}"
                            class="inline-flex items-center gap-3 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105">
                            <i class="fas fa-bag-shopping"></i>
                            Start Shopping
                        </a>
                    </div>
                </div>
            @else
                <!-- Orders Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach ($orders as $order)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <!-- Order Header -->
                            <div class="p-6 border-b border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-bold text-gray-900">Order #{{ $order->order_number }}</h3>
                                    <span class="text-sm text-gray-500">
                                        {{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-2xl font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize 
                                @if ($order->status === 'paid') bg-green-100 text-green-800
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                        <i
                                            class="fas 
                                    @if ($order->status === 'paid') fa-check-circle 
                                    @elseif($order->status === 'pending') fa-clock
                                    @elseif($order->status === 'cancelled') fa-times-circle
                                    @else fa-info-circle @endif mr-1 text-xs">
                                        </i>
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="p-6 border-b border-gray-100">
                                <div class="space-y-3">
                                    @foreach ($order->items->take(2) as $item)
                                        @php
                                            $variantDetails = json_decode($item->variant_details, true) ?? [];
                                            $size = $variantDetails['size'] ?? 'N/A';
                                            $color = $variantDetails['color'] ?? 'N/A';
                                            $sku = $variantDetails['sku'] ?? 'N/A';
                                            $productImage = null;

                                            // Get product image from variant relationship if available
                                            if (
                                                $item->variant &&
                                                $item->variant->product &&
                                                $item->variant->product->images
                                            ) {
                                                $productImage = $item->variant->product->images->first();
                                            }
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            @if ($productImage && $productImage->image_path)
                                                <img src="{{ Str::startsWith($productImage->image_path, ['http://', 'https://'])
                                                    ? $productImage->image_path
                                                    : asset('storage/' . $productImage->image_path) }}"
                                                    alt="{{ $item->product_name }}"
                                                    class="w-12 h-12 object-cover rounded-lg">
                                            @else
                                                <div
                                                    class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-tshirt text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-900 text-sm line-clamp-1">
                                                    {{ $item->product_name }}</p>
                                                <p class="text-xs text-gray-500">Size: {{ $item->size }} • Qty:
                                                    {{ $item->quantity }}</p>
                                            </div>
                                            <!-- FIXED LINE: Changed $order->total_amount to $item->price -->
                                            <span
                                                class="text-sm font-semibold text-gray-900">${{ number_format($item->unit_price, 2) }}</span>
                                        </div>
                                    @endforeach

                                    @if ($order->items->count() > 2)
                                        <div class="text-center">
                                            <span class="text-sm text-gray-500">+{{ $order->items->count() - 2 }} more
                                                items</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <a href="{{ route('orders.show', $order->order_number) }}"
                                        class="flex-1 bg-gray-900 text-white py-2 px-4 rounded-lg font-semibold text-sm hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                    <a href="{{ route('orders.invoice', $order->order_number) }}"
                                        class="flex-1 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-semibold text-sm hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-download"></i>
                                        Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Additional Info Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div>
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shipping-fast text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Fast Delivery</h3>
                            <p class="text-sm text-gray-600">Orders typically delivered within 3-5 business days</p>
                        </div>
                        <div>
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Secure Payments</h3>
                            <p class="text-sm text-gray-600">All transactions are encrypted and secure</p>
                        </div>
                        <div>
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-headset text-purple-600 text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">24/7 Support</h3>
                            <p class="text-sm text-gray-600">Need help? Contact our support team anytime</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        .hover\:scale-105 {
            transition: transform 0.2s ease-in-out;
        }

        .hover\:scale-105:hover {
            transform: scale(1.02);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to order cards
            const orderCards = document.querySelectorAll('.bg-white.rounded-2xl');
            orderCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endsection
