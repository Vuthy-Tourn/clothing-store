<!-- Add Product Modal -->
<div id="addProductModal"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-5xl rounded-xl shadow-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-blue-600"></i> {{ __('admin.products.modal.add_title') }}
            </h2>
            <button onclick="ProductModal.closeAdd()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                &times;
            </button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.product_name') }} <span class="ml-1 text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="{{ __('admin.products.modal.name_placeholder') }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.category') }} <span class="ml-1 text-red-500">*</span>
                    </label>
                    <select name="category_id" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('admin.products.modal.category_placeholder') }}</option>

                        @php
                            $groupedCategories = $categories->groupBy('gender');
                        @endphp

                        @foreach (['men' => __('admin.products.category_groups.men'), 'women' => __('admin.products.category_groups.women'), 'kids' => __('admin.products.category_groups.kids'), 'unisex' => __('admin.products.category_groups.unisex')] as $gender => $label)
                            @if ($groupedCategories->has($gender))
                                <optgroup label="{{ $label }}">
                                    @foreach ($groupedCategories[$gender] as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach

                        @foreach ($categories->whereNull('gender') as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.material') }}
                    </label>
                    <input type="text" name="material" value="{{ old('material') }}"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3"
                        placeholder="{{ __('admin.products.modal.material_placeholder') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.brand') }}
                    </label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="{{ __('admin.products.modal.brand_placeholder') }}">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    {{ __('admin.products.modal.description') }} <span class="ml-1 text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="{{ __('admin.products.modal.description_placeholder') }}">{{ old('description') }}</textarea>
            </div>

            <!-- Product-Level Discount Section -->
            {{-- <div class="mb-8 border border-gray-200 rounded-lg p-6 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tag mr-2 text-blue-600"></i> {{ __('admin.products.modal.discount_settings') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                {{ __('admin.products.modal.discount_type') }}
                            </label>
                            <select name="discount_type"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 discount-type-select">
                                <option value="">{{ __('admin.products.modal.discount_type_none') }}</option>
                                <option value="percentage"
                                    {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                    {{ __('admin.products.modal.discount_type_percentage') }}
                                </option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                    {{ __('admin.products.modal.discount_type_fixed') }}
                                </option>
                            </select>
                        </div>

                        <div class="discount-value-field" style="display: none;">
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                {{ __('admin.products.modal.discount_value') }}
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 discount-prefix">
                                    %</div>
                                <input type="number" step="0.01" name="discount_value"
                                    value="{{ old('discount_value') }}"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="{{ __('admin.products.modal.discount_value_placeholder') }}">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                {{ __('admin.products.modal.discount_start') }}
                            </label>
                            <input type="datetime-local" name="discount_start" value="{{ old('discount_start') }}"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                {{ __('admin.products.modal.discount_end') }}
                            </label>
                            <input type="datetime-local" name="discount_end" value="{{ old('discount_end') }}"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Variants Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-lg font-semibold text-gray-900">
                        {{ __('admin.products.modal.variants') }}
                    </label>
                    <button type="button" onclick="addVariantRow()"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        <i class="fas fa-plus mr-1"></i> {{ __('admin.products.modal.add_variant') }}
                    </button>
                </div>

                <div id="variantsContainer" class="space-y-4">
                    <!-- Default variant row -->
                    <div class="variant-row border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.size') }} <span class="ml-1 text-red-500">*</span>
                                </label>
                                <select name="variants[0][size]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                    <option value="">{{ __('admin.products.modal.size_placeholder') }}</option>
                                    @foreach ([__('admin.products.sizes.xs'), __('admin.products.sizes.s'), __('admin.products.sizes.m'), __('admin.products.sizes.l'), __('admin.products.sizes.xl'), __('admin.products.sizes.xxl'), __('admin.products.sizes.xxxl'), __('admin.products.sizes.free')] as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.color') }} <span class="ml-1 text-red-500">*</span>
                                </label>
                                <input type="text" name="variants[0][color]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="{{ __('admin.products.modal.color_placeholder') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.color_code') }}
                                </label>
                                <input type="color" name="variants[0][color_code]"
                                    class="w-full h-10 border border-gray-200 bg-white text-gray-900 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.sku') }}
                                </label>
                                <input type="text" name="variants[0][sku]" readonly
                                    class="w-full border border-gray-200 bg-gray-100 text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="{{ __('admin.products.modal.sku_placeholder') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.price') }} <span class="ml-1 text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="variants[0][price]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="{{ __('admin.products.modal.price_placeholder') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.stock') }} <span class="ml-1 text-red-500">*</span>
                                </label>
                                <input type="number" name="variants[0][stock]" min="0" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="{{ __('admin.products.modal.stock_placeholder') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.stock_alert') }}
                                </label>
                                <input type="number" name="variants[0][stock_alert]" min="0" value="10"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <!-- Variant-Level Discount Fields -->
                        <!-- Variant-Level Discount Fields - Unified Structure -->
                        <div
                            class="variant-discount-section mb-4 p-4 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 rounded-lg border border-blue-200/50 shadow-sm hover:shadow-md transition-all duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 mr-2 text-xs">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        Variant Discount Configuration
                                    </h4>
                                    <p class="text-xs text-gray-600 mb-3">
                                        Configure specific discounts for this variant. Will override product-level
                                        discounts.
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-medium border border-blue-200">
                                        Variant #<span class="font-bold">1</span>
                                    </span>
                                    <button type="button" onclick="DiscountManager.calculateVariantDiscount(0)"
                                        class="text-xs px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center"
                                        title="Calculate discount">
                                        <i class="fas fa-calculator mr-1.5"></i> Calculate
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-semibold text-gray-900">
                                            <span class="flex items-center">
                                                <i class="fas fa-percentage mr-1.5 text-gray-500 text-xs"></i>
                                                Discount Type
                                            </span>
                                        </label>
                                        <div class="text-xs text-gray-500 cursor-help"
                                            data-tooltip="Select discount calculation method">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                    </div>
                                    <div class="relative group">
                                        <select name="variants[0][discount_type]"
                                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer variant-discount-type"
                                            onchange="DiscountManager.handleVariantDiscountTypeChange(this, 0)">
                                            <option value="">No Discount</option>
                                            <option value="percentage">Percentage Discount (%)</option>
                                            <option value="fixed">Fixed Amount Discount ($)</option>
                                        </select>
                                        <div
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2 variant-discount-value-field" style="display: none;">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-semibold text-gray-900">
                                            <span class="flex items-center">
                                                <i class="fas fa-money-bill-wave mr-1.5 text-gray-500 text-xs"></i>
                                                Discount Value
                                            </span>
                                        </label>
                                        <div class="text-xs text-gray-500 cursor-help"
                                            data-tooltip="Enter discount amount">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                    </div>
                                    <div class="relative group">
                                        <div
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium variant-discount-prefix">
                                            <i class="fas fa-dollar-sign text-xs"></i>
                                        </div>
                                        <input type="number" step="0.01" name="variants[0][discount_value]"
                                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 variant-discount-value"
                                            placeholder="0.00" min="0" max="100"
                                            oninput="DiscountManager.updateDiscountPreview(0)">
                                        <div
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-semibold text-gray-900">
                                            <span class="flex items-center">
                                                <i class="fas fa-toggle-on mr-1.5 text-gray-500 text-xs"></i>
                                                Discount Status
                                            </span>
                                        </label>
                                        <div class="text-xs text-gray-500 cursor-help"
                                            data-tooltip="Enable/disable discount for this variant">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="variants[0][has_discount]" value="1"
                                                class="sr-only peer variant-has-discount"
                                                onchange="DiscountManager.toggleDiscountStatus(this, 0)">
                                            <div
                                                class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                            </div>
                                            <div class="ml-3 flex items-center space-x-2">
                                                <span
                                                    class="text-sm font-medium text-gray-900 peer-checked:text-green-700 transition-colors">Active</span>
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"
                                                    id="discountStatusBadge-0">
                                                    OFF
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200/50">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                    <h5 class="text-xs font-semibold text-gray-900">Discount Period (Optional)</h5>
                                    <span class="ml-2 text-xs text-gray-500">Leave empty for indefinite discount</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-900 flex items-center">
                                            <i class="far fa-calendar-plus mr-2 text-gray-500"></i>
                                            Start Date & Time
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                                <i class="far fa-clock"></i>
                                            </div>
                                            <input type="datetime-local" name="variants[0][discount_start]"
                                                class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                onchange="DiscountManager.updateDiscountPeriod(0)">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-900 flex items-center">
                                            <i class="far fa-calendar-minus mr-2 text-gray-500"></i>
                                            End Date & Time
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                                <i class="far fa-clock"></i>
                                            </div>
                                            <input type="datetime-local" name="variants[0][discount_end]"
                                                class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                onchange="DiscountManager.updateDiscountPeriod(0)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center mr-3">
                                                <i class="fas fa-chart-line text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-semibold text-gray-900">Price Breakdown</h5>
                                                <p class="text-xs text-gray-600">Based on current configuration</p>
                                            </div>
                                        </div>
                                        <div id="discountActiveStatus-0"
                                            class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
                                            <i class="fas fa-info-circle mr-1"></i> Configure
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                                            <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                                <i class="fas fa-money-bill mr-1"></i> Original Price
                                            </div>
                                            <div class="text-lg font-bold text-gray-900 original-price-display"
                                                id="originalPrice-0">
                                                $0.00
                                            </div>
                                        </div>

                                        <div
                                            class="text-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                                            <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                                <i class="fas fa-tags mr-1"></i> Discounted Price
                                            </div>
                                            <div class="text-xl font-bold text-green-700 discounted-price-display"
                                                id="discountedPrice-0">
                                                $0.00
                                            </div>
                                            <div class="text-xs text-green-600 mt-1 discount-type-display"
                                                id="discountType-0">
                                                No discount applied
                                            </div>
                                        </div>

                                        <div
                                            class="text-center p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100">
                                            <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                                <i class="fas fa-piggy-bank mr-1"></i> You Save
                                            </div>
                                            <div class="text-lg font-bold text-red-700 savings-display"
                                                id="savings-0">
                                                $0.00
                                            </div>
                                            <div class="text-xs text-red-600 mt-1" id="savingsPercentage-0">
                                                0% off
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-history mr-2"></i>
                                                <span>Discount Period: </span>
                                                <span class="font-medium ml-1 text-gray-900 period-display"
                                                    id="periodDisplay-0">
                                                    Not set
                                                </span>
                                            </div>
                                            <div class="text-xs">
                                                <span class="text-gray-600">Updated: </span>
                                                <span class="font-medium text-gray-900" id="lastUpdated-0">
                                                    Just now
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-100 rounded-lg hidden"
                                id="validationMessage-0">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-yellow-800" id="validationText-0"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.cost_price') }}
                                </label>
                                <input type="number" step="0.01" name="variants[0][cost_price]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">
                                    {{ __('admin.products.modal.weight') }}
                                </label>
                                <input type="number" step="0.01" name="variants[0][weight]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="variants[0][is_active]" value="1" checked
                                    class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">
                                    {{ __('admin.products.modal.variant_active') }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="mb-6">
                <label class="block text-lg font-semibold text-gray-900 mb-4">
                    {{ __('admin.products.modal.product_images') }}
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Primary Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            {{ __('admin.products.modal.primary_image') }} <span class="ml-1 text-red-500">*</span>
                        </label>
                        <div id="primaryImageUpload"
                            class="primary-upload-area border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-gray-300 transition-colors duration-200 relative bg-white">
                            <input type="file" id="primaryImageInput" name="images[0][image]" accept="image/*"
                                required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="ImageManager.handlePrimaryImageUpload(event)">
                            <div id="primaryUploadContent" class="upload-content">
                                <div
                                    class="mx-auto w-16 h-16 mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700 mb-1">
                                    {{ __('admin.products.modal.primary_image_upload') }}
                                </p>
                                <p class="text-gray-500 text-sm">{{ __('admin.products.modal.image_formats') }}</p>
                            </div>

                            <!-- Preview Container -->
                            <div id="primaryImagePreview" class="hidden mt-4">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div
                                        class="w-16 h-16 rounded overflow-hidden bg-gray-200 flex items-center justify-center">
                                        <img id="primaryPreviewImg" class="w-full h-full object-cover" src=""
                                            alt="{{ __('admin.products.modal.primary_image') }}">
                                    </div>
                                    <div class="flex-1 min-w-0 text-left">
                                        <p id="primaryFileName" class="text-sm font-medium text-gray-900 truncate">
                                        </p>
                                        <p id="primaryFileSize" class="text-xs text-gray-500"></p>
                                        <p id="primaryFileDimensions" class="text-xs text-gray-500"></p>
                                    </div>
                                    <button type="button" onclick="ImageManager.clearPrimaryImage()"
                                        class="text-gray-400 hover:text-red-500 p-2 rounded-full hover:bg-red-50 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="images[0][is_primary]" value="1">
                            <input type="hidden" name="images[0][sort_order]" value="0">
                        </div>
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900">
                                    {{ __('admin.products.modal.additional_images') }}
                                </label>
                                <p class="text-xs text-gray-500">
                                    {{ __('admin.products.modal.additional_images_limit') }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="ImageManager.addImageRow()"
                                    class="text-blue-600 text-sm font-medium flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> {{ __('admin.products.modal.add_image') }}
                                </button>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="imageProgressBar"
                                    class="h-full bg-blue-500 rounded-full transition-all duration-300"
                                    style="width: 0%"></div>
                            </div>
                        </div>

                        <div id="additionalImagesContainer" class="space-y-4">
                            <!-- Additional image rows will be added here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.status') }} <span class="ml-1 text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                            {{ __('admin.products.modal.status_active') }}
                        </option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                            {{ __('admin.products.modal.status_inactive') }}
                        </option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                            {{ __('admin.products.modal.status_draft') }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center">
                    <div class="mr-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">
                                {{ __('admin.products.modal.featured_product') }}
                            </span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" value="1"
                                {{ old('is_new') ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">
                                {{ __('admin.products.modal.new_arrival') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="ProductModal.closeAdd()"
                    class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                    {{ __('admin.products.modal.cancel') }}
                </button>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.products.modal.create') }}
                </button>
            </div>
        </form>
    </div>
</div>
