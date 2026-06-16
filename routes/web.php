<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\ProfileController;

Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/login', [ClientAuthController::class, 'create'])->name('login.form');
Route::post('/login', [ClientAuthController::class, 'store'])->name('login.store');

Route::get('/register', [RegisterController::class, 'create'])->name('register.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/logout', [ClientAuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('page')->name('client.')->middleware('auth')->group(function () {
    Route::get('about', function () {
        return view('client.about');
    })->withoutMiddleware('auth')->name('about');

    Route::get('account', [ProfileController::class, 'show'])->name('account');
    Route::put('account', [ProfileController::class, 'update'])->name('account.update');

    Route::get('cart', function () {
        return view('client.cart');
    })->withoutMiddleware('auth')->name('cart');

    Route::get('catalog', function () {
        return view('client.catalog');
    })->withoutMiddleware('auth')->name('catalog');

    Route::get('checkout', function () {
        return view('client.checkout');
    })->name('checkout');

    Route::get('contact', function () {
        return view('client.contact');
    })->withoutMiddleware('auth')->name('contact');

    Route::get('faq', function () {
        return view('client.faq');
    })->withoutMiddleware('auth')->name('faq');

    Route::get('product', function () {
        return view('client.product');
    })->withoutMiddleware('auth')->name('product');

    Route::get('search', function () {
        return view('client.search');
    })->withoutMiddleware('auth')->name('search');
});

// Tạm comment middleware để test
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::middleware('is_admin')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

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

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', function () {
                return view('admin.categories.index');
            })->name('index');

            Route::get('/create', function () {
                return view('admin.categories.form');
            })->name('create');

            Route::get('/{id}', function () {
                return view('admin.categories.show');
            })->name('show');

            Route::get('/{id}/edit', function () {
                return view('admin.categories.form');
            })->name('edit');
        });

        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', function () {
                return view('admin.brands.index');
            })->name('index');

            Route::get('/create', function () {
                return view('admin.brands.form');
            })->name('create');

            Route::get('/{id}', function () {
                return view('admin.brands.show');
            })->name('show');

            Route::get('/{id}/edit', function () {
                return view('admin.brands.form');
            })->name('edit');
        });

        Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{product}', 'show')->name('show');
            Route::get('/{product}/edit', 'edit')->name('edit');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', function () {
                return view('admin.orders.index');
            })->name('index');

            Route::get('/create', function () {
                return redirect()->route('admin.orders.index');
            })->name('create');

            Route::get('/{id}', function () {
                return redirect()->route('admin.orders.index');
            })->name('show');

            Route::get('/{id}/edit', function () {
                return redirect()->route('admin.orders.index');
            })->name('edit');
        });
    });
});
