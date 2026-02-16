<!-- Edit Product Modal -->
<div id="editProductModal"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-5xl rounded-xl shadow-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 text-blue-600"></i> {{ __('admin.products.modal.edit_title') }}
            </h2>
            <button onclick="ProductModal.closeEdit()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                &times;
            </button>
        </div>

        <form id="editProductForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="product_id" id="editProductId">
            <input type="hidden" name="deleted_images_json" id="deletedImagesInput">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.product_name') }}<span class="ml-1 text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="editName" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3"
                        placeholder="{{ __('admin.products.modal.name_placeholder') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.category') }} <span class="ml-1 text-red-500">*</span>
                    </label>
                    <select name="category_id" id="editCategoryId" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('admin.products.modal.category_placeholder') }}</option>

                        @php
                            $groupedCategories = $categories->groupBy('gender');
                            $selectedCategoryId = old('category_id', isset($product) ? $product->category_id : '');
                        @endphp

                        @foreach (['men' => __('admin.products.category_groups.men'), 'women' => __('admin.products.category_groups.women'), 'kids' => __('admin.products.category_groups.kids'), 'unisex' => __('admin.products.category_groups.unisex')] as $gender => $label)
                            @if ($groupedCategories->has($gender))
                                <optgroup label="{{ $label }}">
                                    @foreach ($groupedCategories[$gender] as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach

                        @foreach ($categories->whereNull('gender') as $cat)
                            <option value="{{ $cat->id }}" {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>
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
                    <input type="text" name="material" id="editMaterial"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3"
                        placeholder="{{ __('admin.products.modal.material_placeholder') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.brand') }}
                    </label>
                    <input type="text" name="brand" id="editBrand"
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3"
                        placeholder="{{ __('admin.products.modal.brand_placeholder') }}">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    {{ __('admin.products.modal.description') }} <span class="ml-1 text-red-500">*</span>
                </label>
                <textarea name="description" id="editDescription" rows="4" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 resize-none"
                    placeholder="{{ __('admin.products.modal.description_placeholder') }}"></textarea>
            </div>

            <!-- Product-Level Discount Section for Edit -->
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
                            <select name="discount_type" id="editDiscountType"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 discount-type-select">
                                <option value="">{{ __('admin.products.modal.discount_type_none') }}</option>
                                <option value="percentage">{{ __('admin.products.modal.discount_type_percentage') }}</option>
                                <option value="fixed">{{ __('admin.products.modal.discount_type_fixed') }}</option>
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
                                <input type="number" step="0.01" name="discount_value" id="editDiscountValue"
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
                            <input type="datetime-local" name="discount_start" id="editDiscountStart"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                {{ __('admin.products.modal.discount_end') }}
                            </label>
                            <input type="datetime-local" name="discount_end" id="editDiscountEnd"
                                class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Variants Section for Edit -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-lg font-semibold text-gray-900">
                        {{ __('admin.products.modal.variants') }}
                    </label>
                    <button type="button" onclick="addEditVariantRow()"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        <i class="fas fa-plus mr-1"></i> {{ __('admin.products.modal.add_variant') }}
                    </button>
                </div>

                <div id="editVariantsContainer" class="space-y-4">
                    <!-- Variant rows will be populated dynamically -->
                </div>
            </div>

            <!-- Images Section for Edit -->
            <div class="mb-6">
                <label class="block text-lg font-semibold text-gray-900 mb-4">
                    {{ __('admin.products.modal.product_images') }}
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            {{ __('admin.products.modal.current_images') }}
                        </label>
                        <div id="currentImages" class="space-y-4">
                            <!-- Images will be populated here -->
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-900">
                                {{ __('admin.products.modal.add_new_images') }}
                            </label>
                            <button type="button" onclick="addNewImageRow()"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                                <i class="fas fa-plus mr-1"></i> {{ __('admin.products.modal.add_image') }}
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
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ __('admin.products.modal.status') }} <span class="ml-1 text-red-500">*</span>
                    </label>
                    <select name="status" id="editStatus" required
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3">
                        <option value="active">{{ __('admin.products.modal.status_active') }}</option>
                        <option value="inactive">{{ __('admin.products.modal.status_inactive') }}</option>
                        <option value="draft">{{ __('admin.products.modal.status_draft') }}</option>
                    </select>
                </div>

                <div class="flex items-center">
                    <div class="mr-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_featured" id="editIsFeatured" value="1"
                                class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">{{ __('admin.products.modal.featured_product') }}</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_new" id="editIsNew" value="1"
                                class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">{{ __('admin.products.modal.new_arrival') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="ProductModal.closeEdit()"
                    class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                    {{ __('admin.products.modal.cancel') }}
                </button>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.products.modal.update') }}
                </button>
            </div>
        </form>
    </div>
</div>