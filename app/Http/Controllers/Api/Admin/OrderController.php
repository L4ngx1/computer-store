<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with('items.product')->latest()->paginate(15);

        return $this->paginated($orders, 'Lấy danh sách đơn hàng thành công.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'shipping_address' => 'required|string',
            'note' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'status' => 'nullable|in:pending,processing,shipping,completed,cancelled',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.product_name' => 'nullable|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'user_id' => $validated['user_id'] ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'note' => $validated['note'] ?? null,
                'total_amount' => $validated['total_amount'] ?? 0,
                'payment_method' => $validated['payment_method'],
                'status' => $validated['status'] ?? 'pending',
            ]);

            $this->syncItems($order, $validated['items'] ?? []);

            if (! array_key_exists('total_amount', $validated) && ! empty($validated['items'])) {
                $order->update([
                    'total_amount' => collect($validated['items'])->sum(function (array $item) {
                        return ((float) $item['price']) * ((int) $item['quantity']);
                    }),
                ]);
            }

            return $order;
        });

        return $this->success($order->fresh()->load('items.product'), 'Tạo đơn hàng thành công.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->success($order->load('items.product'), 'Lấy chi tiết đơn hàng thành công.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'sometimes|required|string|max:255',
            'customer_email' => 'sometimes|required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'shipping_address' => 'sometimes|required|string',
            'note' => 'nullable|string',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'payment_method' => 'sometimes|required|string|max:50',
            'status' => 'sometimes|required|in:pending,processing,shipping,completed,cancelled',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.product_name' => 'nullable|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $order) {
            $order->update([
                'user_id' => $validated['user_id'] ?? $order->user_id,
                'customer_name' => $validated['customer_name'] ?? $order->customer_name,
                'customer_email' => $validated['customer_email'] ?? $order->customer_email,
                'customer_phone' => $validated['customer_phone'] ?? $order->customer_phone,
                'shipping_address' => $validated['shipping_address'] ?? $order->shipping_address,
                'note' => $validated['note'] ?? $order->note,
                'total_amount' => $validated['total_amount'] ?? $order->total_amount,
                'payment_method' => $validated['payment_method'] ?? $order->payment_method,
                'status' => $validated['status'] ?? $order->status,
            ]);

            if (array_key_exists('items', $validated)) {
                $order->items()->delete();
                $this->syncItems($order, $validated['items']);

                if (! array_key_exists('total_amount', $validated)) {
                    $order->update([
                        'total_amount' => collect($validated['items'])->sum(function (array $item) {
                            return ((float) $item['price']) * ((int) $item['quantity']);
                        }),
                    ]);
                }
            }
        });

        return $this->success($order->fresh()->load('items.product'), 'Cập nhật đơn hàng thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return $this->success(null, 'Xóa đơn hàng thành công.');
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return $this->success($order->fresh()->load('items.product'), 'Cập nhật trạng thái đơn hàng thành công!');
    }

    private function syncItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'] ?? $product?->name ?? 'Unknown product',
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }
}