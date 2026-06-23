<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardStats
{
    public function get(): array
    {
        $lowStockProducts = Product::with(['category', 'brand'])
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->latest()
            ->take(5)
            ->get();

        return [
            'counts' => [
                'orders' => Order::count(),
                'products' => Product::count(),
                'categories' => Category::count(),
                'brands' => Brand::count(),
                'users' => User::count(),
                'customers' => User::where('role', 'customer')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'shipping_orders' => Order::where('status', 'shipping')->count(),
                'completed_orders' => Order::where('status', 'completed')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
                'revenue_today' => (float) $this->completedRevenueToday(),
                'revenue_month' => (float) $this->completedRevenueThisMonth(),
                'low_stock_products' => $lowStockProducts->count(),
            ],
            'latest_orders' => Order::with('items.product')->latest()->take(5)->get(),
            'latest_products' => Product::with(['category', 'brand'])->latest()->take(5)->get(),
            'low_stock_products' => $lowStockProducts,
        ];
    }

    private function completedRevenueToday(): mixed
        {
            return Order::where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_amount');
        }

    private function completedRevenueThisMonth(): mixed
        {
        $now = Carbon::now();

        return Order::where('status', 'completed')
            ->whereBetween('created_at', [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth()
        ])
        ->sum('total_amount');
        }
}
