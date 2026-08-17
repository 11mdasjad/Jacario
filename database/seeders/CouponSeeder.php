<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'JACARIO10',
                'description' => '10% off on all orders above ₹1,000',
                'discount_type' => 'percentage',
                'value' => 10.00,
                'min_order_value' => 1000.00,
                'max_discount' => 500.00,
                'usage_limit' => 1000,
                'usage_limit_per_user' => 3,
                'starts_at' => Carbon::now()->subMonths(1),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'FIRSTPOLO',
                'description' => 'Flat ₹300 off on your first JACARIO order above ₹1,499',
                'discount_type' => 'fixed',
                'value' => 300.00,
                'min_order_value' => 1499.00,
                'max_discount' => null,
                'usage_limit' => 500,
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now()->subMonths(1),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'LUXURY20',
                'description' => '20% off on premium orders above ₹2,999 (Max ₹1,000)',
                'discount_type' => 'percentage',
                'value' => 20.00,
                'min_order_value' => 2999.00,
                'max_discount' => 1000.00,
                'usage_limit' => 200,
                'usage_limit_per_user' => 2,
                'starts_at' => Carbon::now()->subMonths(1),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'VIP500',
                'description' => 'Flat ₹500 off on luxury orders above ₹3,500',
                'discount_type' => 'fixed',
                'value' => 500.00,
                'min_order_value' => 3500.00,
                'max_discount' => null,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => Carbon::now()->subMonths(1),
                'expires_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $c) {
            Coupon::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
