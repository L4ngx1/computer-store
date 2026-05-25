<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->success([
            'counts' => [
                'orders' => Order::count(),
                'products' => Product::count(),
                'categories' => Category::count(),
                'brands' => Brand::count(),
                'users' => User::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'shipping_orders' => Order::where('status', 'shipping')->count(),
                'completed_orders' => Order::where('status', 'completed')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            ],
            'latest_orders' => Order::with('items.product')->latest()->take(5)->get(),
            'latest_products' => Product::with(['category', 'brand'])->latest()->take(5)->get(),
        ], 'Lấy dữ liệu dashboard thành công.');
    }
}