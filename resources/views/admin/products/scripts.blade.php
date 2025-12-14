<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

                // Show loading
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

                    // Add event listeners for discount type changes
                    document.addEventListener('DOMContentLoaded', function() {
                        // Product-level discount type change
                        const discountTypeSelects = document.querySelectorAll('.discount-type-select');
                        discountTypeSelects.forEach(select => {
                            select.addEventListener('change', function() {
                                handleDiscountTypeChange(this);
                            });

                            // Initialize on page load
                            if (select.value) {
                                handleDiscountTypeChange(select);
                            }
                        });

                        // Variant-level discount type change
                        document.addEventListener('change', function(e) {
                            if (e.target.classList.contains('variant-discount-type')) {
                                handleVariantDiscountTypeChange(e.target);
                            }
                        });
                    });

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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to load product data'
                    });
                    ProductModal.closeEdit();
                }

            } catch (error) {
                console.error('Error loading product:', error);
                Swal.fire({
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
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${name}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
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

                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the product',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
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
        Swal.fire({
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
        Swal.fire({
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
                Swal.fire('Removed!', 'Variant has been removed.', 'success');
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
        Swal.fire({
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

                Swal.fire('Marked for deletion!', 'Image will be deleted when you save changes.', 'success');
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

        Swal.fire('Restored!', 'Image has been restored.', 'success');
    }

    // Add new image row in EDIT modal
    function addNewImageRow() {
        const container = document.getElementById('newImagesContainer');
        const currentIndex = container.children.length;

        const newRow = document.createElement('div');
        newRow.className = 'border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4';
        newRow.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <span class="text-sm font-medium text-gray-900">New Image ${currentIndex + 1}</span>
            <button type="button" onclick="removeNewImageRow(this)" 
                    class="text-red-600 hover:text-red-700 text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Image File  <span class="ml-1 text-red-500">*</span></label>
                <input type="file" 
                       name="new_images[${currentIndex}][image]" 
                       accept="image/*"
                       class="w-full border border-gray-200 rounded px-3 py-2"
                       required>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Alt Text</label>
                <input type="text" 
                       name="new_images[${currentIndex}][alt_text]" 
                       class="w-full border border-gray-200 rounded px-3 py-2"
                       placeholder="Describe the image for accessibility">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Sort Order</label>
                <input type="number" 
                       name="new_images[${currentIndex}][sort_order]" 
                       value="${currentIndex}"
                       class="w-full border border-gray-200 rounded px-3 py-2"
                       min="0">
            </div>
        </div>
    `;

        container.appendChild(newRow);
    }

    // Remove new image row with confirmation
    function removeNewImageRow(button) {
        Swal.fire({
            title: 'Remove Image?',
            text: 'Are you sure you want to remove this new image?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('.border').remove();
                Swal.fire('Removed!', 'New image has been removed.', 'success');
            }
        });
    }

    // Add image row for ADD product modal
    function addImageRow() {
        const container = document.getElementById('additionalImagesContainer');
        const newRow = document.createElement('div');
        newRow.className = 'border border-gray-200 rounded-lg p-3 mb-3';
        newRow.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-900">Additional Image ${imageIndex}</span>
            <button type="button" onclick="removeNewImageRow(this)" class="text-red-600 hover:text-red-700 text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="border-2 border-dashed border-gray-200 rounded-lg p-3">
            <input type="file" name="images[${imageIndex}][image]" accept="image/*" class="w-full text-gray-900">
            <input type="hidden" name="images[${imageIndex}][is_primary]" value="0">
            <div class="mt-2">
                <input type="text" name="images[${imageIndex}][alt_text]" 
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                    placeholder="Alt text (optional)">
            </div>
            <div class="mt-2">
                <input type="number" name="images[${imageIndex}][sort_order]" value="${imageIndex}"
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                    placeholder="Sort order">
            </div>
        </div>
    `;
        container.appendChild(newRow);
        imageIndex++;
    }

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

        // Show loading
        Swal.fire({
            title: 'Updating Product...',
            text: 'Please wait while we update your product',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit the form
        form.submit();
    }

    // Form submission handlers for SweetAlert2
    document.addEventListener('DOMContentLoaded', function() {
        // Add product form
        const addForm = document.querySelector('form[action="{{ route('admin.products.store') }}"]');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Creating Product...',
                    text: 'Please wait while we create your product',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
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

                // Show loading
                Swal.fire({
                    title: 'Updating Product...',
                    text: 'Please wait while we update your product',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
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
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
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
        @endif
    });

    // Search functionality to update table
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchLoading = document.getElementById('searchLoading');
        let searchTimeout;

        // Debounced search on input
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                const query = e.target.value.trim();

                // Show loading
                searchLoading.classList.remove('hidden');

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
                searchLoading.classList.remove('hidden');
                updateTableWithSearch(query);
            }

            // Escape to clear
            if (e.key === 'Escape') {
                searchInput.value = '';
                clearSearch();
            }
        });
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
                document.getElementById('searchLoading').classList.add('hidden');
                hideTableLoading();
            })
            .catch(error => {
                console.error('Search error:', error);

                // Fallback: reload page normally
                const query = document.getElementById('searchInput').value.trim();
                if (query) {
                    window.location.href = `/admin/products?search=${encodeURIComponent(query)}`;
                } else {
                    window.location.href = '/admin/products';
                }

                document.getElementById('searchLoading').classList.add('hidden');
                hideTableLoading();
            });
    }

    // Clear search
    function clearSearch() {
        // Clear input
        document.getElementById('searchInput').value = '';

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
</script>

<script>
    // Discount type change handler for Add modal
    document.addEventListener('DOMContentLoaded', function() {
        // Handle discount type changes in Add modal
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('variant-discount-type')) {
                const row = e.target.closest('.variant-discount-section');
                const valueField = row.querySelector('.variant-discount-value-field');
                const prefix = row.querySelector('.variant-discount-prefix');
                const preview = row.querySelector('.discount-preview');

                if (e.target.value) {
                    valueField.style.display = 'block';
                    prefix.textContent = e.target.value === 'percentage' ? '%' : '$';
                    preview.style.display = 'block';
                    updateDiscountPreview(row);
                } else {
                    valueField.style.display = 'none';
                    preview.style.display = 'none';
                }
            }

            // Handle price input changes for preview
            if (e.target.name && e.target.name.includes('[price]')) {
                const row = e.target.closest('.variant-row');
                const discountSection = row.querySelector('.variant-discount-section');
                if (discountSection) {
                    updateDiscountPreview(row);
                }
            }

            // Handle discount value changes
            if (e.target.name && e.target.name.includes('[discount_value]')) {
                const row = e.target.closest('.variant-row');
                updateDiscountPreview(row);
            }
        });

        // Real-time preview for date inputs
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('[discount_start]') ||
                e.target.name && e.target.name.includes('[discount_end]')) {
                const row = e.target.closest('.variant-row');
                updateDiscountPreview(row);
            }
        });
    });

    function updateDiscountPreview(row) {
        const priceInput = row.querySelector('input[name$="[price]"]');
        const discountType = row.querySelector('.variant-discount-type');
        const discountValue = row.querySelector('input[name$="[discount_value]"]');
        const discountStart = row.querySelector('input[name$="[discount_start]"]');
        const discountEnd = row.querySelector('input[name$="[discount_end]"]');
        const previewValue = row.querySelector('.discount-preview-value');
        const previewType = row.querySelector('.discount-preview-type');
        const previewPeriod = row.querySelector('.discount-period');

        if (!discountType.value || !priceInput.value || !discountValue.value) {
            return;
        }

        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountValue.value) || 0;

        // Calculate discounted price
        let discountedPrice = price;
        let savings = 0;

        if (discountType.value === 'percentage') {
            savings = price * (discount / 100);
            discountedPrice = price - savings;
        } else if (discountType.value === 'fixed') {
            savings = discount;
            discountedPrice = price - savings;
        }

        // Update preview
        previewValue.textContent = `$${discountedPrice.toFixed(2)}`;
        previewType.textContent = discountType.value === 'percentage' ?
            `${discount}% off` : `$${discount.toFixed(2)} off`;

        // Update period
        let periodText = 'Always active';
        if (discountStart.value && discountEnd.value) {
            const start = new Date(discountStart.value).toLocaleDateString();
            const end = new Date(discountEnd.value).toLocaleDateString();
            periodText = `${start} - ${end}`;
        } else if (discountStart.value) {
            const start = new Date(discountStart.value).toLocaleDateString();
            periodText = `From ${start}`;
        } else if (discountEnd.value) {
            const end = new Date(discountEnd.value).toLocaleDateString();
            periodText = `Until ${end}`;
        }
        previewPeriod.textContent = periodText;
    }

    // Function to calculate discount for edit modal
    function calculateDiscountForVariant(index) {
        const row = document.querySelector(`[data-variant-index="${index}"]`);
        const priceInput = row.querySelector(`input[name="variants[${index}][price]"]`);
        const discountType = row.querySelector(`select[name="variants[${index}][discount_type]"]`);
        const discountValue = row.querySelector(`input[name="variants[${index}][discount_value]"]`);
        const originalPriceEl = row.querySelector('.original-price');
        const discountedPriceEl = row.querySelector('.discounted-price');
        const savingsEl = row.querySelector('.savings-amount');
        const statusEl = row.querySelector('.discount-status');

        if (!priceInput.value || !discountType.value || !discountValue.value) {
            statusEl.textContent = 'Please fill in price, discount type, and value';
            statusEl.style.color = '#ef4444';
            return;
        }

        const price = parseFloat(priceInput.value);
        const discount = parseFloat(discountValue.value);
        let discountedPrice = price;
        let savings = 0;

        if (discountType.value === 'percentage') {
            savings = price * (discount / 100);
            discountedPrice = price - savings;
        } else if (discountType.value === 'fixed') {
            savings = discount;
            discountedPrice = price - discount;
        }

        // Update display
        originalPriceEl.textContent = `$${price.toFixed(2)}`;
        discountedPriceEl.textContent = `$${discountedPrice.toFixed(2)}`;
        savingsEl.textContent = `$${savings.toFixed(2)}`;

        // Update status
        const discountText = discountType.value === 'percentage' ?
            `${discount}% discount` : `$${discount.toFixed(2)} off`;
        statusEl.textContent = `${discountText} applied. New price: $${discountedPrice.toFixed(2)}`;
        statusEl.style.color = '#059669';
    }

    // Enhanced addVariantRow function
   function addVariantRow() {
    const container = document.getElementById('variantsContainer');
    const index = container.children.length;
    
    const template = `
    <div class="variant-row border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition-colors" data-variant-index="${index}">
        <div class="flex justify-between items-center mb-4">
            <h5 class="font-medium text-gray-900 flex items-center">
                <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                Variant #${index + 1}
            </h5>
            <button type="button" onclick="removeVariantRow(this)" 
                    class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                <i class="fas fa-trash mr-1"></i> Remove
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Size  <span class="ml-1 text-red-500">*</span></label>
                <select name="variants[${index}][size]" required
                        class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Size</option>
                    ${['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'].map(size => 
                        `<option value="${size}">${size}</option>`
                    ).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Color  <span class="ml-1 text-red-500">*</span></label>
                <input type="text" name="variants[${index}][color]" required
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., Red, Black">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Color Code</label>
                <div class="flex items-center space-x-2">
                    <input type="color" name="variants[${index}][color_code]"
                           class="w-10 h-10 border border-gray-300 rounded-lg cursor-pointer">
                    <input type="text" name="variants[${index}][color_code_hex]"
                           class="flex-1 border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                           placeholder="#000000" maxlength="7">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">SKU</label>
                <input type="text" name="variants[${index}][sku]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., TEE-M-BLUE">
                <div class="text-xs text-gray-500 mt-1">Leave empty for auto-generation</div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Price ($)  <span class="ml-1 text-red-500">*</span></label>
                <input type="number" step="0.01" name="variants[${index}][price]" required
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 price-input"
                       placeholder="0.00"
                       oninput="updateDiscountPreviewForAdd(${index})">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Sale Price ($)</label>
                <input type="number" step="0.01" name="variants[${index}][sale_price]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                       placeholder="Optional">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Stock  <span class="ml-1 text-red-500">*</span></label>
                <input type="number" name="variants[${index}][stock]" min="0" required
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                       placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Stock Alert</label>
                <input type="number" name="variants[${index}][stock_alert]" min="0" value="10"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <!-- ENHANCED DISCOUNT SECTION FOR ADD MODAL -->
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
                        Variant #<span class="font-bold">${index + 1}</span>
                    </span>
                    <button type="button" onclick="calculateVariantDiscountAdd(${index})" 
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
                        <select name="variants[${index}][discount_type]"
                                class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer variant-discount-type-select"
                                onchange="handleVariantDiscountTypeChangeAdd(this, ${index})">
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
                <div class="space-y-2 variant-discount-value-field-add" style="display: none;">
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
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium variant-discount-prefix-add">
                            <i class="fas fa-dollar-sign text-xs"></i>
                        </div>
                        <input type="number" step="0.01" name="variants[${index}][discount_value]"
                               class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 discount-value-input-add"
                               placeholder="0.00"
                               min="0"
                               max="100"
                               oninput="updateDiscountPreviewForAdd(${index})">
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
                            <input type="checkbox" name="variants[${index}][has_discount]" value="1"
                                   class="sr-only peer variant-has-discount-add"
                                   onchange="toggleDiscountActivationAdd(this, ${index})">
                            <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            <div class="ml-3 flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900 peer-checked:text-green-700 transition-colors">Active</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" id="discountStatusBadgeAdd-${index}">
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
                            <input type="datetime-local" name="variants[${index}][discount_start]"
                                   class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 discount-start-add"
                                   onchange="updateDiscountPeriodAdd(${index})">
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
                            <input type="datetime-local" name="variants[${index}][discount_end]"
                                   class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 discount-end-add"
                                   onchange="updateDiscountPeriodAdd(${index})">
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
                        <div id="discountActiveStatusAdd-${index}" 
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
                            <div class="text-lg font-bold text-gray-900 original-price-display" id="originalPriceAdd-${index}">
                                $0.00
                            </div>
                        </div>
                        
                        <!-- Discounted Price -->
                        <div class="text-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                            <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                <i class="fas fa-tags mr-1"></i> Discounted Price
                            </div>
                            <div class="text-xl font-bold text-green-700 discounted-price-display" id="discountedPriceAdd-${index}">
                                $0.00
                            </div>
                            <div class="text-xs text-green-600 mt-1 discount-type-display" id="discountTypeAdd-${index}">
                                No discount applied
                            </div>
                        </div>
                        
                        <!-- You Save -->
                        <div class="text-center p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100">
                            <div class="text-xs text-gray-600 mb-1 flex items-center justify-center">
                                <i class="fas fa-piggy-bank mr-1"></i> You Save
                            </div>
                            <div class="text-lg font-bold text-red-700 savings-display" id="savingsAdd-${index}">
                                $0.00
                            </div>
                            <div class="text-xs text-red-600 mt-1" id="savingsPercentageAdd-${index}">
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
                                <span class="font-medium ml-1 text-gray-900 period-display" id="periodDisplayAdd-${index}">
                                    Not set
                                </span>
                            </div>
                            <div class="text-xs">
                                <span class="text-gray-600">Updated: </span>
                                <span class="font-medium text-gray-900" id="lastUpdatedAdd-${index}">
                                    Just now
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Message -->
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-100 rounded-lg hidden" id="validationMessageAdd-${index}">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-yellow-800" id="validationTextAdd-${index}"></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Cost Price ($)</label>
                <input type="number" step="0.01" name="variants[${index}][cost_price]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Weight (kg)</label>
                <input type="number" step="0.01" name="variants[${index}][weight]"
                       class="w-full border border-gray-300 bg-white text-gray-900 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
            <label class="inline-flex items-center">
                <input type="checkbox" name="variants[${index}][is_active]" value="1" checked
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active Variant</span>
            </label>
            <button type="button" onclick="updateSkuIfNeeded(this, ${index})"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-sync-alt mr-1"></i> Auto-generate SKU
            </button>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', template);
}

    // Helper function to update SKU
    function updateSkuIfNeeded(element, index) {
        const row = element.closest('.variant-row');
        const skuInput = row.querySelector('input[name="variants[' + index + '][sku]"]');
        const sizeSelect = row.querySelector('select[name="variants[' + index + '][size]"]');
        const colorInput = row.querySelector('input[name="variants[' + index + '][color]"]');

        if (!skuInput.value && sizeSelect.value && colorInput.value) {
            const productName = document.querySelector('input[name="name"]').value || 'PROD';
            const sku =
                `${productName.substring(0, 3).toUpperCase()}-${sizeSelect.value}-${colorInput.value.substring(0, 3).toUpperCase()}`;
            skuInput.value = sku;
        }
    }

    // Function to remove variant row
    function removeVariantRow(button) {
        if (document.querySelectorAll('.variant-row').length > 1) {
            button.closest('.variant-row').remove();
            // Re-index remaining variants
            const rows = document.querySelectorAll('.variant-row');
            rows.forEach((row, index) => {
                const header = row.querySelector('h5');
                if (header) {
                    header.innerHTML =
                        `<i class="fas fa-layer-group mr-2 text-blue-600"></i>Variant #${index + 1}`;
                }
            });
        } else {
            alert('At least one variant is required');
        }
    }

    // Handle discount type change in Edit modal
