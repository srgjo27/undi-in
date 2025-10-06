<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'draw_date',
        'status',
        'winning_coupon_id',
        'drawn_by_user_id',
        'notes',
    ];

    protected $casts = [
        'draw_date' => 'datetime',
    ];

    /**
     * Get the property that the raffle is for.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the winning coupon.
     */
    public function winnerCoupon()
    {
        return $this->belongsTo(Coupon::class, 'winning_coupon_id');
    }

    /**
     * Get the user who conducted the raffle.
     */
    public function drawnBy()
    {
        return $this->belongsTo(User::class, 'drawn_by_user_id');
    }
}
