<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductDisplayController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\EmailSubscriptionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\FeaturedProductController;
use App\Http\Controllers\Admin\NewArrivalController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LanguageController;
use App\Models\Product;

// ==================== PUBLIC ROUTES ====================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Contact Us Page
Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');

// Product Routes
Route::get('/products', [ProductDisplayController::class, 'index'])->name('products.all');
Route::get('/product/{slug}', [ProductDisplayController::class, 'view'])->name('product.view');

// Email Subscriptions (Public)
Route::post('/subscribe-email', [EmailSubscriptionController::class, 'store'])->name('email.subscribe');

// Cart Routes (Public)
Route::get('/cart', [CartController::class, 'view'])->name('cart');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Gender Collection
Route::get('/{gender}', [CategoryPageController::class, 'showByGender'])
    ->where('gender', 'men|women|kids')
    ->name('gender.collection');

// ==================== AUTHENTICATION ROUTES ====================

// Guest Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // OTP Verification Routes
    Route::get('/verify-otp', [OtpController::class, 'showVerificationForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::get('/otp/resend', [OtpController::class, 'resend'])
        ->middleware('throttle:2,1')
        ->name('otp.resend');
    
    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ==================== AUTHENTICATED USER ROUTES (BOTH REGULAR USERS AND ADMINS) ====================

Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    // Cart Add (Authenticated only)
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    
    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/verify', [CheckoutController::class, 'verify'])->name('checkout.verify');
    Route::get('/thank-you/{orderId}', [CheckoutController::class, 'thankYou'])->name('checkout.thankyou');
    
    // Order History & Invoices (for both regular users and admins when accessing /orders)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{orderId}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/order/invoice/{orderId}', [CheckoutController::class, 'downloadInvoice'])
        ->name('order.invoice.download');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });
    
    // Email Subscription Management
    Route::post('/subscribe', [EmailSubscriptionController::class, 'store'])->name('emails.subscribe');
    Route::delete('/unsubscribe', [EmailSubscriptionController::class, 'destroy'])->name('emails.unsubscribe');
    
    // Clear Order Success Session
    Route::post('/clear-order-success', function() {
        session()->forget('show_order_success');
        return response()->json(['success' => true]);
    })->name('clear.order.success');

     // Add review route
    Route::post('/product/{product}/review', [App\Http\Controllers\ProductDisplayController::class, 'submitReview'])
        ->name('review.submit');
});