function handleVariantDiscountTypeChangeEdit(selectElement, variantIndex) {
    const row = selectElement.closest('.variant-discount-section');
    const valueField = row.querySelector('.variant-discount-value-field');
    const prefix = row.querySelector('.variant-discount-prefix');
    const preview = row.querySelector('.discount-preview');

    if (selectElement.value) {
        valueField.style.display = 'block';
        prefix.textContent = selectElement.value === 'percentage' ? '%' : '$';
        preview.style.display = 'block';
        updateDiscountPreviewEdit(variantIndex);
    } else {
        valueField.style.display = 'none';
        preview.style.display = 'none';
        updateDiscountPreviewEdit(variantIndex);
    }
}

// Toggle discount status in Edit modal
function toggleDiscountStatusEdit(checkbox, variantIndex) {
    updateDiscountPreviewEdit(variantIndex);
}

// Update discount preview in Edit modal
function updateDiscountPreviewEdit(variantIndex) {
    const row = document.querySelector(`[name="variants[${variantIndex}][price]"]`).closest('.variant-row');
    
    const priceInput = row.querySelector(`[name="variants[${variantIndex}][price]"]`);
    const discountType = row.querySelector(`[name="variants[${variantIndex}][discount_type]"]`);
    const discountValue = row.querySelector(`[name="variants[${variantIndex}][discount_value]"]`);
    const discountStart = row.querySelector(`[name="variants[${variantIndex}][discount_start]"]`);
    const discountEnd = row.querySelector(`[name="variants[${variantIndex}][discount_end]"]`);
    const previewValue = document.getElementById(`discountPreviewValueEdit-${variantIndex}`);
    const previewType = document.getElementById(`discountPreviewTypeEdit-${variantIndex}`);
    const previewPeriod = document.getElementById(`discountPeriodEdit-${variantIndex}`);

    if (!discountType || !priceInput || !previewValue || !previewType || !previewPeriod) {
        return;
    }

    if (discountType.value && priceInput.value && discountValue.value) {
        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountValue.value) || 0;

        // Calculate discounted price
        let discountedPrice = price;
        let savings = 0;

        if (discountType.value === 'percentage') {
            savings = price * (discount / 100);
            discountedPrice = price - savings;
        } else if (discountType.value === 'fixed') {
            savings = discount;
            discountedPrice = price - savings;
        }

        // Update preview
        previewValue.textContent = `$${discountedPrice.toFixed(2)}`;
        previewType.textContent = discountType.value === 'percentage' ?
            `${discount}% off` : `$${discount.toFixed(2)} off`;
    } else {
        const price = parseFloat(priceInput.value) || 0;
        previewValue.textContent = `$${price.toFixed(2)}`;
        previewType.textContent = '';
    }

    // Update period
    let periodText = 'Not set';
    if (discountStart && discountEnd && discountStart.value && discountEnd.value) {
        const start = new Date(discountStart.value).toLocaleDateString();
        const end = new Date(discountEnd.value).toLocaleDateString();
        periodText = `${start} - ${end}`;
    } else if (discountStart && discountStart.value) {
        const start = new Date(discountStart.value).toLocaleDateString();
        periodText = `From ${start}`;
    } else if (discountEnd && discountEnd.value) {
        const end = new Date(discountEnd.value).toLocaleDateString();
        periodText = `Until ${end}`;
    }
    previewPeriod.textContent = periodText;
}

