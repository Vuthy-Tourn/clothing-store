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
                                            'price' => $variant->price,
                                            'sale_price' => $variant->sale_price,
                                            'stock' => $variant->stock,
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            @endphp

                            <div class="mb-6">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                                <p class="text-gray-500 mb-4">Category: {{ $product->category->name ?? 'N/A' }}</p>

                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        @if ($firstVariant)
                                            <p id="product-price" class="text-2xl font-bold text-gray-900 mb-2">
                                                ${{ number_format($firstVariant->sale_price ?? $firstVariant->price, 2) }}
                                            </p>
                                            <p id="stock-available"
                                                class="text-sm font-medium {{ $firstVariant->stock <= 10 ? 'text-yellow-600 bg-yellow-50' : ($firstVariant->stock <= 0 ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50') }} px-3 py-2 rounded-lg">
                                                {{ $firstVariant->stock }} items in stock
                                            </p>
                                        @else
                                            <p id="product-price" class="text-2xl font-bold text-gray-900 mb-2">
                                                Select Size
                                            </p>
                                            <p id="stock-available"
                                                class="text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                                Please select a size to see stock
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
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Select
                                            Color</label>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach ($availableColors as $colorOption)
                                                <label class="relative">
                                                    <input type="radio" name="color"
                                                        value="{{ $colorOption->color }}" class="hidden color-radio"
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                    <div class="color-option border-2 border-gray-200 rounded-lg p-3 cursor-pointer transition-all hover:border-gray-900 {{ $loop->first ? 'border-gray-900' : '' }}"
                                                        style="background-color: {{ $colorOption->color_code ?? '#e5e7eb' }}; min-width: 60px; height: 60px;">
                                                        <span class="sr-only">{{ $colorOption->color }}</span>
                                                    </div>
                                                    <div class="text-xs text-center mt-1">{{ $colorOption->color }}
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
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">Select Size</label>
                                    <div class="grid grid-cols-4 gap-3" id="size-container">
                                        @foreach ($availableSizes as $variant)
                                            <label class="relative">
                                                <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                                    data-price="{{ $variant->sale_price ?? $variant->price }}"
                                                    data-stock="{{ $variant->stock }}"
                                                    data-size="{{ $variant->size }}" class="hidden variant-radio"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <div
                                                    class="variant-option border-2 border-gray-200 rounded-lg p-3 text-center cursor-pointer transition-all hover:border-gray-900 {{ $loop->first ? 'border-gray-900 bg-gray-900 text-white' : '' }}">
                                                    <span class="font-medium">{{ $variant->size }}</span>
                                                    @if ($variant->sale_price && $variant->sale_price < $variant->price)
                                                        <div class="text-xs text-red-500 mt-1">
                                                            ${{ number_format($variant->sale_price, 2) }}</div>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    @if ($availableSizes->isEmpty())
                                        <p class="text-red-500 text-sm mt-2">No sizes available for this color</p>
                                    @endif
                                    @error('variant_id')
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
                                        @if ($availableSizes->isNotEmpty())
                                            <button type="submit" name="action" value="add"
                                                class="flex-1 bg-gray-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-gray-800 transition-all hover:scale-105">
                                                Add to Cart
                                            </button>

                                            <button type="submit" name="action" value="buy_now"
                                                class="flex-1 text-gray-900 border border-gray-900 px-8 py-4 rounded-xl font-semibold transition-all hover:scale-105">
                                                Buy Now
                                            </button>
                                        @else
                                            <button type="button" disabled
                                                class="flex-1 bg-gray-300 text-gray-500 px-8 py-4 rounded-xl font-semibold cursor-not-allowed">
                                                Out of Stock
                                            </button>
                                        @endif
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
                                        @endphp
                                        <div
                                            class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm">
                                            @if ($minPrice == $maxPrice)
                                                <span
                                                    class="font-semibold text-gray-900">${{ number_format($minPrice, 2) }}</span>
                                            @else
                                                <span
                                                    class="font-semibold text-gray-900">${{ number_format($minPrice, 2) }}
                                                    - ${{ number_format($maxPrice, 2) }}</span>
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
    @php
        $variantsData = $product->variants
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'stock' => $variant->stock,
                ];
            })
            ->values()
            ->toArray();
    @endphp

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

        // Color and variant selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            const colorRadios = document.querySelectorAll('.color-radio');
            const priceLabel = document.getElementById('product-price');
            const stockLabel = document.getElementById('stock-available');
            const productForm = document.getElementById('productForm');

            // Store all variants data
            const allVariants = @json($variantsData);

            // Function to update sizes based on selected color
            function updateSizesForColor(selectedColor) {
                const sizeContainer = document.getElementById('size-container');
                const filteredVariants = allVariants.filter(variant => variant.color === selectedColor);

                if (filteredVariants.length === 0) {
                    sizeContainer.innerHTML =
                        '<p class="text-red-500 text-sm col-span-4">No sizes available for this color</p>';
                    // Reset price and stock display
                    priceLabel.textContent = 'Select Size';
                    stockLabel.textContent = 'Please select a size to see stock';
                    stockLabel.className = 'text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 rounded-lg';
                    return;
                }

                let sizesHTML = '';
                filteredVariants.forEach((variant, index) => {
                    const price = variant.sale_price || variant.price;
                    // For single variant, always check it
                    const isChecked = filteredVariants.length === 1 || index === 0;

                    sizesHTML += `
                <label class="relative">
                    <input type="radio" name="variant_id" value="${variant.id}"
                        data-price="${price}"
                        data-stock="${variant.stock}"
                        data-size="${variant.size}"
                        class="hidden variant-radio"
                        ${isChecked ? 'checked' : ''}>
                    <div class="variant-option border-2 border-gray-200 rounded-lg p-3 text-center cursor-pointer transition-all hover:border-gray-900 ${isChecked ? 'border-gray-900 bg-gray-900 text-white' : ''}">
                        <span class="font-medium">${variant.size}</span>
                        ${variant.sale_price ? `<div class="text-xs text-red-500 mt-1">$${parseFloat(variant.sale_price).toFixed(2)}</div>` : ''}
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

                if (price) {
                    priceLabel.textContent = `$${parseFloat(price).toFixed(2)}`;
                }

                if (stock) {
                    stockLabel.textContent = `${stock} items in stock`;
                    if (stock <= 10) {
                        stockLabel.className =
                            'text-sm font-medium text-yellow-600 bg-yellow-50 px-3 py-2 rounded-lg';
                    } else if (stock <= 0) {
                        stockLabel.className = 'text-sm font-medium text-red-600 bg-red-50 px-3 py-2 rounded-lg';
                    } else {
                        stockLabel.className =
                        'text-sm font-medium text-green-600 bg-green-50 px-3 py-2 rounded-lg';
                    }
                }

                // Update visual selection
                updateVariantVisualSelection();
            }

            // Function to update variant visual selection
            function updateVariantVisualSelection() {
                const selectedVariant = document.querySelector('.variant-radio:checked');
                if (!selectedVariant) return;

                document.querySelectorAll('.variant-option').forEach(opt => {
                    opt.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
                    opt.classList.add('border-gray-200', 'text-gray-900');
                });

                selectedVariant.closest('label').querySelector('.variant-option').classList.add(
                    'border-gray-900', 'bg-gray-900', 'text-white');
            }

            // Add event listeners to color radios
            colorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update active state for colors
                    document.querySelectorAll('.color-option').forEach(opt => {
                        opt.classList.remove('border-gray-900');
                        opt.classList.add('border-gray-200');
                    });
                    this.closest('label').querySelector('.color-option').classList.add(
                        'border-gray-900');

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
                    showWarningToast('Please select a size.');
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
            // Check URL parameters
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get('added') === 'true') {
                showSuccessToast("Product added to cart successfully!");
                // Clean URL without reloading page
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }

            @if (session('status') === 'added_to_cart')
                showSuccessToast("Product added to cart successfully!");
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

        .variant-option {
            transition: all 0.2s ease;
        }

        .color-option {
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
