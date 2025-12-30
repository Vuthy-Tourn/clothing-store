@extends('layouts.front')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12 px-4 mt-10">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header Section (unchanged) -->
                <div class="relative bg-gray-900 h-48 md:h-56">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                            <div class="flex items-end space-x-4">
                                <div class="relative -mb-12 md:-mb-16">
                                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                                        @if ($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-800 rounded-full flex items-center justify-center">
                                                <span class="text-3xl font-bold text-white">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-28 md:ml-36">
                                    <h1 class="text-2xl md:text-3xl font-bold">{{ $user->name }}</h1>
                                    <p class="text-gray-200">{{ $user->email }}</p>
                                    @if ($user->customer_id)
                                        <p class="text-gray-300 text-sm mt-1">
                                            <i class="fas fa-id-card mr-1"></i> Customer ID: {{ $user->customer_id }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:mb-4 flex flex-col space-y-2">
                                <a href="{{ route('profile.edit') }}"
                                    class="inline-flex items-center gap-2 bg-white bg-opacity-10 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-opacity-20 transition-all duration-200 border border-white border-opacity-30">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </a>
                                @if ($user->loyalty_points > 0)
                                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 bg-opacity-20 px-4 py-2 rounded-xl">
                                        <p class="text-yellow-300 text-sm font-medium">
                                            <i class="fas fa-star mr-1"></i> {{ $user->loyalty_points }} Loyalty Points
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Section (unchanged) -->
                <div class="bg-white p-6 md:p-10 pt-20 md:pt-24">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div class="p-6 rounded-2xl border border-gray-200 shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-circle mr-2"></i> Personal Information
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Full Name</p>
                                    <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email Address</p>
                                    <p class="text-gray-900 font-medium flex items-center">
                                        {{ $user->email }}
                                        @if ($user->email_verified_at)
                                            <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                <i class="fas fa-check-circle"></i> Verified
                                            </span>
                                        @else
                                            <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                                <i class="fas fa-exclamation-circle"></i> Unverified
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Phone Number</p>
                                    <p class="text-gray-900 font-medium">{{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Account Type</p>
                                    <p class="text-gray-900 font-medium capitalize">{{ $user->account_type }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="p-6 rounded-2xl border border-gray-200 shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Additional Details
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Date of Birth</p>
                                    <p class="text-gray-900 font-medium">
                                        {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : 'Not provided' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Gender</p>
                                    <p class="text-gray-900 font-medium capitalize">
                                        {{ $user->gender ?? 'Not specified' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Newsletter Subscription</p>
                                    <p class="text-gray-900 font-medium">
                                        @if ($user->newsletter_opt_in)
                                            <span class="text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i> Subscribed
                                            </span>
                                        @else
                                            <span class="text-gray-500">
                                                <i class="fas fa-times-circle mr-1"></i> Not Subscribed
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Account Status</p>
                                    <p class="text-gray-900 font-medium">
                                        @if ($user->is_active)
                                            <span class="text-green-600 font-medium">
                                                <i class="fas fa-circle mr-1"></i> Active
                                            </span>
                                        @else
                                            <span class="text-red-600 font-medium">
                                                <i class="fas fa-circle mr-1"></i> Inactive
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="mt-8">
                        <div class="p-6 rounded-2xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i> Addresses
                                </h2>
                                <button onclick="openAddressModal()"
                                    class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                                    <i class="fas fa-plus mr-1"></i> Add Address
                                </button>
                            </div>

                            @if ($addresses->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($addresses as $address)
                                        <div class="border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition {{ $address->is_default ? 'border-2 border-blue-500 bg-blue-50' : '' }}">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        {{ $address->address_name ?? 'Address' }}</h3>
                                                    @if ($address->is_default)
                                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                            <i class="fas fa-check-circle mr-1"></i> Default
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button onclick="editAddress({{ $address->id }})"
                                                        class="text-gray-600 hover:text-gray-900">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if (!$address->is_default)
                                                        <button onclick="deleteAddress({{ $address->id }})"
                                                            class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="text-gray-600 text-sm mb-1">
                                                <i class="fas fa-user mr-2"></i>{{ $address->full_name }}
                                            </p>
                                            <p class="text-gray-600 text-sm mb-1">
                                                <i class="fas fa-phone mr-2"></i>{{ $address->phone }}
                                            </p>
                                            <p class="text-gray-600 text-sm mb-1">
                                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $address->address_line1 }}
                                                @if ($address->address_line2)
                                                    , {{ $address->address_line2 }}
                                                @endif
                                            </p>
                                            <p class="text-gray-600 text-sm">
                                                <i class="fas fa-city mr-2"></i>{{ $address->city }},
                                                {{ $address->state }} {{ $address->zip_code }}
                                            </p>
                                            <p class="text-gray-600 text-sm mt-2">
                                                <i class="fas fa-globe mr-2"></i>{{ $address->country }}
                                            </p>
                                            <div class="mt-3 flex space-x-2">
                                                @if (!$address->is_default)
                                                    <button onclick="setDefaultAddress({{ $address->id }})"
                                                        class="text-blue-600 hover:text-blue-900 text-sm">
                                                        <i class="fas fa-star mr-1"></i> Set as Default
                                                    </button>
                                                @endif
                                                <span class="text-gray-500 text-sm capitalize">
                                                    <i class="fas fa-tag mr-1"></i>{{ $address->type }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-map-marker-alt text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500">No addresses added yet.</p>
                                    <button onclick="openAddressModal()"
                                        class="mt-4 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                                        <i class="fas fa-plus mr-1"></i> Add Your First Address
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    <div id="addressModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-0 transition-opacity duration-300"></div>
        <div class="modal-container flex items-center justify-center min-h-screen p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
                <!-- Modal Header -->
                <div class="sticky top-0 z-10 bg-white border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Add New Address</h3>
                                <p class="text-gray-500 text-sm">Fill in your address details</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeAddressModal()"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Form Content -->
                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="p-6">
                        <form id="addressForm" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" id="address_id" name="id">

                            <!-- Form Sections -->
                            <div class="space-y-6">
                                <!-- Section 1: Basic Info -->
                                <div class="bg-gray-50 p-4 rounded-xl">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wider">
                                        <i class="fas fa-user-circle mr-2"></i> Contact Information
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Address Name <span class="text-gray-400">(Optional)</span>
                                            </label>
                                            <input type="text" name="address_name" id="address_name"
                                                placeholder="e.g., Home, Office"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Full Name *
                                            </label>
                                            <input type="text" name="full_name" id="full_name" required
                                                placeholder="John Doe"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Phone Number *
                                            </label>
                                            <input type="text" name="phone" id="phone" required
                                                placeholder="+1 (123) 456-7890"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Address Details -->
                                <div class="bg-gray-50 p-4 rounded-xl">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wider">
                                        <i class="fas fa-home mr-2"></i> Address Details
                                    </h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Address Line 1 *
                                            </label>
                                            <input type="text" name="address_line1" id="address_line1" required
                                                placeholder="Street address, P.O. box"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Address Line 2 <span class="text-gray-400">(Optional)</span>
                                            </label>
                                            <input type="text" name="address_line2" id="address_line2"
                                                placeholder="Apartment, suite, unit, building, floor"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    City *
                                                </label>
                                                <input type="text" name="city" id="city" required
                                                    placeholder="City"
                                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    State *
                                                </label>
                                                <input type="text" name="state" id="state" required
                                                    placeholder="State"
                                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    ZIP Code *
                                                </label>
                                                <input type="text" name="zip_code" id="zip_code" required
                                                    placeholder="12345"
                                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Country *
                                            </label>
                                            <select name="country" id="country" required
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                                <option value="United States">United States</option>
                                                <option value="Canada">Canada</option>
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Germany">Germany</option>
                                                <option value="France">France</option>
                                                <option value="Japan">Japan</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 3: Address Settings -->
                                <div class="bg-gray-50 p-4 rounded-xl">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wider">
                                        <i class="fas fa-cog mr-2"></i> Address Settings
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Address Type *
                                            </label>
                                            <div class="flex space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="type" value="shipping"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                        id="type_shipping" checked>
                                                    <span class="ml-2 text-gray-700">
                                                        <i class="fas fa-truck mr-1"></i> Shipping
                                                    </span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="type" value="billing"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                        id="type_billing">
                                                    <span class="ml-2 text-gray-700">
                                                        <i class="fas fa-credit-card mr-1"></i> Billing
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <label class="flex items-center cursor-pointer">
                                                <div class="relative">
                                                    <input type="checkbox" name="is_default" id="is_default"
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-blue-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                                    </div>
                                                </div>
                                                <span class="ml-3 text-sm font-medium text-gray-700">
                                                    Set as Default Address
                                                    <span class="block text-xs text-gray-500">Use this address for all
                                                        orders</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <button type="button" onclick="closeAddressModal()"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 rounded-lg transition hover:shadow-sm">
                            Cancel
                        </button>
                        <button type="submit" form="addressForm" id="addressSubmitBtn"
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span id="submitBtnText">Save Address</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay (Separate from modal) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-[60] hidden">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-gray-700 font-medium">Loading address details...</p>
        </div>
    </div>

    <script>
    // Address Modal Manager
    class AddressModalManager {
        constructor() {
            this.modal = document.getElementById('addressModal');
            this.loadingOverlay = document.getElementById('loadingOverlay');
            this.isOpen = false;
            
            this.init();
        }

        init() {
            // Escape key to close
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });

            // Click outside to close
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal || e.target.classList.contains('modal-backdrop')) {
                    this.close();
                }
            });

            // Form submission
            document.getElementById('addressForm')?.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        async open(addressId = null) {
            if (this.isOpen) return;
            
            this.isOpen = true;
            this.modal.classList.remove('hidden');
            
            // Trigger reflow for animation
            this.modal.offsetHeight;
            
            // Animate backdrop
            this.modal.querySelector('.modal-backdrop').classList.remove('opacity-0');
            this.modal.querySelector('.modal-backdrop').classList.add('opacity-50');
            
            // Animate content
            const content = this.modal.querySelector('.modal-content');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');

            if (addressId) {
                await this.loadAddressData(addressId);
            } else {
                this.resetForm();
            }

            // Focus on first input
            setTimeout(() => {
                document.getElementById('address_name')?.focus();
            }, 300);
        }

        async loadAddressData(addressId) {
            try {
                this.showLoading();
                
                const response = await fetch(`/profile/address/${addressId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) throw new Error('Failed to load address');
                
                const data = await response.json();
                
                if (data.error) throw new Error(data.error);
                
                this.populateForm(data.address);
                document.getElementById('modalTitle').textContent = 'Edit Address';
                document.getElementById('submitBtnText').textContent = 'Update Address';
                
            } catch (error) {
                console.error('Error loading address:', error);
                this.showToast(error.message, 'error');
                this.resetForm();
            } finally {
                this.hideLoading();
            }
        }

        populateForm(address) {
            const fields = {
                'address_name': address.address_name || '',
                'full_name': address.full_name || '',
                'phone': address.phone || '',
                'address_line1': address.address_line1 || '',
                'address_line2': address.address_line2 || '',
                'city': address.city || '',
                'state': address.state || '',
                'zip_code': address.zip_code || '',
                'country': address.country || 'United States'
            };

            // Set text fields
            Object.keys(fields).forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) element.value = fields[fieldId];
            });

            // Set type radio
            const typeElement = document.querySelector(`input[name="type"][value="${address.type || 'shipping'}"]`);
            if (typeElement) typeElement.checked = true;

            // Set default checkbox
            const defaultCheckbox = document.getElementById('is_default');
            if (defaultCheckbox) defaultCheckbox.checked = address.is_default || false;

            // Set hidden ID
            document.getElementById('address_id').value = address.id || '';
        }

        resetForm() {
            const form = document.getElementById('addressForm');
            form.reset();
            
            // Reset specific fields
            document.getElementById('country').value = 'United States';
            document.querySelector('input[name="type"][value="shipping"]').checked = true;
            document.getElementById('is_default').checked = false;
            document.getElementById('address_id').value = '';
            
            document.getElementById('modalTitle').textContent = 'Add New Address';
            document.getElementById('submitBtnText').textContent = 'Save Address';
        }

        close() {
            if (!this.isOpen) return;
            
            this.isOpen = false;
            
            // Animate out
            const content = this.modal.querySelector('.modal-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            this.modal.querySelector('.modal-backdrop').classList.remove('opacity-50');
            this.modal.querySelector('.modal-backdrop').classList.add('opacity-0');
            
            setTimeout(() => {
                this.modal.classList.add('hidden');
                this.resetForm();
            }, 300);
        }

        showLoading() {
            this.loadingOverlay.classList.remove('hidden');
        }

        hideLoading() {
            this.loadingOverlay.classList.add('hidden');
        }

        async handleFormSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const addressId = formData.get('id');
            const isEdit = !!addressId;

            // Handle checkbox
            formData.set('is_default', document.getElementById('is_default').checked ? '1' : '0');
            
            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            const url = isEdit ? `/profile/address/${addressId}/update` : '/profile/address/add';
            const submitBtn = document.getElementById('addressSubmitBtn');
            const originalBtnHTML = submitBtn.innerHTML;

            // Update button state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>${isEdit ? 'Updating...' : 'Saving...'}</span>
            `;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.showToast(data.message || 'Address saved successfully!', 'success');
                    
                    setTimeout(() => {
                        this.close();
                        location.reload();
                    }, 1500);
                } else {
                    let errorMessage = 'An error occurred while saving the address';
                    
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    
                    this.showToast(errorMessage, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                }
            } catch (error) {
                console.error('Error:', error);
                this.showToast('Network error. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
            }
        }

        showToast(message, type = 'success') {
            // Remove existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) existingToast.remove();

            // Create toast
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-6 right-6 px-6 py-4 rounded-xl shadow-xl z-[9999] transition-all duration-300 transform translate-x-full flex items-center gap-3 ${
                type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' 
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
            }`;

            toast.innerHTML = `
                <div class="text-xl font-bold">${type === 'success' ? '✓' : '✗'}</div>
                <div class="font-medium">${message}</div>
            `;

            document.body.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            });

            // Auto remove
            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    }

    // Address Operations
    class AddressOperations {
        constructor(modalManager) {
            this.modalManager = modalManager;
    }

        async setDefaultAddress(id) {
            try {
                const response = await fetch(`/profile/address/${id}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'PUT' })
                });

                const data = await response.json();

                if (data.success) {
                    this.modalManager.showToast('Default address updated successfully!', 'success');
                    this.updateDefaultAddressUI(id);
                } else {
                    this.modalManager.showToast(data.message || 'Error updating default address', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.modalManager.showToast('An error occurred while updating default address', 'error');
            }
        }

        async deleteAddress(id) {
            const result = await Swal.fire({
                title: 'Delete Address?',
                text: "Are you sure you want to delete this address?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg',
                    cancelButton: 'px-4 py-2 rounded-lg border border-gray-300'
                }
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/profile/address/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.modalManager.showToast('Address deleted successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        this.modalManager.showToast(data.message || 'Failed to delete address', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.modalManager.showToast('An error occurred while deleting the address', 'error');
                }
            }
        }

        updateDefaultAddressUI(selectedId) {
            const addressCards = document.querySelectorAll('[class*="border"][class*="rounded-xl"]');

            addressCards.forEach(card => {
                if (!card.querySelector('button[onclick*="Address"]')) return;

                card.classList.remove('border-2', 'border-blue-500', 'bg-blue-50');

                const header = card.querySelector('.flex.justify-between div:first-child');
                if (header) {
                    const defaultBadge = header.querySelector('.bg-blue-100');
                    if (defaultBadge) defaultBadge.remove();
                }

                const setDefaultBtn = card.querySelector('button[onclick*="setDefaultAddress"]');
                if (setDefaultBtn) setDefaultBtn.style.display = 'inline-block';

                const deleteBtn = card.querySelector('button[onclick*="deleteAddress"]');
                if (deleteBtn) deleteBtn.style.display = 'inline-block';
            });

            addressCards.forEach(card => {
                if (!card.querySelector('button[onclick*="Address"]')) return;

                const addressBtn = card.querySelector(`button[onclick="setDefaultAddress(${selectedId})"]`);

                if (addressBtn) {
                    card.classList.add('border-2', 'border-blue-500', 'bg-blue-50');

                    const header = card.querySelector('.flex.justify-between div:first-child');
                    if (header) {
                        const badge = document.createElement('span');
                        badge.className = 'inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2';
                        badge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Default';
                        header.appendChild(badge);
                    }

                    addressBtn.style.display = 'none';

                    const deleteBtn = card.querySelector('button[onclick*="deleteAddress"]');
                    if (deleteBtn) deleteBtn.style.display = 'none';
                }
            });
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize modal manager
        const modalManager = new AddressModalManager();
        const addressOps = new AddressOperations(modalManager);

        // Make functions available globally
        window.openAddressModal = (id = null) => modalManager.open(id);
        window.closeAddressModal = () => modalManager.close();
        window.editAddress = (id) => modalManager.open(id);
        window.deleteAddress = (id) => addressOps.deleteAddress(id);
        window.setDefaultAddress = (id) => addressOps.setDefaultAddress(id);
        window.updateDefaultAddressUI = (id) => addressOps.updateDefaultAddressUI(id);
    });
</script>

<style>
    /* Modal animations */
    #addressModal {
        transition: visibility 0.3s;
    }

    .modal-backdrop {
        transition: opacity 0.3s ease;
    }

    .modal-content {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Loading spinner */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Toast animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .custom-toast {
        animation: slideInRight 0.3s ease-out;
        min-width: 300px;
        backdrop-filter: blur(10px);
    }
</style>
@endsection