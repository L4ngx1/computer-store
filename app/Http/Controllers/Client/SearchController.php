<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        
        $products = Product::query()->where('is_active', true);
        
        if ($keyword) {
            $products->where('name', 'like', "%{$keyword}%");
        } else {
            // Return empty pagination if no keyword
            $products->whereRaw('1 = 0');
        }

        $products = $products->with(['category', 'images'])->paginate(12)->withQueryString();

        return view('client.search', compact('products', 'keyword'));
    }
}
