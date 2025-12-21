@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
    <div class="container-fluid px-4 md:px-8 py-6">
        <!-- Header with Actions -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="text-gray-500 hover:text-Ocean p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">User Details</h1>
                        <p class="text-gray-600 mt-1">View and manage user information</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="editUser({{ $user->id }})"
                        class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-edit"></i>
                        <span class="font-medium">Edit User</span>
                    </button>
                    <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                        class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-trash"></i>
                        <span class="font-medium">Delete User</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- User Profile Header -->
        <div class="card p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
                <!-- Profile Picture -->
                <div class="relative">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                            class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg">
                    @else
                        <div
                            class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center border-4 border-white shadow-lg">
                            <i class="fas fa-user text-gray-500 text-4xl"></i>
                        </div>
                    @endif
                    <!-- Status Badge -->
                    <div class="absolute -bottom-2 -right-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <div class="flex items-center space-x-2 mt-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->account_type === 'admin'
                                    ? 'bg-purple-100 text-purple-800'
                                    : ($user->account_type === 'staff'
                                        ? 'bg-blue-100 text-blue-800'
                                        : ($user->account_type === 'vendor'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-gray-100 text-gray-800')) }}">
                                    <i
                                        class="fas fa-{{ $user->account_type === 'admin' ? 'crown' : ($user->account_type === 'staff' ? 'user-tie' : ($user->account_type === 'vendor' ? 'store' : 'user')) }} mr-1 text-xs"></i>
                                    {{ ucfirst($user->account_type) }}
                                </span>
                                @if ($user->email_verified_at)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Verified
                                    </span>
                                @endif
                                @if ($user->newsletter_opt_in)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-Ocean/10 text-Ocean">
                                        <i class="fas fa-newspaper mr-1"></i>Newsletter
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Loyalty Points -->
                        <div class="mt-4 md:mt-0">
                            <div
                                class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 p-4 rounded-xl">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-700">Loyalty Points</p>
                                        <p class="text-2xl font-bold text-gray-900">
                                            {{ number_format($user->loyalty_points) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-envelope text-gray-400"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Email</p>
                                <a href="mailto:{{ $user->email }}"
                                    class="text-gray-900 hover:text-Ocean">{{ $user->email }}</a>
                            </div>
                        </div>

                        @if ($user->phone)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-phone text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Phone</p>
                                    <a href="tel:{{ $user->phone }}"
                                        class="text-gray-900 hover:text-Ocean">{{ $user->phone }}</a>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-id-card text-gray-400"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Customer ID</p>
                                <p class="text-gray-900 font-mono">{{ $user->customer_id ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Member Since</p>
                                <p class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user-circle text-Ocean mr-2"></i>
                            Personal Information
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <div class="flex items-center space-x-2">
                                <i
                                    class="fas fa-{{ $user->gender == 'male' ? 'mars' : ($user->gender == 'female' ? 'venus' : 'genderless') }} text-gray-400"></i>
                                <p class="text-gray-900">{{ $user->gender ? ucfirst($user->gender) : 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-birthday-cake text-gray-400"></i>
                                <p class="text-gray-900">
                                    {{ $user->dob ? $user->dob->format('F d, Y') : 'Not specified' }}
                                    @if ($user->dob)
                                        <span class="text-sm text-gray-500">({{ $user->dob->age }} years old)</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Last Login -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Login</label>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-sign-in-alt text-gray-400"></i>
                                <p class="text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}
                                </p>
                            </div>
                            @if ($user->last_login_at)
                                <p class="text-sm text-gray-500 mt-1">{{ $user->last_login_at->diffForHumans() }}</p>
                            @endif
                        </div>

                        <!-- Account Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                            <div class="flex items-center space-x-2">
                                @if ($user->is_active)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-green-700 font-medium">Active</span>
                                @else
                                    <i class="fas fa-times-circle text-red-500"></i>
                                    <span class="text-red-700 font-medium">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map-marker-alt text-Ocean mr-2"></i>
                                Address Information
                            </h3>
                            @if ($uniqueAddresses->count() > 0)
                                <span class="text-sm text-gray-500">{{ $uniqueAddresses->count() }} address(es)</span>
                            @endif
                        </div>

                        {{-- @if ($allAddresses->count() > $uniqueAddresses->count())
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Show:</span>
                                <div class="flex items-center space-x-1">
                                    <button id="showUniqueBtn" class="px-3 py-1 text-sm bg-Ocean text-white rounded-lg">
                                        Unique Only
                                    </button>
                                    <button id="showAllBtn"
                                        class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                        All ({{ $allAddresses->count() }})
                                    </button>
                                </div>
                            </div>
                        @endif --}}
                    </div>

                    <!-- Unique Addresses (default view) -->
                    <div id="uniqueAddresses">
                        @if ($uniqueAddresses->count() > 0)
                            <div class="space-y-4">
                                @foreach ($uniqueAddresses as $address)
                                    <div
                                        class="border border-gray-200 rounded-xl p-4 hover:border-Ocean/30 transition-colors">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $address->type === 'shipping' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ ucfirst($address->type) }}
                                                </span>
                                                @if ($address->is_default)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($address->address_name)
                                                <span
                                                    class="text-sm font-medium text-gray-700">{{ $address->address_name }}</span>
                                            @endif
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-gray-900 font-medium">{{ $address->full_name }}</p>
                                            <p class="text-gray-600">{{ $address->phone }}</p>
                                            <p class="text-gray-600">{{ $address->address_line1 }}</p>
                                            @if ($address->address_line2)
                                                <p class="text-gray-600">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="text-gray-600">{{ $address->city }}, {{ $address->state }}
                                                {{ $address->zip_code }}</p>
                                            <p class="text-gray-600">{{ $address->country }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-3"></i>
                                <p class="text-gray-500">No addresses found</p>
                                <p class="text-gray-400 text-sm mt-1">This user hasn't added any addresses yet</p>
                            </div>
                        @endif
                    </div>

                    {{-- <!-- All Addresses (hidden by default) -->
                    <div id="allAddresses" class="hidden">
                        @if ($allAddresses->count() > 0)
                            <div class="space-y-4">
                                @foreach ($allAddresses as $address)
                                    <div
                                        class="border border-gray-200 rounded-xl p-4 hover:border-Ocean/30 transition-colors">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $address->type === 'shipping' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ ucfirst($address->type) }}
                                                </span>
                                                @if ($address->is_default)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($address->address_name)
                                                <span
                                                    class="text-sm font-medium text-gray-700">{{ $address->address_name }}</span>
                                            @endif
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-gray-900 font-medium">{{ $address->full_name }}</p>
                                            <p class="text-gray-600">{{ $address->phone }}</p>
                                            <p class="text-gray-600">{{ $address->address_line1 }}</p>
                                            @if ($address->address_line2)
                                                <p class="text-gray-600">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="text-gray-600">{{ $address->city }}, {{ $address->state }}
                                                {{ $address->zip_code }}</p>
                                            <p class="text-gray-600">{{ $address->country }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Account Statistics -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-6">
                        <i class="fas fa-chart-bar text-Ocean mr-2"></i>
                        Account Statistics
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $stats['orders_count'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Spent</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        ${{ number_format($stats['total_spent'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Saved Addresses</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $uniqueAddressCount }}
                                        {{-- @if ($totalAddressCount > $uniqueAddressCount)
                                            <span class="text-sm font-normal text-gray-500">/ {{ $totalAddressCount }}
                                                total</span>
                                        @endif --}}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Account Age</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $stats['account_age'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-6">
                        <i class="fas fa-cog text-Ocean mr-2"></i>
                        Account Settings
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                                {{ $user->is_verified ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas fa-{{ $user->is_verified ? 'shield-alt' : 'shield' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Account Verification</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->is_verified ? 'Verified' : 'Not Verified' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                                {{ $user->email_verified_at ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Email Verification</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                                {{ $user->newsletter_opt_in ? 'bg-Ocean/10 text-Ocean' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Newsletter Subscription</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->newsletter_opt_in ? 'Subscribed' : 'Not Subscribed' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-100 text-gray-500">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Last Updated</p>
                                    <p class="text-xs text-gray-500">{{ $user->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-6">
                        <i class="fas fa-bolt text-Ocean mr-2"></i>
                        Quick Actions
                    </h3>

                    <div class="space-y-3">
                        <button
                            onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-Ocean/30 hover:bg-Ocean/5 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-power-off text-{{ $user->is_active ? 'green' : 'red' }}-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} Account</p>
                                    <p class="text-xs text-gray-500">Toggle user access</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="editUser({{ $user->id }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-edit text-blue-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Edit Profile</p>
                                    <p class="text-xs text-gray-500">Update user information</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="resetPassword({{ $user->id }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-key text-yellow-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Reset Password</p>
                                    <p class="text-xs text-gray-500">Send password reset link</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="sendVerificationEmail({{ $user->id }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-purple-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Resend Verification</p>
                                    <p class="text-xs text-gray-500">Send email verification</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Edit Modal from index page -->
    @include('admin.users.edit-modal')

    @push('styles')
        <style>
            /* Add some custom styles for the show page */
            .card {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
            }

            .card:hover {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush

    @push('scripts')
       
        <script>
            // Edit User from show page
            function editUser(userId) {
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

                fetch(`{{ url('admin/users') }}/${userId}/edit-form`)
                    .then(response => response.text())
                    .then(html => {
                        editContent.innerHTML = html;
                        initEditForm();
                    })
                    .catch(error => {
                        editContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <p class="text-gray-600">Failed to load user data</p>
                    <button onclick="hideEditModal()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">
                        Close
                    </button>
                </div>
            `;
                    });
            }

            function initEditForm() {
                const form = document.getElementById('editUserForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(this);
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;

                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                        submitBtn.disabled = true;

                        fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(response => {
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
                                    return response.json().then(data => {
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
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to update user'
                                });
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                            });
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

            // Delete User from show page
            async function deleteUser(userId, userName) {
                const result = await Swal.fire({
                    title: 'Delete User?',
                    html: `<div class="text-left">
            <p class="mb-4">Are you sure you want to delete <strong>"${userName}"</strong>?</p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    This will permanently delete all user data including addresses and profile.
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
                            setTimeout(() => {
                                window.location.href = '{{ route('admin.users.index') }}';
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: data.message || 'Failed to delete user'
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
            }

            // Toggle User Status from show page
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
                            setTimeout(() => window.location.reload(), 1000);
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

            // Reset Password Function
            async function resetPassword(userId) {
                const result = await Swal.fire({
                    title: 'Reset Password?',
                    text: 'Send password reset link to this user?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send reset link',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading()
                    });

                    // Here you would implement password reset logic
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reset Link Sent!',
                            text: 'Password reset link has been sent to the user\'s email.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, 1500);
                }
            }

            // Send Verification Email
            async function sendVerificationEmail(userId) {
                const result = await Swal.fire({
                    title: 'Send Verification Email?',
                    text: 'Send email verification link to this user?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send verification',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading()
                    });

                    // Here you would implement email verification logic
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verification Sent!',
                            text: 'Email verification link has been sent.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, 1500);
                }
            }

            // SweetAlert2 Toast Configuration
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

            // Modal close handlers
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    hideEditModal();
                }
            });

            document.getElementById('editUserModal')?.addEventListener('click', function(e) {
                if (e.target === this) hideEditModal();
            });
        </script>
    @endpush
@endsection
