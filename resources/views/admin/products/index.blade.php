@extends('admin.layouts.app')

@section('content')
    <!-- Page Header with Action Button -->
    <div class="mb-8" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin.products.title') }}</h1>
                <p class="text-gray-600 text-base">{{ __('admin.products.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="ProductModal.openAdd()"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-3 rounded-xl font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i>
                    {{ __('admin.products.actions.add_product') }}
                </button>

                <!-- Optional: Add more header actions -->
                <button onclick="showImportModal()"
                    class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-3 rounded-xl font-medium transition-all duration-300">
                    <i class="fas fa-upload"></i>
                    {{ __('admin.products.actions.import') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview with Gradient Design -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @php
            $totalProducts = $products->count();
            $activeProducts = $products->where('status', 'active')->count();
            $inactiveProducts = $products->where('status', '!=', 'active')->count();
            $categoriesCount = $categories->count();
            $totalStock = $products->sum('stock');
            $lowStockCount = $products->where('stock', '<=', 10)->count();
        @endphp

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">{{ __('admin.products.stats.total_products') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="totalProducts">{{ $totalProducts }}</p>
                    <p class="text-blue-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-box mr-1"></i> {{ __('admin.products.stats.all_products') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">{{ __('admin.products.stats.active_products') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="activeProducts">{{ $activeProducts }}</p>
                    <p class="text-green-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ __('admin.products.stats.active_percent', ['percent' => number_format(($activeProducts / $totalProducts) * 100, 0)]) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">{{ __('admin.products.stats.categories') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="categoriesCount">{{ $categoriesCount }}</p>
                    <p class="text-purple-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-tags mr-1"></i> {{ __('admin.products.stats.product_categories') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center">
                    <i class="fas fa-tags text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">{{ __('admin.products.stats.low_stock') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $lowStockCount }}</p>
                    <p class="text-orange-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ __('admin.products.stats.needs_restocking') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-500 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card overflow-hidden" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-bold text-gray-900">{{ __('admin.products.table.all_products') }}</h2>
                    <span id="productCount" class="ml-2 text-sm text-gray-500">({{ $totalProducts }})</span>
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full md:w-auto">
                    <!-- Filters Row -->
                    <div class="flex flex-wrap items-center gap-3 mb-3 md:mb-0">
                        <!-- Category Filter -->
                        <div class="relative">
                            <select id="categoryFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">{{ __('admin.products.table.filter_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <select id="statusFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">{{ __('admin.products.table.filter_status') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                    {{ __('admin.products.table.status_options.active') }}
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                    {{ __('admin.products.table.status_options.inactive') }}
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                    {{ __('admin.products.table.status_options.draft') }}
                                </option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>

                        <!-- Stock Status Filter -->
                        <div class="relative">
                            <select id="stockFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">{{ __('admin.products.table.filter_stock') }}</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>
                                    {{ __('admin.products.table.stock_options.in_stock') }}
                                </option>
                                <option value="out_of_stock"
                                    {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>
                                    {{ __('admin.products.table.stock_options.out_of_stock') }}
                                </option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>

                        <!-- Clear All Filters Button -->
                        <button id="clearFiltersBtn" type="button" onclick="clearFilters()"
                            class="hidden inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                            <i class="fas fa-times"></i>
                            {{ __('admin.products.filters.clear_all') }}
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full md:w-auto">
                        <input type="text" id="searchInput" placeholder="{{ __('admin.products.table.search_placeholder') }}"
                            class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 pl-10 pr-10 focus:ring-2 focus:ring-blue-500 text-sm w-full md:w-64"
                            value="{{ request('search') ?? '' }}">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>

                        <!-- Loading Spinner -->
                        <div id="searchLoading" class="absolute right-3 top-3 hidden">
                            <i class="fas fa-spinner fa-spin text-blue-500"></i>
                        </div>

                        <!-- Clear Button -->
                        <button id="clearSearchBtn" type="button"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.id') }}
                        </th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.image') }}
                        </th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.product') }}
                        </th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.category') }}
                        </th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.price_range') }}
                        </th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.stock') }}
                        </th>
                        <th class="py-4 px-6 text-center text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.status') }}
                        </th>
                        <th class="py-4 px-6 text-right text-gray-900 font-semibold text-sm">
                            {{ __('admin.products.table.headers.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody id="productsTableBody" class="divide-y divide-gray-200">
                    @include('admin.products.partials.table', ['products' => $products])
                </tbody>
            </table>

            <!-- Empty State -->
            <div id="emptyState" class="hidden p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">
                    {{ __('admin.products.empty.no_results_title') }}
                </h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    {{ __('admin.products.empty.no_results_message') }}
                </p>
                <button onclick="clearFilters()"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg font-medium transition-colors duration-300">
                    <i class="fas fa-filter"></i>
                    {{ __('admin.products.empty.reset_filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Include Modal Components -->
    @include('admin.products.modals.add')
    @include('admin.products.modals.edit')
    @include('admin.products.modals.delete')
    @include('admin.products.scripts')

    <style>
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        /* For deleted images */
        .image-deleted {
            opacity: 0.5;
            background-color: #fef2f2;
        }

        .image-deleted img {
            filter: grayscale(100%);
        }

        /* Smooth transitions */
        .variant-row,
        .border {
            transition: all 0.3s ease;
        }

        /* Image preview styling */
        img.object-cover {
            object-fit: cover;
        }

        /* SweetAlert2 custom styling */
        .swal2-popup {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }

        /* Filter active state */
        .filter-active {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
    </style>

    <script>
        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Function to get all current filter values
        function getCurrentFilters() {
            return {
                search: document.getElementById('searchInput').value.trim(),
                category: document.getElementById('categoryFilter').value,
                status: document.getElementById('statusFilter').value,
                stock_status: document.getElementById('stockFilter').value,
                price_range: document.getElementById('priceFilter') ? document.getElementById('priceFilter').value : ''
            };
        }

        // Function to check if any filter is active
        function hasActiveFilters(filters) {
            return Object.values(filters).some(value => value !== '');
        }

        // Function to update filter UI state
        function updateFilterUI(filters) {
            const filtersActive = hasActiveFilters(filters);
            const clearBtn = document.getElementById('clearFiltersBtn');

            if (filtersActive) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            // Update active state on filter selects
            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                if (select.value) {
                    select.classList.add('filter-active');
                } else {
                    select.classList.remove('filter-active');
                }
            });

            // Update clear search button
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            if (filters.search) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
        }

        // Function to apply all filters
        function applyFilters() {
            const filters = getCurrentFilters();

            // Update UI
            updateFilterUI(filters);

            // Show loading
            showTableLoading();
            document.getElementById('searchLoading')?.classList.remove('hidden');

            // Build query string - remove empty filters
            const cleanFilters = {};
            Object.keys(filters).forEach(key => {
                if (filters[key] !== '' && filters[key] !== null && filters[key] !== undefined) {
                    cleanFilters[key] = filters[key];
                }
            });

            const queryString = new URLSearchParams(cleanFilters).toString();
            const url = `/admin/products?${queryString}&ajax=1`;

            // Update URL without reloading page
            const newUrl = queryString ? `/admin/products?${queryString}` : '/admin/products';
            window.history.pushState({}, '', newUrl);

            // Fetch filtered products
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const tableBody = document.getElementById('productsTableBody');
                    const emptyState = document.getElementById('emptyState');

                    if (data.success) {
                        // Update table body
                        if (data.html && data.html.trim() !== '') {
                            tableBody.innerHTML = data.html;
                            tableBody.classList.remove('hidden');
                            emptyState.classList.add('hidden');
                        } else {
                            // Show empty state
                            tableBody.innerHTML = '';
                            tableBody.classList.add('hidden');
                            emptyState.classList.remove('hidden');
                        }

                        // Update stats
                        if (data.stats) {
                            updateStats(data.stats);
                        }

                        // Update product count in header
                        const productCount = document.getElementById('productCount');
                        if (productCount) {
                            productCount.textContent = `(${data.count || 0})`;
                        }

                        // Reinitialize AOS animations for new content
                        if (typeof AOS !== 'undefined') {
                            AOS.refresh();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    // Fallback: reload page normally
                    window.location.href = newUrl;
                })
                .finally(() => {
                    // Hide loading
                    document.getElementById('searchLoading')?.classList.add('hidden');
                    hideTableLoading();
                });
        }

        // Function to clear all filters
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('stockFilter').value = '';
            const priceFilter = document.getElementById('priceFilter');
            if (priceFilter) {
                priceFilter.value = '';
            }

            applyFilters();
        }

        // Function to update stats
        function updateStats(stats) {
            // Update total products
            const totalProducts = document.getElementById('totalProducts');
            if (totalProducts) {
                totalProducts.textContent = stats.total || 0;
            }

            // Update active products
            const activeProducts = document.getElementById('activeProducts');
            if (activeProducts) {
                activeProducts.textContent = stats.active || 0;
            }

            // Update categories count
            const categoriesCount = document.getElementById('categoriesCount');
            if (categoriesCount) {
                categoriesCount.textContent = stats.categories || 0;
            }
        }

        // Initialize filters from URL on page load
        function initializeFiltersFromURL() {
            const urlParams = new URLSearchParams(window.location.search);

            // Set search input
            if (urlParams.has('search')) {
                document.getElementById('searchInput').value = urlParams.get('search');
            }

            // Set filter dropdowns
            const filterMap = {
                'category': 'categoryFilter',
                'status': 'statusFilter',
                'stock_status': 'stockFilter',
                'price_range': 'priceFilter'
            };

            Object.entries(filterMap).forEach(([param, elementId]) => {
                if (urlParams.has(param)) {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.value = urlParams.get(param);
                    }
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            // Initialize filter values from URL
            initializeFiltersFromURL();

            // Update UI state based on initial filters
            updateFilterUI(getCurrentFilters());

            // Apply filters on page load if there are any URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.toString()) {
                // Small delay to ensure DOM is fully loaded
                setTimeout(() => {
                    applyFilters();
                }, 100);
            }

            // Debounced search input
            const debouncedSearch = debounce(function(e) {
                applyFilters();
            }, 500);

            searchInput.addEventListener('input', debouncedSearch);

            // Enter key to search immediately
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }

                // Escape to clear search
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    applyFilters();
                }
            });

            // Clear search button
            document.getElementById('clearSearchBtn').addEventListener('click', function() {
                searchInput.value = '';
                applyFilters();
            });

            // Clear filters button
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearFilters);
            }

            // Filter dropdown change events
            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', applyFilters);
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                initializeFiltersFromURL();
                applyFilters();
            });
        });

        // Show loading overlay on table
        function showTableLoading() {
            const tableContainer = document.querySelector('.overflow-x-auto');
            if (tableContainer) {
                let overlay = tableContainer.querySelector('.table-loading-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className =
                        'table-loading-overlay absolute inset-0 bg-white/80 flex items-center justify-center z-10';
                    overlay.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i>
                        <p class="text-gray-700 font-medium">{{ __('admin.products.table.loading') }}</p>
                    </div>
                `;
                    tableContainer.classList.add('relative');
                    tableContainer.appendChild(overlay);
                }
            }
        }

        // Hide loading overlay
        function hideTableLoading() {
            const overlay = document.querySelector('.table-loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }

        // Function to show import modal (placeholder)
        function showImportModal() {
            Swal.fire({
                title: '{{ __("admin.products.import.title") }}',
                html: `
                    <div class="text-left">
                        <p class="text-gray-600 mb-4">{{ __("admin.products.import.subtitle") }}</p>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 mb-2">{{ __("admin.products.import.upload") }}</p>
                            <p class="text-sm text-gray-500 mb-4">{{ __("admin.products.import.file_formats") }} • {{ __("admin.products.import.max_size") }}</p>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium">
                                {{ __("admin.products.import.download_template") }}
                            </button>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '{{ __("admin.products.import.import") }}',
                cancelButtonText: '{{ __("admin.products.import.cancel") }}',
                confirmButtonColor: '#3b82f6',
                width: '600px'
            });
        }

        // Make functions globally available
        window.applyFilters = applyFilters;
        window.clearFilters = clearFilters;
        window.showImportModal = showImportModal;
    </script>
@endsection