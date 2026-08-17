<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->subtotal;
        });
    }

    public function getDiscountAmountAttribute(): float
    {
        if (!$this->coupon_code) {
            return 0.0;
        }

        $coupon = Coupon::where('code', $this->coupon_code)->where('is_active', true)->first();
        if (!$coupon || !$coupon->isValid($this->user_id, $this->subtotal)) {
            return 0.0;
        }

        return $coupon->calculateDiscount($this->subtotal);
    }

    public function getShippingAmountAttribute(): float
    {
        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 1999);
        $standardShippingRate = (float) Setting::get('standard_shipping_rate', 150);

        if ($this->subtotal <= 0) {
            return 0.0;
        }

        return $this->subtotal >= $freeShippingThreshold ? 0.0 : $standardShippingRate;
    }

    public function getTaxAmountAttribute(): float
    {
        // 5% GST on apparel if enabled, or 0 if all inclusive
        $taxRate = (float) Setting::get('tax_rate', 0);
        if ($taxRate <= 0) {
            return 0.0;
        }

        $taxableAmount = max(0, $this->subtotal - $this->discount_amount);
        return round(($taxableAmount * $taxRate) / 100, 2);
    }

    public function getTotalAttribute(): float
    {
        $taxable = max(0, $this->subtotal - $this->discount_amount);
        return round($taxable + $this->shipping_amount + $this->tax_amount, 2);
    }

    public function getItemCountAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }
}
