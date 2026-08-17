<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category_id',
        'short_description',
        'description',
        'base_price',
        'sale_price',
        'fabric',
        'fit',
        'pattern',
        'collar_type',
        'sleeve_type',
        'wash_care',
        'country_of_origin',
        'is_bestseller',
        'is_new_arrival',
        'is_featured',
        'is_active',
        'size_chart',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_bestseller' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'size_chart' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->withDefault(function () {
            return $this->images()->first();
        });
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Colors available for this product through active variants
    public function availableColors()
    {
        return Color::whereIn('id', $this->activeVariants()->pluck('color_id')->unique())->get();
    }

    // Sizes available for this product through active variants
    public function availableSizes()
    {
        return Size::whereIn('id', $this->activeVariants()->pluck('size_id')->unique())->orderBy('sort_order')->get();
    }

    public function getEffectivePriceAttribute()
    {
        return $this->sale_price !== null && $this->sale_price > 0 ? (float) $this->sale_price : (float) $this->base_price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->base_price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->has_discount || $this->base_price <= 0) {
            return 0;
        }
        return (int) round((($this->base_price - $this->sale_price) / $this->base_price) * 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->approvedReviews()->avg('rating') ?: 5.0);
    }

    public function getReviewsCountAttribute(): int
    {
        return (int) $this->approvedReviews()->count();
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->variants()->sum('stock_quantity');
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBestsellers($query)
    {
        return $query->where('is_active', true)->where('is_bestseller', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_active', true)->where('is_new_arrival', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)->where('is_featured', true);
    }
}
