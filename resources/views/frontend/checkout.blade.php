@extends('layouts.front')
@section('content')
    <div class="min-h-screen bg-gray-50 py-12 mt-10">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Complete Your Order</h1>
                <p class="text-gray-600 text-lg">Secure checkout with fast delivery</p>
            </div>

            @if ($items->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Summary & Checkout Form -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Shipping Information -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                    1</div>
                                <h2 class="text-2xl font-bold text-gray-900">Shipping Information</h2>
                            </div>

                            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Full
                                            Name</label>
                                        <input type="text" id="name" name="name" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                            value="{{ old('name', auth()->user()->name ?? '') }}"
                                            placeholder="Enter your full name">
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email
                                            Address</label>
                                        <input type="email" id="email" name="email" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                                            value="{{ old('email', auth()->user()->email ?? '') }}"
                                            placeholder="your@email.com">
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Phone
                                            Number</label>
                                        <input type="tel" id="phone" name="phone" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('phone') border-red-500 @enderror"
                                            value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="city"
                                            class="block text-sm font-semibold text-gray-900 mb-2">City</label>
                                        <input type="text" id="city" name="city" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('city') border-red-500 @enderror"
                                            value="{{ old('city') }}" placeholder="Your city">
                                        @error('city')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="state"
                                            class="block text-sm font-semibold text-gray-900 mb-2">State</label>
                                        <input type="text" id="state" name="state" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('state') border-red-500 @enderror"
                                            value="{{ old('state') }}" placeholder="Your state">
                                        @error('state')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="zip" class="block text-sm font-semibold text-gray-900 mb-2">ZIP
                                            Code</label>
                                        <input type="text" id="zip" name="zip" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('zip') border-red-500 @enderror"
                                            value="{{ old('zip') }}" placeholder="12345">
                                        @error('zip')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">Full
                                            Address</label>
                                        <textarea id="address" name="address" rows="3" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('address') border-red-500 @enderror"
                                            placeholder="Enter your complete address">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                    2</div>
                                <h2 class="text-2xl font-bold text-gray-900">Payment Method</h2>
                            </div>

                            <div class="space-y-4">
                                <label
                                    class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 transition-all payment-method">
                                    <input type="radio" name="payment_method" value="online" checked class="hidden">
                                    <div
                                        class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center payment-radio">
                                        <div
                                            class="w-3 h-3 bg-gray-900 rounded-full scale-0 transition-transform payment-dot">
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-credit-card text-gray-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Online Payment</p>
                                            <p class="text-sm text-gray-500">Pay securely with your card</p>
                                        </div>
                                    </div>
                                </label>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                            <!-- Order Items -->
                            <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                                @php $grandTotal = 0; @endphp
                                @foreach ($items as $item)
                                    @php
                                        $product = $item->product;
                                        $price = $item->unit_price ?? 0;
                                        $total = $price * $item->quantity;
                                        $grandTotal += $total;

                                    @endphp
                                    <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://'])
                                            ? $product->image
                                            : asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-xl">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm line-clamp-1">
                                                {{ $product->name }}</h3>
                                            <p class="text-sm text-gray-500">Size: {{ $item->size }}</p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">${{ number_format($total, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($grandTotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="text-green-600">Free</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax</span>
                                    <span>${{ number_format($grandTotal * 0.18, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <span>Total</span>
                                        <span>${{ number_format($grandTotal * 1.18, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Badges -->
                            <div class="flex items-center justify-center gap-4 mb-6 py-4 border-t border-gray-200">
                                <div class="text-center">
                                    <div
                                        class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-lock text-green-600 text-sm"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">Secure</p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-shield-alt text-blue-600 text-sm"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">Protected</p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-bolt text-purple-600 text-sm"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">Fast</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full bg-gray-900 text-white py-4 px-6 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105 shadow-lg flex items-center justify-center gap-3">
                               <i class="fa-solid fa-cart-shopping"></i>
                                Pay ${{ number_format($grandTotal * 1.18, 2) }}
                            </button>

                            <!-- Continue Shopping -->
                            <div class="mt-4 text-center">
                                <a href="{{ route('products.all') }}"
                                    class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-arrow-left"></i>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-gray-400 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                        <p class="text-gray-600 mb-8">Add some items to your cart to proceed with checkout.</p>
                        <a href="{{ route('products.all') }}"
                            class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105">
                            <i class="fas fa-bag-shopping"></i>
                            Browse Products
                        </a>
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

        .sticky {
            position: sticky;
        }

        /* Payment method selection styles */
        .payment-method input:checked+.payment-radio {
            border-color: #111827;
        }

        .payment-method input:checked+.payment-radio .payment-dot {
            transform: scale(1);
        }

        .payment-radio,
        .payment-dot {
            transition: all 0.2s ease;
        }

        /* Scrollbar styling */
        .max-h-96::-webkit-scrollbar {
            width: 4px;
        }

        .max-h-96::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .max-h-96::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .max-h-96::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize payment method selection
            const paymentMethods = document.querySelectorAll('.payment-method');

            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    // Remove checked state from all methods
                    paymentMethods.forEach(m => {
                        const radio = m.querySelector('input[type="radio"]');
                        radio.checked = false;
                    });

                    // Set checked state for clicked method
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;

                    // Update visual selection
                    paymentMethods.forEach(m => {
                        m.classList.remove('border-gray-900', 'bg-gray-50');
                    });
                    this.classList.add('border-gray-900', 'bg-gray-50');
                });
            });

            // Set initial state for checked payment method
            const checkedMethod = document.querySelector('.payment-method input:checked');
            if (checkedMethod) {
                checkedMethod.closest('.payment-method').classList.add('border-gray-900', 'bg-gray-50');
            }
        });
    </script>
@endsection
