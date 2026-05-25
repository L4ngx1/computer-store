<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'note',
        'total_amount',
        'payment_method',
        'status'
    ];

    // Đơn hàng thuộc về một User (nếu có đăng nhập)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một đơn hàng có nhiều sản phẩm chi tiết bên trong
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
