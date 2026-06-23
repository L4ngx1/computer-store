<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Support\DashboardStats;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{
    public function index(DashboardStats $dashboard): JsonResponse
    {
        return $this->success(
            $dashboard->get(),
            'Lấy dữ liệu dashboard thành công.'
        );
    }
}
