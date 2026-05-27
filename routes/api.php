<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::middleware('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('me');
        Route::patch('me', [AuthController::class, 'updateProfile'])->name('me.update');
        Route::patch('me/password', [AuthController::class, 'changePassword'])->name('me.password');
        Route::get('me/orders', [ClientController::class, 'orders'])->name('me.orders.index');
        Route::get('me/orders/{order}', [ClientController::class, 'order'])->name('me.orders.show');
    });

    Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::apiResource('users', UserController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('orders', OrderController::class);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});

Route::get('home', [ClientController::class, 'home'])->name('home');
Route::get('categories', [ClientController::class, 'categories'])->name('categories.index');
Route::get('brands', [ClientController::class, 'brands'])->name('brands.index');
Route::get('products', [ClientController::class, 'products'])->name('products.index');
Route::get('products/featured', [ClientController::class, 'featured'])->name('products.featured');
Route::get('products/{product:slug}', [ClientController::class, 'show'])->name('products.show');
Route::post('cart/summary', [ClientController::class, 'cartSummary'])->name('cart.summary');
Route::post('checkout', [ClientController::class, 'checkout'])->name('checkout');
Route::get('my-orders', [ClientController::class, 'orders'])->middleware('auth:sanctum')->name('orders.my');
Route::get('my-orders/{order}', [ClientController::class, 'order'])->middleware('auth:sanctum')->name('orders.show');