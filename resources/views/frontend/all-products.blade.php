@extends('layouts.front')

@section('content')
    {{-- Unique Hero Banner with Diagonal Split --}}
    <section class="relative h-screen overflow-hidden bg-black">
        {{-- Background Image --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/50"
                style="clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
        </div>

        {{-- Shapes --}}
        <div class="absolute top-20 right-20 w-32 h-32 border border-white/20 rotate-45"></div>
        <div class="absolute bottom-40 left-10 w-24 h-24 border border-white/20"></div>

        {{-- Content --}}
        <div class="relative h-full flex items-center justify-center">
            <div class="text-center px-4 max-w-4xl">
                <div class="overflow-hidden mb-8">
                    <h1 class="text-7xl md:text-9xl font-bold text-white tracking-tighter animate-[slideUp_0.8s_ease-out] py-5">
                        {{ __('messages.all_products') }}
                    </h1>
                </div>
                <div class="flex items-center justify-center gap-4 text-white/80 text-sm tracking-[0.3em] uppercase">
                    <span class="w-12 h-px bg-white/60"></span>
                    <span>{{ __('messages.collection') }}</span>
                    <span class="w-12 h-px bg-white/60"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Products Section --}}
    <section class="py-24 bg-white relative">
        <div class="absolute top-0 right-0 w-1/3 h-64 bg-gradient-to-l from-gray-50 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">

                {{-- Filters Sidebar --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-8">
                        <div class="relative">
                            <div class="absolute -left-6 top-0 w-0.5 h-full bg-gradient-to-b from-gray-300 to-transparent">
                            </div>
                            <div class="space-y-10">

                                {{-- Search --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">{{ __('messages.search') }}</h2>
                                    <div class="relative">
                                        <input type="text" id="searchInput" value="{{ request('search') }}"
                                            placeholder="{{ __('messages.find_your_style') }}"
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

                                {{-- Category --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">{{ __('messages.category') }}</h2>
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="category" value=""
                                                    {{ request('category') == '' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ __('messages.all_categories') }}</span>
                                        </label>
                                        @foreach ($categories as $cat)
                                            <label class="flex items-center gap-3 cursor-pointer py-2">
                                                <div class="relative">
                                                    <input type="radio" name="category" value="{{ $cat->id }}"
                                                        {{ request('category') == $cat->id ? 'checked' : '' }}
                                                        class="filter-radio hidden">
                                                    <div
                                                        class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                        <div
                                                            class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Stock --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">{{ __('messages.stock_status') }}</h2>
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
                                            <span class="text-sm text-gray-600">{{ __('messages.all_items') }}</span>
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
                                            <span class="text-sm text-gray-600">{{ __('messages.in_stock') }}</span>
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
                                            <span class="text-sm text-gray-600">{{ __('messages.out_of_stock') }}</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sort --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">{{ __('messages.sort_by') }}</h2>
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
                                            <span class="text-sm text-gray-600">{{ __('messages.newest_first') }}</span>
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
                                            <span class="text-sm text-gray-600">{{ __('messages.price_low_to_high') }}</span>
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
                                            <span class="text-sm text-gray-600">{{ __('messages.price_high_to_low') }}</span>
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

    {{-- Styles --}}
    <style>
        .filter-radio:checked+.radio-custom {
            border-color: black;
        }

        .filter-radio:checked+.radio-custom .radio-dot {
            transform: scale(1);
        }

        label:hover .radio-custom {
            border-color: #666;
        }

        label:hover span {
            color: #000;
        }

        .radio-custom,
        .radio-dot,
        span {
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            box-shadow: none;
        }

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

    {{-- JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterRadios = document.querySelectorAll('.filter-radio');
            const productsContainer = document.getElementById('productsContainer');
            let searchTimeout;

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

            // Unified function to update products
            function updateProducts(event = null, url = null) {
                if (event) event.preventDefault(); // Prevent default if event exists

                const categoryValue = document.querySelector('input[name="category"]:checked')?.value || '';
                const statusValue = document.querySelector('input[name="status"]:checked')?.value || '';
                const sortValue = document.querySelector('input[name="sort"]:checked')?.value || '';
                const searchValue = searchInput?.value || '';

                const params = new URLSearchParams();
                if (categoryValue) params.append('category', categoryValue);
                if (statusValue) params.append('status', statusValue);
                if (sortValue) params.append('sort', sortValue);
                if (searchValue) params.append('search', searchValue);

                if (!url) url = `{{ route('products.all') }}?${params.toString()}`;

                // Update browser URL
                window.history.pushState({}, '', url);

                // Show loading
                productsContainer.style.opacity = '0.5';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('productsContainer');
                        if (newContent) productsContainer.innerHTML = newContent.innerHTML;
                        productsContainer.style.opacity = '1';
                        // Scroll to top of products section
                        scrollToProductsTop();
                        initializePagination();
                    })
                    .catch(err => {
                        console.error(err);
                        productsContainer.style.opacity = '1';
                    });
            }

            // Initialize pagination links
            function initializePagination() {
                const links = document.querySelectorAll('.pagination-arrow, .pagination-number');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        updateProducts(e, this.href);
                    });
                });
            }

            // Filters change
            filterRadios.forEach(radio => {
                radio.addEventListener('change', function(e) {
                    updateProducts(e);
                });
            });

            // Search debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => updateProducts(), 500);
                });
            }

            // Initialize pagination on page load
            initializePagination();

            // Handle browser back/forward
            window.addEventListener('popstate', function() {
                updateProducts(null, window.location.href);
            });
        });
    </script>
@endsection