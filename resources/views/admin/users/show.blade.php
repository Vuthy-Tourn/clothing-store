@extends('admin.layouts.app')

@section('title', __('admin.users.show.title') . ' - ' . $user->name)

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
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('admin.users.show.title') }}</h1>
                        <p class="text-gray-600 mt-1">{{ __('admin.users.show.subtitle') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="editUser({{ $user->id }})"
                        class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-edit"></i>
                        <span class="font-medium">{{ __('admin.users.show.edit_user') }}</span>
                    </button>
                    <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                        class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-trash"></i>
                        <span class="font-medium">{{ __('admin.users.show.delete_user') }}</span>
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
                            {{ $user->is_active ? __('admin.users.show.profile_header.status_active') : __('admin.users.show.profile_header.status_inactive') }}
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
                                        <i class="fas fa-check-circle mr-1"></i>{{ __('admin.users.show.profile_header.verified') }}
                                    </span>
                                @endif
                                @if ($user->newsletter_opt_in)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-Ocean/10 text-Ocean">
                                        <i class="fas fa-newspaper mr-1"></i>{{ __('admin.users.show.profile_header.newsletter') }}
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
                                        <p class="text-sm font-medium text-yellow-700">{{ __('admin.users.show.profile_header.loyalty_points') }}</p>
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
                                <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.contact_info.email') }}</p>
                                <a href="mailto:{{ $user->email }}"
                                    class="text-gray-900 hover:text-Ocean">{{ $user->email }}</a>
                            </div>
                        </div>

                        @if ($user->phone)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-phone text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.contact_info.phone') }}</p>
                                    <a href="tel:{{ $user->phone }}"
                                        class="text-gray-900 hover:text-Ocean">{{ $user->phone }}</a>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-id-card text-gray-400"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.contact_info.customer_id') }}</p>
                                <p class="text-gray-900 font-mono">{{ $user->customer_id ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.contact_info.member_since') }}</p>
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
                            {{ __('admin.users.show.personal_info.title') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.show.personal_info.gender') }}</label>
                            <div class="flex items-center space-x-2">
                                <i
                                    class="fas fa-{{ $user->gender == 'male' ? 'mars' : ($user->gender == 'female' ? 'venus' : 'genderless') }} text-gray-400"></i>
                                <p class="text-gray-900">
                                    @if ($user->gender == 'male')
                                        {{ __('admin.users.show.personal_info.male') }}
                                    @elseif ($user->gender == 'female')
                                        {{ __('admin.users.show.personal_info.female') }}
                                    @elseif ($user->gender == 'other')
                                        {{ __('admin.users.show.personal_info.other') }}
                                    @else
                                        {{ __('admin.users.show.personal_info.not_specified') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.show.personal_info.date_of_birth') }}</label>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-birthday-cake text-gray-400"></i>
                                <p class="text-gray-900">
                                    {{ $user->dob ? $user->dob->format('F d, Y') : __('admin.users.show.personal_info.not_specified') }}
                                    @if ($user->dob)
                                        <span class="text-sm text-gray-500">
                                            ({{ __('admin.users.show.personal_info.years_old', ['age' => $user->dob->age]) }})
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Last Login -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.show.personal_info.last_login') }}</label>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-sign-in-alt text-gray-400"></i>
                                <p class="text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : __('admin.users.show.personal_info.never') }}
                                </p>
                            </div>
                            @if ($user->last_login_at)
                                <p class="text-sm text-gray-500 mt-1">{{ $user->last_login_at->diffForHumans() }}</p>
                            @endif
                        </div>

                        <!-- Account Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.users.show.personal_info.account_status') }}</label>
                            <div class="flex items-center space-x-2">
                                @if ($user->is_active)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span class="text-green-700 font-medium">{{ __('admin.users.table.active') }}</span>
                                @else
                                    <i class="fas fa-times-circle text-red-500"></i>
                                    <span class="text-red-700 font-medium">{{ __('admin.users.table.inactive') }}</span>
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
                                {{ __('admin.users.show.address_info.title') }}
                            </h3>
                            @if ($uniqueAddresses->count() > 0)
                                <span class="text-sm text-gray-500">
                                    {{ __('admin.users.show.address_info.addresses_count', ['count' => $uniqueAddresses->count()]) }}
                                </span>
                            @endif
                        </div>
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
                                                    @if ($address->type === 'shipping')
                                                        {{ __('admin.users.show.address_info.shipping') }}
                                                    @else
                                                        {{ __('admin.users.show.address_info.billing') }}
                                                    @endif
                                                </span>
                                                @if ($address->is_default)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ __('admin.users.show.address_info.default') }}
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
                                <p class="text-gray-500">{{ __('admin.users.show.address_info.no_addresses') }}</p>
                                <p class="text-gray-400 text-sm mt-1">{{ __('admin.users.show.address_info.no_addresses_message') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Account Statistics -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-6">
                        <i class="fas fa-chart-bar text-Ocean mr-2"></i>
                        {{ __('admin.users.show.statistics.title') }}
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.statistics.total_orders') }}</p>
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
                                    <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.statistics.total_spent') }}</p>
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
                                    <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.statistics.saved_addresses') }}</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $uniqueAddressCount }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">{{ __('admin.users.show.statistics.account_age') }}</p>
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
                        {{ __('admin.users.show.settings.title') }}
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
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.settings.account_verification') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->is_verified ? __('admin.users.show.settings.verified') : __('admin.users.show.settings.not_verified') }}
                                    </p>
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
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.settings.email_verification') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->email_verified_at ? __('admin.users.show.settings.verified') : __('admin.users.show.settings.not_verified') }}
                                    </p>
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
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.settings.newsletter_subscription') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->newsletter_opt_in ? __('admin.users.show.settings.subscribed') : __('admin.users.show.settings.not_subscribed') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-100 text-gray-500">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.settings.last_updated') }}</p>
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
                        {{ __('admin.users.show.quick_actions.title') }}
                    </h3>

                    <div class="space-y-3">
                        <button
                            onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-Ocean/30 hover:bg-Ocean/5 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-power-off text-{{ $user->is_active ? 'green' : 'red' }}-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $user->is_active ? __('admin.users.show.quick_actions.deactivate_account') : __('admin.users.show.quick_actions.activate_account') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ __('admin.users.show.quick_actions.toggle_access') }}</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="editUser({{ $user->id }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-edit text-blue-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.quick_actions.edit_profile') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('admin.users.show.quick_actions.update_info') }}</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="resetPassword({{ $user->id }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-key text-yellow-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.quick_actions.reset_password') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('admin.users.show.quick_actions.send_reset_link') }}</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="sendVerificationEmail({{ $user->id }})"
                            class="w-full flex items-center justify-between p-3 text-left rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-purple-500"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ __('admin.users.show.quick_actions.resend_verification') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('admin.users.show.quick_actions.send_verification') }}</p>
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
            <p class="mt-4 text-gray-600">{{ __('admin.users.modal.loading') }}</p>
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
                    <p class="text-gray-600">{{ __('admin.users.modal.failed_load') }}</p>
                    <button onclick="hideEditModal()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">
                        {{ __('admin.users.modal.close') }}
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

                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("admin.users.messages.saving") }}';
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
                                        title: '{{ __("admin.users.messages.success_updated") }}'
                                    });
                                    setTimeout(() => {
                                        hideEditModal();
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    return response.json().then(data => {
                                        let errorMessage = '{{ __("admin.users.messages.error_fix_errors") }}';
                                        if (data.errors) {
                                            errorMessage = Object.values(data.errors).flat().join('<br>');
                                        } else if (data.message) {
                                            errorMessage = data.message;
                                        }
                                        Swal.fire({
                                            icon: 'error',
                                            title: '{{ __("admin.users.messages.error_failed") }}',
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
                                    title: '{{ __("admin.users.messages.error_occurred") }}',
                                    text: '{{ __("admin.users.messages.error_failed_message") }}'
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
                    title: '{{ __("admin.users.delete.title") }}',
                    html: `<div class="text-left">
            <p class="mb-4">{{ __("admin.users.delete.message", ['name' => '"${userName}"']) }}</p>
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
                            setTimeout(() => {
                                window.location.href = '{{ route('admin.users.index') }}';
                            }, 1500);
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

            // Toggle User Status from show page
            async function toggleUserStatus(userId, newStatus) {
                const statusText = newStatus ? 'activate' : 'deactivate';
                const title = newStatus ? 
                    '{{ __("admin.users.toggle_status.activate_title") }}' : 
                    '{{ __("admin.users.toggle_status.deactivate_title") }}';
                const message = newStatus ? 
                    '{{ __("admin.users.toggle_status.activate_message") }}' : 
                    '{{ __("admin.users.toggle_status.deactivate_message") }}';
                const confirmText = newStatus ? 
                    '{{ __("admin.users.toggle_status.yes_activate") }}' : 
                    '{{ __("admin.users.toggle_status.yes_deactivate") }}';

                const result = await Swal.fire({
                    title: title,
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmText,
                    cancelButtonText: '{{ __("admin.users.toggle_status.cancel") }}'
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
                                title: data.message || '{{ __("admin.users.messages.success_status_changed") }}'
                            });
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message || '{{ __("admin.users.messages.error_occurred") }}'
                            });
                        }
                    } catch (error) {
                        Toast.fire({
                            icon: 'error',
                            title: '{{ __("admin.users.messages.error_occurred") }}'
                        });
                    }
                }
            }

            // Reset Password Function
            async function resetPassword(userId) {
                const result = await Swal.fire({
                    title: '{{ __("admin.users.reset_password.title") }}',
                    text: '{{ __("admin.users.reset_password.message") }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("admin.users.reset_password.yes_send") }}',
                    cancelButtonText: '{{ __("admin.users.reset_password.cancel") }}'
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        title: '{{ __("admin.users.reset_password.sending") }}',
                        text: '{{ __("admin.users.messages.please_wait") }}',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading()
                    });

                    try {
                        const response = await fetch(`{{ url('admin/users/reset-password') }}/${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("admin.users.messages.success_reset_sent") }}',
                                text: data.message || '{{ __("admin.users.messages.success_reset_message") }}',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("admin.users.messages.error_failed") }}',
                                text: data.message || '{{ __("admin.users.messages.error_occurred") }}'
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

            // Send Verification Email
            async function sendVerificationEmail(userId) {
                const result = await Swal.fire({
                    title: '{{ __("admin.users.verification.title") }}',
                    text: '{{ __("admin.users.verification.message") }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("admin.users.verification.yes_send") }}',
                    cancelButtonText: '{{ __("admin.users.verification.cancel") }}'
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        title: '{{ __("admin.users.verification.sending") }}',
                        text: '{{ __("admin.users.messages.please_wait") }}',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => Swal.showLoading()
                    });

                    try {
                        const response = await fetch(`{{ url('admin/users/resend-verification') }}/${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("admin.users.messages.success_verification_sent") }}',
                                text: data.message || '{{ __("admin.users.messages.success_verification_message") }}',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("admin.users.messages.error_failed") }}',
                                text: data.message || '{{ __("admin.users.messages.error_occurred") }}'
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