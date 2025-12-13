<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
    @forelse ($products as $product)
        <div class="group">
            {{-- Image Container --}}
            <a href="{{ route('product.view', ['slug' => $product->slug]) }}"
                class="block relative overflow-hidden bg-gray-100 aspect-[3/4] mb-5">
                
                @if($product->images && $product->images->count() > 0)
                    {{-- Primary Image --}}
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                        class="w-full h-full object-cover transition-all duration-700 ease-out group-hover:scale-110"
                        alt="{{ $product->name }}">
                    
                    {{-- Secondary Image (hover) --}}
                    @if($product->images->count() > 1)
                        <img src="{{ asset('storage/' . $product->images->skip(1)->first()->image_path) }}"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-700 ease-out"
                            alt="{{ $product->name }}">
                    @endif
                @else
                    {{-- Placeholder Image --}}
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                {{-- Quick View Overlay --}}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-500 flex items-center justify-center">
                    <button type="button" 
                            onclick="openQuickView({{ $product->id }})"
                            class="quick-view-btn opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0">
                        Quick View
                    </button>
                </div>

                {{-- Status Badges --}}
                <div class="absolute top-5 right-5 flex flex-col gap-2">
                    @if($product->is_new)
                        <span class="px-3 py-1.5 bg-black text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap">
                            New
                        </span>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap">
                            Featured
                        </span>
                    @endif
                    
                    @if($product->IsOnSale())
                        <span class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap">
                            Sale {{ $product->best_discount }}% Off
                        </span>
                    @endif
                    
                    @if($product->is_out_of_stock)
                        <span class="px-3 py-1.5 bg-gray-600 text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap">
                            Out of Stock
                        </span>
                    @endif
                </div>
            </a>

            {{-- Content --}}
            <div class="space-y-3">
                <a href="{{ route('product.view', ['slug' => $product->slug]) }}" class="block">
                    <h3 class="text-base font-semibold text-gray-900 tracking-tight group-hover:text-gray-600 transition-colors line-clamp-2 leading-snug">
                        {{ $product->name }}
                    </h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">
                        {{ $product->category->name ?? 'Uncategorized' }}
                        @if($product->brand)
                            • {{ $product->brand }}
                        @endif
                    </p>
                </a>

                {{-- Rating --}}
                @if($product->rating_cache > 0)
                    <div class="flex items-center gap-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->rating_cache))
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @elseif($i == ceil($product->rating_cache) && $product->rating_cache - floor($product->rating_cache) >= 0.5)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500">
                            ({{ $product->review_count }})
                        </span>
                    </div>
                @endif

                {{-- Price --}}
                <div class="flex items-center gap-3">
                    @if($product->isOnSale())
                        <span class="text-lg font-bold text-black tracking-tight">
                            ₹{{ number_format($product->sale_price ?? $product->min_price, 2) }}
                        </span>
                        <span class="text-sm text-gray-500 line-through">
                            ₹{{ number_format($product->max_price, 2) }}
                        </span>
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">
                            Save {{ $product->best_discount }}%
                        </span>
                    @else
                        @if($product->min_price == $product->max_price)
                            <span class="text-lg font-bold text-black tracking-tight">
                                ₹{{ number_format($product->min_price, 2) }}
                            </span>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 uppercase tracking-widest font-medium">From</span>
                                <span class="text-lg font-bold text-black tracking-tight">
                                    ₹{{ number_format($product->min_price, 2) }}
                                </span>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Variant Options Preview --}}
                @if($product->available_sizes && count($product->available_sizes) > 0)
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @foreach($product->available_sizes as $size)
                            <span class="px-2 py-1 text-xs border border-gray-300 rounded text-gray-700">
                                {{ $size }}
                            </span>
                        @endforeach
                        
                        @if(count($product->available_sizes) > 3)
                            <span class="px-2 py-1 text-xs border border-gray-300 rounded text-gray-700">
                                +{{ count($product->available_sizes) - 3 }} more
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="col-span-full flex flex-col items-center justify-center py-32">
            <div class="relative">
                <div class="w-24 h-24 border-4 border-gray-200"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 border-4 border-black">
                </div>
            </div>
            <h3 class="text-2xl font-bold tracking-tight text-gray-900 mt-8 mb-3">Nothing Found</h3>
            <p class="text-sm text-gray-500 text-center max-w-md tracking-wide leading-relaxed">
                We couldn't find any products matching your search.<br>Try different filters or browse
                our full collection.
            </p>
        </div>
    @endforelse
