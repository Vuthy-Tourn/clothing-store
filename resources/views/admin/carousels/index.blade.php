@extends('admin.layouts.app')

@section('title', 'Carousel Management - Admin Panel')

@section('content')
<div class="mb-8" data-aos="fade-down">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Carousel Management</h1>
            <p class="text-gray-600">Create and manage homepage banners</p>
        </div>
        <button onclick="CarouselModal.openAdd()"
            class="mt-4 md:mt-0 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i>Add New Carousel
        </button>
    </div>
</div>

<!-- Carousel Grid -->
@if($carousels->isEmpty())
<div class="bg-white border border-gray-200 rounded-xl p-12 text-center" data-aos="fade-in">
    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-images text-gray-400 text-3xl"></i>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-3">No Carousels Found</h3>
    <p class="text-gray-600 mb-6 max-w-md mx-auto">Create your first carousel banner to showcase featured products or promotions on your homepage</p>
    <button onclick="CarouselModal.openAdd()"
        class="bg-gray-900 hover:bg-gray-800 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200">
        <i class="fas fa-plus mr-2"></i>Create First Carousel
    </button>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach ($carousels as $carousel)
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
        <div class="relative h-48 overflow-hidden">
            <img src="{{ asset('storage/' . $carousel->image_path) }}" 
                 alt="{{ $carousel->title }}"
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            
            <!-- Status Badge -->
            <div class="absolute top-3 right-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $carousel->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $carousel->title }}</h3>
                <p class="text-gray-600 text-sm line-clamp-2">{{ $carousel->description }}</p>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-medium">
                        <i class="fas fa-mouse-pointer mr-1"></i>{{ $carousel->button_text }}
                    </span>
                    <span class="text-gray-400 text-xs">{{ Str::limit($carousel->button_link, 20) }}</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                    <i class="far fa-calendar mr-1"></i>{{ $carousel->created_at->format('M d, Y') }}
                </span>
                <div class="flex space-x-2">
                    <button onclick="CarouselModal.openEdit({{ $carousel->id }})"
                        class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button onclick="DeleteModal.open('carousel', {{ $carousel->id }}, '{{ $carousel->title }}')"
                        class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Add Carousel Modal -->
<div id="addCarouselModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-3 text-gray-700"></i>Add New Carousel
            </h2>
            <button onclick="CarouselModal.closeAdd()" 
                class="text-gray-400 hover:text-gray-600 text-2xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200">
                &times;
            </button>
        </div>

        <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-900 font-medium mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                        placeholder="Enter carousel title">
                    @error('title')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-900 font-medium mb-2">Button Text *</label>
                    <input type="text" name="button_text" value="{{ old('button_text') }}" required
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                        placeholder="e.g., Shop Now, Explore">
                    @error('button_text')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-900 font-medium mb-2">Description *</label>
                <textarea name="description" rows="3" required
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 resize-none"
                    placeholder="Enter carousel description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-900 font-medium mb-2">Button Link *</label>
                <input type="url" name="button_link" value="{{ old('button_link') }}" required
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                    placeholder="https://example.com/shop">
                @error('button_link')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-900 font-medium mb-2">Status</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="1" checked 
                                class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                            <span class="ml-3 text-gray-900">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="0"
                                class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                            <span class="ml-3 text-gray-900">Inactive</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-900 font-medium mb-2">Image *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gray-400 transition-colors duration-200">
                        <input type="file" name="image" accept="image/*" required
                            class="w-full text-gray-900">
                        <p class="text-gray-500 text-sm mt-3">Recommended size: 1920×800px • Max: 2MB</p>
                        <p class="text-gray-400 text-xs mt-1">JPG, PNG formats supported</p>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="CarouselModal.closeAdd()"
                    class="px-6 py-3 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-gray-900 text-white hover:bg-gray-800 px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>Create Carousel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Carousel Modal -->
