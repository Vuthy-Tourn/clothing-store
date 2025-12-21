<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200/50">
    <!-- Profile Header -->
    <div class="relative h-32 bg-gradient-to-r from-Ocean to-Ocean/70">
        <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
            <!-- Profile Picture -->
            <div class="relative">
                <div
                    class="w-32 h-32 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-4xl font-bold text-gray-600">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="absolute bottom-3 right-3">
                    @if ($user->is_active)
                        <span class="w-6 h-6 bg-green-500 rounded-full border-3 border-white shadow-lg animate-pulse"
                            title="Active"></span>
                    @else
                        <span class="w-6 h-6 bg-red-500 rounded-full border-3 border-white shadow-lg"
                            title="Inactive"></span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Info -->
    <div class="pt-16 pb-6 px-6">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-gray-600 mt-1">{{ $user->email }}</p>
            <div
                class="inline-flex items-center px-3 py-1 mt-2 rounded-full bg-Ocean/10 text-Ocean text-sm font-medium">
                <i class="fas fa-user-shield mr-1"></i>
                {{ ucfirst($user->account_type) }}
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="text-center p-3 bg-gray-50 rounded-xl">
                <div class="text-2xl font-bold text-gray-900">
                    {{ (int) ($stats['account_age'] ?? 0) }}
                </div>

                <div class="text-xs text-gray-500">Days Active</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-xl">
                <div class="text-2xl font-bold text-gray-900">
                    {{ $user->loyalty_points ?? 0 }}
                </div>
                <div class="text-xs text-gray-500">Points</div>
            </div>
        </div>

        <!-- Profile Completeness -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-900">Profile Complete</span>
                <span class="text-sm font-bold text-Ocean">{{ $completeness['percentage'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-Ocean to-Ocean/70 h-2 rounded-full"
                    style="width: {{ $completeness['percentage'] }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                {{ $completeness['filled_fields'] }} of {{ $completeness['total_fields'] }} fields completed
            </p>
        </div>


    </div>
</div>

<!-- Quick Stats Card -->
<div class="mt-6 bg-white rounded-2xl shadow-xl p-6 border border-gray-200/50">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
        <i class="fas fa-chart-line text-Ocean mr-2"></i>
        Account Stats
    </h3>
    <div class="space-y-3">
        <div class="flex justify-between items-center">
            <span class="text-gray-600">User Role</span>
            <span class="font-semibold">{{ $stats['account_type'] }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Status</span>
            <span class="font-semibold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                {{ $stats['status'] }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Verified</span>
            <span class="font-semibold {{ $user->is_verified ? 'text-green-600' : 'text-yellow-600' }}">
                {{ $stats['verification'] }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Email Verified</span>
            <span class="font-semibold {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                {{ $stats['email_verified'] }}
            </span>
        </div>
    </div>
</div>
