<!-- Activity Log Modal -->
<div id="activityModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div
        class="modal-content bg-white rounded-2xl shadow-lg w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[80vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Activity Log</h3>
                    <p class="text-gray-600 mt-1">Recent account activities</p>
                </div>
                <button onclick="hideActivityModal()"
                    class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="space-y-4">
                @foreach ($activities as $activity)
                    <div
                        class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full {{ getActivityColor($activity['action']) }} flex items-center justify-center">
                            <i class="{{ getActivityIcon($activity['action']) }} text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900">{{ $activity['action'] }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span>{{ $activity['time'] }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-network-wired mr-1"></i>
                                <span>{{ $activity['ip'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (empty($activities))
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Activity Yet</h3>
                        <p class="text-gray-600">Your activities will appear here</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Activities are logged for security purposes
                </p>
                <button onclick="hideActivityModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-xl transition-colors duration-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showActivityModal() {
        const modal = document.getElementById('activityModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('.modal-content').classList.add('modal-enter'), 10);
    }

    function hideActivityModal() {
        const modal = document.getElementById('activityModal');
        modal.querySelector('.modal-content').classList.remove('modal-enter');
        modal.querySelector('.modal-content').classList.add('modal-exit');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
    // Helper functions for activity modal (add to controller or blade)
    @php
        function getActivityColor($action)
        {
            $colors = [
                'Profile Updated' => 'bg-blue-500',
                'Password Changed' => 'bg-green-500',
                'Login' => 'bg-purple-500',
                'Dashboard Access' => 'bg-orange-500',
                'Newsletter Update' => 'bg-teal-500',
                'System Activity' => 'bg-gray-500',
            ];
            return $colors[$action] ?? 'bg-Ocean';
        }

        function getActivityIcon($action)
        {
            $icons = [
                'Profile Updated' => 'fas fa-user-edit',
                'Password Changed' => 'fas fa-key',
                'Login' => 'fas fa-sign-in-alt',
                'Dashboard Access' => 'fas fa-chart-pie',
                'Newsletter Update' => 'fas fa-envelope',
                'System Activity' => 'fas fa-cog',
            ];
            return $icons[$action] ?? 'fas fa-info-circle';
        }
    @endphp
</script>