</div>

{{-- Enhanced Pagination --}}
@if ($products->hasPages())
    <div class="mt-20 flex justify-center">
        <div class="flex items-center gap-2" id="paginationContainer">
            {{-- Previous Page --}}
            @if ($products->onFirstPage())
                <span class="pagination-item pagination-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="pagination-item pagination-arrow pagination-prev"
                    data-page="{{ $products->currentPage() - 1 }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $current = $products->currentPage();
                $last = $products->lastPage();
                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
            @endphp
            
            {{-- First Page --}}
            @if($start > 1)
                <a href="{{ $products->url(1) }}" class="pagination-item pagination-number" data-page="1">1</a>
                @if($start > 2)
                    <span class="pagination-item pagination-dots">...</span>
                @endif
            @endif
            
            {{-- Page Numbers Range --}}
            @for($page = $start; $page <= $end; $page++)
                @if($page == $products->currentPage())
                    <span class="pagination-item pagination-active">{{ $page }}</span>
                @else
                    <a href="{{ $products->url($page) }}" class="pagination-item pagination-number"
                        data-page="{{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endfor
            
            {{-- Last Page --}}
            @if($end < $last)
                @if($end < $last - 1)
                    <span class="pagination-item pagination-dots">...</span>
                @endif
                <a href="{{ $products->url($last) }}" class="pagination-item pagination-number" data-page="{{ $last }}">{{ $last }}</a>
            @endif

            {{-- Next Page --}}
            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="pagination-item pagination-arrow pagination-next"
                    data-page="{{ $products->currentPage() + 1 }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="pagination-item pagination-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
            
            {{-- Page Info --}}
            <div class="ml-6 text-sm text-gray-500">
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
            </div>
        </div>
    </div>
@endif

{{-- Quick View Modal --}}
<div id="quickViewModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-6xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-900">Quick View</h2>
            <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">
                &times;
            </button>
        </div>
        <div id="quickViewContent" class="p-6">
            <!-- Content loaded via JavaScript -->
        </div>
    </div>
</div>

<style>
.pagination-item {
    @apply w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-sm font-medium transition-all duration-200;
}

.pagination-number {
    @apply text-gray-700 hover:bg-gray-50 hover:border-gray-300;
}

.pagination-active {
    @apply bg-black text-white border-black;
}

.pagination-arrow {
    @apply text-gray-700 hover:bg-gray-50 hover:border-gray-300;
}

.pagination-disabled {
    @apply text-gray-300 border-gray-200 cursor-not-allowed;
}

.pagination-dots {
    @apply text-gray-400 border-transparent cursor-default;
}

.quick-view-btn {
    @apply px-6 py-3 bg-white text-black text-sm uppercase tracking-widest font-bold border border-white hover:bg-black hover:text-white transition-all duration-300;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}
</style>

