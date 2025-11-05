document.addEventListener("DOMContentLoaded", () => {
    // ======================
    // HERO CAROUSEL
    // ======================

    const slides = document.querySelectorAll('.hero-slide');
    const nextBtn = document.getElementById('carousel-next');
    const prevBtn = document.getElementById('carousel-prev');
    let current = 0;
    let slideInterval;

    if (slides.length) {
        // Show slide by index
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active', 'opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0');
            });
            const activeSlide = slides[index];
            activeSlide.classList.add('active', 'opacity-100', 'z-10');
        }

        // Go to next/prev
        function nextSlide() {
            current = (current + 1) % slides.length;
            showSlide(current);
        }

        function prevSlide() {
            current = (current - 1 + slides.length) % slides.length;
            showSlide(current);
        }

        // Start or restart the interval
        function startAutoSlide() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Event listeners with timer reset
        nextBtn?.addEventListener('click', () => {
            nextSlide();
            startAutoSlide();
        });

        prevBtn?.addEventListener('click', () => {
            prevSlide();
            startAutoSlide();
        });

        // Start auto sliding on load
        startAutoSlide();
    }

    // ======================
    // FEATURED CAROUSEL
    // ======================

    const featured_carousel = document.getElementById('new-arrivals-carousel');
    const nextBtn2 = document.getElementById('new-arrivals-next');
    const prevBtn2 = document.getElementById('new-arrivals-prev');

    if (featured_carousel) {
        const getScrollAmount = () => {
            const card = featured_carousel.querySelector('.product-card');
            if (!card) return 0;
            const gap = 24; // 1.5rem
            return card.offsetWidth + gap;
        };

        let currentIndex = 0;
        const cards = featured_carousel.querySelectorAll('.product-card');
        const totalCards = cards.length;

        const visibleCards = Math.floor(
            featured_carousel.offsetWidth / getScrollAmount()
        );

        nextBtn2?.addEventListener('click', () => {
            const scrollAmount = getScrollAmount();
            if (!scrollAmount) return;
            currentIndex++;
            if (currentIndex >= totalCards - visibleCards + 1) {
                currentIndex = 0;
            }
            featured_carousel.scrollTo({
                left: scrollAmount * currentIndex,
                behavior: 'smooth',
            });
        });

        prevBtn2?.addEventListener('click', () => {
            const scrollAmount = getScrollAmount();
            if (!scrollAmount) return;
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = totalCards - visibleCards;
            }
            featured_carousel.scrollTo({
                left: scrollAmount * currentIndex,
                behavior: 'smooth',
            });
        });

        // --- Drag & Swipe support ---
        let isDown = false;
        let startX, scrollLeft;

        featured_carousel.addEventListener('mousedown', (e) => {
            isDown = true;
            featured_carousel.classList.add('dragging');
            startX = e.pageX - featured_carousel.offsetLeft;
            scrollLeft = featured_carousel.scrollLeft;
        });

        featured_carousel.addEventListener('mouseleave', () => {
            isDown = false;
            featured_carousel.classList.remove('dragging');
        });

        featured_carousel.addEventListener('mouseup', () => {
            isDown = false;
            featured_carousel.classList.remove('dragging');
        });

        featured_carousel.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - featured_carousel.offsetLeft;
            const walk = (x - startX) * 1.2;
            featured_carousel.scrollLeft = scrollLeft - walk;
        });

        // --- Touch support ---
        featured_carousel.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - featured_carousel.offsetLeft;
            scrollLeft = featured_carousel.scrollLeft;
        });

        featured_carousel.addEventListener('touchend', () => {
            isDown = false;
        });

        featured_carousel.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - featured_carousel.offsetLeft;
            const walk = (x - startX) * 1.2;
            featured_carousel.scrollLeft = scrollLeft - walk;
        });

        featured_carousel.addEventListener('selectstart', (e) => {
            if (isDown) e.preventDefault();
        });
    }
});
