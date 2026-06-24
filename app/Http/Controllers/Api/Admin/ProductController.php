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
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with(['category', 'brand'])->latest();

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();

            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        $perPage = (int) $request->integer('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $products = $query->paginate($perPage)->withQueryString();

        return $this->paginated($products, 'Lay danh sach san pham thanh cong.');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateProduct($request, null, false);

        $product = DB::transaction(function () use ($validated, $request) {
            $thumbnailPath = null;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('products/thumbnails', $filename, 'public');
                $thumbnailPath = '/storage/' . $path;
            }

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $this->resolveSlug($validated['slug'] ?? $validated['name']),
                'sku' => $validated['sku'],
                'summary' => $validated['summary'] ?? null,
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'stock' => $validated['stock'] ?? 0,
                'thumbnail' => $thumbnailPath,
                'is_featured' => $validated['is_featured'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
            ]);

            $this->storeUploadedImages($request, $product);
            $this->syncAttributes($product, $validated);

            return $product;
        });

        return $this->success($product->load(['category', 'brand', 'images', 'attributes']), 'Tao san pham thanh cong.', 201);
    }

    public function show(Product $product): JsonResponse
    {
        return $this->success($product->load(['category', 'brand', 'images', 'attributes']), 'Lay chi tiet san pham thanh cong.');
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $this->validateProduct($request, $product->id, true);

        $product = DB::transaction(function () use ($validated, $product, $request) {
            $thumbnailPath = $product->thumbnail;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('products/thumbnails', $filename, 'public');
                $thumbnailPath = '/storage/' . $path;
            }

            $product->update([
                'name' => $validated['name'] ?? $product->name,
                'slug' => $this->resolveSlug($validated['slug'] ?? $validated['name'] ?? $product->name, $product->id),
                'sku' => $validated['sku'] ?? $product->sku,
                'summary' => $validated['summary'] ?? $product->summary,
                'description' => $validated['description'] ?? $product->description,
                'price' => $validated['price'] ?? $product->price,
                'sale_price' => array_key_exists('sale_price', $validated) ? $validated['sale_price'] : $product->sale_price,
                'stock' => $validated['stock'] ?? $product->stock,
                'thumbnail' => $thumbnailPath,
                'is_featured' => $validated['is_featured'] ?? $product->is_featured,
                'is_active' => $validated['is_active'] ?? $product->is_active,
                'category_id' => $validated['category_id'] ?? $product->category_id,
                'brand_id' => $validated['brand_id'] ?? $product->brand_id,
            ]);

            if ($request->hasFile('images')) {
                $product->images()->delete();
                $this->storeUploadedImages($request, $product);
            }

            $this->syncAttributes($product, $validated, true);

            return $product;
        });

        return $this->success($product->fresh()->load(['category', 'brand', 'images', 'attributes']), 'Cap nhat san pham thanh cong.');
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->success(null, 'Xoa san pham thanh cong.');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null, bool $isUpdate = false): array
    {
        $nameRule = $isUpdate ? 'sometimes' : 'required';
        $skuRule = $isUpdate ? 'sometimes' : 'required';
        $priceRule = $isUpdate ? 'sometimes' : 'required';
        $thumbnailRule = $isUpdate ? 'nullable' : 'required';
        $categoryRule = $isUpdate ? 'sometimes' : 'required';
        $brandRule = $isUpdate ? 'sometimes' : 'required';
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
            'thumbnail' => $thumbnailRule . '|image|mimes:jpeg,png,gif,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,gif,webp|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'category_id' => $categoryRule . '|exists:categories,id',
            'brand_id' => $brandRule . '|exists:brands,id',
            'attributes' => 'nullable|array',
            'attributes.*.name' => 'required_with:attributes|string|max:255',
            'attributes.*.value' => 'required_with:attributes|string|max:255',
        ]);
    }

    private function storeUploadedImages(Request $request, Product $product): void
    {
        $images = $request->file('images', []);

        if (! is_array($images)) {
            return;
        }

        foreach ($images as $index => $image) {
            if (! $image || ! $image->isValid()) {
                continue;
            }

            $filename = time() . '_' . uniqid($index . '_', true) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products/images', $filename, 'public');

            if ($path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => '/storage/' . $path,
                ]);
            }
        }
    }

    private function syncAttributes(Product $product, array $validated, bool $replaceExisting = false): void
    {
        if (! array_key_exists('attributes', $validated)) {
            return;
        }

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

    private function resolveSlug(string $source, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($source);
        $slug = $baseSlug;
        $index = 1;

        while (Product::query()
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $index;
            $index++;
        }

        return $slug;
    }
}
