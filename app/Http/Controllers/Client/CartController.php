<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Lấy giỏ hàng hiện tại dưới dạng [product_id => quantity].
     * - User đã đăng nhập: lưu trong bảng cart_items.
     * - Khách (guest): lưu trong session.
     */
    private function getCart(): array
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())
                ->pluck('quantity', 'product_id')
                ->map(fn ($qty) => (int) $qty)
                ->toArray();
        }

        return session('cart', []);
    }

    /**
     * Giới hạn số lượng theo tồn kho của sản phẩm.
     */
    private function clampToStock(Product $product, int $quantity): int
    {
        $quantity = max(1, $quantity);

        if ($product->stock > 0) {
            $quantity = min($quantity, $product->stock);
        }

        return $quantity;
    }

    /**
     * Lưu số lượng cho một sản phẩm vào giỏ (DB hoặc session).
     */
    private function persist(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->forget($productId);
            return;
        }

        if (Auth::check()) {
            CartItem::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productId],
                ['quantity' => $quantity]
            );
        } else {
            $cart = session('cart', []);
            $cart[$productId] = $quantity;
            session(['cart' => $cart]);
        }
    }

    /**
     * Xóa một sản phẩm khỏi giỏ.
     */
    private function forget(int $productId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }
    }

    public function index()
    {
        $cart = $this->getCart();

        $products = Product::whereIn('id', array_keys($cart))->get();

        $cartItems = $products->map(function (Product $product) use ($cart) {
            return (object) [
                'product'  => $product,
                'quantity' => $cart[$product->id] ?? 0,
            ];
        })->filter(fn ($item) => $item->quantity > 0)->values();

        $total = $cartItems->sum(function ($item) {
            return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
        });

        return view('client.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, int|string $id)
    {
        $product = Product::where('is_active', true)->findOrFail((int) $id);

        if ($product->stock <= 0) {
            return redirect()->back()->with('cart_error', 'Sản phẩm đã hết hàng!');
        }

        $addQuantity = max(1, (int) $request->input('quantity', 1));
        $cart = $this->getCart();
        $currentQuantity = $cart[$product->id] ?? 0;

        $quantity = $this->clampToStock($product, $currentQuantity + $addQuantity);

        $this->persist($product->id, $quantity);

        return redirect()->back()
            ->with('cart_success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'quantities'   => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        $products = Product::whereIn('id', array_keys($validated['quantities']))->get()->keyBy('id');
        $currentCart = $this->getCart();

        foreach ($validated['quantities'] as $id => $qty) {
            $id  = (int) $id;
            $qty = (int) $qty;

            // Chỉ cập nhật nếu số lượng thực sự thay đổi để tối ưu
            if (($currentCart[$id] ?? 0) === $qty) {
                continue;
            }

            $product = $products->get($id);

            if (!$product) {
                continue;
            }

            $qty = $qty > 0 ? $this->clampToStock($product, $qty) : 0;
            $this->persist($id, $qty);
        }

        return redirect()->route('client.cart')
            ->with('cart_success', 'Giỏ hàng đã được cập nhật!');
    }

    public function remove(int|string $id)
    {
        $this->forget((int) $id);

        return redirect()->route('client.cart')
            ->with('cart_success', 'Đã xóa sản phẩm!');
    }

    public function clear()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('client.cart')
            ->with('cart_success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    /**
     * Hợp nhất giỏ hàng trong session (guest) vào DB sau khi user đăng nhập.
     * Gọi hàm này trong luồng đăng nhập (ClientAuthController@store).
     */
    public static function mergeSessionCart(int $userId): void
    {
        $cart = session('cart', []);

        foreach ($cart as $productId => $qty) {
            $productId = (int) $productId;
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }

            $item = CartItem::firstOrNew([
                'user_id'    => $userId,
                'product_id' => $productId,
            ]);
            $item->quantity = ($item->quantity ?? 0) + $qty;
            $item->save();
        }

        session()->forget('cart');
    }
}
