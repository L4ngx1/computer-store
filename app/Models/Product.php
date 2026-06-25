<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'summary',
        'description',
        'price',
        'sale_price',
        'stock',
        'thumbnail',
        'is_featured',
        'is_active',
        'category_id',
        'brand_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Lấy URL đầy đủ cho ảnh đại diện của sản phẩm.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getThumbnailAttribute($value)
    {
        if ($value) {
            // Nếu giá trị đã là một URL, trả về luôn
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            // Ngược lại, tạo URL từ đường dẫn lưu trữ
            return Storage::url($value);
        }
        return null; // Hoặc trả về một ảnh placeholder: asset('images/placeholder.png')
    }
}
