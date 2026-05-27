<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::prefix('page')->name('client.')->group(function () {
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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

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
            return view('admin.orders.form');
        })->name('create');

        Route::get('/{id}', function () {
            return view('admin.orders.show');
        })->name('show');

        Route::get('/{id}/edit', function () {
            return view('admin.orders.form');
        })->name('edit');
    });
});
