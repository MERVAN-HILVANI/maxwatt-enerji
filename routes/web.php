<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Route;

// ANA SAYFA
Route::get('/', [ShopController::class, 'index'])->name('home');

// SHOP ROUTES
Route::get('/urunler', [ShopController::class, 'products'])->name('shop.products');
Route::get('/urun/{id}', [ShopController::class, 'productDetail'])->name('shop.product.detail');
Route::get('/kategori/{id}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/ara', [ShopController::class, 'search'])->name('shop.search');

// MÜŞTERİ GİRİŞ/KAYIT
Route::get('/musteri/giris', [CustomerAuthController::class, 'loginForm'])->name('customer.login');
Route::post('/musteri/giris', [CustomerAuthController::class, 'login'])->name('customer.login.post');
Route::get('/musteri/kayit', [CustomerAuthController::class, 'registerForm'])->name('customer.register');
Route::post('/musteri/kayit', [CustomerAuthController::class, 'register'])->name('customer.register.post');
Route::post('/musteri/cikis', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// E-POSTA DOĞRULAMA
Route::get('/email/verify', function () {
    return view('shop.auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [CustomerAuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/resend', [CustomerAuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// MÜŞTERİ HESABI (giriş gerekli)
Route::middleware(['auth'])->group(function () {
    Route::get('/hesabim', [CustomerAuthController::class, 'profile'])->name('customer.profile');
    Route::put('/hesabim/guncelle', [CustomerAuthController::class, 'updateProfile'])->name('customer.profile.update');

    // Sepet
    Route::get('/sepet', [CartController::class, 'index'])->name('cart.index');
    Route::post('/sepet/ekle/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/sepet/guncelle/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/sepet/sil/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/sepet/temizle', [CartController::class, 'clear'])->name('cart.clear');

    // Siparişler
    Route::get('/siparis/olustur', [OrderController::class, 'create'])->name('order.create');
    Route::post('/siparis/kaydet', [OrderController::class, 'store'])->name('order.store');
    Route::get('/siparislerim', [OrderController::class, 'index'])->name('order.index');
    Route::get('/siparislerim/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/siparis/iptal/{order}', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::post('/siparis/iade/{order}', [OrderController::class, 'refund'])->name('order.refund');
    Route::post('/siparis/dekont/{order}', [OrderController::class, 'uploadReceipt'])->name('order.receipt');

    // Favoriler
    Route::post('/favori/ekle/{product}', [ShopController::class, 'addFavorite'])->name('favorite.add');
    Route::delete('/favori/sil/{product}', [ShopController::class, 'removeFavorite'])->name('favorite.remove');
    Route::get('/favorilerim', [ShopController::class, 'favorites'])->name('favorite.index');

    // Yorumlar
    Route::post('/yorum/ekle/{product}', [ShopController::class, 'addReview'])->name('review.add');
});

// ADMIN GİRİŞ
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});

// ADMIN ROUTES
Route::prefix('/admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('index');
    Route::post('/change-password', [AdminHomeController::class, 'changePassword'])->name('change.password');
    Route::get('/profile', [AdminHomeController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminHomeController::class, 'updateProfile'])->name('profile.update');

    // Product Routes
    Route::prefix('product')->name('product.')->controller(AdminProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{product}', 'show')->name('show');
        Route::get('/edit/{product}', 'edit')->name('edit');
        Route::put('/update/{product}', 'update')->name('update');
        Route::delete('/delete/{product}', 'destroy')->name('destroy');
        Route::delete('/image/{image}', [AdminProductController::class, 'destroyImage'])->name('image.destroy');
        Route::post('/image/upload/{product}', [AdminProductController::class, 'uploadImage'])->name('image.upload');
    });

    // Category Routes
    Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{category}', 'show')->name('show');
        Route::get('/edit/{category}', 'edit')->name('edit');
        Route::put('/update/{category}', 'update')->name('update');
        Route::delete('/delete/{category}', 'destroy')->name('destroy');
    });

    // Order Routes
    Route::prefix('orders')->name('orders.')->controller(AdminOrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::put('/{order}/status', 'updateStatus')->name('status');
        Route::post('/{order}/confirm-payment', 'confirmPayment')->name('confirm.payment');
        Route::post('/{order}/reject-payment', 'rejectPayment')->name('reject.payment');
        Route::get('/refunds/list', 'refunds')->name('refunds');
        Route::put('/refunds/{refund}', 'updateRefund')->name('refund.update');
    });
});
