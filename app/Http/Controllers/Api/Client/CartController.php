<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\ApiController;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            $this->buildCartPayload($request),
            'Lay gio hang thanh cong.'
        );
    }

    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if (! $request->user()) {
            return $this->success(
                $this->buildLocalCartPayload($validated['items']),
                'Gio hang local da duoc tinh lai.'
            );
        }

        try {
            foreach ($validated['items'] as $item) {
                $this->saveDatabaseCartItem(
                    $request,
                    (int) $item['product_id'],
                    (int) $item['quantity'],
                    true
                );
            }
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }

        return $this->success(
            $this->buildDatabaseCartPayload($request),
            'Da dong bo gio hang len tai khoan.'
        );
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($validated['product_id']);

        $quantityToAdd = (int) ($validated['quantity'] ?? 1);

        if (! $request->user()) {
            $items = $this->mergeLocalItems($validated['items'] ?? [], $product->id, $quantityToAdd);

            return $this->success(
                $this->buildLocalCartPayload($items),
                'Da them san pham vao gio hang local.',
                201
            );
        }

        try {
            $this->saveDatabaseCartItem($request, $product->id, $quantityToAdd, true);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }

        return $this->success(
            $this->buildDatabaseCartPayload($request),
            'Da them san pham vao gio hang!',
            201
        );
    }

    public function update(Request $request, int $productId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($productId);

        $quantity = (int) $validated['quantity'];

        if (! $request->user()) {
            $items = $this->setLocalItemQuantity($validated['items'] ?? [], $product->id, $quantity);

            return $this->success(
                $this->buildLocalCartPayload($items),
                'Gio hang local da duoc cap nhat.'
            );
        }

        $cartItem = CartItem::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if (! $cartItem) {
            return $this->error('San pham khong ton tai trong gio hang.', 404);
        }

        if ($quantity === 0) {
            $cartItem->delete();

            return $this->success(
                $this->buildDatabaseCartPayload($request),
                'Da xoa san pham khoi gio hang.'
            );
        }

        if ($product->stock !== null && $quantity > $product->stock) {
            return $this->error('So luong trong gio vuot qua ton kho hien co.', 422);
        }

        $cartItem->update([
            'quantity' => $quantity,
        ]);

        return $this->success(
            $this->buildDatabaseCartPayload($request),
            'Gio hang da duoc cap nhat!'
        );
    }

    public function remove(Request $request, int $productId): JsonResponse
    {
        if (! $request->user()) {
            $validated = $request->validate([
                'items' => 'nullable|array',
                'items.*.product_id' => 'required_with:items|exists:products,id',
                'items.*.quantity' => 'required_with:items|integer|min:1',
            ]);

            $items = $this->setLocalItemQuantity($validated['items'] ?? [], $productId, 0);

            return $this->success(
                $this->buildLocalCartPayload($items),
                'Da xoa san pham khoi gio hang local.'
            );
        }

        $deleted = CartItem::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        if (! $deleted) {
            return $this->error('San pham khong ton tai trong gio hang.', 404);
        }

        return $this->success(
            $this->buildDatabaseCartPayload($request),
            'Da xoa san pham khoi gio hang!'
        );
    }

    public function clear(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->success($this->emptyCartPayload('local'), 'Da xoa toan bo gio hang local!');
        }

        CartItem::query()
            ->where('user_id', $request->user()->id)
            ->delete();

        return $this->success($this->emptyCartPayload('database'), 'Da xoa toan bo gio hang!');
    }

    private function buildCartPayload(Request $request): array
    {
        if (! $request->user()) {
            return $this->emptyCartPayload('local');
        }

        return $this->buildDatabaseCartPayload($request);
    }

    private function buildDatabaseCartPayload(Request $request): array
    {
        $items = CartItem::query()
            ->where('user_id', $request->user()->id)
            ->with(['product.category', 'product.brand'])
            ->latest()
            ->get()
            ->map(function (CartItem $item) {
                $product = $item->product;
                $unitPrice = (float) ($product?->sale_price ?? $product?->price ?? 0);
                $quantity = (int) $item->quantity;

                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice * $quantity,
                    'product' => $product,
                ];
            })
            ->values();

        return [
            'storage' => 'database',
            'user_id' => $request->user()->id,
            'items' => $items,
            'total_items' => $items->count(),
            'total_quantity' => $items->sum('quantity'),
            'subtotal' => $items->sum('line_total'),
        ];
    }

    private function buildLocalCartPayload(array $items): array
    {
        $quantities = collect($items)
            ->mapWithKeys(fn (array $item) => [(int) $item['product_id'] => (int) $item['quantity']])
            ->filter(fn (int $quantity) => $quantity > 0);

        $products = Product::query()
            ->whereIn('id', $quantities->keys())
            ->where('is_active', true)
            ->with(['category', 'brand'])
            ->get()
            ->keyBy('id');

        $cartItems = $quantities
            ->map(function (int $quantity, int $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $unitPrice = (float) ($product->sale_price ?? $product->price ?? 0);

                return [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice * $quantity,
                    'product' => $product,
                ];
            })
            ->filter()
            ->values();

        return [
            'storage' => 'local',
            'items' => $cartItems,
            'total_items' => $cartItems->count(),
            'total_quantity' => $cartItems->sum('quantity'),
            'subtotal' => $cartItems->sum('line_total'),
        ];
    }

    private function saveDatabaseCartItem(Request $request, int $productId, int $quantity, bool $increment): CartItem
    {
        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($productId);

        $cartItem = CartItem::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        $currentQuantity = $cartItem->exists ? (int) $cartItem->quantity : 0;
        $newQuantity = $increment ? $currentQuantity + $quantity : $quantity;

        if ($product->stock !== null && $newQuantity > $product->stock) {
            throw new HttpException(422, 'So luong trong gio vuot qua ton kho hien co.');
        }

        $cartItem->fill([
            'cart_token' => $this->databaseCartToken($request),
            'quantity' => $newQuantity,
        ])->save();

        return $cartItem;
    }

    private function databaseCartToken(Request $request): string
    {
        return 'user_' . $request->user()->id;
    }

    private function mergeLocalItems(array $items, int $productId, int $quantityToAdd): array
    {
        $quantities = collect($items)
            ->mapWithKeys(fn (array $item) => [(int) $item['product_id'] => (int) $item['quantity']]);

        $quantities[$productId] = ((int) ($quantities[$productId] ?? 0)) + $quantityToAdd;

        return $quantities
            ->filter(fn (int $quantity) => $quantity > 0)
            ->map(fn (int $quantity, int $id) => ['product_id' => $id, 'quantity' => $quantity])
            ->values()
            ->all();
    }

    private function setLocalItemQuantity(array $items, int $productId, int $quantity): array
    {
        $quantities = collect($items)
            ->mapWithKeys(fn (array $item) => [(int) $item['product_id'] => (int) $item['quantity']]);

        if ($quantity > 0) {
            $quantities[$productId] = $quantity;
        } else {
            $quantities->forget($productId);
        }

        return $quantities
            ->filter(fn (int $quantity) => $quantity > 0)
            ->map(fn (int $quantity, int $id) => ['product_id' => $id, 'quantity' => $quantity])
            ->values()
            ->all();
    }

    private function emptyCartPayload(string $storage): array
    {
        return [
            'storage' => $storage,
            'items' => [],
            'subtotal' => 0,
            'total_quantity' => 0,
            'total_items' => 0,
        ];
    }
}
