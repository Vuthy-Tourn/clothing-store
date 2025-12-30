<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Create a safe wrapper for SweetAlert2
    const SafeSwal = {
        fire: function(options) {
            if (typeof Swal !== 'undefined' && Swal.fire) {
                return Swal.fire(options);
            } else {
                console.warn('SweetAlert2 not available, falling back to native alerts');
                // Fallback to native browser alerts
                if (options.icon === 'warning' && options.showCancelButton) {
                    const result = confirm(options.text || options.title);
                    return Promise.resolve({
                        isConfirmed: result,
                        isDenied: false,
                        isDismissed: !result
                    });
                } else if (options.icon === 'error') {
                    alert('Error: ' + (options.text || options.title));
                } else if (options.icon === 'success') {
                    alert('Success: ' + (options.text || options.title));
                } else if (options.icon === 'info') {
                    alert('Info: ' + (options.text || options.title));
                } else {
                    alert(options.text || options.title);
                }
                return Promise.resolve({
                    isConfirmed: false,
                    isDenied: false,
                    isDismissed: true
                });
            }
        },

        showLoading: function() {
            if (typeof Swal !== 'undefined' && Swal.showLoading) {
                Swal.showLoading();
            }
        },

        close: function() {
            if (typeof Swal !== 'undefined' && Swal.close) {
                Swal.close();
            }
        }
    };

    // ========== PRODUCT FILTERING & SEARCH ==========
    const ProductFilter = {
        // State
        currentFilters: {},
        isLoading: false,
        searchTimeout: null,

        // Initialize
        init: function() {
            this.bindEvents();
            this.initializeFromURL();
            this.applyOnPageLoad();
        },

        // Bind all events
        bindEvents: function() {
            // Search input with debounce
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                // Input event with debounce
                searchInput.addEventListener('input', (e) => {
                    this.debouncedSearch(e);
                });

                // Enter key for immediate search
                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.clearDebounce();
                        this.applyFilters();
                    }

                    // Escape to clear search
                    if (e.key === 'Escape') {
                        searchInput.value = '';
                        this.applyFilters();
                    }
                });
            }

            // Clear search button
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    this.applyFilters();
                });
            }

            // Filter dropdowns
            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', () => {
                    this.applyFilters();
                });
            });

            // Clear all filters button - FIXED: Only show if it exists
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    this.clearAllFilters();
                });
            }

            // Handle browser back/forward
            window.addEventListener('popstate', () => {
                this.initializeFromURL();
                this.applyFilters();
            });
        },

        // Debounced search function
        debouncedSearch: function(e) {
            this.clearDebounce();
            this.searchTimeout = setTimeout(() => {
                this.applyFilters();
            }, 500);
        },

        // Clear debounce timeout
        clearDebounce: function() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = null;
            }
        },

        // Get all current filter values
        getCurrentFilters: function() {
            return {
                search: document.getElementById('searchInput')?.value.trim() || '',
                category: document.getElementById('categoryFilter')?.value || '',
                status: document.getElementById('statusFilter')?.value || '',
                stock_status: document.getElementById('stockFilter')?.value || '',
            };
        },

        // Check if any filter is active
        hasActiveFilters: function(filters = null) {
            const currentFilters = filters || this.getCurrentFilters();
            return Object.values(currentFilters).some(value => value !== '');
        },

        // Update filter UI state
        updateFilterUI: function(filters = null) {
            const currentFilters = filters || this.getCurrentFilters();
            const filtersActive = this.hasActiveFilters(currentFilters);

            // Show/hide clear filters button (only if it exists)
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                if (filtersActive) {
                    clearFiltersBtn.classList.remove('hidden');
                } else {
                    clearFiltersBtn.classList.add('hidden');
                }
            }

            // Update active state on filter selects
            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                if (select.value) {
                    select.classList.add('border-blue-500', 'bg-blue-50');
                    select.classList.remove('border-gray-200');
                } else {
                    select.classList.remove('border-blue-500', 'bg-blue-50');
                    select.classList.add('border-gray-200');
                }
            });

            // Update clear search button
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            const searchInput = document.getElementById('searchInput');
            if (clearSearchBtn && searchInput) {
                if (searchInput.value.trim()) {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
            }
        },

        // Apply all filters
        applyFilters: function() {
            if (this.isLoading) return;

            const filters = this.getCurrentFilters();
            this.currentFilters = filters;

            // Update UI
            this.updateFilterUI(filters);

            // Show loading
            this.showLoading();

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
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.html) {
                        // Update table body
                        const tableBody = document.getElementById('productsTableBody');
                        if (tableBody) {
                            tableBody.innerHTML = data.html;
                        }

                        // Update product count in header
                        const productCount = document.getElementById('productCount');
                        if (productCount && data.count !== undefined) {
                            productCount.textContent = `(${data.count})`;
                        }

                        // Update stats if provided
                        if (data.stats) {
                            this.updateStats(data.stats);
                        }

                        // Reinitialize AOS animations
                        if (typeof AOS !== 'undefined') {
                            AOS.refresh();
                        }

                    }
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    this.showErrorMessage('Failed to apply filters. Please try again.');

                    // Fallback: reload page normally
                    setTimeout(() => {
                        window.location.href = newUrl;
                    }, 1500);
                })
                .finally(() => {
                    this.hideLoading();
                });
        },

        // Clear all filters
        clearAllFilters: function() {
            // Reset all inputs
            const searchInput = document.getElementById('searchInput');
            if (searchInput) searchInput.value = '';

            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                select.value = '';
            });

            // Apply empty filters
            this.applyFilters();

            // Show message
            this.showFilterMessage('All filters cleared');
        },

        // Initialize filters from URL
        initializeFromURL: function() {
            const urlParams = new URLSearchParams(window.location.search);

            // Set search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput && urlParams.has('search')) {
                searchInput.value = urlParams.get('search');
            }

            // Set filter dropdowns
            const filterMap = {
                'category': 'categoryFilter',
                'status': 'statusFilter',
                'stock_status': 'stockFilter'
            };

            Object.entries(filterMap).forEach(([param, elementId]) => {
                if (urlParams.has(param)) {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.value = urlParams.get(param);
                    }
                }
            });
        },

        // Apply filters on page load if URL has parameters
        applyOnPageLoad: function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.toString()) {
                // Small delay to ensure everything is loaded
                setTimeout(() => {
                    if (this.hasActiveFilters()) {
                        this.applyFilters();
                    }
                }, 300);
            }
        },

        // Update stats
        updateStats: function(stats) {
            // Update total products
            const totalProducts = document.getElementById('totalProducts');
            if (totalProducts && stats.total !== undefined) {
                totalProducts.textContent = stats.total;
            }

            // Update active products
            const activeProducts = document.getElementById('activeProducts');
            if (activeProducts && stats.active !== undefined) {
                activeProducts.textContent = stats.active;
            }

            // Update categories count
            const categoriesCount = document.getElementById('categoriesCount');
            if (categoriesCount && stats.categories !== undefined) {
                categoriesCount.textContent = stats.categories;
            }
        },

        // Show loading state
        showLoading: function() {
            this.isLoading = true;

            // Show search loading spinner
            const searchLoading = document.getElementById('searchLoading');
            if (searchLoading) {
                searchLoading.classList.remove('hidden');
            }

            // Show table loading overlay
            const tableContainer = document.querySelector('.overflow-x-auto')?.parentElement;
            if (tableContainer) {
                let overlay = tableContainer.querySelector('.table-loading-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className =
                        'table-loading-overlay absolute inset-0 bg-white/80 flex items-center justify-center z-10 backdrop-blur-sm';
                    overlay.innerHTML = `
                <div class="text-center p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-gray-800 font-medium">Loading products...</p>
                    <p class="text-gray-500 text-sm mt-2">Applying filters</p>
                </div>
            `;
                    tableContainer.classList.add('relative');
                    tableContainer.appendChild(overlay);
                }
            }
        },

        // Hide loading state
        hideLoading: function() {
            this.isLoading = false;

            // Hide search loading spinner
            const searchLoading = document.getElementById('searchLoading');
            if (searchLoading) {
                searchLoading.classList.add('hidden');
            }

            // Remove table loading overlay
            const overlay = document.querySelector('.table-loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        },

        // Show filter message
        showFilterMessage: function(message) {
            // Remove any existing message
            const existingMessage = document.querySelector('.filter-message');
            if (existingMessage) {
                existingMessage.remove();
            }

            // Create message element
            const messageElement = document.createElement('div');
            messageElement.className =
                'filter-message fixed top-20 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slideIn';
            messageElement.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;

            document.body.appendChild(messageElement);

            // Remove after 3 seconds
            setTimeout(() => {
                messageElement.classList.add('animate-slideOut');
                setTimeout(() => {
                    if (messageElement.parentNode) {
                        messageElement.parentNode.removeChild(messageElement);
                    }
                }, 300);
            }, 3000);
        },

        // Show error message
        showErrorMessage: function(message) {
            SafeSwal.fire({
                icon: 'error',
                title: 'Filter Error',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    };

    // ========== INITIALIZATION ==========
    document.addEventListener('DOMContentLoaded', function() {

        // Initialize Product Filter
        ProductFilter.init();

        // Make global functions available
        window.applyFilters = () => ProductFilter.applyFilters();
        window.clearFilters = () => ProductFilter.clearAllFilters();
    });


    function handleDiscountTypeChange(selectElement) {
        // Find the closest parent container
        const discountContainer = selectElement.closest('.space-y-4') ||
            selectElement.closest('.grid').parentElement;

        if (!discountContainer) return;

        // Find all related discount fields
        const discountValueField = discountContainer.querySelector('.discount-value-field');
        const dateFields = discountContainer.querySelectorAll('[name$="discount_start"], [name$="discount_end"]');
        const discountPrefix = discountValueField ? discountValueField.querySelector('.discount-prefix') : null;

        if (selectElement.value) {
            // Show discount value field
            if (discountValueField) {
                discountValueField.style.display = 'block';
                if (discountPrefix) {
                    discountPrefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
                }
            }

            // Show date fields
            dateFields.forEach(field => {
                field.closest('div').style.display = 'block';
            });
        } else {
            // Hide all discount fields
            if (discountValueField) {
                discountValueField.style.display = 'none';
            }

            dateFields.forEach(field => {
                field.closest('div').style.display = 'none';
            });

            // Clear values
            const valueInput = discountValueField ? discountValueField.querySelector('input[type="number"]') : null;
            if (valueInput) {
                valueInput.value = '';
            }
            dateFields.forEach(field => field.value = '');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {

        // Initialize product discount fields
        document.querySelectorAll('.discount-type-select').forEach(select => {
            // Trigger initial state
            setTimeout(() => handleDiscountTypeChange(select), 100);

            // Add event listener
            select.addEventListener('change', function() {
                handleDiscountTypeChange(this);
            });
        });

        // Initialize variant discount fields
        document.querySelectorAll('.variant-discount-type').forEach(select => {
            setTimeout(() => handleVariantDiscountTypeChange(select), 100);
        });
    });

    const ProductModal = {
        openAdd: function() {
            document.getElementById('addProductModal').classList.remove('hidden');
        },
        closeAdd: function() {
            document.getElementById('addProductModal').classList.add('hidden');
        },
        openEdit: async function(productId) {
            try {
                const modal = document.getElementById('editProductModal');
                modal.classList.remove('hidden');

                // Reset containers and global variables
                document.getElementById('newImagesContainer').innerHTML = '';
                document.getElementById('deletedImagesInput').value = '';
                window.deletedImages = [];

                // Show loading using SafeSwal
                const submitBtn = modal.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
                submitBtn.disabled = true;

                // Fetch product data
                const response = await fetch(`/admin/products/${productId}/edit`);
                const data = await response.json();

                if (data.success) {
                    const product = data.data;

                    // Populate basic product info
                    document.getElementById('editProductId').value = product.id;
                    document.getElementById('editName').value = product.name;
                    document.getElementById('editCategoryId').value = product.category_id;
                    document.getElementById('editBrand').value = product.brand;
                    document.getElementById('editMaterial').value = product.material;
                    document.getElementById('editDescription').value = product.description;
                    document.getElementById('editStatus').value = product.status;
                    document.getElementById('editIsFeatured').checked = product.is_featured;
                    document.getElementById('editIsNew').checked = product.is_new;

                    // Set form action
                    document.getElementById('editProductForm').action = `/admin/products/${product.id}`;

                    // Populate variants
                    const variantsContainer = document.getElementById('editVariantsContainer');
                    variantsContainer.innerHTML = '';

                    if (product.variants && product.variants.length > 0) {
                        product.variants.forEach((variant, index) => {
                            addEditVariantRow(variant, index);
                        });
                    } else {
                        addEditVariantRow(null, 0);
                    }

                    // Populate product discount fields
                    if (product.discount_type) {
                        document.getElementById('editDiscountType').value = product.discount_type;
                        // Manually trigger the change to show the field
                        handleDiscountTypeChange(document.getElementById('editDiscountType'));
                    }
                    document.getElementById('editDiscountValue').value = product.discount_value || '';

                    // Format dates for datetime-local input
                    const discountStart = product.discount_start ?
                        new Date(product.discount_start).toISOString().slice(0, 16) :
                        '';
                    const discountEnd = product.discount_end ?
                        new Date(product.discount_end).toISOString().slice(0, 16) :
                        '';

                    document.getElementById('editDiscountStart').value = discountStart;
                    document.getElementById('editDiscountEnd').value = discountEnd;

                    // Populate current images
                    const currentImagesContainer = document.getElementById('currentImages');
                    currentImagesContainer.innerHTML = '';

                    if (product.images && product.images.length > 0) {
                        product.images.forEach((image, index) => {
                            const imageHtml = `
                        <div class="border border-gray-200 rounded-lg p-3 mb-3 image-container" id="image-${image.id}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <img src="/storage/${image.image_path}" 
                                         class="w-16 h-16 object-cover rounded"
                                         onerror="this.src='/storage/products/placeholder.jpg'">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Image ${index + 1}</p>
                                        <p class="text-xs text-gray-500">${image.is_primary ? 'Primary Image' : 'Additional Image'}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="primary_image" 
                                               value="${image.id}" 
                                               ${image.is_primary ? 'checked' : ''} 
                                               class="rounded">
                                        <span class="ml-1 text-xs text-gray-700">Primary</span>
                                    </label>
                                    <button type="button" 
                                            onclick="markImageForDeletion(${image.id})" 
                                            class="text-red-600 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                            currentImagesContainer.innerHTML += imageHtml;
                        });
                    } else {
                        currentImagesContainer.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-image text-2xl mb-2"></i>
                        <p>No images found</p>
                    </div>
                `;
                    }

                    // Restore button
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;

                } else {
                    SafeSwal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to load product data'
                    });
                    ProductModal.closeEdit();
                }

            } catch (error) {
                console.error('Error loading product:', error);
                SafeSwal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load product data. Please try again.'
                });
                ProductModal.closeEdit();
            }
        },
        closeEdit: function() {
            document.getElementById('editProductModal').classList.add('hidden');
            document.getElementById('editProductForm').reset();
            window.deletedImages = [];
        }
    };

    const DeleteModal = {
        open: function(type, id, name) {
            // Use SafeSwal instead of Swal
            SafeSwal.fire({
                title: '{{ __('admin.products.delete.title') }}',
                text: `You are about to delete "${name}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('admin.categories.delete.button_no_products') }}',
                cancelButtonText: '{{ __('admin.products.delete.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/products/${id}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);

                    // Show loading using SafeSwal
                    SafeSwal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the product',
                        allowOutsideClick: false,
                        didOpen: () => {
                            if (typeof Swal !== 'undefined' && Swal.showLoading) {
                                Swal.showLoading();
                            }
                        }
                    });

                    // Submit form
                    form.submit();
                }
            });
        },
        close: function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    };

    // Modal close on background click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'addProductModal') ProductModal.closeAdd();
        if (e.target.id === 'editProductModal') ProductModal.closeEdit();
        if (e.target.id === 'deleteModal') DeleteModal.close();
    });

    // Variant management
    let variantIndex = 1;
    let imageIndex = 1;

    function addVariantRow() {
        const container = document.getElementById('variantsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'variant-row border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4';
        newRow.innerHTML = `
<div class="flex justify-between items-center mb-3">
    <span class="text-sm font-medium text-gray-900">Variant ${variantIndex + 1}</span>
    <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-700 text-sm">
        <i class="fas fa-times"></i>
    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Size  <span class="ml-1 text-red-500">*</span></label>
        <select name="variants[${variantIndex}][size]" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
            <option value="">Select Size</option>
            ${['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'].map(size => 
                `<option value="${size}">${size}</option>`
            ).join('')}
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Color  <span class="ml-1 text-red-500">*</span></label>
        <input type="text" name="variants[${variantIndex}][color]" required 
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="e.g., Red, Black">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Price ($)  <span class="ml-1 text-red-500">*</span></label>
        <input type="number" step="0.01" name="variants[${variantIndex}][price]" required
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="0.00">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Stock  <span class="ml-1 text-red-500">*</span></label>
        <input type="number" name="variants[${variantIndex}][stock]" min="0" required
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="0">
    </div>
</div>

<!-- Variant Discount Section -->
<div class="variant-discount-section mb-4 p-4 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 rounded-lg border border-blue-200/50 shadow-sm hover:shadow-md transition-all duration-200">
<div class="flex items-start justify-between mb-4">
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 mr-2 text-xs">
                <i class="fas fa-tag"></i>
            </span>
            Variant Discount Configuration
        </h4>
        <p class="text-xs text-gray-600 mb-3">
            Configure specific discounts for this variant. Will override product-level discounts.
        </p>
    </div>
    <div class="flex items-center space-x-2">
        <span class="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-medium border border-blue-200">
            Variant #<span class="font-bold">${variantIndex + 1}</span>
        </span>
        <button type="button" onclick="calculateVariantDiscount(${variantIndex})" 
                class="text-xs px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center"
                title="Calculate discount">
            <i class="fas fa-calculator mr-1.5"></i> Calculate
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    <!-- Discount Type -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label class="block text-xs font-semibold text-gray-900">
                <span class="flex items-center">
                    <i class="fas fa-percentage mr-1.5 text-gray-500 text-xs"></i>
                    Discount Type
                </span>
            </label>
            <div class="text-xs text-gray-500 cursor-help" 
                 data-tooltip="Select discount calculation method">
                <i class="fas fa-question-circle"></i>
            </div>
        </div>
        <div class="relative group">
            <select name="variants[${variantIndex}][discount_type]"
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer edit-variant-discount-type"
                    onchange="handleEditVariantDiscountTypeChange(this, ${variantIndex})">
                <option value="">No Discount</option>
                <option value="percentage">Percentage Discount (%)</option>
                <option value="fixed">Fixed Amount Discount ($)</option>
            </select>
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>

    <!-- Discount Value (Conditional) -->
    <div class="space-y-2 edit-variant-discount-value-field" style="display: none;">
        <div class="flex items-center justify-between">
            <label class="block text-xs font-semibold text-gray-900">
                <span class="flex items-center">
                    <i class="fas fa-money-bill-wave mr-1.5 text-gray-500 text-xs"></i>
                    Discount Value
                </span>
            </label>
            <div class="text-xs text-gray-500 cursor-help" 
                 data-tooltip="Enter discount amount">
                <i class="fas fa-question-circle"></i>
            </div>
        </div>
        <div class="relative group">
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium edit-variant-discount-prefix">
                <i class="fas fa-dollar-sign text-xs"></i>
            </div>
            <input type="number" step="0.01" name="variants[${variantIndex}][discount_value]"
                   class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 edit-variant-discount-value"
                   placeholder="0.00"
                   min="0"
                   max="100"
                   oninput="updateDiscountPreview(${variantIndex})">
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                <i class="fas fa-edit"></i>
            </div>
        </div>
    </div>

    <!-- Discount Status -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label class="block text-xs font-semibold text-gray-900">
                <span class="flex items-center">
                    <i class="fas fa-toggle-on mr-1.5 text-gray-500 text-xs"></i>
                    Discount Status
                </span>
            </label>
            <div class="text-xs text-gray-500 cursor-help" 
                 data-tooltip="Enable/disable discount for this variant">
                <i class="fas fa-question-circle"></i>
            </div>
        </div>
        <div class="flex items-center space-x-4 mt-3">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="variants[${variantIndex}][has_discount]" value="1"
                       class="sr-only peer edit-variant-has-discount"
                       onchange="toggleDiscountActivation(this, ${variantIndex})">
                <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                <div class="ml-3 flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-900 peer-checked:text-green-700 transition-colors">Active</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" id="discountStatusBadge-${variantIndex}">
                        OFF
                    </span>
                </div>
            </label>
        </div>
    </div>
</div>

<!-- Discount Period -->
<div class="mt-6 pt-4 border-t border-gray-200/50">
    <div class="flex items-center mb-4">
        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
        <h5 class="text-xs font-semibold text-gray-900">Discount Period (Optional)</h5>
        <span class="ml-2 text-xs text-gray-500">Leave empty for indefinite discount</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Start Date -->
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-900 flex items-center">
                <i class="far fa-calendar-plus mr-2 text-gray-500"></i>
                Start Date & Time
            </label>
            <div class="relative group">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <i class="far fa-clock"></i>
                </div>
                <input type="datetime-local" name="variants[${variantIndex}][discount_start]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 edit-discount-start"
                       onchange="updateDiscountPeriod(${variantIndex})">
            </div>
        </div>
        
        <!-- End Date -->
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-900 flex items-center">
                <i class="far fa-calendar-minus mr-2 text-gray-500"></i>
                End Date & Time
            </label>
            <div class="relative group">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <i class="far fa-clock"></i>
                </div>
                <input type="datetime-local" name="variants[${variantIndex}][discount_end]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 edit-discount-end"
                       onchange="updateDiscountPeriod(${variantIndex})">
            </div>
        </div>
    </div>
</div>

<!-- Discount Preview Card -->
<div class="mt-6">
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center mr-3">
                    <i class="fas fa-chart-line text-white text-sm"></i>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-900">Price Breakdown</h5>
                    <p class="text-xs text-gray-600">Based on current configuration</p>
                </div>
            </div>
            <div id="discountActiveStatus-${variantIndex}" 
                 class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
                <i class="fas fa-info-circle mr-1"></i> Configure
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Original Price -->
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                    <i class="fas fa-money-bill mr-1"></i> Original Price
                </div>
                <div class="text-lg font-bold text-gray-900 original-price-display" id="originalPrice-${variantIndex}">
                    $0.00
                </div>
            </div>
            
            <!-- Discounted Price -->
            <div class="text-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                    <i class="fas fa-tags mr-1"></i> Discounted Price
                </div>
                <div class="text-xl font-bold text-green-700 discounted-price-display" id="discountedPrice-${variantIndex}">
                    $0.00
                </div>
                <div class="text-xs text-green-600 mt-1 discount-type-display" id="discountType-${variantIndex}">
                    No discount applied
                </div>
            </div>
            
            <!-- You Save -->
            <div class="text-center p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100">
                <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                    <i class="fas fa-piggy-bank mr-1"></i> You Save
                </div>
                <div class="text-lg font-bold text-red-700 savings-display" id="savings-${variantIndex}">
                    $0.00
                </div>
                <div class="text-xs text-red-600 mt-1" id="savingsPercentage-${variantIndex}">
                    0% off
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-history mr-2"></i>
                    <span>Discount Period: </span>
                    <span class="font-medium ml-1 text-gray-900 period-display" id="periodDisplay-${variantIndex}">
                        Not set
                    </span>
                </div>
                <div class="text-xs">
                    <span class="text-gray-600">Updated: </span>
                    <span class="font-medium text-gray-900" id="lastUpdated-${variantIndex}">
                        Just now
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Validation Message -->
<div class="mt-3 p-3 bg-yellow-50 border border-yellow-100 rounded-lg hidden" id="validationMessage-${variantIndex}">
    <div class="flex items-start">
        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
        <div class="flex-1">
            <p class="text-xs font-medium text-yellow-800" id="validationText-${variantIndex}"></p>
        </div>
    </div>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Cost Price ($)</label>
        <input type="number" step="0.01" name="variants[${variantIndex}][cost_price]"
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Weight (kg)</label>
        <input type="number" step="0.01" name="variants[${variantIndex}][weight]"
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<div class="mt-3 pt-3 border-t border-gray-200">
    <label class="inline-flex items-center">
        <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1" checked class="rounded border-gray-300">
        <span class="ml-2 text-sm text-gray-700">Active</span>
    </label>
</div>
`;
        container.appendChild(newRow);
        variantIndex++;
    }

    function removeRow(button) {
        SafeSwal.fire({
            title: 'Remove Variant?',
            text: 'Are you sure you want to remove this variant?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.variant-row').remove();
            }
        });
    }

    function removeVariant(button) {
        SafeSwal.fire({
            title: 'Remove Variant?',
            text: 'Are you sure you want to remove this variant?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const variantRow = button.closest('.variant-row');
                const variantIdInput = variantRow.querySelector('input[name$="[id]"]');

                if (variantIdInput && variantIdInput.value) {
                    const form = document.getElementById('editProductForm');
                    let deletedVariantsInput = document.getElementById('deletedVariants');

                    if (!deletedVariantsInput) {
                        deletedVariantsInput = document.createElement('input');
                        deletedVariantsInput.type = 'hidden';
                        deletedVariantsInput.name = 'deleted_variants';
                        deletedVariantsInput.id = 'deletedVariants';
                        form.appendChild(deletedVariantsInput);
                    }

                    const currentDeleted = deletedVariantsInput.value ? JSON.parse(deletedVariantsInput.value) :
                        [];
                    if (!currentDeleted.includes(variantIdInput.value)) {
                        currentDeleted.push(variantIdInput.value);
                        deletedVariantsInput.value = JSON.stringify(currentDeleted);
                    }
                }

                variantRow.remove();
                SafeSwal.fire('Removed!', 'Variant has been removed.', 'success');
            }
        });
    }

    function addEditVariantRow(variantData = null, index = null) {
        const container = document.getElementById('editVariantsContainer');
        const variantId = variantData ? variantData.id : '';
        const currentIndex = index !== null ? index : container.children.length;

        // Format discount dates for datetime-local input
        const discountStart = variantData && variantData.discount_start ?
            new Date(variantData.discount_start).toISOString().slice(0, 16) :
            '';
        const discountEnd = variantData && variantData.discount_end ?
            new Date(variantData.discount_end).toISOString().slice(0, 16) :
            '';

        const newRow = document.createElement('div');
        newRow.className = 'variant-row border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4';
        newRow.innerHTML = `
<div class="flex justify-between items-center mb-3">
    <span class="text-sm font-medium text-gray-900">Variant ${currentIndex + 1}</span>
    ${variantId ? `<input type="hidden" name="variants[${currentIndex}][id]" value="${variantId}">` : ''}
    <button type="button" onclick="removeVariant(this)" class="text-red-600 hover:text-red-700 text-sm">
        <i class="fas fa-times"></i> Remove
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Size  <span class="ml-1 text-red-500">*</span></label>
        <select name="variants[${currentIndex}][size]" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
            <option value="">Select Size</option>
            ${['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'].map(size => 
                `<option value="${size}" ${variantData && variantData.size === size ? 'selected' : ''}>${size}</option>`
            ).join('')}
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Color  <span class="ml-1 text-red-500">*</span></label>
        <input type="text" name="variants[${currentIndex}][color]" value="${variantData ? variantData.color : ''}" required 
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="e.g., Red, Black">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Price ($)  <span class="ml-1 text-red-500">*</span></label>
        <input type="number" step="0.01" name="variants[${currentIndex}][price]" value="${variantData ? variantData.price : ''}" required
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="0.00">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Stock  <span class="ml-1 text-red-500">*</span></label>
        <input type="number" name="variants[${currentIndex}][stock]" value="${variantData ? variantData.stock : ''}" min="0" required
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="0">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Stock Alert</label>
        <input type="number" name="variants[${currentIndex}][stock_alert]" value="${variantData ? variantData.stock_alert : 10}" min="0"
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Weight (kg)</label>
        <input type="number" step="0.01" name="variants[${currentIndex}][weight]" value="${variantData ? variantData.weight : ''}"
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Color Code</label>
        <input type="color" name="variants[${currentIndex}][color_code]" value="${variantData && variantData.color_code ? variantData.color_code : '#000000'}"
            class="w-full h-10 border border-gray-200 bg-white text-gray-900 rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">SKU</label>
        <input type="text" name="variants[${currentIndex}][sku]" value="${variantData ? variantData.sku : ''}"
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<!-- EXACTLY LIKE ADD MODAL -->
<!-- Variant-Level Discount Fields -->
<div class="variant-discount-section mb-4 p-3 bg-gray-100 rounded-lg">
    <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
        <i class="fas fa-tag mr-2 text-sm"></i> Variant Discount (Optional)
    </h4>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-900 mb-2">
                <span class="inline-flex items-center">
                    Discount Type
                    <span class="ml-1 text-red-500">*</span>
                </span>
            </label>
            <select name="variants[${currentIndex}][discount_type]"
                class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all variant-discount-type"
                onchange="handleVariantDiscountTypeChangeEdit(this, ${currentIndex})">
                <option value="">Select Type</option>
                <option value="percentage" ${variantData && variantData.discount_type === 'percentage' ? 'selected' : ''}>Percentage (%)</option>
                <option value="fixed" ${variantData && variantData.discount_type === 'fixed' ? 'selected' : ''}>Fixed Amount ($)</option>
            </select>
        </div>

        <div class="variant-discount-value-field" style="${variantData && variantData.discount_type ? 'display: block;' : 'display: none;'}">
            <label class="block text-xs font-semibold text-gray-900 mb-2">
                Discount Value
                <span class="ml-1 text-red-500">*</span>
            </label>
            <div class="relative group">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-700 variant-discount-prefix font-medium">
                    ${variantData && variantData.discount_type === 'fixed' ? '$' : '%'}
                </div>
                <input type="number" step="0.01" name="variants[${currentIndex}][discount_value]" value="${variantData ? variantData.discount_value : ''}"
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="0.00" min="0" max="100"
                    oninput="updateDiscountPreviewEdit(${currentIndex})">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fas fa-tag"></i>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-900 mb-2">
                Discount Status
            </label>
            <div class="flex items-center space-x-3 mt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="variants[${currentIndex}][has_discount]" value="1" 
                           ${variantData && variantData.has_discount ? 'checked' : ''}
                           class="sr-only peer variant-has-discount"
                           onchange="toggleDiscountStatusEdit(this, ${currentIndex})">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Active</span>
                </label>
                <div class="tooltip" data-tip="Enable to activate discount">
                    <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-900 mb-2">
                    <span class="inline-flex items-center">
                        <i class="far fa-calendar-start mr-2 text-gray-600"></i>
                        Start Date & Time
                    </span>
                </label>
                <div class="relative">
                    <input type="datetime-local" name="variants[${currentIndex}][discount_start]" value="${discountStart}"
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10 transition-all"
                        onchange="updateDiscountPeriodEdit(${currentIndex})">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                        <i class="far fa-clock"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-900 mb-2">
                    <span class="inline-flex items-center">
                        <i class="far fa-calendar-times mr-2 text-gray-600"></i>
                        End Date & Time
                    </span>
                </label>
                <div class="relative">
                    <input type="datetime-local" name="variants[${currentIndex}][discount_end]" value="${discountEnd}"
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10 transition-all"
                        onchange="updateDiscountPeriodEdit(${currentIndex})">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                        <i class="far fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Preview -->
        <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-lg discount-preview" style="${variantData && variantData.discount_type ? 'display: block;' : 'display: none;'}">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-calculator text-blue-600 mr-2"></i>
                    <span class="text-sm font-medium text-blue-900">Discount Preview:</span>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-blue-700 discount-preview-value" id="discountPreviewValueEdit-${currentIndex}">
                        ${variantData && variantData.price ? 
                            variantData.discount_type === 'percentage' && variantData.discount_value ?
                            `$${(variantData.price - (variantData.price * variantData.discount_value / 100)).toFixed(2)}` :
                            variantData.discount_type === 'fixed' && variantData.discount_value ?
                            `$${(variantData.price - variantData.discount_value).toFixed(2)}` :
                            `$${parseFloat(variantData.price || 0).toFixed(2)}`
                            : '$0.00'}
                    </div>
                    <div class="text-xs text-blue-600 discount-preview-type" id="discountPreviewTypeEdit-${currentIndex}">
                        ${variantData && variantData.discount_type === 'percentage' && variantData.discount_value ? 
                            `${variantData.discount_value}% off` :
                            variantData && variantData.discount_type === 'fixed' && variantData.discount_value ?
                            `$${parseFloat(variantData.discount_value).toFixed(2)} off` :
                            ''}
                    </div>
                </div>
            </div>
            <div class="mt-2 text-xs text-blue-800">
                <span class="font-medium">Period:</span>
                <span class="discount-period text-blue-900" id="discountPeriodEdit-${currentIndex}">
                    ${discountStart && discountEnd ? 
                        `${new Date(discountStart).toLocaleDateString()} - ${new Date(discountEnd).toLocaleDateString()}` :
                        discountStart ? `From ${new Date(discountStart).toLocaleDateString()}` :
                        discountEnd ? `Until ${new Date(discountEnd).toLocaleDateString()}` :
                        'Not set'}
                </span>
            </div>
        </div>
    </div>
</div>
`;

        container.appendChild(newRow);

        // Initialize preview calculation if there's discount data
        if (variantData && variantData.discount_type) {
            updateDiscountPreviewEdit(currentIndex);
        }
    }

    // Image deletion handling
    function markImageForDeletion(imageId) {
        SafeSwal.fire({
            title: 'Delete Image?',
            text: 'Are you sure you want to delete this image? This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Initialize deleted images array if not exists
                if (!window.deletedImages) window.deletedImages = [];

                // Add to deleted images array if not already added
                if (!window.deletedImages.includes(imageId)) {
                    window.deletedImages.push(imageId);
                }

                // Update hidden input
                const deletedInput = document.getElementById('deletedImagesInput');
                if (deletedInput) {
                    deletedInput.value = JSON.stringify(window.deletedImages);
                }

                // Find and mark the image row
                const imageRow = document.getElementById(`image-${imageId}`);
                if (imageRow) {
                    // Add deletion class
                    imageRow.classList.add('image-deleted');

                    // Disable the primary radio button
                    const radioBtn = imageRow.querySelector('input[type="radio"]');
                    if (radioBtn) {
                        radioBtn.disabled = true;
                        radioBtn.checked = false;
                    }

                    // Change delete button to restore button
                    const deleteBtn = imageRow.querySelector('button');
                    if (deleteBtn) {
                        deleteBtn.innerHTML = '<i class="fas fa-undo"></i>';
                        deleteBtn.classList.remove('text-red-600', 'hover:text-red-700');
                        deleteBtn.classList.add('text-blue-600', 'hover:text-blue-700');
                        deleteBtn.setAttribute('onclick', `restoreImage(${imageId})`);
                        deleteBtn.title = 'Restore Image';
                    }
                }

                SafeSwal.fire('Marked for deletion!', 'Image will be deleted when you save changes.',
                    'success');
            }
        });
    }

    // Restore image function
    function restoreImage(imageId) {
        // Remove from deleted images array
        if (window.deletedImages) {
            const index = window.deletedImages.indexOf(imageId);
            if (index > -1) {
                window.deletedImages.splice(index, 1);
            }

            // Update hidden input
            const deletedInput = document.getElementById('deletedImagesInput');
            if (deletedInput) {
                deletedInput.value = JSON.stringify(window.deletedImages);
            }
        }

        // Find and restore the image row
        const imageRow = document.getElementById(`image-${imageId}`);
        if (imageRow) {
            // Remove deletion class
            imageRow.classList.remove('image-deleted');

            // Enable the primary radio button
            const radioBtn = imageRow.querySelector('input[type="radio"]');
            if (radioBtn) {
                radioBtn.disabled = false;
            }

            // Change restore button back to delete button
            const restoreBtn = imageRow.querySelector('button');
            if (restoreBtn) {
                restoreBtn.innerHTML = '<i class="fas fa-trash"></i>';
                restoreBtn.classList.remove('text-blue-600', 'hover:text-blue-700');
                restoreBtn.classList.add('text-red-600', 'hover:text-red-700');
                restoreBtn.setAttribute('onclick', `markImageForDeletion(${imageId})`);
                restoreBtn.title = 'Delete Image';
            }
        }

        SafeSwal.fire('Restored!', 'Image has been restored.', 'success');
    }

    // Add new image row in EDIT modal - Matching ImageManager style
    function addNewImageRow() {
        const container = document.getElementById('newImagesContainer');
        const imageCount = container.querySelectorAll('.new-image-row').length;

        // Check if maximum images reached (you can set your own limit)
        const maxNewImages = 10; // Set your desired limit
        if (imageCount >= maxNewImages) {
            showNotification(`Maximum of ${maxNewImages} new images allowed`, 'error');
            return false;
        }

        // Generate unique ID for this image row
        const rowId = `new-image-row-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        const rowIndex = imageCount + 1;

        const newRow = document.createElement('div');
        newRow.id = rowId;
        newRow.className =
            'new-image-row additional-image-row border border-gray-200 rounded-lg p-4 mb-4 hover:border-blue-300 transition-all duration-200';
        newRow.dataset.index = rowIndex;

        newRow.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <div class="flex items-center space-x-3">
                <span class="text-sm font-semibold text-gray-900">New Image</span>
                <span class="text-xs px-2 py-1 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 rounded-full font-medium">
                    #${rowIndex}
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="removeNewImageRow(this)" 
                        class="text-red-600 hover:text-red-700 text-sm p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="space-y-4">
            <!-- File Upload Area with Enhanced Preview -->
            <div class="file-upload-area relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-blue-400 transition-all duration-200 bg-gradient-to-br from-white to-gray-50/50">
                <input type="file" 
                       id="new-image-input-${rowIndex}"
                       name="new_images[${rowIndex}][image]" 
                       accept="image/*" 
                       class="file-input absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                       onchange="handleNewImageUpload(this, ${rowIndex})"
                       required>
                
                <div class="upload-content text-center py-4">
                    <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center shadow-inner">
                        <i class="fas fa-cloud-upload-alt text-purple-500 text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 mb-2">Click to upload new image</p>
         <p class="text-gray-500 text-xs mb-1">Supports: ${ImageManager.allowedTypes.map(t => t.split('/')[1].toUpperCase()).join(', ')}</p>
                        <p class="text-gray-400 text-xs">Maximum file size: 5MB</p>
                </div>
                
                <!-- Enhanced Preview Container -->
                <div id="new-preview-${rowIndex}" class="preview-container hidden">
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="relative flex-shrink-0">
                                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 shadow-sm">
                                    <img class="preview-image w-full h-full object-cover"
                                         src="" 
                                         alt="Preview"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjRjFGNUZGIi8+CjxwYXRoIGQ9Ik01MCA0MEM1MCA0NC40MTgzIDQ2LjQxODMgNDggNDIgNDhDMzcuNTgxNyA0OCAzNCA0NC40MTgzIDM0IDQwQzM0IDM1LjU4MTcgMzcuNTgxNyAzMiA0MiAzMkM0Ni40MTgzIDMyIDUwIDM1LjU4MTcgNTAgNDBaIiBmaWxsPSIjRDhEQ0VGIi8+CjxwYXRoIGQ9Ik02NS4wMDAxIDU1Ljk5OTlWNTIuNDk5OUM2NS4wMDAxIDQ4LjM5OTkgNTcuNTAwMSA0NS45OTk5IDUwLjAwMDEgNDUuOTk5OUM0Mi41MDAxIDQ1Ljk5OTkgMzUuMDAwMSA0OC4zOTk5IDM1LjAwMDEgNTIuNDk5OVY1NS45OTk5SDY1LjAwMDFaIiBmaWxsPSIjRDhEQ0VGIi8+Cjwvc3ZnPgo=';">
                                </div>
                                <div class="absolute -top-2 -right-2">
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium flex items-center">
                                        <i class="fas fa-check mr-1 text-xs"></i> New
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="mb-3">
                                    <p class="file-name text-sm font-semibold text-gray-900 truncate"></p>
                                    <p class="file-size text-xs text-gray-500 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Fields Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Alt Text Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-900 flex items-center">
                        <i class="fas fa-align-left mr-2 text-gray-500 text-sm"></i>
                        Alt Text
                        <span class="ml-1 text-gray-400 text-xs">(optional)</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="new_images[${rowIndex}][alt_text]" 
                               id="new-alt-text-${rowIndex}"
                               class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10"
                               placeholder="Describe the image"
                               maxlength="125"
                               oninput="validateNewAltText(this, ${rowIndex})">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <div class="alt-text-counter text-xs text-gray-400" id="new-alt-counter-${rowIndex}">
                                0/125
                            </div>
                        </div>
                    </div>
                    <div class="alt-text-feedback hidden text-xs mt-1" id="new-alt-feedback-${rowIndex}"></div>
                    <p class="text-xs text-gray-500">Helps with SEO and accessibility. Recommended: 5-15 words.</p>
                </div>
                
                <!-- Sort Order Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-900 flex items-center">
                        <i class="fas fa-sort-numeric-down mr-2 text-gray-500 text-sm"></i>
                        Display Order
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <input type="number" 
                               name="new_images[${rowIndex}][sort_order]" 
                               id="new-sort-order-${rowIndex}"
                               value="${rowIndex}"
                               min="1"
                               max="100"
                               class="sort-input w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               onchange="updateNewSortOrders()">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-sort-amount-down"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Lower numbers appear first in galleries</p>
                </div>
            </div>
        </div>
        
        <!-- Hidden Fields -->
        <input type="hidden" name="new_images[${rowIndex}][is_primary]" value="0">
        <input type="hidden" name="new_images[${rowIndex}][temp_id]" value="new_temp_${Date.now()}_${rowIndex}">
    `;

        // Add animation class
        newRow.classList.add('animate-fadeIn');

        container.appendChild(newRow);

        // Update new images count
        updateNewImagesCount();

        // Show success message
        showNotification('New image slot added successfully', 'success');

        return true;
    }

    // Helper functions for new image management
    function handleNewImageUpload(input, index) {
        const file = input.files[0];
        if (!file) return;

        // Validate file
        if (!ImageManager.validateFile(file, index)) {
            input.value = '';
            return;
        }

        // Show preview
        showNewImagePreview(file, index);

        // Extract and display metadata
        extractNewImageMetadata(file, index);
    }

    function showNewImagePreview(file, index) {
        const previewContainer = document.getElementById(`new-preview-${index}`);
        const uploadArea = previewContainer.closest('.file-upload-area');
        const uploadContent = uploadArea.querySelector('.upload-content');
        const previewImage = previewContainer.querySelector('.preview-image');
        const fileName = previewContainer.querySelector('.file-name');
        const fileSize = previewContainer.querySelector('.file-size');

        // Create object URL for preview
        const objectUrl = URL.createObjectURL(file);

        // Load image to get dimensions
        const img = new Image();
        img.onload = function() {
            // Update preview
            previewImage.src = objectUrl;
            previewContainer.classList.remove('hidden');
            uploadContent.classList.add('hidden');

            // Update file info
            fileName.textContent = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Show meta section
            const metaSection = document.getElementById(`new-meta-${index}`);
            if (metaSection) {
                metaSection.classList.remove('hidden');
                metaSection.querySelector('.file-type').textContent = file.type.split('/')[1].toUpperCase();
                metaSection.querySelector('.resolution').textContent = `${img.width} × ${img.height} px`;
                metaSection.querySelector('.file-size-detail').textContent = formatFileSize(file.size);
            }

            // Clean up object URL
            URL.revokeObjectURL(objectUrl);
        };

        img.src = objectUrl;
    }

    function replaceNewImage(index) {
        const input = document.getElementById(`new-image-input-${index}`);
        if (input) {
            input.click();
        }
    }

    function validateNewAltText(input, index) {
        const value = input.value.trim();
        const counter = document.getElementById(`new-alt-counter-${index}`);
        const feedback = document.getElementById(`new-alt-feedback-${index}`);

        // Update counter
        if (counter) {
            counter.textContent = `${value.length}/125`;

            if (value.length > 100) {
                counter.classList.remove('text-gray-400', 'text-yellow-500');
                counter.classList.add('text-red-500', 'font-semibold');
            } else if (value.length > 80) {
                counter.classList.remove('text-gray-400', 'text-red-500');
                counter.classList.add('text-yellow-500');
            } else {
                counter.classList.remove('text-yellow-500', 'text-red-500', 'font-semibold');
                counter.classList.add('text-gray-400');
            }
        }

        // Validate and show feedback
        if (feedback) {
            feedback.classList.remove('hidden');

            if (value.length === 0) {
                feedback.textContent = '';
                feedback.classList.add('hidden');
                input.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
            } else if (value.length > 125) {
                feedback.textContent = 'Alt text should be under 125 characters';
                feedback.className = 'alt-text-feedback text-xs mt-1 text-red-600';
                input.classList.remove('border-yellow-500', 'border-green-500');
                input.classList.add('border-red-500');
                showNotification('Alt text exceeds 125 characters', 'warning');
            } else if (value.length < 5) {
                feedback.textContent = 'Consider adding more description for better accessibility';
                feedback.className = 'alt-text-feedback text-xs mt-1 text-yellow-600';
                input.classList.remove('border-red-500', 'border-green-500');
                input.classList.add('border-yellow-500');
            } else if (value.length >= 5 && value.length <= 125) {
                feedback.textContent = 'Good alt text length';
                feedback.className = 'alt-text-feedback text-xs mt-1 text-green-600';
                input.classList.remove('border-red-500', 'border-yellow-500');
                input.classList.add('border-green-500');
            }
        }
    }

    function updateNewSortOrders() {
        const rows = document.querySelectorAll('.new-image-row');
        const sortInputs = Array.from(rows).map(row => ({
            input: row.querySelector('.sort-input'),
            index: parseInt(row.dataset.index)
        }));

        // Sort by current values
        sortInputs.sort((a, b) => parseInt(a.input.value) - parseInt(b.input.value));

        // Update values and reorder rows
        sortInputs.forEach((item, newIndex) => {
            const actualIndex = newIndex + 1;
            item.input.value = actualIndex;

            // Move row to correct position if needed
            const row = document.querySelector(`.new-image-row[data-index="${item.index}"]`);
            const container = document.getElementById('newImagesContainer');

            if (row && newIndex < sortInputs.length - 1) {
                const nextRow = sortInputs[newIndex + 1].input.closest('.new-image-row');
                if (nextRow && row.nextElementSibling !== nextRow) {
                    container.insertBefore(row, nextRow);
                }
            }
        });

        reindexNewRows();
    }

    function reindexNewRows() {
        const rows = document.querySelectorAll('.new-image-row');
        rows.forEach((row, index) => {
            const newIndex = index + 1;
            row.dataset.index = newIndex;

            // Update display number
            const badge = row.querySelector('.rounded-full.bg-gradient-to-r');
            if (badge) {
                badge.textContent = `#${newIndex}`;
            }

            // Update sort order input
            const sortInput = row.querySelector('.sort-input');
            if (sortInput) {
                sortInput.value = newIndex;
            }

            // Update all input names
            const inputs = row.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name && name.includes('new_images[')) {
                    input.setAttribute('name', name.replace(/new_images\[\d+\]/g,
                        `new_images[${newIndex}]`));
                }
            });

            // Update IDs
            const rowId = `new-image-row-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            row.id = rowId;

            // Update onclick handlers
            const buttons = row.querySelectorAll('button[onclick]');
            buttons.forEach(button => {
                const onclick = button.getAttribute('onclick');
                if (onclick) {
                    button.setAttribute('onclick', onclick.replace(/\d+/g, newIndex));
                }
            });
        });
    }

    function updateNewImagesCount() {
        const container = document.getElementById('newImagesContainer');
        const count = container.querySelectorAll('.new-image-row').length;
        const countElement = document.getElementById('newImagesCount');

        if (countElement) {
            countElement.textContent = count;
        }
    }

    function extractNewImageMetadata(file, index) {
        if (file.type.startsWith('image/')) {
            const img = new Image();
            img.onload = function() {
                const metaSection = document.getElementById(`new-meta-${index}`);
                if (metaSection) {
                    // Update resolution
                    const resolution = metaSection.querySelector('.resolution');
                    if (resolution) {
                        resolution.textContent = `${img.width} * ${img.height} px`;
                    }

                    // Check if image is optimized
                    const fileSizeKB = file.size / 1024;
                    const megapixels = (img.width * img.height) / 1000000;

                    if (fileSizeKB / megapixels > 500) {
                        const feedback = document.getElementById(`new-alt-feedback-${index}`);
                        if (feedback) {
                            feedback.textContent += ' Consider optimizing this image for web.';
                        }
                    }
                }
            };
            img.src = URL.createObjectURL(file);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showNotification(message, type = 'info') {
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const colors = {
            success: 'bg-green-500 border-green-600',
            error: 'bg-red-500 border-red-600',
            warning: 'bg-yellow-500 border-yellow-600',
            info: 'bg-blue-500 border-blue-600'
        };

        // Remove existing notifications
        const existing = document.querySelectorAll('.new-image-notification');
        existing.forEach(el => el.remove());

        // Create notification
        const notification = document.createElement('div');
        notification.className =
            `new-image-notification fixed top-20 right-4 ${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center animate-slideIn`;
        notification.innerHTML = `
        <i class="${icons[type]} mr-2"></i>
        <span>${message}</span>
    `;

        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('animate-slideIn');
            notification.classList.add('animate-slideOut');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Remove new image row with confirmation
    function removeNewImageRow(button) {
        SafeSwal.fire({
            title: 'Remove New Image?',
            text: 'Are you sure you want to remove this new image? Any uploaded files will be lost.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const row = button.closest('.new-image-row');

                // Add removal animation
                row.style.opacity = '0.5';
                row.style.transform = 'translateX(100px)';

                setTimeout(() => {
                    row.remove();
                    reindexNewRows();
                    updateNewImagesCount();
                    showNotification('New image removed successfully', 'success');
                }, 300);
            }
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Make functions globally available
        window.handleNewImageUpload = handleNewImageUpload;
        window.validateNewAltText = validateNewAltText;
        window.removeNewImageRow = removeNewImageRow;
    });

    // Discount type change handler
    function handleDiscountTypeChange(selectElement) {
        const discountValueField = selectElement.closest('.space-y-4').querySelector('.discount-value-field');
        const discountPrefix = discountValueField.querySelector('.discount-prefix');

        if (selectElement.value) {
            discountValueField.style.display = 'block';
            discountPrefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
        } else {
            discountValueField.style.display = 'none';
        }
    }

    // Variant discount type change handler
    function handleVariantDiscountTypeChange(selectElement) {
        const variantRow = selectElement.closest('.variant-discount-section');
        const discountValueField = variantRow.querySelector('.variant-discount-value-field');
        const discountPrefix = discountValueField.querySelector('.variant-discount-prefix');

        if (selectElement.value) {
            discountValueField.style.display = 'block';
            discountPrefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
        } else {
            discountValueField.style.display = 'none';
        }
    }

    // Handle edit form submission
    function handleEditFormSubmit(e) {
        e.preventDefault();

        // Get the form
        const form = document.getElementById('editProductForm');

        // Ensure deleted images are in the hidden input
        const deletedInput = document.getElementById('deletedImagesInput');
        if (deletedInput && window.deletedImages && window.deletedImages.length > 0) {
            deletedInput.value = JSON.stringify(window.deletedImages);
        } else if (deletedInput) {
            deletedInput.value = '[]';
        }

        // Show loading using SafeSwal
        SafeSwal.fire({
            title: 'Updating Product...',
            text: 'Please wait while we update your product',
            allowOutsideClick: false,
            didOpen: () => {
                if (typeof Swal !== 'undefined' && Swal.showLoading) {
                    Swal.showLoading();
                }
            }
        });

        // Submit the form
        form.submit();
    }

    // Form submission handlers for SweetAlert2
    document.addEventListener('DOMContentLoaded', function() {

        // Add product form
        const addForm = document.querySelector('form[action*="products.store"]');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();

                SafeSwal.fire({
                    title: 'Creating Product...',
                    text: 'Please wait while we create your product',
                    allowOutsideClick: false,
                    didOpen: () => {
                        if (typeof Swal !== 'undefined' && Swal.showLoading) {
                            Swal.showLoading();
                        }
                    }
                });

                // Submit the form
                this.submit();
            });
        }

        // Edit product form
        const editForm = document.getElementById('editProductForm');
        if (editForm) {
            // Remove any existing event listeners
            const newEditForm = editForm.cloneNode(true);
            editForm.parentNode.replaceChild(newEditForm, editForm);

            // Add new event listener to the new form
            document.getElementById('editProductForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Ensure deleted images are in the hidden input
                const deletedInput = document.getElementById('deletedImagesInput');
                if (deletedInput && window.deletedImages && window.deletedImages.length > 0) {
                    deletedInput.value = JSON.stringify(window.deletedImages);
                    console.log('Sending deleted images:', deletedInput.value);
                } else if (deletedInput) {
                    deletedInput.value = '[]';
                }

                // Show loading using SafeSwal
                SafeSwal.fire({
                    title: 'Updating Product...',
                    text: 'Please wait while we update your product',
                    allowOutsideClick: false,
                    didOpen: () => {
                        if (typeof Swal !== 'undefined' && Swal.showLoading) {
                            Swal.showLoading();
                        }
                    }
                });

                // Submit the form normally
                this.submit();
            });
        }

        // Set default values on page load
        const firstVariant = document.querySelector('[name="variants[0][size]"]');
        if (firstVariant) firstVariant.value = 'M';

        const firstColor = document.querySelector('[name="variants[0][color]"]');
        if (firstColor) firstColor.value = 'Black';

        const firstPrice = document.querySelector('[name="variants[0][price]"]');
        if (firstPrice) firstPrice.value = '100';

        const firstStock = document.querySelector('[name="variants[0][stock]"]');
        if (firstStock) firstStock.value = '10';

        const statusSelect = document.querySelector('[name="status"]');
        if (statusSelect) statusSelect.value = 'active';

        // Check for success/error messages from server
        @if (session('success'))
            setTimeout(() => {
                SafeSwal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            }, 500);
        @endif

        @if (session('error'))
            setTimeout(() => {
                SafeSwal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}'
                });
            }, 500);
        @endif

        @if ($errors->any())
            setTimeout(() => {
                SafeSwal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `
            <div class="text-left">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        `
                });
            }, 500);
        @endif
    });

    // Search functionality to update table
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchLoading = document.getElementById('searchLoading');
        let searchTimeout;

        // Debounced search on input
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    const query = e.target.value.trim();

                    // Show loading
                    if (searchLoading) searchLoading.classList.remove('hidden');

                    // Update table with search results
                    updateTableWithSearch(query);
                }, 500); // 500ms delay
            });

            // Enter key to search immediately
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);

                    const query = e.target.value.trim();
                    if (searchLoading) searchLoading.classList.remove('hidden');
                    updateTableWithSearch(query);
                }

                // Escape to clear
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    clearSearch();
                }
            });
        }
    });

    // Update table with search results via AJAX
    function updateTableWithSearch(query) {
        // If empty query, show all products
        if (!query) {
            // Just reload page to show all products
            window.location.href = '/admin/products';
            return;
        }

        // Update URL without reloading page
        const url = new URL(window.location.href);
        url.searchParams.set('search', query);
        window.history.pushState({}, '', url);

        // Show loading state on table
        showTableLoading();

        // Fetch filtered products via AJAX
        fetch(`/admin/products?search=${encodeURIComponent(query)}&ajax=1`)
            .then(response => response.text())
            .then(html => {
                // Extract table content from response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update table body
                const newTableBody = doc.querySelector('tbody');
                const currentTableBody = document.querySelector('tbody');
                if (newTableBody && currentTableBody) {
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                }

                // Update stats if they exist in response
                const newStats = doc.querySelector('.card:nth-child(1) .text-2xl');
                const currentStats = document.querySelector('.card:nth-child(1) .text-2xl');
                if (newStats && currentStats) {
                    currentStats.textContent = newStats.textContent;
                }

                // Hide loading
                const searchLoading = document.getElementById('searchLoading');
                if (searchLoading) searchLoading.classList.add('hidden');
                hideTableLoading();
            })
            .catch(error => {
                console.error('Search error:', error);

                // Fallback: reload page normally
                const query = document.getElementById('searchInput')?.value.trim();
                if (query) {
                    window.location.href = `/admin/products?search=${encodeURIComponent(query)}`;
                } else {
                    window.location.href = '/admin/products';
                }

                const searchLoading = document.getElementById('searchLoading');
                if (searchLoading) searchLoading.classList.add('hidden');
                hideTableLoading();
            });
    }

    // Clear search
    function clearSearch() {
        // Clear input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.value = '';

        // Remove search indicator
        const indicator = document.querySelector('.search-indicator');
        if (indicator) {
            indicator.remove();
        }

        // Remove search param from URL without reloading
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        window.history.pushState({}, '', url);

        // Show loading
        showTableLoading();

        // Reload all products
        fetch('/admin/products?ajax=1')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update table body
                const newTableBody = doc.querySelector('tbody');
                const currentTableBody = document.querySelector('tbody');
                if (newTableBody && currentTableBody) {
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                }

                // Update stats
                const newStats = doc.querySelector('.card:nth-child(1) .text-2xl');
                const currentStats = document.querySelector('.card:nth-child(1) .text-2xl');
                if (newStats && currentStats) {
                    currentStats.textContent = newStats.textContent;
                }

                // Hide loading
                hideTableLoading();
            })
            .catch(error => {
                console.error('Error clearing search:', error);
                // Fallback: reload page
                window.location.href = '/admin/products';
            });
    }

    // Show loading overlay on table
    function showTableLoading() {
        const table = document.querySelector('.overflow-x-auto');
        if (table) {
            let overlay = table.querySelector('.table-loading-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className =
                    'table-loading-overlay absolute inset-0 bg-white/80 flex items-center justify-center z-10';
                overlay.innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i>
            <p class="text-gray-700 font-medium">Loading products...</p>
        </div>
    `;
                table.parentElement.classList.add('relative');
                table.parentElement.appendChild(overlay);
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
    window.clearSearch = clearSearch;
    window.SafeSwal = SafeSwal; // Expose SafeSwal globally

    // Image Management System
    const ImageManager = {
        maxAdditionalImages: 5,
        imageIndex: 1,
        fileSizeLimit: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml','image/avif'],

        // Initialize image manager
        init: function() {
            this.bindEvents();
            this.loadExistingImages();
            this.updateImageCount();
            this.updateProgressBar();
        },

        // Bind all events
        bindEvents: function() {
            // Primary image upload
            const primaryUpload = document.querySelector('.primary-upload-area input[type="file"]');
            if (primaryUpload) {
                primaryUpload.addEventListener('change', (e) => this.handlePrimaryImageUpload(e.target));
            }

            // Clear primary image button
            const clearPrimaryBtn = document.querySelector('[onclick="clearPrimaryFileInput()"]');
            if (clearPrimaryBtn) {
                clearPrimaryBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.clearPrimaryImage();
                });
            }


            // Image container for event delegation
            const container = document.getElementById('additionalImagesContainer');

            if (container) {
                container.addEventListener('click', (e) => {
                    // Handle remove button clicks
                    if (e.target.closest('[onclick*="removeImageRow"]') ||
                        e.target.closest('[onclick*="removeNewImageRow"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        this.removeImageRow(button);
                    }

                    // Handle clear preview button
                    if (e.target.closest('[onclick*="clearImageInput"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const index = parseInt(button.getAttribute('onclick').match(/\d+/)[0]);
                        this.clearImageInput(index);
                    }
                });

                // Handle file input changes
                container.addEventListener('change', (e) => {
                    if (e.target.type === 'file' && e.target.closest('.additional-image-row')) {
                        const index = e.target.closest('.additional-image-row').dataset.index;
                        this.handleImageUpload(e.target, index);
                    }
                });

                // Handle alt text validation
                container.addEventListener('input', (e) => {
                    if (e.target.name && e.target.name.includes('alt_text')) {
                        this.validateAltText(e.target);
                    }
                });
            }
        },

        // Prevent default drag behaviors
        preventDefaults: function(e) {
            e.preventDefault();
            e.stopPropagation();
        },

        // Load existing images (for edit mode)
        loadExistingImages: function() {
            const existingImages = document.querySelectorAll('.image-container');
            existingImages.forEach((container, index) => {
                const imageId = container.id.replace('image-', '');
                if (imageId && !isNaN(imageId)) {
                    // Initialize existing image with proper handlers
                    this.initializeExistingImage(container, parseInt(imageId));
                }
            });
        },

        // Enhanced addImageRow function
        addImageRow: function() {
            const container = document.getElementById('additionalImagesContainer');
            const imageCount = container.querySelectorAll('.additional-image-row').length;

            // Check if maximum images reached
            if (imageCount >= this.maxAdditionalImages) {
                this.showNotification(`Maximum of ${this.maxAdditionalImages} additional images allowed`,
                    'error');
                return false;
            }

            // Generate unique ID for this image row
            const rowId = `image-row-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            const rowIndex = imageCount + 1;

            const newRow = document.createElement('div');
            newRow.id = rowId;
            newRow.className =
                'additional-image-row border border-gray-200 rounded-lg p-4 mb-4 hover:border-blue-300 transition-all duration-200';
            newRow.dataset.index = rowIndex;

            newRow.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-semibold text-gray-900">Additional Image</span>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full font-medium">
                        #${rowIndex}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="ImageManager.removeImageRow(this)" 
                            class="text-red-600 hover:text-red-700 text-sm p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-4">
                <!-- File Upload Area with Enhanced Preview -->
                <div class="file-upload-area relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-blue-400 transition-all duration-200 bg-gradient-to-br from-white to-gray-50/50">
                    <input type="file" 
                           id="image-input-${rowIndex}"
                           name="images[${rowIndex}][image]" 
                           accept="${this.allowedTypes.join(',')}" 
                           class="file-input absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="ImageManager.handleImageUpload(this, ${rowIndex})">
                    
                    <div class="upload-content text-center py-4">
                        <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center shadow-inner">
                            <i class="fas fa-cloud-upload-alt text-blue-500 text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-2">Click to upload</p>
                        <p class="text-gray-500 text-xs mb-1">Supports: ${this.allowedTypes.map(t => t.split('/')[1].toUpperCase()).join(', ')}</p>
                        <p class="text-gray-400 text-xs">Maximum file size: 5MB</p>
                    </div>
                    
                    <!-- Enhanced Preview Container -->
                    <div id="preview-${rowIndex}" class="preview-container hidden">
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="relative flex-shrink-0">
                                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 shadow-sm">
                                        <img class="preview-image w-full h-full object-cover"
                                             src="" 
                                             alt="Preview"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjRjFGNUZGIi8+CjxwYXRoIGQ9Ik01MCA0MEM1MCA0NC40MTgzIDQ2LjQxODMgNDggNDIgNDhDMzcuNTgxNyA0OCAzNCA0NC40MTgzIDM0IDQwQzM0IDM1LjU4MTcgMzcuNTgxNyAzMiA0MiAzMkM0Ni40MTgzIDMyIDUwIDM1LjU4MTcgNTAgNDBaIiBmaWxsPSIjRDhEQ0VGIi8+CjxwYXRoIGQ9Ik02NS4wMDAxIDU1Ljk5OTlWNTIuNDk5OUM2NS4wMDAxIDQ4LjM5OTkgNTcuNTAwMSA0NS45OTk5IDUwLjAwMDEgNDUuOTk5OUM0Mi41MDAxIDQ1Ljk5OTkgMzUuMDAwMSA0OC4zOTk5IDM1LjAwMDEgNTIuNDk5OVY1NS45OTk5SDY1LjAwMDFaIiBmaWxsPSIjRDhEQ0VGIi8+Cjwvc3ZnPgo=';">
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">          
                                        <p class="file-name text-sm font-semibold text-gray-900 truncate"></p>
                                        <p class="file-size text-xs text-gray-500 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Fields Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Alt Text Field -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-900 flex items-center">
                            <i class="fas fa-align-left mr-2 text-gray-500 text-sm"></i>
                            Alt Text
                            <span class="ml-1 text-gray-400 text-xs">(optional)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="images[${rowIndex}][alt_text]" 
                                   id="alt-text-${rowIndex}"
                                   class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10"
                                   placeholder="Describe the image"
                                   maxlength="125"
                                   oninput="ImageManager.validateAltText(this)">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <div class="alt-text-counter text-xs text-gray-400" id="alt-counter-${rowIndex}">
                                    0/125
                                </div>
                            </div>
                        </div>
                        <div class="alt-text-feedback hidden text-xs mt-1" id="alt-feedback-${rowIndex}"></div>
                        <p class="text-xs text-gray-500">Helps with SEO and accessibility. Recommended: 5-15 words.</p>
                    </div>
                    
                    <!-- Sort Order Field -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-900 flex items-center">
                            <i class="fas fa-sort-numeric-down mr-2 text-gray-500 text-sm"></i>
                            Display Order
                        </label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <input type="number" 
                                   name="images[${rowIndex}][sort_order]" 
                                   id="sort-order-${rowIndex}"
                                   value="${rowIndex}"
                                   min="1"
                                   max="100"
                                   class="sort-input w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   onchange="ImageManager.updateAllSortOrders()">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-sort-amount-down"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">Lower numbers appear first in galleries</p>
                    </div>
                </div>
            </div>
            
            <!-- Hidden Fields -->
            <input type="hidden" name="images[${rowIndex}][is_primary]" value="0">
            <input type="hidden" name="images[${rowIndex}][temp_id]" value="temp_${Date.now()}_${rowIndex}">
            <input type="hidden" name="images[${rowIndex}][image_hash]" id="image-hash-${rowIndex}">
        `;

            // Add animation class
            newRow.classList.add('animate-fadeIn');

            container.appendChild(newRow);
            this.imageIndex++;

            // Update UI
            this.updateImageCount();
            this.updateProgressBar();

            // Initialize tooltips
            this.initTooltips(newRow);

            // Show success message
            this.showNotification('New image slot added successfully', 'success');

            return true;
        },

        // Handle image upload
        handleImageUpload: function(input, index) {
            const file = input.files[0];
            if (!file) return;

            // Validate file
            if (!this.validateFile(file)) {
                input.value = '';
                return;
            }

            // Show preview
            this.showImagePreview(file, index);

            // Calculate and store image hash
            this.calculateImageHash(file).then(hash => {
                document.getElementById(`image-hash-${index}`).value = hash;
            });

            // Extract and display metadata
            this.extractImageMetadata(file, index);
        },

        // Validate file
        validateFile: function(file) {
            // Check file type
            if (!this.allowedTypes.includes(file.type)) {
                const typesList = this.allowedTypes.map(t => t.split('/')[1].toUpperCase()).join(', ');
                this.showNotification(`Invalid file type. Allowed types: ${typesList}`, 'error');
                return false;
            }

            // Check file size
            if (file.size > this.fileSizeLimit) {
                this.showNotification(`File size exceeds ${this.formatFileSize(this.fileSizeLimit)} limit`,
                    'error');
                return false;
            }

            // Check image dimensions (for images only)
            if (file.type.startsWith('image/') && !file.type.includes('svg')) {
                const img = new Image();
                img.onload = function() {
                    // Check minimum dimensions
                    if (this.width < 100 || this.height < 100) {
                        this.showNotification('Image dimensions should be at least 100x100 pixels',
                            'warning');
                    }

                    // Check aspect ratio
                    const aspectRatio = this.width / this.height;
                    if (aspectRatio < 0.5 || aspectRatio > 2) {
                        this.showNotification(
                            'Extreme aspect ratio detected. Recommended: between 1:2 and 2:1', 'warning'
                        );
                    }
                }.bind(this);
                img.src = URL.createObjectURL(file);
            }

            return true;
        },

        // Show image preview
        showImagePreview: function(file, index) {
            const previewContainer = document.getElementById(`preview-${index}`);
            const uploadArea = previewContainer.closest('.file-upload-area');
            const uploadContent = uploadArea.querySelector('.upload-content');
            const previewImage = previewContainer.querySelector('.preview-image');
            const fileName = previewContainer.querySelector('.file-name');
            const fileSize = previewContainer.querySelector('.file-size');
            const dimensions = previewContainer.querySelector('.dimensions');
            const statusBadge = previewContainer.querySelector('.image-status-badge');

            // Create object URL for preview
            const objectUrl = URL.createObjectURL(file);

            // Load image to get dimensions
            const img = new Image();
            img.onload = function() {
                // Update preview
                previewImage.src = objectUrl;
                previewContainer.classList.remove('hidden');
                uploadContent.classList.add('hidden');

                // Update file info
                fileName.textContent = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file
                    .name;
                fileSize.textContent = this.formatFileSize(file.size);
                dimensions.textContent = `${img.width} × ${img.height} px`;

                // Update status
                statusBadge.textContent = 'Uploaded';
                statusBadge.className =
                    'image-status-badge text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium';

                // Show meta section
                const metaSection = document.getElementById(`meta-${index}`);
                if (metaSection) {
                    metaSection.classList.remove('hidden');
                    metaSection.querySelector('.file-type').textContent = file.type.split('/')[1]
                        .toUpperCase();
                    metaSection.querySelector('.resolution').textContent = `${img.width} * ${img.height}`;
                    metaSection.querySelector('.last-modified').textContent = new Date(file.lastModified)
                        .toLocaleDateString();
                }

                // Clean up object URL
                URL.revokeObjectURL(objectUrl);
            }.bind(this);

            img.src = objectUrl;
        },

        // Handle primary image upload
        handlePrimaryImageUpload: function(input) {
            const file = input.files[0];
            if (!file) return;

            if (!this.validateFile(file)) {
                input.value = '';
                return;
            }

            this.showPrimaryImagePreview(file);
        },

        // Show primary image preview
        showPrimaryImagePreview: function(file) {
            const previewContainer = document.getElementById('primary-file-preview');
            const uploadContent = document.querySelector('.primary-upload-area .upload-content');
            const previewImage = document.getElementById('primary-preview-image');
            const fileName = document.getElementById('primary-file-name');
            const fileSize = document.getElementById('primary-file-size');

            const objectUrl = URL.createObjectURL(file);
            const img = new Image();

            img.onload = function() {
                previewImage.src = objectUrl;
                previewContainer.classList.remove('hidden');
                uploadContent.classList.add('hidden');

                fileName.textContent = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file
                    .name;
                fileSize.textContent = this.formatFileSize(file.size);

                // Add success styling
                previewContainer.closest('.primary-upload-area').classList.add('border-green-300',
                    'bg-green-50');

                URL.revokeObjectURL(objectUrl);
            }.bind(this);

            img.src = objectUrl;
        },

        // Clear primary image
        clearPrimaryImage: function() {
            const input = document.querySelector('.primary-upload-area input[type="file"]');
            const previewContainer = document.getElementById('primary-file-preview');
            const uploadContent = document.querySelector('.primary-upload-area .upload-content');

            if (input) input.value = '';
            previewContainer.classList.add('hidden');
            uploadContent.classList.remove('hidden');

            // Remove success styling
            previewContainer.closest('.primary-upload-area').classList.remove('border-green-300',
                'bg-green-50');
        },

        // Clear image input
        clearImageInput: function(index) {
            const previewContainer = document.getElementById(`preview-${index}`);
            const uploadArea = previewContainer.closest('.file-upload-area');
            const uploadContent = uploadArea.querySelector('.upload-content');
            const input = uploadArea.querySelector('.file-input');
            const metaSection = document.getElementById(`meta-${index}`);

            // Clear input
            if (input) input.value = '';

            // Show upload content, hide preview
            previewContainer.classList.add('hidden');
            uploadContent.classList.remove('hidden');

            // Hide meta section
            if (metaSection) {
                metaSection.classList.add('hidden');
            }

            // Clear hidden hash
            document.getElementById(`image-hash-${index}`).value = '';

            // Reset form fields
            const altText = document.getElementById(`alt-text-${index}`);
            const sortOrder = document.getElementById(`sort-order-${index}`);
            const altCounter = document.getElementById(`alt-counter-${index}`);
            const altFeedback = document.getElementById(`alt-feedback-${index}`);

            if (altText) altText.value = '';
            if (sortOrder) sortOrder.value = index;
            if (altCounter) altCounter.textContent = '0/125';
            if (altFeedback) {
                altFeedback.classList.add('hidden');
                altFeedback.textContent = '';
            }
        },



        // Remove image row
        removeImageRow: function(button) {
            SafeSwal.fire({
                title: 'Remove Image?',
                text: 'Are you sure you want to remove this image? Any uploaded files will be lost.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const row = button.closest('.additional-image-row');

                    // Add removal animation
                    row.style.opacity = '0.5';
                    row.style.transform = 'translateX(100px)';

                    setTimeout(() => {
                        row.remove();
                        this.reindexRows();
                        this.updateImageCount();
                        this.updateProgressBar();
                        this.showNotification('Image removed successfully', 'success');
                    }, 300);
                }
            });
        },

        // Reindex all rows
        reindexRows: function() {
            const rows = document.querySelectorAll('.additional-image-row');
            rows.forEach((row, index) => {
                const newIndex = index + 1;
                row.dataset.index = newIndex;

                // Update display number
                const titleSpan = row.querySelector('span.text-sm.font-semibold');
                if (titleSpan) {
                    titleSpan.textContent = `Additional Image`;
                }

                // Update badge
                const badge = row.querySelector('.rounded-full.bg-blue-100');
                if (badge) {
                    badge.textContent = `#${newIndex}`;
                }

                // Update sort order input
                const sortInput = row.querySelector('.sort-input');
                if (sortInput) {
                    sortInput.value = newIndex;
                }

                // Update all input names
                const inputs = row.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('images[')) {
                        input.setAttribute('name', name.replace(/images\[\d+\]/g,
                            `images[${newIndex}]`));
                    }
                });

                // Update IDs
                const rowId = `image-row-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
                row.id = rowId;

                // Update onclick handlers
                const buttons = row.querySelectorAll('button[onclick*="ImageManager."]');
                buttons.forEach(button => {
                    const onclick = button.getAttribute('onclick');
                    if (onclick) {
                        button.setAttribute('onclick', onclick.replace(/\d+/g, newIndex));
                    }
                });
            });
        },

        // Update image count
        updateImageCount: function() {
            const container = document.getElementById('additionalImagesContainer');
            const count = container.querySelectorAll('.additional-image-row').length;
            const countElement = document.getElementById('imageCount');

            if (countElement) {
                countElement.textContent = count;

                // Update color based on count
                if (count >= this.maxAdditionalImages) {
                    countElement.classList.remove('text-gray-600', 'text-yellow-600');
                    countElement.classList.add('text-red-600', 'font-bold');
                } else if (count >= this.maxAdditionalImages - 1) {
                    countElement.classList.remove('text-gray-600', 'text-red-600');
                    countElement.classList.add('text-yellow-600', 'font-semibold');
                } else {
                    countElement.classList.remove('text-yellow-600', 'text-red-600', 'font-bold',
                        'font-semibold');
                    countElement.classList.add('text-gray-600');
                }
            }
        },

        // Update progress bar
        updateProgressBar: function() {
            const container = document.getElementById('additionalImagesContainer');
            const count = container.querySelectorAll('.additional-image-row').length;
            const progress = (count / this.maxAdditionalImages) * 100;
            const progressBar = document.getElementById('imageProgress');

            if (progressBar) {
                progressBar.style.width = `${progress}%`;

                // Change color based on progress
                if (progress >= 90) {
                    progressBar.className = 'h-full bg-red-500 rounded-full transition-all duration-300';
                } else if (progress >= 70) {
                    progressBar.className = 'h-full bg-yellow-500 rounded-full transition-all duration-300';
                } else if (progress >= 50) {
                    progressBar.className = 'h-full bg-orange-500 rounded-full transition-all duration-300';
                } else {
                    progressBar.className = 'h-full bg-blue-500 rounded-full transition-all duration-300';
                }

                // Add animation for high usage
                if (progress >= 80) {
                    progressBar.classList.add('pulse-animation');
                } else {
                    progressBar.classList.remove('pulse-animation');
                }
            }
        },

        // Update all sort orders
        updateAllSortOrders: function() {
            const rows = document.querySelectorAll('.additional-image-row');
            const sortInputs = Array.from(rows).map(row => ({
                input: row.querySelector('.sort-input'),
                index: parseInt(row.dataset.index)
            }));

            // Sort by current values
            sortInputs.sort((a, b) => parseInt(a.input.value) - parseInt(b.input.value));

            // Update values and reorder rows
            sortInputs.forEach((item, newIndex) => {
                const actualIndex = newIndex + 1;
                item.input.value = actualIndex;

                // Move row to correct position if needed
                const row = document.querySelector(`[data-index="${item.index}"]`);
                const container = document.getElementById('additionalImagesContainer');

                if (row && newIndex < sortInputs.length - 1) {
                    const nextRow = sortInputs[newIndex + 1].input.closest('.additional-image-row');
                    if (nextRow && row.nextElementSibling !== nextRow) {
                        container.insertBefore(row, nextRow);
                    }
                }
            });

            this.reindexRows();
        },

        // Validate alt text
        validateAltText: function(input) {
            const value = input.value.trim();
            const counterId = input.id.replace('alt-text-', 'alt-counter-');
            const feedbackId = input.id.replace('alt-text-', 'alt-feedback-');
            const counter = document.getElementById(counterId);
            const feedback = document.getElementById(feedbackId);

            // Update counter
            if (counter) {
                counter.textContent = `${value.length}/125`;

                if (value.length > 100) {
                    counter.classList.remove('text-gray-400', 'text-yellow-500');
                    counter.classList.add('text-red-500', 'font-semibold');
                } else if (value.length > 80) {
                    counter.classList.remove('text-gray-400', 'text-red-500');
                    counter.classList.add('text-yellow-500');
                } else {
                    counter.classList.remove('text-yellow-500', 'text-red-500', 'font-semibold');
                    counter.classList.add('text-gray-400');
                }
            }

            // Validate and show feedback
            if (feedback) {
                feedback.classList.remove('hidden');

                if (value.length === 0) {
                    feedback.textContent = '';
                    feedback.classList.add('hidden');
                    input.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
                } else if (value.length > 125) {
                    feedback.textContent = 'Alt text should be under 125 characters';
                    feedback.className = 'alt-text-feedback text-xs mt-1 text-red-600';
                    input.classList.remove('border-yellow-500', 'border-green-500');
                    input.classList.add('border-red-500');
                    this.showNotification('Alt text exceeds 125 characters', 'warning');
                } else if (value.length < 5) {
                    feedback.textContent = 'Consider adding more description for better accessibility';
                    feedback.className = 'alt-text-feedback text-xs mt-1 text-yellow-600';
                    input.classList.remove('border-red-500', 'border-green-500');
                    input.classList.add('border-yellow-500');
                } else if (value.length >= 5 && value.length <= 125) {
                    feedback.textContent = 'Good alt text length';
                    feedback.className = 'alt-text-feedback text-xs mt-1 text-green-600';
                    input.classList.remove('border-red-500', 'border-yellow-500');
                    input.classList.add('border-green-500');
                }
            }
        },

        // Calculate image hash (for duplicate detection)
        calculateImageHash: async function(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const arrayBuffer = e.target.result;

                    // Simple hash calculation
                    crypto.subtle.digest('SHA-256', arrayBuffer).then(hashBuffer => {
                        const hashArray = Array.from(new Uint8Array(hashBuffer));
                        const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0'))
                            .join('');
                        resolve(hashHex);
                    });
                };
                reader.readAsArrayBuffer(file);
            });
        },

        // Extract image metadata
        extractImageMetadata: function(file, index) {
            if (file.type.startsWith('image/') && !file.type.includes('svg')) {
                const img = new Image();
                img.onload = function() {
                    const metaSection = document.getElementById(`meta-${index}`);
                    if (metaSection) {
                        // Update resolution
                        const resolution = metaSection.querySelector('.resolution');
                        if (resolution) {
                            resolution.textContent = `${img.width} × ${img.height} px`;
                        }

                        // Check if image is optimized
                        const fileSizeKB = file.size / 1024;
                        const megapixels = (img.width * img.height) / 1000000;

                        if (fileSizeKB / megapixels > 500) {
                            const feedback = document.getElementById(`alt-feedback-${index}`);
                            if (feedback) {
                                feedback.textContent += ' Consider optimizing this image for web.';
                            }
                        }
                    }
                };
                img.src = URL.createObjectURL(file);
            }
        },

        // Format file size
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        // Show notification
        showNotification: function(message, type = 'info') {
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };

            const colors = {
                success: 'bg-green-500 border-green-600',
                error: 'bg-red-500 border-red-600',
                warning: 'bg-yellow-500 border-yellow-600',
                info: 'bg-blue-500 border-blue-600'
            };

            // Remove existing notifications
            const existing = document.querySelectorAll('.image-notification');
            existing.forEach(el => el.remove());

            // Create notification
            const notification = document.createElement('div');
            notification.className =
                `image-notification fixed top-20 right-4 ${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center animate-slideIn`;
            notification.innerHTML = `
            <i class="${icons[type]} mr-2"></i>
            <span>${message}</span>
        `;

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.remove('animate-slideIn');
                notification.classList.add('animate-slideOut');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        },

        // Initialize tooltips
        initTooltips: function(element) {
            const tooltips = element.querySelectorAll('[title]');
            tooltips.forEach(el => {
                el.addEventListener('mouseenter', (e) => {
                    const tooltip = document.createElement('div');
                    tooltip.className =
                        'fixed z-50 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm tooltip';
                    tooltip.textContent = e.target.title;
                    document.body.appendChild(tooltip);

                    const rect = e.target.getBoundingClientRect();
                    tooltip.style.left = rect.left + 'px';
                    tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';

                    e.target._tooltip = tooltip;
                });

                el.addEventListener('mouseleave', (e) => {
                    if (e.target._tooltip) {
                        e.target._tooltip.remove();
                        delete e.target._tooltip;
                    }
                });
            });
        },

        // Check for duplicate images
        checkForDuplicates: function() {
            const hashInputs = document.querySelectorAll('input[name$="[image_hash]"]');
            const hashes = [];
            const duplicates = [];

            hashInputs.forEach(input => {
                if (input.value) {
                    if (hashes.includes(input.value)) {
                        duplicates.push(input);
                    } else {
                        hashes.push(input.value);
                    }
                }
            });

            if (duplicates.length > 0) {
                this.showNotification(`Found ${duplicates.length} duplicate image(s)`, 'warning');

                // Highlight duplicate rows
                duplicates.forEach(input => {
                    const row = input.closest('.additional-image-row');
                    if (row) {
                        row.classList.add('border-red-300', 'bg-red-50');
                        row.classList.remove('hover:border-blue-300');

                        // Add warning icon
                        const header = row.querySelector('.flex.justify-between');
                        if (header) {
                            const warningIcon = document.createElement('div');
                            warningIcon.className = 'ml-2 text-red-500';
                            warningIcon.innerHTML =
                                '<i class="fas fa-exclamation-triangle" title="Duplicate image detected"></i>';
                            header.appendChild(warningIcon);
                        }
                    }
                });

                return true;
            }

            return false;
        },

        // Validate all images before form submission
        validateAllImages: function() {
            let isValid = true;
            const errors = [];

            // Check primary image
            const primaryInput = document.querySelector('.primary-upload-area input[type="file"]');
            if (primaryInput && !primaryInput.files[0]) {
                errors.push('Primary image is required');
                isValid = false;
            }

            // Check additional images
            const imageRows = document.querySelectorAll('.additional-image-row');
            imageRows.forEach(row => {
                const fileInput = row.querySelector('.file-input');
                const altText = row.querySelector('input[name$="[alt_text]"]');

                // Check if file is uploaded but no alt text
                if (fileInput.files[0] && altText && altText.value.trim().length < 5) {
                    errors.push(`Image #${row.dataset.index}: Consider adding descriptive alt text`);
                    row.classList.add('border-yellow-300', 'bg-yellow-50');
                }

                // Check for very long alt text
                if (altText && altText.value.trim().length > 125) {
                    errors.push(`Image #${row.dataset.index}: Alt text too long (max 125 characters)`);
                    isValid = false;
                    row.classList.add('border-red-300', 'bg-red-50');
                }
            });

            // Check for duplicates
            if (this.checkForDuplicates()) {
                errors.push('Duplicate images detected');
            }

            // Show errors if any
            if (errors.length > 0) {
                const errorHtml = errors.map(error => `<li class="text-sm">${error}</li>`).join('');

                SafeSwal.fire({
                    icon: 'warning',
                    title: 'Image Validation Issues',
                    html: `<div class="text-left"><ul class="list-disc pl-4">${errorHtml}</ul></div>`,
                    confirmButtonText: 'Continue Anyway',
                    showCancelButton: true,
                    cancelButtonText: 'Go Back'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        isValid = false;
                    }
                });
            }

            return isValid;
        },

        // Export all image data
        exportImageData: function() {
            const images = [];
            const primaryRow = document.querySelector('.primary-upload-area');

            // Get primary image data
            if (primaryRow) {
                const fileInput = primaryRow.querySelector('input[type="file"]');
                if (fileInput.files[0]) {
                    images.push({
                        type: 'primary',
                        file: fileInput.files[0],
                        name: fileInput.files[0].name,
                        size: this.formatFileSize(fileInput.files[0].size),
                        is_primary: 1
                    });
                }
            }

            // Get additional image data
            const additionalRows = document.querySelectorAll('.additional-image-row');
            additionalRows.forEach(row => {
                const fileInput = row.querySelector('.file-input');
                const altText = row.querySelector('input[name$="[alt_text]"]');
                const sortOrder = row.querySelector('.sort-input');

                if (fileInput.files[0]) {
                    images.push({
                        type: 'additional',
                        index: row.dataset.index,
                        file: fileInput.files[0],
                        name: fileInput.files[0].name,
                        size: this.formatFileSize(fileInput.files[0].size),
                        alt_text: altText ? altText.value : '',
                        sort_order: sortOrder ? sortOrder.value : row.dataset.index,
                        is_primary: 0
                    });
                }
            });

            return images;
        },

        // Reset all images
        resetAllImages: function() {
            SafeSwal.fire({
                title: 'Reset All Images?',
                text: 'This will remove all uploaded images and reset the form. This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear primary image
                    this.clearPrimaryImage();

                    // Clear all additional images
                    const container = document.getElementById('additionalImagesContainer');
                    container.innerHTML = '';

                    // Reset index
                    this.imageIndex = 1;

                    // Update UI
                    this.updateImageCount();
                    this.updateProgressBar();

                    this.showNotification('All images have been reset', 'success');
                }
            });
        }
    };

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize ImageManager
        ImageManager.init();

        // Make functions globally available
        window.ImageManager = ImageManager;
        window.addImageRow = () => ImageManager.addImageRow();
        window.removeImageRow = (button) => ImageManager.removeImageRow(button);
        window.clearImageInput = (index) => ImageManager.clearImageInput(index);
        window.validateAltText = (input) => ImageManager.validateAltText(input);

        // Form validation
        const forms = document.querySelectorAll('form[enctype="multipart/form-data"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!ImageManager.validateAllImages()) {
                    e.preventDefault();
                }
            });
        });
    });
