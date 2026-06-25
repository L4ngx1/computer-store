<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\CartItem;

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
        // Cấu hình sử dụng giao diện Bootstrap 5 cho hệ thống phân trang (Kéo từ main về)
        Paginator::useBootstrapFive();

        // Chia sẻ số lượng sản phẩm trong giỏ hàng cho mọi view (badge giỏ hàng)
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            } else {
                $cartCount = array_sum(session('cart', []));
            }

            $view->with('cartCount', (int) $cartCount);
        });

        // Tự động kiểm tra và tạo các thư mục lưu trữ hình ảnh sản phẩm nếu chưa tồn tại
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