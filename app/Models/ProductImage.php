<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Lấy URL đầy đủ cho ảnh chi tiết.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getImagePathAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return Storage::url($value);
        }
        return null;
    }
}
