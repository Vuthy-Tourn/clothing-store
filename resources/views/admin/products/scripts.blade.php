<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ========== SWEETALERT2 SAFE WRAPPER ==========
    const SafeSwal = {
        fire: function(options) {
            if (typeof Swal !== 'undefined' && Swal.fire) {
                return Swal.fire(options);
            } else {
                console.warn('SweetAlert2 not available, falling back to native alerts');
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
        currentFilters: {},
        isLoading: false,
        searchTimeout: null,

        init: function() {
            this.bindEvents();
            this.initializeFromURL();
            this.applyOnPageLoad();
        },

        bindEvents: function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.debouncedSearch(e);
                });

                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.clearDebounce();
                        this.applyFilters();
                    }

                    if (e.key === 'Escape') {
                        searchInput.value = '';
                        this.applyFilters();
                    }
                });
            }

            const clearSearchBtn = document.getElementById('clearSearchBtn');
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    this.applyFilters();
                });
            }

            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', () => {
                    this.applyFilters();
                });
            });

            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    this.clearAllFilters();
                });
            }

            window.addEventListener('popstate', () => {
                this.initializeFromURL();
                this.applyFilters();
            });
        },

        debouncedSearch: function(e) {
            this.clearDebounce();
            this.searchTimeout = setTimeout(() => {
                this.applyFilters();
            }, 500);
        },

        clearDebounce: function() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = null;
            }
        },

        getCurrentFilters: function() {
            return {
                search: document.getElementById('searchInput')?.value.trim() || '',
                category: document.getElementById('categoryFilter')?.value || '',
                status: document.getElementById('statusFilter')?.value || '',
                stock_status: document.getElementById('stockFilter')?.value || '',
            };
        },

        hasActiveFilters: function(filters = null) {
            const currentFilters = filters || this.getCurrentFilters();
            return Object.values(currentFilters).some(value => value !== '');
        },

        updateFilterUI: function(filters = null) {
            const currentFilters = filters || this.getCurrentFilters();
            const filtersActive = this.hasActiveFilters(currentFilters);

            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                if (filtersActive) {
                    clearFiltersBtn.classList.remove('hidden');
                } else {
                    clearFiltersBtn.classList.add('hidden');
                }
            }

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

        applyFilters: function() {
            if (this.isLoading) return;

            const filters = this.getCurrentFilters();
            this.currentFilters = filters;

            this.updateFilterUI(filters);
            this.showLoading();

            const cleanFilters = {};
            Object.keys(filters).forEach(key => {
                if (filters[key] !== '' && filters[key] !== null && filters[key] !== undefined) {
                    cleanFilters[key] = filters[key];
                }
            });

            const queryString = new URLSearchParams(cleanFilters).toString();
            const url = `/admin/products?${queryString}&ajax=1`;

            const newUrl = queryString ? `/admin/products?${queryString}` : '/admin/products';
            window.history.pushState({}, '', newUrl);

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
                        const tableBody = document.getElementById('productsTableBody');
                        if (tableBody) {
                            tableBody.innerHTML = data.html;
                        }

                        const productCount = document.getElementById('productCount');
                        if (productCount && data.count !== undefined) {
                            productCount.textContent = `(${data.count})`;
                        }

                        if (data.stats) {
                            this.updateStats(data.stats);
                        }

                        if (typeof AOS !== 'undefined') {
                            AOS.refresh();
                        }

                    }
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    this.showErrorMessage('Failed to apply filters. Please try again.');

                    setTimeout(() => {
                        window.location.href = newUrl;
                    }, 1500);
                })
                .finally(() => {
                    this.hideLoading();
                });
        },

        clearAllFilters: function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) searchInput.value = '';

            const filterSelects = document.querySelectorAll('select[id$="Filter"]');
            filterSelects.forEach(select => {
                select.value = '';
            });

            this.applyFilters();
            this.showFilterMessage('All filters cleared');
        },

        initializeFromURL: function() {
            const urlParams = new URLSearchParams(window.location.search);

            const searchInput = document.getElementById('searchInput');
            if (searchInput && urlParams.has('search')) {
                searchInput.value = urlParams.get('search');
            }

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

        applyOnPageLoad: function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.toString()) {
                setTimeout(() => {
                    if (this.hasActiveFilters()) {
                        this.applyFilters();
                    }
                }, 300);
            }
        },

        updateStats: function(stats) {
            const totalProducts = document.getElementById('totalProducts');
            if (totalProducts && stats.total !== undefined) {
                totalProducts.textContent = stats.total;
            }

            const activeProducts = document.getElementById('activeProducts');
            if (activeProducts && stats.active !== undefined) {
                activeProducts.textContent = stats.active;
            }

            const categoriesCount = document.getElementById('categoriesCount');
            if (categoriesCount && stats.categories !== undefined) {
                categoriesCount.textContent = stats.categories;
            }
        },

        showLoading: function() {
            this.isLoading = true;

            const searchLoading = document.getElementById('searchLoading');
            if (searchLoading) {
                searchLoading.classList.remove('hidden');
            }

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

        hideLoading: function() {
            this.isLoading = false;

            const searchLoading = document.getElementById('searchLoading');
            if (searchLoading) {
                searchLoading.classList.add('hidden');
            }

            const overlay = document.querySelector('.table-loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        },

        showFilterMessage: function(message) {
            const existingMessage = document.querySelector('.filter-message');
            if (existingMessage) {
                existingMessage.remove();
            }

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

            setTimeout(() => {
                messageElement.classList.add('animate-slideOut');
                setTimeout(() => {
                    if (messageElement.parentNode) {
                        messageElement.parentNode.removeChild(messageElement);
                    }
                }, 300);
            }, 3000);
        },

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

    // ========== DISCOUNT MANAGEMENT SYSTEM ==========
    const DiscountManager = {
        init: function() {
            this.bindDiscountEvents();
            this.initializeDiscountFields();
        },

        bindDiscountEvents: function() {
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('discount-type-select')) {
                    this.handleDiscountTypeChange(e.target);
                }

                if (e.target.classList.contains('variant-discount-type') &&
                    !e.target.classList.contains('edit-variant-discount-type')) {
                    this.handleVariantDiscountTypeChange(e.target);
                }

                if (e.target.classList.contains('edit-variant-discount-type')) {
                    const index = this.getVariantIndex(e.target);
                    this.handleEditVariantDiscountTypeChange(e.target, index);
                }

                if (e.target.classList.contains('edit-variant-discount-value') ||
                    e.target.classList.contains('variant-discount-value')) {
                    const index = this.getVariantIndex(e.target);
                    this.updateDiscountPreview(index);
                }

                if (e.target.name && (e.target.name.includes('discount_start') || e.target.name
                        .includes('discount_end'))) {
                    const index = this.getVariantIndex(e.target);
                    this.updateDiscountPeriod(index);
                }
            });

            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('variant-has-discount')) {
                    const index = this.getVariantIndex(e.target);
                    this.toggleDiscountStatus(e.target, index);
                }

                if (e.target.classList.contains('edit-variant-has-discount')) {
                    const index = this.getVariantIndex(e.target);
                    this.toggleDiscountStatusEdit(e.target, index);
                }
            });
        },

        getVariantIndex: function(element) {
            const variantRow = element.closest('.variant-row') || element.closest('[data-index]');
            if (variantRow && variantRow.dataset.index) {
                return parseInt(variantRow.dataset.index);
            }

            const name = element.getAttribute('name');
            if (name && name.includes('[')) {
                const match = name.match(/variants\[(\d+)\]/);
                if (match) return parseInt(match[1]);
            }

            return 0;
        },

        initializeDiscountFields: function() {
            document.querySelectorAll('.discount-type-select').forEach(select => {
                this.handleDiscountTypeChange(select);
            });

            document.querySelectorAll('.variant-discount-type').forEach(select => {
                const index = this.getVariantIndex(select);
                this.handleVariantDiscountTypeChange(select, index);
            });

            document.querySelectorAll('.edit-variant-discount-type').forEach(select => {
                const index = this.getVariantIndex(select);
                this.handleEditVariantDiscountTypeChange(select, index);
            });

            setTimeout(() => {
                this.calculateAllDiscountPreviews();
            }, 500);
        },

        handleDiscountTypeChange: function(selectElement) {
            const discountContainer = selectElement.closest('.space-y-4') ||
                selectElement.closest('.grid').parentElement;

            if (!discountContainer) return;

            const discountValueField = discountContainer.querySelector('.discount-value-field');
            const dateFields = discountContainer.querySelectorAll(
                '[name$="discount_start"], [name$="discount_end"]');
            const discountPrefix = discountValueField ? discountValueField.querySelector('.discount-prefix') :
                null;

            if (selectElement.value) {
                if (discountValueField) {
                    discountValueField.style.display = 'block';
                    if (discountPrefix) {
                        discountPrefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
                    }
                }

                dateFields.forEach(field => {
                    field.closest('div').style.display = 'block';
                });
            } else {
                if (discountValueField) {
                    discountValueField.style.display = 'none';
                }

                dateFields.forEach(field => {
                    field.closest('div').style.display = 'none';
                });

                const valueInput = discountValueField ? discountValueField.querySelector(
                    'input[type="number"]') : null;
                if (valueInput) {
                    valueInput.value = '';
                }
                dateFields.forEach(field => field.value = '');
            }
        },

        handleVariantDiscountTypeChange: function(selectElement, index = null) {
            if (index === null) index = this.getVariantIndex(selectElement);

            const variantRow = selectElement.closest('.variant-discount-section') ||
                selectElement.closest('.variant-row');
            if (!variantRow) return;

            const discountValueField = variantRow.querySelector('.variant-discount-value-field');
            const discountPreview = variantRow.querySelector('.discount-preview');
            const discountPrefix = discountValueField ? discountValueField.querySelector(
                '.variant-discount-prefix') : null;

            if (selectElement.value) {
                if (discountValueField) {
                    discountValueField.style.display = 'block';
                    if (discountPrefix) {
                        discountPrefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
                    }
                }

                if (discountPreview) {
                    discountPreview.style.display = 'block';
                }

                this.updateDiscountPreview(index);
            } else {
                if (discountValueField) {
                    discountValueField.style.display = 'none';
                }

                if (discountPreview) {
                    discountPreview.style.display = 'none';
                }

                const valueInput = discountValueField ? discountValueField.querySelector(
                    'input[type="number"]') : null;
                if (valueInput) {
                    valueInput.value = '';
                }
            }
        },

        handleEditVariantDiscountTypeChange: function(selectElement, index) {
            const variantRow = selectElement.closest('.variant-discount-section') ||
                selectElement.closest('.variant-row');
            if (!variantRow) return;

            const discountValueField = variantRow.querySelector('.variant-discount-value-field');
            const discountPreview = variantRow.querySelector('.discount-preview');
            const discountPrefix = discountValueField ? discountValueField.querySelector(
                '.variant-discount-prefix') : null;

            if (selectElement.value) {
                if (discountValueField) {
                    discountValueField.style.display = 'block';
                    if (discountPrefix) {
                        discountPrefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
                    }
                }

                if (discountPreview) {
                    discountPreview.style.display = 'block';
                }

                this.updateDiscountPreviewEdit(index);
            } else {
                if (discountValueField) {
                    discountValueField.style.display = 'none';
                }

                if (discountPreview) {
                    discountPreview.style.display = 'none';
                }

                const valueInput = discountValueField ? discountValueField.querySelector(
                    'input[type="number"]') : null;
                if (valueInput) {
                    valueInput.value = '';
                }
            }
        },

        updateDiscountPreview: function(index) {
            const variantRow = document.querySelector(`[data-index="${index}"]`) ||
                document.querySelector(`.variant-row:nth-child(${index + 1})`);
            if (!variantRow) return;

            const priceInput = variantRow.querySelector('input[name*="[price]"]');
            const discountTypeSelect = variantRow.querySelector('select[name*="[discount_type]"]');
            const discountValueInput = variantRow.querySelector('input[name*="[discount_value]"]');

            if (!priceInput || !discountTypeSelect || !discountValueInput) return;

            const originalPrice = parseFloat(priceInput.value) || 0;
            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;

            let discountedPrice = originalPrice;
            let discountText = '';

            if (discountType === 'percentage' && discountValue > 0) {
                const discountAmount = originalPrice * (discountValue / 100);
                discountedPrice = originalPrice - discountAmount;
                discountText = `${discountValue}% off`;
            } else if (discountType === 'fixed' && discountValue > 0) {
                discountedPrice = originalPrice - discountValue;
                discountText = `$${discountValue.toFixed(2)} off`;
            }

            const previewValue = variantRow.querySelector('.discount-preview-value');
            const previewType = variantRow.querySelector('.discount-preview-type');

            if (previewValue) {
                previewValue.textContent = `$${discountedPrice.toFixed(2)}`;
            }

            if (previewType) {
                previewType.textContent = discountText || 'No discount';
            }

            this.updateDiscountPeriod(index);
        },

        updateDiscountPreviewEdit: function(index) {
            const variantRow = document.querySelector(`[data-index="${index}"]`) ||
                document.querySelector(`.variant-row:nth-child(${index + 1})`);
            if (!variantRow) return;

            const priceInput = variantRow.querySelector('input[name*="[price]"]');
            const discountTypeSelect = variantRow.querySelector('select[name*="[discount_type]"]');
            const discountValueInput = variantRow.querySelector('input[name*="[discount_value]"]');

            if (!priceInput || !discountTypeSelect || !discountValueInput) return;

            const originalPrice = parseFloat(priceInput.value) || 0;
            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;

            let discountedPrice = originalPrice;
            let discountText = '';
            let savings = 0;

            if (discountType === 'percentage' && discountValue > 0) {
                const discountAmount = originalPrice * (discountValue / 100);
                discountedPrice = originalPrice - discountAmount;
                savings = discountAmount;
                discountText = `${discountValue}% off`;
            } else if (discountType === 'fixed' && discountValue > 0) {
                discountedPrice = originalPrice - discountValue;
                savings = discountValue;
                discountText = `$${discountValue.toFixed(2)} off`;
            }

            const previewValue = variantRow.querySelector('.discount-preview-value') ||
                variantRow.querySelector(`#discountPreviewValueEdit-${index}`);
            const previewType = variantRow.querySelector('.discount-preview-type') ||
                variantRow.querySelector(`#discountPreviewTypeEdit-${index}`);

            if (previewValue) {
                previewValue.textContent = `$${discountedPrice.toFixed(2)}`;
            }

            if (previewType) {
                previewType.textContent = discountText || 'No discount';
            }

            const originalPriceDisplay = variantRow.querySelector(`#originalPrice-${index}`);
            const discountedPriceDisplay = variantRow.querySelector(`#discountedPrice-${index}`);
            const savingsDisplay = variantRow.querySelector(`#savings-${index}`);
            const discountTypeDisplay = variantRow.querySelector(`#discountType-${index}`);
            const savingsPercentageDisplay = variantRow.querySelector(`#savingsPercentage-${index}`);

            if (originalPriceDisplay) {
                originalPriceDisplay.textContent = `$${originalPrice.toFixed(2)}`;
            }

            if (discountedPriceDisplay) {
                discountedPriceDisplay.textContent = `$${discountedPrice.toFixed(2)}`;
            }

            if (savingsDisplay) {
                savingsDisplay.textContent = `$${savings.toFixed(2)}`;
            }

            if (discountTypeDisplay) {
                discountTypeDisplay.textContent = discountText || 'No discount applied';
            }

            if (savingsPercentageDisplay && originalPrice > 0) {
                const savingsPercent = (savings / originalPrice) * 100;
                savingsPercentageDisplay.textContent = `${savingsPercent.toFixed(1)}% off`;
            }

            const statusBadge = variantRow.querySelector(`#discountStatusBadge-${index}`);
            if (statusBadge) {
                if (discountType && discountValue > 0) {
                    statusBadge.textContent = 'ON';
                    statusBadge.className = 'text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-800';
                } else {
                    statusBadge.textContent = 'OFF';
                    statusBadge.className = 'text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600';
                }
            }

            const activeStatus = variantRow.querySelector(`#discountActiveStatus-${index}`);
            if (activeStatus) {
                if (discountType && discountValue > 0) {
                    activeStatus.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Active';
                    activeStatus.className =
                        'text-xs px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium';
                } else {
                    activeStatus.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Configure';
                    activeStatus.className =
                        'text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium';
                }
            }

            this.updateDiscountPeriodEdit(index);
        },

        updateDiscountPeriod: function(index) {
            const variantRow = document.querySelector(`[data-index="${index}"]`) ||
                document.querySelector(`.variant-row:nth-child(${index + 1})`);
            if (!variantRow) return;

            const startInput = variantRow.querySelector('input[name*="[discount_start]"]');
            const endInput = variantRow.querySelector('input[name*="[discount_end]"]');

            if (!startInput || !endInput) return;

            const startDate = startInput.value;
            const endDate = endInput.value;

            const periodDisplay = variantRow.querySelector('.discount-period') ||
                variantRow.querySelector(`#periodDisplay-${index}`);

            if (periodDisplay) {
                if (startDate && endDate) {
                    const start = new Date(startDate).toLocaleDateString();
                    const end = new Date(endDate).toLocaleDateString();
                    periodDisplay.textContent = `${start} - ${end}`;
                } else if (startDate) {
                    const start = new Date(startDate).toLocaleDateString();
                    periodDisplay.textContent = `From ${start}`;
                } else if (endDate) {
                    const end = new Date(endDate).toLocaleDateString();
                    periodDisplay.textContent = `Until ${end}`;
                } else {
                    periodDisplay.textContent = 'Not set';
                }
            }
        },

        updateDiscountPeriodEdit: function(index) {
            const variantRow = document.querySelector(`[data-index="${index}"]`) ||
                document.querySelector(`.variant-row:nth-child(${index + 1})`);
            if (!variantRow) return;

            const startInput = variantRow.querySelector('input[name*="[discount_start]"]');
            const endInput = variantRow.querySelector('input[name*="[discount_end]"]');

            if (!startInput || !endInput) return;

            const startDate = startInput.value;
            const endDate = endInput.value;

            const periodDisplay = variantRow.querySelector('.discount-period') ||
                variantRow.querySelector(`#periodDisplay-${index}`) ||
                variantRow.querySelector(`#discountPeriodEdit-${index}`);

            if (periodDisplay) {
                if (startDate && endDate) {
                    const start = new Date(startDate).toLocaleDateString();
                    const end = new Date(endDate).toLocaleDateString();
                    periodDisplay.textContent = `${start} - ${end}`;
                } else if (startDate) {
                    const start = new Date(startDate).toLocaleDateString();
                    periodDisplay.textContent = `From ${start}`;
                } else if (endDate) {
                    const end = new Date(endDate).toLocaleDateString();
                    periodDisplay.textContent = `Until ${end}`;
                } else {
                    periodDisplay.textContent = 'Not set';
                }
            }

            const lastUpdated = variantRow.querySelector(`#lastUpdated-${index}`);
            if (lastUpdated) {
                const now = new Date();
                lastUpdated.textContent = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        },

        toggleDiscountStatus: function(checkbox, index) {
            const variantRow = checkbox.closest('.variant-row');
            const discountTypeSelect = variantRow.querySelector('select[name*="[discount_type]"]');
            const discountValueInput = variantRow.querySelector('input[name*="[discount_value]"]');

            if (checkbox.checked) {
                if (!discountTypeSelect.value || !discountValueInput.value) {
                    this.showValidationMessage(index, 'Please configure discount type and value first');
                    checkbox.checked = false;
                    return;
                }

                discountTypeSelect.disabled = false;
                discountValueInput.disabled = false;

                this.updateDiscountPreview(index);
            } else {
                discountTypeSelect.disabled = true;
                discountValueInput.disabled = true;
            }
        },

        toggleDiscountStatusEdit: function(checkbox, index) {
            const variantRow = checkbox.closest('.variant-row');
            const discountTypeSelect = variantRow.querySelector('select[name*="[discount_type]"]');
            const discountValueInput = variantRow.querySelector('input[name*="[discount_value]"]');

            if (checkbox.checked) {
                if (!discountTypeSelect.value || !discountValueInput.value) {
                    this.showValidationMessage(index, 'Please configure discount type and value first');
                    checkbox.checked = false;
                    return;
                }

                discountTypeSelect.disabled = false;
                discountValueInput.disabled = false;

                this.updateDiscountPreviewEdit(index);
            } else {
                discountTypeSelect.disabled = true;
                discountValueInput.disabled = true;
            }
        },

        calculateAllDiscountPreviews: function() {
            const addVariants = document.querySelectorAll('#variantsContainer .variant-row');
            addVariants.forEach((variant, index) => {
                this.updateDiscountPreview(index);
            });

            const editVariants = document.querySelectorAll('#editVariantsContainer .variant-row');
            editVariants.forEach((variant, index) => {
                this.updateDiscountPreviewEdit(index);
            });
        },

        showValidationMessage: function(index, message) {
            const variantRow = document.querySelector(`[data-index="${index}"]`) ||
                document.querySelector(`.variant-row:nth-child(${index + 1})`);
            if (!variantRow) return;

            const validationDiv = variantRow.querySelector(`#validationMessage-${index}`) ||
                variantRow.querySelector('.validation-message');

            if (validationDiv) {
                const messageText = validationDiv.querySelector(`#validationText-${index}`) ||
                    validationDiv.querySelector('.validation-text');

                if (messageText) {
                    messageText.textContent = message;
                }

                validationDiv.classList.remove('hidden');

                setTimeout(() => {
                    validationDiv.classList.add('hidden');
                }, 5000);
            }
        },

        calculateVariantDiscount: function(index) {
            const variantRow = document.querySelector(`[data-index="${index}"]`) ||
                document.querySelector(`.variant-row:nth-child(${index + 1})`);
            if (!variantRow) return;

            const priceInput = variantRow.querySelector('input[name*="[price]"]');
            const discountTypeSelect = variantRow.querySelector('select[name*="[discount_type]"]');
            const discountValueInput = variantRow.querySelector('input[name*="[discount_value]"]');

            if (!priceInput || !discountTypeSelect || !discountValueInput) {
                this.showValidationMessage(index, 'Please fill in price and discount details first');
                return;
            }

            const originalPrice = parseFloat(priceInput.value) || 0;
            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;

            if (originalPrice <= 0) {
                this.showValidationMessage(index, 'Please enter a valid price');
                return;
            }

            if (!discountType) {
                this.showValidationMessage(index, 'Please select a discount type');
                return;
            }

            if (discountValue <= 0) {
                this.showValidationMessage(index, 'Please enter a valid discount value');
                return;
            }

            if (discountType === 'percentage' && discountValue > 100) {
                this.showValidationMessage(index, 'Percentage discount cannot exceed 100%');
                return;
            }

            if (discountType === 'fixed' && discountValue >= originalPrice) {
                this.showValidationMessage(index, 'Fixed discount cannot exceed original price');
                return;
            }

            this.updateDiscountPreviewEdit(index);

            SafeSwal.fire({
                icon: 'success',
                title: 'Discount Calculated',
                text: 'Discount has been calculated successfully',
                timer: 2000,
                showConfirmButton: false
            });
        }
    };

    // ========== IMAGE MANAGEMENT SYSTEM ==========
    const ImageManager = {
        // Configuration
        maxAdditionalImages: 5,
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'image/avif'
        ],

        // State
        currentImageIndex: 1,
        additionalImages: [],

        // Initialize
        init: function() {
            this.bindEvents();
            this.updateImageCount();
            this.updateProgressBar();
        },

        // Bind all image-related events
        bindEvents: function() {
            // Primary image upload
            const primaryInput = document.getElementById('primaryImageInput');
            if (primaryInput) {
                primaryInput.addEventListener('change', (e) => this.handlePrimaryImageUpload(e));
            }

            // Clear primary image button
            const clearPrimaryBtn = document.querySelector('[onclick="ImageManager.clearPrimaryImage()"]');
            if (clearPrimaryBtn) {
                clearPrimaryBtn.addEventListener('click', () => this.clearPrimaryImage());
            }

            // Event delegation for additional images container
            const container = document.getElementById('additionalImagesContainer');
            if (container) {
                container.addEventListener('change', (e) => {
                    if (e.target.type === 'file' && e.target.closest('.additional-image-row')) {
                        const index = e.target.closest('.additional-image-row').dataset.index;
                        this.handleAdditionalImageUpload(e.target, index);
                    }
                });

                container.addEventListener('input', (e) => {
                    if (e.target.name && e.target.name.includes('alt_text')) {
                        this.validateAltText(e.target);
                    }
                });

                container.addEventListener('click', (e) => {
                    // Remove image button
                    if (e.target.closest('[onclick*="removeAdditionalImage"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const index = button.closest('.additional-image-row').dataset.index;
                        this.removeAdditionalImage(index);
                    }

                    // Clear preview button
                    if (e.target.closest('[onclick*="clearAdditionalImage"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const index = button.closest('.additional-image-row').dataset.index;
                        this.clearAdditionalImage(index);
                    }
                });
            }
        },

        // Handle primary image upload
        handlePrimaryImageUpload: function(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!this.validateFile(file)) {
                event.target.value = '';
                return;
            }

            this.showPrimaryImagePreview(file);
        },

        // Validate file
        validateFile: function(file) {
            // Check file type
            if (!this.allowedTypes.includes(file.type.toLowerCase())) {
                const typesList = this.allowedTypes.map(t => t.split('/')[1].toUpperCase()).join(', ');
                this.showNotification(`Invalid file type. Allowed types: ${typesList}`, 'error');
                return false;
            }

            // Check file size
            if (file.size > this.maxFileSize) {
                this.showNotification(`File size exceeds ${this.formatFileSize(this.maxFileSize)} limit`,
                    'error');
                return false;
            }

            return true;
        },

        // Show primary image preview
        showPrimaryImagePreview: function(file) {
            const previewContainer = document.getElementById('primaryImagePreview');
            const uploadContent = document.getElementById('primaryUploadContent');
            const previewImg = document.getElementById('primaryPreviewImg');
            const fileName = document.getElementById('primaryFileName');
            const fileSize = document.getElementById('primaryFileSize');
            const fileDimensions = document.getElementById('primaryFileDimensions');

            // Create object URL for preview
            const objectUrl = URL.createObjectURL(file);
            const img = new Image();

            img.onload = () => {
                previewImg.src = objectUrl;
                previewContainer.classList.remove('hidden');
                uploadContent.classList.add('hidden');

                fileName.textContent = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file
                    .name;
                fileSize.textContent = this.formatFileSize(file.size);
                fileDimensions.textContent = `${img.width} × ${img.height} px`;

                // Add success styling
                previewContainer.closest('.primary-upload-area').classList.add('border-green-300',
                    'bg-green-50');

                URL.revokeObjectURL(objectUrl);
            };

            img.onerror = () => {
                this.showNotification('Failed to load image preview', 'error');
                this.clearPrimaryImage();
            };

            img.src = objectUrl;
        },

        // Clear primary image
        clearPrimaryImage: function() {
            const input = document.getElementById('primaryImageInput');
            const previewContainer = document.getElementById('primaryImagePreview');
            const uploadContent = document.getElementById('primaryUploadContent');

            if (input) input.value = '';
            previewContainer.classList.add('hidden');
            uploadContent.classList.remove('hidden');

            // Remove success styling
            previewContainer.closest('.primary-upload-area').classList.remove('border-green-300',
                'bg-green-50');
        },

        // Add additional image row
        addImageRow: function() {
            const container = document.getElementById('additionalImagesContainer');
            const currentCount = container.querySelectorAll('.additional-image-row').length;

            if (currentCount >= this.maxAdditionalImages) {
                this.showNotification(`Maximum of ${this.maxAdditionalImages} additional images allowed`,
                    'error');
                return false;
            }

            const rowIndex = this.currentImageIndex;
            this.currentImageIndex++;

            const newRow = document.createElement('div');
            newRow.className = 'additional-image-row border border-gray-200 rounded-lg p-4 bg-gray-50';
            newRow.dataset.index = rowIndex;

            newRow.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-900">Additional Image ${rowIndex}</span>
                <button type="button" onclick="ImageManager.removeAdditionalImage(${rowIndex})" 
                        class="text-red-600 hover:text-red-700 text-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- File Upload Area -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors duration-200 bg-white relative">
                <input type="file" 
                       name="images[${rowIndex}][image]" 
                       accept="image/*"
                       class="file-input absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                       onchange="ImageManager.handleAdditionalImageUpload(event, ${rowIndex})">
                
                <div id="uploadContent-${rowIndex}" class="upload-content text-center py-4">
                    <div class="mx-auto w-12 h-12 mb-2 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-gray-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Click to upload</p>
                    <p class="text-xs text-gray-500">JPG, PNG, GIF, WebP, SVG up to 5MB</p>
                </div>
                
                <!-- Preview Container -->
                <div id="preview-${rowIndex}" class="hidden">
                    <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                        <div class="w-12 h-12 rounded overflow-hidden bg-gray-200 flex items-center justify-center">
                            <img id="previewImg-${rowIndex}" class="w-full h-full object-cover"
                                 src="" alt="Preview">
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p id="fileName-${rowIndex}" class="text-sm font-medium text-gray-900 truncate"></p>
                            <p id="fileSize-${rowIndex}" class="text-xs text-gray-500"></p>
                        </div>
                        <button type="button" onclick="ImageManager.clearAdditionalImage(${rowIndex})"
                                class="text-gray-400 hover:text-red-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Additional Fields -->
            <div class="mt-3 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">
                        Alt Text <span class="text-gray-400 text-xs">(optional)</span>
                    </label>
                    <input type="text" 
                           name="images[${rowIndex}][alt_text]" 
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                           placeholder="Describe the image for accessibility"
                           maxlength="125"
                           oninput="ImageManager.validateAltText(this)">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">
                        Display Order
                    </label>
                    <input type="number" 
                           name="images[${rowIndex}][sort_order]" 
                           value="${rowIndex}"
                           min="1"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            
            <!-- Hidden Fields -->
            <input type="hidden" name="images[${rowIndex}][is_primary]" value="0">
        `;

            container.appendChild(newRow);
            this.updateImageCount();
            this.updateProgressBar();

            return true;
        },

        // Handle additional image upload
        handleAdditionalImageUpload: function(event, index) {
            const file = event.target.files[0];
            if (!file) return;

            if (!this.validateFile(file)) {
                event.target.value = '';
                return;
            }

            this.showAdditionalImagePreview(file, index);
        },

        // Show additional image preview
        showAdditionalImagePreview: function(file, index) {
            const previewContainer = document.getElementById(`preview-${index}`);
            const uploadContent = document.getElementById(`uploadContent-${index}`);
            const previewImg = document.getElementById(`previewImg-${index}`);
            const fileName = document.getElementById(`fileName-${index}`);
            const fileSize = document.getElementById(`fileSize-${index}`);

            if (!previewContainer || !uploadContent) return;

            // Create object URL for preview
            const objectUrl = URL.createObjectURL(file);
            const img = new Image();

            img.onload = () => {
                previewImg.src = objectUrl;
                previewContainer.classList.remove('hidden');
                uploadContent.classList.add('hidden');

                fileName.textContent = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file
                    .name;
                fileSize.textContent = this.formatFileSize(file.size);

                URL.revokeObjectURL(objectUrl);
            };

            img.onerror = () => {
                this.showNotification('Failed to load image preview', 'error');
                this.clearAdditionalImage(index);
            };

            img.src = objectUrl;
        },

        // Clear additional image
        clearAdditionalImage: function(index) {
            const row = document.querySelector(`[data-index="${index}"]`);
            if (!row) return;

            const fileInput = row.querySelector('input[type="file"]');
            const previewContainer = document.getElementById(`preview-${index}`);
            const uploadContent = document.getElementById(`uploadContent-${index}`);
            const altText = row.querySelector('input[name*="alt_text"]');
            const sortOrder = row.querySelector('input[name*="sort_order"]');

            if (fileInput) fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (uploadContent) uploadContent.classList.remove('hidden');
            if (altText) altText.value = '';
            if (sortOrder) sortOrder.value = index;
        },

        // Remove additional image row
        removeAdditionalImage: function(index) {
            SafeSwal.fire({
                title: 'Remove Image?',
                text: 'Are you sure you want to remove this image?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const row = document.querySelector(`[data-index="${index}"]`);
                    if (row) {
                        row.remove();
                        this.updateImageCount();
                        this.updateProgressBar();
                        this.showNotification('Image removed successfully', 'success');
                    }
                }
            });
        },

        // Validate alt text
        validateAltText: function(input) {
            const value = input.value.trim();
            const maxLength = 125;

            if (value.length > maxLength) {
                input.classList.add('border-red-500');
                this.showNotification('Alt text should be under 125 characters', 'warning');
            } else {
                input.classList.remove('border-red-500');
            }
        },

        // Update image count display
        updateImageCount: function() {
            const container = document.getElementById('additionalImagesContainer');
            const count = container ? container.querySelectorAll('.additional-image-row').length : 0;
            const countElement = document.getElementById('additionalImageCount');

            if (countElement) {
                countElement.textContent = count;

                // Update color based on count
                if (count >= this.maxAdditionalImages) {
                    countElement.classList.add('text-red-600', 'font-bold');
                    countElement.classList.remove('text-gray-600', 'text-yellow-600');
                } else if (count >= this.maxAdditionalImages - 1) {
                    countElement.classList.add('text-yellow-600', 'font-semibold');
                    countElement.classList.remove('text-gray-600', 'text-red-600');
                } else {
                    countElement.classList.add('text-gray-600');
                    countElement.classList.remove('text-yellow-600', 'text-red-600', 'font-bold',
                        'font-semibold');
                }
            }
        },

        // Update progress bar
        updateProgressBar: function() {
            const container = document.getElementById('additionalImagesContainer');
            const count = container ? container.querySelectorAll('.additional-image-row').length : 0;
            const progress = (count / this.maxAdditionalImages) * 100;
            const progressBar = document.getElementById('imageProgressBar');

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

        // Validate all images before form submission
        validateImages: function() {
            let isValid = true;
            const errors = [];

            // Check primary image
            const primaryInput = document.getElementById('primaryImageInput');
            if (primaryInput && !primaryInput.files[0]) {
                errors.push('Primary image is required');
                isValid = false;
            }

            // Check additional images
            const additionalRows = document.querySelectorAll('.additional-image-row');
            additionalRows.forEach(row => {
                const fileInput = row.querySelector('input[type="file"]');
                const altText = row.querySelector('input[name*="alt_text"]');

                if (fileInput.files[0]) {
                    // Validate file size
                    if (fileInput.files[0].size > this.maxFileSize) {
                        errors.push(`Image ${row.dataset.index}: File too large (max 5MB)`);
                        isValid = false;
                    }

                    // Check alt text length
                    if (altText && altText.value.length > 125) {
                        errors.push(
                            `Image ${row.dataset.index}: Alt text too long (max 125 characters)`);
                        isValid = false;
                    }
                }
            });

            // Show errors if any
            if (errors.length > 0) {
                const errorHtml = errors.map(error => `<li class="text-sm">${error}</li>`).join('');

                SafeSwal.fire({
                    icon: 'warning',
                    title: 'Image Validation Issues',
                    html: `<div class="text-left"><ul class="list-disc pl-4">${errorHtml}</ul></div>`,
                    confirmButtonText: 'Continue Anyway',
                    showCancelButton: true,
                    cancelButtonText: 'Fix Issues'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        isValid = false;
                    }
                });
            }

            return isValid;
        }
    };

    // ========== EDIT MODAL IMAGE MANAGEMENT ==========
    // ========== EDIT MODAL IMAGE MANAGEMENT ==========
    const EditImageManager = {
        // Configuration
        maxNewImages: 10,
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'image/avif'
        ],

        // State
        currentNewImageIndex: 1,
        deletedImages: [],

        // Initialize edit modal images
        initEditModal: function(productData) {
            this.bindEditImageEvents();
            this.populateCurrentImages(productData.images || []);
            this.updateNewImageCount();
            window.deletedImages = [];
            this.updateDeletionStatusHeader();
        },

        // Bind edit modal image events
        bindEditImageEvents: function() {
            // Event delegation for new images container
            const container = document.getElementById('newImagesContainer');
            if (container) {
                container.addEventListener('change', (e) => {
                    if (e.target.type === 'file' && e.target.closest('.new-image-row')) {
                        const index = e.target.closest('.new-image-row').dataset.index;
                        this.handleNewImageUpload(e.target, index);
                    }
                });

                container.addEventListener('input', (e) => {
                    if (e.target.name && e.target.name.includes('alt_text')) {
                        this.validateNewAltText(e.target);
                    }
                });

                container.addEventListener('click', (e) => {
                    // Remove new image button
                    if (e.target.closest('[onclick*="removeNewImageRow"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const row = button.closest('.new-image-row');
                        this.removeNewImageRow(row);
                    }

                    // Clear preview button
                    if (e.target.closest('[onclick*="clearNewImageInput"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const index = button.closest('.new-image-row').dataset.index;
                        this.clearNewImageInput(index);
                    }
                });
            }

            // Event delegation for current images
            const currentContainer = document.getElementById('currentImages');
            if (currentContainer) {
                currentContainer.addEventListener('click', (e) => {
                    // Delete image button
                    if (e.target.closest('[onclick*="markImageForDeletion"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const imageId = button.getAttribute('onclick').match(/\d+/)[0];
                        this.markImageForDeletion(parseInt(imageId));
                    }

                    // Restore image button
                    if (e.target.closest('[onclick*="restoreImage"]')) {
                        e.preventDefault();
                        const button = e.target.closest('button');
                        const imageId = button.getAttribute('onclick').match(/\d+/)[0];
                        this.restoreImage(parseInt(imageId));
                    }
                });
            }
        },

        // Populate current images from database
        populateCurrentImages: function(images) {
            const container = document.getElementById('currentImages');
            if (!container) return;

            container.innerHTML = '';

            if (!images || images.length === 0) {
                container.innerHTML = `
                <div class="text-center py-6 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">No images found</p>
                </div>
            `;
                return;
            }

            images.forEach((image, index) => {
                const imageCard = this.createCurrentImageCard(image, index);
                container.appendChild(imageCard);
            });
        },

        // Create current image card HTML
        createCurrentImageCard: function(image, index) {
            const isPrimary = image.is_primary || false;
            const imageId = image.id;
            const imageUrl = image.image_path ? `/storage/${image.image_path}` :
                '/storage/products/placeholder.jpg';
            const altText = image.alt_text || `Product Image ${index + 1}`;

            return document.createRange().createContextualFragment(`
            <div class="current-image-card border border-gray-200 rounded-lg p-4 bg-white transition-shadow duration-200 relative" id="current-image-${imageId}">
                <!-- Deletion Overlay -->
                <div id="deletion-overlay-${imageId}" class="hidden absolute inset-0 z-20 pointer-events-none">
                    <div class="absolute inset-0 bg-red-500/10 rounded-lg border-2 border-red-400"></div>
                </div>
                
                <div class="flex items-start justify-between mb-3 relative z-10">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <img src="${imageUrl}" 
                                 alt="${altText}"
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200"
                                 onerror="this.src='/storage/products/placeholder.jpg'">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Image ${index + 1}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <!-- Primary Radio -->
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="primary_image" 
                                   value="${imageId}"
                                   ${isPrimary ? 'checked' : ''}
                                   class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                                   onchange="EditImageManager.updatePrimaryImage(${imageId})">
                            <span class="ml-2 text-xs text-gray-700">Primary</span>
                        </label>
                        
                        <!-- Delete Button -->
                        <button type="button" 
                                onclick="EditImageManager.markImageForDeletion(${imageId})" 
                                class="text-red-600 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors"
                                title="Delete Image">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Deletion Status Indicator -->
                <div id="deletion-status-${imageId}" class="hidden mt-2">
                    <div class="flex items-center p-2 bg-red-50 border border-red-200 rounded-lg">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <span class="text-xs font-medium text-red-700">Will be deleted when saved</span>
                        <button type="button" 
                                onclick="EditImageManager.restoreImage(${imageId})"
                                class="ml-auto text-xs text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-undo mr-1"></i> Undo
                        </button>
                    </div>
                </div>
                
                <!-- Hidden fields for existing images -->
                <input type="hidden" name="existing_images[${index}][id]" value="${imageId}">
                <input type="hidden" name="existing_images[${index}][alt_text]" value="${image.alt_text || ''}">
                <input type="hidden" name="existing_images[${index}][sort_order]" value="${image.sort_order || index}">
                <input type="hidden" name="existing_images[${index}][is_primary]" value="${isPrimary ? '1' : '0'}">
            </div>
        `);
        },

        // Mark image for deletion
        markImageForDeletion: function(imageId) {
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
                    // Add to deleted images array
                    if (!window.deletedImages) window.deletedImages = [];
                    if (!window.deletedImages.includes(imageId)) {
                        window.deletedImages.push(imageId);
                    }

                    // Update hidden input
                    const deletedInput = document.getElementById('deletedImagesInput');
                    if (deletedInput) {
                        deletedInput.value = JSON.stringify(window.deletedImages);
                    }

                    // Find and mark the image card
                    const imageCard = document.getElementById(`current-image-${imageId}`);
                    if (imageCard) {
                        // Add deletion styling
                        imageCard.classList.add('border-red-400', 'bg-red-50/30');
                        imageCard.classList.remove('border-gray-200', 'bg-white', 'hover:shadow-md');

                        // Show deletion overlay
                        const deletionOverlay = document.getElementById(`deletion-overlay-${imageId}`);
                        if (deletionOverlay) {
                            deletionOverlay.classList.remove('hidden');
                        }

                        // Show deletion status indicator
                        const deletionStatus = document.getElementById(`deletion-status-${imageId}`);
                        if (deletionStatus) {
                            deletionStatus.classList.remove('hidden');
                        }

                        // Add grayscale effect to the image
                        const img = imageCard.querySelector('img');
                        if (img) {
                            img.classList.add('grayscale', 'opacity-70');
                        }

                        // Disable the primary radio button
                        const radioBtn = imageCard.querySelector('input[type="radio"]');
                        if (radioBtn) {
                            radioBtn.disabled = true;
                            radioBtn.checked = false;
                            radioBtn.closest('label').classList.add('opacity-50', 'cursor-not-allowed');
                        }

                        // Change delete button to restore button
                        const deleteBtn = imageCard.querySelector(
                            'button[onclick*="markImageForDeletion"]');
                        if (deleteBtn) {
                            deleteBtn.classList.remove('text-red-600', 'hover:text-red-700',
                                'hover:bg-red-50');
                            deleteBtn.setAttribute('onclick',
                                `EditImageManager.restoreImage(${imageId})`);
                        }
                    }

                    // Show success message with undo option
                    const toast = SafeSwal.fire({
                        title: '✓ Image Marked for Deletion',
                        text: 'The image will be deleted when you save changes.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Undo',
                        timer: 5000,
                        timerProgressBar: true
                    });

                    toast.then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            this.restoreImage(imageId);
                        }
                    });

                    // Update deletion status header
                    this.updateDeletionStatusHeader();
                }
            });
        },

        // Restore image
        restoreImage: function(imageId) {
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

            // Find and restore the image card
            const imageCard = document.getElementById(`current-image-${imageId}`);
            if (imageCard) {
                // Remove deletion styling
                imageCard.classList.remove('border-red-400', 'bg-red-50/30');
                imageCard.classList.add('border-gray-200', 'bg-white', 'hover:shadow-md');

                // Hide deletion overlay
                const deletionOverlay = document.getElementById(`deletion-overlay-${imageId}`);
                if (deletionOverlay) {
                    deletionOverlay.classList.add('hidden');
                }

                // Hide deletion status indicator
                const deletionStatus = document.getElementById(`deletion-status-${imageId}`);
                if (deletionStatus) {
                    deletionStatus.classList.add('hidden');
                }

                // Remove grayscale effect from the image
                const img = imageCard.querySelector('img');
                if (img) {
                    img.classList.remove('grayscale', 'opacity-70');
                }

                // Enable the primary radio button
                const radioBtn = imageCard.querySelector('input[type="radio"]');
                if (radioBtn) {
                    radioBtn.disabled = false;
                    radioBtn.closest('label').classList.remove('opacity-50', 'cursor-not-allowed');
                }

                // Change restore button back to delete button
                const restoreBtn = imageCard.querySelector('button[onclick*="restoreImage"]');
                if (restoreBtn) {
                    restoreBtn.innerHTML = '<i class="fas fa-trash"></i>';
                    restoreBtn.classList.remove('text-green-600', 'hover:text-green-700', 'bg-green-50',
                        'hover:bg-green-100', 'border', 'border-green-300');
                    restoreBtn.classList.add('text-red-600', 'hover:text-red-700', 'hover:bg-red-50');
                    restoreBtn.setAttribute('onclick', `EditImageManager.markImageForDeletion(${imageId})`);
                    restoreBtn.title = 'Delete Image';
                }
            }

            // Show success message
            SafeSwal.fire({
                title: '✓ Image Restored!',
                text: 'The image has been restored and will not be deleted.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });

            // Update deletion status header
            this.updateDeletionStatusHeader();
        },

        // Update deletion status header above images
        updateDeletionStatusHeader: function() {
            const deletionCount = window.deletedImages ? window.deletedImages.length : 0;
            const statusHeader = document.getElementById('deletionStatusHeader');

            if (statusHeader) {
                if (deletionCount > 0) {
                    statusHeader.innerHTML = `
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm font-medium text-red-700">
                                ${deletionCount} image${deletionCount !== 1 ? 's' : ''} marked for deletion
                            </span>
                            <button type="button" 
                                    onclick="EditImageManager.restoreAllImages()"
                                    class="ml-auto text-xs text-blue-600 hover:text-blue-800 font-medium">
                                <i class="fas fa-undo mr-1"></i> Restore All
                            </button>
                        </div>
                    </div>
                `;
                    statusHeader.classList.remove('hidden');
                } else {
                    statusHeader.innerHTML = '';
                    statusHeader.classList.add('hidden');
                }
            }
        },

        // Restore all deleted images
        restoreAllImages: function() {
            if (!window.deletedImages || window.deletedImages.length === 0) {
                SafeSwal.fire({
                    title: 'No Images to Restore',
                    text: 'No images are currently marked for deletion.',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            SafeSwal.fire({
                title: 'Restore All Images?',
                text: `Are you sure you want to restore all ${window.deletedImages.length} images?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a copy of the array to avoid mutation during iteration
                    const imagesToRestore = [...window.deletedImages];

                    // Restore each image
                    imagesToRestore.forEach(imageId => {
                        this.restoreImage(imageId);
                    });

                    // Clear the deleted images array
                    window.deletedImages = [];

                    // Update hidden input
                    const deletedInput = document.getElementById('deletedImagesInput');
                    if (deletedInput) {
                        deletedInput.value = JSON.stringify([]);
                    }

                    SafeSwal.fire({
                        title: 'All Images Restored!',
                        text: `${imagesToRestore.length} images have been restored.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        },

        // Update primary image selection
        updatePrimaryImage: function(imageId) {
            // Uncheck all other existing primary radios
            const existingRadios = document.querySelectorAll('input[name="primary_image"]');
            existingRadios.forEach(radio => {
                if (parseInt(radio.value) !== imageId) {
                    radio.checked = false;
                }
            });

            // Uncheck all new primary radios
            const newPrimaryRadios = document.querySelectorAll('input[name="new_primary_image"]');
            newPrimaryRadios.forEach(radio => {
                radio.checked = false;
                // Update hidden is_primary field for this new image
                const rowIndex = radio.value;
                const row = document.querySelector(`[data-index="${rowIndex}"]`);
                if (row) {
                    const hiddenInput = row.querySelector(
                        `input[name="new_images[${rowIndex}][is_primary]"]`);
                    if (hiddenInput) {
                        hiddenInput.value = '0';
                    }
                }
            });

            // Update hidden is_primary fields for existing images
            const currentImageCards = document.querySelectorAll('.current-image-card');
            currentImageCards.forEach(card => {
                const cardImageId = card.id.replace('current-image-', '');
                const isPrimaryInput = card.querySelector('input[name$="[is_primary]"]');
                if (isPrimaryInput) {
                    isPrimaryInput.value = cardImageId === imageId.toString() ? '1' : '0';
                }
            });
        },

        // Handle new primary image selection
        handleNewPrimaryChange: function(newImageIndex) {
            // Uncheck all other new primary radios
            const allNewPrimaryRadios = document.querySelectorAll('input[name="new_primary_image"]');
            allNewPrimaryRadios.forEach(radio => {
                if (radio.value !== newImageIndex.toString()) {
                    radio.checked = false;
                }
            });

            // Uncheck all existing primary radios
            const existingPrimaryRadios = document.querySelectorAll('input[name="primary_image"]');
            existingPrimaryRadios.forEach(radio => {
                radio.checked = false;
                // Update hidden is_primary field for existing image
                const imageId = radio.value;
                const card = document.getElementById(`current-image-${imageId}`);
                if (card) {
                    const isPrimaryInput = card.querySelector('input[name$="[is_primary]"]');
                    if (isPrimaryInput) {
                        isPrimaryInput.value = '0';
                    }
                }
            });

            // Update hidden is_primary fields for all new images
            const newImageRows = document.querySelectorAll('.new-image-row');
            newImageRows.forEach(row => {
                const rowIndex = row.dataset.index;
                let hiddenInput = row.querySelector(
                `input[name="new_images[${rowIndex}][is_primary]"]`);

                if (!hiddenInput) {
                    // Create hidden input if it doesn't exist
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `new_images[${rowIndex}][is_primary]`;
                    row.appendChild(hiddenInput);
                }
                // Set to 1 if this is the primary, 0 otherwise
                hiddenInput.value = rowIndex === newImageIndex.toString() ? '1' : '0';
            });
        },

        // Consolidate primary image data before form submission
        preparePrimaryImageData: function() {
            // Check for existing primary image
            const existingPrimary = document.querySelector('input[name="primary_image"]:checked');
            if (existingPrimary) {
                // Ensure all new images have is_primary set to 0
                const newImageRows = document.querySelectorAll('.new-image-row');
                newImageRows.forEach(row => {
                    const rowIndex = row.dataset.index;
                    let hiddenInput = row.querySelector(
                        `input[name="new_images[${rowIndex}][is_primary]"]`);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `new_images[${rowIndex}][is_primary]`;
                        hiddenInput.value = '0';
                        row.appendChild(hiddenInput);
                    } else {
                        hiddenInput.value = '0';
                    }
                });
                return;
            }

            // Check for new primary image
            const newPrimary = document.querySelector('input[name="new_primary_image"]:checked');
            if (newPrimary) {
                const newIndex = newPrimary.value;
                // Ensure hidden input exists and is set to 1
                const row = document.querySelector(`[data-index="${newIndex}"]`);
                if (row) {
                    let hiddenInput = row.querySelector(`input[name="new_images[${newIndex}][is_primary]"]`);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `new_images[${newIndex}][is_primary]`;
                        hiddenInput.value = '1';
                        row.appendChild(hiddenInput);
                    } else {
                        hiddenInput.value = '1';
                    }
                }
            }
        },

        // Add new image row in edit modal
        addNewImageRow: function() {
            const container = document.getElementById('newImagesContainer');
            if (!container) return false;

            const currentCount = container.querySelectorAll('.new-image-row').length;
            if (currentCount >= this.maxNewImages) {
                this.showNotification(`Maximum of ${this.maxNewImages} new images allowed`, 'error');
                return false;
            }

            const rowIndex = this.currentNewImageIndex;
            this.currentNewImageIndex++;

            const newRow = document.createElement('div');
            newRow.className = 'new-image-row border border-gray-200 rounded-lg p-4 bg-gray-50';
            newRow.dataset.index = rowIndex;

            // Create hidden is_primary input from the start
            const hiddenPrimaryInput = document.createElement('input');
            hiddenPrimaryInput.type = 'hidden';
            hiddenPrimaryInput.name = `new_images[${rowIndex}][is_primary]`;
            hiddenPrimaryInput.value = '0';

            newRow.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-900">New Image ${rowIndex}</span>
                <button type="button" onclick="EditImageManager.removeNewImageRow(this.closest('.new-image-row'))" 
                        class="text-red-600 hover:text-red-700 text-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- File Upload Area -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors duration-200 bg-white relative mb-3">
                <input type="file" 
                       name="new_images[${rowIndex}][image]" 
                       accept="image/*"
                       class="file-input absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                       onchange="EditImageManager.handleNewImageUpload(this, ${rowIndex})">
                
                <div id="newUploadContent-${rowIndex}" class="upload-content text-center py-4">
                    <div class="mx-auto w-12 h-12 mb-2 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-gray-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Click to upload new image</p>
                    <p class="text-xs text-gray-500">JPG, PNG, GIF, WebP, SVG up to 5MB</p>
                </div>
                
                <!-- Preview Container -->
                <div id="newPreview-${rowIndex}" class="hidden">
                    <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                        <div class="w-12 h-12 rounded overflow-hidden bg-gray-200 flex items-center justify-center">
                            <img id="newPreviewImg-${rowIndex}" class="w-full h-full object-cover"
                                 src="" alt="New Image Preview">
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p id="newFileName-${rowIndex}" class="text-sm font-medium text-gray-900 truncate"></p>
                            <p id="newFileSize-${rowIndex}" class="text-xs text-gray-500"></p>
                        </div>
                        <button type="button" onclick="EditImageManager.clearNewImageInput(${rowIndex})"
                                class="text-gray-400 hover:text-red-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Additional Fields -->
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">
                        Alt Text <span class="text-gray-400 text-xs">(optional)</span>
                    </label>
                    <input type="text" 
                           name="new_images[${rowIndex}][alt_text]" 
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                           placeholder="Describe the image for accessibility"
                           maxlength="125"
                           oninput="EditImageManager.validateNewAltText(this)">
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">
                            Display Order
                        </label>
                        <input type="number" 
                               name="new_images[${rowIndex}][sort_order]" 
                               value="${rowIndex}"
                               min="1"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    </div>
                    
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="new_primary_image" 
                                   value="${rowIndex}"
                                   class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                                   onchange="EditImageManager.handleNewPrimaryChange(${rowIndex})">
                            <span class="ml-2 text-sm text-gray-700">Set as Primary</span>
                        </label>
                    </div>
                </div>
            </div>
        `;

            // Append the hidden input
            newRow.appendChild(hiddenPrimaryInput);
            container.appendChild(newRow);
            this.updateNewImageCount();

            return true;
        },

        // Handle new image upload in edit modal
        handleNewImageUpload: function(fileInput, index) {
            const file = fileInput.files[0];
            if (!file) return;

            if (!this.validateFile(file)) {
                fileInput.value = '';
                return;
            }

            this.showNewImagePreview(file, index);
        },

        // Validate file
        validateFile: function(file) {
            // Check file type
            if (!this.allowedTypes.includes(file.type.toLowerCase())) {
                const typesList = this.allowedTypes.map(t => t.split('/')[1].toUpperCase()).join(', ');
                this.showNotification(`Invalid file type. Allowed types: ${typesList}`, 'error');
                return false;
            }

            // Check file size
            if (file.size > this.maxFileSize) {
                this.showNotification(`File size exceeds ${this.formatFileSize(this.maxFileSize)} limit`,
                    'error');
                return false;
            }

            return true;
        },

        // Show new image preview
        showNewImagePreview: function(file, index) {
            const previewContainer = document.getElementById(`newPreview-${index}`);
            const uploadContent = document.getElementById(`newUploadContent-${index}`);
            const previewImg = document.getElementById(`newPreviewImg-${index}`);
            const fileName = document.getElementById(`newFileName-${index}`);
            const fileSize = document.getElementById(`newFileSize-${index}`);

            if (!previewContainer || !uploadContent) return;

            // Create object URL for preview
            const objectUrl = URL.createObjectURL(file);
            const img = new Image();

            img.onload = () => {
                previewImg.src = objectUrl;
                previewContainer.classList.remove('hidden');
                uploadContent.classList.add('hidden');

                fileName.textContent = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file
                    .name;
                fileSize.textContent = this.formatFileSize(file.size);

                URL.revokeObjectURL(objectUrl);
            };

            img.onerror = () => {
                this.showNotification('Failed to load image preview', 'error');
                this.clearNewImageInput(index);
            };

            img.src = objectUrl;
        },

        // Clear new image input
        clearNewImageInput: function(index) {
            const row = document.querySelector(`[data-index="${index}"]`);
            if (!row) return;

            const fileInput = row.querySelector('input[type="file"]');
            const previewContainer = document.getElementById(`newPreview-${index}`);
            const uploadContent = document.getElementById(`newUploadContent-${index}`);
            const altText = row.querySelector('input[name*="alt_text"]');
            const sortOrder = row.querySelector('input[name*="sort_order"]');
            const primaryRadio = row.querySelector('input[name="new_primary_image"]');

            if (fileInput) fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (uploadContent) uploadContent.classList.remove('hidden');
            if (altText) altText.value = '';
            if (sortOrder) sortOrder.value = index;
            if (primaryRadio) primaryRadio.checked = false;

            // Also update hidden is_primary input
            const hiddenInput = row.querySelector(`input[name="new_images[${index}][is_primary]"]`);
            if (hiddenInput) {
                hiddenInput.value = '0';
            }
        },

        // Remove new image row
        removeNewImageRow: function(row) {
            SafeSwal.fire({
                title: 'Remove New Image?',
                text: 'Are you sure you want to remove this new image?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (row) {
                        row.remove();
                        this.updateNewImageCount();
                        this.showNotification('New image removed', 'success');
                    }
                }
            });
        },

        // Validate new alt text
        validateNewAltText: function(input) {
            const value = input.value.trim();
            const maxLength = 125;

            if (value.length > maxLength) {
                input.classList.add('border-red-500');
                this.showNotification('Alt text should be under 125 characters', 'warning');
            } else {
                input.classList.remove('border-red-500');
            }
        },

        // Update new image count
        updateNewImageCount: function() {
            const container = document.getElementById('newImagesContainer');
            const count = container ? container.querySelectorAll('.new-image-row').length : 0;

            const counterElement = document.getElementById('newImagesCount');
            if (counterElement) {
                counterElement.textContent = count;
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

        // Auto-select primary if none exists
        autoSelectPrimaryIfNeeded: function() {
            const primaryImageSelected = document.querySelector('input[name="primary_image"]:checked');
            const newPrimarySelected = document.querySelector('input[name="new_primary_image"]:checked');

            if (!primaryImageSelected && !newPrimarySelected) {
                // Find first current image not marked for deletion
                const firstValidCurrent = document.querySelector(
                    '.current-image-card:not(.border-red-400) input[name="primary_image"]');
                if (firstValidCurrent) {
                    firstValidCurrent.checked = true;
                    this.updatePrimaryImage(firstValidCurrent.value);
                    return;
                }

                // Or check new images
                const newImageRows = document.querySelectorAll('.new-image-row');
                if (newImageRows.length > 0) {
                    const firstNewImage = newImageRows[0].querySelector('input[name="new_primary_image"]');
                    if (firstNewImage) {
                        firstNewImage.checked = true;
                        this.handleNewPrimaryChange(firstNewImage.value);
                    }
                }
            }
        },

        // Validate all images before form submission
        validateEditImages: function() {
            // Auto-select primary if needed
            this.autoSelectPrimaryIfNeeded();

            // Prepare primary image data
            this.preparePrimaryImageData();

            let isValid = true;
            const errors = [];

            // Count total images that will exist after save
            const currentImagesNotDeleted = document.querySelectorAll(
                '.current-image-card:not(.border-red-400)');
            const newImageRows = document.querySelectorAll('.new-image-row');
            const newImagesWithFiles = Array.from(newImageRows).filter(row => {
                const fileInput = row.querySelector('input[type="file"]');
                return fileInput && fileInput.files && fileInput.files[0];
            });

            const totalImagesAfterSave = currentImagesNotDeleted.length + newImagesWithFiles.length;

            // Only require a primary if we have images
            if (totalImagesAfterSave > 0) {
                // Check if we have a primary image selected
                const existingPrimary = document.querySelector('input[name="primary_image"]:checked');
                const newPrimary = document.querySelector('input[name="new_primary_image"]:checked');

                if (!existingPrimary && !newPrimary) {
                    errors.push('At least one image must be set as primary');
                    isValid = false;
                }
            }

            // Validate new images
            newImageRows.forEach(row => {
                const fileInput = row.querySelector('input[type="file"]');
                const altText = row.querySelector('input[name*="alt_text"]');

                if (fileInput.files[0]) {
                    // Validate file size
                    if (fileInput.files[0].size > this.maxFileSize) {
                        errors.push(`New image: File too large (max 5MB)`);
                        isValid = false;
                    }

                    // Check alt text length
                    if (altText && altText.value.length > 125) {
                        errors.push(`New image: Alt text too long (max 125 characters)`);
                        isValid = false;
                    }
                }
            });

            // Show errors if any
            if (errors.length > 0) {
                const errorHtml = errors.map(error => `<li class="text-sm">${error}</li>`).join('');

                SafeSwal.fire({
                    icon: 'warning',
                    title: 'Image Validation Issues',
                    html: `<div class="text-left"><ul class="list-disc pl-4">${errorHtml}</ul></div>`,
                    confirmButtonText: 'Continue Anyway',
                    showCancelButton: true,
                    cancelButtonText: 'Fix Issues'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        isValid = false;
                    }
                });
            }

            return isValid;
        }
    };

    // ========== PRODUCT MODAL MANAGEMENT ==========
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

                document.getElementById('newImagesContainer').innerHTML = '';
                document.getElementById('deletedImagesInput').value = '';
                window.deletedImages = [];

                const submitBtn = modal.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
                submitBtn.disabled = true;

                const response = await fetch(`/admin/products/${productId}/edit`);
                const data = await response.json();

                if (data.success) {
                    const product = data.data;

                    document.getElementById('editProductId').value = product.id;
                    document.getElementById('editName').value = product.name;
                    document.getElementById('editCategoryId').value = product.category_id;
                    document.getElementById('editBrand').value = product.brand || '';
                    document.getElementById('editMaterial').value = product.material || '';
                    document.getElementById('editDescription').value = product.description;
                    document.getElementById('editStatus').value = product.status || 'active';
                    document.getElementById('editIsFeatured').checked = Boolean(product.is_featured);
                    document.getElementById('editIsNew').checked = Boolean(product.is_new);

                    document.getElementById('editProductForm').action = `/admin/products/${product.id}`;

                    const variantsContainer = document.getElementById('editVariantsContainer');
                    variantsContainer.innerHTML = '';

                    // Initialize EditImageManager FIRST - this will bind events and populate images
                    EditImageManager.initEditModal(product);

                    if (product.variants && product.variants.length > 0) {
                        product.variants.forEach((variant, index) => {
                            addEditVariantRow(variant, index);
                        });
                    } else {
                        addEditVariantRow(null, 0);
                    }

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

    // ========== DELETE MODAL ==========
    const DeleteModal = {
        open: function(type, id, name) {
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

                    form.submit();
                }
            });
        },

        close: function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    };

    // ========== VARIANT MANAGEMENT ==========
    let variantIndex = 1;
    let imageIndex = 1;

    function addVariantRow() {
        const container = document.getElementById('variantsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'variant-row border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4';
        newRow.setAttribute('data-index', variantIndex);
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
            placeholder="0.00"
            oninput="DiscountManager.updateDiscountPreview(${variantIndex})">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-900 mb-1">Stock  <span class="ml-1 text-red-500">*</span></label>
        <input type="number" name="variants[${variantIndex}][stock]" min="0" required
            class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
            placeholder="0">
    </div>
</div>

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
            <button type="button" onclick="DiscountManager.calculateVariantDiscount(${variantIndex})" 
                    class="text-xs px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center"
                    title="Calculate discount">
                <i class="fas fa-calculator mr-1.5"></i> Calculate
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
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
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer variant-discount-type"
                        onchange="DiscountManager.handleVariantDiscountTypeChange(this, ${variantIndex})">
                    <option value="">No Discount</option>
                    <option value="percentage">Percentage Discount (%)</option>
                    <option value="fixed">Fixed Amount Discount ($)</option>
                </select>
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <div class="space-y-2 variant-discount-value-field" style="display: none;">
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
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium variant-discount-prefix">
                    <i class="fas fa-dollar-sign text-xs"></i>
                </div>
                <input type="number" step="0.01" name="variants[${variantIndex}][discount_value]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 variant-discount-value"
                       placeholder="0.00"
                       min="0"
                       max="100"
                       oninput="DiscountManager.updateDiscountPreview(${variantIndex})">
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>

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
                           class="sr-only peer variant-has-discount"
                           onchange="DiscountManager.toggleDiscountStatus(this, ${variantIndex})">
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

    <div class="mt-6 pt-4 border-t border-gray-200/50">
        <div class="flex items-center mb-4">
            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
            <h5 class="text-xs font-semibold text-gray-900">Discount Period (Optional)</h5>
            <span class="ml-2 text-xs text-gray-500">Leave empty for indefinite discount</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                           class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           onchange="DiscountManager.updateDiscountPeriod(${variantIndex})">
                </div>
            </div>
            
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
                           class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           onchange="DiscountManager.updateDiscountPeriod(${variantIndex})">
                </div>
            </div>
        </div>
    </div>

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
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                        <i class="fas fa-money-bill mr-1"></i> Original Price
                    </div>
                    <div class="text-lg font-bold text-gray-900 original-price-display" id="originalPrice-${variantIndex}">
                        $0.00
                    </div>
                </div>
                
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

        const discountStart = variantData && variantData.discount_start ?
            new Date(variantData.discount_start).toISOString().slice(0, 16) :
            '';
        const discountEnd = variantData && variantData.discount_end ?
            new Date(variantData.discount_end).toISOString().slice(0, 16) :
            '';

        const newRow = document.createElement('div');
        newRow.className = 'variant-row border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4';
        newRow.setAttribute('data-index', currentIndex);
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
            placeholder="0.00"
            oninput="DiscountManager.updateDiscountPreviewEdit(${currentIndex})">
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
            Variant #<span class="font-bold">${currentIndex + 1}</span>
        </span>
        <button type="button" onclick="DiscountManager.calculateVariantDiscount(${currentIndex})" 
                class="text-xs px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center"
                title="Calculate discount">
            <i class="fas fa-calculator mr-1.5"></i> Calculate
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
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
            <select name="variants[${currentIndex}][discount_type]"
                    class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer edit-variant-discount-type"
                    onchange="DiscountManager.handleEditVariantDiscountTypeChange(this, ${currentIndex})">
                <option value="">No Discount</option>
                <option value="percentage" ${variantData && variantData.discount_type === 'percentage' ? 'selected' : ''}>Percentage Discount (%)</option>
                <option value="fixed" ${variantData && variantData.discount_type === 'fixed' ? 'selected' : ''}>Fixed Amount Discount ($)</option>
            </select>
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-blue-600 transition-colors">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>

    <div class="space-y-2 edit-variant-discount-value-field" style="${variantData && variantData.discount_type ? 'display: block;' : 'display: none;'}">
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
                ${variantData && variantData.discount_type === 'percentage' ? '%' : '$'}
            </div>
            <input type="number" step="0.01" name="variants[${currentIndex}][discount_value]" value="${variantData ? variantData.discount_value : ''}"
                   class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 edit-variant-discount-value"
                   placeholder="0.00"
                   min="0"
                   max="100"
                   oninput="DiscountManager.updateDiscountPreviewEdit(${currentIndex})">
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                <i class="fas fa-edit"></i>
            </div>
        </div>
    </div>

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
                <input type="checkbox" name="variants[${currentIndex}][has_discount]" value="1" 
                       ${variantData && variantData.has_discount ? 'checked' : ''}
                       class="sr-only peer edit-variant-has-discount"
                       onchange="DiscountManager.toggleDiscountStatusEdit(this, ${currentIndex})">
                <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                <div class="ml-3 flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-900 peer-checked:text-green-700 transition-colors">Active</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" id="discountStatusBadge-${currentIndex}">
                        ${variantData && variantData.has_discount ? 'ON' : 'OFF'}
                    </span>
                </div>
            </label>
        </div>
    </div>
</div>

<div class="mt-6 pt-4 border-t border-gray-200/50">
    <div class="flex items-center mb-4">
        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
        <h5 class="text-xs font-semibold text-gray-900">Discount Period (Optional)</h5>
        <span class="ml-2 text-xs text-gray-500">Leave empty for indefinite discount</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-900 flex items-center">
                <i class="far fa-calendar-plus mr-2 text-gray-500"></i>
                Start Date & Time
            </label>
            <div class="relative group">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <i class="far fa-clock"></i>
                </div>
                <input type="datetime-local" name="variants[${currentIndex}][discount_start]" value="${discountStart}"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                       onchange="DiscountManager.updateDiscountPeriodEdit(${currentIndex})">
            </div>
        </div>
        
        <div class="space-y-2">
            <label class="block text-xs font-medium text-gray-900 flex items-center">
                <i class="far fa-calendar-minus mr-2 text-gray-500"></i>
                End Date & Time
            </label>
            <div class="relative group">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <i class="far fa-clock"></i>
                </div>
                <input type="datetime-local" name="variants[${currentIndex}][discount_end]" value="${discountEnd}"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                       onchange="DiscountManager.updateDiscountPeriodEdit(${currentIndex})">
            </div>
        </div>
    </div>
</div>

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
            <div id="discountActiveStatus-${currentIndex}" 
                 class="text-xs px-3 py-1 rounded-full ${variantData && variantData.discount_type ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700'} font-medium">
                <i class="fas ${variantData && variantData.discount_type ? 'fa-check-circle' : 'fa-info-circle'} mr-1"></i> ${variantData && variantData.discount_type ? 'Active' : 'Configure'}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                    <i class="fas fa-money-bill mr-1"></i> Original Price
                </div>
                <div class="text-lg font-bold text-gray-900 original-price-display" id="originalPrice-${currentIndex}">
                    $${variantData && variantData.price ? parseFloat(variantData.price).toFixed(2) : '0.00'}
                </div>
            </div>
            
            <div class="text-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                    <i class="fas fa-tags mr-1"></i> Discounted Price
                </div>
                <div class="text-xl font-bold text-green-700 discounted-price-display" id="discountedPrice-${currentIndex}">
                    ${variantData && variantData.price ? 
                        variantData.discount_type === 'percentage' && variantData.discount_value ?
                        `$${(variantData.price - (variantData.price * variantData.discount_value / 100)).toFixed(2)}` :
                        variantData.discount_type === 'fixed' && variantData.discount_value ?
                        `$${(variantData.price - variantData.discount_value).toFixed(2)}` :
                        `$${parseFloat(variantData.price || 0).toFixed(2)}`
                        : '$0.00'}
                </div>
                <div class="text-xs text-green-600 mt-1 discount-type-display" id="discountType-${currentIndex}">
                    ${variantData && variantData.discount_type === 'percentage' && variantData.discount_value ? 
                        `${variantData.discount_value}% off` :
                        variantData && variantData.discount_type === 'fixed' && variantData.discount_value ?
                        `$${parseFloat(variantData.discount_value).toFixed(2)} off` :
                        'No discount applied'}
                </div>
            </div>
            
            <div class="text-center p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100">
                <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                    <i class="fas fa-piggy-bank mr-1"></i> You Save
                </div>
                <div class="text-lg font-bold text-red-700 savings-display" id="savings-${currentIndex}">
                    ${variantData && variantData.price && variantData.discount_type && variantData.discount_value ? 
                        variantData.discount_type === 'percentage' ?
                        `$${(variantData.price * variantData.discount_value / 100).toFixed(2)}` :
                        `$${parseFloat(variantData.discount_value).toFixed(2)}`
                        : '$0.00'}
                </div>
                <div class="text-xs text-red-600 mt-1" id="savingsPercentage-${currentIndex}">
                    ${variantData && variantData.price && variantData.discount_value ? 
                        variantData.discount_type === 'percentage' ?
                        `${variantData.discount_value}% off` :
                        `${((variantData.discount_value / variantData.price) * 100).toFixed(1)}% off`
                        : '0% off'}
                </div>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-history mr-2"></i>
                    <span>Discount Period: </span>
                    <span class="font-medium ml-1 text-gray-900 period-display" id="periodDisplay-${currentIndex}">
                        ${discountStart && discountEnd ? 
                            `${new Date(discountStart).toLocaleDateString()} - ${new Date(discountEnd).toLocaleDateString()}` :
                            discountStart ? `From ${new Date(discountStart).toLocaleDateString()}` :
                            discountEnd ? `Until ${new Date(discountEnd).toLocaleDateString()}` :
                            'Not set'}
                    </span>
                </div>
                <div class="text-xs">
                    <span class="text-gray-600">Updated: </span>
                    <span class="font-medium text-gray-900" id="lastUpdated-${currentIndex}">
                        Just now
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 p-3 bg-yellow-50 border border-yellow-100 rounded-lg hidden" id="validationMessage-${currentIndex}">
    <div class="flex items-start">
        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
        <div class="flex-1">
            <p class="text-xs font-medium text-yellow-800" id="validationText-${currentIndex}"></p>
        </div>
    </div>
</div>
</div>

<div class="mt-3 pt-3 border-t border-gray-200">
    <label class="inline-flex items-center">
        <input type="checkbox" name="variants[${currentIndex}][is_active]" value="1" ${variantData && variantData.is_active ? 'checked' : ''} class="rounded border-gray-300">
        <span class="ml-2 text-sm text-gray-700">Active</span>
    </label>
</div>
`;

        container.appendChild(newRow);
    }

    // ========== IMAGE MANAGEMENT ==========
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
                if (!window.deletedImages) window.deletedImages = [];

                if (!window.deletedImages.includes(imageId)) {
                    window.deletedImages.push(imageId);
                }

                const deletedInput = document.getElementById('deletedImagesInput');
                if (deletedInput) {
                    deletedInput.value = JSON.stringify(window.deletedImages);
                }

                const imageRow = document.getElementById(`image-${imageId}`);
                if (imageRow) {
                    imageRow.classList.add('image-deleted');

                    const radioBtn = imageRow.querySelector('input[type="radio"]');
                    if (radioBtn) {
                        radioBtn.disabled = true;
                        radioBtn.checked = false;
                    }

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

    function restoreImage(imageId) {
        if (window.deletedImages) {
            const index = window.deletedImages.indexOf(imageId);
            if (index > -1) {
                window.deletedImages.splice(index, 1);
            }

            const deletedInput = document.getElementById('deletedImagesInput');
            if (deletedInput) {
                deletedInput.value = JSON.stringify(window.deletedImages);
            }
        }

        const imageRow = document.getElementById(`image-${imageId}`);
        if (imageRow) {
            imageRow.classList.remove('image-deleted');

            const radioBtn = imageRow.querySelector('input[type="radio"]');
            if (radioBtn) {
                radioBtn.disabled = false;
            }

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

    // ========== FORM HANDLERS ==========
    function handleEditFormSubmit(e) {
        e.preventDefault();

        const form = document.getElementById('editProductForm');
        const deletedInput = document.getElementById('deletedImagesInput');
        if (deletedInput && window.deletedImages && window.deletedImages.length > 0) {
            deletedInput.value = JSON.stringify(window.deletedImages);
        } else if (deletedInput) {
            deletedInput.value = '[]';
        }

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

        form.submit();
    }

    // ========== INITIALIZATION ==========
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Product Filter
        ProductFilter.init();

        // Initialize Discount Manager
        DiscountManager.init();

        // Initialize Image Manager
        ImageManager.init();

        // Set default values
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

        // Add product form submission
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

                this.submit();
            });
        }

        // Edit product form
        const editForm = document.getElementById('editProductForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate images
                if (!EditImageManager.validateEditImages()) {
                    return;
                }

                // Ensure deleted images are in the hidden input
                const deletedInput = document.getElementById('deletedImagesInput');
                if (deletedInput && window.deletedImages && window.deletedImages.length > 0) {
                    deletedInput.value = JSON.stringify(window.deletedImages);
                } else if (deletedInput) {
                    deletedInput.value = '[]';
                }

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

                this.submit();
            });
        }

        // Modal close on background click
        document.addEventListener('click', function(e) {
            if (e.target.id === 'addProductModal') ProductModal.closeAdd();
            if (e.target.id === 'editProductModal') ProductModal.closeEdit();
        });

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

    // ========== GLOBAL FUNCTIONS ==========
    window.applyFilters = () => ProductFilter.applyFilters();
    window.clearFilters = () => ProductFilter.clearAllFilters();
    window.ProductModal = ProductModal;
    window.DeleteModal = DeleteModal;
    window.addVariantRow = addVariantRow;
    window.addEditVariantRow = addEditVariantRow;
    window.removeRow = removeRow;
    window.removeVariant = removeVariant;
    window.markImageForDeletion = markImageForDeletion;
    window.restoreImage = restoreImage;
    window.SafeSwal = SafeSwal;
    window.ImageManager = ImageManager;
    window.EditImageManager = EditImageManager;
    window.addNewImageRow = () => EditImageManager.addNewImageRow();
    window.markImageForDeletion = (id) => EditImageManager.markImageForDeletion(id);
    window.restoreImage = (id) => EditImageManager.restoreImage(id);
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

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out;
    }

    .animate-slideOut {
        animation: slideOut 0.3s ease-out;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    /* Discount specific styles */
    .discount-preview {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid #3b82f6;
    }

    .variant-discount-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
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
