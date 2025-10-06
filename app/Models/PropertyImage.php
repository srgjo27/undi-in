<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image_path',
        'is_primary',
        'caption',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getImageUrlAttribute()
    {
        if (Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            // If this is set as primary, unset all other primary images for this property
            if ($image->is_primary) {
                static::where('property_id', $image->property_id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::updating(function ($image) {
            // If this is set as primary, unset all other primary images for this property
            if ($image->is_primary && $image->isDirty('is_primary')) {
                static::where('property_id', $image->property_id)
                    ->where('id', '!=', $image->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });

        static::deleting(function ($image) {
            // Delete the actual file
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }
}
