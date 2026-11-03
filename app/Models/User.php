<?php

namespace App\Models;

use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Gửi thông báo xác minh email (bản tiếng Việt).
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'api_token',
        'is_locked',
        'locked_at',
        'locked_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'locked_at' => 'datetime',
            'is_locked' => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra người dùng đã mua (đơn hàng hoàn tất) một sản phẩm hay chưa.
     * Chỉ đơn hàng ở trạng thái 'completed' mới được tính là đã mua hàng.
     */
    public function hasPurchasedProduct(int $productId): bool
    {
        return $this->orders()
            ->where('status', 'completed')
            ->whereHas('items', fn ($query) => $query->where('product_id', $productId))
            ->exists();
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function performedAuditLogs()
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }
}
