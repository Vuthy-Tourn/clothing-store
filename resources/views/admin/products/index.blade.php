@extends('admin.layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Product Management</h1>
        <p class="text-gray-600 text-base">Manage your fashion store's product catalog</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1 product-stat" id="totalProducts">
                        {{ $products->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Products</p>
                    <p class="text-2xl font-bold text-green-600 mt-1 product-stat" id="activeProducts">
                        {{ $products->where('status', 'active')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Categories</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1 product-stat" id="categoriesCount">
                        {{ $categories->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-tags text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Actions</p>
                    <button onclick="ProductModal.openAdd()"
                        class="btn-primary mt-2 px-4 py-2 rounded-lg text-sm font-medium">
                        Add Product
                    </button>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                    <i class="fas fa-plus text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card overflow-hidden" data-aos="fade-up" data-aos-delay="300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-bold text-gray-900">All Products</h2>
                </div>
                
                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full md:w-auto">
                    <!-- Filters Row -->
                    <div class="flex flex-wrap items-center gap-3 mb-3 md:mb-0">
                        <!-- Category Filter -->
                        <div class="relative">
                            <select id="categoryFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="relative">
                            <select id="statusFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>
                        
                        <!-- Stock Status Filter -->
                        <div class="relative">
                            <select id="stockFilter"
                                class="border border-gray-200 bg-white text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer w-40">
                                <option value="">All Stock</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-sm pointer-events-none"></i>
                        </div>
                    </div>
                    
                    <!-- Search Input -->
                    <div class="relative w-full md:w-auto">
                        <input type="text" id="searchInput" placeholder="Search products..."
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
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">ID</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Image</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Product</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Category</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Price Range</th>
                        <th class="py-4 px-6 text-left text-gray-900 font-semibold text-sm">Stock</th>
                        <th class="py-4 px-6 text-center text-gray-900 font-semibold text-sm">Status</th>
                        <th class="py-4 px-6 text-right text-gray-900 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody" class="divide-y divide-gray-200">
                    @include('admin.products.partials.table', ['products' => $products])
                </tbody>
            </table>
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
            price_range: document.getElementById('priceFilter').value
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
            if (data.success) {
                // Update table body
                document.getElementById('productsTableBody').innerHTML = data.html;
                
                // Update stats
                if (data.stats) {
                    updateStats(data.stats);
                }
                
                // Update product count in header
                document.getElementById('productCount').textContent = `(${data.count || 0})`;
                
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
        document.getElementById('priceFilter').value = '';
        
        applyFilters();
    }

    // Function to update stats
    function updateStats(stats) {
        // Update total products
        document.getElementById('totalProducts').textContent = stats.total || 0;
        
        // Update active products
        document.getElementById('activeProducts').textContent = stats.active || 0;
        
        // Update categories count
        document.getElementById('categoriesCount').textContent = stats.categories || 0;
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
        document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
        
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
        const tableContainer = document.querySelector('.overflow-x-auto').parentElement;
        if (tableContainer) {
            let overlay = tableContainer.querySelector('.table-loading-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'table-loading-overlay absolute inset-0 bg-white/80 flex items-center justify-center z-10';
                overlay.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i>
                        <p class="text-gray-700 font-medium">Loading products...</p>
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
    </script>
@endsection