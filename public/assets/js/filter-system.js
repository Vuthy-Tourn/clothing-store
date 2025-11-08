// Reusable filter system for category and product pages
class FilterSystem {
    constructor(options = {}) {
        this.searchInput = options.searchInput;
        this.filterInputs = options.filterInputs;
        this.productsContainer = options.productsContainer;
        this.paginationContainer = options.paginationContainer;
        this.baseUrl = options.baseUrl;
        
        this.searchTimeout = null;
        this.isUpdating = false;
        
        this.init();
    }
    
    init() {
        this.initializeCustomRadios();
        this.initializeEventListeners();
        this.initializePaginationListeners();
    }
    
    // Initialize custom radio buttons
    initializeCustomRadios() {
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
                
                // Update products when filter changes
                this.updateProducts();
            }.bind(this));
        });
    }
    
    // Initialize event listeners
    initializeEventListeners() {
        // Search with debounce
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.updateProducts();
                }, 600);
            });
        }
        
        // Radio buttons - instant update
        if (this.filterInputs) {
            this.filterInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.updateProducts();
                });
            });
        }
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', () => {
            this.updateProducts(window.location.href);
        });
    }
    
    // Initialize pagination event listeners
    initializePaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination-arrow, .pagination-number');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = link.getAttribute('href');
                this.updateProducts(url);
            });
        });
    }
    
    // Function to scroll to top smoothly
    scrollToProductsTop() {
        const productsSection = document.querySelector('section.py-24');
        if (productsSection) {
            const offsetTop = productsSection.offsetTop - 100;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    }
    
    // Function to update products without page reload
    updateProducts(url = null) {
        if (this.isUpdating) return;
        this.isUpdating = true;
        
        // Show loading state
        if (this.productsContainer) {
            this.productsContainer.style.opacity = '0.7';
        }
        if (this.paginationContainer) {
            this.paginationContainer.classList.add('pagination-loading');
        }
        
        // Build URL with current parameters if no specific URL provided
        if (!url) {
            const searchValue = this.searchInput ? this.searchInput.value : '';
            const categoryValue = document.querySelector('input[name="category"]:checked')?.value || '';
            const statusValue = document.querySelector('input[name="status"]:checked')?.value || '';
            const sortValue = document.querySelector('input[name="sort"]:checked')?.value || '';
            const pageValue = new URLSearchParams(window.location.search).get('page') || '';
            
            const params = new URLSearchParams();
            if (searchValue) params.append('search', searchValue);
            if (categoryValue) params.append('category', categoryValue);
            if (statusValue) params.append('status', statusValue);
            if (sortValue) params.append('sort', sortValue);
            if (pageValue) params.append('page', pageValue);
            
            url = `${this.baseUrl}?${params.toString()}`;
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
            
            if (newContent && this.productsContainer) {
                // Smooth transition
                this.productsContainer.style.opacity = '0.5';
                setTimeout(() => {
                    this.productsContainer.innerHTML = newContent.innerHTML;
                    this.productsContainer.style.opacity = '1';
                    
                    // Scroll to top of products section
                    this.scrollToProductsTop();
                    
                    // Re-initialize event listeners for the new pagination
                    this.initializePaginationListeners();
                }, 200);
            }
            this.isUpdating = false;
            
            // Remove loading state
            if (this.paginationContainer) {
                this.paginationContainer.classList.remove('pagination-loading');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.isUpdating = false;
            if (this.productsContainer) {
                this.productsContainer.style.opacity = '1';
            }
            if (this.paginationContainer) {
                this.paginationContainer.classList.remove('pagination-loading');
            }
        });
    }
}