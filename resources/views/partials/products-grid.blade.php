<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
    @forelse ($products as $product)
        <x-product-card :product="$product" />
    @empty
        {{-- Empty State --}}
        <div class="col-span-full flex flex-col items-center justify-center py-32">
            <div class="relative">
                <div class="w-24 h-24 border-4 border-gray-200"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 border-4 border-black">
                </div>
            </div>
            <h3 class="text-2xl font-bold tracking-tight text-gray-900 mt-8 mb-3">
                {{ __('messages.nothing_found') }}
            </h3>
            <p class="text-sm text-gray-500 text-center max-w-md tracking-wide leading-relaxed">
                {!! __('messages.nothing_found_message') !!}
            </p>
        </div>
    @endforelse
</div>

{{-- Enhanced Pagination --}}
<x-pagination :paginator="$products" />

{{-- Quick View Modal --}}
<div id="quickViewModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-6xl rounded-xl shadow-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-900">{{ __('messages.quick_view') }}</h2>
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
                <span class="ml-3 text-gray-600">${'{{ __("messages.loading") }}'}</span>
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
                                    <span class="text-2xl font-bold text-black">$${product.discounted_price}</span>
                                    <span class="text-lg text-gray-500 line-through">$${product.max_price}</span>
                                    <span class="text-sm font-bold text-green-600 bg-green-50 px-3 py-1 rounded">
                                        ${'{{ __("messages.save") }}'} ${product.discount_percentage}%
                                    </span>
                                </div>
                            ` : `
                                <div class="text-2xl font-bold text-black">$${product.min_price}</div>
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
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">${'{{ __("messages.size") }}'}</h3>
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
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">${'{{ __("messages.color") }}'}</h3>
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
                                    ${'{{ __("messages.add_to_cart") }}'}
                                </button>
                            </div>
                        </div>
                        
                        <!-- View Full Details -->
                        <div class="pt-4">
                            <a href="/product/${product.slug}"
                               class="inline-flex items-center gap-2 text-sm font-medium text-black hover:gap-3 transition-all">
                                ${'{{ __("messages.view_full_details") }}'}
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
                    <p class="text-gray-500">${'{{ __("messages.failed_to_load") }}'}</p>
                </div>
            `;
            }

        } catch (error) {
            console.error('Quick view error:', error);
            const content = document.getElementById('quickViewContent');
            content.innerHTML = `
            <div class="text-center py-20">
                <p class="text-gray-500">${'{{ __("messages.error_loading") }}'}</p>
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