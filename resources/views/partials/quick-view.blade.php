<div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
            <h3 class="text-lg font-semibold text-gray-900">Quick View</h3>
            <button class="text-gray-400 hover:text-gray-600 transition-colors quick-view-close">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Content -->
        <div class="overflow-y-auto max-h-[calc(90vh-64px)]">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Product Images -->
                    <div>
                        @if($product->images->count() > 0)
                            <div class="relative rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-auto rounded-lg">
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Details -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        
                        @if($product->category)
                            <p class="text-sm text-gray-500 mt-1">{{ $product->category->name }}</p>
                        @endif
                        
                        <!-- Rating -->
                        @if($product->rating_cache > 0)
                            <div class="flex items-center mt-2">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating_cache))
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">
                                    ({{ $product->review_count ?? 0 }} reviews)
                                </span>
                            </div>
                        @endif
                        
                        <!-- Price -->
                        @php
                            $minPrice = $product->variants->where('stock', '>', 0)->min('price');
                            $maxPrice = $product->variants->where('stock', '>', 0)->max('price');
                            $hasDiscount = $product->variants->where('discount_price', '!=', null)->count() > 0;
                            $discountPrice = $product->variants->where('discount_price', '!=', null)->min('discount_price');
                        @endphp
                        <div class="mt-4">
                            @if($hasDiscount && $discountPrice)
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl font-bold text-gray-900">${{ number_format($discountPrice, 2) }}</span>
                                    <span class="text-lg text-gray-500 line-through">${{ number_format($minPrice, 2) }}</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded">SAVE {{ number_format((($minPrice - $discountPrice) / $minPrice) * 100, 0) }}%</span>
                                </div>
                            @elseif($minPrice == $maxPrice)
                                <span class="text-2xl font-bold text-gray-900">${{ number_format($minPrice, 2) }}</span>
                            @else
                                <span class="text-2xl font-bold text-gray-900">${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Short Description -->
                        @if($product->short_description)
                            <div class="mt-6">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Description</h4>
                                <p class="text-gray-600">{{ $product->short_description }}</p>
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="mt-8 space-y-4">
                            <a href="{{ route('products.view', $product->slug) }}" 
                               class="w-full bg-gray-900 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors text-center block">
                                View Full Details
                            </a>
                            <button onclick="addToCartQuickView('{{ $product->slug }}')"
                                    class="w-full border-2 border-gray-900 text-gray-900 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addToCartQuickView(slug) {
        // Implement add to cart functionality
        console.log('Add to cart from quick view:', slug);
        // Close modal after adding
        document.querySelector('.quick-view-close').click();
    }
</script>