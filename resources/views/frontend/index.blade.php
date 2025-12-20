@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden h-screen min-h-[700px]">
        @foreach ($carousels as $index => $carousel)
            <div class="hero-slide absolute inset-0 opacity-0 transition-all duration-1000 ease-out {{ $loop->first ? 'active opacity-100 z-10' : 'z-0' }}"
                style="background-image: url('{{ asset('storage/'.$carousel->image_path) }}'); background-size: cover; background-position: center;">

                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-transparent"></div>

                <!-- Content -->
                <div class="relative z-10 flex items-center h-full">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
                        <div class="max-w-2xl ml-auto text-right">
                            <span class="text-white/80 text-sm font-medium tracking-wider mb-4 block">NEW COLLECTION</span>
                            <h1 class="font-bold text-5xl md:text-6xl lg:text-7xl text-white mb-6 leading-tight">
                                {{ $carousel->title }}
                            </h1>
                            <p class="text-xl text-white/80 mb-8 leading-relaxed font-light">
                                {{ $carousel->description }}
                            </p>
                            @if ($carousel->button_text && $carousel->button_link)
                                <a href="{{ $carousel->button_link }}"
                                    class="inline-flex items-center border-2 border-white text-white px-8 py-4 hover:bg-white hover:text-gray-900 transition-all duration-500 text-lg font-medium"
                                    {{ Str::startsWith($carousel->button_link, ['http://', 'https://']) ? 'target=_blank rel=noopener' : '' }}>
                                    {{ $carousel->button_text }}
                                    <i
                                        class="fas fa-arrow-right ml-3 text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Dots Navigation -->
        <div class="absolute bottom-8 right-8 z-20 flex flex-col space-y-3">
            @foreach ($carousels as $index => $carousel)
                <button
                    class="carousel-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-all duration-300 {{ $loop->first ? 'bg-white' : '' }}"
                    data-slide="{{ $index }}"></button>
            @endforeach
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
                        <a href="{{ route('product.view', $arrival->id) }}" class="block">
                            <!-- Image Container -->
                            <div class="relative h-80 overflow-hidden">
                                <!-- Product Image -->
                                <img src="{{ asset($arrival->image) }}" alt="{{ $arrival->name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy">

                                <!-- Price Badge -->
                                <div
                                    class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-gray-900 px-3 py-2 rounded-lg text-sm font-semibold shadow-sm">
                                    ${{ number_format($arrival->sizes->min('price'), 2) }}
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
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-1">{{ $arrival->name }}</h3>
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">{{ $arrival->description }}
                                </p>
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
                <p>At Outfit 818, we believe fashion is more than just clothing — it’s confidence, creativity, and comfort.
                    Our mission is to blend timeless designs with modern trends to create something truly unique for every
                    individual.</p>
                <blockquote>“Dress well. Feel unstoppable.”</blockquote>
            </div>
        </div>
    </section>

    <!-- PRODUCT OF THE DAY -->
    @php
        $featured = \App\Models\FeaturedProduct::where('is_active', true)->latest()->first();
    @endphp

    @if ($featured)
        <section class="relative py-20 overflow-hidden">
            <div class="relative z-10" data-aos="zoom-in-up">
                <div class="max-w-7xl mx-auto px-4 md:px-8">
                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                        <!-- Text Content -->
                        <div class="flex-1 text-white space-y-6">
                            <div
                                class="inline-block bg-[#ffb601] text-white px-6 py-2 rounded-full text-sm font-bold shadow-xl">
                                Product of the Day
                            </div>

                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 leading-tight">
                                {{ $featured->title }}
                            </h1>
                            {{-- 
                            <p class="text-2xl md:text-3xl text-[#ffb601] italic font-light">
                                "{{ $featured->tagline }}"
                            </p> --}}

                            <p class="text-gray-600 text-lg md:text-xl leading-relaxed max-w-2xl">
                                {{ $featured->description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-4 pt-4">
                                <span class="text-4xl md:text-5xl font-bold text-[#ffb601]">
                                    ${{ number_format($featured->discounted_price) }}
                                </span>
                                <span class="text-2xl md:text-3xl text-gray-400 line-through font-medium">
                                    ${{ number_format($featured->original_price) }}
                                </span>
                                <span class="bg-red-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                    -{{ round(100 - ($featured->discounted_price / $featured->original_price) * 100) }}%
                                    OFF
                                </span>
                            </div>

                            @if ($featured->button_text && $featured->button_link)
                                <div class="pt-4">
                                    <a href="{{ $featured->button_link }}"
                                        class="inline-block font-medium border border-gray-300 hover:bg-gray-900 hover:text-white text-gray-900 px-6 py-2 rounded-full text-lg transition-all duration-300 transform hover:scale-105">
                                        {{ $featured->button_text }} →
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Product Image -->
                        <div class="flex-1 w-full max-w-lg">
                            <div class="relative">
                                <img src="{{ asset($featured->image_path) }}" alt="{{ $featured->title }}"
                                    class="relative w-full rounded-3xl shadow-2xl transform hover:scale-105 transition-transform duration-500 border-4 border-white/10">
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
