@extends('admin.layout')

@section('content')
<div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8" data-aos="fade-down">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2  ">Carousel Management</h1>
        <p class="text-white text-lg">Create stunning hero sections for your fashion store</p>
    </div>
    <button onclick="CarouselModal.openAdd()"
        class="bg-Ocean text-Pearl px-5 py-2.5 rounded-lg font-medium shadow-sm hover-lift mt-4 md:mt-0 border border-Ocean hover:bg-Ocean/90 transition-all text-sm">
        <i class="fas fa-plus mr-2"></i>New Carousel
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach ($carousels as $carousel)
    <div class="bg-Pearl border border-Silk rounded-lg overflow-hidden hover-lift transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
        <div class="relative group">
            <img src="{{ asset('storage/' . $carousel->image_path) }}" alt="Carousel Image" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
            <div class="absolute top-2 right-2">
                <span class="{{ $carousel->is_active ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }} px-2 py-1 rounded text-xs font-medium shadow-sm">
                    {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"></div>
        </div>
        <div class="p-4">
            <h3 class="text-Ocean font-semibold text-sm mb-1 truncate">{{ $carousel->title }}</h3>
            <p class="text-Wave text-xs mb-3 line-clamp-2 leading-relaxed">{{ $carousel->description }}</p>
            <div class="flex items-center justify-between">
                <span class="text-Wave text-xs bg-Lace px-2 py-1 rounded border border-Silk">
                    <i class="fas fa-mouse-pointer mr-1"></i>{{ $carousel->button_text }}
                </span>
                <div class="flex space-x-2">
                    <button onclick="CarouselModal.openEdit(this)" data-carousel='@json($carousel)'
                        class="text-Ocean hover:text-Wave transition-colors p-1.5 rounded hover:bg-Lace">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button onclick="DeleteModal.open('carousel', {{ $carousel->id }}, '{{ $carousel->title }}')"
                        class="text-red-500 hover:text-red-600 transition-colors p-1.5 rounded hover:bg-red-50">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($carousels->isEmpty())
<div class="bg-Pearl border border-Silk rounded-lg p-8 text-center shadow-sm" data-aos="fade-in">
    <div class="w-16 h-16 bg-Ocean/10 rounded-lg flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-images text-Ocean text-xl"></i>
    </div>
    <h3 class="text-lg font-bold text-Ocean mb-2  ">No Carousels</h3>
    <p class="text-Wave text-sm mb-4">Create your first carousel to showcase featured products</p>
    <button onclick="CarouselModal.openAdd()"
        class="bg-Ocean text-Pearl px-4 py-2 rounded-lg font-medium text-sm hover-lift border border-Ocean hover:bg-Ocean/90 transition-all">
        <i class="fas fa-plus mr-1"></i>Create Carousel
    </button>
</div>
@endif

<!-- Add Carousel Modal -->
<div id="addCarouselModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-Pearl border border-Silk rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl" data-aos="zoom-in">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-Ocean  ">Add New Carousel</h2>
                <button onclick="CarouselModal.closeAdd()" class="text-Wave hover:text-Ocean transition-colors p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean placeholder-Wave transition-all"
                            placeholder="Enter carousel title">
                        @error('title') 
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Description</label>
                        <textarea name="description" rows="2" required
                            class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean placeholder-Wave transition-all resize-none"
                            placeholder="Enter carousel description">{{ old('description') }}</textarea>
                        @error('description') 
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Button Text --}}
                        <div>
                            <label class="block text-Ocean font-medium mb-2 text-sm">Button Text</label>
                            <input type="text" name="button_text" value="{{ old('button_text') }}" required
                                placeholder="e.g. Shop Now"
                                class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean placeholder-Wave transition-all">
                            @error('button_text') 
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        {{-- Button Link --}}
                        <div>
                            <label class="block text-Ocean font-medium mb-2 text-sm">Button Link</label>
                            <input type="url" name="button_link" value="{{ old('button_link') }}" required
                                placeholder="/shop"
                                class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean placeholder-Wave transition-all">
                            @error('button_link') 
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Status</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="1" checked 
                                    class="mr-2 text-Ocean focus:ring-Ocean border-Silk">
                                <span class="text-Ocean text-sm">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="0"
                                    class="mr-2 text-Ocean focus:ring-Ocean border-Silk">
                                <span class="text-Ocean text-sm">Inactive</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Image</label>
                        <div class="bg-Lace border border-Silk p-3 rounded hover:border-Ocean/50 transition-all">
                            <input type="file" name="image" accept="image/*" required
                                class="w-full text-Ocean text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-Ocean file:text-Pearl hover:file:bg-Ocean/90 transition-all">
                            <p class="text-Wave text-xs mt-2">Recommended: 1920x800px</p>
                        </div>
                        @error('image') 
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-Silk">
                    <button type="button" onclick="CarouselModal.closeAdd()"
                        class="px-4 py-2 bg-Lace border border-Silk text-Ocean rounded text-sm font-medium hover:bg-Silk transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-Ocean text-Pearl px-4 py-2 rounded text-sm font-medium shadow-sm hover-lift border border-Ocean hover:bg-Ocean/90 transition-all">
                        Create Carousel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Carousel Modal -->