</script>

<style>
    /* Animation Classes */
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Image States */
    .image-deleted {
        opacity: 0.5;
        background: linear-gradient(to bottom right, #f3f4f6, #e5e7eb);
    }

    .image-deleted img {
        filter: grayscale(100%);
    }

    /* Variant Card Styles */
    .variant-card {
        transition: all 0.3s ease;
    }

    .variant-card:hover {
        border-color: #c7d2fe;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }

    /* Discount Preview Animation */
    .discount-preview {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Enhanced Input Focus */
    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Required Field Indicator */
    .required-field::after {
        content: ' *';
        color: #ef4444;
    }

    /* Loading Overlay */
    .table-loading-overlay {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
    }

    /* Tooltip Enhancement */
    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]:hover::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 100;
        margin-bottom: 5px;
    }

    [data-tooltip]:hover::after {
        content: '';
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: rgba(0, 0, 0, 0.8);
        margin-bottom: -5px;
    }

    /* Status Badges */
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
    }

    .status-badge.active {
        @apply bg-green-100 text-green-800;
    }

    .status-badge.inactive {
        @apply bg-gray-100 text-gray-800;
    }

    .status-badge.draft {
        @apply bg-yellow-100 text-yellow-800;
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        @apply bg-gray-100 rounded-full;
    }

    ::-webkit-scrollbar-thumb {
        @apply bg-gray-400 rounded-full hover:bg-gray-600;
    }

    /* Modal Animation */
    .modal-content {
        animation: modalAppear 0.3s ease-out forwards;
    }

    @keyframes modalAppear {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
