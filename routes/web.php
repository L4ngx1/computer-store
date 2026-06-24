<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/login', [ClientAuthController::class, 'create'])->name('login.form');
Route::post('/login', [ClientAuthController::class, 'store'])->name('login.store');

Route::get('/register', [RegisterController::class, 'create'])->name('register.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/logout', [ClientAuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| CLIENT PAGE ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('page')->name('client.')->group(function () {

    Route::get('about', fn() => view('client.about'))->name('about');
    Route::get('contact', fn() => view('client.contact'))->name('contact');
    Route::get('faq', fn() => view('client.faq'))->name('faq');

    Route::get('account', [ProfileController::class, 'show'])->name('account');
    Route::put('account', [ProfileController::class, 'update'])->name('account.update');

    Route::get('catalog', fn() => view('client.catalog'))->name('catalog');
    Route::get('product', fn() => view('client.product'))->name('product');
    Route::get('search', fn() => view('client.search'))->name('search');

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

    Route::post('cart/update', [CartController::class, 'update'])->name('cart.update');

    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

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
            Route::get('/{product}', 'show')->name('show');
            Route::get('/{product}/edit', 'edit')->name('edit');
        });

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', fn() => view('admin.categories.index'))->name('index');
            Route::get('/create', fn() => view('admin.categories.form'))->name('create');
            Route::get('/{id}', fn() => view('admin.categories.show'))->name('show');
            Route::get('/{id}/edit', fn() => view('admin.categories.form'))->name('edit');
        });

        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', fn() => view('admin.brands.index'))->name('index');
            Route::get('/create', fn() => view('admin.brands.form'))->name('create');
            Route::get('/{id}', fn() => view('admin.brands.show'))->name('show');
            Route::get('/{id}/edit', fn() => view('admin.brands.form'))->name('edit');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', fn() => view('admin.orders.index'))->name('index');
            Route::get('/create', fn() => redirect()->route('admin.orders.index'))->name('create');
            Route::get('/{id}', fn() => redirect()->route('admin.orders.index'))->name('show');
            Route::get('/{id}/edit', fn() => redirect()->route('admin.orders.index'))->name('edit');
        });
    });
});