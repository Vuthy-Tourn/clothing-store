@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-4 md:px-8 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">User Management</h1>
                <p class="text-gray-600 mt-1">Manage all registered users in the system</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.create') }}"
                   class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300 group">
                    <i class="fas fa-plus group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-medium">Add New User</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $totalUsers = \App\Models\User::count();
            $activeUsers = \App\Models\User::where('is_active', 1)->count();
            $verifiedUsers = \App\Models\User::whereNotNull('email_verified_at')->count();
            $newsletterSubscribers = \App\Models\User::where('newsletter_opt_in', 1)->count();
            $activePercentage = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0;
            $verifiedPercentage = $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100) : 0;
        @endphp

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalUsers) }}</p>
                    <p class="text-blue-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-users mr-1"></i> All registered users
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Active Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($activeUsers) }}</p>
                    <div class="flex items-center mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $activePercentage }}%"></div>
                        </div>
                        <span class="text-green-600 text-xs font-medium">{{ $activePercentage }}%</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Verified Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($verifiedUsers) }}</p>
                    <div class="flex items-center mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $verifiedPercentage }}%"></div>
                        </div>
                        <span class="text-purple-600 text-xs font-medium">{{ $verifiedPercentage }}%</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">Newsletter Subscribers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($newsletterSubscribers) }}</p>
                    <p class="text-orange-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-newspaper mr-1"></i> Email newsletter
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                    <i class="fas fa-newspaper text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 p-5 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-user-tag text-gray-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Account Types</p>
                    <div class="flex items-center space-x-2 mt-1">
                        @php
                            $accountStats = \App\Models\User::select('account_type', DB::raw('count(*) as count'))
                                ->groupBy('account_type')
                                ->pluck('count', 'account_type')
                                ->toArray();
                        @endphp
                        @foreach(['customer', 'vendor', 'admin', 'staff'] as $type)
                            @if(isset($accountStats[$type]))
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $type === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($type === 'staff' ? 'bg-blue-100 text-blue-800' : 
                                   ($type === 'vendor' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($type) }}: {{ $accountStats[$type] }}
                            </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 p-5 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-star text-yellow-500"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Loyalty Points</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">
                        {{ number_format(\App\Models\User::sum('loyalty_points')) }}
                        <span class="text-sm font-normal text-gray-500">total points</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 p-5 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-green-500"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Recent Activity</p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ \App\Models\User::whereNotNull('last_login_at')
                            ->orderBy('last_login_at', 'desc')
                            ->first()?->last_login_at?->diffForHumans() ?? 'No recent logins' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card p-5 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name, email, phone..."
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <!-- Account Type Filter -->
            <div>
                <select name="account_type" 
                        class="w-full md:w-auto px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">All Account Types</option>
                    @foreach($accountTypes as $type)
                        <option value="{{ $type }}" {{ request('account_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" 
                        class="w-full md:w-auto px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Verification Filter -->
            <div>
                <select name="verification" 
                        class="w-full md:w-auto px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                    <option value="">All Verification</option>
                    <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button type="submit"
                        class="px-4 py-3 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors duration-300">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card overflow-hidden">
        <!-- Bulk Actions -->
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="select-all" class="w-4 h-4 text-Ocean rounded focus:ring-Ocean">
                <label for="select-all" class="text-sm font-medium text-gray-700">Select All</label>
                <select id="bulk-action-select" class="text-sm border-gray-300 rounded-lg focus:ring-Ocean focus:border-Ocean">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate Selected</option>
                    <option value="deactivate">Deactivate Selected</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button id="apply-bulk-action"
                        class="px-3 py-1.5 text-sm bg-Ocean text-white rounded-lg hover:bg-Ocean/90 transition-colors">
                    Apply
                </button>
            </div>
            <div class="text-sm text-gray-600">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left">
                            <input type="checkbox" id="table-select-all" class="w-4 h-4 text-Ocean rounded focus:ring-Ocean">
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Type</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loyalty Points</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150" data-user-id="{{ $user->id }}">
                        <td class="py-3 px-4">
                            <input type="checkbox" 
                                   value="{{ $user->id }}"
                                   class="user-checkbox w-4 h-4 text-Ocean rounded focus:ring-Ocean">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                             alt="{{ $user->name }}"
                                             class="w-10 h-10 rounded-lg object-cover ring-2 ring-white shadow-sm">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center ring-2 ring-white shadow-sm">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                    @endif
                                    @if($user->is_active)
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="font-medium text-gray-900 hover:text-Ocean transition-colors group flex items-center">
                                        {{ $user->name }}
                                        <i class="fas fa-external-link-alt text-xs text-gray-400 ml-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    @if($user->phone)
                                    <p class="text-xs text-gray-400">{{ $user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->account_type === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 
                                   ($user->account_type === 'staff' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                   ($user->account_type === 'vendor' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 
                                   'bg-gray-100 text-gray-800 border border-gray-200')) }}">
                                <i class="fas fa-{{ $user->account_type === 'admin' ? 'crown' : ($user->account_type === 'staff' ? 'user-tie' : ($user->account_type === 'vendor' ? 'store' : 'user')) }} mr-1 text-xs"></i>
                                {{ ucfirst($user->account_type) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all duration-300
                                            {{ $user->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200 border border-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200 border border-red-200' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->email_verified_at ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                <i class="fas fa-{{ $user->email_verified_at ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-1">
                                <div class="relative">
                                    <i class="fas fa-star text-yellow-500"></i>
                                    @if($user->loyalty_points > 0)
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-Ocean text-white text-xs rounded-full flex items-center justify-center">
                                        {{ min($user->loyalty_points, 99) }}{{ $user->loyalty_points > 99 ? '+' : '' }}
                                    </div>
                                    @endif
                                </div>
                                <span class="font-medium">{{ number_format($user->loyalty_points) }}</span>
                                <span class="text-gray-500 text-xs">pts</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm text-gray-600">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                @if($user->last_login_at)
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $user->last_login_at->format('M d, Y h:i A') }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="action-btn view-btn p-2 rounded-lg hover:bg-Ocean/10 transition-all duration-300 group"
                                   title="View Details">
                                    <i class="fas fa-eye text-gray-400 group-hover:text-Ocean"></i>
                                </a>
                                <button onclick="editUser({{ $user->id }})"
                                   class="action-btn edit-btn p-2 rounded-lg hover:bg-blue-50 transition-all duration-300 group"
                                   title="Edit User">
                                    <i class="fas fa-edit text-gray-400 group-hover:text-blue-600"></i>
                                </button>
                                <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                   class="action-btn delete-btn p-2 rounded-lg hover:bg-red-50 transition-all duration-300 group"
                                   title="Delete User">
                                    <i class="fas fa-trash text-gray-400 group-hover:text-red-600"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">No users found</p>
                                @if(request()->hasAny(['search', 'account_type', 'status', 'verification']))
                                <p class="text-gray-400 mt-1">Try adjusting your filters</p>
                                <a href="{{ route('admin.users.index') }}"
                                   class="mt-4 px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                    Clear Filters
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Edit User</h3>
                    <p class="text-gray-600 mt-1">Update user information</p>
                </div>
                <button onclick="hideEditModal()" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div id="editUserContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Create New User</h3>
                    <p class="text-gray-600 mt-1">Add a new user to the system</p>
                </div>
                <button onclick="hideCreateModal()" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
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

@push('styles')
<style>
.action-btn {
    position: relative;
    overflow: hidden;
}

.action-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(88, 104, 121, 0.1);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
}

.action-btn:hover::after {
    width: 100%;
    height: 100%;
}

.view-btn:hover::after {
    background: rgba(88, 104, 121, 0.1);
}

.edit-btn:hover::after {
    background: rgba(59, 130, 246, 0.1);
}

.delete-btn:hover::after {
    background: rgba(239, 68, 68, 0.1);
}

/* Progress bar animation */
@keyframes progress {
    from { width: 0; }
    to { width: attr(data-progress); }
}

/* Row hover animation */
tr:hover {
    transform: translateX(4px);
    transition: transform 0.2s ease;
}
</style>
@endpush

@push('scripts')
<script>
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

// Select All Checkboxes
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

document.getElementById('table-select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAll = document.getElementById('select-all');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    selectAll.checked = this.checked;
});

// Bulk Actions
document.getElementById('apply-bulk-action').addEventListener('click', async function() {
    const action = document.getElementById('bulk-action-select').value;
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
        .map(cb => cb.value);

    if (!action) {
        Toast.fire({ icon: 'error', title: 'Please select a bulk action' });
        return;
    }

    if (selectedUsers.length === 0) {
        Toast.fire({ icon: 'error', title: 'Please select at least one user' });
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
            const response = await fetch('{{ route("admin.users.bulk-action") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: action, users: selectedUsers })
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
                setTimeout(() => window.location.reload(), 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Failed!', text: data.message });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error!', text: 'An error occurred' });
        }
    }
});

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
                body: JSON.stringify({ is_active: newStatus })
            });

            const data = await response.json();
            
            if (data.success) {
                Toast.fire({ icon: 'success', title: data.message });
                setTimeout(() => window.location.reload(), 1000);
            } else {
                Toast.fire({ icon: 'error', title: data.message });
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'An error occurred' });
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
    const form = document.getElementById('editUserForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
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
                    Toast.fire({ icon: 'success', title: 'User updated successfully!' });
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
                    Swal.fire({ icon: 'error', title: 'Error', html: errorMessage });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update user' });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
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
                    Toast.fire({ icon: 'success', title: 'User created successfully!' });
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
                    Swal.fire({ icon: 'error', title: 'Error', html: errorMessage });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to create user' });
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

// Delete User Function (FIXED)
async function deleteUser(userId, userName) {
    const result = await Swal.fire({
        title: 'Delete User?',
        html: `<div class="text-left">
            <p class="mb-4">Are you sure you want to delete <strong>"${userName}"</strong>?</p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    This will permanently delete all user data.
                </p>
            </div>
        </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait',
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
                    title: 'Deleted!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(() => window.location.reload(), 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Failed!', text: data.message || 'Failed to delete user' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error!', text: 'An error occurred' });
        }
    }
}

// Modal close handlers
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        hideEditModal();
        hideCreateModal();
    }
});

document.getElementById('editUserModal')?.addEventListener('click', function(e) {
    if (e.target === this) hideEditModal();
});

document.getElementById('createUserModal')?.addEventListener('click', function(e) {
    if (e.target === this) hideCreateModal();
});

// Update the "Add New User" button to open modal
document.addEventListener('DOMContentLoaded', function() {
    const addUserBtn = document.querySelector('a[href*="create"]');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showCreateModal();
        });
    }
    
    // Handle profile picture preview functions
    window.previewEditProfilePicture = function(input) {
        const preview = document.getElementById('editProfilePicturePreview');
        const previewImage = document.getElementById('editProfilePreviewImage');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    window.previewCreateProfilePicture = function(input) {
        const preview = document.getElementById('createProfilePicturePreview');
        const previewImage = document.getElementById('createProfilePreviewImage');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
});
</script>
@endpush