@extends('admin.layouts.app')

@section('content')
    <!-- New Arrivals Section -->
    <section class="mt-8" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">New Arrivals</h1>
                <p class="text-gray-700 text-lg">Curate the latest fashion pieces that customers will love</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-sm text-gray-700 mb-1">Total Items</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $arrivals->count() }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-700 mb-1">Active</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $arrivals->where('status', 'active')->count() }}
                        </div>
                    </div>
                </div>
                <button onclick="NewArrivalModal.openAdd()"
                    class="bg-gray-900 text-white hover:bg-gray-800 transition-all duration-200 px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl hover:scale-105 group"
                    data-aos="zoom-in" data-aos-delay="100">
                    <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i> Add New
                    Arrival
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-white border border-gray-200 rounded-xl p-4 shadow-sm" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gray-900 flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Success</p>
                            <p class="text-gray-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if ($arrivals->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-aos="fade-up"
                data-aos-delay="150">
                @foreach ($arrivals as $arrival)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 group hover:-translate-y-1"
                        data-aos="zoom-in-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}">
                        <!-- Product Image with Aspect Ratio -->
                        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100">
                            <div class="pt-[100%] relative overflow-hidden">
                                <img src="{{ asset($arrival->image) }}" alt="{{ $arrival->name }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4 z-10">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg {{ $arrival->status === 'active' ? 'bg-gray-900 text-white' : 'bg-gray-700 text-white' }}">
                                    <i
                                        class="fas fa-circle text-[6px] mr-1.5 {{ $arrival->status === 'active' ? 'animate-pulse' : '' }}"></i>
                                    {{ ucfirst($arrival->status) }}
                                </span>
                            </div>

                            <!-- Quick Actions Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                <div
                                    class="flex space-x-2 transform translate-y-6 group-hover:translate-y-0 transition-transform duration-300">
                                    <button onclick="NewArrivalModal.openEdit('{{ $arrival->id }}')"
                                        class="bg-white text-gray-900 p-3 rounded-xl shadow-xl hover:shadow-lg hover:scale-110 transition-all duration-200 hover:bg-gray-50 group/btn"
                                        title="Edit Product">
                                        <i
                                            class="fas fa-edit text-sm group-hover/btn:rotate-12 transition-transform duration-300"></i>
                                    </button>
                                    <button
                                        onclick="confirmDelete({{ $arrival->id }}, '{{ addslashes($arrival->name) }}')"
                                        class="bg-white text-gray-900 p-3 rounded-xl shadow-xl hover:shadow-lg hover:scale-110 transition-all duration-200 hover:bg-gray-900 hover:text-white group/btn"
                                        title="Delete Product">
                                        <i
                                            class="fas fa-trash text-sm group-hover/btn:shake transition-transform duration-300"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-5">
                            <h3
                                class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-gray-700 transition-colors">
                                {{ $arrival->name }}
                            </h3>
                            <p class="text-gray-700 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ $arrival->description }}
                            </p>

                            <!-- Price and Date -->
                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <div>
                                    <span class="bg-gray-900 text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-sm">
                                        ${{ number_format($arrival->price, 2) }}
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-700 text-xs">
                                    <i class="far fa-clock mr-1.5"></i>
                                    <span>{{ $arrival->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination or View All -->
            <div class="mt-8 text-center" data-aos="fade-up">
                <button class="text-gray-700 hover:text-gray-900 font-medium text-sm hover:underline transition-colors">
                    View All New Arrivals →
                </button>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl p-16 text-center transition-all hover:border-gray-300"
                data-aos="fade-in" data-aos-delay="200">
                <div
                    class="w-24 h-24 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border-4 border-gray-100">
                    <i class="fas fa-box-open text-gray-300 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No New Arrivals Yet</h3>
                <p class="text-gray-700 text-base mb-8 max-w-md mx-auto">
                    Showcase your latest fashion pieces. Add new arrivals to attract customers with fresh styles.
                </p>
                <button onclick="NewArrivalModal.openAdd()"
                    class="bg-gray-900 text-white px-8 py-3.5 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200 group">
                    <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                    Add First Arrival
                </button>
            </div>
        @endif
    </section>

    <!-- Add New Arrival Modal -->
    <div id="addNewArrivalModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-3xl w-full max-w-4xl shadow-lg border border-gray-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Add New Arrival</h2>
                        <p class="text-gray-700">Add a fresh fashion piece to your collection</p>
                    </div>
                </div>
                <button onclick="NewArrivalModal.closeAdd()"
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                    <i
                        class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                <form action="{{ route('admin.new-arrivals.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-gray-700"></i>
                                    Product Name *
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200 placeholder-gray-500"
                                    placeholder="e.g., Premium Leather Jacket">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-dollar-sign mr-2 text-gray-700"></i>
                                    Price ($) *
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700 font-medium">$</span>
                                    <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                                        required
                                        class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl pl-12 pr-4 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200"
                                        placeholder="0.00">
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-gray-700"></i>
                                    Status
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="active" checked
                                            class="sr-only peer">
                                        <div
                                            class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-check-circle text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Active</p>
                                            <p class="text-gray-700 text-sm">Visible to customers</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="inactive" class="sr-only peer">
                                        <div
                                            class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-eye-slash text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Inactive</p>
                                            <p class="text-gray-700 text-sm">Hidden from customers</p>
                                        </div>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-gray-700"></i>
                                    Description *
                                </label>
                                <textarea name="description" rows="5" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 resize-none transition-all duration-200 placeholder-gray-500"
                                    placeholder="Describe this fashion piece...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-image mr-2 text-gray-700"></i>
                                    Product Image *
                                </label>
                                <div class="relative">
                                    <input type="file" name="image" accept="image/*" required id="addImageInput"
                                        class="hidden" onchange="previewImage(this, 'addImagePreview')">
                                    <label for="addImageInput"
                                        class="block border-4 border-dashed border-gray-200 hover:border-gray-900 rounded-2xl p-12 text-center cursor-pointer transition-all duration-200 group/upload">
                                        <div
                                            class="w-16 h-16 rounded-full bg-gray-100 group-hover/upload:bg-gray-900 flex items-center justify-center mx-auto mb-4 transition-all duration-200">
                                            <i
                                                class="fas fa-cloud-upload-alt text-gray-700 group-hover/upload:text-white text-2xl transition-all duration-200"></i>
                                        </div>
                                        <p class="text-gray-900 font-medium mb-2">Upload Product Image</p>
                                        <p class="text-gray-700 text-sm">Recommended: Square image • Max 5MB</p>
                                        <p class="text-gray-700 text-sm mt-1">JPG, PNG, WebP formats</p>
                                    </label>
                                </div>
                                <div id="addImagePreview" class="mt-4 hidden">
                                    <div class="relative rounded-xl overflow-hidden border-4 border-gray-100">
                                        <img id="addPreviewImage" class="w-full h-48 object-cover">
                                        <button type="button" onclick="removePreview('addImagePreview', 'addImageInput')"
                                            class="absolute top-3 right-3 w-8 h-8 bg-white hover:bg-gray-900 hover:text-white rounded-full flex items-center justify-center transition-all duration-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="text-gray-700 text-xs text-center mt-2">Image Preview</p>
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                        <button type="button" onclick="NewArrivalModal.closeAdd()"
                            class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-semibold transition-all duration-200 flex items-center group/submit shadow-lg hover:shadow-xl">
                            <i
                                class="fas fa-plus-circle mr-3 group-hover/submit:rotate-90 transition-transform duration-300"></i>
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit New Arrival Modal -->
    <div id="editNewArrivalModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-3xl w-full max-w-4xl shadow-lg border border-gray-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center mr-4">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Edit Product</h2>
                        <p class="text-gray-700">Update product details</p>
                    </div>
                </div>
                <button onclick="NewArrivalModal.closeEdit()"
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                    <i
                        class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                <form id="editArrivalForm" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-gray-700"></i>
                                    Product Name *
                                </label>
                                <input type="text" name="name" id="edit_name" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-dollar-sign mr-2 text-gray-700"></i>
                                    Price ($) *
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-700 font-medium">$</span>
                                    <input type="number" step="0.01" name="price" id="edit_price" required
                                        class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl pl-12 pr-4 py-4 focus:border-gray-900 focus:ring-0 transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-gray-700"></i>
                                    Status
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" id="edit_status_active" value="active"
                                            class="sr-only peer">
                                        <div
                                            class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-check-circle text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Active</p>
                                            <p class="text-gray-700 text-sm">Visible to customers</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" id="edit_status_inactive" value="inactive"
                                            class="sr-only peer">
                                        <div
                                            class="p-4 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-900 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50">
                                            <i class="fas fa-eye-slash text-gray-700 mb-2"></i>
                                            <p class="font-medium text-gray-900">Inactive</p>
                                            <p class="text-gray-700 text-sm">Hidden from customers</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-gray-700"></i>
                                    Description *
                                </label>
                                <textarea name="description" id="edit_description" rows="5" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-gray-900 focus:ring-0 resize-none transition-all duration-200"></textarea>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-image mr-2 text-gray-700"></i>
                                    Product Image
                                </label>
                                <div class="space-y-4">
                                    <!-- Current Image -->
                                    <div
                                        class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4">
                                        <div class="relative w-full pt-[100%] rounded-lg overflow-hidden">
                                            <img id="edit_image_preview" src=""
                                                class="absolute inset-0 w-full h-full object-cover">
                                        </div>
                                        <p class="text-gray-700 text-xs text-center mt-3">Current Image</p>
                                    </div>

                                    <!-- Change Image -->
                                    <div class="relative">
                                        <input type="file" name="image" accept="image/*" id="editImageInput"
                                            class="hidden" onchange="previewImage(this, 'editImagePreview')">
                                        <label for="editImageInput"
                                            class="block border-2 border-dashed border-gray-200 hover:border-gray-900 rounded-xl p-6 text-center cursor-pointer transition-all duration-200 group/change">
                                            <div
                                                class="w-12 h-12 rounded-full bg-gray-100 group-hover/change:bg-gray-900 flex items-center justify-center mx-auto mb-3 transition-all duration-200">
                                                <i
                                                    class="fas fa-sync-alt text-gray-700 group-hover/change:text-white transition-all duration-200"></i>
                                            </div>
                                            <p class="text-gray-900 font-medium mb-1">Change Image</p>
                                            <p class="text-gray-700 text-sm">Optional: Upload new image</p>
                                        </label>
                                    </div>
                                    <div id="editImagePreview" class="hidden">
                                        <div class="relative rounded-xl overflow-hidden border-4 border-gray-100 mt-4">
                                            <img id="editPreviewImage" class="w-full h-48 object-cover">
                                            <button type="button"
                                                onclick="removePreview('editImagePreview', 'editImageInput')"
                                                class="absolute top-3 right-3 w-8 h-8 bg-white hover:bg-gray-900 hover:text-white rounded-full flex items-center justify-center transition-all duration-200">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <p class="text-gray-700 text-xs text-center mt-2">New Image Preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                        <button type="button" onclick="NewArrivalModal.closeEdit()"
                            class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-4 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-semibold transition-all duration-200 flex items-center group/update shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-3 group-hover/update:rotate-12 transition-transform duration-300"></i>
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar hidden */
        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }

        .overflow-y-auto {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom animations */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-2px);
            }

            75% {
                transform: translateX(2px);
            }
        }

        .group-hover\/btn\:shake:hover i {
            animation: shake 0.5s ease-in-out;
        }

        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        /* Smooth transitions */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Image preview function
            function previewImage(input, previewContainerId) {
                const previewContainer = document.getElementById(previewContainerId);
                const previewImage = previewContainer.querySelector('img');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Remove preview function
            function removePreview(previewContainerId, inputId) {
                const previewContainer = document.getElementById(previewContainerId);
                const input = document.getElementById(inputId);

                previewContainer.classList.add('hidden');
                input.value = '';
            }

            // SweetAlert2 Delete Confirmation
            function confirmDelete(id, name) {
                Swal.fire({
                    title: 'Delete Product?',
                    html: `<div class="text-left">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash text-gray-700 text-2xl"></i>
                    </div>
                    <p class="text-gray-900 font-semibold text-lg mb-2">"${name}"</p>
                    <p class="text-gray-700">This product will be permanently removed from new arrivals. This action cannot be undone.</p>
                </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#111827',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'px-6 py-3 rounded-lg font-semibold',
                        cancelButton: 'px-6 py-3 rounded-lg font-semibold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/new-arrivals/${id}`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';

                        form.appendChild(csrfToken);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            const NewArrivalModal = {
                openAdd: function() {
                    document.getElementById('addNewArrivalModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                },
                closeAdd: function() {
                    const modal = document.getElementById('addNewArrivalModal');
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';

                    // Reset form
                    const form = modal.querySelector('form');
                    form.reset();

                    // Hide preview and reset file input
                    removePreview('addImagePreview', 'addImageInput');
                },
                openEdit: async function(id) {
                    try {
                        // Show loading state
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Fetching product details',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Fetch product data
                        const response = await fetch(`/admin/new-arrivals/${id}/edit`);

                        if (!response.ok) {
                            throw new Error('Failed to load product data');
                        }

                        const product = await response.json();

                        Swal.close();

                        // Populate form fields
                        document.getElementById('edit_name').value = product.name;
                        document.getElementById('edit_description').value = product.description;
                        document.getElementById('edit_price').value = product.price;
                        document.getElementById('edit_image_preview').src = product.image;

                        // Set status
                        if (product.status === 'active') {
                            document.getElementById('edit_status_active').checked = true;
                        } else {
                            document.getElementById('edit_status_inactive').checked = true;
                        }

                        // Set form action
                        document.getElementById('editArrivalForm').action = `/admin/new-arrivals/${id}`;

                        // Show modal
                        document.getElementById('editNewArrivalModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load product data. Please try again.',
                            confirmButtonColor: '#111827',
                        });
                    }
                },
                closeEdit: function() {
                    const modal = document.getElementById('editNewArrivalModal');
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';

                    // Hide preview and reset file input
                    removePreview('editImagePreview', 'editImageInput');
                }
            };

            // Close modals on outside click
            document.addEventListener('click', function(e) {
                if (e.target.id === 'addNewArrivalModal') NewArrivalModal.closeAdd();
                if (e.target.id === 'editNewArrivalModal') NewArrivalModal.closeEdit();
            });

            // Close modals on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    NewArrivalModal.closeAdd();
                    NewArrivalModal.closeEdit();
                }
            });

            // Initialize radio button styling
            document.addEventListener('DOMContentLoaded', function() {
                // Handle radio button styling
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const allRadios = this.closest('div').querySelectorAll('input[type="radio"]');
                        allRadios.forEach(r => {
                            const parentDiv = r.nextElementSibling;
                            if (r.checked) {
                                parentDiv.classList.add('peer-checked:border-gray-900',
                                    'peer-checked:bg-gray-50');
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
