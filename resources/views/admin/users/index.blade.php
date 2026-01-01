@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid">
        <!-- Header with animation -->
        <div class="mb-8" data-aos="fade-up">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('admin.users.title') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('admin.users.subtitle') }}</p>
                </div>
                <div class="flex items-center space-x-3" data-aos="fade-left" data-aos-delay="200">
                    <button onclick="showCreateModal()"
                        class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl transition-all duration-300 hover:from-Ocean/90 hover:to-Ocean/70 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        <span class="font-medium">{{ __('admin.users.actions.add_user') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards with staggered animations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $totalUsers = \App\Models\User::count();
                $activeUsers = \App\Models\User::where('is_active', 1)->count();
                $verifiedUsers = \App\Models\User::whereNotNull('email_verified_at')->count();
                $newsletterSubscribers = \App\Models\User::where('newsletter_opt_in', 1)->count();
                $activePercentage = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0;
                $verifiedPercentage = $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100) : 0;
            @endphp

            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300"
                data-aos="fade-up" data-aos-delay="50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">{{ __('admin.users.stats.total_users') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1" id="totalUsers">{{ number_format($totalUsers) }}
                        </p>
                        <p class="text-blue-500 text-xs mt-2 flex items-center">
                            <i class="fas fa-users mr-1"></i> {{ __('admin.users.stats.all_registered') }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300"
                data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">{{ __('admin.users.stats.active_users') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1" id="activeUsers">{{ number_format($activeUsers) }}
                        </p>
                        <div class="flex items-center mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $activePercentage }}%"></div>
                            </div>
                            <span class="text-green-600 text-xs font-medium">{{ $activePercentage }}%</span>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300"
                data-aos="fade-up" data-aos-delay="150">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium">{{ __('admin.users.stats.verified_users') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1" id="verifiedUsers">
                            {{ number_format($verifiedUsers) }}</p>
                        <div class="flex items-center mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $verifiedPercentage }}%"></div>
                            </div>
                            <span class="text-purple-600 text-xs font-medium">{{ $verifiedPercentage }}%</span>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-envelope text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300"
                data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-medium">{{ __('admin.users.stats.newsletter_subscribers') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1" id="newsletterUsers">
                            {{ number_format($newsletterSubscribers) }}</p>
                        <p class="text-orange-500 text-xs mt-2 flex items-center">
                            <i class="fas fa-newspaper mr-1"></i> {{ __('admin.users.stats.email_newsletter') }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-md">
                        <i class="fas fa-newspaper text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up"
            data-aos-delay="250">
            <!-- Filters and Search -->
            <div class="p-5 mb-6" data-aos="fade-in" data-aos-delay="300">
                <div class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
                    <!-- Search -->
                    <div class="flex-1" data-aos="fade-right" data-aos-delay="350">
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search by name, email, phone..."
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300 shadow-sm">
                            <div id="searchLoading" class="hidden absolute right-4 top-1/2 transform -translate-y-1/2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-Ocean"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Type Filter -->
                    <div data-aos="fade-down" data-aos-delay="400">
                        <select id="accountTypeFilter"
                            class="w-full md:w-auto px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300 shadow-sm">
                            <option value="">{{ __('admin.users.filters.all_account_types') }}</option>
                            @foreach ($accountTypes as $type)
                                <option value="{{ $type }}"
                                    {{ request('account_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div data-aos="fade-down" data-aos-delay="450">
                        <select id="statusFilter"
                            class="w-full md:w-auto px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300 shadow-sm">
                            <option value="">{{ __('admin.users.filters.all_status') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.users.filters.status_active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.users.filters.status_inactive') }}
                            </option>
                        </select>
                    </div>

                    <!-- Verification Filter -->
                    <div data-aos="fade-down" data-aos-delay="500">
                        <select id="verificationFilter"
                            class="w-full md:w-auto px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300 shadow-sm">
                            <option value="">{{ __('admin.users.filters.all_verification') }}</option>
                            <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>
                                {{ __('admin.users.filters.verified') }}</option>
                            <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>
                                {{ __('admin.users.filters.unverified') }}</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div data-aos="fade-left" data-aos-delay="550">
                        <button id="resetFilters"
                            class="w-full md:w-auto px-4 py-3 bg-gray-100 text-gray-700 rounded-xl transition-colors duration-300 hover:bg-gray-200 shadow-sm">
                            <i class="fas fa-redo mr-2"></i> {{ __('admin.users.filters.reset') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between" data-aos="fade-in"
                data-aos-delay="600">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="select-all" class="w-4 h-4 text-Ocean rounded focus:ring-Ocean">
                    <label for="select-all" class="text-sm font-medium text-gray-700">{{ __('admin.users.bulk_actions.select_all') }}</label>
                    <select id="bulk-action-select"
                        class="text-sm p-2 border-gray-300 rounded-lg focus:ring-Ocean focus:border-Ocean shadow-sm">
                        <option value="">{{ __('admin.users.bulk_actions.bulk_actions') }}</option>
                        <option value="activate">{{ __('admin.users.bulk_actions.activate_selected') }}</option>
                        <option value="deactivate">{{ __('admin.users.bulk_actions.deactivate_selected') }}</option>
                        <option value="delete">{{ __('admin.users.bulk_actions.delete_selected') }}</option>
                    </select>
                    <button id="apply-bulk-action"
                        class="px-3 py-1.5 text-sm bg-Ocean text-white rounded-lg transition-colors hover:bg-Ocean/90 shadow-sm">
                        Apply
                    </button>
                </div>
                <div class="text-sm text-gray-600">
                    <span id="showingText">{{ __('admin.users.filters.loading') }}</span>
                </div>
            </div>

            <!-- Table Container -->
            <div id="tableContainer">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left">
                                    <input type="checkbox" id="table-select-all"
                                        class="w-4 h-4 text-Ocean rounded focus:ring-Ocean">
                                </th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.users.table.user')  }}</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.users.table.account_type') }}</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.users.table.status') }}</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.users.table.verification') }}</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.users.table.loyalty_points') }}</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.users.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody" class="divide-y divide-gray-200">
                            <!-- Content will be loaded via AJAX -->
                            <tr data-aos="fade-in">
                                <td colspan="8" class="py-8 text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-Ocean mx-auto"></div>
                                    <p class="mt-4 text-gray-600">{{ __('admin.users.filters.loading') }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="p-4 border-t border-gray-200 hidden">
                <!-- Pagination will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    @include('admin.users.edit-modal', ['accountTypes' => $accountTypes])

    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        data-aos="zoom-in">
        <div
            class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ __('admin.users.modal.create_title') }}</h3>
                        <p class="text-gray-600 mt-1">{{ __('admin.users.modal.create_subtitle') }}</p>
                    </div>
                    <button onclick="hideCreateModal()" class="text-gray-500 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div id="createUserContent">
                    <!-- Include create form directly -->
                    @include('admin.users.create-modal', ['accountTypes' => $accountTypes])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
   <script>
    // Initialize AOS
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 600,
            easing: 'ease-out-quart',
            once: true,
            offset: 100
        });
        
        // Initialize the page
        initializePage();
    });

    // Global variables
    let searchTimeout;
    let currentPage = 1;
    let loading = false;

    // SweetAlert2 Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // Initialize page functions
    function initializePage() {
        // Load initial users
        loadUsers(1);

        // Setup filters
        setupFilters();

        // Setup bulk actions
        setupBulkActions();
        setupBulkActionButton();
    }

    // Load users with AJAX
    function loadUsers(page = 1) {
        if (loading) return;

        loading = true;
        currentPage = page;

        const search = document.getElementById('searchInput')?.value || '';
        const accountType = document.getElementById('accountTypeFilter')?.value || '';
        const status = document.getElementById('statusFilter')?.value || '';
        const verification = document.getElementById('verificationFilter')?.value || '';

        const params = new URLSearchParams({
            page: page,
            search: search,
            account_type: accountType,
            status: status,
            verification: verification,
            ajax: 1
        });

        // Show loading in table
        const tbody = document.getElementById('usersTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="py-8 text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-Ocean mx-auto"></div>
                        <p class="mt-4 text-gray-600">Loading users...</p>
                    </td>
                </tr>
            `;
        }

        // Show search loading if search input exists
        const searchLoading = document.getElementById('searchLoading');
        if (searchLoading) {
            searchLoading.classList.remove('hidden');
        }

        fetch(`{{ route('admin.users.index') }}?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update stats
                updateStats(data.stats || {});

                // Update table
                updateTable(data.users || {});

                // Update pagination
                updatePagination(data.pagination || {});

                // Update showing text
                updateShowingText(data.users || {});
            })
            .catch(error => {
                console.error('Error loading users:', error);
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="py-8 text-center">
                                <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                                <p class="text-gray-600">Failed to load users</p>
                                <button onclick="loadUsers(1)" class="mt-2 px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded">
                                    Retry
                                </button>
                            </td>
                        </tr>
                    `;
                }
            })
            .finally(() => {
                loading = false;
                if (searchLoading) {
                    searchLoading.classList.add('hidden');
                }
            });
    }

    // Update statistics
    function updateStats(stats) {
        const totalUsersEl = document.getElementById('totalUsers');
        const activeUsersEl = document.getElementById('activeUsers');
        const verifiedUsersEl = document.getElementById('verifiedUsers');
        const newsletterUsersEl = document.getElementById('newsletterUsers');

        if (totalUsersEl && stats.totalUsers !== undefined) {
            totalUsersEl.textContent = stats.totalUsers.toLocaleString();
        }
        if (activeUsersEl && stats.activeUsers !== undefined) {
            activeUsersEl.textContent = stats.activeUsers.toLocaleString();
        }
        if (verifiedUsersEl && stats.verifiedUsers !== undefined) {
            verifiedUsersEl.textContent = stats.verifiedUsers.toLocaleString();
        }
        if (newsletterUsersEl && stats.newsletterUsers !== undefined) {
            newsletterUsersEl.textContent = stats.newsletterUsers.toLocaleString();
        }
    }

    // Update table content
    function updateTable(users) {
        const tbody = document.getElementById('usersTableBody');
        if (!tbody) return;

        if (!users.data || users.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">No users found</p>
                            <p class="text-gray-400 mt-1">Try adjusting your filters</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        users.data.forEach((user, index) => {
            html += `
                <tr data-user-id="${user.id}" data-aos="fade-up" data-aos-delay="${index * 50}">
                    <td class="py-3 px-4">
                        <input type="checkbox" 
                               value="${user.id}"
                               class="user-checkbox w-4 h-4 text-Ocean rounded focus:ring-Ocean">
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                ${user.profile_picture ? 
                                    `<img src="/storage/${user.profile_picture}" 
                                          alt="${user.name}"
                                          class="w-10 h-10 rounded-lg object-cover ring-2 ring-white shadow-sm">` :
                                    `<div class="w-10 h-10 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center ring-2 ring-white shadow-sm">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>`
                                }
                                ${user.is_active ? 
                                    `<div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>` :
                                    ''
                                }
                            </div>
                            <div>
                                <a href="/admin/users/${user.id}"
                                   class="font-medium text-gray-900 transition-colors hover:text-Ocean">
                                    ${user.name}
                                </a>
                                <p class="text-sm text-gray-500">${user.email}</p>
                                ${user.phone ? `<p class="text-xs text-gray-400">${user.phone}</p>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            ${user.account_type === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 
                               user.account_type === 'staff' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                               user.account_type === 'vendor' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 
                               'bg-gray-100 text-gray-800 border border-gray-200'}">
                            <i class="fas fa-${user.account_type === 'admin' ? 'crown' : (user.account_type === 'staff' ? 'user-tie' : (user.account_type === 'vendor' ? 'store' : 'user'))} mr-1 text-xs"></i>
                            ${user.account_type ? user.account_type.charAt(0).toUpperCase() + user.account_type.slice(1) : 'User'}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleUserStatus(${user.id}, ${user.is_active ? 'false' : 'true'})"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        ${user.is_active ? 'bg-green-100 text-green-800 border border-green-200 hover:bg-green-200' : 'bg-red-100 text-red-800 border border-red-200 hover:bg-red-200'} transition-colors">
                                <i class="fas fa-${user.is_active ? 'check-circle' : 'times-circle'} mr-1"></i>
                                ${user.is_active ? 'Active' : 'Inactive'}
                            </button>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            ${user.email_verified_at ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'}">
                            <i class="fas fa-${user.email_verified_at ? 'check-circle' : 'times-circle'} mr-1"></i>
                            ${user.email_verified_at ? 'Verified' : 'Unverified'}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-1">
                            <div class="relative">
                                <i class="fas fa-star text-yellow-500"></i>
                                ${user.loyalty_points > 0 ? 
                                    `<div class="absolute -top-1 -right-1 w-4 h-4 bg-Ocean text-white text-xs rounded-full flex items-center justify-center">
                                        ${Math.min(user.loyalty_points, 99)}${user.loyalty_points > 99 ? '+' : ''}
                                    </div>` : ''
                                }
                            </div>
                            <span class="font-medium">${user.loyalty_points?.toLocaleString() || '0'}</span>
                            <span class="text-gray-500 text-xs">pts</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center space-x-1">
                            <a href="/admin/users/${user.id}"
                               class="p-2 rounded-lg text-gray-400 hover:text-Ocean hover:bg-gray-100 transition-colors"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="editUser(${user.id})"
                               class="p-2 rounded-lg text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-colors"
                               title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteUser(${user.id}, '${user.name ? user.name.replace(/'/g, "\\'") : ''}')"
                               class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                               title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        
        // Re-initialize AOS for newly loaded content
        AOS.refresh();
    }

    // Update pagination
    function updatePagination(pagination) {
        const container = document.getElementById('paginationContainer');
        if (!container) return;

        if (!pagination || pagination.total <= pagination.per_page) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');

        let html = '<div class="flex justify-center">';
        html += '<div class="flex items-center space-x-1">';

        // Previous button
        if (pagination.current_page > 1) {
            html += `
                <button onclick="loadUsers(${pagination.current_page - 1})" 
                        class="px-3 py-1 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
        }

        // Page numbers
        const totalPages = Math.ceil(pagination.total / pagination.per_page);
        const maxVisible = 5;
        let start = Math.max(1, pagination.current_page - Math.floor(maxVisible / 2));
        let end = Math.min(totalPages, start + maxVisible - 1);

        if (end - start + 1 < maxVisible) {
            start = Math.max(1, end - maxVisible + 1);
        }

        for (let i = start; i <= end; i++) {
            if (i === pagination.current_page) {
                html += `
                    <button class="px-3 py-1 rounded-lg bg-Ocean text-white">
                        ${i}
                    </button>
                `;
            } else {
                html += `
                    <button onclick="loadUsers(${i})" 
                            class="px-3 py-1 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                        ${i}
                    </button>
                `;
            }
        }

        // Next button
        if (pagination.current_page < totalPages) {
            html += `
                <button onclick="loadUsers(${pagination.current_page + 1})" 
                        class="px-3 py-1 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
        }

        html += '</div></div>';
        container.innerHTML = html;
    }

    // Update showing text
    function updateShowingText(users) {
        const showingText = document.getElementById('showingText');
        if (!showingText || !users) return;

        const start = (users.current_page - 1) * users.per_page + 1;
        const end = Math.min(users.current_page * users.per_page, users.total);

        showingText.textContent = `Showing ${start} to ${end} of ${users.total} users`;
    }

    // Real-time search and filtering
    function setupFilters() {
        const searchInput = document.getElementById('searchInput');
        const accountTypeFilter = document.getElementById('accountTypeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const verificationFilter = document.getElementById('verificationFilter');
        const resetButton = document.getElementById('resetFilters');

        if (!searchInput || !accountTypeFilter || !statusFilter || !verificationFilter || !resetButton) {
            return;
        }

        // Real-time search with debounce
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadUsers(1);
            }, 500);
        });

        // Instant filtering for selects
        accountTypeFilter.addEventListener('change', () => loadUsers(1));
        statusFilter.addEventListener('change', () => loadUsers(1));
        verificationFilter.addEventListener('change', () => loadUsers(1));

        // Reset filters
        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            accountTypeFilter.value = '';
            statusFilter.value = '';
            verificationFilter.value = '';
            loadUsers(1);
        });
    }

    // Select All Checkboxes
    function setupBulkActions() {
        const selectAll = document.getElementById('select-all');
        const tableSelectAll = document.getElementById('table-select-all');

        if (!selectAll || !tableSelectAll) return;

        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        tableSelectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const selectAll = document.getElementById('select-all');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            if (selectAll) {
                selectAll.checked = this.checked;
            }
        });
    }

    // Bulk Actions
        function setupBulkActionButton() {
            document.getElementById('apply-bulk-action').addEventListener('click', async function() {
                const action = document.getElementById('bulk-action-select').value;
                const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                    .map(cb => cb.value);

                if (!action) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Please select a bulk action'
                    });
                    return;
                }

                if (selectedUsers.length === 0) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Please select at least one user'
                    });
                    return;
                }

                const result = await Swal.fire({
                    title: `${action.charAt(0).toUpperCase() + action.slice(1)} Users?`,
                    text: `Are you sure you want to ${action} ${selectedUsers.length} user(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Yes, ${action}!`,
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading()
                    });

                    try {
                        const response = await fetch('{{ route('admin.users.bulk-action') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                action: action,
                                users: selectedUsers
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadUsers(currentPage); // Reload current page
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: data.message
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred'
                        });
                    }
                }
            });
        }

        // Toggle User Status
        async function toggleUserStatus(userId, newStatus) {
            const statusText = newStatus ? 'activate' : 'deactivate';

            const result = await Swal.fire({
                title: `${statusText.charAt(0).toUpperCase() + statusText.slice(1)} User?`,
                text: `Are you sure you want to ${statusText} this user?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${statusText}!`,
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`{{ url('admin/users') }}/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            is_active: newStatus
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        loadUsers(currentPage); // Reload current page
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }
                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred'
                    });
                }
            }
        }

        // Edit User Modal Functions
        async function editUser(userId) {
            const modal = document.getElementById('editUserModal');
            const modalContent = modal.querySelector('.modal-content');
            const editContent = document.getElementById('editUserContent');

            // Show modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('flex');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 50);
            }, 50);

            // Load edit form
            editContent.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-Ocean mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading user data...</p>
        </div>
    `;

            try {
                const response = await fetch(`{{ url('admin/users') }}/${userId}/edit-form`);
                const html = await response.text();
                editContent.innerHTML = html;
                initEditForm();
            } catch (error) {
                editContent.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                <p class="text-gray-600">Failed to load user data</p>
                <button onclick="hideEditModal()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">
                    Close
                </button>
            </div>
        `;
            }
        }

        function initEditForm() {
            console.log('Initializing edit form...');

            // Profile picture preview for edit form
            const editFileInput = document.getElementById('edit_profile_picture');
            if (editFileInput) {
                console.log('Found edit file input, attaching event listener...');

                // Remove any existing event listeners
                const newEditInput = editFileInput.cloneNode(true);
                editFileInput.parentNode.replaceChild(newEditInput, editFileInput);

                // Attach new event listener
                newEditInput.addEventListener('change', function(e) {
                    const preview = document.getElementById('editProfilePicturePreview');
                    const previewImage = document.getElementById('editProfilePreviewImage');

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                console.log('Edit file preview initialized');
            } else {
                console.log('Edit file input not found');
            }

            // Form submission for edit form
            const form = document.getElementById('editUserForm');
            if (form) {
                console.log('Found edit form, attaching submit handler...');

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    console.log('Edit form submitted');

                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        if (response.ok) {
                            Toast.fire({
                                icon: 'success',
                                title: 'User updated successfully!'
                            });
                            setTimeout(() => {
                                hideEditModal();
                                window.location.reload();
                            }, 1500);
                        } else {
                            const data = await response.json();
                            let errorMessage = 'Please fix the errors:';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('<br>');
                            } else if (data.message) {
                                errorMessage = data.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: errorMessage
                            });
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update user'
                        });
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });

                console.log('Edit form submission initialized');
            } else {
                console.log('Edit form not found');
            }
        }

        function hideEditModal() {
            const modal = document.getElementById('editUserModal');
            const modalContent = modal.querySelector('.modal-content');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
        // Create User Modal Functions
        function showCreateModal() {
            const modal = document.getElementById('createUserModal');
            const modalContent = modal.querySelector('.modal-content');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('flex');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 50);
            }, 50);

            // Initialize create form
            initCreateForm();
        }

        function initCreateForm() {
            const form = document.getElementById('createUserForm');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        if (response.ok) {
                            Toast.fire({
                                icon: 'success',
                                title: 'User created successfully!'
                            });
                            setTimeout(() => {
                                hideCreateModal();
                                window.location.reload();
                            }, 1500);
                        } else {
                            const data = await response.json();
                            let errorMessage = 'Please fix the errors:';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('<br>');
                            } else if (data.message) {
                                errorMessage = data.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: errorMessage
                            });
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to create user'
                        });
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        }

        function hideCreateModal() {
            const modal = document.getElementById('createUserModal');
            const modalContent = modal.querySelector('.modal-content');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }


        // Delete User Function
        async function deleteUser(userId, userName) {
    const result = await Swal.fire({
        title: '{{ __("admin.users.delete.title") }}',
        html: `<div class="text-left">
            <p class="mb-4">Are you sure you want to delete <strong>"${userName}"</strong>?</p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ __("admin.users.delete.warning") }}
                </p>
            </div>
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("admin.users.delete.yes_delete") }}',
        cancelButtonText: '{{ __("admin.users.delete.cancel") }}'
    });

    if (result.isConfirmed) {
        Swal.fire({
            title: '{{ __("admin.users.delete.deleting") }}',
            text: '{{ __("admin.users.messages.please_wait") }}',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading()
        });

        try {
            const response = await fetch(`{{ url('admin/users') }}/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("admin.users.messages.success_deleted") }}',
                    text: data.message || '{{ __("admin.users.messages.success_deleted_message") }}',
                    timer: 2000,
                    showConfirmButton: false
                });
                loadUsers(currentPage); // Reload current page
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("admin.users.messages.error_failed") }}',
                    text: data.message || '{{ __("admin.users.messages.error_failed_message") }}'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: '{{ __("admin.users.messages.error_occurred") }}',
                text: '{{ __("admin.users.messages.error_occurred") }}'
            });
        }
    }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial users
            loadUsers(1);

            // Setup filters
            setupFilters();

            // Setup bulk actions
            setupBulkActions();
            setupBulkActionButton();

            // Add New User button
            const addUserBtn = document.querySelector('a[href*="create"]');
            if (addUserBtn) {
                addUserBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showCreateModal();
                });
            }
        });
</script>
@endpush
