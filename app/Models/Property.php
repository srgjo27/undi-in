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
        'price',
        'coupon_price',
        'max_coupons',
        'sale_start_date',
        'sale_end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'facilities' => 'array',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'price' => 'decimal:2',
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

    public function user(): BelongsTo
    {
        return $this->seller();
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
        return $this->orders()->whereIn('status', ['paid', 'completed'])->sum('quantity');
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

    public function getStatusIconAttribute()
    {
        $icons = [
            'draft' => 'pause-circle',
            'active' => 'play-circle',
            'pending_draw' => 'clock',
            'completed' => 'check-circle',
            'cancelled' => 'times-circle',
        ];

        return $icons[$this->status] ?? 'pause-circle';
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

    /**
     * Check if property should be automatically updated to active status
     */
    public function shouldBeActive()
    {
        return $this->status === 'draft' &&
            $this->sale_start_date &&
            now()->gte($this->sale_start_date);
    }

    /**
     * Check if property should be automatically updated to pending_draw status
     */
    public function shouldBePendingDraw()
    {
        return $this->status === 'active' &&
            $this->sale_end_date &&
            now()->gte($this->sale_end_date);
    }

    /**
     * Update property status automatically based on dates
     */
    public function updateStatusAutomatically()
    {
        $updated = false;

        if ($this->shouldBeActive()) {
            $this->update(['status' => 'active']);
            $updated = true;
        } elseif ($this->shouldBePendingDraw()) {
            $this->update(['status' => 'pending_draw']);
            $updated = true;
        }

        return $updated;
    }

    /**
     * Scope to get properties that need status update
     */
    public function scopeNeedsStatusUpdate($query)
    {
        $now = now();

        return $query->where(function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->where('status', 'draft')
                    ->where('sale_start_date', '<=', $now);
            })
                ->orWhere(function ($subQ) use ($now) {
                    $subQ->where('status', 'active')
                        ->where('sale_end_date', '<=', $now);
                });
        });
    }

    /**
     * Check if property has available coupons for purchase
     */
    public function hasAvailableCoupons($quantity = 1)
    {
        if (!$this->max_coupons) {
            return true;
        }

        return $this->available_coupons >= $quantity;
    }

    /**
     * Validate if quantity can be purchased
     */
    public function canPurchaseQuantity($quantity)
    {
        if (!$this->max_coupons) {
            return ['valid' => true];
        }

        $available = $this->available_coupons;

        if ($available <= 0) {
            return [
                'valid' => false,
                'message' => 'Kuota kupon untuk properti ini sudah habis.'
            ];
        }

        if ($quantity > $available) {
            return [
                'valid' => false,
                'message' => "Hanya tersisa {$available} kupon untuk properti ini."
            ];
        }

        return ['valid' => true];
    }
}
