@props([
    'product',
    'layout' => 'grid', // 'grid' or 'carousel'
    'showQuickView' => true,
    'showHoverImage' => true,
    'showAvailableSizes' => true,
    'showCategory' => true,
    'showRating' => true,
    'showPriceDetails' => true,
    'showBrand' => true,
])

@php
    // Load variants if not loaded
    if (!$product->relationLoaded('variants')) {
        $product->load('variants');
    }
    
    // Get variant pricing info
    $variantMinPrice = $product->variants->min('price') ?? 0;
    $variantMaxPrice = $product->variants->max('price') ?? 0;
    $variantMinFinalPrice = $product->variants->min('final_price') ?? $variantMinPrice;
    
    // Check if any variant has discount
    $hasVariantDiscount = $product->variants->contains('is_discounted', true);
    
    // Get best discount percentage from variants
    $bestVariantDiscount = $product->variants->max('discount_percentage') ?? 0;
    
    // Calculate display price (use variant final price)
    $displayPrice = $variantMinFinalPrice;
    $isDiscounted = $hasVariantDiscount;
    $discountPercent = $bestVariantDiscount;
    
    // Also check product-level sale (for backward compatibility)
    $isOnSale = $product->IsOnSale();
    $productDiscountPercent = $isOnSale ? round((($product->min_price - $product->sale_price) / $product->min_price) * 100) : 0;
    
    // Use whichever discount is higher
    $bestDiscount = max($discountPercent, $productDiscountPercent);
    $hasAnyDiscount = $isDiscounted || $isOnSale;

    // Layout-specific classes
    $containerClasses = match ($layout) {
        'carousel' => 'rounded-xl overflow-hidden transition-all duration-300 cursor-pointer group flex-shrink-0 w-64 sm:w-72',
        default => 'group rounded-xl overflow-hidden transition-all duration-300 cursor-pointer',
    };

    // Same image container for both layouts
    $imageContainerClasses = 'relative overflow-hidden bg-gray-100 aspect-[3/4]';
@endphp

