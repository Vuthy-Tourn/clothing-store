@extends('layouts.front')

@section('title', 'Search Results - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Search Results</h1>
                        <p class="mt-2 text-gray-600">
                            @if ($products->count() > 0)
                                Found {{ $products->total() }} result{{ $products->total() > 1 ? 's' : '' }} for "<span
                                    class="font-semibold text-gray-900">{{ $searchQuery }}</span>"
                            @else
                                No results found for "<span class="font-semibold text-gray-900">{{ $searchQuery }}</span>"
                            @endif
                        </p>
                    </div>

                    <!-- Search Form -->
                    <div class="mt-4 md:mt-0">
                        <form action="{{ route('products.search') }}" method="GET" class="relative">
                            <input type="text" name="q" value="{{ $searchQuery }}"
                                placeholder="Search products..."
                                class="w-full md:w-80 px-4 py-2 pl-10 pr-4 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if ($products->count() > 0)
                <!-- Filters and Sorting -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <!-- Active Filters -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-700">Active filter:</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full flex items-center">
                                "{{ $searchQuery }}"
                                <button onclick="window.location.href='{{ route('products.search') }}'"
                                    class="ml-2 text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        </div>

                        <!-- Sorting -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">Sort by:</span>
                            <select id="sortSelect"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                <option value="relevance">Relevance</option>
                                <option value="newest">Newest</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="name_asc">Name: A to Z</option>
                                <option value="name_desc">Name: Z to A</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        @php
                            $primaryImage =
                                $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            $minPrice = $product->variants->where('stock', '>', 0)->min('price');
                            $maxPrice = $product->variants->where('stock', '>', 0)->max('price');
                            $hasDiscount = $product->variants->where('discount_price', '!=', null)->count() > 0;
                            $discountPrice = $product->variants
                                ->where('discount_price', '!=', null)
                                ->min('discount_price');
                        @endphp

                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>

                        <h3 class="mt-6 text-xl font-semibold text-gray-900">No products found</h3>
                        <p class="mt-2 text-gray-600">
                            We couldn't find any products matching "{{ $searchQuery }}". Try adjusting your search.
                        </p>

                        <!-- Search Tips -->
                        <div class="mt-8 bg-gray-50 rounded-lg p-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Search tips:</h4>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Check your spelling
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Try more general keywords
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Browse by category instead
                                </li>
                            </ul>
                        </div>

                        <!-- Browse Categories -->
                        <div class="mt-8">
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">Browse by category:</h4>
                            <div class="flex flex-wrap justify-center gap-3">
                                <a href="{{ url('men') }}"
                                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    Men
                                </a>
                                <a href="{{ url('women') }}"
                                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    Women
                                </a>
                                <a href="{{ url('kids') }}"
                                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    Kids
                                </a>
                                {{-- <a href="{{ route('products.new-arrivals') }}" 
                               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                New Arrivals
                            </a>
                            <a href="{{ route('products.featured') }}" 
                               class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                Featured
                            </a> --}}
                            </div>
                        </div>
                    </div>
            @endif
        </div>
    </div>
    </div>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0"
                id="quickViewContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Sorting functionality
            document.getElementById('sortSelect')?.addEventListener('change', function(e) {
                const sort = e.target.value;
                const url = new URL(window.location.href);
                url.searchParams.set('sort', sort);
                window.location.href = url.toString();
            });

            // Quick View Function
            function quickView(slug) {
                const modal = document.getElementById('quickViewModal');
                const content = document.getElementById('quickViewContent');

                // Show loading
                content.innerHTML = `
            <div class="flex items-center justify-center h-64">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
            </div>
        `;

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);

                // Load product data via AJAX
                fetch(`/product/${slug}/quick-view`)
                    .then(response => response.text())
                    .then(html => {
                        content.innerHTML = html;
                        // Initialize quick view modal interactions
                        initQuickViewModal();
                    })
                    .catch(error => {
                        console.error('Error loading quick view:', error);
                        content.innerHTML = `
                    <div class="p-8 text-center">
                        <p class="text-gray-600">Error loading product details</p>
                    </div>
                `;
                    });
            }

            function initQuickViewModal() {
                const modal = document.getElementById('quickViewModal');
                const content = document.getElementById('quickViewContent');

                // Close modal on escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeQuickView();
                    }
                });

                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeQuickView();
                    }
                });

                // Add close button functionality
                const closeBtn = content.querySelector('.quick-view-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', closeQuickView);
                }

                // Initialize variant selection
                const variantSelects = content.querySelectorAll('.variant-select');
                variantSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        updateVariantPrice();
                    });
                });
            }

            function closeQuickView() {
                const modal = document.getElementById('quickViewModal');
                const content = document.getElementById('quickViewContent');

                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                modal.classList.add('opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    content.innerHTML = '';
                }, 300);
            }

            function updateVariantPrice() {
                // Implement variant price update logic here
            }

            // Add to Cart from Search Results
            function addToCartFromSearch(slug) {
                // You'll need to implement this based on your cart functionality
                console.log('Add to cart:', slug);
                // For now, redirect to product page
                window.location.href = `/product/${slug}`;
            }

            // Initialize search page
            document.addEventListener('DOMContentLoaded', function() {
                // Set active sort option
                const urlParams = new URLSearchParams(window.location.search);
                const sortParam = urlParams.get('sort');
                const sortSelect = document.getElementById('sortSelect');
                if (sortParam && sortSelect) {
                    sortSelect.value = sortParam;
                }

                // Highlight search terms in product names
                const searchQuery = "{{ $searchQuery }}";
                if (searchQuery) {
                    highlightSearchTerms(searchQuery);
                }
            });

            function highlightSearchTerms(query) {
                const terms = query.toLowerCase().split(' ');
                const productNames = document.querySelectorAll('.product-name-highlight');

                productNames.forEach(element => {
                    let html = element.textContent;
                    terms.forEach(term => {
                        if (term.length > 2) {
                            const regex = new RegExp(`(${term})`, 'gi');
                            html = html.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                        }
                    });
                    element.innerHTML = html;
                });
            }
        </script>

        <style>
            /* Line clamp utility */
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Smooth transitions */
            .transition-all {
                transition-property: all;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Custom scrollbar for quick view */
            #quickViewContent::-webkit-scrollbar {
                width: 8px;
            }

            #quickViewContent::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }

            #quickViewContent::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            #quickViewContent::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
    @endpush
