@extends('admin.layout')

@section('content')

<!-- New Arrivals Section -->
<section class="mt-8" data-aos="fade-down">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-bold text-white mb-2  ">New Arrivals</h1>
            <p class="text-white text-lg">Manage the newest products displayed in the store's new arrivals section.</p>
        </div>
        <div>
            <button onclick="NewArrivalModal.openAdd()"
                class="bg-Ocean text-Pearl hover:bg-Ocean/90 transition-all duration-200 px-6 py-3 rounded-lg font-medium flex items-center"
                data-aos="zoom-in" data-aos-delay="100">
                <i class="fas fa-plus-circle mr-2"></i> Add New Arrival
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" data-aos="fade-up" data-aos-delay="100">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($arrivals->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="150">
        @foreach ($arrivals as $arrival)
        <div class="bg-Pearl border border-Silk rounded-xl overflow-hidden hover-lift shadow-sm group" 
             data-aos="zoom-in-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}">
            <!-- Product Image -->
            <div class="relative overflow-hidden">
                <img src="{{ asset($arrival->image) }}"
                    alt="{{ $arrival->name }}"
                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                
                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $arrival->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($arrival->status) }}
                    </span>
                </div>
                
                <!-- Price Tag -->
                <div class="absolute bottom-3 left-3">
                    <span class="bg-Ocean text-Pearl px-3 py-1 rounded-lg text-sm font-bold">
                        ₹{{ number_format($arrival->price, 2) }}
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="text-lg font-bold text-Ocean mb-2 line-clamp-1">{{ $arrival->name }}</h3>
                <p class="text-Wave text-sm mb-4 line-clamp-2">{{ $arrival->description }}</p>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center text-Wave text-sm">
                        <i class="fas fa-calendar mr-1"></i>
                        <span>{{ $arrival->created_at->format('M d') }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            onclick="NewArrivalModal.openEdit('{{ $arrival->id }}', '{{ $arrival->name }}', `{{ $arrival->description }}`, '{{ asset($arrival->image) }}', '{{ $arrival->status }}', '{{ $arrival->price }}')"
                            class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-3 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>

                        <form action="{{ route('admin.new-arrivals.destroy', $arrival->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-Silk text-Ocean hover:bg-Silk/80 px-3 py-2 rounded-lg text-sm font-medium flex items-center transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this new arrival?')">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-Pearl border border-Silk rounded-xl p-12 text-center" data-aos="fade-in" data-aos-delay="200">
        <div class="w-24 h-24 rounded-full bg-Lace flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-box-open text-Ocean text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-Ocean mb-3  ">No New Arrivals</h3>
        <p class="text-Wave mb-6 max-w-md mx-auto">Start showcasing your latest products by adding them to the new arrivals section.</p>
        <button onclick="NewArrivalModal.openAdd()"
                class="bg-Ocean text-Pearl hover:bg-Ocean/90 transition-all duration-200 px-6 py-3 rounded-lg font-medium inline-flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Add First Arrival
        </button>
    </div>
    @endif
</section>

<!-- Add New Arrival Modal -->
<div id="addNewArrivalModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300">
    <div class="bg-Pearl w-full max-w-2xl rounded-xl shadow-2xl border border-Silk transform transition-all duration-300 scale-95"
         data-aos="zoom-in">
        <div class="flex items-center justify-between p-6 border-b border-Silk">
            <h2 class="text-xl font-bold text-Ocean   flex items-center">
                <i class="fas fa-plus-circle mr-2 text-Ocean"></i> Add New Arrival
            </h2>
            <button onclick="NewArrivalModal.closeAdd()"
                class="text-Wave hover:text-Ocean transition-colors duration-200 text-xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-Lace">
                &times;
            </button>
        </div>

        <form action="{{ route('admin.new-arrivals.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    {{-- Product Name --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Price (₹)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
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

                <!-- Right Column -->
                <div class="space-y-4">
                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Description</label>
                        <textarea name="description" rows="4" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors resize-none">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Image</label>
                        <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center transition-colors hover:border-Ocean/50">
                            <input type="file" name="image" accept="image/*" required class="w-full text-Ocean">
                            <p class="text-Wave text-sm mt-2">PNG, JPG, JPEG up to 5MB</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-Silk">
                <button type="button" onclick="NewArrivalModal.closeAdd()"
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

<!-- Edit New Arrival Modal -->
<div id="editNewArrivalModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300">
    <div class="bg-Pearl w-full max-w-2xl rounded-xl shadow-2xl border border-Silk transform transition-all duration-300 scale-95"
         data-aos="zoom-in">
        <div class="flex items-center justify-between p-6 border-b border-Silk">
            <h2 class="text-xl font-bold text-Ocean   flex items-center">
                <i class="fas fa-edit mr-2 text-Ocean"></i> Edit New Arrival
            </h2>
            <button onclick="NewArrivalModal.closeEdit()" 
                    class="text-Wave hover:text-Ocean transition-colors duration-200 text-xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-Lace">
                &times;
            </button>
        </div>

        <form id="editArrivalForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    {{-- Product Name --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Name</label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Price (₹)</label>
                        <input type="number" step="0.01" name="price" id="editPrice" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Status</label>
                        <select name="status" id="edit_status" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="4" required
                            class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean transition-colors resize-none"></textarea>
                    </div>

                    {{-- Image Section --}}
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Image</label>
                        
                        <!-- Current Image Preview -->
                        <div class="mb-3">
                            <label class="block text-sm text-Wave mb-2">Current Image</label>
                            <img id="edit_image_preview" src="" 
                                 class="w-full h-32 object-cover rounded-lg border border-Silk">
                        </div>

                        <!-- Replace Image -->
                        <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center transition-colors hover:border-Ocean/50">
                            <label class="block text-sm text-Wave mb-2">Replace Image (Optional)</label>
                            <input type="file" name="image" accept="image/*" 
                                   class="w-full text-Ocean">
                            <p class="text-Wave text-sm mt-2">Leave empty to keep current image</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-Silk">
                <button type="button" onclick="NewArrivalModal.closeEdit()"
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

<script>
    const NewArrivalModal = {
        openAdd: function() {
            const modal = document.getElementById('addNewArrivalModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.scale-95').classList.remove('scale-95');
            }, 50);
        },
        closeAdd: function() {
            const modal = document.getElementById('addNewArrivalModal');
            modal.querySelector('.scale-95').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        },
        openEdit: function(id, name, description, image, status, price) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_image_preview').src = image;
            document.getElementById('edit_status').value = status;
            document.getElementById('editPrice').value = price;
            document.getElementById('editArrivalForm').action = `/admin/new-arrivals/${id}`;
            
            const modal = document.getElementById('editNewArrivalModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.scale-95').classList.remove('scale-95');
            }, 50);
        },
        closeEdit: function() {
            const modal = document.getElementById('editNewArrivalModal');
            modal.querySelector('.scale-95').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    };

    // Close modals when clicking on backdrop
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addNewArrivalModal') {
            NewArrivalModal.closeAdd();
        }
        if (e.target.id === 'editNewArrivalModal') {
            NewArrivalModal.closeEdit();
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
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
    }
</style>

@endsection