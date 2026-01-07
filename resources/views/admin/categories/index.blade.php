@extends('admin.layouts.app')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin.categories.title') }}</h1>
                <p class="text-gray-700">{{ __('admin.categories.subtitle') }}</p>
            </div>
            <button onclick="CategoryModal.openAdd()"
                class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl transition-all duration-300 hover:from-Ocean/90 hover:to-Ocean/70 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i
                    class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>{{ __('admin.categories.add_new') }}
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
        @php
            $totalCategories = $categories->count();
            $activeCategories = $categories->where('status', 'active')->count();
            $menCategories = $categories->where('gender', 'men')->count();
            $womenCategories = $categories->where('gender', 'women')->count();
            $kidsCategories = $categories->where('gender', 'kids')->count();
            $unisexCategories = $categories->where('gender', 'unisex')->count();

            // Calculate total products across all categories
            $totalProducts = 0;
            foreach ($categories as $category) {
                $totalProducts += $category->products->count();
            }
        @endphp

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">{{ __('admin.categories.stats.total') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalCategories }}</p>
                    <p class="text-blue-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-folder mr-1"></i> {{ __('admin.categories.stats.all_categories') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-folder text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">{{ __('admin.categories.stats.active') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $activeCategories }}</p>
                    <p class="text-green-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ $totalCategories > 0 ? number_format(($activeCategories / $totalCategories) * 100, 0) : 0 }}%
                        active
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">{{ __('admin.categories.stats.total_products') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalProducts }}</p>
                    <p class="text-purple-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-box mr-1"></i> {{ __('admin.categories.stats.across_categories') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Gender Distribution Cards -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">{{ __('admin.categories.stats.mens') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $menCategories }}</p>
                    <p class="text-blue-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-mars mr-1"></i> {{ __('admin.categories.stats.male_categories') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-mars text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-100 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-600 text-sm font-medium">{{ __('admin.categories.stats.womens') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $womenCategories }}</p>
                    <p class="text-pink-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-venus mr-1"></i> {{ __('admin.categories.stats.female_categories') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-pink-400 to-pink-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-venus text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="350">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">{{ __('admin.categories.stats.kids') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $kidsCategories }}</p>
                    <p class="text-green-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-child mr-1"></i> {{ __('admin.categories.stats.children_categories') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-400 to-green-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-child text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">{{ __('admin.categories.stats.unisex') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $unisexCategories }}</p>
                    <p class="text-gray-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-venus-mars mr-1"></i> {{ __('admin.categories.stats.gender_neutral') }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-venus-mars text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    @include('admin.categories.partials.table', ['categories' => $categories])


    <!-- Add Category Modal -->
    <div id="addCategoryModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
        <div class="bg-gradient-to-b from-white to-gray-50 w-full max-w-4xl rounded-3xl shadow-lg">
            <!-- Modal Header -->
            <div
                class="flex items-center justify-between p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.categories.add_new') }}</h2>
                        <p class="text-gray-700">{{ __('admin.categories.modal.add_subtitle') }}</p>
                    </div>
                </div>
                <button onclick="CategoryModal.closeAdd()"
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                    <i
                        class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-blue-600"></i>
                                    {{ __('admin.categories.modal.name') }} *
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm"
                                    placeholder="e.g., Men's Clothing">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-link mr-2 text-gray-600"></i>
                                    {{ __('admin.categories.modal.slug_label') }} *
                                </label>
                                <div class="relative">
                                    <div class="flex items-center">
                                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                            required
                                            class="flex-1 border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm"
                                            placeholder="e.g., mens-clothing">
                                    </div>
                                    <button type="button" onclick="generateSlug()"
                                        class="absolute right-3 top-4 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-all duration-200">
                                        <i class="fas fa-sync-alt mr-1"></i> Generate
                                    </button>
                                </div>
                                @error('slug')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender Selection -->
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-venus-mars mr-2 text-purple-600"></i>
                                    {{ __('admin.categories.modal.gender_label') }}
                                </label>
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" value="men"
                                            {{ old('gender', 'unisex') === 'men' ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-mars text-blue-600 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_men') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" value="women"
                                            {{ old('gender') === 'women' ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-venus text-pink-600 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_women') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" value="kids"
                                            {{ old('gender') === 'kids' ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-child text-green-600 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_kids') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" value="unisex"
                                            {{ old('gender', 'unisex') === 'unisex' ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-500 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-venus-mars text-gray-700 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_unisex') }}</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="text-gray-700 text-xs mt-2">{{ __('admin.categories.modal.gender_desc') }}</p>
                                @error('gender')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-sort-numeric-up mr-2 text-green-600"></i>
                                    {{ __('admin.categories.modal.display_order') }} *
                                </label>
                                <input type="number" name="sort_order"
                                    value="{{ old('sort_order', $categories->count()) }}" min="0"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 shadow-sm"
                                    placeholder="Position in display sequence">
                                <p class="text-gray-700 text-xs mt-2">
                                    {{ __('admin.categories.modal.display_order_desc') }}</p>
                                @error('sort_order')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-emerald-600"></i>
                                    Status
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="active" checked
                                            class="sr-only peer">
                                        <div
                                            class="p-4 border-2 h-32 border-gray-200 rounded-xl text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-check-circle text-emerald-600 mb-2"></i>
                                            <p class="font-medium text-gray-900">
                                                {{ __('admin.categories.modal.status_active') }}</p>
                                            <p class="text-gray-700 text-sm">
                                                {{ __('admin.categories.modal.status_active_desc') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" value="inactive" class="sr-only peer">
                                        <div
                                            class="p-4 border-2 h-32 border-gray-200 rounded-xl text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-eye-slash text-red-600 mb-2"></i>
                                            <p class="font-medium text-gray-900">
                                                {{ __('admin.categories.modal.status_inactive') }}</p>
                                            <p class="text-gray-700 text-sm">
                                                {{ __('admin.categories.modal.status_inactive_desc') }}</p>
                                        </div>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>


                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                                    {{ __('admin.categories.modal.description_label') }}
                                </label>
                                <textarea name="description" rows="4"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 resize-none transition-all duration-200 shadow-sm"
                                    placeholder="{{ __('admin.categories.modal.description_placeholder') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-image mr-2 text-rose-600"></i>
                                    Category Image
                                </label>
                                <div class="relative">
                                    <input type="file" name="image" accept="image/*" id="categoryImageInput"
                                        class="hidden" onchange="previewImage(this, 'categoryImagePreview')">
                                    <label for="categoryImageInput"
                                        class="block border-4 border-dashed border-gray-200 hover:border-rose-500 rounded-2xl p-12 text-center cursor-pointer transition-all duration-200 group/upload bg-gradient-to-b from-white to-gray-50">
                                        <div
                                            class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 group-hover/upload:from-rose-500 group-hover/upload:to-rose-600 flex items-center justify-center mx-auto mb-4 transition-all duration-200 shadow-sm">
                                            <i
                                                class="fas fa-cloud-upload-alt text-gray-700 group-hover/upload:text-white text-2xl transition-all duration-200"></i>
                                        </div>
                                        <p class="text-gray-900 font-medium mb-2">
                                            {{ __('admin.categories.modal.image_upload') }}</p>
                                        <p class="text-gray-700 text-sm">Recommended: 400×400px • Max 2MB</p>
                                        <p class="text-gray-700 text-sm">JPG, PNG, WebP formats</p>
                                    </label>
                                </div>
                                <div id="categoryImagePreview" class="mt-4 hidden">
                                    <div class="relative rounded-xl overflow-hidden border-4 border-gray-100 shadow-sm">
                                        <img id="categoryPreviewImage" class="w-full h-48 object-cover">
                                        <button type="button"
                                            onclick="removePreview('categoryImagePreview', 'categoryImageInput')"
                                            class="absolute top-3 right-3 w-8 h-8 bg-white hover:bg-red-500 hover:text-white rounded-full flex items-center justify-center transition-all duration-200 shadow">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <p class="text-gray-700 text-xs text-center mt-2">
                                        {{ __('admin.categories.modal.image_preview') }}</p>
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div> --}}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                        <button type="button" onclick="CategoryModal.closeAdd()"
                            class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900 shadow-sm hover:shadow">
                            {{ __('admin.categories.modal.cancel') }}
                        </button>
                        <button type="submit"
                            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 flex items-center group/submit shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i
                                class="fas fa-plus-circle mr-3 group-hover/submit:rotate-90 transition-transform duration-300"></i>
                            {{ __('admin.categories.modal.create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-md flex items-center justify-center z-50 hidden p-4">
        <div class="bg-gradient-to-b from-white to-gray-50 w-full max-w-4xl rounded-3xl shadow-lg">
            <!-- Modal Header -->
            <div
                class="flex items-center justify-between p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.categories.modal.edit_title') }}</h2>
                        <p class="text-gray-700">{{ __('admin.categories.modal.edit_subtitle') }}</p>
                    </div>
                </div>
                <button onclick="CategoryModal.closeEdit()"
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition-all duration-200 group">
                    <i
                        class="fas fa-times text-gray-700 text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="overflow-y-auto" style="max-height: calc(90vh - 180px);">
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-tag mr-2 text-blue-600"></i>
                                    {{ __('admin.categories.modal.name') }} *
                                </label>
                                <input type="text" name="name" id="editName" required
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-link mr-2 text-gray-600"></i>
                                    {{ __('admin.categories.modal.slug_label') }} *
                                </label>
                                <div class="relative">
                                    <div class="flex items-center">
                                        <input type="text" name="slug" id="editSlug" required
                                            class="flex-1 border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 shadow-sm"
                                            placeholder="e.g., mens-clothing">
                                    </div>
                                    <button type="button" onclick="generateSlugForEdit()"
                                        class="absolute right-3 top-4 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-all duration-200">
                                        <i class="fas fa-sync-alt mr-1"></i> Generate
                                    </button>
                                </div>
                            </div>

                            <!-- Gender Selection -->
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-venus-mars mr-2 text-purple-600"></i>
                                    Gender
                                </label>
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" id="editGenderMen" value="men"
                                            class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-mars text-blue-600 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_men') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" id="editGenderWomen" value="women"
                                            class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-venus text-pink-600 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_women') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" id="editGenderKids" value="kids"
                                            class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-child text-green-600 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_kids') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="gender" id="editGenderUnisex" value="unisex"
                                            class="sr-only peer">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-xl text-center peer-checked:border-gray-500 peer-checked:bg-gray-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-venus-mars text-gray-700 mb-1"></i>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ __('admin.categories.modal.gender_unisex') }}</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-sort-numeric-up mr-2 text-green-600"></i>
                                    {{ __('admin.categories.modal.display_order') }}
                                </label>
                                <input type="number" name="sort_order" id="editSortOrder" min="0"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 shadow-sm">
                            </div>

                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-toggle-on mr-2 text-emerald-600"></i>
                                    Status
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" id="editStatusActive" value="active"
                                            class="sr-only peer">
                                        <div
                                            class="p-4 border-2 h-32 border-gray-200 rounded-xl text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-check-circle text-emerald-600 mb-2"></i>
                                            <p class="font-medium text-gray-900">
                                                {{ __('admin.categories.modal.status_active') }}</p>
                                            <p class="text-gray-700 text-sm">
                                                {{ __('admin.categories.modal.status_active_desc') }}</p>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="status" id="editStatusInactive" value="inactive"
                                            class="sr-only peer">
                                        <div
                                            class="p-4 border-2 h-32 border-gray-200 rounded-xl text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all duration-200 hover:bg-gray-50 shadow-sm hover:shadow">
                                            <i class="fas fa-eye-slash text-red-600 mb-2"></i>
                                            <p class="font-medium text-gray-900">
                                                {{ __('admin.categories.modal.status_inactive') }}</p>
                                            <p class="text-gray-700 text-sm">
                                                {{ __('admin.categories.modal.status_inactive_desc') }}</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                                    {{ __('admin.categories.modal.description_label') }}
                                </label>
                                <textarea name="description" id="editDescription" rows="4"
                                    class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-5 py-4 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 resize-none transition-all duration-200 shadow-sm"></textarea>
                            </div>

                            {{-- <div>
                                <label class="block text-gray-900 font-semibold mb-3 flex items-center">
                                    <i class="fas fa-image mr-2 text-rose-600"></i>
                                    {{ __('admin.categories.modal.image_label') }}
                                </label>
                                <div class="space-y-4">
                                    <!-- Current Image -->
                                    <div id="currentImageContainer" class="hidden">
                                        <div
                                            class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4 shadow-sm">
                                            <div class="relative w-full pt-[100%] rounded-lg overflow-hidden">
                                                <img id="editCurrentImage" src=""
                                                    class="absolute inset-0 w-full h-full object-cover">
                                            </div>
                                            <p class="text-gray-700 text-xs text-center mt-3">
                                                {{ __('admin.categories.modal.current_image') }}</p>
                                        </div>
                                    </div>

                                    <!-- Change Image -->
                                    <div class="relative">
                                        <input type="file" name="image" accept="image/*"
                                            id="editCategoryImageInput" class="hidden"
                                            onchange="previewImage(this, 'editCategoryImagePreview')">
                                        <label for="editCategoryImageInput"
                                            class="block border-2 border-dashed border-gray-200 hover:border-rose-500 rounded-xl p-6 text-center cursor-pointer transition-all duration-200 group/change bg-gradient-to-b from-white to-gray-50">
                                            <div
                                                class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 group-hover/change:from-rose-500 group-hover/change:to-rose-600 flex items-center justify-center mx-auto mb-3 transition-all duration-200 shadow-sm">
                                                <i
                                                    class="fas fa-sync-alt text-gray-700 group-hover/change:text-white transition-all duration-200"></i>
                                            </div>
                                            <p class="text-gray-900 font-medium mb-1">
                                                {{ __('admin.categories.modal.change_image') }}</p>
                                            <p class="text-gray-700 text-sm">
                                                {{ __('admin.categories.modal.change_image_optional') }}</p>
                                        </label>
                                    </div>
                                    <div id="editCategoryImagePreview" class="hidden">
                                        <div
                                            class="relative rounded-xl overflow-hidden border-4 border-gray-100 mt-4 shadow-sm">
                                            <img id="editCategoryPreviewImage" class="w-full h-48 object-cover">
                                            <button type="button"
                                                onclick="removePreview('editCategoryImagePreview', 'editCategoryImageInput')"
                                                class="absolute top-3 right-3 w-8 h-8 bg-white hover:bg-red-500 hover:text-white rounded-full flex items-center justify-center transition-all duration-200 shadow">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <p class="text-gray-700 text-xs text-center mt-2">New Image Preview</p>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-8 mt-8 border-t border-gray-100">
                        <button type="button" onclick="CategoryModal.closeEdit()"
                            class="px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:border-gray-900 shadow-sm hover:shadow">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 flex items-center group/update shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-3 group-hover/update:rotate-12 transition-transform duration-300"></i>
                            {{ __('admin.categories.modal.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom Animations */
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

        .group-hover\/delete\:shake:hover i {
            animation: shake 0.5s ease-in-out;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        /* Sortable styles */
        .sortable-item {
            user-select: none;
        }

        .sortable-item.sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6;
        }

        .sortable-item.sortable-drag {
            background-color: #f9fafb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        /* Hide scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }

        .overflow-y-auto {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Smooth transitions */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar for modal */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            // Global configuration
            const Config = {
                csrfToken: '{{ csrf_token() }}',
                routes: {
                    categories: {
                        index: "{{ route('admin.categories.index') }}",
                        store: "{{ route('admin.categories.store') }}",
                        updateOrder: "{{ route('admin.categories.update-order') }}",
                        edit: (id) => `{{ url('admin/categories') }}/${id}/edit`,
                        update: (id) => `{{ url('admin/categories') }}/${id}`,
                        destroy: (id) => `{{ url('admin/categories') }}/${id}`
                    }
                },
                assets: {
                    base: "{{ asset('') }}"
                }
            };

            // Utility functions
            const Utils = {
                showLoading: (title = 'Loading...', text = 'Please wait') => {
                    Swal.fire({
                        title: title,
                        text: text,
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                showSuccess: (message, title = 'Success!') => {
                    return Swal.fire({
                        icon: 'success',
                        title: title,
                        text: message,
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        timerProgressBar: true
                    });
                },

                showError: (message, title = 'Error') => {
                    return Swal.fire({
                        icon: 'error',
                        title: title,
                        text: message,
                        confirmButtonColor: '#111827'
                    });
                },

                handleAjaxError: (error) => {
                    console.error('AJAX Error:', error);

                    let message = 'An error occurred. Please try again.';
                    if (error.message.includes('JSON')) {
                        message = 'Server returned an unexpected response. Please check your network connection.';
                    }

                    Utils.showError(message);
                },

                clearValidationErrors: () => {
                    document.querySelectorAll('.text-red-500').forEach(el => el.remove());
                    document.querySelectorAll('.border-red-500').forEach(el => {
                        el.classList.remove('border-red-500');
                        el.classList.add('border-gray-200');
                    });
                }
            };

            // Slug generation functions
            const SlugManager = {
                generateFromName: function(name) {
                    return name
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/--+/g, '-')
                        .trim();
                },

                generate: function() {
                    const nameInput = document.querySelector('input[name="name"]');
                    const slugInput = document.getElementById('slug');

                    if (nameInput && slugInput) {
                        slugInput.value = this.generateFromName(nameInput.value);
                    }
                },

                generateForEdit: function() {
                    const nameInput = document.getElementById('editName');
                    const slugInput = document.getElementById('editSlug');

                    if (nameInput && slugInput) {
                        slugInput.value = this.generateFromName(nameInput.value);
                    }
                }
            };

            // Make functions available globally
            window.generateSlug = () => SlugManager.generate();
            window.generateSlugForEdit = () => SlugManager.generateForEdit();

            // Image Preview Functions
            const ImagePreview = {
                preview: function(input, previewContainerId) {
                    const previewContainer = document.getElementById(previewContainerId);
                    const previewImage = previewContainer.querySelector('img');

                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            previewImage.src = e.target.result;
                            previewContainer.classList.remove('hidden');
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                },

                remove: function(previewContainerId, inputId) {
                    const previewContainer = document.getElementById(previewContainerId);
                    const input = document.getElementById(inputId);

                    if (previewContainer) previewContainer.classList.add('hidden');
                    if (input) input.value = '';
                }
            };

            // Sortable Categories
            const SortableManager = {
                sortable: null,

                init: function() {
                    const sortableList = document.getElementById('sortableList');
                    if (!sortableList) return;

                    this.sortable = new Sortable(sortableList, {
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        dragClass: 'sortable-drag',
                        handle: '.cursor-move',
                        onUpdate: () => this.updateOrderNumbers()
                    });
                },

                togglePanel: function() {
                    const panel = document.getElementById('sortOrderPanel');
                    panel.classList.toggle('hidden');

                    if (!panel.classList.contains('hidden')) {
                        this.init();
                    }
                },

                updateOrderNumbers: function() {
                    document.querySelectorAll('.sortable-item').forEach((item, index) => {
                        const positionSpan = item.querySelector('span.text-sm');
                        if (positionSpan) {
                            positionSpan.textContent = `Position: #${index + 1}`;
                        }
                    });
                },

                saveOrder: async function() {
                    const items = document.querySelectorAll('.sortable-item');
                    const order = Array.from(items).map(item => item.dataset.id);

                    Utils.showLoading('Saving Order...', 'Updating category positions');

                    try {
                        const response = await fetch(Config.routes.categories.updateOrder, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': Config.csrfToken
                            },
                            body: JSON.stringify({
                                order: order
                            })
                        });

                        const data = await response.json();

                        Swal.close();

                        if (data.success) {
                            await Utils.showSuccess('Category positions have been saved successfully.',
                                'Order Updated!');
                            location.reload();
                        } else {
                            Utils.showError('Failed to update order. Please try again.');
                        }
                    } catch (error) {
                        Swal.close();
                        Utils.handleAjaxError(error);
                    }
                }
            };

            // Category Modal Manager
            const CategoryModal = {
                openAdd: function() {
                    document.getElementById('addCategoryModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                },

                closeAdd: function() {
                    const modal = document.getElementById('addCategoryModal');
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';

                    const form = modal.querySelector('form');
                    if (form) form.reset();

                    ImagePreview.remove('categoryImagePreview', 'categoryImageInput');
                    Utils.clearValidationErrors();
                },

                openEdit: async function(categoryId) {
                    try {
                        Utils.showLoading('Loading...', 'Fetching category details');

                        const response = await fetch(Config.routes.categories.edit(categoryId), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const category = await response.json();
                        Swal.close();

                        // Populate form fields
                        this.populateEditForm(category);

                        // Show modal
                        document.getElementById('editCategoryModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                        // Hide any existing preview
                        ImagePreview.remove('editCategoryImagePreview', 'editCategoryImageInput');

                    } catch (error) {
                        Swal.close();
                        console.error('Error loading category:', error);
                        Utils.showError('Failed to load category data. Please try again.');
                    }
                },

                populateEditForm: function(category) {
                    // Basic fields
                    document.getElementById('editName').value = category.name || '';
                    document.getElementById('editSlug').value = category.slug || '';
                    document.getElementById('editSortOrder').value = category.sort_order || 0;
                    document.getElementById('editDescription').value = category.description || '';

                    // Gender
                    document.querySelectorAll('input[name="gender"]').forEach(radio => {
                        radio.checked = (radio.value === category.gender);
                        this.updateRadioStyle(radio);
                    });

                    // Status
                    document.querySelectorAll('input[name="status"]').forEach(radio => {
                        radio.checked = (radio.value === category.status);
                        this.updateRadioStyle(radio);
                    });

                    // Image
                    // const currentImageContainer = document.getElementById('currentImageContainer');
                    // const currentImage = document.getElementById('editCurrentImage');

                    // if (category.image) {
                    //     const imagePath = category.image.startsWith('storage/') ?
                    //         category.image : `storage/${category.image}`;
                    //     currentImage.src = `${Config.assets.base}${imagePath}`;
                    //     currentImageContainer.classList.remove('hidden');
                    // } else {
                    //     currentImageContainer.classList.add('hidden');
                    // }

                    // Update form action
                    document.getElementById('editCategoryForm').action = Config.routes.categories.update(category.id);
                },

                updateRadioStyle: function(radio) {
                    const parentDiv = radio.nextElementSibling;
                    if (radio.checked) {
                        parentDiv.classList.add('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                    } else {
                        parentDiv.classList.remove('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                    }
                },

                closeEdit: function() {
                    const modal = document.getElementById('editCategoryModal');
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';

                    ImagePreview.remove('editCategoryImagePreview', 'editCategoryImageInput');
                    Utils.clearValidationErrors();
                }
            };

            // Delete Category Handler
            const DeleteHandler = {
                confirm: function(id, name, productCount) {
                    const hasProducts = productCount > 0;
                    const title = hasProducts ?
                        '{{ __('admin.categories.delete.title_in_use') }}' :
                        '{{ __('admin.categories.delete.title') }}';

                    const warningHtml = hasProducts ?
                        `<div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-lg p-4 text-sm text-gray-700 mt-3">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    This category has <span class="font-bold">${productCount} products</span>. 
                    Deleting it will move all products to "Uncategorized" category.
                </div>` :
                        `<div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4 text-sm text-gray-700 mt-3">
                    <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                    This category has no products. It can be safely deleted.
                </div>`;

                    Swal.fire({
                        title: title,
                        html: `<div class="text-left">
                    <p class="text-gray-900 font-semibold text-lg mb-2">"${name}"</p>
                    <p class="text-gray-700 mb-3">
                        ${hasProducts ? 'This category contains products and cannot be directly deleted.' : 'This category will be permanently removed.'}
                    </p>
                    ${warningHtml}
                </div>`,
                        icon: hasProducts ? 'error' : 'warning',
                        showCancelButton: true,
                        confirmButtonColor: hasProducts ? '#dc2626' : '#6b7280',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: hasProducts ?
                            '{{ __('admin.categories.delete.moving_products') }}' :
                            '{{ __('admin.categories.delete.button_no_products') }}',
                        cancelButtonText: '{{ __('admin.categories.delete.cancel') }}',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: `px-6 py-3 rounded-lg font-semibold ${hasProducts ? 'bg-gradient-to-r from-red-600 to-red-700' : 'bg-gradient-to-r from-gray-600 to-gray-700'}`,
                            cancelButton: 'px-6 py-3 rounded-lg font-semibold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.executeDelete(id, hasProducts);
                        }
                    });
                },

                executeDelete: function(id, hasProducts) {
                    Utils.showLoading(
                        'Deleting...',
                        hasProducts ? 'Moving products and deleting category' : 'Deleting category'
                    );

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = Config.routes.categories.destroy(id);

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = Config.csrfToken;

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            };

            // Form Handlers
            const FormHandlers = {
                initAddForm: function() {
                    const form = document.querySelector('#addCategoryModal form');
                    if (!form) return;

                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        await this.submitForm(form, 'Creating Category...');
                    });
                },

                initEditForm: function() {
                    const form = document.getElementById('editCategoryForm');
                    if (!form) return;

                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        await this.submitForm(form, 'Updating Category...');
                    });
                },

                submitForm: async function(form, loadingText) {
                    Utils.showLoading(loadingText, 'Please wait');

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: form.method,
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': Config.csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        Swal.close();

                        if (data.success) {
                            await Utils.showSuccess(data.message);

                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                setTimeout(() => location.reload(), 500);
                            }
                        } else {
                            Utils.showError(data.message || 'Operation failed');
                        }
                    } catch (error) {
                        Swal.close();
                        Utils.handleAjaxError(error);
                    }
                }
            };

            // Initialize Radio Button Styling
            const RadioButtonManager = {
                init: function() {
                    document.querySelectorAll('input[type="radio"]').forEach(radio => {
                        radio.addEventListener('change', (e) => this.updateStyle(e.target));
                        this.updateStyle(radio); // Initial state
                    });
                },

                updateStyle: function(radio) {
                    const allRadios = radio.closest('div')?.querySelectorAll('input[type="radio"]') || [];
                    allRadios.forEach(r => {
                        const parentDiv = r.nextElementSibling;
                        if (r.checked) {
                            parentDiv.classList.add('peer-checked:border-gray-900', 'peer-checked:bg-gray-50');
                        } else {
                            parentDiv.classList.remove('peer-checked:border-gray-900',
                                'peer-checked:bg-gray-50');
                        }
                    });
                }
            };

            // Highlight Active Menu
            const MenuHighlighter = {
                highlight: function() {
                    const currentPath = window.location.pathname;
                    document.querySelectorAll('.nav-menu-item').forEach(item => {
                        const href = item.getAttribute('href');
                        if (href && currentPath.includes(href.split('/').filter(Boolean).pop())) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });
                }
            };

            // Event Listeners
            const EventManager = {
                init: function() {
                    // Close modals on outside click
                    document.addEventListener('click', (e) => {
                        if (e.target.id === 'addCategoryModal') CategoryModal.closeAdd();
                        if (e.target.id === 'editCategoryModal') CategoryModal.closeEdit();
                    });

                    // Close modals on Escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            CategoryModal.closeAdd();
                            CategoryModal.closeEdit();
                        }
                    });

                    // Highlight active menu
                    MenuHighlighter.highlight();

                    // Initialize form handlers
                    FormHandlers.initAddForm();
                    FormHandlers.initEditForm();

                    // Initialize radio buttons
                    RadioButtonManager.init();
                }
            };

            // Global exports (make functions available to onclick attributes)
            window.previewImage = (input, containerId) => ImagePreview.preview(input, containerId);
            window.removePreview = (containerId, inputId) => ImagePreview.remove(containerId, inputId);
            window.toggleSortOrder = () => SortableManager.togglePanel();
            window.saveSortOrder = () => SortableManager.saveOrder();
            window.CategoryModal = CategoryModal;
            window.confirmDeleteCategory = (id, name, productCount) => DeleteHandler.confirm(id, name, productCount);

            // Initialize everything when DOM is ready
            document.addEventListener('DOMContentLoaded', () => {
                EventManager.init();
            });

            // Global error handler
            window.addEventListener('error', (e) => {
                console.error('Global error:', {
                    message: e.message,
                    filename: e.filename,
                    lineno: e.lineno,
                    colno: e.colno
                });
            });
        </script>
    @endpush
@endsection
