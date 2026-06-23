<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends ApiController
{
    public function index(): JsonResponse
    {
        $categories = Category::query()->latest()->paginate(15);

        return $this->paginated($categories, 'Lấy danh sách danh mục thành công.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'image' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['slug'] = $this->resolveSlug($validated['slug'] ?? $validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $category = Category::create($validated);

        return $this->success($category, 'Tạo danh mục thành công.', 201);
    }

    public function show(Category $category): JsonResponse
    {
        return $this->success($category, 'Lấy chi tiết danh mục thành công.');
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'image' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        if (array_key_exists('name', $validated) || array_key_exists('slug', $validated)) {
            $source = $validated['slug'] ?? $validated['name'] ?? $category->name;
            $validated['slug'] = $this->resolveSlug($source, $category->id);
        }

        $category->update($validated);

        return $this->success($category->fresh(), 'Cập nhật danh mục thành công.');
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return $this->success(null, 'Xóa danh mục thành công.');
    }

    private function resolveSlug(string $source, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($source);
        $slug = $baseSlug;
        $index = 1;

        while (Category::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $index;
            $index++;
        }

        return $slug;
    }
}
