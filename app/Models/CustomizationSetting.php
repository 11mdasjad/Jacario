<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'customizable_product_id',
        'allow_front',
        'allow_back',
        'allowed_file_types',
        'max_file_size_mb',
        'allowed_fonts',
        'min_text_len',
        'max_text_len',
        'max_images',
        'print_area',
        'base_customization_fee',
        'extra_image_fee',
        'extra_text_fee',
        'bulk_pricing',
    ];

    protected $casts = [
        'allow_front' => 'boolean',
        'allow_back' => 'boolean',
        'allowed_file_types' => 'array',
        'max_file_size_mb' => 'integer',
        'allowed_fonts' => 'array',
        'min_text_len' => 'integer',
        'max_text_len' => 'integer',
        'max_images' => 'integer',
        'print_area' => 'array',
        'base_customization_fee' => 'decimal:2',
        'extra_image_fee' => 'decimal:2',
        'extra_text_fee' => 'decimal:2',
        'bulk_pricing' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(CustomizableProduct::class, 'customizable_product_id');
    }

    public function calculateBulkDiscount(int $quantity): float
    {
        if (empty($this->bulk_pricing) || !is_array($this->bulk_pricing)) {
            return 0.0;
        }

        $highestDiscount = 0.0;
        foreach ($this->bulk_pricing as $tier) {
            $minQty = (int) ($tier['min_qty'] ?? 0);
            $discountPct = (float) ($tier['discount_percent'] ?? 0);
            if ($quantity >= $minQty && $discountPct > $highestDiscount) {
                $highestDiscount = $discountPct;
            }
        }

        return $highestDiscount;
    }
}
