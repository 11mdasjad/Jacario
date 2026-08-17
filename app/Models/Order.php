<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'tax_amount',
        'total_amount',
        'coupon_code',
        'status',
        'payment_method',
        'payment_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'billing_address',
        'tracking_number',
        'courier_name',
        'notes',
        'cancelled_reason',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function getIsPaidAttribute(): bool
    {
        return in_array($this->payment_status, ['authorized', 'captured', 'paid']);
    }

    public function getCanBeCancelledAttribute(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'processing']);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
            'processing' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'packed' => 'bg-purple-100 text-purple-800 border-purple-200',
            'shipped' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
            'out_for_delivery' => 'bg-orange-100 text-orange-800 border-orange-200',
            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
            'returned', 'refunded' => 'bg-gray-100 text-gray-800 border-gray-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function getPaymentStatusBadgeColorAttribute(): string
    {
        return match ($this->payment_status) {
            'captured', 'authorized', 'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'failed' => 'bg-rose-100 text-rose-800 border-rose-200',
            'refunded' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function getTimelineStepsAttribute(): array
    {
        $allSteps = [
            'confirmed' => ['label' => 'Order Placed', 'icon' => 'check-circle'],
            'processing' => ['label' => 'Processing', 'icon' => 'cog'],
            'packed' => ['label' => 'Packed', 'icon' => 'archive'],
            'shipped' => ['label' => 'Shipped', 'icon' => 'truck'],
            'out_for_delivery' => ['label' => 'Out for Delivery', 'icon' => 'map-pin'],
            'delivered' => ['label' => 'Delivered', 'icon' => 'home'],
        ];

        $statusIndex = [
            'pending' => 0,
            'confirmed' => 1,
            'processing' => 2,
            'packed' => 3,
            'shipped' => 4,
            'out_for_delivery' => 5,
            'delivered' => 6,
        ];

        $currentLevel = $statusIndex[$this->status] ?? 1;

        $result = [];
        $i = 1;
        foreach ($allSteps as $key => $data) {
            $isCompleted = $i <= $currentLevel;
            $isCurrent = ($this->status === $key);
            $result[] = [
                'key' => $key,
                'label' => $data['label'],
                'icon' => $data['icon'],
                'completed' => $isCompleted,
                'current' => $isCurrent,
            ];
            $i++;
        }

        return $result;
    }

    public function getFormattedShippingAddressAttribute(): string
    {
        if (is_array($this->shipping_address)) {
            $parts = array_filter([
                $this->shipping_address['address_line_1'] ?? null,
                $this->shipping_address['address_line_2'] ?? null,
                $this->shipping_address['city'] ?? null,
                $this->shipping_address['state'] ?? null,
                $this->shipping_address['postal_code'] ?? null,
                $this->shipping_address['country'] ?? 'India',
            ]);
            return implode(', ', $parts);
        }

        return (string) $this->shipping_address;
    }
}
