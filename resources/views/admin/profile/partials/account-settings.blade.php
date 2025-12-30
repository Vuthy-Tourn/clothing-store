<div class="bg-white rounded-2xl shadow-lg border border-gray-200/50"> <!-- Removed overflow-hidden -->
    <div class="px-6 py-5 border-b border-gray-200/50 bg-gradient-to-r from-gray-50 to-white">
        <h2 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-cog text-Ocean mr-3"></i>
            {{ __('admin.profile.account_settings.title') }}
        </h2>
    </div>
    <div class="p-6">
        <div class="space-y-6">
            <!-- Language Preference -->
            <div class="relative">
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100/50 rounded-xl border border-green-200/50">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ __('admin.profile.account_settings.language_preference') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('admin.profile.account_settings.language_subtitle') }}</p>
                        </div>
                    </div>
                    <div class="ml-4 relative">
                       
                                              @include('components.language-switcher')

                        
                    </div>
                </div>
            </div>
            <!-- Newsletter Subscription -->
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100/50 rounded-xl border border-green-200/50">
                <div>
                    <h4 class="font-semibold text-gray-900">{{ __('admin.profile.account_settings.newsletter_subscription') }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ __('admin.profile.account_settings.newsletter_subtitle') }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="newsletterToggle" class="sr-only peer"
                        {{ $user->newsletter_opt_in ? 'checked' : '' }} onchange="toggleNewsletter(this)">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                    </div>
                </label>
            </div>
            <!-- Password Security -->
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50">
                <div>
                    <h4 class="font-semibold text-gray-900">{{ __('admin.profile.account_settings.password_security') }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ __('admin.profile.account_settings.last_changed', ['time' => $user->updated_at->diffForHumans()]) }}</p>
                </div>
                <button onclick="showPasswordModal()"
                    class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-lg hover:shadow-lg transition-all duration-300">
                    {{ __('admin.profile.account_settings.change_password') }}
                </button>
            </div>

            

            
        </div>
    </div>
</div>


<script>
    // Newsletter toggle function with detailed debugging
function toggleNewsletter(checkbox) {
    const isChecked = checkbox.checked;
    
    console.log('Toggle initiated:', {
        isChecked: isChecked,
        csrfToken: '{{ csrf_token() }}',
        route: '{{ route('admin.profile.toggle-newsletter') }}'
    });

    fetch('{{ route('admin.profile.toggle-newsletter') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            newsletter_opt_in: isChecked ? 1 : 0
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        // Clone response to read it twice
        return response.clone().text().then(text => {
            console.log('Raw response:', text);
            try {
                const json = JSON.parse(text);
                return {
                    ok: response.ok,
                    status: response.status,
                    data: json
                };
            } catch (e) {
                console.error('JSON parse error:', e);
                return {
                    ok: false,
                    status: response.status,
                    data: { error: 'Invalid JSON response', raw: text }
                };
            }
        });
    })
    .then(({ok, status, data}) => {
        console.log('Parsed response:', data);
        
        if (!ok) {
            console.error('Request failed:', {
                status: status,
                errors: data.errors,
                message: data.message
            });
            
            // Show specific validation errors if available
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    console.error(`Validation error for ${field}:`, data.errors[field]);
                });
            }
            
            showToast(data.message || 'Failed to update newsletter preference', 'error');
            checkbox.checked = !isChecked;
            return;
        }
        
        if (data.success) {
            showToast(
                isChecked ? 'Subscribed to newsletter!' : 'Unsubscribed from newsletter',
                'success'
            );
        } else {
            showToast('Failed to update newsletter preference', 'error');
            checkbox.checked = !isChecked;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('An error occurred: ' + error.message, 'error');
        checkbox.checked = !isChecked;
    });
}
</script>
