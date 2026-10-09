<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'is_customized',
        'customizable_product_id',
        'customization_data',
        'customization_preview_path',
        'artwork_file_path',
        'artwork_files',
        'customization_fee',
        'custom_production_status',
        'product_name',
        'size_name',
        'color_name',
        'color_hex',
        'sku',
        'image_path',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'is_customized' => 'boolean',
        'customization_data' => 'array',
        'artwork_files' => 'array',
        'customization_fee' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function customizableProduct()
    {
        return $this->belongsTo(CustomizableProduct::class, 'customizable_product_id');
    }

    public function getDisplayImageAttribute(): ?string
    {
        if ($this->is_customized && $this->customization_preview_path) {
            return asset($this->customization_preview_path);
        }

        return $this->image_path ? asset($this->image_path) : null;
    }
}
