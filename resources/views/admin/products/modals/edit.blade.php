<!-- Edit Product Modal -->
<div id="editProductModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-5xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 text-blue-600"></i> Edit Product
            </h2>
            <button onclick="ProductModal.closeEdit()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                &times;
            </button>
        </div>

        <form id="editProductForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="product_id" id="editProductId">
            <!-- Hidden field for deleted images -->
            <input type="hidden" name="deleted_images_json" id="deletedImagesInput">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Product Name   <span class="ml-1 text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Category   <span class="ml-1 text-red-500">*</span></label>
                    <select name="category_id" id="editCategoryId" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Brand</label>
                    <input type="text" name="brand" id="editBrand"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">Description   <span class="ml-1 text-red-500">*</span></label>
                <textarea name="description" id="editDescription" rows="4" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 resize-none"></textarea>
            </div>

            <!-- Product-Level Discount Section for Edit -->
            <div class="mb-8 border border-gray-200 rounded-lg p-6 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tag mr-2 text-blue-600"></i> Product Discount Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount Type</label>
                            <select name="discount_type" id="editDiscountType"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 discount-type-select">
                                <option value="">No Discount</option>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <div class="discount-value-field" style="display: none;">
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount Value</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 discount-prefix">%</div>
                                <input type="number" step="0.01" name="discount_value" id="editDiscountValue"
                                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 10">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount Start Date</label>
                            <input type="datetime-local" name="discount_start" id="editDiscountStart"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Discount End Date</label>
                            <input type="datetime-local" name="discount_end" id="editDiscountEnd"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variants Section for Edit -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-lg font-semibold text-gray-900">Product Variants</label>
                    <button type="button" onclick="addEditVariantRow()"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        <i class="fas fa-plus mr-1"></i> Add Variant
                    </button>
                </div>

                <div id="editVariantsContainer" class="space-y-4">
                    <!-- Variant rows will be populated dynamically -->
                </div>
            </div>

            <!-- Images Section for Edit -->
            <div class="mb-6">
                <label class="block text-lg font-semibold text-gray-900 mb-4">Product Images</label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Current Images</label>
                        <div id="currentImages" class="space-y-4">
                            <!-- Images will be populated here -->
                        </div>
                    </div>

                    <!-- New Images -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-900">Add New Images</label>
                            <button type="button" onclick="addNewImageRow()"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                                <i class="fas fa-plus mr-1"></i> Add Image
                            </button>
                        </div>

                        <div id="newImagesContainer" class="space-y-4">
                            <!-- New image rows will be added here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Status   <span class="ml-1 text-red-500">*</span></label>
                    <select name="status" id="editStatus" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div class="flex items-center">
                    <div class="mr-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_featured" id="editIsFeatured" value="1"
                                class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" id="editIsNew" value="1"
                                class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">New Arrival</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="ProductModal.closeEdit()"
                    class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                    Cancel
                </button>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
            </div>
        </form>
    </div>
</div>