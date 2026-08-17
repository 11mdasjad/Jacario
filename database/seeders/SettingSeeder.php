<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'store_name' => 'JACARIO',
            'store_tagline' => 'Premium Polo T-Shirts, Made for Every Move',
            'contact_email' => 'concierge@jacario.com',
            'contact_phone' => '+91 (0) 22 8900 1200',
            'support_hours' => 'Mon – Sat: 9:00 AM – 8:00 PM IST',
            'store_address' => 'JACARIO Flagship Atelier, 42 Heritage Boulevard, Bandra West, Mumbai, MH 400050, India',
            'free_shipping_threshold' => '1999',
            'standard_shipping_rate' => '150',
            'tax_rate' => '0', // All inclusive pricing
            'currency_symbol' => '₹',
            'currency_code' => 'INR',
            
            // Social Media
            'instagram_url' => 'https://instagram.com/jacario.official',
            'twitter_url' => 'https://twitter.com/jacario_brand',
            'facebook_url' => 'https://facebook.com/jacario.official',
            'pinterest_url' => 'https://pinterest.com/jacario_couture',

            // Brand Story & Policies
            'brand_story_excerpt' => 'At JACARIO, we do only one thing — and we do it to perfection. We obsess over the micro-details of the Polo T-Shirt: double-twisted long-staple yarns, mother-of-pearl hardware, and hand-finished collars that never curl.',
            
            'shipping_policy' => "### Shipping & Delivery
All JACARIO orders are packaged in our signature luxury presentation box and shipped via express courier.

- **Complimentary Express Shipping**: On all orders above ₹1,999.
- **Standard Express Delivery (₹150)**: Delivered within 2–4 business days across major metro cities, and 3–5 business days nationwide.
- **Order Tracking**: Real-time SMS and email notifications with live GPS tracking links as soon as your parcel departs our fulfillment atelier.",

            'return_policy' => "### Returns & Exchanges
We offer a 15-day complimentary exchange and return window on all unwashed, unworn Polo T-Shirts with original tags intact.

- **Hassle-free Doorstep Pickup**: Our courier partner will collect the parcel from your doorstep at no additional charge.
- **Instant Store Credit or Refund**: Refunds are processed back to the original payment method within 48 hours of quality inspection at our atelier.",

            'privacy_policy' => "### Privacy Policy
Your privacy is sacred at JACARIO. We protect your personal data using industry-standard 256-bit SSL encryption. We never sell, trade, or share your contact or transaction details with unauthorized third parties.",

            'terms_conditions' => "### Terms & Conditions
Welcome to JACARIO. By accessing our platform, you agree to our standard terms of purchase, authenticity guarantees, and fair usage guidelines.",
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
