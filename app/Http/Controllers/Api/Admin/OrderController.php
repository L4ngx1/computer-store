<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::query()
            ->with(['user:id,name,email,phone', 'items.product'])
            ->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();

            $query->where(function ($builder) use ($keyword) {
                $builder->where('customer_name', 'like', "%{$keyword}%")
                    ->orWhere('customer_email', 'like', "%{$keyword}%")
                    ->orWhere('customer_phone', 'like', "%{$keyword}%");

                if (is_numeric($keyword)) {
                    $builder->orWhereKey((int) $keyword);
                }
            });
        }

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $orders = $query->paginate($perPage)->withQueryString();

        return $this->paginated($orders, 'Lấy danh sách đơn hàng thành công.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateOrderPayload($request);

        $order = DB::transaction(function () use ($validated) {
            $customer = $this->resolveCustomer($validated);
            $order = Order::create($this->buildOrderAttributes($validated, $customer));

            $calculatedTotal = $this->syncItems($order, $validated['items']);

            if (! array_key_exists('total_amount', $validated)) {
                $order->update(['total_amount' => $calculatedTotal]);
            }

            return $order->load(['user:id,name,email,phone', 'items.product']);
        });

        return $this->success($order, 'Tạo đơn hàng thành công.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->success($this->loadOrder($order), 'Lấy chi tiết đơn hàng thành công.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $this->validateOrderPayload($request, true);

        DB::transaction(function () use ($validated, $order) {
            $customer = $this->resolveCustomer($validated);
            $order->update($this->buildOrderAttributes($validated, $customer, $order));

            if (array_key_exists('items', $validated)) {
                $calculatedTotal = $this->syncItems($order, $validated['items']);

                if (! array_key_exists('total_amount', $validated)) {
                    $order->update(['total_amount' => $calculatedTotal]);
                }
            } elseif (array_key_exists('total_amount', $validated)) {
                $order->update(['total_amount' => $validated['total_amount']]);
            }
        });

        return $this->success($this->loadOrder($order), 'Cập nhật đơn hàng thành công.');
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

    private function validateOrderPayload(Request $request, bool $isUpdate = false): array
    {
        $usesExistingCustomer = $request->filled('user_id');

        $nameRule = $usesExistingCustomer ? 'nullable' : ($isUpdate ? 'sometimes|required' : 'required');
        $emailRule = $usesExistingCustomer ? 'nullable' : ($isUpdate ? 'sometimes|required' : 'required');
        $phoneRule = $usesExistingCustomer ? 'nullable' : ($isUpdate ? 'sometimes|required' : 'required');
        $shippingRule = $isUpdate ? 'sometimes|required' : 'required';
        $paymentRule = $isUpdate ? 'sometimes|required' : 'required';
        $statusRule = $isUpdate ? 'sometimes|required' : 'nullable';
        $itemsRule = $isUpdate ? 'sometimes|array|min:1' : 'required|array|min:1';
        $itemRule = $isUpdate ? 'sometimes|required_with:items' : 'required_with:items';

        return $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => $nameRule . '|string|max:255',
            'customer_email' => $emailRule . '|email|max:255',
            'customer_phone' => $phoneRule . '|string|max:30',
            'shipping_address' => $shippingRule . '|string',
            'note' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_method' => $paymentRule . '|in:COD,VNPAY,CREDIT_CARD,BANK_TRANSFER,EWALLET',
            'status' => $statusRule . '|in:pending,processing,shipping,completed,cancelled',
            'items' => $itemsRule,
            'items.*.product_id' => $itemRule . '|exists:products,id',
            'items.*.product_name' => 'nullable|string|max:255',
            'items.*.quantity' => $itemRule . '|integer|min:1',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);
    }

    private function resolveCustomer(array $validated): ?User
    {
        if (! array_key_exists('user_id', $validated) || ! $validated['user_id']) {
            return null;
        }

        return User::query()->find($validated['user_id']);
    }

    private function buildOrderAttributes(array $validated, ?User $customer, ?Order $order = null): array
    {
        return [
            'user_id' => array_key_exists('user_id', $validated) ? $customer?->id : $order?->user_id,
            'customer_name' => $customer?->name ?? ($validated['customer_name'] ?? $order?->customer_name),
            'customer_email' => $customer?->email ?? ($validated['customer_email'] ?? $order?->customer_email),
            'customer_phone' => $customer?->phone ?? ($validated['customer_phone'] ?? $order?->customer_phone),
            'shipping_address' => $validated['shipping_address'] ?? $order?->shipping_address,
            'note' => array_key_exists('note', $validated) ? $validated['note'] : $order?->note,
            'total_amount' => $validated['total_amount'] ?? $order?->total_amount ?? 0,
            'payment_method' => $validated['payment_method'] ?? $order?->payment_method ?? 'COD',
            'status' => $validated['status'] ?? $order?->status ?? 'pending',
        ];
    }

    private function syncItems(Order $order, array $items): float
    {
        $order->items()->delete();
        $total = 0;

        foreach ($items as $item) {
            $product = Product::query()->find($item['product_id']);
            $quantity = (int) $item['quantity'];
            $price = array_key_exists('price', $item) && $item['price'] !== null
                ? (float) $item['price']
                : (float) ($product?->sale_price ?? $product?->price ?? 0);

            $order->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'] ?? $product?->name ?? 'Unknown product',
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $total += $price * $quantity;
        }

        return $total;
    }

    private function loadOrder(Order $order): Order
    {
        return $order->fresh()->load(['user:id,name,email,phone', 'items.product']);
    }
}