<script>
// Quick View Functions
async function openQuickView(productId) {
    try {
        const modal = document.getElementById('quickViewModal');
        const content = document.getElementById('quickViewContent');
        
        // Show loading
        content.innerHTML = `
            <div class="flex items-center justify-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black"></div>
            </div>
        `;
        
        modal.classList.remove('hidden');
        
        // Fetch product data
        const response = await fetch(`/category/quick-view/${productId}`);
        const data = await response.json();
        
        if (data.success) {
            const product = data.product;
            
            // Build quick view content
            content.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Images -->
                    <div class="space-y-4">
                        <div class="aspect-square overflow-hidden rounded-xl">
                            <img src="${product.images[0]?.path || '/images/placeholder.jpg'}" 
                                 class="w-full h-full object-cover" 
                                 alt="${product.name}">
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            ${product.images.map((image, index) => `
                                <button onclick="changeQuickViewImage('${image.path}', this)"
                                        class="aspect-square overflow-hidden rounded-lg border-2 ${index === 0 ? 'border-black' : 'border-transparent'}">
                                    <img src="${image.path}" 
                                         class="w-full h-full object-cover" 
                                         alt="${product.name}">
                                </button>
                            `).join('')}
                        </div>
                    </div>
                    
                    <!-- Details -->
                    <div class="space-y-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">${product.name}</h1>
                            <p class="text-gray-500 mt-2">${product.category}</p>
                        </div>
                        
                        <!-- Price -->
                        <div class="space-y-2">
                            ${product.is_on_sale ? `
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl font-bold text-black">₹${product.discounted_price}</span>
                                    <span class="text-lg text-gray-500 line-through">₹${product.max_price}</span>
                                    <span class="text-sm font-bold text-green-600 bg-green-50 px-3 py-1 rounded">
                                        Save ${product.discount_percentage}%
                                    </span>
                                </div>
                            ` : `
                                <div class="text-2xl font-bold text-black">₹${product.min_price}</div>
                            `}
                        </div>
                        
                        <!-- Description -->
                        <div class="text-gray-600 leading-relaxed">
                            ${product.short_description || product.description.substring(0, 200) + '...'}
                        </div>
                        
                        <!-- Variants -->
                        <div class="space-y-4">
                            <!-- Sizes -->
                            ${product.available_sizes.length > 0 ? `
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">Size</h3>
                                    <div class="flex flex-wrap gap-2" id="sizeSelection">
                                        ${product.available_sizes.map(size => `
                                            <button type="button"
                                                    onclick="selectSize('${size}')"
                                                    class="px-4 py-2 border rounded-lg text-sm hover:border-black">
                                                ${size}
                                            </button>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                            
                            <!-- Colors -->
                            ${product.available_colors.length > 0 ? `
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">Color</h3>
                                    <div class="flex flex-wrap gap-2" id="colorSelection">
                                        ${product.available_colors.map(color => `
                                            <button type="button"
                                                    onclick="selectColor('${color}')"
                                                    class="w-10 h-10 rounded-full border-2 border-transparent hover:border-black"
                                                    style="background-color: ${product.variants.find(v => v.color === color)?.color_code || '#ccc'}"
                                                    title="${color}">
                                            </button>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        
                        <!-- Add to Cart -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex gap-4">
                                <div class="flex items-center border rounded-lg">
                                    <button onclick="updateQuantity(-1)" class="px-4 py-3 text-gray-600 hover:text-black">-</button>
                                    <input type="number" id="quantity" value="1" min="1" class="w-16 text-center border-0 focus:ring-0">
                                    <button onclick="updateQuantity(1)" class="px-4 py-3 text-gray-600 hover:text-black">+</button>
                                </div>
                                <button onclick="addToCartFromQuickView(${product.id})"
                                        class="flex-1 bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        
                        <!-- View Full Details -->
                        <div class="pt-4">
                            <a href="/product/${product.slug}"
                               class="inline-flex items-center gap-2 text-sm font-medium text-black hover:gap-3 transition-all">
                                View Full Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="text-center py-20">
                    <p class="text-gray-500">Failed to load product details</p>
                </div>
            `;
        }
        
    } catch (error) {
        console.error('Quick view error:', error);
        const content = document.getElementById('quickViewContent');
        content.innerHTML = `
            <div class="text-center py-20">
                <p class="text-gray-500">Error loading product</p>
            </div>
        `;
    }
}

function closeQuickView() {
    document.getElementById('quickViewModal').classList.add('hidden');
}

function changeQuickViewImage(imageUrl, button) {
    const mainImage = document.querySelector('#quickViewModal img[class*="object-cover"]');
    mainImage.src = imageUrl;
    
    // Update active state
    document.querySelectorAll('#quickViewModal button[onclick^="changeQuickViewImage"]').forEach(btn => {
        btn.classList.remove('border-black');
        btn.classList.add('border-transparent');
    });
    button.classList.remove('border-transparent');
    button.classList.add('border-black');
}

function updateQuantity(change) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) || 1;
    value = Math.max(1, value + change);
    input.value = value;
}

function selectSize(size) {
    // Implementation for size selection
    console.log('Selected size:', size);
}

function selectColor(color) {
    // Implementation for color selection
    console.log('Selected color:', color);
}

function addToCartFromQuickView(productId) {
    const quantity = document.getElementById('quantity').value;
    // Implement add to cart logic
    console.log('Add to cart:', productId, quantity);
}

function addToCartQuick(productId) {
    // Quick add to cart from product grid
    console.log('Quick add to cart:', productId);
    // You can implement a toast notification here
}
</script>