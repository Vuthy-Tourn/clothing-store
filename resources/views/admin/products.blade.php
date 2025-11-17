@extends('admin.layout')

@section('content')
    <!-- Page Header -->
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-white mb-2  ">Product Management</h1>
        <p class="text-white text-lg">Manage your fashion store's product catalog</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-Ocean mt-1">{{ $products->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-Ocean/10 flex items-center justify-center">
                    <i class="fas fa-box text-Ocean text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Active Products</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $products->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Categories</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $categories->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-tags text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-Pearl border border-Silk p-6 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-Wave text-sm font-medium">Actions</p>
                    <button onclick="ProductModal.openAdd()" 
                            class="bg-Ocean text-Pearl hover:bg-Ocean/90 mt-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Add Product
                    </button>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-plus text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Import CSV Card -->
    <div class="bg-Pearl border border-Silk rounded-xl shadow-sm mb-8" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-Silk">
            <h2 class="text-xl font-bold text-Ocean   flex items-center">
                <i class="fas fa-file-import mr-2 text-Ocean"></i> Import Products (CSV)
            </h2>
        </div>
        
        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-Ocean mb-2">Upload CSV File</label>
                    <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center transition-colors hover:border-Ocean/50">
                        <input type="file" name="csv_file" accept=".csv" required class="w-full text-Ocean">
                        <p class="text-Wave text-sm mt-2">Select CSV file with product data</p>
                    </div>
                </div>
                
                <div>
                    <button type="submit"
                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 w-full px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-upload mr-2"></i> Import Products
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Product Table -->
    <div class="bg-Pearl border border-Silk rounded-xl shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="350">
        <div class="p-6 border-b border-Silk flex items-center justify-between">
            <h2 class="text-xl font-bold text-Ocean  ">All Products</h2>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" placeholder="Search products..." 
                           class="border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-Ocean text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-Wave text-sm"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-Lace border-b border-Silk">
                    <tr>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">#</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Image</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Product</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Category</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Price Range</th>
                        <th class="py-4 px-6 text-left text-Ocean font-semibold text-sm">Stock</th>
                        <th class="py-4 px-6 text-center text-Ocean font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-right text-Ocean font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-Silk">
                    @forelse($products as $index => $product)
                        <tr class="hover:bg-Lace/50 transition-colors" data-aos="fade-in" data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6 text-Wave font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-12 h-12 object-cover rounded-lg border border-Silk">
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-semibold text-Ocean">{{ $product->name }}</p>
                                    <p class="text-Wave text-sm mt-1 line-clamp-1">{{ Str::limit($product->description, 50) }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-Ocean/10 text-Ocean px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($product->sizes->count() > 0)
                                    <div class="text-sm">
                                        <span class="text-Ocean font-medium">₹{{ number_format($product->sizes->min('price'), 2) }}</span>
                                        <span class="text-Wave mx-1">-</span>
                                        <span class="text-Ocean font-medium">₹{{ number_format($product->sizes->max('price'), 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-Wave text-sm">No prices</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($product->sizes->count() > 0)
                                    <div class="text-sm">
                                        <span class="text-Ocean font-medium">{{ $product->sizes->sum('stock') }}</span>
                                        <span class="text-Wave text-xs ml-1">in stock</span>
                                    </div>
                                @else
                                    <span class="text-Wave text-sm">No stock</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-2 h-2 rounded-full {{ $product->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></span>
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="ProductModal.openEdit(this)" data-product='@json($product)'
                                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-3 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                                        <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                    </button>
                                    <button onclick="DeleteModal.open('product', {{ $product->id }}, '{{ $product->name }}')"
                                        class="bg-Silk text-Ocean hover:bg-Silk/80 px-3 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                                        <i class="fas fa-trash mr-1 text-xs"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="w-24 h-24 rounded-full bg-Lace flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-box-open text-Ocean text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-Ocean mb-2">No Products Found</h3>
                                <p class="text-Wave mb-4">Get started by adding your first product</p>
                                <button onclick="ProductModal.openAdd()" 
                                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-6 py-3 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Add First Product
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300">
        <div class="bg-Pearl w-full max-w-4xl rounded-xl shadow-2xl border border-Silk transform transition-all duration-300 scale-95 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-Silk sticky top-0 bg-Pearl z-10">
                <h2 class="text-xl font-bold text-Ocean   flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-Ocean"></i> Add New Product
                </h2>
                <button onclick="ProductModal.closeAdd()"
                    class="text-Wave hover:text-Ocean transition-colors duration-200 text-xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-Lace">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors"
                            placeholder="Enter product name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Category</label>
                        <select name="category_id" required class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                            <option value="">Select category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Size, Price & Stock -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-Ocean mb-3">Sizes, Prices & Stock</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (['S' => 'Small (S)', 'M' => 'Medium (M)', 'L' => 'Large (L)', 'XL' => 'Extra Large (XL)'] as $code => $label)
                            <div class="bg-Lace border border-Silk p-4 rounded-xl hover-lift">
                                <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $code }}">
                                <label class="block text-Ocean font-medium mb-2">{{ $label }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" name="sizes[{{ $loop->index }}][price]" required
                                            class="w-full border border-Silk bg-Pearl text-Ocean rounded-lg px-3 py-2 text-sm placeholder-Wave"
                                            placeholder="Price" value="{{ old("sizes.$loop->index.price") }}">
                                    </div>
                                    <div>
                                        <input type="number" name="sizes[{{ $loop->index }}][stock]" min="0" required
                                            class="w-full border border-Silk bg-Pearl text-Ocean rounded-lg px-3 py-2 text-sm placeholder-Wave"
                                            placeholder="Stock" value="{{ old("sizes.$loop->index.stock") }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('sizes')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-Ocean mb-2">Description</label>
                    <textarea name="description" rows="4" required 
                        class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors resize-none"
                        placeholder="Enter product description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Image</label>
                        <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center transition-colors hover:border-Ocean/50">
                            <input type="file" name="image" accept="image/*" required class="w-full text-Ocean">
                            <p class="text-Wave text-sm mt-2">PNG, JPG up to 5MB</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Status</label>
                        <select name="status" required class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-Silk">
                    <button type="button" onclick="ProductModal.closeAdd()"
                        class="px-6 py-3 bg-Silk text-Ocean hover:bg-Silk/80 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300">
        <div class="bg-Pearl w-full max-w-4xl rounded-xl shadow-2xl border border-Silk transform transition-all duration-300 scale-95 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-Silk sticky top-0 bg-Pearl z-10">
                <h2 class="text-xl font-bold text-Ocean   flex items-center">
                    <i class="fas fa-edit mr-2 text-Ocean"></i> Edit Product
                </h2>
                <button onclick="ProductModal.closeEdit()"
                    class="text-Wave hover:text-Ocean transition-colors duration-200 text-xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-Lace">
                    &times;
                </button>
            </div>

            <form id="editProductForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="product_id" id="editProductId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Name</label>
                        <input type="text" name="name" id="editName" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Category</label>
                        <select name="category_id" id="editCategoryId" required class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Size-based Pricing -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-Ocean mb-3">Sizes, Prices & Stock</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (['S' => 'Small (S)', 'M' => 'Medium (M)', 'L' => 'Large (L)', 'XL' => 'Extra Large (XL)'] as $code => $label)
                            <div class="bg-Lace border border-Silk p-4 rounded-xl hover-lift">
                                <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $code }}">
                                <label class="block text-Ocean font-medium mb-2">{{ $label }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" class="w-full border border-Silk bg-Pearl text-Ocean rounded-lg px-3 py-2 text-sm"
                                            name="sizes[{{ $loop->index }}][price]" id="editSize_{{ $code }}_price" required>
                                    </div>
                                    <div>
                                        <input type="number" min="0" class="w-full border border-Silk bg-Pearl text-Ocean rounded-lg px-3 py-2 text-sm"
                                            name="sizes[{{ $loop->index }}][stock]" id="editSize_{{ $code }}_stock" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-Ocean mb-2">Description</label>
                    <textarea id="editDescription" name="description" rows="4" required 
                        class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Current Image</label>
                        <img id="editCurrentImage" src="" class="w-20 h-20 object-cover rounded-lg border border-Silk mb-3">
                        <label class="block text-sm font-medium text-Ocean mb-2">Change Image</label>
                        <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center transition-colors hover:border-Ocean/50">
                            <input type="file" name="image" class="w-full text-Ocean">
                            <p class="text-Wave text-sm mt-2">Leave empty to keep current image</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Status</label>
                        <select name="status" id="editStatus" required class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-Silk">
                    <button type="button" onclick="ProductModal.closeEdit()"
                        class="px-6 py-3 bg-Silk text-Ocean hover:bg-Silk/80 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300">
        <div class="bg-Pearl w-full max-w-md rounded-xl shadow-2xl border border-Silk transform transition-all duration-300 scale-95">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-Ocean mb-2">Confirm Deletion</h3>
                <p class="text-Wave mb-6" id="deleteModalText">Are you sure you want to delete this item?</p>
                
                <form id="deleteForm" method="POST" class="flex justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="DeleteModal.close()"
                        class="px-6 py-3 bg-Silk text-Ocean hover:bg-Silk/80 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-white hover:bg-red-700 px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const ProductModal = {
            openAdd: function() {
                const modal = document.getElementById('addProductModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.scale-95').classList.remove('scale-95');
                }, 50);
            },
            closeAdd: function() {
                const modal = document.getElementById('addProductModal');
                modal.querySelector('.scale-95').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            },
            openEdit: function(button) {
                const productData = JSON.parse(button.getAttribute('data-product'));
                
                // Fill form data
                document.getElementById('editProductId').value = productData.id;
                document.getElementById('editName').value = productData.name;
                document.getElementById('editCategoryId').value = productData.category_id;
                document.getElementById('editDescription').value = productData.description;
                document.getElementById('editStatus').value = productData.status;
                
                // Set current image preview
                if (productData.image) {
                    document.getElementById('editCurrentImage').src = "{{ asset('storage/') }}/" + productData.image;
                }
                
                // Set size prices and stock
                if (productData.sizes) {
                    productData.sizes.forEach(size => {
                        const priceInput = document.getElementById(`editSize_${size.size}_price`);
                        const stockInput = document.getElementById(`editSize_${size.size}_stock`);
                        if (priceInput) priceInput.value = size.price;
                        if (stockInput) stockInput.value = size.stock;
                    });
                }
                
                // Set form action
                document.getElementById('editProductForm').action = `/admin/products/${productData.id}`;
                
                const modal = document.getElementById('editProductModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.scale-95').classList.remove('scale-95');
                }, 50);
            },
            closeEdit: function() {
                const modal = document.getElementById('editProductModal');
                modal.querySelector('.scale-95').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        };

        const DeleteModal = {
            open: function(type, id, name) {
                const modal = document.getElementById('deleteModal');
                const form = document.getElementById('deleteForm');
                const text = document.getElementById('deleteModalText');
                
                if (type === 'product') {
                    form.action = `/admin/products/${id}`;
                    text.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
                }
                
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.scale-95').classList.remove('scale-95');
                }, 50);
            },
            close: function() {
                const modal = document.getElementById('deleteModal');
                modal.querySelector('.scale-95').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        };

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.id === 'addProductModal') ProductModal.closeAdd();
            if (e.target.id === 'editProductModal') ProductModal.closeEdit();
            if (e.target.id === 'deleteModal') DeleteModal.close();
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ProductModal.closeAdd();
                ProductModal.closeEdit();
                DeleteModal.close();
            }
        });
    </script>

    <style>
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection