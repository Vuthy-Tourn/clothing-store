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
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
            <div class="mb-8 border border-gray-200 rounded-lg p-6 bg-gray-50">
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
            </div>

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
                        <div class="variant-discount-section mb-4 p-3 bg-gray-100 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-tag mr-2 text-sm"></i> {{ __('admin.products.modal.variant_discount') }}
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-900 mb-2">
                                        <span class="inline-flex items-center">
                                            {{ __('admin.products.modal.variant_discount_type') }}
                                            <span class="ml-1 text-red-500">*</span>
                                        </span>
                                    </label>
                                    <select name="variants[0][discount_type]"
                                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all variant-discount-type">
                                        <option value="">{{ __('admin.products.modal.variant_discount_type_select') }}</option>
                                        <option value="percentage">{{ __('admin.products.modal.discount_type_percentage') }}</option>
                                        <option value="fixed">{{ __('admin.products.modal.discount_type_fixed') }}</option>
                                    </select>
                                </div>

                                <div class="variant-discount-value-field" style="display: none;">
                                    <label class="block text-xs font-semibold text-gray-900 mb-2">
                                        {{ __('admin.products.modal.variant_discount_value') }}
                                        <span class="ml-1 text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-700 variant-discount-prefix font-medium">
                                            %</div>
                                        <input type="number" step="0.01" name="variants[0][discount_value]"
                                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="0.00" min="0" max="100">
                                        <div
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-900 mb-2">
                                        {{ __('admin.products.modal.variant_discount_status') }}
                                    </label>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="variants[0][has_discount]" value="1"
                                                class="sr-only peer variant-has-discount">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-gray-900">
                                                {{ __('admin.products.modal.variant_discount_active') }}
                                            </span>
                                        </label>
                                        <div class="tooltip" data-tip="{{ __('admin.products.modal.variant_discount_tooltip') }}">
                                            <i
                                                class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-900 mb-2">
                                            <span class="inline-flex items-center">
                                                <i class="far fa-calendar-start mr-2 text-gray-600"></i>
                                                {{ __('admin.products.modal.variant_discount_start') }}
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="datetime-local" name="variants[0][discount_start]"
                                                class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10 transition-all">
                                            <div
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                                <i class="far fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-900 mb-2">
                                            <span class="inline-flex items-center">
                                                <i class="far fa-calendar-times mr-2 text-gray-600"></i>
                                                {{ __('admin.products.modal.variant_discount_end') }}
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="datetime-local" name="variants[0][discount_end]"
                                                class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10 transition-all">
                                            <div
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                                <i class="far fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Discount Preview -->
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-lg discount-preview"
                                    style="display: none;">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-calculator text-blue-600 mr-2"></i>
                                            <span class="text-sm font-medium text-blue-900">
                                                {{ __('admin.products.modal.variant_discount_preview') }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-blue-700 discount-preview-value">$0.00
                                            </div>
                                            <div class="text-xs text-blue-600 discount-preview-type"></div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-blue-800">
                                        <span class="font-medium">{{ __('admin.products.modal.variant_discount_period') }}</span>
                                        <span class="discount-period text-blue-900">
                                            {{ __('admin.products.modal.variant_discount_period_not_set') }}
                                        </span>
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
                        <div
                            class="primary-upload-area border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-gray-300 transition-colors duration-200 relative">
                            <input type="file" name="images[0][image]" accept="image/*" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="ProductManager.handlePrimaryImageUpload(this)">
                            <div class="upload-content">
                                <div
                                    class="mx-auto w-12 h-12 mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-lg"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700 mb-1">
                                    {{ __('admin.products.modal.primary_image_upload') }}
                                </p>
                                <p class="text-gray-500 text-sm">{{ __('admin.products.modal.image_formats') }}</p>
                            </div>

                            <!-- Preview Container -->
                            <div id="primary-file-preview" class="hidden mt-3">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 rounded overflow-hidden bg-gray-200">
                                        <img id="primary-preview-image" class="w-full h-full object-cover"
                                            src="" alt="{{ __('admin.products.modal.primary_image') }}">
                                    </div>
                                    <div class="flex-1 min-w-0 text-left">
                                        <p id="primary-file-name" class="text-sm font-medium text-gray-900 truncate">
                                        </p>
                                        <p id="primary-file-size" class="text-xs text-gray-500"></p>
                                    </div>
                                    <button type="button" onclick="clearPrimaryFileInput()"
                                        class="text-gray-400 hover:text-red-500">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="images[0][is_primary]" value="1">
                        </div>
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-900">
                                    {{ __('admin.products.modal.additional_images') }}
                                </label>
                                <p class="text-xs text-gray-500">{{ __('admin.products.modal.additional_images_limit') }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">
                                    <span id="imageCount">0</span>/5 {{ __('admin.products.modal.images_added') }}
                                </span>
                                <button type="button" onclick="addImageRow()"
                                    class="text-blue-600 text-sm font-medium flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> {{ __('admin.products.modal.add_image') }}
                                </button>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="imageProgress"
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