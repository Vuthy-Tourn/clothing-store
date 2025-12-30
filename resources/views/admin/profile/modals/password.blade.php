<!-- Password Change Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div
        class="modal-content bg-white rounded-2xl shadow-lg w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ __('admin.profile.password_modal.title') }}</h3>
                    <p class="text-gray-600 mt-1">{{ __('admin.profile.password_modal.subtitle') }}</p>
                </div>
                <button onclick="hidePasswordModal()"
                    class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="passwordUpdateForm" method="POST" class="p-6">
            @csrf

            <div class="space-y-4">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('admin.profile.password_modal.current_password') }} <span class="text-red-500">{{ __('admin.profile.password_modal.required') }}</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                        <button type="button" onclick="togglePassword(this)"
                            class="absolute right-3 top-3 text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('admin.profile.password_modal.new_password') }} <span class="text-red-500">{{ __('admin.profile.password_modal.required') }}</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                        <button type="button" onclick="togglePassword(this)"
                            class="absolute right-3 top-3 text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('admin.profile.password_modal.password_hint') }}</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('admin.profile.password_modal.confirm_password') }} <span class="text-red-500">{{ __('admin.profile.password_modal.required') }}</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                        <button type="button" onclick="togglePassword(this)"
                            class="absolute right-3 top-3 text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="hidePasswordModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
                    {{ __('admin.profile.password_modal.cancel') }}
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    {{ __('admin.profile.password_modal.update_password') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showPasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('.modal-content').classList.add('modal-enter'), 10);
    }

    function hidePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.querySelector('.modal-content').classList.remove('modal-enter');
        modal.querySelector('.modal-content').classList.add('modal-exit');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

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
</script>
