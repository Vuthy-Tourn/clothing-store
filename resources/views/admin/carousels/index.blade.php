@extends('admin.layouts.app')

@section('title', 'Carousel Management - Admin Panel')

@section('content')
<div class="mb-8" data-aos="fade-down">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Carousel Banners</h1>
            <p class="text-gray-700">Arrange and showcase your featured collections and promotions</p>
        </div>
        <div class="flex items-center gap-3 mt-4 md:mt-0">
            @if($carousels->count() > 1)
            <button onclick="showSortOrder()"
                class="bg-white border border-gray-300 hover:border-gray-900 text-gray-900 hover:text-gray-900 px-6 py-3 rounded-lg font-medium transition-all duration-200 group">
                <i class="fas fa-sort mr-2 group-hover:rotate-180 transition-transform duration-300"></i> Arrange Order
            </button>
            @endif
            <button onclick="CarouselModal.openAdd()"
                class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 group">
                <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>Add New Banner
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 text-sm font-medium mb-1">Total Banners</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $carousels->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                <i class="fas fa-images text-white text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 text-sm font-medium mb-1">Active Banners</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $carousels->where('is_active', true)->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                <i class="fas fa-eye text-white text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 text-sm font-medium mb-1">Inactive Banners</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $carousels->where('is_active', false)->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                <i class="fas fa-eye-slash text-white text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-700 text-sm font-medium mb-1">Display Order</p>
                <h3 class="text-lg font-bold text-gray-900">
                    @if($carousels->isNotEmpty())
                        {{ $carousels->sortBy('sort_order')->first()->title }}
                    @else
                        No banners
                    @endif
                </h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center">
                <i class="fas fa-sort-numeric-up text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Sort Order Panel (Initially Hidden) -->
<div id="sortOrderPanel" class="bg-white border border-gray-200 rounded-2xl p-6 mb-8 hidden" data-aos="fade-up">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Arrange Banner Order</h2>
            <p class="text-gray-700 text-sm">Drag and drop to reorder banners. First banner will be shown first.</p>
        </div>
        <button onclick="saveSortOrder()"
            class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg font-medium transition-all duration-200">
            <i class="fas fa-save mr-2"></i> Save Order
        </button>
    </div>
    
    <div id="sortableList" class="space-y-3">
        @foreach($carousels->sortBy('sort_order') as $carousel)
        <div class="flex items-center gap-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl p-4 transition-all duration-200 cursor-move sortable-item"
             data-id="{{ $carousel->id }}">
            <div class="w-10 h-10 rounded-lg bg-gray-900 flex items-center justify-center">
                <i class="fas fa-arrows-alt text-white"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-medium text-gray-900">{{ $carousel->title }}</h4>
                <p class="text-gray-700 text-sm">Current position: #{{ $carousel->sort_order + 1 }}</p>
            </div>
            <div class="text-gray-700 font-medium">
                <i class="fas fa-grip-vertical"></i>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Carousel Grid -->