<div id="editCarouselModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-Pearl border border-Silk rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl" data-aos="zoom-in">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-Ocean  ">Edit Carousel</h2>
                <button onclick="CarouselModal.closeEdit()" class="text-Wave hover:text-Ocean transition-colors p-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editCarouselForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Title</label>
                        <input type="text" name="title" id="editTitle" required
                            class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean transition-all">
                        @error('title')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Description</label>
                        <textarea name="description" id="editDescription" rows="2" required
                            class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean transition-all resize-none"></textarea>
                        @error('description')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Button Text --}}
                        <div>
                            <label class="block text-Ocean font-medium mb-2 text-sm">Button Text</label>
                            <input type="text" name="button_text" id="editButtonText" required
                                class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean transition-all">
                            @error('button_text')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Button Link --}}
                        <div>
                            <label class="block text-Ocean font-medium mb-2 text-sm">Button Link</label>
                            <input type="url" name="button_link" id="editButtonLink" required
                                class="w-full bg-Lace border border-Silk text-Ocean px-3 py-2 rounded text-sm focus:ring-1 focus:ring-Ocean focus:border-Ocean transition-all">
                            @error('button_link')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Status</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_active" id="editIsActiveTrue" value="1"
                                    class="mr-2 text-Ocean focus:ring-Ocean border-Silk">
                                <span class="text-Ocean text-sm">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_active" id="editIsActiveFalse" value="0"
                                    class="mr-2 text-Ocean focus:ring-Ocean border-Silk">
                                <span class="text-Ocean text-sm">Inactive</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-Ocean font-medium mb-2 text-sm">Current Image</label>
                        <div class="bg-Lace border border-Silk p-3 rounded mb-3">
                            <img id="editCurrentImage" src="" class="w-full h-20 object-cover rounded mb-2 border border-Silk">
                            <p class="text-Wave text-xs text-center">Current image</p>
                        </div>
                        
                        <label class="block text-Ocean font-medium mb-2 text-sm">Change Image</label>
                        <div class="bg-Lace border border-Silk p-3 rounded hover:border-Ocean/50 transition-all">
                            <input type="file" name="image" accept="image/*"
                                class="w-full text-Ocean text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-Ocean file:text-Pearl hover:file:bg-Ocean/90 transition-all">
                            <p class="text-Wave text-xs mt-2">Optional: Upload new image</p>
                        </div>
                        @error('image')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-Silk">
                    <button type="button" onclick="CarouselModal.closeEdit()"
                        class="px-4 py-2 bg-Lace border border-Silk text-Ocean rounded text-sm font-medium hover:bg-Silk transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-Ocean text-Pearl px-4 py-2 rounded text-sm font-medium shadow-sm hover-lift border border-Ocean hover:bg-Ocean/90 transition-all">
                        Update Carousel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-Pearl border border-Silk rounded-lg w-full max-w-sm shadow-xl" data-aos="zoom-in">
        <div class="p-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-200">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                
                <h3 class="text-lg font-bold text-Ocean mb-2  ">Confirm Deletion</h3>
                <p class="text-Wave text-sm mb-6" id="deleteModalText">Are you sure you want to delete this carousel?</p>
                
                <form id="deleteForm" method="POST" class="flex justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="DeleteModal.close()"
                        class="px-4 py-2 bg-Lace border border-Silk text-Ocean rounded text-sm font-medium hover:bg-Silk transition-all flex-1">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-red-600 text-Pearl px-4 py-2 rounded text-sm font-medium hover:bg-red-700 transition-all border border-red-600 flex-1">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </form>
            </div>
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
        openEdit: function(button) {
            const carouselData = JSON.parse(button.getAttribute('data-carousel'));
            
            // Fill form data
            document.getElementById('editTitle').value = carouselData.title;
            document.getElementById('editDescription').value = carouselData.description;
            document.getElementById('editButtonText').value = carouselData.button_text;
            document.getElementById('editButtonLink').value = carouselData.button_link;
            
            // Set current image preview
            if (carouselData.image_path) {
                document.getElementById('editCurrentImage').src = "{{ asset('storage/') }}/" + carouselData.image_path;
            }
            
            // Set active status
            if (carouselData.is_active) {
                document.getElementById('editIsActiveTrue').checked = true;
            } else {
                document.getElementById('editIsActiveFalse').checked = true;
            }
            
            // Set form action
            document.getElementById('editCarouselForm').action = `/admin/carousels/${carouselData.id}`;
            document.getElementById('editCarouselModal').classList.remove('hidden');
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

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addCarouselModal') CarouselModal.closeAdd();
        if (e.target.id === 'editCarouselModal') CarouselModal.closeEdit();
        if (e.target.id === 'deleteModal') DeleteModal.close();
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            CarouselModal.closeAdd();
            CarouselModal.closeEdit();
            DeleteModal.close();
        }
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
</style>
@endsection