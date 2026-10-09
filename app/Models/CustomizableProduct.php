<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizableProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'short_description',
        'description',
        'base_price',
        'discount_price',
        'sku',
        'featured_image',
        'mockup_views',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'mockup_views' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function variants()
    {
        return $this->hasMany(CustomizableProductVariant::class);
    }

    public function settings()
    {
        return $this->hasOne(CustomizationSetting::class);
    }

    public function savedDesigns()
    {
        return $this->hasMany(SavedCustomDesign::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->base_price) {
            return (float) $this->discount_price;
        }

        return (float) $this->base_price;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->effective_price, 2);
    }

    public function getFormattedBasePriceAttribute(): string
    {
        return '₹' . number_format($this->base_price, 2);
    }
}
