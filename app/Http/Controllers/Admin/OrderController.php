<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];

    public function index(Request $request): View
    {
        $query = Order::withCount('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_email', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%")
                    ->orWhere('id', $q);
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $statuses = self::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load('items', 'user', 'statusHistories.user');
        $statuses = self::STATUSES;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $previousStatus = $order->status;
        $order->update(['status' => $data['status']]);
        if ($previousStatus !== $data['status']) {
            $order->statusHistories()->create([
                'from_status' => $previousStatus,
                'to_status' => $data['status'],
                'changed_by' => $request->user()?->id,
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã xóa đơn hàng!');
    }
}
