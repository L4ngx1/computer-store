<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'slug' => 'nullable|string|unique:products,slug',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'stock' => 'required|integer|min:0',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle Thumbnail
            $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');

            // 2. Create Product
            $product = Product::create([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'slug' => $this->uniqueSlug($validated['slug'] ?: $validated['name']),
                'summary' => $validated['summary'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'],
                'stock' => $validated['stock'],
                'warranty_start_date' => $validated['warranty_start_date'] ?? null,
                'warranty_end_date' => $validated['warranty_end_date'] ?? null,
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'is_active' => $validated['is_active'],
                'is_featured' => $validated['is_featured'],
                'thumbnail' => $thumbnailPath, // Save the path
            ]);

            // 3. Handle Detail Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imagePath = $imageFile->store('products/images', 'public');
                    $product->images()->create(['image_path' => $imagePath]);
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được tạo thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            // Clean up uploaded file if transaction fails
            if (isset($thumbnailPath) && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            // Note: cleaning up multiple images is more complex and omitted for brevity

            return response()->json(['success' => false, 'message' => 'Đã có lỗi xảy ra khi tạo sản phẩm. Vui lòng thử lại.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'stock' => 'required|integer|min:0',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'thumbnail' => 'required_if:delete_thumbnail,1|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_thumbnail' => 'nullable|boolean',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
            'replace_images' => 'nullable|array',
            'replace_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $updateData = $validated;
            unset($updateData['delete_images'], $updateData['replace_images'], $updateData['images'], $updateData['delete_thumbnail']);

            // 1. Handle Thumbnail update
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                $oldThumbnail = $product->getRawOriginal('thumbnail');
                if ($oldThumbnail && Storage::disk('public')->exists($oldThumbnail)) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
                // Store new one
                $updateData['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            // 2. Remove only the existing images selected in the editor.
            $imagesToDelete = $product->images()
                ->whereIn('id', $validated['delete_images'] ?? [])
                ->get();

            foreach ($imagesToDelete as $image) {
                $imagePath = $image->getRawOriginal('image_path');
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $image->delete();
            }

            // 3. Replace only the gallery items whose thumbnails were clicked.
            foreach ($request->file('replace_images', []) as $imageId => $replacementFile) {
                $image = $product->images()->whereKey($imageId)->first();
                if (!$image || in_array((int) $imageId, $validated['delete_images'] ?? [], true)) {
                    continue;
                }

                $oldPath = $image->getRawOriginal('image_path');
                $newPath = $replacementFile->store('products/images', 'public');
                $image->update(['image_path' => $newPath]);

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // 4. Add new images to the current gallery.
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imagePath = $imageFile->store('products/images', 'public');
                    $product->images()->create(['image_path' => $imagePath]);
                }
            }

            // 5. Update Product
            if (empty($updateData['slug'])) {
                $updateData['slug'] = $this->uniqueSlug($updateData['name'], $product->id);
            }
            $product->update($updateData);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được cập nhật thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating product {$product->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Đã có lỗi xảy ra khi cập nhật. Vui lòng thử lại.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $thumbnail = $product->getRawOriginal('thumbnail');
        $imagePaths = $product->images()->pluck('image_path')->all();
        foreach (array_filter(array_merge([$thumbnail], $imagePaths)) as $path) {
            Storage::disk('public')->delete($path);
        }
        $product->images()->delete();
        $product->attributes()->delete();
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Sản phẩm đã được xóa.']);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'product';
        $slug = $base;
        $suffix = 2;

        while (Product::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
