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