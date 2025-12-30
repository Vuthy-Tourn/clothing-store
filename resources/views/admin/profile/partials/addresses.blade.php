<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200/50 mt-8">
    <div class="px-6 py-5 border-b border-gray-200/50 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-map-marker-alt text-Ocean mr-3"></i>
                {{ __('admin.profile.addresses.title') }}
            </h2>
            <button onclick="showAddressModal()"
                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white text-sm rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-1"></i>
                {{ __('admin.profile.addresses.add_address') }}
            </button>
        </div>
    </div>
    <div class="p-6">
        @if ($addresses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($addresses as $address)
                    <div
                        class="border border-gray-200 rounded-xl p-4 hover:border-Ocean/30 hover:shadow-md transition-all duration-300 {{ $address->is_default ? 'ring-2 ring-Ocean/20' : '' }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span
                                    class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $address->type == 'shipping' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $address->type == 'shipping' ? __('admin.profile.addresses.shipping') : __('admin.profile.addresses.billing') }}
                                </span>
                                @if ($address->is_default)
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-2">
                                        {{ __('admin.profile.addresses.default') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editAddress({{ $address->id }})"
                                    class="text-gray-500 hover:text-Ocean transition-colors duration-300 p-1"
                                    title="{{ __('admin.profile.addresses.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteAddress({{ $address->id }})"
                                    class="text-gray-500 hover:text-red-600 transition-colors duration-300 p-1"
                                    title="{{ __('admin.profile.addresses.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        @if ($address->address_name)
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $address->address_name }}</h4>
                        @endif

                        <p class="text-gray-700 mb-1">{{ $address->full_name }}</p>
                        <p class="text-gray-700 mb-1">{{ $address->phone }}</p>
                        <p class="text-gray-700 mb-1">{{ $address->address_line1 }}</p>
                        @if ($address->address_line2)
                            <p class="text-gray-700 mb-1">{{ $address->address_line2 }}</p>
                        @endif
                        <p class="text-gray-700">{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}
                        </p>
                        <p class="text-gray-600 text-sm mt-2">{{ $address->country }}</p>

                        @if (!$address->is_default)
                            <button onclick="setDefaultAddress({{ $address->id }})"
                                class="mt-4 text-sm text-Ocean hover:text-Ocean/80 transition-colors duration-300">
                                {{ __('admin.profile.addresses.set_as_default') }}
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('admin.profile.addresses.no_addresses') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('admin.profile.addresses.no_addresses_desc') }}</p>
                <button onclick="showAddressModal()"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('admin.profile.addresses.add_first_address') }}
                </button>
            </div>
        @endif
    </div>
</div>