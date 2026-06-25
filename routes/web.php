<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CatalogController;
use App\Http\Controllers\Client\SearchController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [ClientAuthController::class, 'create'])->name('login.form');
Route::post('/login', [ClientAuthController::class, 'store'])->name('login.store');
Route::get('/register', [RegisterController::class, 'create'])->name('register.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCodeEmail'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::post('/logout', [ClientAuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('page')->name('client.')->group(function () {
    Route::get('about', fn() => view('client.about'))->name('about');
    Route::get('contact', fn() => view('client.contact'))->name('contact');
    Route::get('faq', fn() => view('client.faq'))->name('faq');

    Route::get('account', [ProfileController::class, 'show'])->name('account');
    Route::put('account', [ProfileController::class, 'update'])->name('account.update');

    Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('product/{slug}', [CatalogController::class, 'show'])->name('product');
    Route::get('search', [SearchController::class, 'index'])->name('search');

    // Giỏ hàng: cho phép cả khách (guest) và user đã đăng nhập
    Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout yêu cầu đăng nhập
    Route::middleware('auth')->group(function () {
        Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::middleware('is_admin')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}', 'show')->name('show');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

        Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{product}', 'show')->name('show');
            Route::get('/{product}/edit', 'edit')->name('edit');
            Route::put('/{product}', 'update')->name('update');
            Route::delete('/{product}', 'destroy')->name('destroy');
        });

        Route::prefix('categories')->name('categories.')->controller(AdminCategoryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{category}', 'show')->name('show');
            Route::get('/{category}/edit', 'edit')->name('edit');
            Route::put('/{category}', 'update')->name('update');
            Route::delete('/{category}', 'destroy')->name('destroy');
        });

        Route::prefix('brands')->name('brands.')->controller(AdminBrandController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{brand}', 'show')->name('show');
            Route::get('/{brand}/edit', 'edit')->name('edit');
            Route::put('/{brand}', 'update')->name('update');
            Route::delete('/{brand}', 'destroy')->name('destroy');
        });

        Route::prefix('orders')->name('orders.')->controller(AdminOrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{order}', 'show')->name('show');
            Route::patch('/{order}/status', 'updateStatus')->name('updateStatus');
            Route::delete('/{order}', 'destroy')->name('destroy');
        });

        // API routes for admin product management (AJAX)
        Route::group([
            'prefix' => 'products-api',
            'as' => 'products.api.',
            'controller' => \App\Http\Controllers\Api\Admin\ProductController::class
        ], function () {
            // This is for AJAX from create.blade.php
            Route::post('/', 'store')->name('store');
            Route::patch('/{product}', 'update')->name('update'); // Use PATCH to match the spoofed method from the form
            Route::delete('/{product}', 'destroy')->name('destroy');
        });
    });
});