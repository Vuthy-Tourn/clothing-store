@extends('layouts.front')

@section('content')
    {{-- Unique Hero Banner with Diagonal Split --}}
    <section class="relative h-screen overflow-hidden bg-black">
        {{-- Background Image with Clip Path --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset($category->image) }}'); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/50"
                style="clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
        </div>

        {{-- Geometric Shapes --}}
        <div class="absolute top-20 right-20 w-32 h-32 border border-white/20 rotate-45"></div>
        <div class="absolute bottom-40 left-10 w-24 h-24 border border-white/20"></div>

        {{-- Content --}}
        <div class="relative h-full flex items-center justify-center">
            <div class="text-center px-4 max-w-4xl">
                <div class="overflow-hidden mb-8">
                    <h1 class="text-7xl md:text-9xl font-bold text-white tracking-tighter animate-[slideUp_0.8s_ease-out]">
                        {{ $category->name }}
                    </h1>
                </div>
                <div class="flex items-center justify-center gap-4 text-white/80 text-sm tracking-[0.3em] uppercase">
                    <span class="w-12 h-px bg-white/60"></span>
                    <span>Collection</span>
                    <span class="w-12 h-px bg-white/60"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Products Section --}}
    <section class="py-24 bg-white relative">
        {{-- Background Accent --}}
        <div class="absolute top-0 right-0 w-1/3 h-64 bg-gradient-to-l from-gray-50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">

                <aside class="lg:col-span-1">
                    <div class="sticky top-8">
                        <div class="relative">
                            {{-- Minimal decorative line --}}
                            <div class="absolute -left-6 top-0 w-0.5 h-full bg-gradient-to-b from-gray-300 to-transparent">
                            </div>

                            <div class="space-y-10">
                                {{-- Search Section --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        SEARCH
                                    </h2>

                                    <div class="relative">
                                        <input type="text" id="searchInput" value="{{ request('search') }}"
                                            placeholder="Find your style..."
                                            class="w-full px-0 py-3 bg-transparent border-0 border-b border-gray-300 focus:border-black focus:ring-0 transition-all text-sm placeholder:text-gray-400 rounded-none">
                                        <div class="absolute right-0 top-1/2 -translate-y-1/2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Stock Filter --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        STOCK STATUS
                                    </h2>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value=""
                                                    {{ request('status') == '' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">All Items</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value="active"
                                                    {{ request('status') == 'active' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">In Stock</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value="inactive"
                                                    {{ request('status') == 'inactive' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Out of Stock</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sort Filter --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        SORT BY
                                    </h2>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value=""
                                                    {{ request('sort') == '' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">All price</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="low"
                                                    {{ request('sort') == 'low' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Price: Low to High</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="high"
                                                    {{ request('sort') == 'high' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Price: High to Low</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Products Grid --}}
                <div class="lg:col-span-4" id="productsContainer">
                    @include('partials.products-grid', ['products' => $products])
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Custom radio button states */
        .filter-radio:checked+.radio-custom {
            border-color: black;
        }

        .filter-radio:checked+.radio-custom .radio-dot {
            transform: scale(1);
        }

        /* Hover effects */
        label:hover .radio-custom {
            border-color: #666;
        }

        label:hover span {
            color: #000;
        }

        /* Smooth transitions */
        .radio-custom,
        .radio-dot,
        span {
            transition: all 0.2s ease;
        }

        /* Clean input focus */
        input:focus {
            outline: none;
            box-shadow: none;
        }

        /* Enhanced Pagination Styles */
        .pagination-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .pagination-number:hover {
            border-color: #000;
            color: #000;
            transform: translateY(-1px);
        }

        .pagination-active {
            background-color: #000;
            border-color: #000;
            color: white;
        }

        .pagination-arrow:hover {
            background-color: #000;
            border-color: #000;
            color: white;
            transform: translateY(-1px);
        }

        .pagination-disabled {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .pagination-disabled:hover {
            transform: none;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
        }

        /* Loading state */
        .pagination-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <script>
        // Initialize custom radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.filter-radio');

            radios.forEach(radio => {
                // Set initial checked state
                if (radio.checked) {
                    const dot = radio.nextElementSibling.querySelector('.radio-dot');
                    dot.style.transform = 'scale(1)';
                }

                // Add change event
                radio.addEventListener('change', function() {
                    const groupName = this.name;
                    document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                        const customRadio = r.nextElementSibling;
                        const dot = customRadio.querySelector('.radio-dot');
                        dot.style.transform = 'scale(0)';
                    });

                    const currentDot = this.nextElementSibling.querySelector('.radio-dot');
                    currentDot.style.transform = 'scale(1)';
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySlug = "{{ $category->slug }}";
            const searchInput = document.getElementById('searchInput');
            const filterInputs = document.querySelectorAll('.filter-radio');
            const productsContainer = document.getElementById('productsContainer');
            const paginationContainer = document.getElementById('paginationContainer');

            let searchTimeout;
            let isUpdating = false;

            // Function to scroll to top smoothly
            function scrollToProductsTop() {
                const productsSection = document.querySelector('section.py-24');
                if (productsSection) {
                    const offsetTop = productsSection.offsetTop - 60; // 100px offset from top
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            }

            // Function to update products without page reload
            function updateProducts(url = null) {
                if (isUpdating) return;
                isUpdating = true;

                // Show loading state
                productsContainer.style.opacity = '0.7';
                if (paginationContainer) {
                    paginationContainer.classList.add('pagination-loading');
                }

                // Build URL with current parameters if no specific URL provided
                if (!url) {
                    const searchValue = searchInput.value;
                    const statusValue = document.querySelector('input[name="status"]:checked')?.value || '';
                    const sortValue = document.querySelector('input[name="sort"]:checked')?.value || '';
                    const pageValue = new URLSearchParams(window.location.search).get('page') || '';

                    const params = new URLSearchParams();
                    if (searchValue) params.append('search', searchValue);
                    if (statusValue) params.append('status', statusValue);
                    if (sortValue) params.append('sort', sortValue);
                    if (pageValue) params.append('page', pageValue);

                    url = `/${categorySlug}?${params.toString()}`;
                }

                // Update URL without reload
                window.history.pushState({}, '', url);

                // Fetch new content
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Parse the HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('productsContainer');

                        if (newContent) {
                            // Smooth transition
                            productsContainer.style.opacity = '0.5';
                            setTimeout(() => {
                                productsContainer.innerHTML = newContent.innerHTML;
                                productsContainer.style.opacity = '1';

                                // Scroll to top of products section
                                scrollToProductsTop();

                                // Re-initialize event listeners for the new pagination
                                initializePaginationListeners();
                            }, 200);
                        }
                        isUpdating = false;

                        // Remove loading state
                        if (paginationContainer) {
                            paginationContainer.classList.remove('pagination-loading');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        isUpdating = false;
                        productsContainer.style.opacity = '1';
                        if (paginationContainer) {
                            paginationContainer.classList.remove('pagination-loading');
                        }
                    });
            }

            // Initialize pagination event listeners
            function initializePaginationListeners() {
                const paginationLinks = document.querySelectorAll('.pagination-arrow, .pagination-number');

                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        updateProducts(url);
                    });
                });
            }

            // Search with debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        updateProducts();
                    }, 600);
                });
            }

            // Radio buttons - instant update
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    updateProducts();
                });
            });

            // Initialize pagination listeners on page load
            initializePaginationListeners();

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                updateProducts(window.location.href);
            });
        });
    </script>
@endsection
