@extends('admin.layouts.app')

@section('content')
<!-- New Arrivals Section -->
<section class="mt-8" data-aos="fade-down">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">New Arrivals</h1>
            <p class="text-gray-600 text-lg">Manage the newest products displayed in the store's new arrivals section.</p>
        </div>
        <div>
            <button onclick="NewArrivalModal.openAdd()"
                class="bg-gray-900 text-white hover:bg-gray-800 transition-all duration-200 px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl hover:scale-105"
                data-aos="zoom-in" data-aos-delay="100">
                <i class="fas fa-plus-circle mr-2"></i> Add New Arrival
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($arrivals->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="150">
        @foreach ($arrivals as $arrival)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 group" 
             data-aos="zoom-in-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}">
            <!-- Product Image -->
            <div class="relative w-full pt-[100%] bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                <img src="{{ asset($arrival->image) }}"
                    alt="{{ $arrival->name }}"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                
                <!-- Status Badge -->
                <div class="absolute top-3 right-3 z-10">
                    <span class="px-3 py-1.5 rounded-full text-xs font-medium shadow-lg {{ $arrival->status === 'active' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-gray-400 to-gray-500 text-white' }}">
                        <i class="fas fa-circle text-[8px] mr-1"></i>{{ ucfirst($arrival->status) }}
                    </span>
                </div>
                
                <!-- Quick Actions Overlay -->
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                    <div class="flex space-x-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                        <button onclick="NewArrivalModal.openEdit('{{ $arrival->id }}', '{{ $arrival->name }}', `{{ addslashes($arrival->description) }}`, '{{ asset($arrival->image) }}', '{{ $arrival->status }}', '{{ $arrival->price }}')"
                            class="bg-white text-gray-800 p-3 rounded-xl shadow-xl hover:shadow-2xl hover:scale-110 transition-all duration-200 hover:bg-gray-50"
                            title="Edit Product">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <form action="{{ route('admin.new-arrivals.destroy', $arrival->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-white text-gray-800 p-3 rounded-xl shadow-xl hover:shadow-2xl hover:scale-110 transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                                    title="Delete Product"
                                    onclick="return confirm('Are you sure you want to delete this new arrival?')">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-gray-700 transition-colors">{{ $arrival->name }}</h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $arrival->description }}</p>

                <!-- Price and Date -->
                <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                    <div class="flex items-center">
                        <span class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-sm">
                            ₹{{ number_format($arrival->price, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-1 text-gray-400">
                        <i class="far fa-clock text-xs"></i>
                        <span class="text-xs">{{ $arrival->created_at->format('M d') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-12 text-center transition-all hover:border-gray-300" data-aos="fade-in" data-aos-delay="200">
        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <i class="fas fa-box-open text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">No New Arrivals</h3>
        <p class="text-gray-600 text-base mb-6 max-w-md mx-auto">Start showcasing your latest products by adding them to the new arrivals section.</p>
        <button onclick="NewArrivalModal.openAdd()"
                class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200">
            <i class="fas fa-plus-circle mr-2"></i> Add First Arrival
        </button>
    </div>
    @endif
</section>

<!-- Add New Arrival Modal -->
<div id="addNewArrivalModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-200" data-aos="zoom-in">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Add New Arrival</h2>
                <p class="text-gray-500 text-sm mt-1">Add a new product to arrivals section</p>
            </div>
            <button onclick="NewArrivalModal.closeAdd()"
                class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.new-arrivals.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    {{-- Product Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Product Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all"
                            placeholder="Enter product name">
                        @error('name')
                            <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Price (₹)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all"
                            placeholder="0.00">
                        @error('price')
                            <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                        <select name="status" required 
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                        <textarea name="description" rows="4" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all resize-none"
                            placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Product Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gray-400 transition-colors group cursor-pointer"
                             onclick="document.getElementById('addImageInput').click()">
                            <input type="file" name="image" accept="image/*" required id="addImageInput"
                                class="hidden" onchange="previewImage(this, 'addImagePreview')">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-gray-900 font-medium text-sm">Click to upload</span>
                                    <p class="text-gray-500 text-xs mt-1">PNG, JPG, JPEG up to 5MB</p>
                                </div>
                                <p class="text-gray-500 text-xs mt-3">
                                    <i class="fas fa-info-circle mr-1"></i>Recommended: Square image (1:1 ratio)
                                </p>
                            </div>
                        </div>
                        <div id="addImagePreview" class="mt-3 hidden">
                            <div class="relative w-full pt-[100%]">
                                <img id="addPreviewImage" class="absolute inset-0 w-full h-full object-cover rounded-lg border border-gray-200">
                            </div>
                            <p class="text-gray-500 text-xs text-center mt-1">Image preview</p>
                        </div>
                        @error('image')
                            <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="NewArrivalModal.closeAdd()"
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:shadow-lg hover:scale-105 transition-all duration-200 shadow-sm">
                    <i class="fas fa-save mr-1"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit New Arrival Modal -->
<div id="editNewArrivalModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-200">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Edit New Arrival</h2>
                <p class="text-gray-500 text-sm mt-1">Update product details</p>
            </div>
            <button onclick="NewArrivalModal.closeEdit()" 
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editArrivalForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    {{-- Product Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Product Name</label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all">
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Price (₹)</label>
                        <input type="number" step="0.01" name="price" id="editPrice" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                        <select name="status" id="edit_status" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="4" required
                            class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:outline-none transition-all resize-none"></textarea>
                    </div>

                    {{-- Image Section --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Product Image</label>
                        
                        <!-- Current Image Preview -->
                        <div class="mb-3">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 p-4 rounded-xl">
                                <div class="relative w-full pt-[100%]">
                                    <img id="edit_image_preview" src="" 
                                         class="absolute inset-0 w-full h-full object-cover rounded-lg">
                                </div>
                                <p class="text-gray-500 text-xs text-center mt-2">Current product image</p>
                            </div>
                        </div>

                        <!-- Replace Image -->
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Change Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gray-400 transition-colors group cursor-pointer"
                             onclick="document.getElementById('editImageInput').click()">
                            <input type="file" name="image" accept="image/*" id="editImageInput"
                                class="hidden" onchange="previewImage(this, 'editImagePreview')">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-sync-alt text-gray-400 text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-gray-900 font-medium text-sm">Click to replace</span>
                                    <p class="text-gray-500 text-xs mt-1">Optional: Upload new image</p>
                                </div>
                            </div>
                        </div>
                        <div id="editImagePreview" class="mt-3 hidden">
                            <div class="relative w-full pt-[100%]">
                                <img id="editPreviewImage" class="absolute inset-0 w-full h-full object-cover rounded-lg border border-gray-200">
                            </div>
                            <p class="text-gray-500 text-xs text-center mt-1">New image preview</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="NewArrivalModal.closeEdit()"
                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:shadow-lg hover:scale-105 transition-all duration-200 shadow-sm">
                    <i class="fas fa-save mr-1"></i> Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview function
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
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    const NewArrivalModal = {
        openAdd: function() {
            const modal = document.getElementById('addNewArrivalModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('input[name="name"]')?.focus();
            }, 100);
        },
        closeAdd: function() {
            const modal = document.getElementById('addNewArrivalModal');
            modal.classList.add('hidden');
            
            // Reset form
            const form = modal.querySelector('form');
            form.reset();
            
            // Hide preview
            document.getElementById('addImagePreview').classList.add('hidden');
            
            // Reset file input
            document.getElementById('addImageInput').value = '';
        },
        openEdit: function(id, name, description, image, status, price) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_image_preview').src = image;
            document.getElementById('edit_status').value = status;
            document.getElementById('editPrice').value = price;
            document.getElementById('editArrivalForm').action = `/admin/new-arrivals/${id}`;
            
            // Hide edit preview if exists
            document.getElementById('editImagePreview').classList.add('hidden');
            
            // Reset file input
            document.getElementById('editImageInput').value = '';
            
            const modal = document.getElementById('editNewArrivalModal');
            modal.classList.remove('hidden');
            
            // Focus on first input
            setTimeout(() => {
                document.getElementById('edit_name').focus();
            }, 100);
        },
        closeEdit: function() {
            const modal = document.getElementById('editNewArrivalModal');
            modal.classList.add('hidden');
            
            // Hide preview
            document.getElementById('editImagePreview').classList.add('hidden');
            
            // Reset file input
            document.getElementById('editImageInput').value = '';
        }
    };

    // Make upload areas clickable
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handlers for upload areas
        const addUploadArea = document.querySelector('#addNewArrivalModal .cursor-pointer');
        if (addUploadArea) {
            addUploadArea.addEventListener('click', function(e) {
                if (!e.target.closest('input[type="file"]')) {
                    document.getElementById('addImageInput').click();
                }
            });
        }
        
        const editUploadArea = document.querySelector('#editNewArrivalModal .cursor-pointer');
        if (editUploadArea) {
            editUploadArea.addEventListener('click', function(e) {
                if (!e.target.closest('input[type="file"]')) {
                    document.getElementById('editImageInput').click();
                }
            });
        }
    });

    // Close modals on outside click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addNewArrivalModal') NewArrivalModal.closeAdd();
        if (e.target.id === 'editNewArrivalModal') NewArrivalModal.closeEdit();
    });

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            NewArrivalModal.closeAdd();
            NewArrivalModal.closeEdit();
        }
    });
</script>
@endsection