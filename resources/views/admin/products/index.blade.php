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
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $products->where('status', 'active')->count() }}
                    </p>
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

    <!-- Product Table -->
    <div class="card overflow-hidden" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">All Products</h2>
            <div class="flex items-center gap-4">
                <!-- Simple Search Input -->
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search products..."
                        class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 pl-10 pr-10 focus:ring-2 focus:ring-blue-500 text-sm w-64"
                        value="{{ request('search') ?? '' }}">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>

                    <!-- Loading Spinner -->
                    <div id="searchLoading" class="absolute right-3 top-3 hidden">
                        <i class="fas fa-spinner fa-spin text-blue-500"></i>
                    </div>

                    <!-- Clear Button (only when there's text) -->
                    @if (request('search'))
                        <button id="clearSearchBtn" type="button" onclick="clearSearch()"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">ID</th>
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
                        <tr class="hover:bg-gray-50 transition-colors" data-aos="fade-in"
                            data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6 text-gray-600 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                @if ($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                @elseif($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                @else
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-gray-600 text-sm mt-1 line-clamp-1">
                                        {{ Str::limit($product->short_description ?: $product->description, 50) }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if ($product->variants->count() > 0)
                                    <div class="text-sm">
                                        <span
                                            class="text-gray-900 font-medium">${{ number_format($product->min_price, 2) }}</span>
                                        <span class="text-gray-500 mx-1">-</span>
                                        <span
                                            class="text-gray-900 font-medium">${{ number_format($product->max_price, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">No variants</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if ($product->variants->count() > 0)
                                    <div class="text-sm">
                                        <span class="text-gray-900 font-medium">{{ $product->total_stock }}</span>
                                        <span class="text-gray-500 text-xs ml-1">in stock</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $product->variants->count() }} variant(s)
                                    </div>
                                @else
                                    <span class="text-gray-500 text-sm">No stock</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-50 text-green-700' : ($product->status === 'inactive' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-50 text-yellow-700') }}">
                                    <span
                                        class="w-2 h-2 rounded-full {{ $product->status === 'active' ? 'bg-green-500' : ($product->status === 'inactive' ? 'bg-gray-500' : 'bg-yellow-500') }} mr-2"></span>
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="ProductModal.openEdit({{ $product->id }})"
                                        class="btn-primary px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                                        <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                    </button>
                                    <button
                                        onclick="DeleteModal.open('product', {{ $product->id }}, '{{ $product->name }}')"
                                        class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                                        <i class="fas fa-trash mr-1 text-xs"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center">
                                <div
                                    class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
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

    <!-- Include Modal Components -->
    @include('admin.products.modals.add')
    @include('admin.products.modals.edit')
    @include('admin.products.modals.delete')

    <!-- Include JavaScript -->
    @include('admin.products.scripts')

    <style>
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        /* For deleted images */
        .image-deleted {
            opacity: 0.5;
            background-color: #fef2f2;
        }

        .image-deleted img {
            filter: grayscale(100%);
        }

        /* Smooth transitions */
        .variant-row,
        .border {
            transition: all 0.3s ease;
        }

        /* Image preview styling */
        img.object-cover {
            object-fit: cover;
        }

        /* SweetAlert2 custom styling */
        .swal2-popup {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
    </style>
@endsection
