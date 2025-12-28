<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $product->name ?? __('messages.products') }} - Outfit 818</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tab-content {
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .tab-content.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .tab-button {
            position: relative;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            color: #111827;
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #111827;
            border-radius: 2px;
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

        .variant-option {
            transition: all 0.2s ease;
        }

        .color-option {
            transition: all 0.2s ease;
            position: relative;
        }

        .carousel-img {
            transition: opacity 0.5s ease;
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.navbar')

    <x-toast />

    <main class="py-8">
        <div class="max-w-6xl mx-auto px-4 mt-5">
            @if (isset($product))
                <!-- Single Product View -->
                <div class="overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                        <!-- Product Images -->
                        <div class="space-y-4">
                            <!-- Product Badges -->
                            <div class="flex flex-wrap gap-2">
                                @if ($product->is_featured)
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-star mr-1"></i> {{ __('messages.featured') }}
                                    </span>
                                @endif

                                @if ($product->is_new)
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-bolt mr-1"></i>{{ __('messages.new_arrival') }}
                                    </span>
                                @endif

                                @php
                                    $hasSale = $product->variants->whereNotNull('sale_price')->count() > 0;
                                @endphp
                                @if ($hasSale)
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-tag mr-1"></i> {{ __('messages.on_sale') }}
                                    </span>
                                @endif

                                @if ($product->rating_cache >= 4)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-thumbs-up mr-1"></i> {{ __('messages.top_rated') }}
                                    </span>
                                @endif
                            </div>

                            @php
                                // Collect images from images relationship
                                $images = $product->images;

                                // If no images in relationship, use placeholder
                                if ($images->isEmpty()) {
                                    $images = collect([(object) ['image_path' => 'products/placeholder.jpg']]);
                                }
                            @endphp

                            <!-- Main Image -->
                            @if ($images->isNotEmpty())
                                <div class="relative rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]"
                                    id="carousel-container">
                                    @foreach ($images as $key => $image)
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            class="carousel-img absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $key === 0 ? 'opacity-100' : 'opacity-0' }}"
                                            alt="{{ $image->alt_text ?? $product->name }}">

                                        <!-- Navigation -->
                                        @if ($images->count() > 1)
                                            <button onclick="prevSlide()"
                                                class="absolute left-4 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/80 hover:bg-white rounded-full shadow-lg transition-all z-20">
                                                ‹
                                            </button>
                                            <button onclick="nextSlide()"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/80 hover:bg-white rounded-full shadow-lg transition-all z-20">
                                                ›
                                            </button>
                                        @endif
                                    @endforeach

                                    <!-- Image Counter -->
                                    @if ($images->count() > 1)
                                        <div
                                            class="absolute bottom-4 right-4 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                            <span id="current-slide">1</span> / {{ $images->count() }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Thumbnails -->
                            @if ($images->count() > 1)
                                <div class="flex space-x-3" id="carousel-dots">
                                    @foreach ($images as $key => $image)
                                        <button onclick="goToSlide({{ $key }})"
                                            class="flex-1 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-gray-300 transition-all thumbnail-btn">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="py-4">
                            @php
                                // Get unique colors from variants
                                $availableColors = $product
                                    ->variants()
                                    ->where('is_active', true)
                                    ->where('stock', '>', 0)
                                    ->select('color', 'color_code')
                                    ->distinct()
                                    ->get();

                                // Get sizes based on selected color (default to first color)
                                $selectedColor = $availableColors->first()?->color;
                                $availableSizes = $selectedColor
                                    ? $product
                                        ->variants()
                                        ->where('color', $selectedColor)
                                        ->where('is_active', true)
                                        ->where('stock', '>', 0)
                                        ->orderByRaw("FIELD(size, 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'FREE')")
                                        ->get()
                                    : collect();

                                // Get the first variant to show initial price and stock
                                $firstVariant = $availableSizes->first();

                                // Prepare variants data for JavaScript
                                $variantsData = $product->variants
                                    ->where('is_active', true)
                                    ->where('stock', '>', 0)
                                    ->map(function ($variant) {
                                        return [
                                            'id' => $variant->id,
                                            'size' => $variant->size,
                                            'color' => $variant->color,
                                            'color_code' => $variant->color_code,
                                            'price' => $variant->price,
                                            'sale_price' => $variant->sale_price,
                                            'stock' => $variant->stock,
                                            'weight' => $variant->weight,
                                            'dimensions' => $variant->dimensions,
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            @endphp

                            <div class="mb-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                                        <p class="text-gray-500">SKU: {{ $product->sku }}</p>
                                    </div>
                                    <!-- Share Button -->
                                    <button onclick="shareProduct()" class="text-gray-400 hover:text-gray-700">
                                        <i class="fas fa-share-alt text-xl"></i>
                                    </button>
                                </div>

                                <!-- Category & Brand -->
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                    @if ($product->category)
                                        <span class="bg-gray-100 px-3 py-1 rounded-full">
                                            <i class="fas fa-tag mr-1"></i> {{ $product->category->name }}
                                        </span>
                                    @endif

                                    @if ($product->brand)
                                        <span class="bg-gray-100 px-3 py-1 rounded-full">
                                            <i class="fas fa-trademark mr-1"></i> {{ $product->brand }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Rating -->
                                @if ($product->rating_cache > 0)
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex text-amber-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->rating_cache))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $product->rating_cache)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span
                                            class="font-bold text-gray-900">{{ number_format($product->rating_cache, 1) }}</span>
                                        <span class="text-gray-500">({{ $product->review_count }} reviews)</span>
                                        <span class="text-gray-400">•</span>
                                        <span class="text-gray-500">{{ $product->view_count }} views</span>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        @if ($firstVariant)
                                            <div class="flex items-baseline gap-3 mb-2">
                                                <p id="product-price" class="text-2xl font-bold text-gray-900">
                                                    ${{ number_format($firstVariant->sale_price ?? $firstVariant->price, 2) }}
                                                </p>
                                                @if ($firstVariant->sale_price)
                                                    <p class="text-lg text-gray-400 line-through">
                                                        ${{ number_format($firstVariant->price, 2) }}
                                                    </p>
                                                    <span
                                                        class="bg-red-100 text-red-800 text-sm font-bold px-2 py-1 rounded">
                                                        Save
                                                        {{ round((($firstVariant->price - $firstVariant->sale_price) / $firstVariant->price) * 100) }}%
                                                    </span>
                                                @endif
                                            </div>
                                            <p id="stock-available"
                                                class="text-sm font-medium {{ $firstVariant->stock <= 10 ? 'text-yellow-600 bg-yellow-50' : ($firstVariant->stock <= 0 ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50') }} px-3 py-2 rounded-lg inline-flex items-center gap-2">
                                                <i
                                                    class="fas fa-{{ $firstVariant->stock <= 10 ? 'exclamation-triangle' : ($firstVariant->stock <= 0 ? 'times-circle' : 'check-circle') }}"></i>
                                                {{ $firstVariant->stock }} {{ __('messages.items_in_stock') }}
                                            </p>
                                        @else
                                            <p id="product-price" class="text-2xl font-bold text-gray-900 mb-2">
                                                Select Size
                                            </p>
                                            <p id="stock-available"
                                                class="text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 rounded-lg inline-flex items-center gap-2">
                                                <i class="fas fa-info-circle"></i>
                                                {{ __('messages.no_sizes_available') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <form id="productForm" action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Color Selection -->
                                @if ($availableColors->count() > 0)
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <label
                                                class="text-sm font-semibold text-gray-900">{{ __('messages.select_color') }}</label>
                                            <span id="selected-color-name"
                                                class="text-sm text-gray-600">{{ $availableColors->first()->color ?? '' }}</span>
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach ($availableColors as $colorOption)
                                                <label class="relative">
                                                    <input type="radio" name="color"
                                                        value="{{ $colorOption->color }}" class="hidden color-radio"
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                    <div class="color-option border-2 border-gray-200 rounded-lg p-3 cursor-pointer transition-all hover:border-gray-900 {{ $loop->first ? 'border-gray-900 ring-2 ring-gray-900 ring-offset-2' : '' }}"
                                                        style="background-color: {{ $colorOption->color_code ?? '#e5e7eb' }}; min-width: 60px; height: 60px;">
                                                        <span class="sr-only">{{ $colorOption->color }}</span>
                                                        @if ($loop->first)
                                                            <div
                                                                class="absolute -top-1 -right-1 w-4 h-4 bg-gray-900 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-check text-white text-xs"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-center mt-1 font-medium">
                                                        {{ $colorOption->color }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('color')
                                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Size Selection -->
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <label
                                            class="text-sm font-semibold text-gray-900">{{ __('messages.select_size') }}</label>
                                        <button type="button" onclick="openSizeGuide()"
                                            class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                                            <i class="fas fa-ruler"></i> {{ __('messages.size_guide') }}
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-4 gap-3" id="size-container">
                                        @foreach ($availableSizes as $variant)
                                            <label class="relative">
                                                <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                                    data-price="{{ $variant->sale_price ?? $variant->price }}"
                                                    data-stock="{{ $variant->stock }}"
                                                    data-size="{{ $variant->size }}"
                                                    data-weight="{{ $variant->weight }}"
                                                    data-dimensions="{{ $variant->dimensions }}"
                                                    class="hidden variant-radio" {{ $loop->first ? 'checked' : '' }}>
                                                <div
                                                    class="variant-option border-2 border-gray-200 rounded-lg p-3 text-center cursor-pointer transition-all hover:border-gray-900 hover:bg-gray-50 {{ $loop->first ? 'border-gray-900 bg-gray-900 text-white hover:bg-gray-900' : '' }}">
                                                    <span class="font-medium">{{ $variant->size }}</span>
                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                        <div class="text-xs text-red-500 mt-1 font-bold">
                                                            SAVE
                                                            ${{ number_format($variant->price - $variant->sale_price, 2) }}
                                                        </div>
                                                    @endif

                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    @if ($availableSizes->isEmpty())
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            No sizes available for this color
                                        </p>
                                    @endif
                                    @error('variant_id')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <label
                                            class="text-sm font-semibold text-gray-900">{{ __('messages.quantity') }}</label>
                                        <span id="max-stock-label" class="text-sm text-gray-600">
                                            @if ($firstVariant)
                                                {{ __('messages.max') }}: {{ $firstVariant->stock }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button type="button" onclick="decrementQuantity()"
                                            class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                            −
                                        </button>
                                        <input type="number" name="quantity" id="quantity" value="1"
                                            min="1"
                                            class="w-20 text-center border-0 bg-transparent text-lg font-semibold">
                                        <button type="button" onclick="incrementQuantity()"
                                            class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-4 pt-4">
                                    @auth
                                        @if ($availableSizes->isNotEmpty())
                                            <button type="submit" name="action" value="add"
                                                class="flex-1 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all hover:scale-105 flex items-center justify-center gap-2">
                                                <i class="fas fa-shopping-cart"></i>
                                                {{ __('messages.add_to_cart') }}
                                            </button>

                                            <button type="submit" name="action" value="buy_now"
                                                class="flex-1 text-gray-900 border border-gray-900 px-8 py-4 rounded-xl font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2">
                                                <i class="fas fa-bolt"></i>
                                                {{ __('messages.buy_now') }}
                                            </button>
                                        @else
                                            <button type="button" disabled
                                                class="flex-1 bg-gray-300 text-gray-500 px-8 py-4 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center gap-2">
                                                <i class="fas fa-times-circle"></i>
                                                {{ __('messages.out_of_stock') }}
                                            </button>
                                        @endif
                                    @else
                                        <button type="button"
                                            onclick="showWarningToast(' {{ __('messages.login_to_purchase') }}')"
                                            class="flex-1 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all hover:scale-105 flex items-center justify-center gap-2">
                                            <i class="fas fa-sign-in-alt"></i>
                                            {{ __('messages.login_to_purchase') }}
                                        </button>
                                    @endauth
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Tabs Navigation -->
                    <div class="mt-8 border-b border-gray-200">
                        <nav class="flex space-x-8">
                            <button onclick="switchTab('details')"
                                class="tab-button py-3 font-medium text-gray-500 hover:text-gray-900 active"
                                data-tab="details">
                                <i class="fas fa-info-circle mr-2"></i>{{ __('messages.product_details') }}
                            </button>
                            <button onclick="switchTab('reviews')"
                                class="tab-button py-3 font-medium text-gray-500 hover:text-gray-900"
                                data-tab="reviews">
                                <i class="fas fa-star mr-2"></i>
                                {{ __('messages.reviews') }} ({{ $product->review_count }})
                            </button>
                            <button onclick="switchTab('specifications')"
                                class="tab-button py-3 font-medium text-gray-500 hover:text-gray-900"
                                data-tab="specifications">
                                <i class="fas fa-list-alt mr-2"></i>{{ __('messages.specifications') }}
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-6">
                        <!-- Details Tab -->
                        <div id="details-tab" class="tab-content active">
                            @if ($product->description)
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-file-alt"></i>
                                        {{ __('messages.product_description') }}
                                    </h3>
                                    <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                                        {{ $product->description }}
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">{{ __('messages.no_description_available') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Reviews Tab -->
                        <div id="reviews-tab" class="tab-content">
                            <div class="space-y-6">
                                <!-- Reviews Summary -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                        <div class="text-center md:text-left">
                                            <div class="text-4xl font-bold text-gray-900 mb-1">
                                                {{ number_format($product->rating_cache, 1) }}
                                            </div>
                                            <div class="flex text-amber-400 justify-center md:justify-start mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($product->rating_cache))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $product->rating_cache)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-gray-600">
                                                {{ __('messages.based_on_reviews', ['count' => $product->review_count]) }}
                                            </p>
                                        </div>

                                        <!-- Review Stats -->
                                        <div class="flex-1 max-w-md">
                                            @php
                                                $ratingDistribution = [
                                                    5 => $product->reviews->where('rating', 5)->count(),
                                                    4 => $product->reviews->where('rating', 4)->count(),
                                                    3 => $product->reviews->where('rating', 3)->count(),
                                                    2 => $product->reviews->where('rating', 2)->count(),
                                                    1 => $product->reviews->where('rating', 1)->count(),
                                                ];
                                            @endphp

                                            @foreach ($ratingDistribution as $stars => $count)
                                                @if ($product->review_count > 0)
                                                    @php
                                                        $percentage = ($count / $product->review_count) * 100;
                                                    @endphp
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <div class="flex items-center w-16">
                                                            <span
                                                                class="text-sm text-gray-600 w-4">{{ $stars }}</span>
                                                            <i class="fas fa-star text-amber-400 ml-1"></i>
                                                        </div>
                                                        <div
                                                            class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                            <div class="h-full bg-amber-400 rounded-full"
                                                                style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                        <span class="text-sm text-gray-600 w-10 text-right">
                                                            {{ $count }}
                                                        </span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Review Submission -->
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                        {{ __('messages.please_write_review') }}
                                    </h4>

                                    @auth
                                        @php
                                            $hasReviewed = auth()
                                                ->user()
                                                ->reviews()
                                                ->where('product_id', $product->id)
                                                ->exists();
                                        @endphp

                                        @if (!$hasReviewed)
                                            <form id="reviewForm" action="{{ route('review.submit', $product->slug) }}"
                                                method="POST" class="bg-gray-50 rounded-xl p-6">
                                                @csrf

                                                <!-- Star Rating -->
                                                <div class="mb-6">
                                                    <label
                                                        class="block text-sm font-medium text-gray-900 mb-3">{{ __('messages.your_rating') }}
                                                        <span class="text-red-500">*</span></label>
                                                    <div class="flex items-center space-x-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <button type="button"
                                                                onclick="setRating({{ $i }})"
                                                                class="star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-colors">
                                                                <i class="far fa-star"></i>
                                                            </button>
                                                        @endfor
                                                        <input type="hidden" name="rating" id="rating"
                                                            value="0" required>
                                                        <span id="rating-text" class="ml-3 text-sm text-gray-600">{{ __('messages.select_stars') }}</span>
                                                    </div>
                                                    @error('rating')
                                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Review Title -->
                                                <div class="mb-4">
                                                    <label for="title"
                                                        class="block text-sm font-medium text-gray-900 mb-2">
                                                      {{ __('messages.review_title_placeholder') }} 
                                                    </label>
                                                    <input type="text" name="title" id="title"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                                        placeholder=" {{ __('messages.your_review') }}">
                                                    @error('title')
                                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Review Comment -->
                                                <div class="mb-6">
                                                    <label for="comment"
                                                        class="block text-sm font-medium text-gray-900 mb-2">
                                                         {{ __('messages.your_review') }} <span class="text-red-500">*</span>
                                                    </label>
                                                    <textarea name="comment" id="comment" rows="4" required
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                                        placeholder="Share your experience with this product..."></textarea>
                                                    @error('comment')
                                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit"
                                                    class="bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-all flex items-center gap-2">
                                                    <i class="fas fa-paper-plane"></i>
                                                   {{ __('messages.submit_review') }}
                                                </button>
                                            </form>
                                        @else
                                            <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                                <i class="fas fa-check-circle text-green-500 text-3xl mb-3"></i>
                                                <p class="text-green-800 font-medium"> {{ __('messages.already_reviewed') }}
                                                </p>
                                                <p class="text-green-600 text-sm mt-1"> {{ __('messages.thank_you_for_feedback') }}</p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                                            <i class="fas fa-user-circle text-gray-400 text-3xl mb-3"></i>
                                            <p class="text-gray-800 font-medium">
                                                {{ __('messages.please_login_to_review') }}</p>
                                            <p class="text-gray-600 text-sm mt-1">
                                                {{ __('messages.login_to_review_message') }}</p>
                                            <a href="{{ route('login') }}"
                                                class="inline-block mt-3 text-gray-900 font-medium hover:text-gray-700">
                                                {{ __('messages.login_to_review') }}
                                            </a>
                                        </div>
                                    @endauth
                                </div>

                                <!-- Customer Reviews List -->
                                @if (isset($product->reviews) && $product->reviews->count() > 0)
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                                            {{ __('messages.customer_reviews') }}
                                        </h4>

                                        <div class="space-y-4">
                                            @foreach ($product->reviews->take(5) as $review)
                                                <div class="bg-white border border-gray-200 rounded-xl p-6">
                                                    <div
                                                        class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
                                                        <div class="flex items-center gap-3 mb-2 sm:mb-0">
                                                            <div class="flex text-amber-400">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $review->rating)
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            @if ($review->title)
                                                                <span
                                                                    class="font-medium text-gray-900">{{ $review->title }}</span>
                                                            @endif
                                                        </div>
                                                        <span class="text-sm text-gray-500">
                                                            {{ $review->created_at->format('M d, Y') }}
                                                        </span>
                                                    </div>

                                                    <p class="text-gray-600 mb-4 leading-relaxed">
                                                        {{ $review->comment }}
                                                    </p>

                                                    <div class="flex items-center gap-2">
                                                        @php
                                                            $user = $review->user;
                                                            $hasProfile = false;
                                                        @endphp

                                                        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=Anonymous&background=gray&color=fff&size=80' }}"
                                                            alt="{{ $user->name ?? 'Anonymous' }}"
                                                            class="w-8 h-8 rounded-full object-cover">

                                                        <span class="text-sm text-gray-700">
                                                            {{ $review->user->name ?? 'Anonymous' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($product->review_count > 5)
                                                <div class="text-center pt-4">
                                                    <button onclick="showAllReviews()"
                                                        class="text-gray-900 hover:text-gray-700 font-medium px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                                                        {{ __('messages.load_more_reviews') }}
                                                        ({{ $product->review_count - 5 }}
                                                        {{ __('messages.more_items') }})
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500"> {{ __('messages.no_reviews_yet') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Specifications Tab -->
                        <div id="specifications-tab" class="tab-content">
                            <div class="space-y-6">
                                <!-- Product Specifications -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <i class="fas fa-info-circle"></i>
                                        {{ __('messages.product_specifications') }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @if ($firstVariant && $firstVariant->weight)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-1">Weight</h4>
                                                <p class="text-gray-600 text-sm">{{ $firstVariant->weight }} kg</p>
                                            </div>
                                        @endif


                                        @if ($product->brand)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-1">Brand</h4>
                                                <p class="text-gray-600 text-sm">{{ $product->brand }}</p>
                                            </div>
                                        @endif

                                        @if ($product->material)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-1">Material</h4>
                                                <p class="text-gray-600 text-sm">{{ $product->material }}</p>
                                            </div>
                                        @endif

                                        @if ($product->sku)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-1">SKU</h4>
                                                <p class="text-gray-600 text-sm">{{ $product->sku }}</p>
                                            </div>
                                        @endif

                                        @if ($product->category)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700 mb-1">Category</h4>
                                                <p class="text-gray-600 text-sm">{{ $product->category->name }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Stats -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <i class="fas fa-chart-bar"></i>
                                        {{ __('messages.product_statistics') }}
                                    </h3>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                                            <div class="text-xl font-bold text-gray-900">{{ $product->view_count }}
                                            </div>
                                            <div class="text-xs text-gray-600"> {{ __('messages.views') }}</div>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                                            <div class="text-xl font-bold text-gray-900">{{ $product->review_count }}
                                            </div>
                                            <div class="text-xs text-gray-600"> {{ __('messages.reviews') }}</div>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                                            <div class="text-xl font-bold text-gray-900">
                                                {{ $product->is_featured ? 'Yes' : 'No' }}</div>
                                            <div class="text-xs text-gray-600"> {{ __('messages.featured_product') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(isset($arrivals))
                <!-- New Arrivals Grid -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">{{ __('messages.new_arrivals') }}</h2>
                        <p class="text-gray-500">{{ count($arrivals) }} {{ __('messages.items') }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($arrivals as $arrival)
                            <div
                                class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                                <a href="{{ route('product.view', $arrival->id) }}" class="block">
                                    <!-- Image -->
                                    <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
                                        @php
                                            $mainImage = $arrival->images->first();
                                        @endphp
                                        @if ($mainImage)
                                            <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                                                alt="{{ $arrival->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class="fas fa-tshirt text-6xl text-gray-400"></i>
                                            </div>
                                        @endif

                                        <!-- Price -->
                                        @php
                                            $minPrice = $arrival->variants->min('price');
                                            $maxPrice = $arrival->variants->max('price');
                                            $minSalePrice = $arrival->variants
                                                ->whereNotNull('sale_price')
                                                ->min('sale_price');
                                            $displayPrice = $minSalePrice ?: $minPrice;
                                        @endphp
                                        <div
                                            class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm">
                                            @if ($minPrice == $maxPrice)
                                                <span
                                                    class="font-semibold text-gray-900">${{ number_format($displayPrice, 2) }}</span>
                                            @else
                                                <span
                                                    class="font-semibold text-gray-900">${{ number_format($displayPrice, 2) }}</span>
                                            @endif
                                            @if ($minSalePrice)
                                                <span class="text-xs text-red-500 line-through ml-1">
                                                    ${{ number_format($minPrice, 2) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Quick Action -->
                                        <div
                                            class="absolute bottom-3 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                            <div
                                                class="bg-white text-gray-900 px-4 py-2 rounded-lg shadow-lg font-medium text-sm">
                                                Quick View
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Info -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">
                                            {{ $arrival->name }}
                                        </h3>
                                        <p class="text-gray-500 text-sm line-clamp-2">
                                            {{ $arrival->short_description ?? $arrival->description }}</p>

                                        <!-- Rating -->
                                        @if ($arrival->rating_cache > 0)
                                            <div class="flex items-center gap-1 mt-2">
                                                <div class="flex text-amber-400 text-xs">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($arrival->rating_cache))
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span
                                                    class="text-xs text-gray-500">({{ $arrival->review_count }})</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-gray-300 mb-4">
                                    <i class="fas fa-tshirt text-6xl"></i>
                                </div>
                                <p class="text-gray-500 text-lg">No new arrivals yet</p>
                                <p class="text-gray-400 text-sm mt-2">Check back soon for new styles!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Related Products -->
            @if (isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">{{ __('messages.you_may_also_like') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $related)
                            <div
                                class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <a href="{{ route('product.view', $related->slug) }}" class="block">
                                    <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
                                        @if ($related->images->count() > 0)
                                            <img src="{{ asset('storage/' . $related->images->first()->image_path) }}"
                                                alt="{{ $related->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @endif

                                        @if ($related->is_new)
                                            <div
                                                class="absolute top-3 left-3 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                                                NEW
                                            </div>
                                        @endif

                                        @if ($related->is_featured)
                                            <div
                                                class="absolute top-3 right-3 bg-amber-500 text-white px-2 py-1 rounded text-xs font-bold">
                                                FEATURED
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                                            {{ $related->name }}
                                        </h3>
                                        <div class="flex items-center justify-between">
                                            @php
                                                $minPrice = $related->variants->min('price');
                                                $minSalePrice = $related->variants
                                                    ->whereNotNull('sale_price')
                                                    ->min('sale_price');
                                                $displayPrice = $minSalePrice ?: $minPrice;
                                            @endphp
                                            <span
                                                class="font-bold text-gray-900">${{ number_format($displayPrice, 2) }}</span>
                                            @if ($minSalePrice)
                                                <span
                                                    class="text-sm text-gray-400 line-through">${{ number_format($minPrice, 2) }}</span>
                                            @endif
                                        </div>

                                        <!-- Rating -->
                                        @if ($related->rating_cache > 0)
                                            <div class="flex items-center gap-1 mt-2">
                                                <div class="flex text-amber-400 text-xs">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($related->rating_cache))
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span
                                                    class="text-xs text-gray-500">({{ $related->review_count }})</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>

    <!-- Size Guide Modal -->
    <div id="sizeGuideModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.size_guide') }}</h3>
                <button onclick="closeSizeGuide()" class="text-gray-400 hover:text-gray-900">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">{{ __('messages.size') }}</th>
                            <th class="px-6 py-3">{{ __('messages.chest') }} (in)</th>
                            <th class="px-6 py-3">{{ __('messages.waist') }} (in)</th>
                            <th class="px-6 py-3">{{ __('messages.hip') }} (in)</th>
                            <th class="px-6 py-3">{{ __('messages.fit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 font-medium">XS</td>
                            <td class="px-6 py-4">32-34</td>
                            <td class="px-6 py-4">26-28</td>
                            <td class="px-6 py-4">34-36</td>
                            <td class="px-6 py-4">{{ __('messages.slim') }}</td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 font-medium">S</td>
                            <td class="px-6 py-4">35-37</td>
                            <td class="px-6 py-4">29-31</td>
                            <td class="px-6 py-4">37-39</td>
                            <td class="px-6 py-4">{{ __('messages.regular') }}</td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 font-medium">M</td>
                            <td class="px-6 py-4">38-40</td>
                            <td class="px-6 py-4">32-34</td>
                            <td class="px-6 py-4">40-42</td>
                            <td class="px-6 py-4">{{ __('messages.regular') }}</td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4 font-medium">L</td>
                            <td class="px-6 py-4">41-43</td>
                            <td class="px-6 py-4">35-37</td>
                            <td class="px-6 py-4">43-45</td>
                            <td class="px-6 py-4">{{ __('messages.regular') }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-6 py-4 font-medium">XL</td>
                            <td class="px-6 py-4">44-46</td>
                            <td class="px-6 py-4">38-40</td>
                            <td class="px-6 py-4">46-48</td>
                            <td class="px-6 py-4">{{ __('messages.loose') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">{{ __('messages.how_to_measure') }}</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><strong>{{ __('messages.chest') }}:</strong> {{ __('messages.chest_measurement') }}</li>
                    <li><strong>{{ __('messages.waist') }}:</strong> {{ __('messages.waist_measurement') }}</li>
                    <li><strong>{{ __('messages.hip') }}:</strong> {{ __('messages.hip_measurement') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching Functionality
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(`${tabName}-tab`).classList.add('active');

            // Add active class to clicked tab button
            event.target.classList.add('active');
        }

        // Star Rating Functionality
        let selectedRating = 0;

        function setRating(rating) {
            selectedRating = rating;
            document.getElementById('rating').value = rating;

            // Update star display
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('far', 'fa-star');
                    icon.classList.add('fas', 'fa-star', 'text-yellow-400');
                } else {
                    icon.classList.remove('fas', 'fa-star', 'text-yellow-400');
                    icon.classList.add('far', 'fa-star');
                }
            });

            // Update rating text
            const ratingText = document.getElementById('rating-text');
            const texts = [
                '{{ __('messages.select_stars') }}',
                '{{ __('messages.poor') }}',
                '{{ __('messages.fair') }}',
                '{{ __('messages.good') }}',
                '{{ __('messages.very_good') }}',
                '{{ __('messages.excellent') }}'
            ];
            ratingText.textContent = texts[rating];
        }

        // Form validation
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            if (selectedRating === 0) {
                e.preventDefault();
                showWarningToast('{{ __('messages.please_select_rating') }}');
                return false;
            }

            const comment = document.getElementById('comment').value.trim();
            if (!comment) {
                e.preventDefault();
                showWarningToast('{{ __('messages.please_write_review') }}');
                return false;
            }

            return true;
        });

        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-img');
        const thumbnails = document.querySelectorAll('.thumbnail-btn');
        const currentSlideElement = document.getElementById('current-slide');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === index);
                slide.classList.toggle('opacity-0', i !== index);
            });

            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle('border-gray-900', i === index);
                thumb.classList.toggle('border-transparent', i !== index);
            });

            currentSlide = index;

            // Update slide counter
            if (currentSlideElement) {
                currentSlideElement.textContent = index + 1;
            }
        }

        function nextSlide() {
            showSlide((currentSlide + 1) % slides.length);
        }

        function prevSlide() {
            showSlide((currentSlide - 1 + slides.length) % slides.length);
        }

        function goToSlide(index) {
            showSlide(index);
        }

        // Color and variant selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            const colorRadios = document.querySelectorAll('.color-radio');
            const priceLabel = document.getElementById('product-price');
            const stockLabel = document.getElementById('stock-available');
            const selectedColorName = document.getElementById('selected-color-name');
            const maxStockLabel = document.getElementById('max-stock-label');
            const productForm = document.getElementById('productForm');

            // Store all variants data
            const allVariants = @json($variantsData);

            // Function to update sizes based on selected color
            function updateSizesForColor(selectedColor) {
                const sizeContainer = document.getElementById('size-container');
                const filteredVariants = allVariants.filter(variant => variant.color === selectedColor);

                if (filteredVariants.length === 0) {
                    sizeContainer.innerHTML =
                        '<div class="col-span-4 text-center py-8">' +
                        '<p class="text-gray-500">{{ __('messages.no_sizes_available') }}</p>' +
                        '</div>';
                    // Reset price and stock display
                    priceLabel.textContent = '{{ __('messages.select_size') }}';
                    stockLabel.innerHTML =
                        '<i class="fas fa-info-circle"></i> {{ __('messages.select_size_to_see_stock') }}';
                    stockLabel.className =
                        'text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 rounded-lg inline-flex items-center gap-2';
                    maxStockLabel.textContent = '';
                    return;
                }

                let sizesHTML = '';
                filteredVariants.forEach((variant, index) => {
                    const price = variant.sale_price || variant.price;
                    const originalPrice = variant.price;
                    const discount = variant.sale_price ? originalPrice - variant.sale_price : 0;
                    const discountPercent = variant.sale_price ? Math.round((discount / originalPrice) *
                        100) : 0;
                    const isChecked = filteredVariants.length === 1 || index === 0;
                    const isLowStock = variant.stock <= 10;
                    const isOutOfStock = variant.stock <= 0;

                    sizesHTML += `
            <label class="relative">
                <input type="radio" name="variant_id" value="${variant.id}"
                    data-price="${price}"
                    data-stock="${variant.stock}"
                    data-size="${variant.size}"
                    data-weight="${variant.weight || ''}"
                    data-dimensions="${variant.dimensions || ''}"
                    class="hidden variant-radio"
                    ${isChecked ? 'checked' : ''}
                    ${isOutOfStock ? 'disabled' : ''}>
                <div class="variant-option border-2 rounded-lg p-3 text-center cursor-pointer transition-all hover:border-gray-900 hover:bg-gray-50
                    ${isOutOfStock ? 'border-gray-100 bg-gray-50 text-gray-400 cursor-not-allowed' : 
                      'border-gray-200 hover:border-gray-900 hover:bg-gray-50'}
                    ${isChecked ? (isOutOfStock ? '' : 'border-gray-900 bg-gray-900 text-white hover:bg-gray-900') : ''}">
                    <span class="font-medium block">${variant.size}</span>
                    ${variant.sale_price ? 
                        `<div class="text-xs font-bold mt-1 ${isChecked ? 'text-red-300' : 'text-red-500'}">
                                        ${'{{ __('messages.save_amount') }}'.replace(':amount', discount.toFixed(2))}
                                    </div>` : ''}
                    ${isLowStock && !isOutOfStock ? 
                        `<div class="text-xs ${isChecked ? 'text-yellow-300' : 'text-yellow-600'} mt-1">
                                        <i class="fas fa-exclamation-circle"></i> ${variant.stock} {{ __('messages.items_in_stock') }}
                                    </div>` : ''}
                    ${isOutOfStock ? 
                        `<div class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-times-circle"></i> {{ __('messages.out_of_stock') }}
                                    </div>` : ''}
                </div>
            </label>
            `;
                });

                sizeContainer.innerHTML = sizesHTML;

                // Update price and stock immediately
                updatePriceAndStock();

                // Re-attach event listeners to new variant radios
                const newVariantRadios = document.querySelectorAll('.variant-radio');
                newVariantRadios.forEach(radio => {
                    radio.addEventListener('change', updatePriceAndStock);
                });
            }

            // Function to update price and stock display
            function updatePriceAndStock() {
                const selectedVariant = document.querySelector('.variant-radio:checked');
                if (!selectedVariant) return;

                const price = selectedVariant.dataset.price;
                const stock = selectedVariant.dataset.stock;
                const weight = selectedVariant.dataset.weight;
                const dimensions = selectedVariant.dataset.size;

                if (price) {
                    priceLabel.textContent = `$${parseFloat(price).toFixed(2)}`;
                }

                if (stock) {
                    stockLabel.innerHTML =
                        `<i class="fas fa-${stock <= 10 ? 'exclamation-triangle' : (stock <= 0 ? 'times-circle' : 'check-circle')}"></i> ${stock} {{ __('messages.items_in_stock') }}`;
                    if (stock <= 10) {
                        stockLabel.className =
                            'text-sm font-medium text-yellow-600 bg-yellow-50 px-3 py-2 rounded-lg inline-flex items-center gap-2';
                    } else if (stock <= 0) {
                        stockLabel.className =
                            'text-sm font-medium text-red-600 bg-red-50 px-3 py-2 rounded-lg inline-flex items-center gap-2';
                    } else {
                        stockLabel.className =
                            'text-sm font-medium text-green-600 bg-green-50 px-3 py-2 rounded-lg inline-flex items-center gap-2';
                    }

                    // Update max stock label
                    maxStockLabel.textContent = `{{ __('messages.max') }}: ${stock}`;
                }

                // Update quantity max
                const quantityInput = document.getElementById('quantity');
                quantityInput.max = stock;

                // Update visual selection
                updateVariantVisualSelection();
            }

            // Function to update variant visual selection
            function updateVariantVisualSelection() {
                const selectedVariant = document.querySelector('.variant-radio:checked');
                if (!selectedVariant) return;

                document.querySelectorAll('.variant-option').forEach(opt => {
                    opt.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
                    const parentRadio = opt.closest('label').querySelector('.variant-radio');
                    if (parentRadio.disabled) {
                        opt.classList.add('border-gray-100', 'bg-gray-50', 'text-gray-400');
                    } else if (parseInt(parentRadio.dataset.stock) <= 10) {
                        opt.classList.add('text-gray-900');
                    } else {
                        opt.classList.add('border-gray-200', 'text-gray-900', 'hover:text-gray-900');
                    }
                });

                if (!selectedVariant.disabled) {
                    selectedVariant.closest('label').querySelector('.variant-option').classList.add(
                        'border-gray-900', 'bg-gray-900', 'text-white'
                    );
                }
            }

            // Add event listeners to color radios
            colorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update active state for colors
                    document.querySelectorAll('.color-option').forEach(opt => {
                        opt.classList.remove('border-gray-900', 'ring-2', 'ring-gray-900',
                            'ring-offset-2');
                        opt.querySelector('.absolute')?.remove();
                    });

                    const colorOption = this.closest('label').querySelector('.color-option');
                    colorOption.classList.add('border-gray-900', 'ring-2', 'ring-gray-900',
                        'ring-offset-2');

                    // Add checkmark
                    const checkmark = document.createElement('div');
                    checkmark.className =
                        'absolute -top-1 -right-1 w-4 h-4 bg-gray-900 rounded-full flex items-center justify-center';
                    checkmark.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
                    colorOption.appendChild(checkmark);

                    // Update selected color name
                    if (selectedColorName) {
                        selectedColorName.textContent = this.value;
                    }

                    // Update sizes for selected color
                    updateSizesForColor(this.value);
                });
            });

            // Event delegation for variant radios
            document.addEventListener('change', function(e) {
                if (e.target.matches('.variant-radio')) {
                    updatePriceAndStock();
                }
            });

            // Ensure variant is selected before form submission
            productForm.addEventListener('submit', function(e) {
                const selectedVariant = document.querySelector('.variant-radio:checked');

                if (!selectedVariant) {
                    e.preventDefault();
                    showWarningToast('{{ __('messages.please_select_size') }}');
                    return false;
                }

                if (selectedVariant.disabled) {
                    e.preventDefault();
                    showWarningToast('{{ __('messages.size_out_of_stock') }}');
                    return false;
                }

                // Make sure the radio button is properly checked
                selectedVariant.checked = true;
                return true;
            });

            // Initial setup
            const initialColor = document.querySelector('.color-radio:checked');
            if (initialColor) {
                // Update sizes for initial color
                updateSizesForColor(initialColor.value);
            }
        });

        // Quantity controls
        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const selectedVariant = document.querySelector('.variant-radio:checked');
            const maxStock = selectedVariant ? parseInt(selectedVariant.dataset.stock) : 1;

            let current = parseInt(quantityInput.value);
            if (current < maxStock) {
                quantityInput.value = current + 1;
            } else {
                showWarningToast(`{{ __('messages.maximum_items') }}`.replace(':count', maxStock));
            }
        }

        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            let current = parseInt(quantityInput.value);
            if (current > 1) {
                quantityInput.value = current - 1;
            }
        }

        // Auto-rotate carousel
        let slideInterval = setInterval(nextSlide, 5000);

        function resetAutoSlide() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Pause on hover
        const carouselContainer = document.getElementById('carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', () => clearInterval(slideInterval));
            carouselContainer.addEventListener('mouseleave', () => slideInterval = setInterval(nextSlide, 5000));
        }

        // Size Guide Modal
        function openSizeGuide() {
            document.getElementById('sizeGuideModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSizeGuide() {
            document.getElementById('sizeGuideModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Share product
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                        title: '{{ $product->name }}',
                        text: '{{ __('messages.check_out_this_product') }}',
                        url: window.location.href,
                    })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing:', error));
            } else {
                // Fallback for browsers that don't support Web Share API
                navigator.clipboard.writeText(window.location.href)
                    .then(() => showSuccessToast('{{ __('messages.link_copied') }}'))
                    .catch(err => showWarningToast('{{ __('messages.failed_to_copy') }}'));
            }
        }

        // Show all reviews
        function showAllReviews() {
            // Implement your logic to show all reviews
            alert('{{ __('messages.this_would_show_all_reviews') }}');
        }

        // Toast Functions
        function showSuccessToast(message) {
            // Check if toast component exists
            if (typeof window.showToast === 'function') {
                window.showToast(message, 'success');
            } else {
                alert('Success: ' + message);
            }
        }

        function showWarningToast(message) {
            // Check if toast component exists
            if (typeof window.showToast === 'function') {
                window.showToast(message, 'warning');
            } else {
                alert('Warning: ' + message);
            }
        }

        // URL parameter handling
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get('added') === 'true') {
                showSuccessToast("{{ __('messages.product_added_to_cart') }}");
                // Clean URL without reloading page
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }

            @if (session('status') === 'added_to_cart')
                showSuccessToast("{{ __('messages.product_added_to_cart') }}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showWarningToast("{{ $error }}");
                @endforeach
            @endif

            @if (session('error'))
                showWarningToast("{{ session('error') }}");
            @endif
        });
    </script>

    @include('partials.footer')
    @stack('styles')
    @stack('scripts')
</body>

</html>
