<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $product->name ?? 'Products' }} - Outfit 818</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('partials.navbar')

    <x-toast />

    <main class="py-8">
        <div class="max-w-6xl mx-auto px-4 mt-5">
            @if (isset($product))
                <!-- Single Product View -->
                <div class=" overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                        <!-- Product Images -->
                        <div class="space-y-4">
                            @php
                                // Filter out null or empty images
                                $images = collect([
                                    $product->image,
                                    $product->image_2 ?? null,
                                    $product->image_3 ?? null,
                                    $product->image_4 ?? null,
                                ])
                                    ->filter(fn($img) => !empty($img) && $img !== 'null')
                                    ->values();
                            @endphp

                            <!-- Main Image -->
                            @if ($images->isNotEmpty())
                                <div class="relative rounded-xl overflow-hidden bg-gray-100 aspect-[4/5]"
                                    id="carousel-container">
                                    @foreach ($images as $key => $img)
                                        <img src="{{ asset($img) }}"
                                            class="carousel-img absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $key === 0 ? 'opacity-100' : 'opacity-0' }}"
                                            alt="{{ $product->name }}">


                                        <!-- Navigation -->
                                        @if (!empty($img) && $img !== 'null' && file_exists(public_path($img)))
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
                                </div>
                            @endif

                            <!-- Thumbnails -->
                            @if ($images->count() > 1)
                                <div class="flex space-x-3" id="carousel-dots">
                                    @foreach ($images as $key => $img)
                                        @if (!empty($img) && $img !== 'null' && file_exists(public_path($img)))
                                            <button onclick="goToSlide({{ $key }})"
                                                class="flex-1 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-gray-300 transition-all thumbnail-btn">
                                                <img src="{{ asset($img) }}" class="w-full h-full object-cover">
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>


                        <!-- Product Info -->
                        <div class="py-4">
                            <div class="mb-6">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                                <p class="text-gray-500 mb-4">Category: {{ $product->category->name ?? 'N/A' }}</p>

                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <p id="product-price" class="text-2xl font-bold text-gray-900 mb-2">
                                            Select Size
                                        </p>
                                        <p id="stock-available"
                                            class="text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                            Please select a size to see stock
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <form id="productForm" action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Size Selection -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">Select Size</label>
                                    <div class="grid grid-cols-4 gap-3">
                                        @foreach ($product->sizes as $index => $size)
                                            <label class="relative">
                                                <input type="radio" name="size" value="{{ $size->size }}"
                                                    data-price="{{ $size->price }}" data-stock="{{ $size->stock }}"
                                                    class="hidden size-radio" {{ $index === 0 ? 'checked' : '' }}>
                                                <div
                                                    class="size-option border-2 border-gray-200 rounded-lg p-3 text-center cursor-pointer transition-all hover:border-gray-900 {{ $index === 0 ? 'border-gray-900 text-white' : '' }}">
                                                    <span class="font-medium text-gray-900">{{ $size->size }}</span>
                                                </div>
                                            </label>
                                        @endforeach

                                    </div>
                                    @error('size')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!-- Quantity -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">Quantity</label>
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

                                <!-- Buttons -->
                                <div class="flex space-x-4 pt-4">
                                    @auth
                                        <button type="submit" name="action" value="add"
                                            class="flex-1 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all hover:scale-105">
                                            Add to Cart
                                        </button>

                                        <button type="submit" name="action" value="buy_now"
                                            class="flex-1 text-gray-900 border border-gray-900 px-8 py-4 rounded-xl font-semibold transition-all hover:scale-105">
                                            Buy Now
                                        </button>
                                    @else
                                        <button type="button"
                                            onclick="showWarningToast('Please log in to purchase this item.')"
                                            class="flex-1 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all hover:scale-105">
                                            Add to Cart
                                        </button>
                                        <button type="button"
                                            onclick="showWarningToast('Please log in to purchase this item.')"
                                            class="flex-1 text-gray-900 border border-gray-900 px-8 py-4 rounded-xl font-semibold transition-all hover:scale-105">
                                            Buy Now
                                        </button>
                                    @endauth
                                </div>
                            </form>


                            <!-- Description -->
                            @if ($product->description)
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Details</h3>
                                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                        {{ $product->description }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif(isset($arrivals))
                <!-- New Arrivals Grid -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">New Arrivals</h2>
                        <p class="text-gray-500">{{ count($arrivals) }} items</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($arrivals as $arrival)
                            <div
                                class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                                <a href="{{ route('product.view', $arrival->id) }}" class="block">
                                    <!-- Image -->
                                    <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
                                        <img src="{{ asset($arrival->image) }}" alt="{{ $arrival->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                        <!-- Price -->
                                        <div
                                            class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm">
                                            <span
                                                class="font-semibold text-gray-900">${{ number_format($arrival->price) }}</span>
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
                                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $arrival->name }}
                                        </h3>
                                        <p class="text-gray-500 text-sm line-clamp-2">{{ $arrival->description }}</p>
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
        </div>
    </main>

    <script>
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-img');
        const thumbnails = document.querySelectorAll('.thumbnail-btn');

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

        // Size selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sizeRadios = document.querySelectorAll('.size-radio');
            const priceLabel = document.getElementById('product-price');
            const stockLabel = document.getElementById('stock-available');

            sizeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update active state
                    document.querySelectorAll('.size-option').forEach(opt => {
                        opt.classList.remove('border-gray-900', 'text-white');
                        opt.classList.add('border-gray-200', 'text-gray-900');
                    });
                    this.closest('label').querySelector('.size-option').classList.add(
                        'border-gray-900', 'text-white');

                    // Update price and stock
                    const price = this.dataset.price;
                    const stock = this.dataset.stock;
                    if (price) priceLabel.textContent = '$' + parseFloat(price).toFixed(2);
                    if (stock) {
                        stockLabel.textContent = `${stock} items in stock`;
                        stockLabel.className =
                            'text-sm font-medium text-green-600 bg-green-50 px-3 py-2 rounded-lg';
                    }
                });
            });

            // Trigger change for the initially checked radio
            const initial = document.querySelector('.size-radio:checked');
            if (initial) initial.dispatchEvent(new Event('change'));
        });


        // Quantity controls
        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
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
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showWarningToast("{{ $error }}");
                @endforeach
            @endif

            @if (session('success'))
                showSuccessToast("{{ session('success') }}");
            @endif

            @if (session('error'))
                showWarningToast("{{ session('error') }}");
            @endif
        });
    </script>



    <style>
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

        .size-option {
            transition: all 0.2s ease;
        }

        .carousel-img {
            transition: opacity 0.5s ease;
        }
    </style>

    @include('partials.footer')
    @stack('styles')
    @stack('scripts')
</body>

</html>
