@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden h-screen min-h-[500px]">
        @foreach ($carousels as $index => $carousel)
            <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-1000 ease-in-out {{ $loop->first ? 'active opacity-100 z-10' : 'z-0' }}"
                style="background-image: url('{{ asset($carousel->image_path) }}'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-black/40"></div>

                <div class="relative z-10 flex flex-col items-center justify-center text-center h-full text-white px-6">
                    <h1 class="font-extrabold text-5xl md:text-6xl mb-4">{{ $carousel->title }}</h1>
                    <p class="max-w-2xl text-lg md:text-xl mb-6">{{ $carousel->description }}</p>

                    @if ($carousel->button_text && $carousel->button_link)
                        <a href="{{ $carousel->button_link }}"
                            class="hero-btn inline-block px-6 py-3 rounded-full font-semibold transition-all duration-300"
                            {{ Str::startsWith($carousel->button_link, ['http://', 'https://']) ? 'target=_blank rel=noopener' : '' }}>
                            {{ $carousel->button_text }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Arrows -->
        <div class="absolute inset-0 flex justify-between items-center px-6 z-20 ">
            <button id="carousel-prev"
                class="text-white text-3xl bg-black/40 hover:bg-black/70 rounded-full p-3 transition">
                &#10094;
            </button>
            <button id="carousel-next"
                class="text-white text-3xl bg-black/40 hover:bg-black/70 rounded-full p-3 transition">
                &#10095;
            </button>
        </div>
    </section>
    <br>

    <section class="featured-categories py-16 px-4 md:px-8 lg:px-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-12">Shop by Category</h2>

        <div class="grid gap-6 grid-cols-[repeat(auto-fit,minmax(220px,1fr))]">
            @forelse ($categories as $category)
                <a href="{{ url($category->slug) }}" class="group relative block overflow-hidden rounded-xl shadow-lg">
                    {{-- Category Image --}}
                    <div class="w-full h-56 sm:h-64 md:h-72 bg-cover transition-transform duration-500 group-hover:scale-105"
                        style="background-image: url('{{ asset($category->image) }}');">
                    </div>

                    {{-- Overlay --}}
                    <div
                        class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl">
                        <h3 class="text-white text-xl sm:text-2xl font-extrabold text-center px-4">{{ $category->name }}
                        </h3>
                    </div>
                </a>
            @empty
                <p class="text-center text-gray-500 col-span-full mt-4">No categories available.</p>
            @endforelse
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
                        class="product-card w-64 bg-white rounded-lg overflow-hidden shadow-sm transition transform hover:-translate-y-2 cursor-pointer">
                        <!-- Image Container -->
                        <div class="relative h-80 overflow-hidden group">
                            <!-- Product Image -->
                            <div class="h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                                style="background-image: url('{{ asset($arrival->image) }}');"></div>

                            <!-- Price Badge -->
                            <span
                                class="absolute top-3 left-3 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                ${{ number_format($arrival->price) }}
                            </span>

                            <!-- Quick View Button on Hover -->
                            <a href="#"
                                class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-semibold opacity-0 group-hover:opacity-100 transition duration-300 shadow hover:bg-gray-100">
                                Shop Now
                            </a>

                            <!-- Overlay on hover -->
                            <div
                                class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-30 transition duration-500">
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4 text-center">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $arrival->name }}</h3>
                            <p class="text-gray-500 text-sm mt-1 truncate">{{ $arrival->description }}</p>
                        </div>
                    </div>

                @empty
                    <p class="text-gray-500 px-4 py-2">No new arrivals yet.</p>
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
                <h2 style="font-weight: 900;">About <span>Outfit 818</span></h2>
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
        <section class="product-of-the-day-grand">
            <div class="product-bg-overlay" data-aos="zoom-in-up">
                <div class="product-content-wrapper">
                    <div class="product-text">
                        <div class="badge">Product of the Day</div>
                        <h1 class="product-title">{{ $featured->title }}</h1>
                        <p class="product-tagline">"{{ $featured->tagline }}"</p>
                        <p class="product-description">{{ $featured->description }}</p>
                        <div class="price-box">
                            <span class="original-price">₹{{ number_format($featured->original_price) }}</span>
                            <span class="discounted-price">₹{{ number_format($featured->discounted_price) }}</span>
                            <span
                                class="discount-badge">-{{ round(100 - ($featured->discounted_price / $featured->original_price) * 100) }}%</span>
                        </div>
                        @if ($featured->button_text && $featured->button_link)
                            <a href="{{ $featured->button_link }}" class="shop-button">
                                {{ $featured->button_text }}
                            </a>
                        @endif
                    </div>
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $featured->image_path) }}" alt="{{ $featured->title }}">
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
                $isSubscribed = \App\Models\Email::where('email', auth()->user()->email)->exists();
            @endphp

            @if ($isSubscribed)
                <div class="bg-green-100 text-green-800 p-4 rounded mt-3 flex items-center justify-between">
                    <span>You are subscribed and will receive notifications via email.</span>

                    <form action="{{ route('emails.unsubscribe') }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to unsubscribe?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ml-4 text-red-600 hover:underline">Unsubscribe</button>
                    </form>
                </div>
            @else
                <form action="{{ route('emails.subscribe') }}" method="POST" class="optin-form-bottom">
                    @csrf
                    <input type="email" name="email" value="{{ auth()->user()->email }}" readonly
                        class="bg-gray-100 cursor-not-allowed" required>
                    <button type="submit">Subscribe</button>
                </form>
            @endif
        @else
            <form class="optin-form-bottom">
                <input type="email" placeholder="Enter your email" required disabled class="cursor-not-allowed">
                <button type="submit" disabled>Login to Subscribe</button>
            </form>
        @endauth

        <div id="subscribe-message" class="text-sm mt-2"></div>

    </section>
@endsection
