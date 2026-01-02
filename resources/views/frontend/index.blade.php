@extends('layouts.front')

@section('content')
    <!-- Hero Section with Swiper -->
    <section class="relative overflow-hidden h-screen min-h-[700px]">
        <div class="swiper hero-swiper h-full">
            <div class="swiper-wrapper">
                @foreach ($carousels as $index => $carousel)
                    <div class="swiper-slide">
                        <div class="relative h-full w-full"
                            style="background-image: url('{{ asset('storage/' . $carousel->image_path) }}'); background-size: cover; background-position: center;">

                            <!-- Dynamic Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-transparent"></div>

                            <!-- Animated Background Pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <div class="absolute inset-0"
                                    style="background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);">
                                </div>
                            </div>

                            <!-- Content Container -->
                            <div class="relative z-10 flex items-center h-full">
                                <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
                                    <div
                                        class="max-w-3xl {{ $index % 2 == 0 ? 'ml-auto text-right' : 'mr-auto text-left' }}">
                                        <!-- Badge -->
                                        <div class="mb-6 animate-fade-in-down">
                                            <span
                                                class="inline-block bg-white/10 backdrop-blur-sm border border-white/30 text-white text-xs font-semibold tracking-widest px-6 py-2 rounded-full uppercase">
                                                {{ __('messages.new_arrivals') }}
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h1
                                            class="font-bold text-5xl md:text-6xl lg:text-7xl xl:text-8xl text-white mb-6 leading-none tracking-tight animate-fade-in-up">
                                            {{ $carousel->title }}
                                        </h1>

                                        <!-- Description -->
                                        <p
                                            class="text-lg md:text-xl lg:text-2xl text-white/90 mb-10 leading-relaxed font-light max-w-2xl {{ $index % 2 == 0 ? 'ml-auto' : 'mr-auto' }} animate-fade-in-up animation-delay-200">
                                            {{ $carousel->description }}
                                        </p>

                                        <!-- CTA Button -->
                                        @if ($carousel->button_text && $carousel->button_link)
                                            <div class="animate-fade-in-up animation-delay-400">
                                                <a href="{{ $carousel->button_link }}"
                                                    class="group inline-flex items-center bg-white text-gray-900 px-10 py-5 hover:bg-gray-900 hover:text-white transition-all duration-500 text-base md:text-lg font-semibold shadow-2xl hover:shadow-white/20 transform hover:scale-105"
                                                    {{ Str::startsWith($carousel->button_link, ['http://', 'https://']) ? 'target=_blank rel=noopener' : '' }}>
                                                    <span>{{ $carousel->button_text }}</span>
                                                    <i
                                                        class="fas fa-arrow-right ml-3 text-sm transition-transform duration-300 group-hover:translate-x-2"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Slide Number Indicator -->
                            <div class="absolute top-8 left-8 z-20 text-white/60 font-light text-sm tracking-widest">
                                <span class="text-2xl font-bold text-white">0{{ $index + 1 }}</span> /
                                0{{ count($carousels) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Pagination -->
            <div class="swiper-pagination !bottom-8 !right-8 !left-auto !w-auto flex flex-col gap-3"></div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
            <div class="flex flex-col items-center text-white/70">
                <span class="text-xs tracking-widest mb-2 uppercase">{{ __('messages.scroll') }}</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </div>
        </div>
    </section>

    <br>

    {{-- Feature --}}
    <section class="featured-categories py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">
                    {{ __('messages.explore_collections') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('messages.discover_collections') }}
                </p>
            </div>

            <!-- Categories List -->
            <div class="space-y-16">
                <!-- Men Collection -->
                <div class="category-item group">
                    <div class="flex flex-col lg:flex-row items-center justify-center gap-8">
                        <!-- Image -->
                        <div class="w-full lg:w-1/3">
                            <div class="overflow-hidden rounded-2xl">
                                <img src="https://static.zara.net/assets/public/271d/8f90/93384415b608/d29c5bb69e67/image-web-e23758c2-4305-42d5-bfed-2911b6f732d8-default/image-web-e23758c2-4305-42d5-bfed-2911b6f732d8-default.jpg?ts=1761911121683&w=1888"
                                    alt="Men Collection"
                                    class="w-full h-64 lg:h-80 object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy" data-aos="fade-right">
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="w-full lg:w-1/2 lg:pl-8">
                            <div class="text-center lg:text-left" data-aos="fade-left">
                                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                                    {{ __('messages.men_collection') }}
                                </h3>
                                <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                                    {{ __('messages.men_description') }}
                                </p>
                                <a href="/men"
                                    class="inline-flex items-center text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-900 transition-all duration-300 pb-1">
                                    {{ __('messages.shop') }}
                                    <i
                                        class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Women Collection -->
                <div class="category-item group">
                    <div class="flex flex-col lg:flex-row items-center justify-center gap-8">
                        <!-- Content -->
                        <div class="w-full lg:w-1/2 order-2 lg:order-1 lg:pr-8" data-aos="fade-right">
                            <div class="text-center lg:text-left">
                                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                                    {{ __('messages.women_collection') }}
                                </h3>
                                <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                                    {{ __('messages.women_description') }}
                                </p>
                                <a href="/women"
                                    class="inline-flex items-center text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-900 transition-all duration-300 pb-1">
                                    {{ __('messages.shop') }}
                                    <i
                                        class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="w-full lg:w-1/3 order-1 lg:order-2">
                            <div class="overflow-hidden rounded-2xl" data-aos="fade-left">
                                <img src="https://static.zara.net/assets/public/7f8f/31e9/c6f54de7b828/1743439b03be/04813857800-h1/04813857800-h1.jpg?ts=1761899542478&w=1888"
                                    alt="Women Collection"
                                    class="w-full h-64 lg:h-80 object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kids Collection -->
                <div class="category-item group">
                    <div class="flex flex-col lg:flex-row items-center justify-center gap-8">
                        <!-- Image -->
                        <div class="w-full lg:w-1/3">
                            <div class="overflow-hidden rounded-2xl">
                                <img src="https://static.zara.net/assets/public/b87f/4ffa/d91b42b4b2bc/55726691dfde/image-web-5-204e051f-8609-4829-929b-91db4c71ee24-default/image-web-5-204e051f-8609-4829-929b-91db4c71ee24-default.jpg?ts=1761740019541&w=1888"
                                    alt="Kids Collection"
                                    class="w-full h-64 lg:h-80 object-cover transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy" data-aos="fade-right">
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="w-full lg:w-1/2 lg:pl-8">
                            <div class="text-center lg:text-left" data-aos="fade-left">
                                <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                                    {{ __('messages.kids_collection') }}
                                </h3>
                                <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                                    {{ __('messages.kids_description') }}
                                </p>
                                <a href="/kids"
                                    class="inline-flex items-center text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-900 transition-all duration-300 pb-1">
                                    {{ __('messages.shop') }}
                                    <i
                                        class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View All Button -->
            <div class="text-center mt-12">
                <a href="/products"
                    class="inline-flex items-center px-8 py-3 border border-gray-300 text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-900 hover:text-white hover:border-gray-400 transition-all duration-300">
                    {{ __('messages.view_all_products') }}
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </section>
    <br>

    <!-- New Arrivals -->
    @if ($arrivals && $arrivals->count() > 0)
    <section class="new-arrivals" data-aos="fade-left" id="NewArrivals">
        <h2 style="font-weight: 900;">{{ __('messages.new_arrivals_title') }}</h2>

        <div class="carousel-wrapper">
            <button class="carousel-btn prev" id="new-arrivals-prev">&#10094;</button>

            <div class="carousel-track" id="new-arrivals-carousel">
                @forelse ($arrivals as $arrival)
                    <x-product-card :product="$arrival" layout="carousel" />
                @empty
                    <div class="col-span-full text-center py-12 w-full">
                        <!-- Empty state remains the same -->
                    </div>
                @endforelse
            </div>

            <button class="carousel-btn next" id="new-arrivals-next">&#10095;</button>
        </div>
    </section>
    @endif

    <!-- About Brand -->
    <section class="about-brand" data-aos="fade-right">
        <div class="about-container">
            <div class="w-64 h-auto">
                 <div
            class="p-5 relative overflow-hidden">
            <!-- Background accent -->
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-Ocean/5 rounded-full blur-xl"></div>

            <div class="flex items-center space-x-4 relative">
                <!-- logo container -->
                <div class="logo-container relative group">
                    <!-- Logo with multiple animations -->
                    <div class="logo-glow relative overflow-hidden p-1.5">
                        <img src="{{ asset('assets/images/logo1.png') }}" alt="Nova Studio"
                            class="object-contain transition-all duration-500 group-hover:scale-105"
                            style="filter: drop-shadow(0 4px 8px rgba(88, 104, 121, 0.15));" 
                            width="500px" height="500px"/>

                        <!-- Subtle shine effect -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full group-hover:animate-shine">
                        </div>
                    </div>

                    <!-- "STUDIO" text with animation -->
                    <div class="absolute -bottom-5 left-1/2 transform -translate-x-1/2 w-full">
                        <div
                            class="studio-text text-center text-[10px] font-semibold uppercase tracking-[0.15em] text-gray-500/80 opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:translate-y-0 translate-y-1">
                            STUDIO
                            <!-- Underline animation -->
                            <div
                                class="h-px bg-gradient-to-r from-transparent via-Ocean/30 to-transparent w-0 group-hover:w-full transition-all duration-700 mx-auto mt-0.5">
                            </div>
                        </div>
                    </div>

                    <!-- Floating particles animation -->
                    <div
                        class="absolute -inset-2 -z-10 opacity-0 group-hover:opacity-30 transition-opacity duration-700">
                        <div class="absolute top-1/4 left-1/4 w-1 h-1 bg-Ocean/30 rounded-full animate-float-1"></div>
                        <div class="absolute top-1/3 right-1/4 w-0.5 h-0.5 bg-Ocean/20 rounded-full animate-float-2">
                        </div>
                    </div>
                </div>
                <!-- Close button with hover animation -->
                <button id="closeSidebar"
                    class="lg:hidden text-gray-500 hover:text-Ocean p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                    <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                </button>
            </div>
        </div>
            </div>
            <div class="about-content">
                <h2 style="font-weight: 900;">{{ __('messages.about_brand') }}</h2>
                <p>{{ __('messages.about_description') }}</p>
                <blockquote>"{{ __('messages.brand_quote') }}"</blockquote>
            </div>
        </div>
    </section>

@if ($featured)
    <section class="relative py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-6 py-2 bg-gray-100 rounded-full mb-4">
                    <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        {{ __('messages.todays_featured') }}
                    </span>
                </div>
                <h2 class="text-4xl font-bold">{{ __('messages.product_of_the_day') }}</h2>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Product Visual -->
                <div class="relative">
                    <!-- Main Image Frame -->
                    <div class="relative">
                        @if ($featured->images->count() > 0)
                            @php
                                // Get primary image or first image
                                $primaryImage = $featured->images->where('is_primary', true)->first() 
                                    ?? $featured->images->sortBy('sort_order')->first();
                                    
                                // Calculate discount
                                $activeVariants = $featured->variants->where('is_active', true)->where('stock', '>', 0);
                                $minPrice = $activeVariants->min('price');
                                $minFinalPrice = $activeVariants->min('final_price');
                                $hasDiscount = $minFinalPrice < $minPrice;
                                $discountPercent = $hasDiscount ? round((($minPrice - $minFinalPrice) / $minPrice) * 100) : 0;
                            @endphp
                            
                            <!-- Discount Ribbon -->
                            @if ($hasDiscount && $discountPercent > 0)
                                <div class="absolute top-0 left-0 z-20">
                                    <div class="relative overflow-hidden">
                                        <div class="bg-gradient-to-r from-red-500 via-red-600 to-red-500 text-white px-6 py-3 text-lg font-bold tracking-wider shadow-xl transform -translate-x-3 -translate-y-2 -rotate-45 origin-top-left"
                                             style="width: 140px; text-align: center;">
                                            <div class="flex items-center justify-center gap-2">
                                                <i class="fas fa-fire text-white text-sm"></i>
                                                -{{ $discountPercent }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Featured Badge -->
                            <div class="absolute top-0 right-0 z-10">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-5 py-2.5 text-sm font-bold tracking-wider shadow-lg rounded-bl-lg">
                                    <i class="fas fa-crown mr-2"></i>
                                    {{ __('messages.featured') }}
                                </div>
                            </div>

                            <div class="relative h-full flex items-center justify-center">
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                    alt="{{ $primaryImage->alt_text ?? $featured->name }}"
                                    class="max-h-full w-auto object-contain" />
                            </div>

                            <!-- Dynamic Color Options -->
                            @php
                                // Get unique colors from variants
                                $uniqueColors = $featured->variants
                                    ->where('is_active', true)
                                    ->where('stock', '>', 0)
                                    ->unique('color')
                                    ->sortBy('color');
                            @endphp
                            
                            @if($uniqueColors->count() > 0)
                                <div class="absolute bottom-8 right-20">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">{{ __('messages.available_in') }}</span>
                                        <div class="flex gap-1">
                                            @foreach($uniqueColors as $variant)
                                                @php
                                                    // Generate background color
                                                    $bgColor = $variant->color_code 
                                                        ? "background-color: {$variant->color_code}" 
                                                        : "background-color: #" . substr(md5($variant->color), 0, 6);
                                                    
                                                    // Add border for light colors - simplified check
                                                    $borderClass = '';
                                                    if ($variant->color_code) {
                                                        // Simple brightness check without helper function
                                                        $hex = str_replace('#', '', $variant->color_code);
                                                        if (strlen($hex) == 3) {
                                                            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
                                                        }
                                                        $r = hexdec(substr($hex, 0, 2));
                                                        $g = hexdec(substr($hex, 2, 2));
                                                        $b = hexdec(substr($hex, 4, 2));
                                                        $brightness = ($r + $g + $b) / 3;
                                                        if ($brightness > 200) { // Light color threshold
                                                            $borderClass = 'border border-gray-300';
                                                        } else {
                                                            $borderClass = 'border-2 border-white';
                                                        }
                                                    } else {
                                                        $borderClass = 'border-2 border-white';
                                                    }
                                                @endphp
                                                <div class="w-6 h-6 rounded-full shadow cursor-pointer hover:scale-110 transition-transform {{ $borderClass }}"
                                                    style="{{ $bgColor }}"
                                                    title="{{ ucfirst($variant->color) }}"
                                                    data-color="{{ $variant->color }}"
                                                    onclick="changeProductColor('{{ $variant->color }}', '{{ $variant->color_code }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="h-80 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-gray-300 text-8xl mb-4">
                                        <i class="fas fa-gem"></i>
                                    </div>
                                    <p class="text-gray-400 text-lg">{{ __('messages.premium_product') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div class="space-y-8">
                    <!-- Product Title -->
                    <div>
                        <h1 class="text-4xl md:text-5xl font-light text-gray-900 leading-tight tracking-tight">
                            {{ $featured->name }}
                        </h1>
                        <div class="h-px w-20 bg-gray-300 mt-4"></div>
                    </div>

                    <!-- Rating -->
                    @if ($featured->rating_cache > 0)
                        <div class="flex items-center gap-4">
                            <div class="flex items-center">
                                <div class="flex text-amber-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $featured->rating_cache ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="ml-3 text-gray-900 font-bold text-lg">
                                    {{ number_format($featured->rating_cache, 1) }}
                                </span>
                            </div>
                            <span class="text-gray-500">•</span>
                            <span class="text-gray-600">{{ $featured->review_count }}
                                {{ __('messages.verified_reviews') }}</span>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="space-y-6">
                        <p class="text-gray-600 text-lg leading-relaxed">
                            {{ $featured->short_description ?? Str::limit($featured->description, 300) }}
                        </p>

                        <!-- Highlights -->
                        <div class="space-y-3">
                            <h4 class="font-medium text-gray-900">{{ __('messages.key_features') }}</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-3 text-gray-600">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                    <span>{{ __('messages.premium_quality') }}</span>
                                </li>
                                <li class="flex items-center gap-3 text-gray-600">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                    <span>{{ __('messages.extended_warranty') }}</span>
                                </li>
                                <li class="flex items-center gap-3 text-gray-600">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                    <span>{{ __('messages.free_delivery') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Pricing -->
                    @if ($featured->variants->count() > 0)
                        @php
                            $activeVariants = $featured->variants->where('is_active', true)->where('stock', '>', 0);
                            
                            // Get min prices from active variants
                            $minPrice = $activeVariants->min('price');
                            $minFinalPrice = $activeVariants->min('final_price');
                            $displayPrice = $minFinalPrice;
                            $originalPrice = $minPrice;
                            $hasDiscount = $minFinalPrice < $minPrice;
                            $discountPercent = $hasDiscount ? round((($minPrice - $minFinalPrice) / $minPrice) * 100) : 0;
                            $savingsAmount = $hasDiscount ? ($minPrice - $minFinalPrice) : 0;
                        @endphp

                        <div class="space-y-4">
                            <div class="flex items-baseline gap-4">
                                <span class="text-5xl font-bold text-gray-900">
                                    ${{ number_format($displayPrice, 2) }}
                                </span>

                                @if ($hasDiscount)
                                    <span class="text-2xl text-gray-400 line-through">
                                        ${{ number_format($originalPrice, 2) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Discount Info -->
                            @if ($hasDiscount)
                                <div class="space-y-3">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-gradient-to-r from-red-50 to-red-100 text-red-700 px-4 py-2 rounded-lg font-bold">
                                            <i class="fas fa-tag mr-2"></i>
                                            SAVE {{ $discountPercent }}%
                                        </div>
                                        <div class="text-lg text-green-600 font-semibold">
                                            <i class="fas fa-piggy-bank mr-2"></i>
                                            Save ${{ number_format($savingsAmount, 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                           
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="space-y-4 pt-4">
                        <div class="flex gap-4">
                            <a href="{{ route('product.view', $featured->slug) }}"
                                class="flex-1 bg-gradient-to-r from-gray-900 to-black text-white px-8 py-4 text-center font-bold text-lg hover:from-gray-800 hover:to-gray-900 transition-all duration-300 transform hover:scale-105 rounded-xl shadow-lg">
                                <i class="fas fa-bolt mr-2"></i>
                                {{ __('messages.purchase_now') }}
                            </a>
                            <button onclick="addToCartFeatured({{ $featured->id }})"
                                class="flex-1 border-2 border-gray-900 text-gray-900 px-8 py-4 font-bold text-lg hover:bg-gray-900 hover:text-white transition-all duration-300 transform hover:scale-105 rounded-xl">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                {{ __('messages.add_to_cart') }}
                            </button>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="grid grid-cols-2 gap-4 pt-8 border-t">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center">
                                <i class="fas fa-truck text-green-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ __('messages.free_shipping') }}</div>
                                <div class="text-xs text-gray-500">{{ __('messages.worldwide') }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ __('messages.secure_payment') }}</div>
                                <div class="text-xs text-gray-500">{{ __('messages.ssl_encrypted') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

    <!-- EMAIL OPT-IN SECTION -->
    <section class="email-optin-full" data-aos="fade-up" id="emails">
        <div class="optin-text">
            <h2>{{ __('messages.get_styled') }}</h2>
            <p>{{ __('messages.subscribe_description') }}</p>
        </div>

        @auth
            @php
                $isSubscribed = \App\Models\NewsletterSubscription::where('email', auth()->user()->email)->exists();
            @endphp

            @if ($isSubscribed)
                <!-- Subscribed State -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mt-4 max-w-3xl mx-auto">
                    <div class="flex items-center justify-between gap-20">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-left">
                                <p class="text-green-800 font-medium">{{ __('messages.subscribed_message') }}</p>
                                <p class="text-green-600 text-sm">{{ __('messages.subscribed_detail') }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="showUnsubscribeModal()"
                            class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            {{ __('messages.unsubscribe') }}
                        </button>
                    </div>
                </div>
            @else
                <!-- Not Subscribed State -->
                <form action="{{ route('emails.subscribe') }}" method="POST" class="optin-form-bottom mt-4">
                    @csrf
                    <div class="flex space-x-3">
                        <input type="email" name="email" value="{{ auth()->user()->email }}" readonly
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed focus:outline-none"
                            required>
                        <button type="button" onclick="showSubscribeModal()"
                            class="px-6 py-3 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors duration-200">
                            {{ __('messages.subscribe') }}
                        </button>
                    </div>
                </form>
            @endif
        @else
            <!-- Guest State -->
            <form class="optin-form-bottom mt-4">
                <div class="flex space-x-3">
                    <input type="email" placeholder="{{ __('messages.enter_email') }}" required disabled
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed focus:outline-none">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors duration-200">
                        {{ __('messages.login_to_subscribe') }}
                    </a>
                </div>
            </form>
        @endauth

        <div id="subscribe-message" class="text-sm mt-3"></div>
    </section>
@endsection

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.hero-swiper', {
            // Swiper Configuration
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 1200,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            loop: true,
            grabCursor: true,
            watchSlidesProgress: true,

            // Navigation
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                renderBullet: function(index, className) {
                    return '<span class="' + className +
                        ' !w-3 !h-3 !bg-white/50 hover:!bg-white !rounded-full !transition-all !duration-300 !mx-0"></span>';
                },
            },

            // Keyboard Control
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },

            // Mouse Wheel
            mousewheel: {
                forceToAxis: true,
                sensitivity: 1,
                releaseOnEdges: true,
            },

            // Parallax Effect
            parallax: true,

            // Events
            on: {
                init: function() {
                    // Add entrance animations
                    this.slides.forEach(slide => {
                        const content = slide.querySelector('.max-w-3xl');
                        if (content) {
                            content.style.opacity = '0';
                            content.style.transform = 'translateY(30px)';
                        }
                    });

                    // Animate first slide
                    const firstSlide = this.slides[this.activeIndex];
                    const firstContent = firstSlide.querySelector('.max-w-3xl');
                    if (firstContent) {
                        setTimeout(() => {
                            firstContent.style.transition = 'all 1s ease-out';
                            firstContent.style.opacity = '1';
                            firstContent.style.transform = 'translateY(0)';
                        }, 300);
                    }
                },
                slideChangeTransitionStart: function() {
                    // Hide content on slide change
                    this.slides.forEach(slide => {
                        const content = slide.querySelector('.max-w-3xl');
                        if (content) {
                            content.style.opacity = '0';
                            content.style.transform = 'translateY(30px)';
                        }
                    });
                },
                slideChangeTransitionEnd: function() {
                    // Show content on new slide
                    const activeSlide = this.slides[this.activeIndex];
                    const activeContent = activeSlide.querySelector('.max-w-3xl');
                    if (activeContent) {
                        activeContent.style.transition = 'all 1s ease-out';
                        activeContent.style.opacity = '1';
                        activeContent.style.transform = 'translateY(0)';
                    }
                }
            }
        });

        // Pause autoplay on hover
        const swiperContainer = document.querySelector('.hero-swiper');
        swiperContainer.addEventListener('mouseenter', () => {
            swiper.autoplay.stop();
        });
        swiperContainer.addEventListener('mouseleave', () => {
            swiper.autoplay.start();
        });
    });
</script>

<style>
    /* Custom Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 1s ease-out forwards;
    }

    .animate-fade-in-up {
        animation: fadeInUp 1s ease-out forwards;
    }

    .animation-delay-200 {
        animation-delay: 0.2s;
        opacity: 0;
    }

    .animation-delay-400 {
        animation-delay: 0.4s;
        opacity: 0;
    }

    /* Swiper Custom Styles */
    .swiper-pagination-bullet-active {
        background: white !important;
        height: 2rem !important;
    }

    .swiper-button-prev:after,
    .swiper-button-next:after {
        font-weight: 900;
    }

    /* Loading State */
    .swiper-slide img {
        object-fit: cover;
    }

    /* Smooth Transitions */
    .hero-swiper {
        will-change: transform;
    }

    .swiper-slide {
        will-change: opacity;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {

        .swiper-button-prev,
        .swiper-button-next {
            width: 3rem !important;
            height: 3rem !important;
        }

        .swiper-button-prev {
            left: 1rem !important;
        }

        .swiper-button-next {
            right: 1rem !important;
        }

        .swiper-pagination {
            bottom: 1.5rem !important;
            right: 1.5rem !important;
        }
    }
</style>