// ==================== ADMIN-ONLY ROUTES ====================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // ========== DASHBOARD API ENDPOINTS ==========
    Route::prefix('api')->group(function () {
        Route::get('/sidebar-stats', [AdminController::class, 'sidebarStats'])->name('api.sidebar-stats');
        Route::get('/dashboard-stats', [AdminController::class, 'dashboardStats'])->name('api.dashboard-stats');
        Route::get('/top-products', [AdminController::class, 'topProducts'])->name('api.top-products');
        Route::get('/sales-chart', [AdminController::class, 'salesChart'])->name('api.sales-chart');
        Route::get('/recent-activity', [AdminController::class, 'recentActivity'])->name('api.recent-activity');
        Route::get('/order-status-distribution', [AdminController::class, 'orderStatusDistribution'])->name('api.order-status-distribution');
        Route::get('/performance-metrics', [AdminController::class, 'performanceMetrics'])->name('api.performance-metrics');
        Route::get('/category-sales', [AdminController::class, 'categorySales'])->name('api.category-sales');
        Route::get('/low-stock-alerts', [AdminController::class, 'lowStockAlerts'])->name('api.low-stock-alerts');
        Route::get('/revenue-comparison', [AdminController::class, 'revenueComparison'])->name('api.revenue-comparison');
    });
    
    // Dashboard API Endpoints (non-prefixed)
    Route::get('/dashboard-stats', [AdminController::class, 'dashboardStats'])->name('dashboard.stats');
    Route::get('/sales-chart', [AdminController::class, 'salesChart'])->name('sales.chart');
    Route::get('/recent-activity', [AdminController::class, 'recentActivity'])->name('recent.activity');
    Route::get('/top-products', [AdminController::class, 'topProducts'])->name('top.products');
    Route::get('/sidebar-stats', [AdminController::class, 'sidebarStats'])->name('sidebar.stats');
    Route::get('/order-status-distribution', [AdminController::class, 'orderStatusDistribution'])->name('order.status');
    Route::get('/performance-metrics', [AdminController::class, 'performanceMetrics'])->name('performance.metrics');
    Route::get('/category-sales', [AdminController::class, 'categorySales'])->name('category.sales');
    Route::get('/low-stock-alerts', [AdminController::class, 'lowStockAlerts'])->name('low.stock');
    Route::get('/revenue-comparison', [AdminController::class, 'revenueComparison'])->name('revenue.comparison');
    
    // ========== CONTENT MANAGEMENT ==========

     // Email Subscribers
    Route::prefix('emails')->name('emails.')->group(function () {
        Route::get('/', [EmailController::class, 'index'])->name('index');
        Route::post('/send', [EmailController::class, 'send'])->name('send');
        Route::post('/test', [EmailController::class, 'sendTest'])->name('test');
        Route::post('/subscribe', [EmailController::class, 'addSubscriber'])->name('subscribe');
        Route::delete('/{email}', [EmailController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [EmailController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export', [EmailController::class, 'export'])->name('export');
        Route::post('/{email}/reactivate', [EmailController::class, 'reactivate'])->name('reactivate');
        Route::get('/statistics', [EmailController::class, 'getStatistics'])->name('statistics');
    });
    
    // Carousels
    Route::prefix('carousels')->name('carousels.')->group(function () {
        Route::get('/', [CarouselController::class, 'index'])->name('index');
        Route::get('/create', [CarouselController::class, 'create'])->name('create');
        Route::post('/', [CarouselController::class, 'store'])->name('store');
        Route::get('/{carousel}/edit', [CarouselController::class, 'edit'])->name('edit');
        Route::put('/{carousel}', [CarouselController::class, 'update'])->name('update');
        Route::delete('/{carousel}', [CarouselController::class, 'destroy'])->name('destroy');
        Route::post('/update-order', [CarouselController::class, 'updateOrder'])->name('update-order');
    });
    
    // Featured Products
    Route::prefix('featured-product')->name('featured.')->group(function () {
        Route::get('/', [FeaturedProductController::class, 'index'])->name('index');
        Route::post('/', [FeaturedProductController::class, 'store'])->name('store');
        Route::put('/{id}', [FeaturedProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [FeaturedProductController::class, 'destroy'])->name('destroy');
    });
    
    // New Arrivals
    Route::prefix('new-arrivals')->name('new-arrivals.')->group(function () {
        Route::get('/', [NewArrivalController::class, 'index'])->name('index');
        Route::post('/', [NewArrivalController::class, 'store'])->name('store');
        Route::put('/{id}', [NewArrivalController::class, 'update'])->name('update');
        Route::delete('/{id}', [NewArrivalController::class, 'destroy'])->name('destroy');
    });
    
    // ========== CATALOG MANAGEMENT ==========
    
    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/update-order', [CategoryController::class, 'updateOrder'])->name('update-order');
    });
    
    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/import', [ProductController::class, 'import'])->name('import');
    });
    
    // ========== ADMIN ORDERS MANAGEMENT (Different from frontend orders) ==========
    
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('invoice');
        Route::get('/{id}/details', [AdminOrderController::class, 'getOrderDetails'])->name('details');
        Route::put('/{id}', [AdminOrderController::class, 'update'])->name('update');
        Route::put('/{id}/payment', [AdminOrderController::class, 'updatePayment'])->name('update.payment');
        Route::put('/{id}/tracking', [AdminOrderController::class, 'updateTracking'])->name('update.tracking');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
        Route::post('/export', [AdminOrderController::class, 'export'])->name('export');
        
        // Order actions
        Route::post('/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/send-tracking', [AdminOrderController::class, 'sendTracking'])->name('send-tracking');
        Route::post('/{id}/add-note', [AdminOrderController::class, 'addNote'])->name('add-note');
        Route::post('/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('cancel');
    });
    
    
    // ========== ADMIN PROFILE MANAGEMENT ==========
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::post('/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/security', [App\Http\Controllers\Admin\ProfileController::class, 'security'])->name('security');
        Route::post('/security', [App\Http\Controllers\Admin\ProfileController::class, 'updateSecurity'])->name('security.update');
        Route::get('/activity', [App\Http\Controllers\Admin\ProfileController::class, 'activity'])->name('activity');
        Route::get('/export', [App\Http\Controllers\Admin\ProfileController::class, 'exportData'])->name('export');
        Route::post('/newsletter/toggle', [App\Http\Controllers\Admin\ProfileController::class, 'toggleNewsletter'])->name('toggle-newsletter');
    });

    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::get('/{user}/edit-form', [UserController::class, 'editForm'])->name('edit-form');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // AJAX Routes
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
    });
    
});

// ==================== TEST ROUTES ====================

// Test Product Form (temporary)
Route::get('/test-product', function() {
    return view('test-product-form');
});

// ==================== CATEGORY ROUTES (MOVE TO BOTTOM) ====================

// Category routes should be at the bottom to avoid conflicts
Route::get('/category/{slug}', [CategoryPageController::class, 'show'])->name('category.show');

// In your web.php routes file
Route::post('/products/{product}/review', [ProductDisplayController::class, 'submitReview'])->name('product.review.submit');
Route::post('/products/{product}/wishlist/toggle', [ProductDisplayController::class, 'toggleWishlist'])->name('product.wishlist.toggle');
Route::get('/products/{product}/reviews', [ProductDisplayController::class, 'getReviews'])->name('product.reviews');

// Language routes
Route::post('/language/set', [LanguageController::class, 'set'])->name('language.set');
Route::post('/language/ajax', [LanguageController::class, 'ajax'])->name('language.ajax');
Route::get('/search', [ProductDisplayController::class, 'search'])->name('products.search');