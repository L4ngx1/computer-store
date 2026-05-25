<?php

namespace App\Models;

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
        'brand_id'
    ];

    // Sản phẩm thuộc về một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Sản phẩm thuộc về một thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Một sản phẩm có nhiều ảnh phụ (Album)
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Một sản phẩm có nhiều thông số kỹ thuật (CPU, RAM...)
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
