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
                                                New Collection
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

            <!-- Navigation Arrows -->
            <div
                class="swiper-button-prev !w-16 !h-16 !bg-white/10 backdrop-blur-md !text-white rounded-full hover:!bg-white hover:!text-gray-900 !transition-all !duration-300 after:!text-xl !left-8">
            </div>
            <div
                class="swiper-button-next !w-16 !h-16 !bg-white/10 backdrop-blur-md !text-white rounded-full hover:!bg-white hover:!text-gray-900 !transition-all !duration-300 after:!text-xl !right-8">
            </div>

            <!-- Custom Pagination -->
            <div class="swiper-pagination !bottom-8 !right-8 !left-auto !w-auto flex flex-col gap-3"></div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
            <div class="flex flex-col items-center text-white/70">
                <span class="text-xs tracking-widest mb-2 uppercase">Scroll</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </div>
        </div>
    </section>

    <br>

    {{-- Feature --}}
    <section class="featured-categories py-20 px-4 sm:px-6 lg:px-8 ">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">Explore Collections</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover our carefully curated categories designed for
                    every style and occasion</p>
            </div>

            <!-- Categories List -->
            <div class="space-y-16">
                @forelse ($categories as $index => $category)
                    <div class="category-item group">
                        <!-- For even indexes: Image on left, content on right -->
                        @if ($index % 2 == 0)
                            <div class="flex flex-col lg:flex-row items-center justify-center gap-8">
                                <!-- Image -->
                                <div class="w-full lg:w-1/3">
                                    <div class="overflow-hidden rounded-2xl">
                                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                            class="w-full h-64 lg:h-80 object-cover transition-transform duration-700 group-hover:scale-105"
                                            loading="lazy" data-aos="fade-right">
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="w-full lg:w-1/2 lg:pl-8">
                                    <div class="text-center lg:text-left" data-aos="fade-left">
                                        <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                                            {{ $category->name }}</h3>
                                        <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                                            Discover our exclusive {{ $category->name }} collection featuring the latest
                                            trends and timeless pieces.
                                        </p>
                                        <a href="{{ url($category->slug) }}"
                                            class="inline-flex items-center text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-900 transition-all duration-300 pb-1">
                                            Shop {{ $category->name }}
                                            <i
                                                class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- For odd indexes: Content on left, image on right -->
                        @else
                            <div class="flex flex-col lg:flex-row items-center justify-center gap-8">
                                <!-- Content -->
                                <div class="w-full lg:w-1/2 order-2 lg:order-1 lg:pr-8" data-aos="fade-right">
                                    <div class="text-center lg:text-left">
                                        <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                                            {{ $category->name }}</h3>
                                        <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                                            Explore our premium {{ $category->name }} selection with carefully crafted
                                            pieces for every occasion.
                                        </p>
                                        <a href="{{ url($category->slug) }}"
                                            class="inline-flex items-center text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-900 transition-all duration-300 pb-1">
                                            Shop {{ $category->name }}
                                            <i
                                                class="fas fa-arrow-right ml-2 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="w-full lg:w-1/3 order-1 lg:order-2">
                                    <div class="overflow-hidden rounded-2xl" data-aos="fade-left">
                                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                            class="w-full h-64 lg:h-80 object-cover transition-transform duration-700 group-hover:scale-105"
                                            loading="lazy">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-folder-open text-6xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg">No categories available at the moment.</p>
                        <p class="text-gray-400 text-sm mt-2">Check back soon for new collections</p>
                    </div>
                @endforelse
            </div>

            <!-- View All Button -->
            <div class="text-center mt-12">
                <a href="{{ route('products.all') }}"
                    class="inline-flex items-center px-8 py-3 border border-gray-300 text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-900 hover:text-white hover:border-gray-400 transition-all duration-300">
                    View All Products
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </section>
    <br>

    <!-- New Arrivals -->
    <section class="new-arrivals" data-aos="fade-left" id="NewArrivals">
        <h2 style="font-weight: 900;">New Arrivals</h2>

        <div class="carousel-wrapper">
            <button class="carousel-btn prev" id="new-arrivals-prev">&#10094;</button>

            <div class="carousel-track" id="new-arrivals-carousel">
                @forelse ($arrivals as $arrival)
                    <div
                        class="product-card w-72 bg-white rounded-xl overflow-hidden transition-all duration-300 cursor-pointer group">
                        <a href="{{ route('product.view', $arrival->slug) }}" class="block">
                            <!-- Image Container -->
                            <div class="relative h-80 overflow-hidden">
                                <!-- Product Image -->
                                @if ($arrival->images->count() > 0)
                                    <img src="{{ asset('storage/' . $arrival->images->first()->image_path) }}"
                                        alt="{{ $arrival->images->first()->alt_text ?? $arrival->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                        loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif

                                <!-- Price Badge -->
                                <div
                                    class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-gray-900 px-3 py-2 rounded-lg text-sm font-semibold shadow-sm">
                                    @if ($arrival->variants->count() > 0)
                                        @php
                                            $minPrice = $arrival->variants->min('price');
                                            $minSalePrice = $arrival->variants
                                                ->whereNotNull('sale_price')
                                                ->min('sale_price');
                                            $displayPrice = $minSalePrice ? min($minPrice, $minSalePrice) : $minPrice;
                                        @endphp
                                        ${{ number_format($displayPrice, 2) }}
                                        @if ($minSalePrice && $minSalePrice < $minPrice)
                                            <span class="text-xs text-red-500 ml-1">SALE</span>
                                        @endif
                                    @else
                                        $0.00
                                    @endif
                                </div>

                                <!-- Quick Action Overlay -->
                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-500 flex items-center justify-center">
                                    <span
                                        class="text-white text-sm tracking-widest uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform translate-y-4 group-hover:translate-y-0">
                                        View Details
                                    </span>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-1">{{ $arrival->name }}
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">
                                    {{ $arrival->short_description ?? $arrival->description }}</p>

                                <!-- Rating -->
                                @if ($arrival->rating_cache > 0)
                                    <div class="flex items-center mt-2">
                                        <div class="flex text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($arrival->rating_cache))
                                                    <i class="fas fa-star text-sm"></i>
                                                @elseif($i - 0.5 <= $arrival->rating_cache)
                                                    <i class="fas fa-star-half-alt text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-xs ml-2">({{ $arrival->review_count }})</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg">No new arrivals yet.</p>
                        <p class="text-gray-400 text-sm mt-2">Check back soon for new products!</p>
                    </div>
                @endforelse
            </div>

            <button class="carousel-btn next" id="new-arrivals-next">&#10095;</button>
        </div>
    </section>

    <!-- About Brand -->
    <section class="about-brand" data-aos="fade-right">
        <div class="about-container">
            <div class="about-image">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="200px" height="200px">
            </div>
            <div class="about-content">
                <h2 style="font-weight: 900;">About Outfit 818 </h2>
                <p>At Outfit 818, we believe fashion is more than just clothing — it's confidence, creativity, and comfort.
                    Our mission is to blend timeless designs with modern trends to create something truly unique for every
                    individual.</p>
                <blockquote>"Dress well. Feel unstoppable."</blockquote>
            </div>
        </div>
    </section>

    <!-- PRODUCT OF THE DAY -->
    @if ($featured)
        <section class="relative py-20 overflow-hidden bg-gray-50">
            <div class="relative z-10" data-aos="zoom-in-up">
                <div class="max-w-7xl mx-auto px-4 md:px-8">
                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                        <!-- Text Content -->
                        <div class="flex-1 space-y-6">
                            <div
                                class="inline-block bg-[#ffb601] text-white px-6 py-2 rounded-full text-sm font-bold shadow-xl">
                                Product of the Day
                            </div>

                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 leading-tight">
                                {{ $featured->name }}
                            </h1>

                            <p class="text-gray-600 text-lg md:text-xl leading-relaxed max-w-2xl">
                                {{ $featured->short_description ?? Str::limit($featured->description, 200) }}
                            </p>

                            <div class="flex flex-wrap items-center gap-4 pt-4">
                                @if ($featured->variants->count() > 0)
                                    @php
                                        $minPrice = $featured->variants->min('price');
                                        $minSalePrice = $featured->variants
                                            ->whereNotNull('sale_price')
                                            ->min('sale_price');
                                        $displayPrice = $minSalePrice ? min($minPrice, $minSalePrice) : $minPrice;
                                        $originalPrice = $minPrice;
                                    @endphp

                                    <span class="text-4xl md:text-5xl font-bold text-[#ffb601]">
                                        ${{ number_format($displayPrice, 2) }}
                                    </span>

                                    @if ($minSalePrice && $minSalePrice < $originalPrice)
                                        <span class="text-2xl md:text-3xl text-gray-400 line-through font-medium">
                                            ${{ number_format($originalPrice, 2) }}
                                        </span>
                                        <span
                                            class="bg-red-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                            -{{ round((($originalPrice - $displayPrice) / $originalPrice) * 100) }}% OFF
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <!-- Rating -->
                            @if ($featured->rating_cache > 0)
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($featured->rating_cache))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $featured->rating_cache)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-600 ml-2">{{ number_format($featured->rating_cache, 1) }}
                                        ({{ $featured->review_count }} reviews)</span>
                                </div>
                            @endif

                            <div class="pt-4">
                                <a href="{{ route('product.view', $featured->id) }}"
                                    class="inline-block font-medium border-2 border-gray-900 bg-gray-900 text-white px-8 py-3 rounded-full text-lg hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                                    Shop Now →
                                </a>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="flex-1 w-full max-w-lg">
                            <div class="relative">
                                @if ($featured->images->count() > 0)
                                    <img src="{{ asset('storage/' . $featured->images->first()->image_path) }}"
                                        alt="{{ $featured->images->first()->alt_text ?? $featured->name }}"
                                        class="relative w-full rounded-3xl shadow-lg transform hover:scale-105 transition-transform duration-500 border-4 border-white/10">
                                @else
                                    <div
                                        class="relative w-full h-96 bg-gray-200 rounded-3xl flex items-center justify-center">
                                        <span class="text-gray-400 text-lg">No Image Available</span>
                                    </div>
                                @endif
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
            <h2>Get Styled, Stay Updated.</h2>
            <p>Be the first to know about exclusive drops, latest arrivals, and limited-time offers from Outfit 818.</p>
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
                                <p class="text-green-800 font-medium">You're subscribed to our updates!</p>
                                <p class="text-green-600 text-sm">We'll keep you informed about the latest trends and exclusive
                                    offers.</p>
                            </div>
                        </div>
                        <button type="button" onclick="showUnsubscribeModal()"
                            class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                            Unsubscribe
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
                            Subscribe
                        </button>
                    </div>
                </form>
            @endif
        @else
            <!-- Guest State -->
            <form class="optin-form-bottom mt-4">
                <div class="flex space-x-3">
                    <input type="email" placeholder="Enter your email" required disabled
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed focus:outline-none">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors duration-200">
                        Login to Subscribe
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
