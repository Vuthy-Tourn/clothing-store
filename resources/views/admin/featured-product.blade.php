@extends('admin.layout')

@section('content')
<div class="mb-8" data-aos="fade-down">
    <h1 class="text-3xl font-bold text-white mb-2  ">Featured Product</h1>
    <p class="text-white text-lg">Showcase your star product across the store</p>
</div>

@if($product)
<!-- Modern Card Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Main Product Card -->
    <div class="lg:col-span-2" data-aos="fade-right">
        <div class="bg-Pearl border border-Silk rounded-xl overflow-hidden shadow-sm hover-lift">
            <div class="relative h-64 md:h-80">
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" 
                     class="w-full h-full object-cover">
                <div class="absolute top-4 right-4">
                    <span class="bg-Ocean text-Pearl px-3 py-1 rounded-full text-sm font-medium">
                        Featured
                    </span>
                </div>
                <div class="absolute bottom-4 left-4">
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        -{{ round(100 - ($product->discounted_price / $product->original_price) * 100) }}%
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-Ocean   mb-2">{{ $product->title }}</h2>
                        <p class="text-Wave text-sm mb-3">{{ $product->tagline }}</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full {{ $product->is_active ? 'bg-green-500' : 'bg-red-500' }} mr-2"></div>
                        <span class="text-Wave text-sm">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
                
                <p class="text-Wave text-sm mb-6 leading-relaxed">{{ $product->description }}</p>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <span class="block text-Wave text-sm">Original</span>
                            <span class="text-Wave line-through">₹{{ number_format($product->original_price) }}</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-Wave text-sm">Discounted</span>
                            <span class="text-2xl font-bold text-Ocean">₹{{ number_format($product->discounted_price) }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="FeaturedModal.openEdit()" 
                                class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('admin.featured.destroy', $product->id) }}" 
                              onsubmit="return confirm('Are you sure you want to remove this featured product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-Silk text-Ocean hover:bg-Silk/80 px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                                <i class="fas fa-times mr-2"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Side Panel -->
    <div class="space-y-6" data-aos="fade-left">
        <!-- Performance Stats -->
        <div class="bg-Pearl border border-Silk rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-Ocean mb-4  ">Performance</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-lg bg-Lace">
                    <span class="text-Wave">Views</span>
                    <span class="font-semibold text-Ocean">1,247</span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-Lace">
                    <span class="text-Wave">Clicks</span>
                    <span class="font-semibold text-Ocean">324</span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-Lace">
                    <span class="text-Wave">Conversion</span>
                    <span class="font-semibold text-green-600">12.4%</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-Pearl border border-Silk rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-Ocean mb-4  ">Quick Actions</h3>
            <div class="space-y-3">
                <button onclick="FeaturedModal.openEdit()" 
                        class="w-full bg-Ocean text-Pearl hover:bg-Ocean/90 py-3 rounded-lg font-medium flex items-center justify-center transition-colors">
                    <i class="fas fa-edit mr-2"></i> Edit Product Details
                </button>
                <a href="#" class="block bg-Lace text-Ocean hover:bg-Silk py-3 rounded-lg font-medium text-center transition-colors">
                    <i class="fas fa-eye mr-2"></i> Preview on Site
                </a>
            </div>
        </div>
    </div>
</div>

@else
<!-- Empty State with Illustration -->
<div class="flex flex-col items-center justify-center py-12" data-aos="fade-up">
    <div class="w-48 h-48 mb-6 relative">
        <div class="absolute inset-0 bg-Lace rounded-full flex items-center justify-center">
            <i class="fas fa-star text-Ocean text-6xl opacity-20"></i>
        </div>
        <div class="absolute inset-0 flex items-center justify-center">
            <i class="fas fa-crown text-Ocean text-4xl"></i>
        </div>
    </div>
    
    <h2 class="text-2xl font-bold text-white mb-3  ">No Featured Product</h2>
    <p class="text-white text-lg mb-2 text-center max-w-md">Highlight your best product to drive sales and engagement</p>
    <p class="text-white/80 text-sm mb-8 text-center">Featured products get 3x more visibility</p>
    
    <button onclick="FeaturedModal.open()" 
            class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-6 py-3 rounded-lg font-medium flex items-center transition-colors" 
            data-aos="zoom-in">
        <i class="fas fa-plus-circle mr-2"></i> Set Featured Product
    </button>
