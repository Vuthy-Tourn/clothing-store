<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('admin.products.delete.title') }}</h3>
            <p class="text-gray-600 mb-6" id="deleteModalText">{{ __('admin.products.delete.message') }}</p>

            <form id="deleteForm" method="POST" class="flex justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="DeleteModal.close()"
                    class="px-6 py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">
                    {{ __('admin.products.delete.cancel') }}
                </button>
                <button type="submit"
                    class="bg-red-600 text-white hover:bg-red-700 px-6 py-3 rounded-lg font-medium flex items-center">
                    <i class="fas fa-trash mr-2"></i> {{ __('admin.products.delete.yes_delete') }}
                </button>
            </form>
        </div>
    </div>
</div>