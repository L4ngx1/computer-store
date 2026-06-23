<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends ApiController
{
    public function index(): JsonResponse
    {
        $brands = Brand::query()->latest()->paginate(15);

        return $this->paginated($brands, 'Lấy danh sách thương hiệu thành công.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'logo' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? $validated['name']);

        $brand = Brand::create($validated);

        return $this->success($brand, 'Tạo thương hiệu thành công.', 201);
    }

    public function show(Brand $brand): JsonResponse
    {
        return $this->success($brand, 'Lấy chi tiết thương hiệu thành công.');
    }

    public function update(Request $request, Brand $brand): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:brands,name,' . $brand->id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'logo' => 'nullable|string|max:255',
        ]);

        if (array_key_exists('name', $validated) || array_key_exists('slug', $validated)) {
            $source = $validated['slug'] ?? $validated['name'] ?? $brand->name;
            $validated['slug'] = $this->resolveSlug($source, $brand->id);
        }

        $brand->update($validated);

        return $this->success($brand->fresh(), 'Cập nhật thương hiệu thành công.');
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();

        return $this->success(null, 'Xóa thương hiệu thành công.');
    }

    private function resolveSlug(string $source, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($source);
        $slug = $baseSlug;
        $index = 1;

        while (Brand::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $index;
            $index++;
        }

        return $slug;
    }
}
