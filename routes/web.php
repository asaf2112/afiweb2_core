<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\PcBuilderController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ana Sayfa
Route::get('/', [ProductController::class, 'home'])->name('home');

// SEO — Sitemap & Robots
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Ürünler
Route::get('/urunler', [ProductController::class, 'index'])->name('products.index');
Route::get('/api/products/search', [ProductController::class, 'searchApi'])->name('products.search.api');
Route::get('/ikinci-el', [ProductController::class, 'secondHandIndex'])->name('second-hand.index');
Route::get('/kategori/{slug}', [ProductController::class, 'category'])->name('category.show');
Route::get('/urun-detay/{slug}', [ProductController::class, 'show'])->name('products.show');

// Marka Katalogları
Route::get('/markalar', [ProductController::class, 'brandsIndex'])->name('brands.index');
Route::get('/marka/{brand}', [ProductController::class, 'brandShow'])->name('brands.show');

// İletişim Formu (Rate Limit: 5 req/min)
Route::get('/iletisim', function () {
    return view('contact');
})->name('contact');
Route::post('/iletisim', [\App\Http\Controllers\ContactMessageController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::post('/contact', [\App\Http\Controllers\ContactMessageController::class, 'store'])->middleware('throttle:5,1');

// Kategoriler
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Yorum Gönderme (POST - Rate Limit: 5 req/min)
Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])->middleware('throttle:5,1')->name('reviews.store');

// Servis & Destek Talebi (Müşteri - Rate Limitler)
Route::get('/teknik-servis', function () {
    return view('customer.service-request');
})->name('service-request.create');

Route::post('/service-requests', [ServiceRequestController::class, 'store'])->middleware('throttle:5,1')->name('service-requests.store');
Route::post('/service-request', [ServiceRequestController::class, 'store'])->middleware('throttle:5,1')->name('service-request.store');
Route::match(['get', 'post'], '/teknik-servis/takip', [ServiceRequestController::class, 'track'])->middleware('throttle:15,1')->name('service-request.track');
Route::match(['get', 'post'], '/service-request/track', [ServiceRequestController::class, 'track'])->middleware('throttle:15,1');

// PC Toplama Sihirbazı & Donanım Uyumluluk Matrisi Rotaları
Route::get('/pc-toplama', [PcBuilderController::class, 'index'])->name('pc-builder.index');
Route::get('/pc-toplama/parts', [PcBuilderController::class, 'getParts'])->name('pc-builder.parts');
Route::post('/pc-toplama/validate', [PcBuilderController::class, 'validateConfig'])->name('pc-builder.validate');
Route::post('/pc-toplama/add-to-cart', [PcBuilderController::class, 'addToCart'])->name('pc-builder.add-to-cart');

Route::middleware('guest')->group(function () {
    // User Auth & Registration (Rate Limit: 5 req/min)
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->middleware('throttle:5,1')->name('register.post');

    // Socialite (Google)
    Route::get('/auth/google', [\App\Http\Controllers\Auth\RegisterController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\RegisterController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Login (Rate Limit: 5 req/min)
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.post');

    // Password Reset (Rate Limit: 5 req/min)
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'store'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'update'])->middleware('throttle:5,1')->name('password.update');
});