@if($carousels->isEmpty())
<div class="bg-white border border-gray-200 rounded-2xl p-16 text-center" data-aos="fade-in">
    <div class="w-32 h-32 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-8 border-4 border-gray-100">
        <i class="fas fa-images text-gray-300 text-4xl"></i>
    </div>
    <h3 class="text-3xl font-bold text-gray-900 mb-4">No Banners Yet</h3>
    <p class="text-gray-700 mb-8 max-w-md mx-auto">Create stunning banners to showcase your collections, promotions, and seasonal offers on the homepage</p>
    <button onclick="CarouselModal.openAdd()"
        class="bg-gray-900 hover:bg-gray-800 text-white px-10 py-4 rounded-xl font-medium transition-all duration-200 group text-lg">
        <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform duration-300"></i>Create First Banner
    </button>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($carousels->sortBy('sort_order') as $carousel)
    <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
        <!-- Order Badge -->
        <div class="absolute top-4 left-4 z-10">
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-gray-900 text-white shadow-lg">
                #{{ $carousel->sort_order + 1 }}
            </span>
        </div>
        
        <div class="relative h-72 overflow-hidden bg-gray-100">
            <img src="{{ asset('storage/' . $carousel->image_path) }}" 
                 alt="{{ $carousel->title }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            
            <!-- Overlay gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
                <span class="px-4 py-2 rounded-full text-sm font-bold backdrop-blur-sm {{ $carousel->is_active ? 'bg-gray-900 text-white' : 'bg-gray-700 text-gray-300' }} shadow-lg">
                    {{ $carousel->is_active ? '● LIVE' : '○ Draft' }}
                </span>
            </div>

            <!-- Quick Actions (shown on hover) -->
            <div class="absolute bottom-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button onclick="CarouselModal.openEdit({{ $carousel->id }})"
                    class="bg-white/95 hover:bg-gray-900 text-gray-900 hover:text-white w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 shadow-lg backdrop-blur-sm group/btn">
                    <i class="fas fa-edit group-hover/btn:rotate-12 transition-transform duration-300"></i>
                </button>
                <button onclick="deleteCarousel({{ $carousel->id }}, '{{ $carousel->title }}')"
                    class="bg-white/95 hover:bg-gray-900 text-gray-900 hover:text-white w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 shadow-lg backdrop-blur-sm group/btn">
                    <i class="fas fa-trash-alt group-hover/btn:shake transition-transform duration-300"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $carousel->title }}</h3>
                <p class="text-gray-700 text-sm line-clamp-2 leading-relaxed">{{ $carousel->description }}</p>
            </div>
            
            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 bg-gray-100 rounded-lg px-4 py-3 overflow-hidden">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-external-link-alt text-gray-700 text-xs"></i>
                        <span class="text-gray-900 font-semibold text-sm">{{ $carousel->button_text }}</span>
                    </div>
                    <p class="text-gray-700 text-xs mt-1 truncate">{{ $carousel->button_link }}</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between pt-4 border-t-2 border-gray-100">
                <div class="flex items-center gap-2 text-gray-700">
                    <i class="far fa-calendar-alt"></i>
                    <span class="text-sm font-medium">{{ $carousel->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full {{ $carousel->is_active ? 'bg-gray-900' : 'bg-gray-400' }}"></div>
                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                        {{ $carousel->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Add Carousel Modal -->
<div id="addCarouselModal" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-8 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center mr-4">
                    <i class="fas fa-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Create New Banner</h2>
                    <p class="text-gray-700">Add a new promotional banner to your store</p>
                </div>
            </div>
            <button onclick="CarouselModal.closeAdd()" 
                class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                <i class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-heading mr-2 text-gray-700"></i>
                                Banner Title *
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                placeholder="e.g., Summer Collection 2024">
                            @error('title')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-align-left mr-2 text-gray-700"></i>
                                Description *
                            </label>
                            <textarea name="description" rows="5" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 resize-none transition-all duration-200"
                                placeholder="Describe your promotion or collection...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-sort-numeric-up mr-2 text-gray-700"></i>
                                Display Order
                            </label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $carousels->count()) }}" min="0"
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                placeholder="Position in display sequence">
                            <p class="text-gray-700 text-xs mt-2">Lower numbers appear first. Leave empty for last position.</p>
                            @error('sort_order')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-mouse-pointer mr-2 text-gray-700"></i>
                                Button Text *
                            </label>
                            <input type="text" name="button_text" value="{{ old('button_text') }}" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                placeholder="e.g., Shop Now, Explore Collection">
                            @error('button_text')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-link mr-2 text-gray-700"></i>
                                Button Link *
                            </label>
                            <input type="url" name="button_link" value="{{ old('button_link') }}" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                placeholder="https://yourstore.com/collection">
                            @error('button_link')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-gray-700"></i>
                                Status
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="is_active" value="1" checked class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                        <i class="fas fa-check-circle text-gray-700 mb-2"></i>
                                        <p class="font-medium text-gray-900">Active</p>
                                        <p class="text-gray-700 text-sm">Visible to customers</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="is_active" value="0" class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                        <i class="fas fa-eye-slash text-gray-700 mb-2"></i>
                                        <p class="font-medium text-gray-900">Inactive</p>
                                        <p class="text-gray-700 text-sm">Hidden from customers</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-image mr-2 text-gray-700"></i>
                                Banner Image *
                            </label>
                            <div class="relative">
                                <input type="file" name="image" accept="image/*" required
                                    id="imageInput"
                                    class="hidden" onchange="previewImage(this, 'addImagePreview')">
                                <label for="imageInput" 
                                    class="border-4 border-dashed border-gray-200 hover:border-gray-900 rounded-2xl p-12 text-center cursor-pointer transition-all duration-200 group/upload">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 group-hover/upload:bg-gray-900 flex items-center justify-center mx-auto mb-4 transition-all duration-200">
                                        <i class="fas fa-cloud-upload-alt text-gray-700 group-hover/upload:text-white text-2xl transition-all duration-200"></i>
                                    </div>
                                    <p class="text-gray-900 font-medium mb-2">Upload Banner Image</p>
                                    <p class="text-gray-700 text-sm">Recommended: 1920×800px • Max 2MB</p>
                                    <p class="text-gray-700 text-sm">JPG, PNG, WebP formats</p>
                                </label>
                            </div>
                            <div id="addImagePreview" class="mt-4 hidden">
                                <div class="relative rounded-xl overflow-hidden border-4 border-gray-100">
                                    <img id="addPreviewImage" class="w-full h-48 object-cover">
                                    <button type="button" onclick="removePreview('addImagePreview', 'imageInput')"
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
                    <button type="button" onclick="CarouselModal.closeAdd()"
                        class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-semibold transition-all duration-200 flex items-center group/submit">
                        <i class="fas fa-plus-circle mr-3 group-hover/submit:rotate-90 transition-transform duration-300"></i>
                        Create Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Carousel Modal -->
<div id="editCarouselModal" class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-8 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center mr-4">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Banner</h2>
                    <p class="text-gray-700">Update your promotional banner</p>
                </div>
            </div>
            <button onclick="CarouselModal.closeEdit()" 
                class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                <i class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <form id="editCarouselForm" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-heading mr-2 text-gray-700"></i>
                                Banner Title *
                            </label>
                            <input type="text" name="title" id="editTitle" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-align-left mr-2 text-gray-700"></i>
                                Description *
                            </label>
                            <textarea name="description" id="editDescription" rows="5" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 resize-none transition-all duration-200"></textarea>
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-sort-numeric-up mr-2 text-gray-700"></i>
                                Display Order
                            </label>
                            <input type="number" name="sort_order" id="editSortOrder" min="0"
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                            <p class="text-gray-700 text-xs mt-2">Lower numbers appear first. 0 = First position.</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-mouse-pointer mr-2 text-gray-700"></i>
                                Button Text *
                            </label>
                            <input type="text" name="button_text" id="editButtonText" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-link mr-2 text-gray-700"></i>
                                Button Link *
                            </label>
                            <input type="url" name="button_link" id="editButtonLink" required
                                class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-gray-700"></i>
                                Status
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="is_active" id="editIsActiveTrue" value="1" class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                        <i class="fas fa-check-circle text-gray-700 mb-2"></i>
                                        <p class="font-medium text-gray-900">Active</p>
                                        <p class="text-gray-700 text-sm">Visible to customers</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="is_active" id="editIsActiveFalse" value="0" class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                        <i class="fas fa-eye-slash text-gray-700 mb-2"></i>
                                        <p class="font-medium text-gray-900">Inactive</p>
                                        <p class="text-gray-700 text-sm">Hidden from customers</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-gray-900 font-semibold mb-3 flex items-center">
                                <i class="fas fa-image mr-2 text-gray-700"></i>
                                Banner Image
                            </label>
                            <div class="space-y-4">
                                <!-- Current Image -->
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4">
                                    <div class="relative w-full pt-[56.25%] rounded-lg overflow-hidden">
                                        <img id="editCurrentImage" src=""
                                            class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                    <p class="text-gray-700 text-xs text-center mt-3">Current Image</p>
                                </div>

                                <!-- Change Image -->
                                <div class="relative">
                                    <input type="file" name="image" accept="image/*" id="editImageInput"
                                        class="hidden" onchange="previewImage(this, 'editImagePreview')">
                                    <label for="editImageInput" 
                                        class="border-2 border-dashed border-gray-200 hover:border-gray-900 rounded-xl p-6 text-center cursor-pointer transition-all duration-200 group/change">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 group-hover/change:bg-gray-900 flex items-center justify-center mx-auto mb-3 transition-all duration-200">
                                            <i class="fas fa-sync-alt text-gray-700 group-hover/change:text-white transition-all duration-200"></i>
                                        </div>
                                        <p class="text-gray-900 font-medium mb-1">Change Image</p>
                                        <p class="text-gray-700 text-sm">Optional: Upload new image</p>
                                    </label>
                                </div>
                                <div id="editImagePreview" class="hidden">
                                    <div class="relative rounded-xl overflow-hidden border-4 border-gray-100 mt-4">
                                        <img id="editPreviewImage" class="w-full h-48 object-cover">
                                        <button type="button" onclick="removePreview('editImagePreview', 'editImageInput')"
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
                    <button type="button" onclick="CarouselModal.closeEdit()"
                        class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-semibold transition-all duration-200 flex items-center group/update">
                        <i class="fas fa-save mr-3 group-hover/update:rotate-12 transition-transform duration-300"></i>
                        Update Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar Styling */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
    
    /* Hide scrollbar for Chrome, Safari and Opera */
    .overflow-y-auto::-webkit-scrollbar {
        display: none;
    }
    
    /* Hide scrollbar for IE, Edge and Firefox */
    .overflow-y-auto {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    
    /* Custom Animations */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        75% { transform: translateX(2px); }
    }
    
    .group-hover\/btn\:shake:hover i {
        animation: shake 0.5s ease-in-out;
    }
    
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
    
    /* Smooth transitions */
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
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
        }
    }

    // Remove preview function
    function removePreview(previewContainerId, inputId) {
        const previewContainer = document.getElementById(previewContainerId);
        const input = document.getElementById(inputId);
        
        previewContainer.classList.add('hidden');
        input.value = '';
    }

    // Show/Hide Sort Order Panel
    function showSortOrder() {
        const panel = document.getElementById('sortOrderPanel');
        panel.classList.toggle('hidden');
        
        if (!panel.classList.contains('hidden')) {
            initSortable();
        }
    }

    // Initialize Sortable.js
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

    // Update order numbers in sortable list
    function updateOrderNumbers() {
        const items = document.querySelectorAll('.sortable-item');
        items.forEach((item, index) => {
            const positionText = item.querySelector('p.text-sm');
            if (positionText) {
                positionText.textContent = `New position: #${index + 1}`;
            }
        });
    }

    // Save sort order to server
    function saveSortOrder() {
        const items = document.querySelectorAll('.sortable-item');
        const order = Array.from(items).map(item => item.dataset.id);
        
        Swal.fire({
            title: 'Saving Order...',
            text: 'Updating banner positions',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("admin.carousels.update-order") }}', {
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
                    text: 'Banner positions have been saved successfully.',
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

    const CarouselModal = {
        openAdd: function() {
            document.getElementById('addCarouselModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        },
        closeAdd: function() {
            const modal = document.getElementById('addCarouselModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Reset form
            const form = modal.querySelector('form');
            form.reset();
            
            // Hide preview and reset file input
            removePreview('addImagePreview', 'imageInput');
        },
        openEdit: async function(carouselId) {
            try {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching banner details',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(`/admin/carousels/${carouselId}/edit`);
                
                if (!response.ok) {
                    throw new Error('Failed to load banner data');
                }
                
                const carousel = await response.json();
                
                Swal.close();
                
                // Populate form
                document.getElementById('editTitle').value = carousel.title;
                document.getElementById('editDescription').value = carousel.description;
                document.getElementById('editButtonText').value = carousel.button_text;
                document.getElementById('editButtonLink').value = carousel.button_link;
                document.getElementById('editSortOrder').value = carousel.sort_order;
                
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
                
                // Trigger status styling
                document.querySelectorAll('input[name="is_active"]').forEach(radio => {
                    const parentDiv = radio.nextElementSibling;
                    if (radio.checked) {
                        parentDiv.classList.add('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                    }
                });
                
                // Set form action
                document.getElementById('editCarouselForm').action = `/admin/carousels/${carousel.id}`;
                
                // Show modal
                document.getElementById('editCarouselModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Hide edit preview if exists
                removePreview('editImagePreview', 'editImageInput');
                
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load banner data. Please try again.',
                    confirmButtonColor: '#111827',
                });
            }
        },
        closeEdit: function() {
            const modal = document.getElementById('editCarouselModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Hide preview and reset file input
            removePreview('editImagePreview', 'editImageInput');
        }
    };

    // Delete function with SweetAlert2
    function deleteCarousel(id, title) {
        Swal.fire({
            title: 'Delete Banner?',
            html: `<div class="text-left">
                <p class="text-gray-900 font-semibold text-lg mb-2">"${title}"</p>
                <p class="text-gray-700">This banner will be permanently removed from your store. This action cannot be undone.</p>
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
                form.action = `/admin/carousels/${id}`;
                
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

    // Close modals on outside click and Escape key
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addCarouselModal') CarouselModal.closeAdd();
        if (e.target.id === 'editCarouselModal') CarouselModal.closeEdit();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            CarouselModal.closeAdd();
            CarouselModal.closeEdit();
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