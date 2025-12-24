@extends('layouts.front')

@section('content')
    {{-- Hero Banner --}}
    <section class="relative h-screen overflow-hidden bg-black">
        {{-- Background Image with Clip Path --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="
                    @if ($gender == 'men') background-image: url('https://static.zara.net/assets/public/271d/8f90/93384415b608/d29c5bb69e67/image-web-e23758c2-4305-42d5-bfed-2911b6f732d8-default/image-web-e23758c2-4305-42d5-bfed-2911b6f732d8-default.jpg?ts=1761911121683&w=1888');
                    @elseif($gender == 'women')
                        background-image: url('https://static.zara.net/assets/public/7f8f/31e9/c6f54de7b828/1743439b03be/04813857800-h1/04813857800-h1.jpg?ts=1761899542478&w=1888');
                    @else
                        background-image: url('https://static.zara.net/assets/public/b87f/4ffa/d91b42b4b2bc/55726691dfde/image-web-5-204e051f-8609-4829-929b-91db4c71ee24-default/image-web-5-204e051f-8609-4829-929b-91db4c71ee24-default.jpg?ts=1761740019541&w=1888'); @endif
                    clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
                ">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/50"
                style="clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
        </div>

        {{-- Geometric Shapes --}}
        <div class="absolute top-20 right-20 w-32 h-32 border border-white/20 rotate-45"></div>
        <div class="absolute bottom-40 left-10 w-24 h-24 border border-white/20"></div>

        {{-- Content --}}
        <div class="relative h-full flex items-center justify-center">
            <div class="text-center px-4 max-w-4xl">
                <div class="overflow-hidden mb-8">
                    <h1 class="text-7xl md:text-9xl font-bold text-white tracking-tighter animate-[slideUp_0.8s_ease-out]">
                        {{ ucfirst($gender) }}
                    </h1>
                </div>
                <div class="flex items-center justify-center gap-4 text-white/80 text-sm tracking-[0.3em] uppercase">
                    <span class="w-12 h-px bg-white/60"></span>
                    <span>Collection</span>
                    <span class="w-12 h-px bg-white/60"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Products Section --}}
    <section id="productsSection" class="py-24 relative">
        {{-- Background Accent --}}
        <div class="absolute top-0 right-0 w-1/3 h-64 bg-gradient-to-l from-gray-50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">

                <aside class="lg:col-span-1 relative">
                    <div class="sticky top-0">
                        <div class="relative">
                            {{-- Minimal decorative line --}}
                            <div class="absolute -left-6 top-0 w-0.5 h-full bg-gradient-to-b from-gray-300 to-transparent">
                            </div>

                            <div class="space-y-10">
                                {{-- Search Section --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        SEARCH
                                    </h2>

                                    <div class="relative">
                                        <input type="text" id="searchInput" value="{{ request('search') }}"
                                            placeholder="Find your style..."
                                            class="w-full px-0 py-3 bg-transparent border-0 border-b border-gray-300 focus:border-black focus:ring-0 transition-all text-sm placeholder:text-gray-400 rounded-none">
                                        <div class="absolute right-0 top-1/2 -translate-y-1/2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Category Filter (for gender pages) --}}
                                @if (isset($gender) && $genderCategories && $genderCategories->count() > 1)
                                    <div class="space-y-4">
                                        <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                            CATEGORIES
                                        </h2>

                                        <div class="space-y-2">
                                            <label class="flex items-center gap-3 cursor-pointer py-2">
                                                <div class="relative">
                                                    <input type="radio" name="category" value=""
                                                        {{ request('category') == '' ? 'checked' : '' }}
                                                        class="filter-radio hidden">
                                                    <div
                                                        class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                        <div
                                                            class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-600">All Categories</span>
                                            </label>
                                            @foreach ($genderCategories as $cat)
                                                <label class="flex items-center gap-3 cursor-pointer py-2">
                                                    <div class="relative">
                                                        <input type="radio" name="category" value="{{ $cat->slug }}"
                                                            {{ request('category') == $cat->slug ? 'checked' : '' }}
                                                            class="filter-radio hidden">
                                                        <div
                                                            class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                            <div
                                                                class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Price Range Filter --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        PRICE RANGE
                                    </h2>

                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">${{ $priceRange['min'] ?? 0 }}</span>
                                            <span class="text-sm text-gray-600">${{ $priceRange['max'] ?? 1000 }}</span>
                                        </div>

                                        <!-- Container for dual range slider -->
                                        <div class="relative h-6">
                                            <!-- Full track background (gray) -->
                                            <div
                                                class="absolute w-full h-1 bg-gray-300 rounded-lg top-1/2 -translate-y-1/2">
                                            </div>

                                            <!-- Selected range track (black) -->
                                            <div class="absolute h-1 bg-black rounded-lg top-1/2 -translate-y-1/2"
                                                id="selectedRangeTrack"></div>

                                            <!-- Min price slider -->
                                            <input type="range" id="priceMin" name="min_price"
                                                min="{{ $priceRange['min'] ?? 0 }}" max="{{ $priceRange['max'] ?? 1000 }}"
                                                value="{{ request('min_price', $priceRange['min'] ?? 0) }}"
                                                class="absolute w-full h-1 bg-transparent appearance-none cursor-pointer z-10 min-slider">

                                            <!-- Max price slider -->
                                            <input type="range" id="priceMax" name="max_price"
                                                min="{{ $priceRange['min'] ?? 0 }}" max="{{ $priceRange['max'] ?? 1000 }}"
                                                value="{{ request('max_price', $priceRange['max'] ?? 1000) }}"
                                                class="absolute w-full h-1 bg-transparent appearance-none cursor-pointer z-20 max-slider">
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500" id="priceMinValue">
                                                ${{ request('min_price', $priceRange['min'] ?? 0) }}
                                            </span>
                                            <span class="text-xs text-gray-500" id="priceMaxValue">
                                                ${{ request('max_price', $priceRange['max'] ?? 1000) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Size Filter --}}
                                @if (!empty($availableSizes))
                                    <div class="space-y-4">
                                        <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                            SIZE
                                        </h2>

                                        <div class="space-y-2">
                                            @foreach ($availableSizes as $size)
                                                <label class="flex items-center gap-3 cursor-pointer py-2">
                                                    <div class="relative">
                                                        <input type="checkbox" name="size[]" value="{{ $size }}"
                                                            {{ in_array($size, (array) request('size', [])) ? 'checked' : '' }}
                                                            class="filter-checkbox hidden">
                                                        <div
                                                            class="w-4 h-4 border border-gray-400 rounded flex items-center justify-center transition-all checkbox-custom">
                                                            <svg class="w-3 h-3 text-black opacity-0 transition-opacity check-icon"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ $size }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Color Filter --}}
                                @if (!empty($availableColors))
                                    <div class="space-y-4">
                                        <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                            COLOR
                                        </h2>

                                        <div class="space-y-2">
                                            @foreach ($availableColors as $color)
                                                <label class="flex items-center gap-3 cursor-pointer py-2">
                                                    <div class="relative">
                                                        <input type="checkbox" name="color[]"
                                                            value="{{ $color }}"
                                                            {{ in_array($color, (array) request('color', [])) ? 'checked' : '' }}
                                                            class="filter-checkbox hidden">
                                                        <div
                                                            class="w-4 h-4 border border-gray-400 rounded flex items-center justify-center transition-all checkbox-custom">
                                                            <svg class="w-3 h-3 text-black opacity-0 transition-opacity check-icon"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ $color }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Brand Filter --}}
                                @if (!empty($availableBrands))
                                    <div class="space-y-4">
                                        <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                            BRAND
                                        </h2>

                                        <div class="space-y-2">
                                            @foreach ($availableBrands as $brand)
                                                <label class="flex items-center gap-3 cursor-pointer py-2">
                                                    <div class="relative">
                                                        <input type="checkbox" name="brand[]"
                                                            value="{{ $brand }}"
                                                            {{ in_array($brand, (array) request('brand', [])) ? 'checked' : '' }}
                                                            class="filter-checkbox hidden">
                                                        <div
                                                            class="w-4 h-4 border border-gray-400 rounded flex items-center justify-center transition-all checkbox-custom">
                                                            <svg class="w-3 h-3 text-white opacity-0 transition-opacity check-icon"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ $brand }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Featured & New Arrivals Filter --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        COLLECTION
                                    </h2>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="checkbox" name="featured" value="1"
                                                    {{ request('featured') == '1' ? 'checked' : '' }}
                                                    class="filter-checkbox hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded flex items-center justify-center transition-all checkbox-custom">
                                                    <svg class="w-3 h-3 text-white opacity-0 transition-opacity check-icon"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Featured</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="checkbox" name="new_arrival" value="1"
                                                    {{ request('new_arrival') == '1' ? 'checked' : '' }}
                                                    class="filter-checkbox hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded flex items-center justify-center transition-all checkbox-custom">
                                                    <svg class="w-3 h-3 text-black opacity-0 transition-opacity check-icon"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">New Arrivals</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Stock Status Filter --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        STOCK STATUS
                                    </h2>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value=""
                                                    {{ request('status') == '' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">All Items</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value="in_stock"
                                                    {{ request('status') == 'in_stock' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">In Stock</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value="low_stock"
                                                    {{ request('status') == 'low_stock' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Low Stock</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="status" value="out_of_stock"
                                                    {{ request('status') == 'out_of_stock' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Out of Stock</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sort Filter --}}
                                <div class="space-y-4">
                                    <h2 class="text-base font-medium text-gray-900 tracking-wide">
                                        SORT BY
                                    </h2>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value=""
                                                    {{ request('sort') == '' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Recommended</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="newest"
                                                    {{ request('sort') == 'newest' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Newest</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="popular"
                                                    {{ request('sort') == 'popular' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Popular</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="price_low_high"
                                                    {{ request('sort') == 'price_low_high' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Price: Low to High</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="price_high_low"
                                                    {{ request('sort') == 'price_high_low' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Price: High to Low</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer py-2">
                                            <div class="relative">
                                                <input type="radio" name="sort" value="rating"
                                                    {{ request('sort') == 'rating' ? 'checked' : '' }}
                                                    class="filter-radio hidden">
                                                <div
                                                    class="w-4 h-4 border border-gray-400 rounded-full flex items-center justify-center transition-all radio-custom">
                                                    <div
                                                        class="w-2 h-2 bg-black rounded-full scale-0 transition-transform radio-dot">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-600">Highest Rated</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Clear Filters Button --}}
                                @if (request()->hasAny([
                                        'search',
                                        'min_price',
                                        'max_price',
                                        'size',
                                        'color',
                                        'brand',
                                        'featured',
                                        'new_arrival',
                                        'status',
                                        'sort',
                                        'category',
                                    ]))
                                    <div class="pt-4">
                                        <button type="button" id="clearFilters"
                                            class="w-full py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded hover:bg-gray-50 transition-colors duration-200">
                                            Clear All Filters
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Products Grid --}}
                <div class="lg:col-span-4" id="productsContainer">
                    @include('partials.products-grid', ['products' => $products])
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Custom radio button states */
        .filter-radio:checked+.radio-custom {
            border-color: black;
        }

        .filter-radio:checked+.radio-custom .radio-dot {
            transform: scale(1);
        }

        /* Custom radio button states */
        .filter-radio:checked+.radio-custom {
            border-color: black;
        }

        .filter-radio:checked+.radio-custom .radio-dot {
            transform: scale(1);
        }

        /* Range slider styling */
        .slider {
            -webkit-appearance: none;
            height: 4px;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: black;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .slider::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: black;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* Range slider styling */
        .min-slider,
        .max-slider {
            -webkit-appearance: none;
            height: 4px;
            margin: 0;
            pointer-events: none;
        }

        /* Min slider thumb - only responds on left side */
        .min-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: black;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            pointer-events: auto;
            z-index: 25;
        }

        .min-slider::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: black;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            pointer-events: auto;
            z-index: 25;
        }

        /* Max slider thumb - only responds on right side */
        .max-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: black;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            pointer-events: auto;
            z-index: 35;
        }

        .max-slider::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: black;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            pointer-events: auto;
            z-index: 35;
        }

        /* Track styling for Firefox */
        .min-slider::-moz-range-track,
        .max-slider::-moz-range-track {
            background: transparent;
            border: none;
        }

        /* Track styling for IE/Edge */
        .min-slider::-ms-track,
        .max-slider::-ms-track {
            background: transparent;
            border-color: transparent;
            color: transparent;
        }

        /* Hover effects */
        .min-slider::-webkit-slider-thumb:hover,
        .max-slider::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }

        .min-slider::-moz-range-thumb:hover,
        .max-slider::-moz-range-thumb:hover {
            transform: scale(1.1);
        }


        /* Hover effects */
        label:hover .radio-custom {
            border-color: #666;
        }

        label:hover span {
            color: #000;
        }

        /* Smooth transitions */
        .checkbox-custom,
        .radio-custom,
        .check-icon,
        .radio-dot,
        span {
            transition: all 0.2s ease;
        }

        /* Clean input focus */
        input:focus {
            outline: none;
            box-shadow: none;
        }

        /* Enhanced Pagination Styles */
        .pagination-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .pagination-number:hover {
            border-color: #000;
            color: #000;
            transform: translateY(-1px);
        }

        .pagination-active {
            background-color: #000;
            border-color: #000;
            color: white;
        }

        .pagination-arrow:hover {
            background-color: #000;
            border-color: #000;
            color: white;
            transform: translateY(-1px);
        }

        .pagination-disabled {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .pagination-disabled:hover {
            transform: none;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
        }

        /* Loading state */
        .pagination-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Category badge styles */
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // 1. INITIALIZE CUSTOM FORM CONTROLS
            // ============================================

            // Initialize custom radio buttons
            const radios = document.querySelectorAll('.filter-radio');
            radios.forEach(radio => {
                // Set initial checked state
                if (radio.checked) {
                    const dot = radio.nextElementSibling.querySelector('.radio-dot');
                    if (dot) dot.style.transform = 'scale(1)';
                }

                // Add change event
                radio.addEventListener('change', function() {
                    const groupName = this.name;
                    document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                        const customRadio = r.nextElementSibling;
                        const dot = customRadio.querySelector('.radio-dot');
                        if (dot) dot.style.transform = 'scale(0)';
                    });

                    const currentDot = this.nextElementSibling.querySelector('.radio-dot');
                    if (currentDot) currentDot.style.transform = 'scale(1)';
                });
            });

            // Initialize checkboxes
            const checkboxes = document.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(checkbox => {
                // Set initial checked state
                if (checkbox.checked) {
                    const checkIcon = checkbox.nextElementSibling.querySelector('.check-icon');
                    if (checkIcon) {
                        checkIcon.style.opacity = '1';
                        checkbox.nextElementSibling.style.backgroundColor = 'black';
                        checkbox.nextElementSibling.style.borderColor = 'black';
                    }
                }

                // Add change event
                checkbox.addEventListener('change', function() {
                    const checkIcon = this.nextElementSibling.querySelector('.check-icon');
                    if (checkIcon) {
                        if (this.checked) {
                            checkIcon.style.opacity = '1';
                            this.nextElementSibling.style.backgroundColor = 'black';
                            this.nextElementSibling.style.borderColor = 'black';
                        } else {
                            checkIcon.style.opacity = '0';
                            this.nextElementSibling.style.backgroundColor = 'transparent';
                            this.nextElementSibling.style.borderColor = '#d1d5db';
                        }
                    }
                });
            });

            // ============================================
            // 2. PRICE RANGE SLIDERS
            // ============================================

            const priceMin = document.getElementById('priceMin');
            const priceMax = document.getElementById('priceMax');
            const priceMinValue = document.getElementById('priceMinValue');
            const priceMaxValue = document.getElementById('priceMaxValue');
            const selectedRangeTrack = document.getElementById('selectedRangeTrack');

            if (priceMin && priceMax && selectedRangeTrack) {
                function updatePriceRange() {
                    const minVal = parseInt(priceMin.value);
                    const maxVal = parseInt(priceMax.value);
                    const minRange = parseInt(priceMin.min);
                    const maxRange = parseInt(priceMax.max);

                    // Ensure min doesn't exceed max
                    if (minVal > maxVal) {
                        priceMin.value = maxVal;
                        priceMinValue.textContent = '$' + maxVal;
                    } else {
                        priceMinValue.textContent = '$' + minVal;
                    }

                    // Ensure max doesn't go below min
                    if (maxVal < minVal) {
                        priceMax.value = minVal;
                        priceMaxValue.textContent = '$' + minVal;
                    } else {
                        priceMaxValue.textContent = '$' + maxVal;
                    }

                    // Update the black line (selected range track)
                    const minPercent = ((minVal - minRange) / (maxRange - minRange)) * 100;
                    const maxPercent = ((maxVal - minRange) / (maxRange - minRange)) * 100;

                    // Set position and width of the black line
                    selectedRangeTrack.style.left = minPercent + '%';
                    selectedRangeTrack.style.width = (maxPercent - minPercent) + '%';
                }

                priceMin.addEventListener('input', updatePriceRange);
                priceMax.addEventListener('input', updatePriceRange);

                // Initialize values and track
                updatePriceRange();
            }

            // ============================================
            // 3. CLEAR FILTERS BUTTON
            // ============================================

            const clearFiltersBtn = document.getElementById('clearFilters');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    // Clear all form inputs
                    const formInputs = document.querySelectorAll(
                        'input[type="text"], input[type="radio"], input[type="checkbox"], input[type="range"]'
                    );
                    formInputs.forEach(input => {
                        if (input.type === 'text') {
                            input.value = '';
                        } else if (input.type === 'radio') {
                            input.checked = false;
                            // Reset radio UI
                            const dot = input.nextElementSibling?.querySelector('.radio-dot');
                            if (dot) dot.style.transform = 'scale(0)';
                        } else if (input.type === 'checkbox') {
                            input.checked = false;
                            // Reset checkbox UI
                            const checkIcon = input.nextElementSibling?.querySelector(
                                '.check-icon');
                            if (checkIcon) {
                                checkIcon.style.opacity = '0';
                                input.nextElementSibling.style.backgroundColor = 'transparent';
                                input.nextElementSibling.style.borderColor = '#d1d5db';
                            }
                        } else if (input.type === 'range') {
                            // Reset to min/max values
                            if (input.id === 'priceMin') {
                                input.value = input.min;
                            } else if (input.id === 'priceMax') {
                                input.value = input.max;
                            }
                        }
                    });

                    // Reset price display
                    if (priceMin && priceMax && priceMinValue && priceMaxValue) {
                        priceMinValue.textContent = '$' + priceMin.min;
                        priceMaxValue.textContent = '$' + priceMax.max;
                    }

                    // Trigger filter update
                    if (typeof updateProducts === 'function') {
                        updateProducts();
                    }
                });
            }

            // ============================================
            // 4. AUTO-UPDATE PRODUCTS ON FILTER CHANGE
            // ============================================

            // Auto-update products when filters change (except search which has debounce)
            const filterElements = document.querySelectorAll(
                '.filter-radio, .filter-checkbox, input[type="range"]');
            filterElements.forEach(element => {
                element.addEventListener('change', function() {
                    // For checkboxes, wait a moment to allow UI update
                    if (this.type === 'checkbox') {
                        setTimeout(() => {
                            if (typeof updateProducts === 'function') {
                                updateProducts();
                            }
                        }, 50);
                    } else {
                        if (typeof updateProducts === 'function') {
                            updateProducts();
                        }
                    }
                });
            });

            // Search with debounce
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (typeof updateProducts === 'function') {
                            updateProducts();
                        }
                    }, 600);
                });
            }

            // ============================================
            // 5. AJAX PRODUCT FILTERING & PAGINATION
            // ============================================

            // Get the correct slug - use gender if available, otherwise use category slug
            const gender = "{{ $gender ?? '' }}";
            const currentSlug = gender ? gender : "{{ $category->slug ?? '' }}";

            const productsContainer = document.getElementById('productsContainer');
            const paginationContainer = document.getElementById('paginationContainer');

            let isUpdating = false;

            // Function to build URL with all filter parameters
            function buildFilterURL() {
                const params = new URLSearchParams();

                // Search
                if (searchInput && searchInput.value) {
                    params.append('search', searchInput.value);
                }

                // Category filter (for gender pages)
                const categoryRadio = document.querySelector('input[name="category"]:checked');
                if (categoryRadio && categoryRadio.value) {
                    params.append('category', categoryRadio.value);
                }

                // Price range
                if (priceMin && priceMin.value && priceMin.value != priceMin.min) {
                    params.append('min_price', priceMin.value);
                }
                if (priceMax && priceMax.value && priceMax.value != priceMax.max) {
                    params.append('max_price', priceMax.value);
                }

                // Size filter (checkboxes)
                const sizeCheckboxes = document.querySelectorAll('input[name="size[]"]:checked');
                sizeCheckboxes.forEach(cb => {
                    params.append('size[]', cb.value);
                });

                // Color filter (checkboxes)
                const colorCheckboxes = document.querySelectorAll('input[name="color[]"]:checked');
                colorCheckboxes.forEach(cb => {
                    params.append('color[]', cb.value);
                });

                // Brand filter (checkboxes)
                const brandCheckboxes = document.querySelectorAll('input[name="brand[]"]:checked');
                brandCheckboxes.forEach(cb => {
                    params.append('brand[]', cb.value);
                });

                // Featured & New Arrivals
                const featuredCheckbox = document.querySelector('input[name="featured"]:checked');
                if (featuredCheckbox) {
                    params.append('featured', '1');
                }

                const newArrivalCheckbox = document.querySelector('input[name="new_arrival"]:checked');
                if (newArrivalCheckbox) {
                    params.append('new_arrival', '1');
                }

                // Stock status
                const statusRadio = document.querySelector('input[name="status"]:checked');
                if (statusRadio && statusRadio.value) {
                    params.append('status', statusRadio.value);
                }

                // Sort
                const sortRadio = document.querySelector('input[name="sort"]:checked');
                if (sortRadio && sortRadio.value) {
                    params.append('sort', sortRadio.value);
                }

                // Current page for pagination
                const pageValue = new URLSearchParams(window.location.search).get('page') || '';
                if (pageValue) {
                    params.append('page', pageValue);
                }

                return `/${currentSlug}?${params.toString()}`;
            }

            // Function to update products without page reload
            window.updateProducts = function(url = null) {
                if (isUpdating) return;
                isUpdating = true;

                // Show loading state
                if (productsContainer) {
                    productsContainer.style.opacity = '0.7';
                }
                if (paginationContainer) {
                    paginationContainer.classList.add('pagination-loading');
                }

                // Build URL with current parameters if no specific URL provided
                if (!url) {
                    url = buildFilterURL();
                }

                // Update URL without reload
                window.history.pushState({}, '', url);

                // Fetch new content
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Parse the HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('productsContainer');

                        if (newContent && productsContainer) {
                            // Smooth transition
                            productsContainer.style.opacity = '0.5';
                            setTimeout(() => {
                                productsContainer.innerHTML = newContent.innerHTML;
                                productsContainer.style.opacity = '1';

                                // Scroll to top of products section
                                const productsSection = document.getElementById('productsSection');
                                if (productsSection) {
                                    const offsetTop = productsSection.offsetTop - 80;
                                    window.scrollTo({
                                        top: offsetTop,
                                        behavior: 'smooth'
                                    });
                                }

                                // Re-initialize event listeners for the new pagination
                                initializePaginationListeners();
                            }, 200);
                        }
                        isUpdating = false;

                        // Remove loading state
                        if (paginationContainer) {
                            paginationContainer.classList.remove('pagination-loading');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        isUpdating = false;
                        if (productsContainer) {
                            productsContainer.style.opacity = '1';
                        }
                        if (paginationContainer) {
                            paginationContainer.classList.remove('pagination-loading');
                        }
                    });
            };

            // Initialize pagination event listeners
            function initializePaginationListeners() {
                const paginationLinks = document.querySelectorAll('.pagination-arrow, .pagination-number');

                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        if (typeof updateProducts === 'function') {
                            updateProducts(url);
                        }
                    });
                });
            }

            // Price range sliders with debounce for AJAX
            if (priceMin && priceMax) {
                let priceTimeout;

                function handlePriceChange() {
                    clearTimeout(priceTimeout);
                    priceTimeout = setTimeout(() => {
                        if (typeof updateProducts === 'function') {
                            updateProducts();
                        }
                    }, 300);
                }

                priceMin.addEventListener('input', handlePriceChange);
                priceMax.addEventListener('input', handlePriceChange);
            }

            // Initialize pagination listeners on page load
            initializePaginationListeners();

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                if (typeof updateProducts === 'function') {
                    updateProducts(window.location.href);
                }
            });
        });
    </script>

@endsection
