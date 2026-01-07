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