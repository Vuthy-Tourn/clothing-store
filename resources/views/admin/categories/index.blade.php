@extends('admin.layouts.app')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Categories</h1>
        <p class="text-gray-600 text-base">Manage product categories for your store.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $categories->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $categories->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Inactive</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $categories->where('status', 'inactive')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                    <i class="fas fa-pause-circle text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Actions</p>
                    <button onclick="CategoryModal.openAdd()" 
                            class="btn-primary mt-2 px-4 py-2 rounded-lg text-sm font-medium">
                        Add Category
                    </button>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-plus text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card overflow-hidden" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">All Categories</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">#</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Category</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Slug</th>
                        <th class="py-4 px-6 text-center text-gray-900 font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-right text-gray-900 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($categories as $index => $category)
                        <tr class="hover:bg-gray-50 transition-colors" data-aos="fade-in" data-aos-delay="{{ $index * 50 }}">
                            <td class="py-4 px-6 text-gray-600 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" 
                                         class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                    @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-folder text-gray-400"></i>
                                    </div>
                                    @endif
                                    <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-600 text-sm">{{ $category->slug }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $category->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-2 h-2 rounded-full {{ $category->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></span>
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button
                                        onclick="CategoryModal.openEdit('{{ $category->id }}', '{{ $category->name }}', '{{ $category->slug }}', '{{ $category->status }}')"
                                        class="btn-primary px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                                        <i class="fas fa-edit mr-1 text-xs"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium flex items-center"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fas fa-trash mr-1 text-xs"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">No Categories Found</h3>
                                <p class="text-gray-600 mb-4">Get started by creating your first category</p>
                                <button onclick="CategoryModal.openAdd()" 
                                        class="btn-primary px-6 py-3 rounded-lg font-medium">
                                    <i class="fas fa-plus mr-2"></i> Create Category
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-blue-600"></i> Add New Category
                </h2>
                <button onclick="CategoryModal.closeAdd()"
                    class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Category Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Category Image</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-gray-300">
                            <input type="file" name="image" accept="image/*" class="w-full text-gray-900">
                            <p class="text-gray-500 text-sm mt-2">PNG, JPG up to 5MB</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                        <select name="status" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="CategoryModal.closeAdd()"
                        class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-blue-600"></i> Edit Category
                </h2>
                <button onclick="CategoryModal.closeEdit()"
                    class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                    &times;
                </button>
            </div>

            <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Category Name</label>
                        <input type="text" name="name" id="editName" required
                            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Category Image</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-gray-300">
                            <input type="file" name="image" accept="image/*" class="w-full text-gray-900">
                            <p class="text-gray-500 text-sm mt-2">Leave empty to keep current image</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                        <select name="status" id="editStatus" required
                            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="CategoryModal.closeEdit()"
                        class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                        class="btn-primary px-6 py-3 rounded-lg font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CategoryModal = {
            openAdd: function() {
                document.getElementById('addCategoryModal').classList.remove('hidden');
            },
            closeAdd: function() {
                document.getElementById('addCategoryModal').classList.add('hidden');
            },
            openEdit: function(id, name, slug, status) {
                document.getElementById('editName').value = name;
                document.getElementById('editStatus').value = status;
                document.getElementById('editCategoryForm').action = `/admin/categories/${id}`;
                document.getElementById('editCategoryModal').classList.remove('hidden');
            },
            closeEdit: function() {
                document.getElementById('editCategoryModal').classList.add('hidden');
            }
        };

        document.addEventListener('click', function(e) {
            if (e.target.id === 'addCategoryModal') CategoryModal.closeAdd();
            if (e.target.id === 'editCategoryModal') CategoryModal.closeEdit();
        });
    </script>
@endsection