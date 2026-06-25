<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity');

        // Here, you would typically add the product to the user's session cart
        // For now, we'll just redirect back with a success message.
        // In a real application, you'd have a more robust cart management system.

        return redirect()->back()->with('success', "Đã thêm '$quantity x {$product->name}' vào giỏ hàng!");
    }
}
