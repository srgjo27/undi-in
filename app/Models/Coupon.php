<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'buyer_id',
        'property_id',
        'coupon_number',
        'is_winner',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
    ];

    /**
     * Get the order that owns the coupon.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the buyer that owns the coupon.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the property that the coupon is for.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
