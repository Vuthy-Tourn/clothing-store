<!-- Add Product Modal -->
<div id="addProductModal"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-5xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-blue-600"></i> Add New Product
            </h2>
            <button onclick="ProductModal.closeAdd()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                &times;
            </button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Product Name <span
                            class="ml-1 text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter product name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Category <span
                            class="ml-1 text-red-500">*</span></label>
                    <select name="category_id" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select category</option>
                        @foreach ($categories as $cat)
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
                    <label class="block text-sm font-medium text-gray-900 mb-2">Material</label>
                    <input type="text" name="material" value="{{ old('material') }}"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3"
                        placeholder="Enter material name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter brand name">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">Description <span
                        class="ml-1 text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Enter detailed product description">{{ old('description') }}</textarea>
            </div>

            <!-- Product-Level Discount Section -->
            <div class="mb-8 border border-gray-200 rounded-lg p-6 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tag mr-2 text-blue-600"></i> Product Discount Settings
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount Type</label>
                            <select name="discount_type"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 discount-type-select">
                                <option value="">No Discount</option>
                                <option value="percentage"
                                    {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)
                                </option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed
                                    Amount</option>
                            </select>
                        </div>

                        <div class="discount-value-field" style="display: none;">
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount Value</label>
                            <div class="relative">
                                <div
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 discount-prefix">
                                    %</div>
                                <input type="number" step="0.01" name="discount_value"
                                    value="{{ old('discount_value') }}"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 10">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount Start Date</label>
                            <input type="datetime-local" name="discount_start" value="{{ old('discount_start') }}"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount End Date</label>
                            <input type="datetime-local" name="discount_end" value="{{ old('discount_end') }}"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variants Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-lg font-semibold text-gray-900">Product Variants</label>
                    <button type="button" onclick="addVariantRow()"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        <i class="fas fa-plus mr-1"></i> Add Variant
                    </button>
                </div>

                <div id="variantsContainer" class="space-y-4">
                    <!-- Default variant row -->
                    <div class="variant-row border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Size <span
                                        class="ml-1 text-red-500">*</span></label>
                                <select name="variants[0][size]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Select Size</option>
                                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'] as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Color <span
                                        class="ml-1 text-red-500">*</span></label>
                                <input type="text" name="variants[0][color]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="e.g., Red, Black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Color Code</label>
                                <input type="color" name="variants[0][color_code]"
                                    class="w-full h-10 border border-gray-200 bg-white text-gray-900 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">SKU (Auto)</label>
                                <input type="text" name="variants[0][sku]" readonly
                                    class="w-full border border-gray-200 bg-gray-100 text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="Will be auto-generated">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Price ($) <span
                                        class="ml-1 text-red-500">*</span></label>
                                <input type="number" step="0.01" name="variants[0][price]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Stock <span
                                        class="ml-1 text-red-500">*</span></label>
                                <input type="number" name="variants[0][stock]" min="0" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Stock Alert</label>
                                <input type="number" name="variants[0][stock_alert]" min="0" value="10"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <!-- Variant-Level Discount Fields -->
                        <div class="variant-discount-section mb-4 p-3 bg-gray-100 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-tag mr-2 text-sm"></i> Variant Discount (Optional)
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-900 mb-2">
                                        <span class="inline-flex items-center">
                                            Discount Type
                                            <span class="ml-1 text-red-500">*</span>
                                        </span>
                                    </label>
                                    <select name="variants[0][discount_type]"
                                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all variant-discount-type">
                                        <option value="">Select Type</option>
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount ($)</option>
                                    </select>
                                </div>

                                <div class="variant-discount-value-field" style="display: none;">
                                    <label class="block text-xs font-semibold text-gray-900 mb-2">
                                        Discount Value
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
                                        Discount Status
                                    </label>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="variants[0][has_discount]" value="1"
                                                class="sr-only peer variant-has-discount">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-gray-900">Active</span>
                                        </label>
                                        <div class="tooltip" data-tip="Enable to activate discount">
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
                                                Start Date & Time
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
                                                End Date & Time
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
                                            <span class="text-sm font-medium text-blue-900">Discount Preview:</span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-blue-700 discount-preview-value">$0.00
                                            </div>
                                            <div class="text-xs text-blue-600 discount-preview-type"></div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-blue-800">
                                        <span class="font-medium">Period:</span>
                                        <span class="discount-period text-blue-900">Not set</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Cost Price ($)</label>
                                <input type="number" step="0.01" name="variants[0][cost_price]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Weight (kg)</label>
                                <input type="number" step="0.01" name="variants[0][weight]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="variants[0][is_active]" value="1" checked
                                    class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="mb-6">
                <label class="block text-lg font-semibold text-gray-900 mb-4">Product Images</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Primary Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Primary Image <span class="ml-1 text-red-500">*</span>
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
                                <p class="text-sm font-medium text-gray-700 mb-1">Click to upload primary image</p>
                                <p class="text-gray-500 text-sm">PNG, JPG, GIF, WebP up to 5MB</p>
                            </div>

                            <!-- Preview Container -->
                            <div id="primary-file-preview" class="hidden mt-3">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 rounded overflow-hidden bg-gray-200">
                                        <img id="primary-preview-image" class="w-full h-full object-cover"
                                            src="" alt="Primary image preview">
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
                                <label class="block text-sm font-medium text-gray-900">Additional Images</label>
                                <p class="text-xs text-gray-500">Up to 5 additional images</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">
                                    <span id="imageCount">0</span>/5 added
                                </span>
                                <button type="button" onclick="addImageRow()"
                                    class="text-blue-600 text-sm font-medium flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> Add Image
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
                    <label class="block text-sm font-medium text-gray-900 mb-2">Status <span
                            class="ml-1 text-red-500">*</span></label>
                    <select name="status" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="flex items-center">
                    <div class="mr-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" value="1"
                                {{ old('is_new') ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">New Arrival</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="ProductModal.closeAdd()"
                    class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                    Cancel
                </button>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                    <i class="fas fa-save mr-2"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>
