<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\CustomizableProduct;
use App\Models\CustomizableProductVariant;
use App\Models\CustomizationSetting;
use App\Models\Size;
use Illuminate\Database\Seeder;

class CustomizationSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure 3XL size exists if needed
        Size::firstOrCreate(
            ['code' => '3XL'],
            [
                'name' => '3XL',
                'chest' => '48',
                'length' => '31',
                'shoulder' => '21',
                'sort_order' => 6,
            ]
        );

        $sizes = Size::all();
        $colors = Color::all();

        // 1. Customize Polo T-Shirt
        $polo = CustomizableProduct::updateOrCreate(
            ['slug' => 'polo-t-shirt'],
            [
                'name' => 'Customize Polo T-Shirt',
                'type' => 'polo_tshirt',
                'short_description' => 'Engineered from signature 240 GSM double-pique cotton with reinforced knit collar and mother-of-pearl buttons. Bespoke embroidery & HD printing ready.',
                'description' => 'Craft your bespoke JACARIO Polo T-Shirt. Crafted from 100% long-staple Supima® double-pique cotton, featuring our structured anti-curl collar, tailored sleeves, and refined split-hem. Personalize with your custom logo artwork, brand initials, or typography with state-of-the-art print precision.',
                'base_price' => 1499.00,
                'discount_price' => 1299.00,
                'sku' => 'JAC-CUST-POLO',
                'featured_image' => 'images/customizer/polo/black.jpg',
                'mockup_views' => [
                    'front' => 'images/customizer/polo/{color}.jpg',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // 2. Customize Plain T-Shirt
        $tshirt = CustomizableProduct::updateOrCreate(
            ['slug' => 't-shirt'],
            [
                'name' => 'Customize Plain T-Shirt',
                'type' => 'tshirt',
                'short_description' => 'Ultra-luxurious 220 GSM combed Supima® crewneck jersey. Ultra-soft touch, tailored modern fit, zero shrinkage.',
                'description' => 'Create your signature luxury Plain Crewneck T-Shirt. Made with premium combed compact organic cotton, designed for seamless drape, incredible breathability, and long-lasting durability. Upload your graphics or craft custom typography with precision placement.',
                'base_price' => 999.00,
                'discount_price' => 849.00,
                'sku' => 'JAC-CUST-TSHIRT',
                'featured_image' => 'images/customizer/tshirt/black.jpg',
                'mockup_views' => [
                    'front' => 'images/customizer/tshirt/{color}.jpg',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Populate Variants for both products
        foreach ([$polo, $tshirt] as $prod) {
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    CustomizableProductVariant::updateOrCreate(
                        [
                            'customizable_product_id' => $prod->id,
                            'size_id' => $size->id,
                            'color_id' => $color->id,
                        ],
                        [
                            'stock_quantity' => 150,
                            'is_active' => true,
                            'price_override' => null,
                        ]
                    );
                }
            }
        }

        // Customization Settings for Polo
        CustomizationSetting::updateOrCreate(
            ['customizable_product_id' => $polo->id],
            [
                'allow_front' => true,
                'allow_back' => false,
                'allowed_file_types' => ['png', 'jpg', 'jpeg', 'svg', 'webp'],
                'max_file_size_mb' => 15,
                'allowed_fonts' => [
                    'Cinzel',
                    'Plus Jakarta Sans',
                    'Inter',
                    'Playfair Display',
                    'Montserrat',
                    'Bebas Neue',
                    'Outfit',
                    'Space Grotesk',
                ],
                'min_text_len' => 1,
                'max_text_len' => 50,
                'max_images' => 4,
                'print_area' => [
                    'top' => 32,
                    'left' => 30,
                    'width' => 40,
                    'height' => 42,
                ],
                'base_customization_fee' => 0.00,
                'extra_image_fee' => 99.00,
                'extra_text_fee' => 49.00,
                'bulk_pricing' => [
                    ['min_qty' => 5, 'discount_percent' => 10],
                    ['min_qty' => 15, 'discount_percent' => 18],
                    ['min_qty' => 30, 'discount_percent' => 25],
                    ['min_qty' => 50, 'discount_percent' => 35],
                ],
            ]
        );

        // Customization Settings for T-Shirt
        CustomizationSetting::updateOrCreate(
            ['customizable_product_id' => $tshirt->id],
            [
                'allow_front' => true,
                'allow_back' => false,
                'allowed_file_types' => ['png', 'jpg', 'jpeg', 'svg', 'webp'],
                'max_file_size_mb' => 15,
                'allowed_fonts' => [
                    'Cinzel',
                    'Plus Jakarta Sans',
                    'Inter',
                    'Playfair Display',
                    'Montserrat',
                    'Bebas Neue',
                    'Outfit',
                    'Space Grotesk',
                ],
                'min_text_len' => 1,
                'max_text_len' => 50,
                'max_images' => 4,
                'print_area' => [
                    'top' => 28,
                    'left' => 28,
                    'width' => 44,
                    'height' => 46,
                ],
                'base_customization_fee' => 0.00,
                'extra_image_fee' => 99.00,
                'extra_text_fee' => 49.00,
                'bulk_pricing' => [
                    ['min_qty' => 5, 'discount_percent' => 10],
                    ['min_qty' => 15, 'discount_percent' => 18],
                    ['min_qty' => 30, 'discount_percent' => 25],
                    ['min_qty' => 50, 'discount_percent' => 35],
                ],
            ]
        );
    }
}