// Update discount period display in Edit modal
function updateDiscountPeriodEdit(variantIndex) {
    updateDiscountPreviewEdit(variantIndex);
}

// Add event listeners for Edit modal
document.addEventListener('DOMContentLoaded', function() {
    // Handle price input changes for Edit modal preview
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('[price]') && e.target.name.includes('variants[')) {
            const variantIndex = e.target.name.match(/variants\[(\d+)\]/)[1];
            if (variantIndex) {
                updateDiscountPreviewEdit(variantIndex);
            }
        }
        
        // Handle discount value changes for Edit modal preview
        if (e.target.name && e.target.name.includes('[discount_value]')) {
            const variantIndex = e.target.name.match(/variants\[(\d+)\]/)[1];
            if (variantIndex) {
                updateDiscountPreviewEdit(variantIndex);
            }
        }
    });
    
    // Handle discount start/end changes for Edit modal preview
    document.addEventListener('change', function(e) {
        if (e.target.name && e.target.name.includes('[discount_start]') || 
            e.target.name && e.target.name.includes('[discount_end]')) {
            const variantIndex = e.target.name.match(/variants\[(\d+)\]/)[1];
            if (variantIndex) {
                updateDiscountPreviewEdit(variantIndex);
            }
        }
    });
});
</script>
<style>
    /* Card Components */
    .card {
        @apply bg-white rounded-2xl border border-gray-200/50 p-6 shadow-sm hover:shadow-md transition-shadow;
    }

    .card-header {
        @apply mb-6 pb-4 border-b border-gray-200/50;
    }

    /* Variant Cards */
    .variant-card {
        @apply bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200/50 p-5 hover:border-gray-300 transition-all;
    }

    /* Image Upload Area */
    .image-upload-area {
        @apply relative rounded-2xl overflow-hidden;
    }

    /* Feature Toggles */
    .feature-toggle {
        @apply relative flex items-center space-x-3 cursor-pointer group;
    }

    .toggle-bg {
        @apply w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-gray-900 transition-colors relative;
    }

    .toggle-bg::after {
        content: '';
        @apply absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6;
    }

    .feature-badge {
        @apply absolute -top-2 -right-2 bg-gradient-to-r from-gray-900 to-gray-800 text-white text-xs px-2 py-0.5 rounded-full transform scale-0 group-hover:scale-100 transition-transform;
    }

    /* Form Input Focus States */
    input:focus,
    textarea:focus,
    select:focus {
        @apply outline-none;
    }

    /* Scrollbar Styling */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        @apply bg-gray-100 rounded-full;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        @apply bg-gray-400 rounded-full hover:bg-gray-600 transition-colors;
    }

    /* Modal Animation */
    #addModalContent,
    #editModalContent {
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

    /* Current Images Grid */
    .image-container {
        @apply relative rounded-xl overflow-hidden transition-all hover:shadow-md;
    }

    .image-container.image-deleted {
        @apply opacity-50 bg-gradient-to-br from-gray-100 to-gray-200;
    }

    .image-container.image-deleted img {
        @apply grayscale;
    }

    /* Price Input Icons */
    .price-input-container {
        @apply relative;
    }

    .price-icon {
        @apply absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500;
    }

    /* Status Badges */
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
    }

    .status-badge.active {
        @apply bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200;
    }

    .status-badge.inactive {
        @apply bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200;
    }

    .status-badge.draft {
        @apply bg-gradient-to-r from-yellow-50 to-amber-50 text-yellow-700 border border-yellow-200;
    }

    /* Required Indicator */
    .required-indicator {
        @apply text-red-500 font-bold ml-1;
    }

    /* Loading States */
    .loading-spinner {
        @apply animate-spin rounded-full border-2 border-gray-300 border-t-gray-900;
    }

    /* Hover Effects */
    .hover-lift {
        @apply transition-transform hover:-translate-y-1;
    }

    /* Gradient Borders */
    .gradient-border {
        @apply border border-transparent bg-clip-border bg-gradient-to-r from-gray-900 to-gray-800;
    }

    /* Enhanced tooltip styles */
    .tooltip {
        position: relative;
    }

    .tooltip:hover::before {
        content: attr(data-tip);
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

    .tooltip:hover::after {
        content: '';
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: rgba(0, 0, 0, 0.8);
        margin-bottom: -5px;
    }

    /* Smooth transitions */
    .variant-discount-section {
        transition: all 0.3s ease;
    }

    .variant-discount-section:hover {
        border-color: #c7d2fe;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }

    /* Discount preview animations */
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

    /* Enhanced input focus styles */
    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Better toggle switch */
    input[type="checkbox"].sr-only+div {
        transition: all 0.3s ease;
    }

    input[type="checkbox"]:checked.sr-only+div {
        background-color: #2563eb;
    }

    /* Improved grid gaps */
    .grid-cols-auto-fit {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
</style>
