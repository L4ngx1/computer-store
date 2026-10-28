<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function isAdmin()
    {
        return $this->role === 'admin';
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