<div id="editCarouselModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-3 text-gray-700"></i>Edit Carousel
            </h2>
            <button onclick="CarouselModal.closeEdit()" 
                class="text-gray-400 hover:text-gray-600 text-2xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200">
                &times;
            </button>
        </div>

        <form id="editCarouselForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-900 font-medium mb-2">Title *</label>
                    <input type="text" name="title" id="editTitle" required
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>

                <div>
                    <label class="block text-gray-900 font-medium mb-2">Button Text *</label>
                    <input type="text" name="button_text" id="editButtonText" required
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-900 font-medium mb-2">Description *</label>
                <textarea name="description" id="editDescription" rows="3" required
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 resize-none"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-900 font-medium mb-2">Button Link *</label>
                <input type="url" name="button_link" id="editButtonLink" required
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-900 font-medium mb-2">Status</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="is_active" id="editIsActiveTrue" value="1"
                                class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                            <span class="ml-3 text-gray-900">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="is_active" id="editIsActiveFalse" value="0"
                                class="w-4 h-4 text-gray-900 border-gray-300 focus:ring-gray-500">
                            <span class="ml-3 text-gray-900">Inactive</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-900 font-medium mb-2">Current Image</label>
                    <img id="editCurrentImage" src="" class="w-full h-40 object-cover rounded-xl border border-gray-200 mb-4">
                    
                    <label class="block text-gray-900 font-medium mb-2">Change Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-gray-400 transition-colors duration-200">
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-gray-900">
                        <p class="text-gray-500 text-sm mt-2">Optional: Upload new image</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="CarouselModal.closeEdit()"
                    class="px-6 py-3 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-gray-900 text-white hover:bg-gray-800 px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>Update Carousel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Confirm Deletion</h3>
            <p class="text-gray-600 mb-8" id="deleteModalText">Are you sure you want to delete this carousel?</p>
            
            <form id="deleteForm" method="POST" class="flex justify-center gap-4">
                @csrf
                @method('DELETE')
                <button type="button" onclick="DeleteModal.close()"
                    class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200 flex-1">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-red-600 text-white hover:bg-red-700 px-8 py-3 rounded-lg font-medium transition-colors duration-200 flex-1 flex items-center justify-center">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const CarouselModal = {
        openAdd: function() {
            document.getElementById('addCarouselModal').classList.remove('hidden');
        },
        closeAdd: function() {
            document.getElementById('addCarouselModal').classList.add('hidden');
        },
        openEdit: async function(carouselId) {
            try {
                const modal = document.getElementById('editCarouselModal');
                modal.classList.remove('hidden');
                
                // Fetch carousel data
                const response = await fetch(`/admin/carousels/${carouselId}/edit`);
                
                // Check if response is JSON
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error('Server returned non-JSON response');
                }
                
                const carousel = await response.json();
                
                if (response.ok) {
                    // Populate form fields
                    document.getElementById('editTitle').value = carousel.title;
                    document.getElementById('editDescription').value = carousel.description;
                    document.getElementById('editButtonText').value = carousel.button_text;
                    document.getElementById('editButtonLink').value = carousel.button_link;
                    
                    // Set image
                    if (carousel.image_path) {
                        document.getElementById('editCurrentImage').src = `/storage/${carousel.image_path}`;
                    }
                    
                    // Set status
                    if (carousel.is_active) {
                        document.getElementById('editIsActiveTrue').checked = true;
                    } else {
                        document.getElementById('editIsActiveFalse').checked = true;
                    }
                    
                    // Set form action
                    document.getElementById('editCarouselForm').action = `/admin/carousels/${carousel.id}`;
                } else {
                    throw new Error('Failed to load carousel data');
                }
            } catch (error) {
                console.error('Error loading carousel:', error);
                alert('Failed to load carousel data. Please try again.');
                this.closeEdit();
            }
        },
        closeEdit: function() {
            document.getElementById('editCarouselModal').classList.add('hidden');
        }
    };

    const DeleteModal = {
        open: function(type, id, name) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const text = document.getElementById('deleteModalText');
            
            if (type === 'carousel') {
                form.action = `/admin/carousels/${id}`;
                text.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            }
            
            modal.classList.remove('hidden');
        },
        close: function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    };

    document.addEventListener('click', function(e) {
        if (e.target.id === 'addCarouselModal') CarouselModal.closeAdd();
        if (e.target.id === 'editCarouselModal') CarouselModal.closeEdit();
        if (e.target.id === 'deleteModal') DeleteModal.close();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            CarouselModal.closeAdd();
            CarouselModal.closeEdit();
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
    
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    
    /* Smooth transitions */
    * {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection