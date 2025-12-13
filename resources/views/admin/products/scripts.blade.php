<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
                    document.getElementById('editSku').value = product.sku;
                    document.getElementById('editCategoryId').value = product.category_id;
                    document.getElementById('editBrand').value = product.brand;
                    document.getElementById('editShortDescription').value = product.short_description;
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
                <label class="block text-sm font-medium text-gray-900 mb-1">Size *</label>
                <select name="variants[${variantIndex}][size]" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Size</option>
                    ${['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'].map(size => 
                        `<option value="${size}">${size}</option>`
                    ).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Color *</label>
                <input type="text" name="variants[${variantIndex}][color]" required 
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                    placeholder="e.g., Red, Black">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Price (₹) *</label>
                <input type="number" step="0.01" name="variants[${variantIndex}][price]" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                    placeholder="0.00">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Stock *</label>
                <input type="number" name="variants[${variantIndex}][stock]" min="0" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm"
                    placeholder="0">
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

                    const currentDeleted = deletedVariantsInput.value ? JSON.parse(deletedVariantsInput.value) : [];
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
                <label class="block text-sm font-medium text-gray-900 mb-1">Size *</label>
                <select name="variants[${currentIndex}][size]" required class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Size</option>
                    ${['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE'].map(size => 
                        `<option value="${size}" ${variantData && variantData.size === size ? 'selected' : ''}>${size}</option>`
                    ).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Color *</label>
                <input type="text" name="variants[${currentIndex}][color]" value="${variantData ? variantData.color : ''}" required 
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
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Price (₹) *</label>
                <input type="number" step="0.01" name="variants[${currentIndex}][price]" value="${variantData ? variantData.price : ''}" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Sale Price (₹)</label>
                <input type="number" step="0.01" name="variants[${currentIndex}][sale_price]" value="${variantData ? variantData.sale_price : ''}"
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Cost Price (₹)</label>
                <input type="number" step="0.01" name="variants[${currentIndex}][cost_price]" value="${variantData ? variantData.cost_price : ''}"
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1">Stock *</label>
                <input type="number" name="variants[${currentIndex}][stock]" value="${variantData ? variantData.stock : ''}" min="0" required
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
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
                <label class="block text-sm font-medium text-gray-900 mb-1">Dimensions</label>
                <input type="text" name="variants[${currentIndex}][dimensions]" value="${variantData ? variantData.dimensions : ''}"
                    class="w-full border border-gray-200 bg-white text-gray-900 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex items-center">
                <label class="inline-flex items-center mt-6">
                    <input type="checkbox" name="variants[${currentIndex}][is_active]" value="1" ${variantData && variantData.is_active ? 'checked' : ''} class="rounded border-gray-300">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>
    `;
        container.appendChild(newRow);
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
                <label class="block text-sm font-medium text-gray-900 mb-1">Image File *</label>
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
    input:focus, textarea:focus, select:focus {
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
    #addModalContent, #editModalContent {
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
</style>