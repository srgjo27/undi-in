<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'title',
        'slug',
        'description',
        'address',
        'city',
        'province',
        'latitude',
        'longitude',
        'land_area',
        'building_area',
        'bedrooms',
        'bathrooms',
        'facilities',
        'coupon_price',
        'max_coupons',
        'sale_start_date',
        'sale_end_date',
        'status',
        'verification_status',
        'verification_notes',
    ];

    protected $casts = [
        'facilities' => 'array',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'coupon_price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title);
            }
        });

        static::updating(function ($property) {
            if ($property->isDirty('title')) {
                $property->slug = Str::slug($property->title);
            }
        });
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function primaryImage()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    public function getSoldCouponsAttribute()
    {
        return $this->orders()->where('status', 'completed')->sum('quantity');
    }

    public function getAvailableCouponsAttribute()
    {
        if ($this->max_coupons) {
            return $this->max_coupons - $this->sold_coupons;
        }
        return null;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-secondary',
            'active' => 'bg-success',
            'pending_draw' => 'bg-warning',
            'completed' => 'bg-primary',
            'cancelled' => 'bg-danger',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'active' => 'Aktif',
            'pending_draw' => 'Menunggu Undian',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the coupons for the property.
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the raffles for the property.
     */
    public function raffles()
    {
        return $this->hasMany(Raffle::class);
    }
}
