<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'cta_text',
        'cta_url',
        'image_path',
        'mobile_image_path',
        'sort_order',
        'position',
        'is_active',
        'text_alignment',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    public function scopeHero($query)
    {
        return $query->where('position', 'hero')->active();
    }

    public function scopeShop($query)
    {
        return $query->where('position', 'shop')->active();
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/polos/black-polo.svg');
        }
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        if (str_starts_with($this->image_path, '/')) {
            return asset(ltrim($this->image_path, '/'));
        }
        return asset('storage/' . $this->image_path);
    }

    public function getMobileImageUrlAttribute(): string
    {
        if (!empty($this->mobile_image_path)) {
            if (str_starts_with($this->mobile_image_path, 'http://') || str_starts_with($this->mobile_image_path, 'https://')) {
                return $this->mobile_image_path;
            }
            if (str_starts_with($this->mobile_image_path, '/')) {
                return asset(ltrim($this->mobile_image_path, '/'));
            }
            return asset('storage/' . $this->mobile_image_path);
        }
        return $this->image_url;
    }
}
