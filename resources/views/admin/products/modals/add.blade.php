<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
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
                    <label class="block text-sm font-medium text-gray-900 mb-2">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter product name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">SKU (Leave empty to auto-generate)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g., PROD-ABC123">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Category *</label>
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
                    <label class="block text-sm font-medium text-gray-900 mb-2">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter brand name">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">Short Description</label>
                <textarea name="short_description" rows="2"
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Brief description (max 500 chars)">{{ old('short_description') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Max 500 characters</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">Full Description *</label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Enter detailed product description">{{ old('description') }}</textarea>
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
                                <label class="block text-sm font-medium text-gray-900 mb-1">Size *</label>
                                <select name="variants[0][size]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Select Size</option>
                                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'] as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Color *</label>
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
                                <label class="block text-sm font-medium text-gray-900 mb-1">Price (₹) *</label>
                                <input type="number" step="0.01" name="variants[0][price]" required
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Sale Price (₹)</label>
                                <input type="number" step="0.01" name="variants[0][sale_price]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="Optional">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Stock *</label>
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Cost Price (₹)</label>
                                <input type="number" step="0.01" name="variants[0][cost_price]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Weight (kg)</label>
                                <input type="number" step="0.01" name="variants[0][weight]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-1">Dimensions</label>
                                <input type="text" name="variants[0][dimensions]"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                    placeholder="e.g., 10x20x5">
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
                        <label class="block text-sm font-medium text-gray-900 mb-2">Primary Image *</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-gray-300">
                            <input type="file" name="images[0][image]" accept="image/*" required
                                class="w-full text-gray-900">
                            <input type="hidden" name="images[0][is_primary]" value="1">
                            <p class="text-gray-500 text-sm mt-2">PNG, JPG up to 2MB</p>
                        </div>
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-900">Additional Images</label>
                            <button type="button" onclick="addImageRow()"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                                <i class="fas fa-plus mr-1"></i> Add Image
                            </button>
                        </div>

                        <div id="additionalImagesContainer" class="space-y-4">
                            <!-- Additional image rows will be added here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Status *</label>
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
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}
                                class="rounded border-gray-300">
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