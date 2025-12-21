<!-- Security Settings Modal -->
<div id="securityModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div
        class="modal-content bg-white rounded-2xl shadow-lg w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Security Settings</h3>
                    <p class="text-gray-600 mt-1">Manage your account security</p>
                </div>
                <button onclick="hideSecurityModal()"
                    class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="securitySettingsForm" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Two-Factor Authentication -->
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-gray-900">Two-Factor Authentication</h4>
                        <p class="text-sm text-gray-600 mt-1">Add an extra layer of security</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="two_factor_enabled" class="sr-only peer"
                            {{ $securitySettings['two_factor_enabled'] ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                        </div>
                    </label>
                </div>

                <!-- Login Notifications -->
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-gray-900">Login Notifications</h4>
                        <p class="text-sm text-gray-600 mt-1">Get alerts for new logins</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="login_notifications" class="sr-only peer"
                            {{ $securitySettings['login_notifications'] ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                        </div>
                    </label>
                </div>

                <!-- Session Timeout -->
                <div>
                    <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">
                        Session Timeout (minutes)
                    </label>
                    <div class="relative">
                        <i class="fas fa-clock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select id="session_timeout" name="session_timeout"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-Ocean focus:border-transparent transition-all duration-300">
                            <option value="15" {{ $securitySettings['session_timeout'] == 15 ? 'selected' : '' }}>15
                                minutes</option>
                            <option value="30" {{ $securitySettings['session_timeout'] == 30 ? 'selected' : '' }}>30
                                minutes (Default)</option>
                            <option value="60" {{ $securitySettings['session_timeout'] == 60 ? 'selected' : '' }}>1
                                hour</option>
                            <option value="120" {{ $securitySettings['session_timeout'] == 120 ? 'selected' : '' }}>2
                                hours</option>
                            <option value="480" {{ $securitySettings['session_timeout'] == 480 ? 'selected' : '' }}>8
                                hours</option>
                        </select>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Automatically log out after inactivity</p>
                </div>

                <!-- Last Password Change -->
                <div class="p-4 bg-gray-50 rounded-xl">
                    <h4 class="font-semibold text-gray-900 mb-2">Password Information</h4>
                    <p class="text-sm text-gray-600">Last changed: <span
                            class="font-medium">{{ $securitySettings['last_password_change'] }}</span></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="hideSecurityModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showSecurityModal() {
        const modal = document.getElementById('securityModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('.modal-content').classList.add('modal-enter'), 10);
    }

    function hideSecurityModal() {
        const modal = document.getElementById('securityModal');
        modal.querySelector('.modal-content').classList.remove('modal-enter');
        modal.querySelector('.modal-content').classList.add('modal-exit');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>
