<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizableProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'customizable_product_id',
        'size_id',
        'color_id',
        'stock_quantity',
        'price_override',
        'is_active',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'price_override' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(CustomizableProduct::class, 'customizable_product_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->price_override !== null && $this->price_override > 0) {
            return (float) $this->price_override;
        }

        return $this->product ? $this->product->effective_price : 0.0;
    }
}
