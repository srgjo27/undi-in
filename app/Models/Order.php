<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'property_id',
        'quantity',
        'total_price',
        'status',
        'payment_gateway',
        'payment_token',
        'payment_url',
        'paid_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'failed' => 'bg-danger',
            'cancelled' => 'bg-secondary',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Pembayaran Gagal',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    public function getTotalAmountAttribute()
    {
        return $this->total_price;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the coupons for the order.
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the transactions for the order.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