// Sepet Rotaları
Route::get('/sepet', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::get('/api/cart', [\App\Http\Controllers\CartController::class, 'getCartApi'])->name('cart.api');
Route::post('/sepet/ekle/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/sepet/guncelle/{product}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/sepet/kaldir/{product}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

// Ödeme (Checkout) Rotası (Rate Limit: 5 req/min - Carding / brute-force koruması)
Route::get('/odeme', [\App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
Route::post('/odeme', [\App\Http\Controllers\CartController::class, 'processCheckout'])->middleware('throttle:5,1')->name('checkout.process');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Telefon Doğrulama (OTP Guessing ve SMS flood koruması)
    Route::get('/verify-phone', [\App\Http\Controllers\Auth\PhoneVerificationController::class, 'showVerifyForm'])->name('verification.phone');
    Route::post('/verify-phone', [\App\Http\Controllers\Auth\PhoneVerificationController::class, 'verify'])->middleware('throttle:5,1')->name('verification.phone.post');
    Route::post('/verify-phone/resend', [\App\Http\Controllers\Auth\PhoneVerificationController::class, 'resend'])->middleware('throttle:3,1')->name('verification.phone.resend');

    // Siparişlerim
    Route::get('/siparislerim', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/siparislerim/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Favoriler / İstek Listesi (Auth + Guest)
Route::get('/favorilerim', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index']);
Route::post('/favorites/toggle/{product}', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::get('/api/favorites/count', [\App\Http\Controllers\FavoriteController::class, 'count'])->name('favorites.count');

// Ürün Karşılaştırma Aracı Rotaları
Route::get('/karsilastir', [\App\Http\Controllers\CompareController::class, 'index'])->name('compare.index');
Route::post('/karsilastir/toggle/{product}', [\App\Http\Controllers\CompareController::class, 'toggle'])->name('compare.toggle');
Route::post('/karsilastir/clear', [\App\Http\Controllers\CompareController::class, 'clear'])->name('compare.clear');
Route::get('/api/compare/list', [\App\Http\Controllers\CompareController::class, 'apiList'])->name('compare.list.api');

// Canlı Destek & Asistan Chatbot Rotası (Rate Limit: 20 req/min)
Route::post('/api/chatbot/query', [\App\Http\Controllers\ChatbotController::class, 'query'])->middleware('throttle:20,1')->name('chatbot.query');

// Admin Giriş (Misafir - Rate Limit: 5 req/min)
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.post');
});

// Korumalı Admin Rotaları
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Çıkış Yap
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Admin Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Ürün Yönetimi (CRUD)
    Route::post('bulk-discount', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDiscount'])->name('bulk-discount');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/{product}/remove-discount', [\App\Http\Controllers\Admin\ProductController::class, 'removeDiscount'])->name('products.remove_discount');
    Route::post('products/{product}/toggle-flag', [\App\Http\Controllers\Admin\ProductController::class, 'toggleFlag'])->name('products.toggle_flag');
    Route::delete('products/images/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::delete('products/{product}/main-image', [\App\Http\Controllers\Admin\ProductController::class, 'destroyMainImage'])->name('products.main_image.destroy');
    
    // Slider / Banner Yönetimi (CRUD)
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::post('banners/{banner}/toggle-active', [\App\Http\Controllers\Admin\BannerController::class, 'toggleActive'])->name('banners.toggle-active');
    
    // Sipariş Yönetimi
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Kategori Yönetimi
    Route::get('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}/specs', [\App\Http\Controllers\Admin\ProductController::class, 'getCategorySpecs'])->name('categories.specs');

    // Marka Yönetimi (CRUD)
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::post('brands/{brand}/toggle-featured', [\App\Http\Controllers\Admin\BrandController::class, 'toggleFeatured'])->name('brands.toggle-featured');
    Route::post('brands/{brand}/toggle-active', [\App\Http\Controllers\Admin\BrandController::class, 'toggleActive'])->name('brands.toggle-active');
    
    // Genel Arama
    Route::get('/search', [\App\Http\Controllers\Admin\DashboardController::class, 'search'])->name('search');

    // Gelen İletişim Mesajları Yönetimi (Eski Onay Bekleyen Yorumlar Ekranı)
    Route::get('/reviews/pending', [\App\Http\Controllers\ContactMessageController::class, 'index'])->name('reviews.pending');
    Route::get('/contact-messages', [\App\Http\Controllers\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::delete('/contact-messages/{id}', [\App\Http\Controllers\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    Route::post('/contact-messages/{id}/toggle-read', [\App\Http\Controllers\ContactMessageController::class, 'toggleRead'])->name('contact-messages.toggle-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\ContactMessageController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/mark-single-read', [\App\Http\Controllers\ContactMessageController::class, 'markSingleRead'])->name('notifications.mark-single-read');
    
    // Müşteriler (Kullanıcılar) Yönetimi
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show', 'destroy']);
    
    // Servis & Destek Talepleri Yönetimi
    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::match(['post', 'put'], '/service-requests/{id}/status', [ServiceRequestController::class, 'updateStatus'])->name('service-requests.status');
    Route::delete('/service-requests/{id}', [ServiceRequestController::class, 'destroy'])->name('service-requests.destroy');

    // 2FA Yönetimi ve Doğrulama Rotaları (Rate Limit: 5 req/min - Brute-force koruması)
    Route::get('/2fa/verify', [TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->middleware('throttle:5,1')->name('2fa.verify.post');
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/setup', [TwoFactorController::class, 'confirmSetup'])->name('2fa.setup.post');
});
Route::get('/api/subcategories/{category}', function (\App\Models\Category $category) {
    return $category->children;
});
Route::post('/price-alert', [\App\Http\Controllers\PriceAlertController::class, 'store'])->middleware('throttle:10,1')->name('price-alert.store');

Route::middleware('auth')->group(function () {
    Route::get('/bildirimlerim', [\App\Http\Controllers\ProfileController::class, 'notifications'])->name('profile.notifications');
    Route::post('/bildirimlerim/okundu', [\App\Http\Controllers\PriceAlertController::class, 'markAsRead'])->name('profile.notifications.read');
});
