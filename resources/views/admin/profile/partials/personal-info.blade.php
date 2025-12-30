<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200/50">
    <div class="px-6 py-5 border-b border-gray-200/50 bg-gradient-to-r from-gray-50 to-white">
        <h2 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-user-circle text-Ocean mr-3"></i>
            {{ __('admin.profile.personal_info.title') }}
        </h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.full_name') }}</label>
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-user text-Ocean mr-3"></i>
                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.email_address') }}</label>
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-envelope text-Ocean mr-3"></i>
                    <span class="font-medium text-gray-900">{{ $user->email }}</span>
                    @if ($user->email_verified_at)
                        <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                            {{ __('admin.profile.personal_info.verified') }}
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.phone_number') }}</label>
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-phone text-Ocean mr-3"></i>
                    <span class="font-medium text-gray-900">{{ $user->phone ?? __('admin.profile.personal_info.not_provided') }}</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.address') }}</label>
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-id-card text-Ocean mr-3"></i>
                    <span class="font-medium text-gray-900">
                        {{ optional($user->defaultAddress)->full_address ?? '' }}
                    </span>

                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.date_of_birth') }}</label>
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-birthday-cake text-Ocean mr-3"></i>
                    <span class="font-medium text-gray-900">
                        {{ $user->dob ? $user->dob->format('F d, Y') : __('admin.profile.personal_info.not_provided') }}
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.gender') }}</label>
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-venus-mars text-Ocean mr-3"></i>
                    <span class="font-medium text-gray-900">
                        {{ $user->gender ? ucfirst($user->gender) : __('admin.profile.personal_info.not_specified') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Address -->
        @if ($user->address)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.address') }}</label>
                <div class="flex items-start p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-map-marker-alt text-Ocean mr-3 mt-1"></i>
                    <span class="font-medium text-gray-900">{{ $user->address }}</span>
                </div>
            </div>
        @endif

        <!-- Member Since -->
        <div class="mt-6 pt-6 border-t border-gray-200/50">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.member_since') }}</label>
                    <p class="font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('admin.profile.personal_info.account_updated') }}</label>
                    <p class="font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>