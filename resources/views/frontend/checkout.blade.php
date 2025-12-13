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

                            <!-- Address Selection Tabs -->
                            <div class="mb-6">
                                <div class="flex border-b border-gray-200">
                                    <button type="button"
                                        class="address-tab flex-1 py-3 px-4 text-center font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all"
                                        data-tab="saved">
                                        <i class="fas fa-bookmark mr-2"></i>Use Saved Address
                                    </button>
                                    <button type="button"
                                        class="address-tab flex-1 py-3 px-4 text-center font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all"
                                        data-tab="new">
                                        <i class="fas fa-plus mr-2"></i>Add New Address
                                    </button>
                                </div>
                            </div>

                            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                                @csrf

                                <!-- Saved Addresses Section (Initially Hidden) -->
                                <div id="saved-address-section" class="space-y-4 hidden">
                                    @if ($savedAddresses->count() > 0)
                                        <div class="space-y-4">
                                            @foreach ($savedAddresses as $address)
                                                <label class="saved-address-option block cursor-pointer">
                                                    <input type="radio" name="address_option" value="saved"
                                                        class="hidden address-option-radio"
                                                        data-address-id="{{ $address->id }}">
                                                    <div
                                                        class="border-2 border-gray-200 rounded-xl p-4 hover:border-gray-900 transition-all">
                                                        <div class="flex items-start justify-between">
                                                            <div>
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <span
                                                                        class="font-semibold text-gray-900">{{ $address->address_name ?? 'Home' }}</span>
                                                                    @if ($address->is_default)
                                                                        <span
                                                                            class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Default</span>
                                                                    @endif
                                                                </div>
                                                                <p class="text-gray-900 font-medium">
                                                                    {{ $address->full_name }}</p>
                                                                <p class="text-gray-600 text-sm">{{ $address->phone }}</p>
                                                                <p class="text-gray-600 text-sm mt-1">
                                                                    {{ $address->address_line1 }}</p>
                                                                @if ($address->address_line2)
                                                                    <p class="text-gray-600 text-sm">
                                                                        {{ $address->address_line2 }}</p>
                                                                @endif
                                                                <p class="text-gray-600 text-sm">{{ $address->city }},
                                                                    {{ $address->state }} {{ $address->zip_code }}</p>
                                                                <p class="text-gray-600 text-sm">{{ $address->country }}
                                                                </p>
                                                            </div>
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                                    <div
                                                                        class="w-3 h-3 bg-gray-900 rounded-full scale-0 transition-transform">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="saved_address_id" id="saved_address_id" value="">
                                    @else
                                        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-xl">
                                            <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-3"></i>
                                            <p class="text-gray-600">No saved addresses found.</p>
                                            <p class="text-gray-500 text-sm mt-1">Please add a new address below.</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- New Address Form Section (Initially Visible) -->
                                <div id="new-address-section" class="space-y-6">
                                    <input type="hidden" name="address_option" value="new" id="address_option">
                                    <!-- Add address type hidden field -->
                                    <input type="hidden" name="address_type" value="shipping">

                                    <!-- Save Address Option -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                        <div>
                                            <p class="font-medium text-gray-900">Save this address for future use</p>
                                            <p class="text-sm text-gray-600">We'll store this address in your address book
                                            </p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="save_address" value="1" class="sr-only peer"
                                                id="save_address_toggle">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900">
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Address Name Field (Conditional) -->
                                    <div id="address_name_field" class="hidden">
                                        <label for="address_name" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Address Name
                                        </label>
                                        <input type="text" id="address_name" name="address_name"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                            placeholder="e.g., Home, Office, Mom's House"
                                            value="{{ old('address_name') }}">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Full Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="name" name="name" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                                value="{{ old('name', auth()->user()->name ?? '') }}"
                                                placeholder="Enter your full name">
                                            @error('name')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Email Address <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" id="email" name="email" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                                placeholder="your@email.com">
                                            @error('email')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Phone Number <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" id="phone" name="phone" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('phone') border-red-500 @enderror"
                                                value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                                placeholder="e.g., +855123456789">
                                            <p class="text-xs text-gray-500 mt-1">Cambodia format: +85510xxxxxx or
                                                010xxxxxx (10, 11, 12, 15-20, 23-29)</p>
                                            @error('phone')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="city" class="block text-sm font-semibold text-gray-900 mb-2">
                                                City <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="city" name="city" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('city') border-red-500 @enderror"
                                                value="{{ old('city') }}" placeholder="Your city">
                                            @error('city')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="state" class="block text-sm font-semibold text-gray-900 mb-2">
                                                State/Province <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="state" name="state" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('state') border-red-500 @enderror"
                                                value="{{ old('state') }}" placeholder="Your state or province">
                                            @error('state')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="zip" class="block text-sm font-semibold text-gray-900 mb-2">
                                                ZIP Code <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="zip" name="zip" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('zip') border-red-500 @enderror"
                                                value="{{ old('zip') }}" placeholder="12345">
                                            <p class="text-xs text-gray-500 mt-1">5-6 digit postal code</p>
                                            @error('zip')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Full Address <span class="text-red-500">*</span>
                                            </label>
                                            <textarea id="address" name="address" rows="3" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('address') border-red-500 @enderror"
                                                placeholder="House number, street, building name (min. 10 characters)">{{ old('address') }}</textarea>
                                            @error('address')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="address2" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Address Line 2 (Optional)
                                            </label>
                                            <input type="text" id="address2" name="address2"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                value="{{ old('address2') }}" placeholder="Apartment, suite, unit, etc.">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="country" class="block text-sm font-semibold text-gray-900 mb-2">
                                                Country <span class="text-red-500">*</span>
                                            </label>
                                            <select id="country" name="country" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                                                <option value="United States"
                                                    {{ old('country', 'United States') == 'United States' ? 'selected' : '' }}>
                                                    United States</option>
                                                <option value="Cambodia"
                                                    {{ old('country') == 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
                                                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>
                                                    Canada</option>
                                                <option value="United Kingdom"
                                                    {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United
                                                    Kingdom</option>
                                                <option value="Australia"
                                                    {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Optional: Separate billing address section -->
                                        <div class="md:col-span-2">
                                            <label class="flex items-center gap-3 mb-4">
                                                <input type="checkbox" name="different_billing" value="1"
                                                    class="w-5 h-5 text-gray-900 rounded focus:ring-gray-900"
                                                    id="different_billing_toggle">
                                                <span class="text-gray-900 font-medium">Use a different billing
                                                    address</span>
                                            </label>

                                            <!-- Billing Address Fields (Initially Hidden) -->
                                            <div id="billing_address_fields"
                                                class="hidden space-y-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                                <h3 class="font-semibold text-gray-900 mb-3">Billing Address</h3>

                                                <div>
                                                    <label for="billing_name"
                                                        class="block text-sm font-semibold text-gray-900 mb-2">
                                                        Billing Name
                                                    </label>
                                                    <input type="text" id="billing_name" name="billing_name"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                        value="{{ old('billing_name') }}" placeholder="Name on the card">
                                                </div>

                                                <div>
                                                    <label for="billing_address"
                                                        class="block text-sm font-semibold text-gray-900 mb-2">
                                                        Billing Address
                                                    </label>
                                                    <textarea id="billing_address" name="billing_address" rows="2"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                        placeholder="Billing address">{{ old('billing_address') }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="billing_city"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            City
                                                        </label>
                                                        <input type="text" id="billing_city" name="billing_city"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                            value="{{ old('billing_city') }}" placeholder="City">
                                                    </div>

                                                    <div>
                                                        <label for="billing_state"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            State
                                                        </label>
                                                        <input type="text" id="billing_state" name="billing_state"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                            value="{{ old('billing_state') }}" placeholder="State">
                                                    </div>

                                                    <div>
                                                        <label for="billing_zip"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            ZIP Code
                                                        </label>
                                                        <input type="text" id="billing_zip" name="billing_zip"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                            value="{{ old('billing_zip') }}" placeholder="ZIP">
                                                    </div>

                                                    <div>
                                                        <label for="billing_country"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            Country
                                                        </label>
                                                        <select id="billing_country" name="billing_country"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                                                            <option value="United States">United States</option>
                                                            <option value="Cambodia">Cambodia</option>
                                                            <option value="Canada">Canada</option>
                                                            <option value="United Kingdom">United Kingdom</option>
                                                            <option value="Australia">Australia</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Make Default Checkbox (Conditional) -->
                                        <div id="make_default_field" class="md:col-span-2 hidden">
                                            <label class="flex items-center gap-3">
                                                <input type="checkbox" name="make_default" value="1"
                                                    class="w-5 h-5 text-gray-900 rounded focus:ring-gray-900"
                                                    {{ old('make_default') ? 'checked' : '' }}>
                                                <span class="text-gray-900 font-medium">Set as default shipping
                                                    address</span>
                                            </label>
                                        </div>

                                        <!-- Customer Notes -->
                                        <div class="md:col-span-2">
                                            <label for="customer_notes"
                                                class="block text-sm font-semibold text-gray-900 mb-2">
                                                Order Notes (Optional)
                                            </label>
                                            <textarea id="customer_notes" name="customer_notes" rows="2"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                placeholder="Any special instructions for your order?">{{ old('customer_notes') }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">Delivery instructions, gift messages,
                                                etc.</p>
                                        </div>
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
                                            <p class="font-semibold text-gray-900">Online Payment (Stripe)</p>
                                            <p class="text-sm text-gray-500">Pay securely with your card</p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-globe-americas"></i> USD → KHR conversion shown at
                                                checkout
                                            </p>
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
                                @foreach ($items as $item)
                                    <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                        @if (isset($item->product_image) && $item->product_image)
                                            <img src="{{ Str::startsWith($item->product_image, ['http://', 'https://'])
                                                ? $item->product_image
                                                : asset('storage/' . $item->product_image) }}"
                                                alt="{{ $item->product_name ?? 'Product' }}"
                                                class="w-16 h-16 object-cover rounded-xl">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-tshirt text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm line-clamp-1">
                                                {{ $item->product_name ?? 'Product' }}</h3>
                                            <p class="text-sm text-gray-500">Size: {{ $item->size ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">Color: {{ $item->color ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">
                                                ${{ number_format($item->total_price ?? 0, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span>${{ number_format($shipping, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax (8%)</span>
                                    <span>${{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <span>Total</span>
                                        <span>${{ number_format($grandTotal, 2) }}</span>
                                    </div>
                                    @php
                                        $exchangeRate = 4000;
                                        $grandTotalKHR = $grandTotal * $exchangeRate;
                                    @endphp
                                    <p class="text-xs text-gray-500 text-right mt-1">
                                        ≈ ៛{{ number_format($grandTotalKHR, 0) }} KHR
                                    </p>
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
                                class="w-full bg-gray-900 text-white py-4 px-6 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105 shadow-lg flex items-center justify-center gap-3 checkout-btn">
                                <i class="fa-solid fa-cart-shopping"></i>
                                Pay ${{ number_format($grandTotal, 2) }}
                            </button>

                            <!-- Continue Shopping -->
                            <div class="mt-4 text-center">
                                <a href="{{ route('cart') }}"
                                    class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-arrow-left"></i>
                                    Back to Cart
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

        /* Tab styles */
        .address-tab.active {
            border-bottom-color: #111827;
            color: #111827;
            font-weight: 600;
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

        /* Saved address selection styles */
        .saved-address-option input:checked+div {
            border-color: #111827;
            background-color: #f9fafb;
        }

        .saved-address-option input:checked+div .w-3.h-3 {
            transform: scale(1);
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

        /* Toggle switch */
        input:checked~.dot {
            transform: translateX(100%);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.address-tab');
            const savedSection = document.getElementById('saved-address-section');
            const newSection = document.getElementById('new-address-section');
            const addressOptionInput = document.getElementById('address_option');
            const savedAddressIdInput = document.getElementById('saved_address_id');

            // Different billing address toggle
            const differentBillingToggle = document.getElementById('different_billing_toggle');
            const billingAddressFields = document.getElementById('billing_address_fields');

            if (differentBillingToggle) {
                differentBillingToggle.addEventListener('change', function() {
                    if (this.checked) {
                        billingAddressFields.classList.remove('hidden');
                        // Set required fields if needed
                        document.querySelectorAll('#billing_address_fields [required]').forEach(field => {
                            field.required = true;
                        });
                    } else {
                        billingAddressFields.classList.add('hidden');
                        // Remove required from billing fields
                        document.querySelectorAll('#billing_address_fields [required]').forEach(field => {
                            field.required = false;
                        });
                    }
                });
            }

            // Initially show new address section by default
            tabs[1].classList.add('active');
            newSection.classList.remove('hidden');
            savedSection.classList.add('hidden');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabType = this.getAttribute('data-tab');

                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide sections
                    if (tabType === 'saved') {
                        savedSection.classList.remove('hidden');
                        newSection.classList.add('hidden');
                        addressOptionInput.value = 'saved';
                    } else {
                        savedSection.classList.add('hidden');
                        newSection.classList.remove('hidden');
                        addressOptionInput.value = 'new';
                    }
                });
            });

            // Saved address selection
            const savedAddressOptions = document.querySelectorAll('.saved-address-option');
            savedAddressOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selection from all options
                    savedAddressOptions.forEach(opt => {
                        opt.querySelector('input').checked = false;
                    });

                    // Select this option
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    savedAddressIdInput.value = radio.getAttribute('data-address-id');
                });
            });

            // Save address toggle functionality
            const saveAddressToggle = document.getElementById('save_address_toggle');
            const addressNameField = document.getElementById('address_name_field');
            const makeDefaultField = document.getElementById('make_default_field');

            saveAddressToggle.addEventListener('change', function() {
                if (this.checked) {
                    addressNameField.classList.remove('hidden');
                    makeDefaultField.classList.remove('hidden');
                } else {
                    addressNameField.classList.add('hidden');
                    makeDefaultField.classList.add('hidden');
                }
            });

            // Payment method selection
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

            // Form validation before submission
            const checkoutForm = document.querySelector('form');
            const checkoutBtn = document.querySelector('.checkout-btn');

            checkoutForm.addEventListener('submit', function(e) {
                // Validate address selection
                const addressOption = addressOptionInput.value;

                if (addressOption === 'saved') {
                    const selectedAddress = savedAddressIdInput.value;
                    if (!selectedAddress) {
                        e.preventDefault();
                        alert('Please select a saved address');
                        return false;
                    }
                } else {
                    // Validate new address fields
                    const requiredFields = ['name', 'email', 'phone', 'city', 'state', 'zip', 'address'];
                    let isValid = true;

                    requiredFields.forEach(field => {
                        const input = document.getElementById(field);
                        if (!input.value.trim()) {
                            isValid = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required fields marked with *');
                        return false;
                    }
                }

                // Disable button and show loading state
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            });
        });
    </script>
@endsection
