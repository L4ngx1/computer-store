<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;

Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/login', [ClientAuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [ClientAuthController::class, 'store'])->middleware('guest')->name('login.store');
Route::post('/logout', [ClientAuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::name('client.')->group(function () {
    Route::view('about', 'client.about')->name('about');
    Route::view('account', 'client.account')->name('account');
    Route::view('cart', 'client.cart')->name('cart');
    Route::view('catalog', 'client.catalog')->name('catalog');
    Route::view('checkout', 'client.checkout')->name('checkout');
    Route::view('contact', 'client.contact')->name('contact');
    Route::view('faq', 'client.faq')->name('faq');
    Route::view('product', 'client.product')->name('product');
    Route::view('search', 'client.search')->name('search');
});

Route::get('/admin/login', [AdminAuthController::class, 'create'])->middleware('guest')->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('guest')->name('admin.login.store');
Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->middleware('auth')->name('admin.logout');

Route::get('/admin', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::view('/', 'admin.users.index')->name('index');
        Route::view('/create', 'admin.users.form')->name('create');
        Route::view('/{id}', 'admin.users.show')->name('show');
        Route::view('/{id}/edit', 'admin.users.form')->name('edit');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::view('/', 'admin.products.index')->name('index');
        Route::view('/create', 'admin.products.form')->name('create');
        Route::view('/{id}', 'admin.products.show')->name('show');
        Route::view('/{id}/edit', 'admin.products.form')->name('edit');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::view('/', 'admin.orders.index')->name('index');
        Route::view('/create', 'admin.orders.index')->name('create');
        Route::view('/{id}', 'admin.orders.index')->name('show');
        Route::view('/{id}/edit', 'admin.orders.index')->name('edit');
    });
});
