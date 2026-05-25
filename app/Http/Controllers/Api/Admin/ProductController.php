<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(15);

        return $this->paginated($products, 'Lấy danh sách sản phẩm thành công.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateProduct($request, null, false);

        $product = DB::transaction(function () use ($validated) {
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $this->resolveSlug($validated['slug'] ?? $validated['name']),
                'sku' => $validated['sku'],
                'summary' => $validated['summary'] ?? null,
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'stock' => $validated['stock'] ?? 0,
                'thumbnail' => $validated['thumbnail'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
            ]);

            $this->syncRelations($product, $validated);

            return $product;
        });

        return $this->success($product->load(['category', 'brand', 'images', 'attributes']), 'Tạo sản phẩm thành công.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return $this->success($product->load(['category', 'brand', 'images', 'attributes']), 'Lấy chi tiết sản phẩm thành công.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $this->validateProduct($request, $product->id, true);

        $product = DB::transaction(function () use ($validated, $product) {
            $product->update([
                'name' => $validated['name'] ?? $product->name,
                'slug' => $this->resolveSlug($validated['slug'] ?? $validated['name'] ?? $product->name, $product->id),
                'sku' => $validated['sku'] ?? $product->sku,
                'summary' => $validated['summary'] ?? $product->summary,
                'description' => $validated['description'] ?? $product->description,
                'price' => $validated['price'] ?? $product->price,
                'sale_price' => array_key_exists('sale_price', $validated) ? $validated['sale_price'] : $product->sale_price,
                'stock' => $validated['stock'] ?? $product->stock,
                'thumbnail' => $validated['thumbnail'] ?? $product->thumbnail,
                'is_featured' => $validated['is_featured'] ?? $product->is_featured,
                'is_active' => $validated['is_active'] ?? $product->is_active,
                'category_id' => $validated['category_id'] ?? $product->category_id,
                'brand_id' => $validated['brand_id'] ?? $product->brand_id,
            ]);

            $this->syncRelations($product, $validated, true);

            return $product;
        });

        return $this->success($product->fresh()->load(['category', 'brand', 'images', 'attributes']), 'Cập nhật sản phẩm thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->success(null, 'Xóa sản phẩm thành công.');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null, bool $isUpdate = false): array
    {
        $nameRule = $isUpdate ? 'sometimes|required' : 'required';
        $skuRule = $isUpdate ? 'sometimes|required' : 'required';
        $priceRule = $isUpdate ? 'sometimes|required' : 'required';
        $thumbnailRule = $isUpdate ? 'sometimes|required' : 'required';
        $categoryRule = $isUpdate ? 'sometimes|required' : 'required';
        $brandRule = $isUpdate ? 'sometimes|required' : 'required';
        $uniqueSuffix = $ignoreId ? ',' . $ignoreId : '';

        return $request->validate([
            'name' => $nameRule . '|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug' . $uniqueSuffix,
            'sku' => $skuRule . '|string|max:255|unique:products,sku' . $uniqueSuffix,
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => $priceRule . '|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'thumbnail' => $thumbnailRule . '|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'category_id' => $categoryRule . '|exists:categories,id',
            'brand_id' => $brandRule . '|exists:brands,id',
            'images' => 'nullable|array',
            'images.*' => 'string|max:255',
            'attributes' => 'nullable|array',
            'attributes.*.name' => 'required_with:attributes|string|max:255',
            'attributes.*.value' => 'required_with:attributes|string|max:255',
        ]);
    }

    private function syncRelations(Product $product, array $validated, bool $replaceExisting = false): void
    {
        if (array_key_exists('images', $validated)) {
            if ($replaceExisting) {
                $product->images()->delete();
            }

            foreach ($validated['images'] as $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        if (array_key_exists('attributes', $validated)) {
            if ($replaceExisting) {
                $product->attributes()->delete();
            }

            foreach ($validated['attributes'] as $attribute) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'name' => $attribute['name'],
                    'value' => $attribute['value'],
                ]);
            }
        }
    }

    private function resolveSlug(string $source, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($source);
        $slug = $baseSlug;
        $index = 1;

        while (Product::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $index;
            $index++;
        }

        return $slug;
    }
}