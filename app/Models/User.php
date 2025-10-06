<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the properties for the seller.
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'seller_id');
    }

    /**
     * Get the orders for the buyer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Get the coupons for the buyer.
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'buyer_id');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is seller.
     */
    public function isSeller()
    {
        return $this->role === 'seller';
    }

    /**
     * Check if user is buyer.
     */
    public function isBuyer()
    {
        return $this->role === 'buyer';
    }

    /**
     * Check if user is active (not blocked).
     */
    public function isActive()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Toggle user active/blocked status.
     */
    public function toggleStatus(): void
    {
        $this->email_verified_at = $this->isActive() ? null : now();
        $this->save();
    }
}
