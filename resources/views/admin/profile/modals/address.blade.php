<!-- Address Management Modal -->
<div id="addressModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="modal-content bg-white rounded-2xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="addressModalTitle">Add New Address</h3>
                    <p class="text-gray-600 mt-1">Fill in your address details</p>
                </div>
                <button onclick="hideAddressModal()"
                    class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <form id="addressForm" class="p-6">
            @csrf
            <input type="hidden" id="address_id" name="id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Address Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative">
                            <input type="radio" name="type" value="shipping" class="sr-only peer" checked>
                            <div
                                class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-Ocean peer-checked:bg-Ocean/5 cursor-pointer transition-all duration-300">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-truck text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Shipping Address</h4>
                                        <p class="text-sm text-gray-600">For product deliveries</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="type" value="billing" class="sr-only peer">
                            <div
                                class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-Ocean peer-checked:bg-Ocean/5 cursor-pointer transition-all duration-300">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-credit-card text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Billing Address</h4>
                                        <p class="text-sm text-gray-600">For invoices and payments</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Address Name -->
                <div class="md:col-span-2">
                    <label for="address_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Address Nickname (Optional)
                    </label>
                    <input type="text" id="address_name" name="address_name"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300"
                        placeholder="e.g., Home, Office">
                </div>

                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- Address Line 1 -->
                <div class="md:col-span-2">
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-2">
                        Address Line 1 <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="address_line1" name="address_line1" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- Address Line 2 -->
                <div class="md:col-span-2">
                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-2">
                        Address Line 2 (Optional)
                    </label>
                    <input type="text" id="address_line2" name="address_line2"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        City <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="city" name="city" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                        State <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="state" name="state" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- Zip Code -->
                <div>
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                        ZIP Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="zip_code" name="zip_code" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                        Country
                    </label>
                    <select id="country" name="country"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                        <option value="United States" selected>United States</option>
                        <option value="Canada">Canada</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Australia">Australia</option>
                        <option value="Germany">Germany</option>
                        <option value="France">France</option>
                        <option value="Japan">Japan</option>
                    </select>
                </div>

                <!-- Set as Default -->
                <div class="md:col-span-2 flex items-center">
                    <input type="checkbox" id="is_default" name="is_default"
                        class="w-4 h-4 text-Ocean rounded focus:ring-Ocean focus:ring-2">
                    <label for="is_default" class="ml-2 text-sm font-medium text-gray-700">
                        Set as default address
                    </label>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="hideAddressModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
                    Cancel
                </button>
                <button type="submit" id="addressSubmitBtn"
                    class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    Save Address
                </button>
            </div>
        </form>
    </div>
</div>