<div {{ $attributes->merge(['class' => $containerClasses, 'data-product-id' => $product->id]) }}>
    {{-- Image Container --}}
    <a href="{{ route('product.view', ['slug' => $product->slug]) }}" class="block {{ $imageContainerClasses }}">

        @if ($product->images && $product->images->count() > 0)
            {{-- Primary Image --}}
            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                alt="{{ $product->images->first()->alt_text ?? $product->name }}" loading="lazy">

            {{-- Secondary Image (hover) --}}
            @if ($showHoverImage && $product->images->count() > 1)
                <img src="{{ asset('storage/' . $product->images->skip(1)->first()->image_path) }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-700 ease-out"
                    alt="{{ $product->name }}" loading="lazy">
            @endif
        @else
            {{-- Placeholder Image --}}
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        {{-- Price Badge (show for both layouts) --}}
        <div
            class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-gray-900 px-3 py-2 rounded-lg text-sm font-semibold shadow-sm">
            ${{ number_format($displayPrice, 2) }}
            @if ($hasAnyDiscount)
                <span class="text-xs text-red-500 ml-1">{{ __('messages.sale') }}</span>
            @endif
        </div>

        {{-- Top Right Badges Container --}}
        <div class="absolute top-3 right-3 flex flex-col gap-2">
            {{-- Discount Badge --}}
            @if ($bestDiscount > 0)
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">
                    -{{ $bestDiscount }}%
                </div>
            @endif

            {{-- New Badge (only show if no discount or if there's space) --}}
            @if ($product->is_new && $bestDiscount == 0)
                <span class="px-2 py-1 bg-black text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap rounded">
                    {{ __('messages.new') }}
                </span>
            @endif

            {{-- Featured Badge (only show if no discount or if there's space) --}}
            @if ($product->is_featured && $bestDiscount == 0)
                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap rounded">
                    {{ __('messages.featured') }}
                </span>
            @endif
        </div>

        {{-- Secondary Status Badges (shown below if discount exists) --}}
        @if (($product->is_new || $product->is_featured) && $bestDiscount > 0)
            <div class="absolute @if($bestDiscount > 0) top-14 @else top-3 @endif right-3 flex flex-col gap-2">
                @if ($product->is_new)
                    <span class="px-2 py-1 bg-black text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap rounded">
                        {{ __('messages.new') }}
                    </span>
                @endif

                @if ($product->is_featured)
                    <span class="px-2 py-1 bg-blue-600 text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap rounded">
                        {{ __('messages.featured') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Out of Stock Badge (always at bottom right) --}}
        @if ($product->is_out_of_stock)
            <div class="absolute @if($bestDiscount > 0) bottom-14 @else bottom-3 @endif right-3">
                <span class="px-2 py-1 bg-gray-600 text-white text-xs font-bold tracking-widest uppercase whitespace-nowrap rounded">
                    {{ __('messages.out_of_stock') }}
                </span>
            </div>
        @endif

        {{-- Quick View Overlay --}}
        @if ($showQuickView)
            <div
                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-500 flex items-center justify-center">
                <button type="button" onclick="openQuickView({{ $product->id }})"
                    class="quick-view-btn opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0">
                    {{ __('messages.quick_view') }}
                </button>
            </div>
        @endif
    </a>

    {{-- Content --}}
    <div class="p-4 space-y-3 text-left">
        <a href="{{ route('product.view', ['slug' => $product->slug]) }}" class="block">
            <h3
                class="text-base font-semibold text-gray-900 tracking-tight group-hover:text-gray-600 transition-colors line-clamp-2 leading-snug">
                {{ $product->name }}
            </h3>

            @if ($showCategory || $showBrand)
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">
                    @if ($showCategory)
                        {{ $product->category->name ?? __('messages.uncategorized') }}
                    @endif
                    @if ($showBrand && $product->brand)
                        @if ($showCategory)
                            •
                        @endif {{ $product->brand }}
                    @endif
                </p>
            @endif
        </a>

        {{-- Rating --}}
        @if ($showRating && $product->rating_cache > 0)
            <div class="flex items-center gap-2">
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($product->rating_cache))
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @elseif($i == ceil($product->rating_cache) && $product->rating_cache - floor($product->rating_cache) >= 0.5)
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-gray-500">({{ $product->review_count }})</span>
            </div>
        @endif

        {{-- Price with Discount Details --}}
        @if ($showPriceDetails)
            <div class="flex items-center gap-3">
                @if ($hasAnyDiscount)
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-bold text-black tracking-tight">
                            ${{ number_format($displayPrice, 2) }}
                        </span>
                        @if ($variantMinPrice > $displayPrice)
                            <span class="text-sm text-gray-500 line-through">
                                ${{ number_format($variantMinPrice, 2) }}
                            </span>
                        @endif
                        @if ($bestDiscount > 0)
                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">
                                {{ __('messages.save') }} {{ $bestDiscount }}%
                            </span>
                        @endif
                    </div>
                @else
                    @if ($variantMinPrice == $variantMaxPrice)
                        <span class="text-lg font-bold text-black tracking-tight">
                            ${{ number_format($variantMinPrice, 2) }}
                        </span>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 uppercase tracking-widest font-medium">
                                {{ __('messages.from') }}
                            </span>
                            <span class="text-lg font-bold text-black tracking-tight">
                                ${{ number_format($variantMinPrice, 2) }}
                            </span>
                        </div>
                    @endif
                @endif
            </div>
        @endif

        {{-- Variant Options Preview --}}
        @if ($showAvailableSizes && $product->available_sizes && count($product->available_sizes) > 0)
            <div class="flex flex-wrap gap-1.5 mt-2">
                @foreach ($product->available_sizes as $size)
                    <span class="px-2 py-1 text-xs border border-gray-300 rounded text-gray-700">
                        {{ $size }}
                    </span>
                @endforeach

                @if (count($product->available_sizes) > 3)
                    <span class="px-2 py-1 text-xs border border-gray-300 rounded text-gray-700">
                        +{{ count($product->available_sizes) - 3 }} {{ __('messages.more') }}
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>