</div>
@endif

<!-- Modals (same as before but with updated styling) -->
@if($product)
<!-- Edit Modal -->
<div id="editFeaturedModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-Pearl w-full max-w-2xl rounded-xl shadow-lg p-6 relative border border-Silk max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-Ocean  ">Edit Featured Product</h2>
            <button onclick="FeaturedModal.closeEdit()" class="text-Wave hover:text-Ocean text-xl transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.featured.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Title</label>
                        <input type="text" name="title" value="{{ $product->title }}" required 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Tagline</label>
                        <input type="text" name="tagline" value="{{ $product->tagline }}" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Button Text</label>
                        <input type="text" name="button_text" value="{{ $product->button_text }}" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Button Link</label>
                        <input type="url" name="button_link" value="{{ $product->button_link }}" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-Ocean mb-2">Original Price</label>
                            <input type="number" name="original_price" value="{{ $product->original_price }}" step="0.01" 
                                   class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-Ocean mb-2">Discounted Price</label>
                            <input type="number" name="discounted_price" value="{{ $product->discounted_price }}" step="0.01" 
                                   class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">{{ $product->description }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Image</label>
                        <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center">
                            <input type="file" name="image" class="w-full text-Ocean">
                            <p class="text-Wave text-sm mt-2">PNG, JPG up to 5MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-Silk">
                <button type="button" onclick="FeaturedModal.closeEdit()" 
                        class="px-6 py-3 bg-Silk hover:bg-Silk/80 text-Ocean rounded-lg font-medium transition-colors">
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
@endif

<!-- Add Featured Modal -->
<div id="featuredModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-Pearl w-full max-w-2xl rounded-xl shadow-lg p-6 relative border border-Silk max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-Ocean  ">Add Featured Product</h2>
            <button onclick="FeaturedModal.close()" class="text-Wave hover:text-Ocean text-xl transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="featuredProductForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.featured.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Title</label>
                        <input type="text" name="title" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Tagline</label>
                        <input type="text" name="tagline" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                        @error('tagline')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Button Text</label>
                        <input type="text" name="button_text" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                        @error('button_text')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Button Link</label>
                        <input type="text" name="button_link" 
                               class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                        @error('button_link')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-Ocean mb-2">Original Price</label>
                            <input type="number" name="original_price" step="0.01" 
                                   class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                            @error('original_price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-Ocean mb-2">Discounted Price</label>
                            <input type="number" name="discounted_price" step="0.01" 
                                   class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean">
                            @error('discounted_price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full border border-Silk bg-Lace text-Ocean rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-Ocean"></textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-Ocean mb-2">Product Image</label>
                        <div class="border-2 border-dashed border-Silk rounded-lg p-4 text-center">
                            <input type="file" name="image" class="w-full text-Ocean">
                            <p class="text-Wave text-sm mt-2">PNG, JPG up to 5MB</p>
                            @error('image')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-Silk">
                <button type="button" onclick="FeaturedModal.close()" 
                        class="px-6 py-3 bg-Silk hover:bg-Silk/80 text-Ocean rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-Ocean text-Pearl hover:bg-Ocean/90 px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Add Featured Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const FeaturedModal = {
        open: function() {
            document.getElementById('featuredModal').classList.remove('hidden');
        },
        close: function() {
            document.getElementById('featuredModal').classList.add('hidden');
        },
        openEdit: function() {
            document.getElementById('editFeaturedModal').classList.remove('hidden');
        },
        closeEdit: function() {
            document.getElementById('editFeaturedModal').classList.add('hidden');
        }
    };
</script>
@endsection