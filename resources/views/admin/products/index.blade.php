@extends('admin.layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Product Management</h1>
        <p class="text-gray-600 text-base">Manage your fashion store's product catalog</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $products->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Products</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $products->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Categories</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $categories->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-tags text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Actions</p>
                    <button onclick="ProductModal.openAdd()" 
                            class="btn-primary mt-2 px-4 py-2 rounded-lg text-sm font-medium">
                        Add Product
                    </button>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                    <i class="fas fa-plus text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Import CSV Card -->
    <div class="card mb-8" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-file-import mr-2 text-blue-600"></i> Import Products (CSV)
            </h2>
        </div>
        
        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Upload CSV File</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-gray-300">
                        <input type="file" name="csv_file" accept=".csv" required class="w-full text-gray-900">
                        <p class="text-gray-500 text-sm mt-2">Select CSV file with product data</p>
                    </div>
                </div>
                
                <div>
                    <button type="submit"
                        class="btn-primary w-full px-6 py-3 rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-upload mr-2"></i> Import Products
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Product Table -->
    <div class="card overflow-hidden" data-aos="fade-up" data-aos-delay="350">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">All Products</h2>
            <div class="relative">
                <input type="text" placeholder="Search products..." 
                       class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 pl-10 focus:ring-2 focus:ring-blue-500 text-sm">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">#</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Image</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Product</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Category</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Price Range</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Stock</th>
                        <th class="py-4 px-6 text-center text-gray-900 font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-right text-gray-900 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $index => $product)
                        <tr class="hover:bg-gray-50 transition-colors" data-aos="fade-in" data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6 text-gray-600 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-gray-600 text-sm mt-1 line-clamp-1">{{ Str::limit($product->description, 50) }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($product->sizes->count() > 0)
                                    <div class="text-sm">
                                        <span class="text-gray-900 font-medium">₹{{ number_format($product->sizes->min('price'), 2) }}</span>
                                        <span class="text-gray-500 mx-1">-</span>
                                        <span class="text-gray-900 font-medium">₹{{ number_format($product->sizes->max('price'), 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">No prices</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($product->sizes->count() > 0)
                                    <div class="text-sm">
                                        <span class="text-gray-900 font-medium">{{ $product->sizes->sum('stock') }}</span>
                                        <span class="text-gray-500 text-xs ml-1">in stock</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">No stock</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-2 h-2 rounded-full {{ $product->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></span>
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="ProductModal.openEdit({{ $product->id }})"
                                        class="btn-primary px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                                        <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                    </button>
                                    <button onclick="DeleteModal.open('product', {{ $product->id }}, '{{ $product->name }}')"
                                        class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                                        <i class="fas fa-trash mr-1 text-xs"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">No Products Found</h3>
                                <p class="text-gray-600 mb-4">Get started by adding your first product</p>
                                <button onclick="ProductModal.openAdd()" 
                                        class="btn-primary px-6 py-3 rounded-lg font-medium">
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
    <div id="addProductModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
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
                        <label class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter product name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                        <select name="category_id" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                </div>

                <!-- Size, Price & Stock -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">Sizes, Prices & Stock</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (['S' => 'Small (S)', 'M' => 'Medium (M)', 'L' => 'Large (L)', 'XL' => 'Extra Large (XL)'] as $code => $label)
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl">
                                <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $code }}">
                                <label class="block text-gray-900 font-medium mb-2">{{ $label }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="number" step="0.01" name="sizes[{{ $loop->index }}][price]" required
                                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                        placeholder="Price" value="{{ old("sizes.$loop->index.price") }}">
                                    <input type="number" name="sizes[{{ $loop->index }}][stock]" min="0" required
                                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                        placeholder="Stock" value="{{ old("sizes.$loop->index.stock") }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                    <textarea name="description" rows="4" required 
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Enter product description">{{ old('description') }}</textarea>
                </div>

                <!-- Multiple Images -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">Product Images</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Main Image *</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-gray-300">
                                <input type="file" name="image" accept="image/*" required class="w-full text-gray-900">
                                <p class="text-gray-500 text-sm mt-2">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Additional Images</label>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Image 2</label>
                                    <input type="file" name="image_2" accept="image/*" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Image 3</label>
                                    <input type="file" name="image_3" accept="image/*" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Image 4</label>
                                    <input type="file" name="image_4" accept="image/*" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                        <select name="status" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="ProductModal.closeAdd()"
                        class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl transform transition-all duration-300 scale-95 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-blue-600"></i> Edit Product
                </h2>
                <button onclick="ProductModal.closeEdit()"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 text-xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                    &times;
                </button>
            </div>

            <form id="editProductForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="product_id" id="editProductId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Product Name</label>
                        <input type="text" name="name" id="editName" required
                            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Category</label>
                        <select name="category_id" id="editCategoryId" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Size-based Pricing -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">Sizes, Prices & Stock</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (['S' => 'Small (S)', 'M' => 'Medium (M)', 'L' => 'Large (L)', 'XL' => 'Extra Large (XL)'] as $code => $label)
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl">
                                <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $code }}">
                                <label class="block text-gray-900 font-medium mb-2">{{ $label }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                            name="sizes[{{ $loop->index }}][price]" id="editSize_{{ $code }}_price" required>
                                    </div>
                                    <div>
                                        <input type="number" min="0" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                                            name="sizes[{{ $loop->index }}][stock]" id="editSize_{{ $code }}_stock" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                    <textarea id="editDescription" name="description" rows="4" required 
                        class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors resize-none"></textarea>
                </div>

                <!-- Multiple Images Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">Product Images</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Main Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Current Main Image</label>
                            <div class="flex items-center space-x-4 mb-4">
                                <img id="editCurrentImage" src="" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <div class="flex-1">
                                    <label class="block text-sm text-gray-600 mb-1">Change Main Image</label>
                                    <input type="file" name="image" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                    <p class="text-gray-500 text-xs mt-1">Leave empty to keep current image</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Additional Images</label>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center space-x-4 mb-2">
                                        <img id="editCurrentImage2" src="" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                        <div class="flex-1">
                                            <label class="block text-sm text-gray-600 mb-1">Image 2</label>
                                            <input type="file" name="image_2" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                        </div>
                                    </div>
                                    <p class="text-gray-500 text-xs">Leave empty to keep current image</p>
                                </div>
                                
                                <div>
                                    <div class="flex items-center space-x-4 mb-2">
                                        <img id="editCurrentImage3" src="" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                        <div class="flex-1">
                                            <label class="block text-sm text-gray-600 mb-1">Image 3</label>
                                            <input type="file" name="image_3" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                        </div>
                                    </div>
                                    <p class="text-gray-500 text-xs">Leave empty to keep current image</p>
                                </div>
                                
                                <div>
                                    <div class="flex items-center space-x-4 mb-2">
                                        <img id="editCurrentImage4" src="" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                        <div class="flex-1">
                                            <label class="block text-sm text-gray-600 mb-1">Image 4</label>
                                            <input type="file" name="image_4" class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                                        </div>
                                    </div>
                                    <p class="text-gray-500 text-xs">Leave empty to keep current image</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                        <select name="status" id="editStatus" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="ProductModal.closeEdit()"
                        class="px-6 py-3 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-blue-600 text-white hover:bg-blue-700 px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">Confirm Deletion</h3>
                <p class="text-gray-600 mb-6" id="deleteModalText">Are you sure you want to delete this item?</p>
                
                <form id="deleteForm" method="POST" class="flex justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="DeleteModal.close()"
                        class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-white hover:bg-red-700 px-6 py-3 rounded-lg font-medium flex items-center">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const ProductModal = {
            openAdd: function() {
                document.getElementById('addProductModal').classList.remove('hidden');
            },
            closeAdd: function() {
                document.getElementById('addProductModal').classList.add('hidden');
            },
            openEdit: async function(productId) {
                try {
                    // Show loading state
                    const modal = document.getElementById('editProductModal');
                    modal.classList.remove('hidden');
                    
                    // Fetch product data
                    const response = await fetch(`/admin/products/${productId}/edit`);
                    const product = await response.json();
                    
                    if (response.ok) {
                        // Populate form fields
                        document.getElementById('editProductId').value = product.id;
                        document.getElementById('editName').value = product.name;
                        document.getElementById('editCategoryId').value = product.category_id;
                        document.getElementById('editDescription').value = product.description;
                        document.getElementById('editStatus').value = product.status;
                        
                        // Set current images with fallback for missing images
                        const setImage = (elementId, imagePath) => {
                            const element = document.getElementById(elementId);
                            if (element && imagePath) {
                                element.src = `/storage/${imagePath}`;
                                element.style.display = 'block';
                            } else if (element) {
                                element.style.display = 'none';
                            }
                        };
                        
                        setImage('editCurrentImage', product.image);
                        setImage('editCurrentImage2', product.image_2);
                        setImage('editCurrentImage3', product.image_3);
                        setImage('editCurrentImage4', product.image_4);
                        
                        // Initialize all size fields to 0 first
                        ['S', 'M', 'L', 'XL'].forEach(size => {
                            document.getElementById(`editSize_${size}_price`).value = 0;
                            document.getElementById(`editSize_${size}_stock`).value = 0;
                        });
                        
                        // Populate sizes from product data
                        if (product.sizes && Array.isArray(product.sizes)) {
                            product.sizes.forEach(size => {
                                const priceInput = document.getElementById(`editSize_${size.size}_price`);
                                const stockInput = document.getElementById(`editSize_${size.size}_stock`);
                                
                                if (priceInput) priceInput.value = size.price || 0;
                                if (stockInput) stockInput.value = size.stock || 0;
                            });
                        }
                        
                        // Set form action
                        document.getElementById('editProductForm').action = `/admin/products/${product.id}`;
                        
                        // Show modal with animation
                        setTimeout(() => {
                            modal.querySelector('div').classList.remove('scale-95');
                            modal.querySelector('div').classList.add('scale-100');
                        }, 10);
                    } else {
                        throw new Error('Failed to load product data');
                    }
                } catch (error) {
                    console.error('Error loading product:', error);
                    alert('Failed to load product data. Please try again.');
                    this.closeEdit();
                }
            },
            closeEdit: function() {
                const modal = document.getElementById('editProductModal');
                modal.querySelector('div').classList.remove('scale-100');
                modal.querySelector('div').classList.add('scale-95');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    // Reset form
                    document.getElementById('editProductForm').reset();
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
            },
            close: function() {
                document.getElementById('deleteModal').classList.add('hidden');
            }
        };

        document.addEventListener('click', function(e) {
            if (e.target.id === 'addProductModal') ProductModal.closeAdd();
            if (e.target.id === 'editProductModal') ProductModal.closeEdit();
            if (e.target.id === 'deleteModal') DeleteModal.close();
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .scale-95 {
            transform: scale(0.95);
        }
        
        .scale-100 {
            transform: scale(1);
        }
        
        img[src=""] {
            display: none;
        }
    </style>
@endsection