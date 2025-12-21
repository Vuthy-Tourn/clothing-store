@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Admin Profile</h1>
                        <p class="text-gray-600 mt-2">Manage your administrator account</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="showProfileModal()"
                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div id="profileContent">
                @include('admin.profile.partials.content')
                {{-- @include('admin.profile.partials.addresses') --}}
            </div>
        </div>
    </div>

    <!-- Include Modals -->
    @include('admin.profile.modals.edit')
    @include('admin.profile.modals.password')
    @include('admin.profile.modals.activity')
    {{-- @include('admin.profile.modals.address') --}}

    <style>
        /* Modal animations - centered */
        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            opacity: 0;
            transition: all 0.3s ease-out;
        }

        .modal-enter {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .modal-exit {
            transform: translate(-50%, -50%) scale(0.95);
            opacity: 0;
        }
    </style>

    <!-- JavaScript -->
    <script>
        // Modal functions
        function showProfileModal() {
            const modal = document.getElementById('profileEditModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                const modalContent = modal.querySelector('.modal-content');
                modalContent.classList.remove('modal-exit');
                modalContent.classList.add('modal-enter');
            }, 10);
        }

        function hideProfileModal() {
            const modal = document.getElementById('profileEditModal');
            const modalContent = modal.querySelector('.modal-content');
            modalContent.classList.remove('modal-enter');
            modalContent.classList.add('modal-exit');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function showPasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                const modalContent = modal.querySelector('.modal-content');
                modalContent.classList.remove('modal-exit');
                modalContent.classList.add('modal-enter');
            }, 10);
        }

        function hidePasswordModal() {
            const modal = document.getElementById('passwordModal');
            const modalContent = modal.querySelector('.modal-content');
            modalContent.classList.remove('modal-enter');
            modalContent.classList.add('modal-exit');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // function showAddressModal(addressId = null) {
        //     const modal = document.getElementById('addressModal');
        //     const title = document.getElementById('addressModalTitle');

        //     if (addressId) {
        //         title.textContent = 'Edit Address';
        //         // Load address data
        //         fetch(`/admin/profile/addresses/${addressId}`)
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.success) {
        //                     const address = data.address;
        //                     document.getElementById('address_id').value = address.id;
        //                     document.querySelector(`input[name="type"][value="${address.type}"]`).checked = true;
        //                     document.getElementById('address_name').value = address.address_name || '';
        //                     document.getElementById('full_name').value = address.full_name;
        //                     document.getElementById('phone').value = address.phone;
        //                     document.getElementById('address_line1').value = address.address_line1;
        //                     document.getElementById('address_line2').value = address.address_line2 || '';
        //                     document.getElementById('city').value = address.city;
        //                     document.getElementById('state').value = address.state;
        //                     document.getElementById('zip_code').value = address.zip_code;
        //                     document.getElementById('country').value = address.country;
        //                     document.getElementById('is_default').checked = address.is_default;
        //                 }
        //             });
        //     } else {
        //         title.textContent = 'Add New Address';
        //         // Reset form
        //         document.getElementById('addressForm').reset();
        //         document.getElementById('address_id').value = '';
        //     }

        //     modal.classList.remove('hidden');
        //     setTimeout(() => {
        //         const modalContent = modal.querySelector('.modal-content');
        //         modalContent.classList.remove('modal-exit');
        //         modalContent.classList.add('modal-enter');
        //     }, 10);
        // }

        // function hideAddressModal() {
        //     const modal = document.getElementById('addressModal');
        //     const modalContent = modal.querySelector('.modal-content');
        //     modalContent.classList.remove('modal-enter');
        //     modalContent.classList.add('modal-exit');
        //     setTimeout(() => modal.classList.add('hidden'), 300);
        // }

        // Password toggle
        function togglePassword(button) {
            const input = button.previousElementSibling;
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // AJAX form submissions
        document.addEventListener('DOMContentLoaded', function() {
            // Profile update form
            const profileForm = document.getElementById('profileUpdateForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitForm(this, '{{ route('admin.profile.update') }}', 'profileEditModal');
                });
            }

            // Password update form
            const passwordForm = document.getElementById('passwordUpdateForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitForm(this, '{{ route('admin.profile.password.update') }}', 'passwordModal');
                });
            }

            // Newsletter toggle
            const newsletterToggle = document.getElementById('newsletterToggle');
            if (newsletterToggle) {
                newsletterToggle.addEventListener('change', function() {
                    const isChecked = this.checked;
                    const formData = new FormData();
                    formData.append('newsletter_opt_in', isChecked);

                    fetch('{{ route('admin.profile.toggle-newsletter') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast(
                                    isChecked ? 'Subscribed to newsletter!' :
                                    'Unsubscribed from newsletter',
                                    'success'
                                );
                            } else {
                                showToast(data.message || 'Update failed', 'error');
                                this.checked = !isChecked;
                            }
                        })
                        .catch(error => {
                            showToast('An error occurred', 'error');
                            this.checked = !isChecked;
                        });
                });
            }
        });

        function submitForm(form, url, modalId = null) {
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            submitBtn.disabled = true;

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        if (modalId) {
                            // Close modal after success
                            setTimeout(() => {
                                if (modalId === 'profileEditModal') hideProfileModal();
                                if (modalId === 'passwordModal') hidePasswordModal();
                                if (modalId === 'addressModal') hideAddressModal();
                            }, 1000);
                        }
                        // Reload page after delay to show updated data
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        let errorMessage = 'Update failed';
                        if (data.errors) {
                            // Get first error message
                            const firstError = Object.values(data.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        showToast(errorMessage, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        // Toast notification
        function showToast(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-Ocean',
                warning: 'bg-yellow-500'
            };

            const toast = document.createElement('div');
            toast.className =
                `fixed top-6 right-6 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-3"></i>
            <span>${message}</span>
        </div>
    `;

            document.body.appendChild(toast);

            setTimeout(() => toast.classList.remove('translate-x-full'), 10);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Address management functions
        function editAddress(id) {
            showAddressModal(id);
        }

        function deleteAddress(id) {
            if (confirm('Are you sure you want to delete this address?')) {
                fetch(`/admin/profile/addresses/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Address deleted successfully!', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(data.message || 'Failed to delete address', 'error');
                        }
                    })
                    .catch(error => {
                        showToast('An error occurred', 'error');
                    });
            }
        }

        function setDefaultAddress(id) {
            fetch(`/admin/profile/addresses/${id}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Default address updated successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Failed to update default address', 'error');
                    }
                })
                .catch(error => {
                    showToast('An error occurred', 'error');
                });
        }
    </script>
@endsection
