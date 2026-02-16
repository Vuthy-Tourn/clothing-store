@extends('layouts.front')
@section('content')
    <div class="min-h-screen bg-gray-50 py-12 mt-10">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ __('messages.complete_your_order') }}</h1>
                <p class="text-gray-600 text-lg">{{ __('messages.secure_checkout_fast_delivery') }}</p>
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
                                <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.shipping_information') }}</h2>
                            </div>

                            <!-- Address Selection Tabs -->
                            <div class="mb-6">
                                <div class="flex border-b border-gray-200">
                                    <button type="button"
                                        class="address-tab flex-1 py-3 px-4 text-center font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all @if ($savedAddresses->count() > 0) active @endif"
                                        data-tab="saved">
                                        <i class="fas fa-bookmark mr-2"></i>{{ __('messages.use_saved_address') }}
                                        @if ($savedAddresses->count() > 0)
                                            <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                                {{ $savedAddresses->count() }}
                                            </span>
                                        @endif
                                    </button>
                                    <button type="button"
                                        class="address-tab flex-1 py-3 px-4 text-center font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-all @if ($savedAddresses->count() == 0) active @endif"
                                        data-tab="new">
                                        <i class="fas fa-plus mr-2"></i>{{ __('messages.add_new_address') }}
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
                                                    <!-- Change name to saved_address_radio to avoid conflict -->
                                                    <input type="radio" name="saved_address_radio"
                                                        value="{{ $address->id }}" class="hidden address-option-radio"
                                                        data-address-id="{{ $address->id }}">
                                                    <div
                                                        class="border-2 border-gray-200 rounded-xl p-4 hover:border-gray-900 transition-all">
                                                        <div class="flex items-start justify-between">
                                                            <div>
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <span
                                                                        class="font-semibold text-gray-900">{{ $address->address_name ?? __('messages.home') }}</span>
                                                                    @if ($address->is_default)
                                                                        <span
                                                                            class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">{{ __('messages.default') }}</span>
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
                                            <p class="text-gray-600">{{ __('messages.no_saved_addresses') }}</p>
                                            <p class="text-gray-500 text-sm mt-1">
                                                {{ __('messages.add_new_address_below') }}</p>
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
                                            <p class="font-medium text-gray-900">
                                                {{ __('messages.save_address_for_future') }}</p>
                                            <p class="text-sm text-gray-600">{{ __('messages.save_address_description') }}
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
                                            {{ __('messages.address_name') }}
                                        </label>
                                        <input type="text" id="address_name" name="address_name"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                            placeholder="{{ __('messages.address_name_placeholder') }}"
                                            value="{{ old('address_name') }}">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.full_name') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="name" name="name" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                                value="{{ old('name', auth()->user()->name ?? '') }}"
                                                placeholder="{{ __('messages.enter_full_name') }}">
                                            @error('name')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.email_address') }} <span class="text-red-500">*</span>
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
                                                {{ __('messages.phone_number') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" id="phone" name="phone" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('phone') border-red-500 @enderror"
                                                value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                                placeholder="{{ __('messages.phone_placeholder') }}">
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ __('messages.cambodia_phone_format') }}</p>
                                            @error('phone')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="city" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.city') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="city" name="city" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('city') border-red-500 @enderror"
                                                value="{{ old('city') }}"
                                                placeholder="{{ __('messages.your_city') }}">
                                            @error('city')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="state" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.state_province') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="state" name="state" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('state') border-red-500 @enderror"
                                                value="{{ old('state') }}"
                                                placeholder="{{ __('messages.your_state_province') }}">
                                            @error('state')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="zip" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.zip_code') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="zip" name="zip" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('zip') border-red-500 @enderror"
                                                value="{{ old('zip') }}" placeholder="12345">
                                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.zip_code_format') }}</p>
                                            @error('zip')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.full_address') }} <span class="text-red-500">*</span>
                                            </label>
                                            <textarea id="address" name="address" rows="3" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('address') border-red-500 @enderror"
                                                placeholder="{{ __('messages.address_placeholder') }}">{{ old('address') }}</textarea>
                                            @error('address')
                                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="address2" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.address_line_2') }}
                                            </label>
                                            <input type="text" id="address2" name="address2"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                value="{{ old('address2') }}"
                                                placeholder="{{ __('messages.address_line_2_placeholder') }}">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label for="country" class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.country') }} <span class="text-red-500">*</span>
                                            </label>
                                            <select id="country" name="country" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                                                <option value="United States"
                                                    {{ old('country', 'United States') == 'United States' ? 'selected' : '' }}>
                                                    {{ __('messages.united_states') }}</option>
                                                <option value="Cambodia"
                                                    {{ old('country') == 'Cambodia' ? 'selected' : '' }}>
                                                    {{ __('messages.cambodia') }}</option>
                                                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>
                                                    {{ __('messages.canada') }}</option>
                                                <option value="United Kingdom"
                                                    {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>
                                                    {{ __('messages.united_kingdom') }}
                                                </option>
                                                <option value="Australia"
                                                    {{ old('country') == 'Australia' ? 'selected' : '' }}>
                                                    {{ __('messages.australia') }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Optional: Separate billing address section -->
                                        <div class="md:col-span-2">
                                            <label class="flex items-center gap-3 mb-4">
                                                <input type="checkbox" name="different_billing" value="1"
                                                    class="w-5 h-5 text-gray-900 rounded focus:ring-gray-900"
                                                    id="different_billing_toggle">
                                                <span
                                                    class="text-gray-900 font-medium">{{ __('messages.different_billing_address') }}</span>
                                            </label>

                                            <!-- Billing Address Fields (Initially Hidden) -->
                                            <div id="billing_address_fields"
                                                class="hidden space-y-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                                <h3 class="font-semibold text-gray-900 mb-3">
                                                    {{ __('messages.billing_address') }}</h3>

                                                <div>
                                                    <label for="billing_name"
                                                        class="block text-sm font-semibold text-gray-900 mb-2">
                                                        {{ __('messages.billing_name') }}
                                                    </label>
                                                    <input type="text" id="billing_name" name="billing_name"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                        value="{{ old('billing_name') }}"
                                                        placeholder="{{ __('messages.name_on_card') }}">
                                                </div>

                                                <div>
                                                    <label for="billing_address"
                                                        class="block text-sm font-semibold text-gray-900 mb-2">
                                                        {{ __('messages.billing_address_field') }}
                                                    </label>
                                                    <textarea id="billing_address" name="billing_address" rows="2"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                        placeholder="{{ __('messages.billing_address_placeholder') }}">{{ old('billing_address') }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="billing_city"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            {{ __('messages.city') }}
                                                        </label>
                                                        <input type="text" id="billing_city" name="billing_city"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                            value="{{ old('billing_city') }}"
                                                            placeholder="{{ __('messages.city') }}">
                                                    </div>

                                                    <div>
                                                        <label for="billing_state"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            {{ __('messages.state') }}
                                                        </label>
                                                        <input type="text" id="billing_state" name="billing_state"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                            value="{{ old('billing_state') }}"
                                                            placeholder="{{ __('messages.state') }}">
                                                    </div>

                                                    <div>
                                                        <label for="billing_zip"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            {{ __('messages.zip_code') }}
                                                        </label>
                                                        <input type="text" id="billing_zip" name="billing_zip"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                            value="{{ old('billing_zip') }}" placeholder="ZIP">
                                                    </div>

                                                    <div>
                                                        <label for="billing_country"
                                                            class="block text-sm font-semibold text-gray-900 mb-2">
                                                            {{ __('messages.country') }}
                                                        </label>
                                                        <select id="billing_country" name="billing_country"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                                                            <option value="United States">
                                                                {{ __('messages.united_states') }}</option>
                                                            <option value="Cambodia">{{ __('messages.cambodia') }}
                                                            </option>
                                                            <option value="Canada">{{ __('messages.canada') }}</option>
                                                            <option value="United Kingdom">
                                                                {{ __('messages.united_kingdom') }}</option>
                                                            <option value="Australia">{{ __('messages.australia') }}
                                                            </option>
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
                                                <span
                                                    class="text-gray-900 font-medium">{{ __('messages.set_as_default_shipping') }}</span>
                                            </label>
                                        </div>

                                        <!-- Customer Notes -->
                                        <div class="md:col-span-2">
                                            <label for="customer_notes"
                                                class="block text-sm font-semibold text-gray-900 mb-2">
                                                {{ __('messages.order_notes') }}
                                            </label>
                                            <textarea id="customer_notes" name="customer_notes" rows="2"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                                                placeholder="{{ __('messages.order_notes_placeholder') }}">{{ old('customer_notes') }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ __('messages.order_notes_description') }}</p>
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
                                <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.payment_method') }}</h2>
                            </div>

                            <div class="space-y-4">
                                <!-- Stripe Card Payment -->
                                <label
                                    class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 transition-all payment-method">
                                    <input type="radio" name="payment_method" value="stripe" checked class="hidden">
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
                                            <p class="font-semibold text-gray-900">
                                                {{ __('messages.credit_card') }} (Stripe)</p>
                                            <p class="text-sm text-gray-500">{{ __('messages.pay_securely_with_card') }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-globe-americas"></i>
                                                {{ __('messages.usd_khr_conversion') }}
                                            </p>
                                        </div>
                                    </div>
                                </label>

                                <!-- QR Code Payment Option -->
                                <label
                                    class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-gray-900 transition-all payment-method">
                                    <input type="radio" name="payment_method" value="qr_code" class="hidden">
                                    <div
                                        class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center payment-radio">
                                        <div
                                            class="w-3 h-3 bg-gray-900 rounded-full scale-0 transition-transform payment-dot">
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-qrcode text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ __('messages.qr_code_payment') }}</p>
                                            <p class="text-sm text-gray-500">{{ __('messages.scan_pay_local') }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <img src="https://informal.digitaleconomy.gov.kh/images/ministry-icon/aba_round.png"
                                                    alt="ABA Bank" class="w-10 h-10 object-contain p-2">
                                                <img src="https://www.acledasecurities.com.kh/as/assets/listed_company/ABC/logo.png"
                                                    alt="ACLEDA Bank" class="w-10 h-10 object-contain p-2">
                                                <img src="https://api.customs.gov.kh/wp-content/uploads/2023/12/wing.png"
                                                    alt="Wing" class="w-10 h-10 object-contain p-2">
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Hidden QR Code Bank Selection -->
                                <div id="qr_bank_selection"
                                    class="hidden mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-3">{{ __('messages.select_bank') }}</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        <!-- ABA Bank -->
                                        <label class="bank-option cursor-pointer">
                                            <input type="radio" name="qr_bank" value="aba" class="hidden">
                                            <div
                                                class="bg-white p-3 rounded-lg border-2 border-transparent hover:border-blue-500 transition-all text-center">
                                                <div
                                                    class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 overflow-hidden bg-blue-50">
                                                    <!-- Replace src with your ABA logo image -->
                                                    <img src="https://informal.digitaleconomy.gov.kh/images/ministry-icon/aba_round.png"
                                                        alt="ABA Bank" class="w-full h-full object-contain p-2"
                                                        onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHJ4PSIyNCIgZmlsbD0iI0RFRTFFNiIvPjx0ZXh0IHg9IjI0IiB5PSIyNCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjMzc0MTUxIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0iY2VudHJhbCI+QUJBPC90ZXh0Pjwvc3ZnPg=='">
                                                </div>
                                                <p class="text-sm font-medium">ABA Bank</p>
                                            </div>
                                        </label>

                                        <!-- ACLEDA Bank -->
                                        <label class="bank-option cursor-pointer">
                                            <input type="radio" name="qr_bank" value="acleda" class="hidden">
                                            <div
                                                class="bg-white p-3 rounded-lg border-2 border-transparent hover:border-blue-800 transition-all text-center">
                                                <div
                                                    class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 overflow-hidden bg-green-50">
                                                    <!-- Replace src with your ACLEDA logo image -->
                                                    <img src="https://www.acledasecurities.com.kh/as/assets/listed_company/ABC/logo.png"
                                                        alt="ACLEDA Bank" class="w-full h-full object-contain p-2"
                                                        onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHJ4PSIyNCIgZmlsbD0iI0RDRkZFMSIvPjx0ZXh0IHg9IjI0IiB5PSIyNCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjMTY2NTM0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0iY2VudHJhbCI+QUM8L3RleHQ+PC9zdmc+'">
                                                </div>
                                                <p class="text-sm font-medium">ACLEDA Bank</p>
                                            </div>
                                        </label>

                                        <!-- Wing -->
                                        <label class="bank-option cursor-pointer">
                                            <input type="radio" name="qr_bank" value="wing" class="hidden">
                                            <div
                                                class="bg-white p-3 rounded-lg border-2 border-transparent hover:border-green-500 transition-all text-center">
                                                <div
                                                    class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 overflow-hidden bg-purple-50">
                                                    <!-- Replace src with your Wing logo image -->
                                                    <img src="https://api.customs.gov.kh/wp-content/uploads/2023/12/wing.png"
                                                        alt="Wing" class="w-full h-full object-contain p-2"
                                                        onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHJ4PSIyNCIgZmlsbD0iI0Y1RjVGOCIvPjx0ZXh0IHg9IjI0IiB5PSIyNCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjN0MzRkZGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0iY2VudHJhbCI+VzwvdGV4dD48L3N2Zz4='">
                                                </div>
                                                <p class="text-sm font-medium">Wing</p>
                                            </div>
                                        </label>

                                    </div>
                                    <p class="text-xs text-gray-500 mt-3">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('messages.qr_instructions') }}
                                    </p>
                                </div>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('messages.order_summary') }}</h2>

                            @php
                                // RE-CALCULATE TOTALS BASED ON ACTUAL PRICES
                                $calculatedSubtotal = 0;
                                $calculatedTotalSavings = 0;
                                $calculatedOriginalSubtotal = 0;

                                foreach ($items as $item) {
                                    $variant = $item->variant ?? null;

                                    if ($variant) {
                                        // Get final price using same logic as cart
                                        $finalPrice = 0;
                                        $hasDiscount = false;

                                        if (method_exists($variant, 'getFinalPriceAttribute')) {
                                            $finalPrice = $variant->final_price;
                                        } elseif (isset($variant->sale_price) && $variant->sale_price > 0) {
                                            $finalPrice = $variant->sale_price;
                                            $hasDiscount = true;
                                        } elseif (isset($variant->discount_price) && $variant->discount_price > 0) {
                                            $finalPrice = $variant->discount_price;
                                            $hasDiscount = true;
                                        } elseif (isset($variant->discount_value) && $variant->discount_value > 0) {
                                            $finalPrice = $variant->price * (1 - $variant->discount_value / 100);
                                            $hasDiscount = true;
                                        } else {
                                            $finalPrice = $variant->price;
                                        }

                                        // Calculate totals
                                        $itemTotal = $finalPrice * $item->quantity;
                                        $originalItemTotal = $variant->price * $item->quantity;

                                        $calculatedSubtotal += $itemTotal;
                                        $calculatedOriginalSubtotal += $originalItemTotal;

                                        if ($hasDiscount) {
                                            $calculatedTotalSavings += $originalItemTotal - $itemTotal;
                                        }
                                    }
                                }

                                // Use calculated values instead of controller variables
                                $displaySubtotal = $calculatedSubtotal;
                                $displayTotalSavings = $calculatedTotalSavings;
                                $displayOriginalSubtotal = $calculatedOriginalSubtotal;
                                $displayTax = $displaySubtotal * 0.08;
                                $displayGrandTotal = $displaySubtotal + $displayTax; // Shipping is 0
                            @endphp

                            <!-- Total Savings Display -->
                            @if ($displayTotalSavings > 0)
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                                <i class="fas fa-tags text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-green-800">
                                                    {{ __('messages.total_savings') }}</p>
                                                <p class="text-xs text-green-600">{{ __('messages.discounts_applied') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-lg font-bold text-green-700">-${{ number_format($displayTotalSavings, 2) }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Order Items -->
                            <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                                @foreach ($items as $item)
                                    @php
                                        // Get variant and product info
                                        $variant = $item->variant ?? null;
                                        $product = $variant->product ?? null;

                                        // Get the FINAL price - use same logic as cart
                                        $finalPrice = 0;
                                        $hasDiscount = $variant->has_discount ?? false;
                                        $discountPercentage = (int) $variant->discount_value ?? 0;

                                        if ($variant) {
                                            // Method 1: Check if variant has a discount method
                                            if (method_exists($variant, 'getFinalPriceAttribute')) {
                                                $finalPrice = $variant->final_price;
                                            }
                                            // Method 2: Check if there's a sale_price or discounted_price
                                            elseif (isset($variant->sale_price) && $variant->sale_price > 0) {
                                                $finalPrice = $variant->sale_price;
                                                $hasDiscount = true;
                                                $discountPercentage = round(
                                                    (($variant->price - $variant->sale_price) / $variant->price) * 100,
                                                );
                                            }
                                            // Method 3: Check if variant has any discount field
                                            elseif (isset($variant->discount_price) && $variant->discount_price > 0) {
                                                $finalPrice = $variant->discount_price;
                                                $hasDiscount = true;
                                                $discountPercentage = round(
                                                    (($variant->price - $variant->discount_price) / $variant->price) *
                                                        100,
                                                );
                                            }
                                            // Method 4: Check for discount percentage
                                            elseif (isset($variant->discount_value) && $variant->discount_value > 0) {
                                                $finalPrice = $variant->price * (1 - $variant->discount_value / 100);
                                                $hasDiscount = true;
                                                $discountPercentage = $variant->discount_value;
                                            }
                                            // Method 5: Last resort - use regular price
                                            else {
                                                $finalPrice = $variant->price;
                                            }
                                        }

                                        // Calculate item totals
                                        $originalItemTotal = ($variant->price ?? 0) * $item->quantity;
                                        $finalItemTotal = $finalPrice * $item->quantity;
                                        $itemSavings = $hasDiscount ? $originalItemTotal - $finalItemTotal : 0;
                                    @endphp

                                    <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                        @if (isset($item->product_image) && $item->product_image)
                                            <div class="relative">
                                                <img src="{{ Str::startsWith($item->product_image, ['http://', 'https://'])
                                                    ? $item->product_image
                                                    : asset('storage/' . $item->product_image) }}"
                                                    alt="{{ $item->product_name ?? __('messages.product') }}"
                                                    class="w-16 h-16 object-cover rounded-xl">
                                                @if ($hasDiscount)
                                                    <!-- Discount Badge -->
                                                    <span
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10">
                                                        -{{ $discountPercentage }}%
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="relative">
                                                <div
                                                    class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                                    <i class="fas fa-tshirt text-gray-400"></i>
                                                </div>
                                                @if ($hasDiscount)
                                                    <!-- Discount Badge -->
                                                    <div
                                                        class="absolute -top-1 -left-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center z-10">
                                                        <i class="fas fa-tag text-xs"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm line-clamp-1">
                                                {{ $item->product_name ?? __('messages.product') }}</h3>
                                            <div class="mt-1">
                                                @if ($hasDiscount && $itemSavings > 0)
                                                    <p
                                                        class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full inline-block mt-1">
                                                        <i class="fas fa-piggy-bank mr-1"></i>
                                                        Save ${{ number_format($itemSavings, 2) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">{{ __('messages.size') }}:
                                                {{ $item->size ?? __('messages.na') }}</p>
                                            <p class="text-sm text-gray-500">{{ __('messages.color') }}:
                                                {{ $item->color ?? __('messages.na') }}</p>
                                            <p class="text-sm text-gray-500">{{ __('messages.quantity') }}:
                                                {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if ($hasDiscount && $itemSavings > 0)
                                                <p class="text-xs text-gray-500 line-through mb-1">
                                                    ${{ number_format($originalItemTotal, 2) }}
                                                </p>
                                            @endif
                                            <p class="font-semibold text-gray-900">
                                                ${{ number_format($finalItemTotal, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="space-y-3 mb-6">
                                @if ($displayTotalSavings > 0)
                                    <div class="flex justify-between text-gray-600">
                                        <span>{{ __('messages.original_subtotal') }}</span>
                                        <span>${{ number_format($displayOriginalSubtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-green-600">
                                        <span>{{ __('messages.discount') }}</span>
                                        <span class="font-semibold">-${{ number_format($displayTotalSavings, 2) }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('messages.subtotal') }} ({{ $items->count() }}
                                        {{ __('messages.items') }})</span>
                                    <span id="subtotal">${{ number_format($displaySubtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('messages.shipping') }}</span>
                                    <span class="text-green-600">{{ __('messages.free') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ __('messages.tax') }} (8%)</span>
                                    <span id="tax">${{ number_format($displayTax, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <span>{{ __('messages.total') }}</span>
                                        <span id="grand-total">${{ number_format($displayGrandTotal, 2) }}</span>
                                    </div>
                                    @php
                                        $exchangeRate = 4000;
                                        $grandTotalKHR = $displayGrandTotal * $exchangeRate;
                                        $savingsKHR = $displayTotalSavings * $exchangeRate;
                                    @endphp
                                    <p class="text-xs text-gray-500 text-right mt-1">
                                        ≈ ៛{{ number_format($grandTotalKHR, 0) }} KHR
                                    </p>
                                    @if ($displayTotalSavings > 0)
                                        <p class="text-xs text-green-600 text-right mt-1">
                                            <i class="fas fa-shopping-bag mr-1"></i>
                                            You saved ៛{{ number_format($savingsKHR, 0) }} KHR
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Security Badges -->
                            <div class="flex items-center justify-center gap-4 mb-6 py-4 border-t border-gray-200">
                                <div class="text-center">
                                    <div
                                        class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-lock text-green-600 text-sm"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('messages.secure') }}</p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-shield-alt text-blue-600 text-sm"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('messages.protected') }}</p>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                        <i class="fas fa-bolt text-purple-600 text-sm"></i>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('messages.fast') }}</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full bg-gray-900 text-white py-4 px-6 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105 shadow-lg flex items-center justify-center gap-3 checkout-btn relative">
                                <i class="fa-solid fa-cart-shopping"></i>
                                @if ($displayTotalSavings > 0)
                                    <div
                                        class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                                        <i class="fas fa-tag text-xs"></i>
                                    </div>
                                @endif
                                {{ __('messages.pay') }} ${{ number_format($displayGrandTotal, 2) }}
                            </button>

                            <!-- Continue Shopping -->
                            <div class="mt-4 text-center">
                                <a href="{{ route('cart') }}"
                                    class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-arrow-left"></i>
                                    {{ __('messages.back_to_cart') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-gray-400 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.your_cart_is_empty') }}</h2>
                        <p class="text-gray-600 mb-8">{{ __('messages.add_items_to_checkout') }}</p>
                        <a href="{{ route('products.all') }}"
                            class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105">
                            <i class="fas fa-bag-shopping"></i>
                            {{ __('messages.browse_products') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 animate-scaleIn">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">{{ __('messages.scan_to_pay') }}</h3>
                <button type="button" onclick="closeQRModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="text-center mb-6">
                <div id="qrCodeImage" class="mx-auto mb-4">
                    <!-- QR code will be generated here -->
                    <div class="w-64 h-64 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                        <i class="fas fa-qrcode text-gray-300 text-6xl"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">{{ __('messages.amount_to_pay') }}</p>
                    <p class="text-2xl font-bold text-gray-900" id="qrAmount">$0.00</p>
                    <p class="text-sm text-gray-500" id="qrAmountKHR">≈ ៛0 KHR</p>
                </div>

                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-blue-600 text-sm" id="qrBankIcon"></i>
                    </div>
                    <p class="font-medium text-gray-900" id="qrBankName"></p>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    {{ __('messages.qr_modal_instructions') }}
                </p>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                        <p class="text-sm font-medium text-yellow-800">
                            {{ __('messages.payment_expires_in') }}
                        </p>
                    </div>
                    <div class="mt-2 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900" id="countdown">10:00</div>
                            <p class="text-xs text-gray-600">{{ __('messages.minutes_seconds') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <button onclick="simulatePayment()"
                    class="w-full bg-green-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    {{ __('messages.simulate_payment') }}
                </button>
                <button onclick="closeQRModal()"
                    class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                    {{ __('messages.cancel_payment') }}
                </button>
            </div>
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

        /* Add animation for modal */
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-scaleIn {
            animation: scaleIn 0.2s ease-out;
        }

        /* Bank option selection */
        .bank-option input:checked+div {
            border-color: #3b82f6;
            background-color: #eff6ff;
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
            const checkoutForm = document.querySelector('form');
            const checkoutBtn = document.querySelector('.checkout-btn');

            // Get all form fields in new address section
            const newAddressFields = document.querySelectorAll(
                '#new-address-section input, #new-address-section select, #new-address-section textarea'
            );

            // Define which fields should be required when using new address
            const requiredFieldIds = ['name', 'email', 'phone', 'city', 'state', 'zip', 'address'];

            // Check which tab should be active initially based on PHP condition
            const initialActiveTab = document.querySelector('.address-tab.active');
            const initialTabType = initialActiveTab ? initialActiveTab.getAttribute('data-tab') : 'new';

            // Set initial visibility
            if (initialTabType === 'saved') {
                savedSection.classList.remove('hidden');
                newSection.classList.add('hidden');
                addressOptionInput.value = 'saved';
                // Disable new address fields
                disableNewAddressFields();
            } else {
                savedSection.classList.add('hidden');
                newSection.classList.remove('hidden');
                addressOptionInput.value = 'new';
                // Enable new address fields
                enableNewAddressFields();
            }

            // Initialize form state based on active tab
            function initializeFormState() {
                const activeTab = document.querySelector('.address-tab.active');
                if (activeTab && activeTab.getAttribute('data-tab') === 'new') {
                    enableNewAddressFields();
                } else {
                    disableNewAddressFields();
                }
            }

            function enableNewAddressFields() {
                newAddressFields.forEach(field => {
                    const fieldId = field.id;
                    field.disabled = false;

                    // Set required attribute for specific fields
                    if (requiredFieldIds.includes(fieldId)) {
                        field.setAttribute('required', 'required');
                    } else {
                        field.removeAttribute('required');
                    }
                });
            }

            function disableNewAddressFields() {
                newAddressFields.forEach(field => {
                    const fieldId = field.id;
                    field.disabled = true;
                    field.removeAttribute('required');
                    field.classList.remove('border-red-500'); // Clear any error styling

                    // Clear any error messages
                    const errorMsg = field.nextElementSibling;
                    if (errorMsg && errorMsg.classList.contains('text-red-500')) {
                        errorMsg.remove();
                    }
                });
            }

            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabType = this.getAttribute('data-tab');

                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide sections and handle form state
                    if (tabType === 'saved') {
                        savedSection.classList.remove('hidden');
                        newSection.classList.add('hidden');
                        addressOptionInput.value = 'saved';
                        disableNewAddressFields();
                        clearNewAddressFormErrors();
                    } else {
                        savedSection.classList.add('hidden');
                        newSection.classList.remove('hidden');
                        addressOptionInput.value = 'new';
                        enableNewAddressFields();
                        deselectSavedAddresses();
                    }
                });
            });

            function clearNewAddressFormErrors() {
                const errorElements = document.querySelectorAll('#new-address-section .text-red-500');
                errorElements.forEach(el => {
                    // Only remove error messages that are not part of Laravel validation
                    if (!el.classList.contains('laravel-error')) {
                        el.remove();
                    }
                });

                requiredFieldIds.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) {
                        field.classList.remove('border-red-500');
                    }
                });
            }

            function deselectSavedAddresses() {
                const savedAddressOptions = document.querySelectorAll('.saved-address-option');
                savedAddressOptions.forEach(option => {
                    const radio = option.querySelector('input[type="radio"]');
                    if (radio) radio.checked = false;

                    // Reset visual selection
                    const dot = option.querySelector('.w-3.h-3');
                    const box = option.querySelector('.border-2');
                    if (dot) dot.classList.add('scale-0');
                    if (box) box.classList.remove('border-gray-900', 'bg-gray-50');
                });

                if (savedAddressIdInput) savedAddressIdInput.value = '';
            }

            // Saved address selection
            const savedAddressOptions = document.querySelectorAll('.saved-address-option');
            savedAddressOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selection from all options
                    savedAddressOptions.forEach(opt => {
                        const radio = opt.querySelector('input[type="radio"]');
                        if (radio) radio.checked = false;

                        // Reset visual selection
                        const dot = opt.querySelector('.w-3.h-3');
                        const box = opt.querySelector('.border-2');
                        if (dot) dot.classList.add('scale-0');
                        if (box) box.classList.remove('border-gray-900', 'bg-gray-50');
                    });

                    // Select this option
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        if (savedAddressIdInput) savedAddressIdInput.value = radio.getAttribute(
                            'data-address-id');

                        // Update visual selection
                        const dot = this.querySelector('.w-3.h-3');
                        const box = this.querySelector('.border-2');
                        if (dot) dot.classList.remove('scale-0');
                        if (box) box.classList.add('border-gray-900', 'bg-gray-50');

                        // Switch to saved address tab if not already there
                        if (addressOptionInput.value === 'new') {
                            tabs[0].click();
                        }
                    }
                });
            });

            // Different billing address toggle
            const differentBillingToggle = document.getElementById('different_billing_toggle');
            const billingAddressFields = document.getElementById('billing_address_fields');

            if (differentBillingToggle) {
                differentBillingToggle.addEventListener('change', function() {
                    if (this.checked) {
                        billingAddressFields.classList.remove('hidden');
                        // Only set required if we're using new address form
                        if (addressOptionInput.value === 'new') {
                            document.querySelectorAll('#billing_address_fields [name^="billing_"]').forEach(
                                field => {
                                    field.required = true;
                                });
                        }
                    } else {
                        billingAddressFields.classList.add('hidden');
                        // Remove required from billing fields
                        document.querySelectorAll('#billing_address_fields [name^="billing_"]').forEach(
                            field => {
                                field.required = false;
                                field.classList.remove('border-red-500');
                            });
                    }
                });
            }

            // Save address toggle functionality
            const saveAddressToggle = document.getElementById('save_address_toggle');
            const addressNameField = document.getElementById('address_name_field');
            const makeDefaultField = document.getElementById('make_default_field');

            if (saveAddressToggle) {
                saveAddressToggle.addEventListener('change', function() {
                    const addressNameInput = document.getElementById('address_name');

                    if (this.checked) {
                        addressNameField.classList.remove('hidden');
                        makeDefaultField.classList.remove('hidden');
                        // Make address name required if saving address
                        if (addressNameInput) addressNameInput.required = true;
                    } else {
                        addressNameField.classList.add('hidden');
                        makeDefaultField.classList.add('hidden');
                        // Remove required from address name
                        if (addressNameInput) addressNameInput.required = false;
                    }
                });
            }

            // Initialize form state on load
            initializeFormState();

            // Form submission validation
            checkoutForm.addEventListener('submit', function(e) {
                const selectedAddressOption = addressOptionInput.value;

                if (selectedAddressOption === 'saved') {
                    // Validate saved address selection
                    if (!savedAddressIdInput.value) {
                        e.preventDefault();
                        alert('{{ __('messages.select_saved_address') }}');
                        // Switch to saved address tab if not already there
                        if (!tabs[0].classList.contains('active')) {
                            tabs[0].click();
                        }
                        return false;
                    }
                } else {
                    // Validate new address fields
                    let isValid = true;
                    const errors = [];

                    requiredFieldIds.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field && !field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            errors.push(fieldId);
                        } else if (field) {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('{{ __('messages.fill_required_fields') }}');
                        return false;
                    }
                }

                // Disable button and show loading state
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> {{ __('messages.processing') }}...';
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payment method selection
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const qrBankSection = document.getElementById('qr_bank_selection');

            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    if (this.value === 'qr_code') {
                        qrBankSection.classList.remove('hidden');
                        // Require bank selection for QR code
                        document.querySelectorAll('input[name="qr_bank"]')[0].required = true;
                    } else {
                        qrBankSection.classList.add('hidden');
                        document.querySelectorAll('input[name="qr_bank"]').forEach(bank => {
                            bank.required = false;
                        });
                    }
                });
            });

            // Bank selection
            const bankOptions = document.querySelectorAll('.bank-option');
            bankOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        // Update visual selection
                        bankOptions.forEach(opt => {
                            opt.querySelector('div').classList.remove('border-blue-500',
                                'border-green-500', 'border-purple-500',
                                'border-red-500', 'border-yellow-500');
                        });
                        this.querySelector('div').classList.add('border-' + radio.value);
                    }
                });
            });

            // Form submission
            const checkoutForm = document.querySelector('form');
            const checkoutBtn = document.querySelector('.checkout-btn');

            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

                if (paymentMethod === 'qr_code') {
                    // Validate bank selection
                    const selectedBank = document.querySelector('input[name="qr_bank"]:checked');
                    if (!selectedBank) {
                        alert('Please select a bank for QR code payment.');
                        return false;
                    }

                    // Show QR code modal
                    showQRModal(selectedBank.value);

                    // Don't submit the form - we'll handle it after QR payment
                    return false;
                } else {
                    // Proceed with Stripe payment
                    proceedToStripe();
                }
            });
        });

        let countdownInterval;
        let paymentTimeout;

        function showQRModal(bank) {
            const modal = document.getElementById('qrCodeModal');
            const grandTotal = parseFloat('{{ $displayGrandTotal }}');
            const exchangeRate = 4000;
            const totalKHR = grandTotal * exchangeRate;

            // Set bank info
            const bankInfo = {
                'aba': {
                    name: 'ABA Bank',
                    icon: 'fa-building',
                    color: 'blue'
                },
                'acleda': {
                    name: 'ACLEDA Bank',
                    icon: 'fa-university',
                    color: 'green'
                },
                'wing': {
                    name: 'Wing',
                    icon: 'fa-mobile-alt',
                    color: 'purple'
                },
            };

            document.getElementById('qrBankName').textContent = bankInfo[bank].name;
            document.getElementById('qrBankIcon').className =
                `${bankInfo[bank].icon} text-${bankInfo[bank].color}-600 text-sm`;

            // Set amounts
            document.getElementById('qrAmount').textContent = '$' + grandTotal.toFixed(2);
            document.getElementById('qrAmountKHR').textContent = '≈ ៛' + totalKHR.toLocaleString() + ' KHR';

            // Generate dummy QR code (in production, you'd generate a real QR code)
            const qrContainer = document.getElementById('qrCodeImage');
            qrContainer.innerHTML = `
                <div class="relative mx-auto">
                    <div class="w-64 h-64 bg-white rounded-lg border-4 border-gray-300 p-4 mx-auto">
                        <div class="relative w-full h-full">
                            <!-- Simulated QR code pattern -->
                            <div class="absolute inset-0 flex flex-wrap">
                                ${Array(16).fill().map((_, i) => 
                                    `<div class="w-1/4 h-1/4 ${Math.random() > 0.5 ? 'bg-black' : 'bg-white'} border border-gray-100"></div>`
                                ).join('')}
                            </div>
                            <!-- Bank logo overlay -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                                <i class="${bankInfo[bank].icon} text-${bankInfo[bank].color}-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Start countdown
            startCountdown();

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Set timeout to simulate payment verification
            paymentTimeout = setTimeout(() => {
                // Simulate payment timeout
                if (modal.classList.contains('hidden')) return;
                alert('Payment timeout. Please try again.');
                closeQRModal();
            }, 600000); // 10 minutes
        }

        function closeQRModal() {
            const modal = document.getElementById('qrCodeModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            clearInterval(countdownInterval);
            clearTimeout(paymentTimeout);
        }

        function startCountdown() {
            let timeLeft = 600; // 10 minutes in seconds
            const countdownElement = document.getElementById('countdown');

            clearInterval(countdownInterval);

            countdownInterval = setInterval(() => {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.textContent = '00:00';
                    return;
                }

                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                countdownElement.textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
        }

        function simulatePayment() {
            // In production, this would be replaced with actual payment verification
            const checkoutForm = document.querySelector('form');
            const paymentMethod = document.createElement('input');
            paymentMethod.type = 'hidden';
            paymentMethod.name = 'payment_method';
            paymentMethod.value = 'qr_code';
            checkoutForm.appendChild(paymentMethod);

            // Add loading state
            const checkoutBtn = document.querySelector('.checkout-btn');
            checkoutBtn.disabled = true;
            checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying Payment...';

            // Close modal and submit form
            closeQRModal();

            // Simulate API call delay
            setTimeout(() => {
                checkoutForm.submit();
            }, 1500);
        }

        function proceedToStripe() {
            const checkoutForm = document.querySelector('form');
            const checkoutBtn = document.querySelector('.checkout-btn');

            // Disable button and show loading state
            checkoutBtn.disabled = true;
            checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            // Submit the form
            checkoutForm.submit();
        }
    </script>
@endsection
