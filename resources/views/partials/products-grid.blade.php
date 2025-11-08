<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
    @forelse ($products as $product)
        <div class="group">
            {{-- Image Container --}}
            <a href="{{ route('product.view', $product->id) }}"
                class="block relative overflow-hidden bg-gray-100 aspect-[3/4] mb-5">
                <img src="{{ asset('storage/' . $product->image) }}"
                    class="w-full h-full object-cover transition-all duration-700 ease-out group-hover:scale-110"
                    alt="{{ $product->name }}">

                {{-- Quick View Overlay --}}
                <div
                    class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-500 flex items-center justify-center">
                    <span
                        class="text-white text-sm tracking-widest uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform translate-y-4 group-hover:translate-y-0">
                        View Details
                    </span>
                </div>

                {{-- Status Badge --}}
                @if ($product->status != 'active')
                    <div class="absolute top-5 right-5">
                        <span
                            class="px-4 py-2 bg-black text-white text-xs font-bold tracking-widest uppercase">
                            Out of Stock
                        </span>
                    </div>
                @endif
            </a>

            {{-- Content --}}
            <div class="space-y-3">
                <a href="{{ route('product.view', $product->id) }}" class="block">
                    <h3
                        class="text-base font-semibold text-gray-900 tracking-tight group-hover:text-gray-600 transition-colors line-clamp-2 leading-snug">
                        {{ $product->name }}
                    </h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>
                </a>

                {{-- Price --}}
                <div class="flex items-center gap-3">
                    <span
                        class="text-xs text-gray-500 uppercase tracking-widest font-medium">From</span>
                    <span class="text-lg font-bold text-black tracking-tight">
                        ${{ number_format($product->sizes->min('price'), 2) }}
                    </span>
                </div>

                {{-- Action Link --}}
                <a href="{{ route('product.view', $product->id) }}"
                    class="inline-flex items-center gap-2 text-xs uppercase tracking-widest font-bold text-black hover:gap-3 transition-all group/link">
                    <span>Shop Now</span>
                    <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="col-span-full flex flex-col items-center justify-center py-32">
            <div class="relative">
                <div class="w-24 h-24 border-4 border-gray-200"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 border-4 border-black">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="pagination-item pagination-arrow pagination-prev" data-page="{{ $products->currentPage() - 1 }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if ($page == $products->currentPage())
                    <span class="pagination-item pagination-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-item pagination-number" data-page="{{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page --}}
            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="pagination-item pagination-arrow pagination-next" data-page="{{ $products->currentPage() + 1 }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="pagination-item pagination-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>
@endif