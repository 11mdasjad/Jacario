<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'is_customized',
        'customizable_product_id',
        'custom_color_id',
        'custom_size_id',
        'customization_data',
        'customization_preview_path',
        'artwork_file_path',
        'artwork_files',
        'customization_fee',
        'quantity',
        'unit_price',
    ];

    protected $casts = [
        'is_customized' => 'boolean',
        'customization_data' => 'array',
        'artwork_files' => 'array',
        'customization_fee' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function customizableProduct()
    {
        return $this->belongsTo(CustomizableProduct::class, 'customizable_product_id');
    }

    public function customColor()
    {
        return $this->belongsTo(Color::class, 'custom_color_id');
    }

    public function customSize()
    {
        return $this->belongsTo(Size::class, 'custom_size_id');
    }

    public function getSubtotalAttribute(): float
    {
        return round((float) $this->unit_price * $this->quantity, 2);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_customized && $this->customizableProduct) {
            return 'Customized ' . $this->customizableProduct->name;
        }

        return $this->variant && $this->variant->product ? $this->variant->product->name : 'Polo Item';
    }

    public function getDisplayImageAttribute(): ?string
    {
        if ($this->is_customized && $this->customization_preview_path) {
            return asset($this->customization_preview_path);
        }

        if ($this->variant && $this->variant->product) {
            $img = $this->variant->product->images->firstWhere('color_id', $this->variant->color_id) ?: $this->variant->product->primaryImage;
            return $img ? asset($img->image_path) : null;
        }

        return null;
    }
}
