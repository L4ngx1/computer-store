<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Client\CartController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::post('logout', [AuthController::class, 'logout'])->middleware('api_token')->name('api.logout');
Route::get('me', [AuthController::class, 'me'])->middleware('api_token')->name('me');
Route::patch('me', [AuthController::class, 'updateProfile'])->middleware('api_token')->name('me.update');
Route::patch('me/password', [AuthController::class, 'changePassword'])->middleware('api_token')->name('me.password');

Route::get('categories', [ClientController::class, 'categories'])->name('categories.index');
Route::get('brands', [ClientController::class, 'brands'])->name('brands.index');
Route::get('products', [ClientController::class, 'products'])->name('products.index');
Route::get('products/featured', [ClientController::class, 'featured'])->name('products.featured');
Route::get('products/{product:slug}', [ClientController::class, 'show'])->name('products.show');

Route::prefix('cart')->name('cart.')->middleware('api_token')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/sync', 'sync')->name('sync');
    Route::post('/items', 'add')->name('items.add');
    Route::patch('/items/{productId}', 'update')->name('items.update');
    Route::delete('/items/{productId}', 'remove')->name('items.remove');
    Route::delete('/clear', 'clear')->name('clear');
});

Route::prefix('admin')->name('api.admin.')->group(function () {
	// ->middleware('is_admin')
	Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/', 'store')->name('store');
		Route::get('{user}', 'show')->name('show');
		Route::put('{user}', 'update')->name('update');
		Route::patch('{user}', 'update')->name('update');
		Route::delete('{user}', 'destroy')->name('destroy');
	});
	Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/', 'store')->name('store');
		Route::get('{category}', 'show')->name('show');
		Route::put('{category}', 'update')->name('update');
		Route::patch('{category}', 'update')->name('update');
		Route::delete('{category}', 'destroy')->name('destroy');
	});
	Route::prefix('brands')->name('brands.')->controller(BrandController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/', 'store')->name('store');
		Route::get('{brand}', 'show')->name('show');
		Route::put('{brand}', 'update')->name('update');
		Route::patch('{brand}', 'update')->name('update');
		Route::delete('{brand}', 'destroy')->name('destroy');
	});
	Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/', 'store')->name('store');
		Route::get('{product}', 'show')->name('show');
		Route::put('{product}', 'update')->name('update');
		Route::patch('{product}', 'update')->name('update');
		Route::delete('{product}', 'destroy')->name('destroy');
	});
	Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::post('/', 'store')->name('store');
		Route::get('{order}', 'show')->name('show');
		Route::put('{order}', 'update')->name('update');
		Route::patch('{order}', 'update')->name('update');
		Route::delete('{order}', 'destroy')->name('destroy');
		Route::patch('{order}/status', 'updateStatus')->name('updateStatus');
	});
});
