<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'property_id',
        'quantity',
        'total_price',
        'status',
        'paid_at',
        'transfer_proof',
        'seller_bank_info',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'seller_bank_info' => 'array',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'awaiting_verification' => 'bg-info',
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
            'awaiting_verification' => 'Menunggu Verifikasi',
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

    /**
     * Check if order has transfer proof uploaded
     */
    public function hasTransferProof()
    {
        return !empty($this->transfer_proof);
    }

    /**
     * Check if order is verified by seller
     */
    public function isVerified()
    {
        return !empty($this->verified_by) && !empty($this->verified_at);
    }

    /**
     * Get seller from property relationship
     */
    public function getSeller()
    {
        return $this->property->seller ?? null;
    }

    /**
     * Mark order as awaiting verification after transfer proof upload
     */
    public function markAwaitingVerification($transferProofPath, $sellerBankInfo = null)
    {
        $this->update([
            'status' => 'awaiting_verification',
            'transfer_proof' => $transferProofPath,
            'seller_bank_info' => $sellerBankInfo,
        ]);
    }

    /**
     * Verify the manual transfer payment
     */
    public function verifyTransfer($verifierId, $notes = null)
    {
        DB::transaction(function () use ($verifierId, $notes) {
            $this->update([
                'status' => 'paid',
                'verified_by' => $verifierId,
                'verified_at' => now(),
                'paid_at' => now(),
                'verification_notes' => $notes,
            ]);

            $this->createTransaction();

            $this->createCoupons();
        });
    }

    /**
     * Create transaction record for this order
     */
    protected function createTransaction()
    {
        Transaction::create([
            'order_id' => $this->id,
            'amount' => $this->total_price,
            'payment_method' => 'bank_transfer',
            'status' => 'completed',
            'gateway_response' => [
                'verified_by' => $this->verified_by,
                'verified_at' => $this->verified_at,
                'transfer_proof' => $this->transfer_proof,
            ],
        ]);
    }

    /**
     * Create coupons for the buyer
     */
    protected function createCoupons()
    {
        for ($i = 0; $i < $this->quantity; $i++) {
            Coupon::create([
                'order_id' => $this->id,
                'buyer_id' => $this->buyer_id,
                'property_id' => $this->property_id,
                'coupon_number' => $this->generateCouponNumber(),
                'is_winner' => false,
            ]);
        }
    }

    /**
     * Generate unique coupon number
     */
    protected function generateCouponNumber()
    {
        do {
            $couponNumber = 'CPN-' . $this->property_id . '-' . strtoupper(uniqid());
        } while (Coupon::where('coupon_number', $couponNumber)->exists());

        return $couponNumber;
    }

    /**
     * Reject the manual transfer payment
     */
    public function rejectTransfer($verifierId, $notes = null)
    {
        $this->update([
            'status' => 'failed',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Scope for orders that need verification (for sellers)
     */
    public function scopeAwaitingVerification($query)
    {
        return $query->where('status', 'awaiting_verification');
    }

    /**
     * Scope for orders by seller (through property relationship)
     */
    public function scopeBySeller($query, $sellerId)
    {
        return $query->whereHas('property', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        });
    }
}
