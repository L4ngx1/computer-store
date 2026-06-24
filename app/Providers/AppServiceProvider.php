<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
<<<<<<< HEAD
=======
use Illuminate\Pagination\Paginator;
>>>>>>> origin/main

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
=======
        Paginator::useBootstrapFive();

>>>>>>> origin/main
        // Ensure storage directories exist for product images
        $storagePaths = [
            storage_path('app/public/products/thumbnails'),
            storage_path('app/public/products/images'),
        ];

        foreach ($storagePaths as $path) {
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
        }
    }
}
