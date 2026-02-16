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
                    class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl transition-all duration-300 hover:from-Ocean/90 hover:to-Ocean/70 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('admin.products.actions.add_product') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview with Gradient Design -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
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
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="activeProducts">{{ $totalActiveProducts }}</p>
                    <p class="text-green-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ __('admin.products.stats.active_percent', ['percent' => number_format(($totalActiveProducts / $totalProducts) * 100, 0)]) }}
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
                  

                    <!-- Search Input -->
                    <div class="relative w-full md:w-auto">
                        <input type="text" id="searchInput"
                            placeholder="{{ __('admin.products.table.search_placeholder') }}"
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
              <!-- Filters Row -->
                    <div class="flex flex-wrap items-center gap-3 mb-3 md:mb-0 mt-5">
                        <!-- Gender Filter -->
                        <div class="relative">
                            <select id="genderFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">{{ __('admin.categories.table.all_genders') }}</option>
                                @foreach ($availableGenders as $gender)
                                    <option value="{{ $gender }}"
                                        {{ request('gender') == $gender ? 'selected' : '' }}>
                                        {{ ucfirst($gender) }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>

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
                        <button id="clearFiltersBtn" type="button"
                            class="hidden items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
                            <i class="fas fa-times"></i>
                            {{ __('admin.products.filters.clear_all') }}
                        </button>
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

            <!-- Pagination Section -->
            <div id="paginationSection">
                @include('admin.products.partials.pagination', ['products' => $products])
            </div>

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

        /* Pagination Styles */
        nav[aria-label="Pagination"] {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-link {
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination-link:hover {
            transform: translateY(-1px);
        }

        .pagination-active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .pagination-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Shake animation for delete button */
        @keyframes shake {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(10deg);
            }

            75% {
                transform: rotate(-10deg);
            }
        }

        .group-hover\/delete:shake {
            animation: shake 0.3s ease-in-out;
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
                gender: document.getElementById('genderFilter').value,
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

        // Function to scroll to top smoothly
        function scrollToTop() {
            const tableContainer = document.querySelector('.overflow-x-auto');
            if (tableContainer) {
                tableContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Fallback: scroll to top of page
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        // Function to load a specific page
        function loadPage(url) {
            showTableLoading();

            // Get current filters
            const filters = getCurrentFilters();
            const cleanFilters = {};
            Object.keys(filters).forEach(key => {
                if (filters[key] !== '' && filters[key] !== null && filters[key] !== undefined) {
                    cleanFilters[key] = filters[key];
                }
            });

            // Get per page value
            const perPage = document.getElementById('perPageSelect')?.value || 10;
            cleanFilters.per_page = perPage;

            // Extract page number from URL or use default
            const urlObj = new URL(url, window.location.origin);
            const page = urlObj.searchParams.get('page') || 1;
            cleanFilters.page = page;

            const queryString = new URLSearchParams(cleanFilters).toString();
            const fetchUrl = `/admin/products?${queryString}&ajax=1`;

            // Update URL without reloading
            window.history.pushState({}, '', `/admin/products?${queryString}`);

            fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTableAndUI(data);
                        // Scroll to top after content is loaded
                        setTimeout(scrollToTop, 100);
                    }
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    // Fallback: reload the page
                    window.location.href = url;
                })
                .finally(() => {
                    hideTableLoading();
                });
        }

        // Function to change items per page
        function changePerPage(perPage) {
            // Update URL with per_page parameter and reset to page 1
            const filters = getCurrentFilters();
            filters.per_page = perPage;
            filters.page = 1;

            const cleanFilters = {};
            Object.keys(filters).forEach(key => {
                if (filters[key] !== '' && filters[key] !== null && filters[key] !== undefined) {
                    cleanFilters[key] = filters[key];
                }
            });

            const queryString = new URLSearchParams(cleanFilters).toString();
            const url = `/admin/products?${queryString}&ajax=1`;

            // Update URL
            window.history.pushState({}, '', `/admin/products?${queryString}`);

            // Fetch with new per page setting
            showTableLoading();
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTableAndUI(data);
                    }
                })
                .catch(error => {
                    console.error('Error changing per page:', error);
                    // Reload page
                    window.location.href = `/admin/products?${queryString}`;
                })
                .finally(() => {
                    hideTableLoading();
                });
        }

        // Function to apply all filters (main function)
        function applyFilters() {
            const filters = getCurrentFilters();
            filters.page = 1; // Reset to first page when filters change

            // Update UI
            updateFilterUI(filters);

            // Show loading
            showTableLoading();
            document.getElementById('searchLoading')?.classList.remove('hidden');

            // Build query string
            const cleanFilters = {};
            Object.keys(filters).forEach(key => {
                if (filters[key] !== '' && filters[key] !== null && filters[key] !== undefined) {
                    cleanFilters[key] = filters[key];
                }
            });

            // Add per page
            const perPage = document.getElementById('perPageSelect')?.value || 10;
            cleanFilters.per_page = perPage;

            const queryString = new URLSearchParams(cleanFilters).toString();
            const url = `/admin/products?${queryString}&ajax=1`;

            // Update URL
            window.history.pushState({}, '', `/admin/products?${queryString}`);

            // Fetch filtered products
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTableAndUI(data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    // Fallback: reload page
                    window.location.href = `/admin/products?${queryString}`;
                })
                .finally(() => {
                    document.getElementById('searchLoading')?.classList.add('hidden');
                    hideTableLoading();
                });
        }

        // Helper function to update table and UI
        function updateTableAndUI(data) {
            const tableBody = document.getElementById('productsTableBody');
            const emptyState = document.getElementById('emptyState');
            const paginationSection = document.getElementById('paginationSection');

            if (data.html && data.html.trim() !== '') {
                tableBody.innerHTML = data.html;
                tableBody.classList.remove('hidden');
                emptyState.classList.add('hidden');

                // Update pagination if exists
                if (paginationSection && data.pagination) {
                    paginationSection.innerHTML = data.pagination;
                    paginationSection.classList.remove('hidden');
                }
            } else {
                tableBody.innerHTML = '';
                tableBody.classList.add('hidden');
                emptyState.classList.remove('hidden');

                // Hide pagination when no results
                if (paginationSection) {
                    paginationSection.classList.add('hidden');
                }
            }

            // Update stats
            if (data.stats) {
                updateStats(data.stats);
            }

            // Update product count
            const productCount = document.getElementById('productCount');
            if (productCount) {
                productCount.textContent = `(${data.totalCount || 0})`;
            }

            // Reinitialize animations
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
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
                'gender': 'genderFilter',
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

            // Set per page select
            if (urlParams.has('per_page')) {
                const perPageSelect = document.getElementById('perPageSelect');
                if (perPageSelect) {
                    perPageSelect.value = urlParams.get('per_page');
                }
            }
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
            if (urlParams.toString() && urlParams.get('ajax') !== '1') {
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



        // Make functions globally available
        window.applyFilters = applyFilters;
        window.clearFilters = clearFilters;
        window.showImportModal = showImportModal;
        window.loadPage = loadPage;
        window.changePerPage = changePerPage;
    </script>
@endsection
