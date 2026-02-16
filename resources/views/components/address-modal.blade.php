<!-- Cambodia Address Modal - Reusable Component -->
<div id="addressModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95"
            id="addressModalContent">
            <div class="p-6">
                <!-- Modal Header with Cambodian Flag -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200">
                            <span class="text-xl">🇰🇭</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">ការកំណត់អាសយដ្ឋាន</h3>
                            <p class="text-gray-500 text-sm">Address Details for Cambodia</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAddressModal()" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Cambodia Address Form -->
                <form id="addressForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="address_id" name="id">
                    <input type="hidden" name="country" value="Cambodia">

                    <!-- Address Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ឈ្មោះអាសយដ្ឋាន</span>
                            <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <input type="text" name="address_name" id="address_name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="ផ្ទះ, ការិយាល័យ, ហាង...">
                    </div>

                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ឈ្មោះពេញ</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="បញ្ចូលឈ្មោះពេញ">
                    </div>

                    <!-- Cambodian Phone Number -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">លេខទូរស័ព្ទ</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-700 font-medium">
                                +855
                            </div>
                            <input type="text" name="phone" id="phone" required
                                class="w-full px-4 py-3 pl-16 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="92 345 678"
                                pattern="[0-9]{8,9}"
                                title="លេខទូរស័ព្ទកម្ពុជា (៨-៩ ខ្ទង់)"
                                maxlength="9">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">លេខទូរស័ព្ទកម្ពុជា (៨-៩ ខ្ទង់)</p>
                    </div>

                    <!-- Address Line 1 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">អាសយដ្ឋានបន្ទាត់ទី១</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address_line1" id="address_line1" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="ផ្លូវ, ភូមិ, ឃុំ...">
                    </div>

                    <!-- Address Line 2 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">អាសយដ្ឋានបន្ទាត់ទី២</span>
                            <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <input type="text" name="address_line2" id="address_line2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="អគារ, ជាន់, បន្ទប់...">
                    </div>

                    <!-- Province Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ខេត្ត/រាជធានី</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="province" id="province" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none bg-white cursor-pointer">
                            <option value="">ជ្រើសរើសខេត្ត/រាជធានី</option>
                            <option value="Phnom Penh">ភ្នំពេញ (Phnom Penh)</option>
                            <option value="Kandal">កណ្ដាល (Kandal)</option>
                            <option value="Kampong Speu">កំពង់ស្ពឺ (Kampong Speu)</option>
                            <option value="Takeo">តាកែវ (Takeo)</option>
                            <option value="Prey Veng">ព្រៃវែង (Prey Veng)</option>
                            <option value="Svay Rieng">ស្វាយរៀង (Svay Rieng)</option>
                            <option value="Kampong Cham">កំពង់ចាម (Kampong Cham)</option>
                            <option value="Kampong Thom">កំពង់ធំ (Kampong Thom)</option>
                            <option value="Siem Reap">សៀមរាប (Siem Reap)</option>
                            <option value="Battambang">បាត់ដំបង (Battambang)</option>
                            <option value="Banteay Meanchey">បន្ទាយមានជ័យ (Banteay Meanchey)</option>
                            <option value="Pursat">ពោធិ៍សាត់ (Pursat)</option>
                            <option value="Kampong Chhnang">កំពង់ឆ្នាំង (Kampong Chhnang)</option>
                            <option value="Kampot">កំពត (Kampot)</option>
                            <option value="Kep">កែប (Kep)</option>
                            <option value="Preah Sihanouk">ព្រះសីហនុ (Preah Sihanouk)</option>
                            <option value="Koh Kong">កោះកុង (Koh Kong)</option>
                            <option value="Mondulkiri">មណ្ឌលគិរី (Mondulkiri)</option>
                            <option value="Ratanakiri">រតនគិរី (Ratanakiri)</option>
                            <option value="Stung Treng">ស្ទឹងត្រែង (Stung Treng)</option>
                            <option value="Kratie">ក្រចេះ (Kratie)</option>
                            <option value="Preah Vihear">ព្រះវិហារ (Preah Vihear)</option>
                            <option value="Oddar Meanchey">ឧត្ដរមានជ័យ (Oddar Meanchey)</option>
                            <option value="Pailin">ប៉ៃលិន (Pailin)</option>
                            <option value="Tbong Khmum">ត្បូងឃ្មុំ (Tbong Khmum)</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>

                    <!-- City/District -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ក្រុង/ស្រុក</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="បញ្ចូលក្រុង/ស្រុក">
                    </div>

                    <!-- Commune -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ឃុំ/សង្កាត់</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="commune" id="commune" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="បញ្ចូលឃុំ/សង្កាត់">
                    </div>

                    <!-- Village -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ភូមិ</span>
                            <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <input type="text" name="village" id="village"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="បញ្ចូលភូមិ">
                    </div>

                    <!-- Address Type Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">ប្រភេទអាសយដ្ឋាន</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition group">
                                <input type="radio" name="type" value="shipping" checked
                                    class="sr-only peer">
                                <div class="flex flex-col items-center space-y-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition">
                                        <i class="fas fa-truck text-blue-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">ដឹកជញ្ជូន</span>
                                    <span class="text-xs text-gray-500">Shipping</span>
                                </div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500"></div>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition group">
                                <input type="radio" name="type" value="billing"
                                    class="sr-only peer">
                                <div class="flex flex-col items-center space-y-2">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition">
                                        <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">វិក័យប័ត្រ</span>
                                    <span class="text-xs text-gray-500">Billing</span>
                                </div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Default Address Checkbox -->
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <input type="checkbox" name="is_default" id="is_default" 
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_default" class="ml-3 text-gray-700 cursor-pointer">
                            <span class="font-medium">កំណត់ជាអាសយដ្ឋានលំនាំដើម</span>
                            <p class="text-sm text-gray-500 mt-1">អាសយដ្ឋាននេះនឹងត្រូវបានប្រើសម្រាប់ការដឹកជញ្ជូនដោយស្វ័យប្រវត្តិ</p>
                        </label>
                    </div>

                    <!-- Additional Notes -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <span class="text-gray-900">កំណត់ចំណាំបន្ថែម</span>
                            <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <textarea name="notes" id="notes" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                            placeholder="កំណត់ចំណាំបន្ថែមសម្រាប់ការដឹកជញ្ជូន..."></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                        <button type="button" onclick="closeAddressModal()" 
                            class="px-5 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition hover:shadow-sm flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            បោះបង់
                        </button>
                        <button type="submit" id="addressSubmitBtn"
                            class="px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span id="submitBtnText">រក្សាទុកអាសយដ្ឋាន</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for modal */
    #addressModalContent {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f7fafc;
    }
    
    #addressModalContent::-webkit-scrollbar {
        width: 6px;
    }
    
    #addressModalContent::-webkit-scrollbar-track {
        background: #f7fafc;
        border-radius: 3px;
    }
    
    #addressModalContent::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }
    
    #addressModalContent::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    /* Custom radio button styling */
    input[type="radio"]:checked + div {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    /* Smooth transitions */
    .transition {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Focus styles */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
</style>

<script>
    // Cambodia Address Modal Functions
    window.openAddressModal = function(addressId = null) {
        const modal = document.getElementById('addressModal');
        const modalContent = document.getElementById('addressModalContent');
        const title = document.getElementById('modalTitle');
        const submitBtnText = document.getElementById('submitBtnText');
        
        if (addressId) {
            title.textContent = 'កែសម្រួលអាសយដ្ឋាន';
            submitBtnText.textContent = 'បន្ទាន់សម័យអាសយដ្ឋាន';
            // Load existing address data via AJAX
            loadAddressData(addressId);
        } else {
            title.textContent = 'ការកំណត់អាសយដ្ឋាន';
            submitBtnText.textContent = 'រក្សាទុកអាសយដ្ឋាន';
            resetAddressForm();
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    };

    window.closeAddressModal = function() {
        const modal = document.getElementById('addressModal');
        const modalContent = document.getElementById('addressModalContent');
        
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        modal.classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };

    function resetAddressForm() {
        const form = document.getElementById('addressForm');
        form.reset();
        document.getElementById('address_id').value = '';
        document.getElementById('country').value = 'Cambodia';
        document.getElementById('addressSubmitBtn').disabled = false;
    }

    function loadAddressData(addressId) {
        // AJAX call to load address data
        fetch(`/api/addresses/${addressId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const address = data.address;
                    document.getElementById('address_id').value = address.id;
                    document.getElementById('address_name').value = address.address_name || '';
                    document.getElementById('full_name').value = address.full_name || '';
                    document.getElementById('phone').value = address.phone || '';
                    document.getElementById('address_line1').value = address.address_line1 || '';
                    document.getElementById('address_line2').value = address.address_line2 || '';
                    document.getElementById('province').value = address.province || '';
                    document.getElementById('city').value = address.city || '';
                    document.getElementById('commune').value = address.commune || '';
                    document.getElementById('village').value = address.village || '';
                    document.getElementById('notes').value = address.notes || '';
                    
                    // Set address type
                    if (address.type === 'billing') {
                        document.querySelector('input[name="type"][value="billing"]').checked = true;
                    } else {
                        document.querySelector('input[name="type"][value="shipping"]').checked = true;
                    }
                    
                    // Set default address
                    document.getElementById('is_default').checked = address.is_default || false;
                }
            })
            .catch(error => {
                console.error('Error loading address:', error);
                showAlert('Error', 'មិនអាចទាញយកទិន្នន័យអាសយដ្ឋានបាន', 'error');
            });
    }

    // Phone number formatting for Cambodia
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = this.value.replace(/\D/g, '');
                
                // Limit to 9 digits (Cambodian phone numbers)
                if (value.length > 9) {
                    value = value.slice(0, 9);
                }
                
                // Format with spaces: 92 345 678
                if (value.length > 3) {
                    value = value.replace(/(\d{2})(\d{3})(\d{0,4})/, '$1 $2 $3').trim();
                } else if (value.length > 2) {
                    value = value.replace(/(\d{2})(\d{1,})/, '$1 $2').trim();
                }
                
                this.value = value;
            });
        }

        // Form validation
        const addressForm = document.getElementById('addressForm');
        if (addressForm) {
            addressForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('addressSubmitBtn');
                const originalText = submitBtn.innerHTML;
                const formData = new FormData(this);
                
                // Remove spaces from phone number before submission
                const phone = formData.get('phone');
                formData.set('phone', phone.replace(/\s/g, ''));
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <i class="fas fa-spinner fa-spin"></i>
                    កំពុងរក្សាទុក...
                `;

                const addressId = formData.get('id');
                const url = addressId ? `/profile/address/${addressId}/update` : '/profile/address/add';
                const method = addressId ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('ជោគជ័យ', data.message || 'អាសយដ្ឋានត្រូវបានរក្សាទុកដោយជោគជ័យ', 'success', function() {
                            closeAddressModal();
                            if (typeof window.onAddressSaved === 'function') {
                                window.onAddressSaved(data);
                            }
                            // Reload page if on profile page
                            if (window.location.pathname.includes('/profile')) {
                                window.location.reload();
                            }
                        });
                    } else {
                        showAlert('បញ្ហា', data.message || 'មានបញ្ហាក្នុងការរក្សាទុកអាសយដ្ឋាន', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('បញ្ហា', 'មានបញ្ហាក្នុងការភ្ជាប់ទៅកាន់ម៉ាស៊ីនបម្រើ', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    });

    // Helper function for showing alerts
    function showAlert(title, text, icon, callback = null) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'យល់ព្រម',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg bg-blue-600 text-white'
                }
            }).then((result) => {
                if (callback && typeof callback === 'function') {
                    callback(result);
                }
            });
        } else {
            alert(`${title}: ${text}`);
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    }
</script>