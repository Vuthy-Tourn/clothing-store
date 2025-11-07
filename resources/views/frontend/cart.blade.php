@extends('layouts.front')
@section('content')
<div class="min-h-screen bg-gray-50 py-12 mt-10">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
            <p class="text-gray-600">Review your items and proceed to checkout</p>
        </div>

        {{-- Check if cart is empty --}}
        @if ($items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @php $grandTotal = 0; @endphp
                    @foreach ($items as $item)
                        @php
                            $product = $item->product;
                            $price = $item->unit_price ?? 0;
                            $total = $price * $item->quantity;
                            $grandTotal += $total;
                        @endphp
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300" id="cart-item-{{ $item->id }}">
                            <div class="flex items-start gap-6">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                         class="w-24 h-24 object-cover rounded-xl shadow-sm">
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                            <p class="text-sm text-gray-500 mb-2">#{{ $product->id }}</p>
                                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                                <span class="bg-gray-100 px-3 py-1 rounded-full">Size: {{ $item->size }}</span>
                                                <span class="font-medium text-green-600">In Stock</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-gray-900">${{ number_format($price, 2) }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm font-medium text-gray-700">Quantity:</span>
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $item->id }}, -1)"
                                                        class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-l-lg transition-colors quantity-btn"
                                                        data-item-id="{{ $item->id }}"
                                                        data-change="-1">
                                                    −
                                                </button>
                                                <span id="quantity-{{ $item->id }}" class="w-12 text-center font-medium">{{ $item->quantity }}</span>
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $item->id }}, 1)"
                                                        class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-r-lg transition-colors quantity-btn"
                                                        data-item-id="{{ $item->id }}"
                                                        data-change="1">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <button type="button" 
                                                onclick="showRemoveConfirmation({{ $item->id }}, '{{ addslashes($product->name) }}')"
                                                class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center gap-2 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Item Total -->
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                <span class="text-sm text-gray-600">Item total</span>
                                <span id="item-total-{{ $item->id }}" class="text-xl font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span id="subtotal">${{ number_format($grandTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span>Calculated at checkout</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span id="grand-total">${{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('checkout') }}" 
                           class="w-full bg-gray-900 text-white py-4 px-6 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105 shadow-lg flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Proceed to Checkout
                        </a>

                        <!-- Continue Shopping -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('products.all') }}" 
                               class="text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Continue Shopping
                            </a>
                        </div>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-center space-x-6 text-gray-400">
                                <div class="text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <p class="text-xs">Secure Checkout</p>
                                </div>
                                <div class="text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="text-xs">Free Shipping</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                    <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('products.all') }}" 
                       class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Browse Products
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 max-w-md mx-4 transform transition-all duration-300 scale-95 opacity-0">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2" id="modalTitle">Remove Item</h3>
            <p class="text-gray-600 mb-6" id="modalMessage">Are you sure you want to remove this item from your cart?</p>
            <div class="flex gap-3 justify-center">
                <button type="button" 
                        onclick="hideConfirmationModal()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" 
                        onclick="confirmRemove()"
                        class="px-6 py-3 bg-red-600 text-white rounded-xl font-medium hover:bg-red-700 transition-colors"
                        id="confirmButton">
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .sticky {
        position: sticky;
    }
    
    @media (max-width: 1024px) {
        .sticky {
            position: relative;
        }
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
    // Quantity Update System
    async function updateQuantity(itemId, change) {
        const quantityElement = document.getElementById(`quantity-${itemId}`);
        const currentQuantity = parseInt(quantityElement.textContent);
        const newQuantity = currentQuantity + change;

        if (newQuantity < 1) {
            showRemoveConfirmation(itemId, 'Product');
            return;
        }

        // Disable buttons during request
        const buttons = document.querySelectorAll(`[data-item-id="${itemId}"]`);
        buttons.forEach(btn => btn.disabled = true);

        try {
            const response = await fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    quantity: newQuantity
                })
            });

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON response');
            }

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Server error');
            }

            if (data.success) {
                // Update UI
                quantityElement.textContent = newQuantity;
                updateItemTotal(itemId, data.item_total);
                updateCartTotals(data.grand_total);
                
                if (typeof showSuccessToast !== 'undefined') {
                    showSuccessToast('Quantity updated successfully');
                }
            } else {
                throw new Error(data.message || 'Failed to update quantity');
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            
            // Show appropriate error message
            let errorMessage = 'Failed to update quantity';
            if (error.message.includes('Stock not available')) {
                errorMessage = 'Not enough stock available';
            } else if (error.message.includes('non-JSON')) {
                errorMessage = 'Server error - please try again';
            }
            
            if (typeof showErrorToast !== 'undefined') {
                showErrorToast(errorMessage);
            }
            
            // Revert quantity display on error
            quantityElement.textContent = currentQuantity;
        } finally {
            // Re-enable buttons
            buttons.forEach(btn => btn.disabled = false);
        }
    }

    function updateItemTotal(itemId, total) {
        const itemTotalElement = document.getElementById(`item-total-${itemId}`);
        if (itemTotalElement) {
            itemTotalElement.textContent = `$${parseFloat(total).toFixed(2)}`;
        }
    }

    function updateCartTotals(grandTotal) {
        const subtotalElement = document.getElementById('subtotal');
        const grandTotalElement = document.getElementById('grand-total');
        
        if (subtotalElement && grandTotalElement) {
            subtotalElement.textContent = `$${parseFloat(grandTotal).toFixed(2)}`;
            grandTotalElement.textContent = `$${parseFloat(grandTotal).toFixed(2)}`;
        }
    }

    // Confirmation Modal System
    let currentItemIdToRemove = null;

    function showRemoveConfirmation(itemId, productName) {
        currentItemIdToRemove = itemId;
        
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        
        modalTitle.textContent = 'Remove Item';
        modalMessage.textContent = `Are you sure you want to remove "${productName}" from your cart?`;
        
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.querySelector('.bg-white').classList.remove('scale-95', 'opacity-0');
            modal.querySelector('.bg-white').classList.add('scale-100', 'opacity-100');
        });
    }

    function hideConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('.bg-white');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            currentItemIdToRemove = null;
        }, 300);
    }

    async function confirmRemove() {
        if (!currentItemIdToRemove) return;

        try {
            const response = await fetch(`/cart/remove/${currentItemIdToRemove}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // If it's not JSON, assume it worked and reload
                location.reload();
                return;
            }

            const data = await response.json();

            if (data.success) {
                // Remove item from UI
                const itemElement = document.getElementById(`cart-item-${currentItemIdToRemove}`);
                if (itemElement) {
                    itemElement.style.opacity = '0';
                    itemElement.style.transform = 'translateX(100px)';
                    
                    setTimeout(() => {
                        itemElement.remove();
                        if (data.grand_total !== undefined) {
                            updateCartTotals(data.grand_total);
                        }
                        
                        // Check if cart is empty
                        if (document.querySelectorAll('[id^="cart-item-"]').length === 0) {
                            location.reload(); // Reload to show empty state
                        }
                    }, 300);
                }

                if (typeof showSuccessToast !== 'undefined') {
                    showSuccessToast('Item removed from cart');
                }
            } else {
                throw new Error(data.message || 'Failed to remove item');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            
            // If we get a non-JSON response, just reload the page
            if (error.message.includes('non-JSON') || error.message.includes('Unexpected token')) {
                location.reload();
                return;
            }
            
            if (typeof showErrorToast !== 'undefined') {
                showErrorToast('Failed to remove item');
            }
        } finally {
            hideConfirmationModal();
        }
    }

    // Close modal when clicking outside
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideConfirmationModal();
        }
    });

    // Add smooth animations for quantity changes
    document.addEventListener('DOMContentLoaded', function() {
        const quantityButtons = document.querySelectorAll('.quantity-btn');
        
        quantityButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.classList.add('scale-105');
                setTimeout(() => {
                    this.parentElement.classList.remove('scale-105');
                }, 150);
            });
        });
    });
</script>
@endsection