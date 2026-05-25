<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\ApiController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends ApiController
{
    public function home(): JsonResponse
    {
        return $this->success([
            'featured_products' => Product::query()
                ->with(['category', 'brand'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->take(8)
                ->get(),
            'new_products' => Product::query()
                ->with(['category', 'brand'])
                ->where('is_active', true)
                ->latest()
                ->take(8)
                ->get(),
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->take(8)
                ->get(),
            'brands' => Brand::query()
                ->orderBy('name')
                ->take(8)
                ->get(),
        ], 'Lấy dữ liệu trang chủ thành công.');
    }

    public function categories(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return $this->success($categories, 'Lấy danh sách danh mục thành công.');
    }

    public function brands(): JsonResponse
    {
        $brands = Brand::query()
            ->orderBy('name')
            ->get();

        return $this->success($brands, 'Lấy danh sách thương hiệu thành công.');
    }

    public function products(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with(['category', 'brand'])
            ->where('is_active', true);

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();

            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($builder) use ($request) {
                $builder->where('slug', $request->string('category'));
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($builder) use ($request) {
                $builder->where('slug', $request->string('brand'));
            });
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $perPage = (int) $request->integer('per_page', 12);
        $perPage = max(1, min($perPage, 48));

        $products = $query->latest()->paginate($perPage);

        return $this->paginated($products, 'Lấy danh sách sản phẩm thành công.');
    }

    public function featured(): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        return $this->success($products, 'Lấy danh sách sản phẩm nổi bật thành công.');
    }

    public function show(Product $product): JsonResponse
    {
        if (! $product->is_active) {
            return $this->error('Sản phẩm không tồn tại.', 404);
        }

        return $this->success($product->load(['category', 'brand', 'images', 'attributes']), 'Lấy chi tiết sản phẩm thành công.');
    }

    public function cartSummary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $items = [];
        $subtotal = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::query()->findOrFail($item['product_id']);
            $quantity = (int) $item['quantity'];
            $unitPrice = $product->sale_price ?? $product->price;
            $lineTotal = $unitPrice * $quantity;

            $subtotal += $lineTotal;

            $items[] = [
                'product' => $product->load(['category', 'brand']),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        }

        $shipping = $subtotal > 0 ? 21.00 : 0.00;
        $tax = $subtotal > 0 ? round($subtotal * 0.1, 2) : 0.00;

        return $this->success([
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $subtotal + $shipping + $tax,
        ], 'Tính giỏ hàng thành công.');
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string',
            'note' => 'nullable|string',
            'payment_method' => 'nullable|string|max:50',
            'status' => 'nullable|in:pending,processing,shipping,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $items = [];
            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::query()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];
                $unitPrice = $product->sale_price ?? $product->price;

                $totalAmount += $unitPrice * $quantity;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                ];
            }

            $order = Order::create([
                'user_id' => $validated['user_id'] ?? Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'note' => $validated['note'] ?? null,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'] ?? 'COD',
                'status' => $validated['status'] ?? 'pending',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    ...$item,
                ]);
            }

            return $order->load(['user', 'items.product']);
        });

        return $this->success($order, 'Đặt hàng thành công.', 201);
    }

    public function orders(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['items.product'])
            ->latest()
            ->paginate(15);

        return $this->paginated($orders, 'Lấy danh sách đơn hàng của tôi thành công.');
    }

    public function order(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return $this->error('Bạn không có quyền xem đơn hàng này.', 403);
        }

        return $this->success($order->load(['user', 'items.product']), 'Lấy chi tiết đơn hàng của tôi thành công.');
    }
}