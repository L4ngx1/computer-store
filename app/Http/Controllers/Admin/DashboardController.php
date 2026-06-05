<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\DashboardStats;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardStats $dashboard): View
    {
        $data = $dashboard->get();

        return view('admin.dashboard.index', [
            'counts' => $data['counts'],
            'latestOrders' => $data['latest_orders'],
            'latestProducts' => $data['latest_products'],
            'lowStockProducts' => $data['low_stock_products'],
        ]);
    }
}
