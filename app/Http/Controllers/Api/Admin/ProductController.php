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
                'slug' => $validated['slug'] ?: Str::slug($validated['name']),
                'summary' => $validated['summary'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'],
                'stock' => $validated['stock'],
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
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Nullable on update
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $updateData = $validated;

            // 1. Handle Thumbnail update
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                // Store new one
                $updateData['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            // 2. Handle Detail Images update (replace logic)
            if ($request->hasFile('images')) {
                // Delete all old images and their files
                foreach ($product->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage->image_path)) {
                        Storage::disk('public')->delete($oldImage->image_path);
                    }
                    $oldImage->delete();
                }

                // Store new images
                foreach ($request->file('images') as $imageFile) {
                    $imagePath = $imageFile->store('products/images', 'public');
                    $product->images()->create(['image_path' => $imagePath]);
                }
            }

            // 3. Update Product
            if (empty($updateData['slug'])) {
                $updateData['slug'] = Str::slug($updateData['name']);
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
        // You might want to add logic to prevent deleting products with active orders
        $product->delete(); // This will trigger model events to delete images if set up
        return response()->json(['success' => true, 'message' => 'Sản phẩm đã được xóa.']);
    }
}
