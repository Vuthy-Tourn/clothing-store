@extends('admin.layouts.app')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Categories</h1>
                <p class="text-gray-700">Organize your products with categories and subcategories</p>
            </div>
            <button onclick="CategoryModal.openAdd()"
                class="mt-4 md:mt-0 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 group">
                <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>Add New Category
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium mb-1">Total Categories</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $categories->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                    <i class="fas fa-folder text-white text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium mb-1">Active Categories</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $categories->where('status', 'active')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium mb-1">Inactive Categories</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $categories->where('status', 'inactive')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                    <i class="fas fa-eye-slash text-white text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 text-sm font-medium mb-1">Main Categories</p>
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $categories->whereNull('parent_id')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                    <i class="fas fa-layer-group text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="150">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-900">All Categories</h2>
                <p class="text-gray-700 text-sm mt-1">Manage and organize your product categories</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleSortOrder()"
                    class="bg-white border border-gray-300 text-gray-900 hover:bg-gray-50 px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 group">
                    <i class="fas fa-sort mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                    Arrange Order
                </button>
            </div>
        </div>

        @if($categories->isEmpty())
            <!-- Empty State -->
            <div class="p-16 text-center">
                <div class="w-24 h-24 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-6 border-4 border-gray-100">
                    <i class="fas fa-folder-open text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Categories Yet</h3>
                <p class="text-gray-700 mb-6 max-w-md mx-auto">Organize your products by creating categories and subcategories for better navigation</p>
                <button onclick="CategoryModal.openAdd()"
                    class="bg-gray-900 hover:bg-gray-800 text-white px-8 py-3.5 rounded-xl font-medium transition-all duration-200 group text-lg">
                    <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                    Create First Category
                </button>
            </div>
        @else
            <!-- Sort Order Panel (Initially Hidden) -->
            <div id="sortOrderPanel" class="p-6 border-b border-gray-100 hidden">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Arrange Category Order</h3>
                        <p class="text-gray-700 text-sm">Drag and drop to reorder categories</p>
                    </div>
                    <button onclick="saveSortOrder()"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-lg font-medium text-sm transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> Save Order
                    </button>
                </div>
                
                <div id="sortableList" class="space-y-2">
                    @foreach($categories->sortBy('sort_order') as $category)
                    <div class="flex items-center gap-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl p-4 transition-all duration-200 cursor-move sortable-item"
                         data-id="{{ $category->id }}">
                        <div class="w-10 h-10 rounded-lg bg-gray-900 flex items-center justify-center">
                            <i class="fas fa-arrows-alt text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-folder text-gray-700"></i>
                                </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                    @if($category->parent)
                                    <p class="text-gray-700 text-xs">Subcategory of: {{ $category->parent->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-gray-700 font-medium">
                            <span class="text-sm">Position: #{{ $category->sort_order + 1 }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Categories Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Order</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Category</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Slug</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Description</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Status</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Parent</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Products</th>
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories->sortBy('sort_order') as $index => $category)
                        <tr class="hover:bg-gray-50 transition-colors duration-200" data-aos="fade-in" data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-900 text-white text-sm font-bold">
                                    {{ $category->sort_order + 1 }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" 
                                         class="w-12 h-12 rounded-xl object-cover border border-gray-200">
                                    @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-folder text-gray-700 text-lg"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $category->name }}</h4>
                                        <p class="text-gray-700 text-xs mt-1">
                                            {{ $category->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <code class="text-gray-700 text-sm bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-gray-700 text-sm line-clamp-2">{{ $category->description ?? 'No description' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $category->status === 'active' ? 'bg-gray-900 text-white' : 'bg-gray-700 text-gray-300' }}">
                                    <i class="fas fa-circle text-[6px] mr-1.5 {{ $category->status === 'active' ? 'animate-pulse' : '' }}"></i>
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($category->parent)
                                <span class="text-gray-700 text-sm bg-gray-100 px-3 py-1.5 rounded-lg">
                                    {{ $category->parent->name }}
                                </span>
                                @else
                                <span class="text-gray-500 text-sm italic">Main Category</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-gray-900 font-bold">{{ $category->products_count ?? 0 }}</span>
                                <span class="text-gray-700 text-sm ml-1">products</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button onclick="CategoryModal.openEdit({{ $category->id }})"
                                        class="w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-900 hover:text-white text-gray-700 flex items-center justify-center transition-all duration-200 group/edit">
                                        <i class="fas fa-edit text-sm group-hover/edit:rotate-12 transition-transform duration-300"></i>
                                    </button>
                                    <button onclick="confirmDeleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                        class="w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-900 hover:text-white text-gray-700 flex items-center justify-center transition-all duration-200 group/delete">
                                        <i class="fas fa-trash text-sm group-hover/delete:shake transition-transform duration-300"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Create New Category</h2>
                        <p class="text-gray-700">Add a new category to organize your products</p>
                    </div>
                </div>
                <button onclick="CategoryModal.closeAdd()" 
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                    <i class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-gray-700"></i>
                                    Category Name *
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                    placeholder="e.g., Men's Clothing">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-sort-numeric-up mr-2 text-gray-700"></i>
                                    Display Order
                                </label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $categories->count()) }}" min="0"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                    placeholder="Position in display sequence">
                                <p class="text-gray-700 text-xs mt-2">Lower numbers appear first. Leave empty for last position.</p>
                                @error('sort_order')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-gray-700"></i>
                                    Description
                                </label>
                                <textarea name="description" rows="4"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 resize-none transition-all duration-200"
                                    placeholder="Brief description of this category...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-layer-group mr-2 text-gray-700"></i>
                                    Parent Category
                                </label>
                                <select name="parent_id"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                                    <option value="">Select Parent Category (Optional)</option>
                                    @foreach($categories->whereNull('parent_id') as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-gray-700 text-xs mt-2">Leave empty to create a main category</p>
                                @error('parent_id')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-gray-700"></i>
                                    Status
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="active" checked class="sr-only peer">
                                        <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-check-circle text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Active</p>
                                            <p class="text-gray-700 text-sm">Visible to customers</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="inactive" class="sr-only peer">
                                        <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-eye-slash text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Inactive</p>
                                            <p class="text-gray-700 text-sm">Hidden from customers</p>
                                        </div>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-image mr-2 text-gray-700"></i>
                                    Category Image
                                </label>
                                <div class="relative">
                                    <input type="file" name="image" accept="image/*"
                                        id="categoryImageInput"
                                        class="hidden" onchange="previewImage(this, 'categoryImagePreview')">
                                    <label for="categoryImageInput" 
                                        class="block border-4 border-dashed border-gray-200 hover:border-gray-900 rounded-2xl p-12 text-center cursor-pointer transition-all duration-200 group/upload">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 group-hover/upload:bg-gray-900 flex items-center justify-center mx-auto mb-4 transition-all duration-200">
                                            <i class="fas fa-cloud-upload-alt text-gray-700 group-hover/upload:text-white text-2xl transition-all duration-200"></i>
                                        </div>
                                        <p class="text-gray-900 font-medium mb-2">Upload Category Image</p>
                                        <p class="text-gray-700 text-sm">Recommended: 400×400px • Max 2MB</p>
                                        <p class="text-gray-700 text-sm">JPG, PNG, WebP formats</p>
                                    </label>
                                </div>
                                <div id="categoryImagePreview" class="mt-4 hidden">
                                    <div class="relative rounded-xl overflow-hidden border-4 border-gray-100">
                                        <img id="categoryPreviewImage" class="w-full h-48 object-cover">
                                        <button type="button" onclick="removePreview('categoryImagePreview', 'categoryImageInput')"
                                            class="absolute top-3 right-3 w-8 h-8 bg-white hover:bg-gray-900 hover:text-white rounded-full flex items-center justify-center transition-all duration-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="text-gray-700 text-xs text-center mt-2">Image Preview</p>
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                        <button type="button" onclick="CategoryModal.closeAdd()"
                            class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-semibold transition-all duration-200 flex items-center group/submit">
                            <i class="fas fa-plus-circle mr-3 group-hover/submit:rotate-90 transition-transform duration-300"></i>
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center mr-4">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Edit Category</h2>
                        <p class="text-gray-700">Update category details</p>
                    </div>
                </div>
                <button onclick="CategoryModal.closeEdit()" 
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                    <i class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-gray-700"></i>
                                    Category Name *
                                </label>
                                <input type="text" name="name" id="editName" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-sort-numeric-up mr-2 text-gray-700"></i>
                                    Display Order
                                </label>
                                <input type="number" name="sort_order" id="editSortOrder" min="0"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-gray-700"></i>
                                    Description
                                </label>
                                <textarea name="description" id="editDescription" rows="4"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 resize-none transition-all duration-200"></textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-layer-group mr-2 text-gray-700"></i>
                                    Parent Category
                                </label>
                                <select name="parent_id" id="editParentId"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                                    <option value="">Select Parent Category (Optional)</option>
                                    @foreach($categories->whereNull('parent_id') as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-gray-700"></i>
                                    Status
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" id="editStatusActive" value="active" class="sr-only peer">
                                        <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-check-circle text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Active</p>
                                            <p class="text-gray-700 text-sm">Visible to customers</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" id="editStatusInactive" value="inactive" class="sr-only peer">
                                        <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-eye-slash text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Inactive</p>
                                            <p class="text-gray-700 text-sm">Hidden from customers</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-image mr-2 text-gray-700"></i>
                                    Category Image
                                </label>
                                <div class="space-y-4">
                                    <!-- Current Image -->
                                    <div id="currentImageContainer" class="hidden">
                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4">
                                            <div class="relative w-full pt-[100%] rounded-lg overflow-hidden">
                                                <img id="editCurrentImage" src=""
                                                    class="absolute inset-0 w-full h-full object-cover">
                                            </div>
                                            <p class="text-gray-700 text-xs text-center mt-3">Current Image</p>
                                        </div>
                                    </div>

                                    <!-- Change Image -->
                                    <div class="relative">
                                        <input type="file" name="image" accept="image/*" id="editCategoryImageInput"
                                            class="hidden" onchange="previewImage(this, 'editCategoryImagePreview')">
                                        <label for="editCategoryImageInput" 
                                            class="block border-2 border-dashed border-gray-200 hover:border-gray-900 rounded-xl p-6 text-center cursor-pointer transition-all duration-200 group/change">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 group-hover/change:bg-gray-900 flex items-center justify-center mx-auto mb-3 transition-all duration-200">
                                                <i class="fas fa-sync-alt text-gray-700 group-hover/change:text-white transition-all duration-200"></i>
                                            </div>
                                            <p class="text-gray-900 font-medium mb-1">Change Image</p>
                                            <p class="text-gray-700 text-sm">Optional: Upload new image</p>
                                        </label>
                                    </div>
                                    <div id="editCategoryImagePreview" class="hidden">
                                        <div class="relative rounded-xl overflow-hidden border-4 border-gray-100 mt-4">
                                            <img id="editCategoryPreviewImage" class="w-full h-48 object-cover">
                                            <button type="button" onclick="removePreview('editCategoryImagePreview', 'editCategoryImageInput')"
                                                class="absolute top-3 right-3 w-8 h-8 bg-white hover:bg-gray-900 hover:text-white rounded-full flex items-center justify-center transition-all duration-200">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <p class="text-gray-700 text-xs text-center mt-2">New Image Preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                        <button type="button" onclick="CategoryModal.closeEdit()"
                            class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-semibold transition-all duration-200 flex items-center group/update">
                            <i class="fas fa-save mr-3 group-hover/update:rotate-12 transition-transform duration-300"></i>
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom Animations */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            75% { transform: translateX(2px); }
        }
        
        .group-hover\/delete\:shake:hover i {
            animation: shake 0.5s ease-in-out;
        }
        
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
        
        /* Sortable styles */
        .sortable-item {
            user-select: none;
        }
        
        .sortable-item.sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6;
        }
        
        .sortable-item.sortable-drag {
            background-color: #f9fafb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
        
        /* Hide scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }
        
        .overflow-y-auto {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Image preview functions
        function previewImage(input, previewContainerId) {
            const previewContainer = document.getElementById(previewContainerId);
            const previewImage = previewContainer.querySelector('img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview(previewContainerId, inputId) {
            const previewContainer = document.getElementById(previewContainerId);
            const input = document.getElementById(inputId);
            
            previewContainer.classList.add('hidden');
            input.value = '';
        }

        // Sort order functionality
        function toggleSortOrder() {
            const panel = document.getElementById('sortOrderPanel');
            panel.classList.toggle('hidden');
            
            if (!panel.classList.contains('hidden')) {
                initSortable();
            }
        }

        let sortable = null;
        function initSortable() {
            const sortableList = document.getElementById('sortableList');
            if (!sortableList) return;
            
            sortable = new Sortable(sortableList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                handle: '.cursor-move',
                onUpdate: function() {
                    updateOrderNumbers();
                }
            });
        }

        function updateOrderNumbers() {
            const items = document.querySelectorAll('.sortable-item');
            items.forEach((item, index) => {
                const positionSpan = item.querySelector('span.text-sm');
                if (positionSpan) {
                    positionSpan.textContent = `Position: #${index + 1}`;
                }
            });
        }

        function saveSortOrder() {
            const items = document.querySelectorAll('.sortable-item');
            const order = Array.from(items).map(item => item.dataset.id);
            
            Swal.fire({
                title: 'Saving Order...',
                text: 'Updating category positions',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("admin.categories.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Updated!',
                        text: 'Category positions have been saved successfully.',
                        confirmButtonColor: '#111827',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update order. Please try again.',
                        confirmButtonColor: '#111827'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update order. Please try again.',
                    confirmButtonColor: '#111827'
                });
            });
        }

        // Category Modal
        const CategoryModal = {
            openAdd: function() {
                document.getElementById('addCategoryModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            },
            closeAdd: function() {
                const modal = document.getElementById('addCategoryModal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                
                // Reset form
                const form = modal.querySelector('form');
                form.reset();
                
                // Hide preview and reset file input
                removePreview('categoryImagePreview', 'categoryImageInput');
            },
            openEdit: async function(categoryId) {
                try {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Fetching category details',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch(`/admin/categories/${categoryId}/edit`);
                    
                    if (!response.ok) {
                        throw new Error('Failed to load category data');
                    }
                    
                    const category = await response.json();
                    
                    Swal.close();
                    
                    // Populate form
                    document.getElementById('editName').value = category.name;
                    document.getElementById('editSortOrder').value = category.sort_order;
                    document.getElementById('editDescription').value = category.description || '';
                    document.getElementById('editParentId').value = category.parent_id || '';
                    
                    // Set image
                    if (category.image) {
                        document.getElementById('editCurrentImage').src = category.image;
                        document.getElementById('currentImageContainer').classList.remove('hidden');
                    } else {
                        document.getElementById('currentImageContainer').classList.add('hidden');
                    }
                    
                    // Set status
                    if (category.status === 'active') {
                        document.getElementById('editStatusActive').checked = true;
                    } else {
                        document.getElementById('editStatusInactive').checked = true;
                    }
                    
                    // Trigger status styling
                    document.querySelectorAll('input[name="status"]').forEach(radio => {
                        const parentDiv = radio.nextElementSibling;
                        if (radio.checked) {
                            parentDiv.classList.add('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                        }
                    });
                    
                    // Set form action
                    document.getElementById('editCategoryForm').action = `/admin/categories/${category.id}`;
                    
                    // Show modal
                    document.getElementById('editCategoryModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    
                    // Hide edit preview if exists
                    removePreview('editCategoryImagePreview', 'editCategoryImageInput');
                    
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load category data. Please try again.',
                        confirmButtonColor: '#111827',
                    });
                }
            },
            closeEdit: function() {
                const modal = document.getElementById('editCategoryModal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                
                // Hide preview and reset file input
                removePreview('editCategoryImagePreview', 'editCategoryImageInput');
            }
        };

        // Delete category with SweetAlert2
        function confirmDeleteCategory(id, name) {
            Swal.fire({
                title: 'Delete Category?',
                html: `<div class="text-left">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash text-gray-700 text-2xl"></i>
                    </div>
                    <p class="text-gray-900 font-semibold text-lg mb-2">"${name}"</p>
                    <p class="text-gray-700 mb-3">This category will be permanently removed.</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700">
                        <i class="fas fa-exclamation-triangle text-gray-700 mr-2"></i>
                        Products in this category will be moved to "Uncategorized"
                    </div>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-3 rounded-lg font-semibold bg-gray-900',
                    cancelButton: 'px-6 py-3 rounded-lg font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/categories/${id}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Close modals on outside click
        document.addEventListener('click', function(e) {
            if (e.target.id === 'addCategoryModal') CategoryModal.closeAdd();
            if (e.target.id === 'editCategoryModal') CategoryModal.closeEdit();
        });

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                CategoryModal.closeAdd();
                CategoryModal.closeEdit();
            }
        });

        // Initialize radio button styling
        document.addEventListener('DOMContentLoaded', function() {
            // Handle radio button styling
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const allRadios = this.closest('div').querySelectorAll('input[type="radio"]');
                    allRadios.forEach(r => {
                        const parentDiv = r.nextElementSibling;
                        if (r.checked) {
                            parentDiv.classList.add('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                        } else {
                            parentDiv.classList.remove('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                        }
                    });
                });
                
                // Initialize checked state
                if (radio.checked) {
                    const parentDiv = radio.nextElementSibling;
                    parentDiv.classList.add('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                }
            });
        });
    </script>
    @endpush
@endsection