<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // Đảm bảo bạn đã có model Category để chọn danh mục khi thêm sản phẩm
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. DANH SÁCH SẢN PHẨM
    public function index()
    {
        // Lấy sản phẩm kèm theo danh mục của nó để tránh lỗi N+1 query
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // 2. FORM THÊM MỚI SẢN PHẨM
    public function create()
    {
        // Lấy danh sách danh mục đổ vào thẻ <select> trong form
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // 3. XỬ LÝ LƯU SẢN PHẨM MỚI
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Giới hạn file ảnh 2MB
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        // Xử lý upload ảnh thuần bằng Storage của Laravel
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm mới thành công!');
    }

    // 4. XEM CHI TIẾT SẢN PHẨM
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    // 5. FORM CHỈNH SỬA SẢN PHẨM
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 6. XỬ LÝ CẬP NHẬT SẢN PHẨM
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có để tránh rác bộ nhớ server
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Lưu ảnh mới
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // 7. XỬ LÝ XÓA SẢN PHẨM
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh đính kèm trong thư mục storage trước khi xóa hẳn trong DB
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm thành công.');
    }
}