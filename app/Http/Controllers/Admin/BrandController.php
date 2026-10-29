<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $query = Brand::query()->withCount('products');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $brands = $query->latest()->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thêm thương hiệu thành công!');
    }

    public function show(Brand $brand): View
    {
        $brand->loadCount('products');

        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $oldLogo = null;
        if ($request->hasFile('logo')) {
            $oldLogo = $brand->logo;
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        } else {
            unset($data['logo']);
        }

        $brand->update($data);
        $this->deleteStoredImage($oldLogo);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return redirect()->route('admin.brands.index')
                ->with('error', 'Không thể xóa thương hiệu đang có sản phẩm.');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Đã xóa thương hiệu!');
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && !filter_var($path, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
