<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;

Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/login', [ClientAuthController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [ClientAuthController::class, 'store'])->middleware('guest')->name('login.store');
Route::post('/logout', [ClientAuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::name('client.')->group(function () {
    Route::get('about', function () {
        return view('client.about');
    })->name('about');

    Route::get('account', function () {
        return view('client.account');
    })->name('account');

    Route::get('cart', function () {
        return view('client.cart');
    })->name('cart');

    Route::get('catalog', function () {
        return view('client.catalog');
    })->name('catalog');

    Route::get('checkout', function () {
        return view('client.checkout');
    })->name('checkout');

    Route::get('contact', function () {
        return view('client.contact');
    })->name('contact');

    Route::get('faq', function () {
        return view('client.faq');
    })->name('faq');

    Route::get('product', function () {
        return view('client.product');
    })->name('product');

    Route::get('search', function () {
        return view('client.search');
    })->name('search');
});

Route::get('/admin/login', [AdminAuthController::class, 'create'])->middleware('guest')->name('admin.auth.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('guest')->name('admin.auth.login.store');
Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->middleware('auth')->name('admin.auth.logout');

Route::get('/admin', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('admin.auth.login');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () {
            return view('admin.users.index');
        })->name('index');

        Route::get('/create', function () {
            return view('admin.users.form');
        })->name('create');

        Route::get('/{id}', function () {
            return view('admin.users.show');
        })->name('show');

        Route::get('/{id}/edit', function () {
            return view('admin.users.form');
        })->name('edit');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () {
            return view('admin.products.index');
        })->name('index');

        Route::get('/create', function () {
            return view('admin.products.form');
        })->name('create');

        Route::get('/{id}', function () {
            return view('admin.products.show');
        })->name('show');

        Route::get('/{id}/edit', function () {
            return view('admin.products.form');
        })->name('edit');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return view('admin.orders.index');
        })->name('index');

        Route::get('/create', function () {
            return view('admin.orders.index');
        })->name('create');

        Route::get('/{id}', function () {
            return view('admin.orders.index');
        })->name('show');

        Route::get('/{id}/edit', function () {
            return view('admin.orders.index');
        })->name('edit');
    });
});
