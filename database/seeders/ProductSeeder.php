<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $poloCat = Category::where('slug', 'mens-polo-t-shirts')->first();
        $roundNeckCat = Category::where('slug', 'round-neck-t-shirts')->first();
        $newArrivalCat = Category::where('slug', 'new-arrival-t-shirts')->first();

        $sizes = Size::all();
        $colors = Color::all();

        $productsData = [
            // --- 1. MEN'S POLO T-SHIRTS ---
            [
                'name' => "JACARIO Signature Obsidian Supima Polo",
                'slug' => 'jacario-signature-obsidian-supima-polo',
                'sku' => 'JAC-POLO-001',
                'category_id' => $poloCat->id,
                'base_price' => 2499.00,
                'sale_price' => 1999.00,
                'short_description' => 'The definitive black luxury polo. Knitted from 100% American Supima® cotton with mother-of-pearl buttons.',
                'description' => "Engineered from long-staple 100% American Supima® cotton (240 GSM) with an anti-curl ribbed collar and authentic Australian mother-of-pearl buttons.\n\nPre-washed and thermally stabilized to prevent shrinkage, ensuring an immaculate silhouette through years of wear.",
                'fabric' => '100% American Supima® Cotton (240 GSM)',
                'fit' => 'Tailored Regular Fit',
                'collar_type' => 'Stay-Flat Structured Ribbed Collar',
                'sleeve_type' => 'Short Sleeve with Knitted Arm Cuffs',
                'wash_care' => 'Machine wash cold inside out, dry flat in shade',
                'country_of_origin' => 'India',
                'is_bestseller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'obsidian-black',
            ],
            [
                'name' => "JACARIO Amalfi Navy Silk-Cotton Polo",
                'slug' => 'jacario-amalfi-navy-silk-cotton-polo',
                'sku' => 'JAC-POLO-002',
                'category_id' => $poloCat->id,
                'base_price' => 2899.00,
                'sale_price' => 2399.00,
                'short_description' => 'A regal deep navy polo blended with 30% Mulberry silk for exceptional breathability and fluid drape.',
                'description' => "Our Amalfi Navy polo combines 70% Egyptian Mako cotton with 30% Grade-6A Mulberry silk. Featherweight, cool to the touch, and designed with a seamless 3-button placket.",
                'fabric' => '70% Egyptian Cotton, 30% Mulberry Silk',
                'fit' => 'Tailored Slim Fit',
                'collar_type' => 'Italian Spread Collar',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Hand wash cold or dry clean recommended',
                'country_of_origin' => 'India',
                'is_bestseller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'amalfi-navy',
            ],
            [
                'name' => "JACARIO Pure Ivory Piqué Classic Polo",
                'slug' => 'jacario-pure-ivory-pique-classic-polo',
                'sku' => 'JAC-POLO-003',
                'category_id' => $poloCat->id,
                'base_price' => 2299.00,
                'sale_price' => 1899.00,
                'short_description' => 'Crisp, luminous ivory white polo with high-density honeycomb piqué knit structure.',
                'description' => "Crafted from double-mercerized combed cotton for a brilliant non-translucent ivory tone. Features heat-sealed cross-stitch button anchoring and reinforced side vents.",
                'fabric' => '100% Double-Mercerized Combed Cotton (230 GSM)',
                'fit' => 'Classic Regular Fit',
                'collar_type' => 'Stay-Flat Ribbed Collar',
                'sleeve_type' => 'Short Sleeve with Elastic Rib',
                'wash_care' => 'Machine wash cold with whites only',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => true,
                'is_new_arrival' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'pure-ivory-white',
            ],
            [
                'name' => "JACARIO Tuscany Olive Dusk Knit Polo",
                'slug' => 'jacario-tuscany-olive-dusk-knit-polo',
                'sku' => 'JAC-POLO-004',
                'category_id' => $poloCat->id,
                'base_price' => 2699.00,
                'sale_price' => 2199.00,
                'short_description' => 'Earthy olive hue in high-density piqué knit. Perfect for Mediterranean evenings and smart tailoring.',
                'description' => "Dyed using reactive pigment techniques that retain rich olive depth across washes. Finished with reinforced shoulder tape and custom carved oyster shell buttons.",
                'fabric' => '100% Supima® Cotton (240 GSM)',
                'fit' => 'Tailored Fit',
                'collar_type' => 'Structured Ribbed Collar',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold inside out',
                'country_of_origin' => 'India',
                'is_bestseller' => true,
                'is_featured' => false,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'olive-dusk',
            ],
            [
                'name' => "JACARIO Bordeaux Royale Luxury Polo",
                'slug' => 'jacario-bordeaux-royale-luxury-polo',
                'sku' => 'JAC-POLO-005',
                'category_id' => $poloCat->id,
                'base_price' => 2799.00,
                'sale_price' => 2299.00,
                'short_description' => 'Opulent wine red polo with velvety soft handfeel and structured collar construction.',
                'description' => "Inspired by the vintage wine châteaux of France, this rich Bordeaux Royale polo is woven with double-twisted yarns for enhanced structural drape and zero pill guarantee.",
                'fabric' => '100% Extra-Long Staple Cotton',
                'fit' => 'Tailored Regular Fit',
                'collar_type' => 'Anti-Curl Interlined Collar',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold with darks',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => true,
                'is_new_arrival' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'burgundy-royale',
            ],
            [
                'name' => "JACARIO Sahara Desert Sand Tailored Polo",
                'slug' => 'jacario-sahara-desert-sand-tailored-polo',
                'sku' => 'JAC-POLO-006',
                'category_id' => $poloCat->id,
                'base_price' => 2499.00,
                'sale_price' => 1999.00,
                'short_description' => 'Refined warm neutral sand tone engineered for summer effortless sophistication.',
                'description' => "A versatile neutral polo that pairs seamlessly with white trousers and linen blazers. Designed with a clean clean-cut 2-button placket and micro-ribbed cuffs.",
                'fabric' => '100% Supima® Cotton',
                'fit' => 'Tailored Regular Fit',
                'collar_type' => 'Ribbed Spread Collar',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => false,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'desert-sand',
            ],

            // --- 2. ROUND NECK T-SHIRTS ---
            [
                'name' => "JACARIO Essential Pitch Black Round Neck Tee",
                'slug' => 'jacario-essential-pitch-black-round-neck-tee',
                'sku' => 'JAC-RND-001',
                'category_id' => $roundNeckCat->id,
                'base_price' => 1499.00,
                'sale_price' => 1199.00,
                'short_description' => 'The ultimate heavyweight black crew neck t-shirt. Clean, sculpted, and unyielding in collar shape.',
                'description' => "Crafted from 100% organic long-staple combed cotton (220 GSM). Engineered with a high-density 1x1 rib neckline that maintains its crisp circle wash after wash without sagging.",
                'fabric' => '100% Organic Combed Cotton (220 GSM)',
                'fit' => 'Tailored Sculpted Fit',
                'collar_type' => 'Reinforced Seamless Crew Neck',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold inside out',
                'country_of_origin' => 'India',
                'is_bestseller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'obsidian-black',
            ],
            [
                'name' => "JACARIO Heavyweight Minimalist White Round Neck Tee",
                'slug' => 'jacario-heavyweight-minimalist-white-round-neck-tee',
                'sku' => 'JAC-RND-002',
                'category_id' => $roundNeckCat->id,
                'base_price' => 1499.00,
                'sale_price' => 1199.00,
                'short_description' => 'Premium non-see-through heavyweight white crew neck tee with silky mercerized finish.',
                'description' => "Designed for everyday luxury. Completely opaque and breathable with double-needle hems and blind-stitched sleeves for a pristine minimalist aesthetic.",
                'fabric' => '100% Mercerized Cotton (220 GSM)',
                'fit' => 'Sculpted Regular Fit',
                'collar_type' => 'Stay-Round Elastic Rib Collar',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold with whites',
                'country_of_origin' => 'India',
                'is_bestseller' => true,
                'is_featured' => true,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'pure-ivory-white',
            ],
            [
                'name' => "JACARIO Nautical Deep Navy Round Neck Tee",
                'slug' => 'jacario-nautical-deep-navy-round-neck-tee',
                'sku' => 'JAC-RND-003',
                'category_id' => $roundNeckCat->id,
                'base_price' => 1499.00,
                'sale_price' => 1199.00,
                'short_description' => 'Deep maritime navy crew neck with luminous cotton luster and ultra-soft handle.',
                'description' => "Pre-shrunk, bio-washed cotton engineered to drape naturally over the shoulders without excess bulk around the midriff.",
                'fabric' => '100% Supima® Cotton',
                'fit' => 'Tailored Fit',
                'collar_type' => 'Reinforced Crew Neck',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => false,
                'is_new_arrival' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'amalfi-navy',
            ],
            [
                'name' => "JACARIO Heather Grey Melange Round Neck Tee",
                'slug' => 'jacario-heather-grey-melange-round-neck-tee',
                'sku' => 'JAC-RND-004',
                'category_id' => $roundNeckCat->id,
                'base_price' => 1499.00,
                'sale_price' => 1199.00,
                'short_description' => 'Classic tri-blend textured grey crew neck with athletic stretch and featherweight breathability.',
                'description' => "Woven with multi-tonal grey fibers that offer subtle heathering and exceptional tactile comfort. Perfect under unbuttoned overshirts or jackets.",
                'fabric' => '95% Combed Cotton, 5% Elastane',
                'fit' => 'Athletic Regular Fit',
                'collar_type' => 'Ribbed Crew Collar',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => true,
                'is_new_arrival' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'heather-grey',
            ],

            // --- 3. NEW ARRIVAL T-SHIRTS ---
            [
                'name' => "JACARIO Royal Sapphire Silk-Knit Polo",
                'slug' => 'jacario-royal-sapphire-silk-knit-polo',
                'sku' => 'JAC-NEW-001',
                'category_id' => $newArrivalCat->id,
                'base_price' => 3199.00,
                'sale_price' => 2699.00,
                'short_description' => 'New Season Drop: Vibrant royal sapphire blue polo with fine-gauge knit and open Johnny collar.',
                'description' => "An exquisite new arrival designed with a retro open collar (no buttons) and rib-hem finish. Crafted from pure Mulberry silk-cotton blend for unparalleled summer luxury.",
                'fabric' => '60% Mulberry Silk, 40% Egyptian Cotton',
                'fit' => 'Relaxed Sartorial Fit',
                'collar_type' => 'Open Riviera Johnny Collar',
                'sleeve_type' => 'Short Sleeve with Ribbed Cuffs',
                'wash_care' => 'Dry clean or gentle hand wash',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => true,
                'is_new_arrival' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'sapphire-blue',
            ],
            [
                'name' => "JACARIO Forest Emerald Textured Knit Tee",
                'slug' => 'jacario-forest-emerald-textured-knit-tee',
                'sku' => 'JAC-NEW-002',
                'category_id' => $newArrivalCat->id,
                'base_price' => 1799.00,
                'sale_price' => 1499.00,
                'short_description' => 'New Season Drop: Micro-waffle textured forest green crew neck tee with tailored European shoulders.',
                'description' => "Our latest seasonal introduction featuring a subtle micro-waffle honeycomb weave that adds tactile dimensionality to a classic round neck silhouette.",
                'fabric' => '100% Micro-Waffle Pima Cotton (230 GSM)',
                'fit' => 'Tailored Slim Fit',
                'collar_type' => 'Ribbed Crew Neck',
                'sleeve_type' => 'Short Sleeve',
                'wash_care' => 'Machine wash cold',
                'country_of_origin' => 'India',
                'is_bestseller' => false,
                'is_featured' => true,
                'is_new_arrival' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80',
                ],
                'default_color' => 'forest-emerald',
            ],
        ];

        foreach ($productsData as $data) {
            $images = $data['images'];
            $defaultColorSlug = $data['default_color'];
            unset($data['images'], $data['default_color']);

            $product = Product::updateOrCreate(['slug' => $data['slug']], $data);

            // Create product images
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($images as $idx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgUrl,
                    'alt_text' => $product->name,
                    'sort_order' => $idx + 1,
                    'is_primary' => ($idx === 0),
                ]);
            }

            // Create full matrix of variants for this product
            ProductVariant::where('product_id', $product->id)->delete();
            $defaultColor = Color::where('slug', $defaultColorSlug)->first() ?: $colors->first();

            // Primary colorway variants
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                    'color_id' => $defaultColor->id,
                    'sku' => $product->sku . '-' . $defaultColor->slug . '-' . $size->code,
                    'price' => null,
                    'sale_price' => null,
                    'stock_quantity' => rand(10, 30),
                    'is_active' => true,
                ]);
            }

            // Also attach 2-3 additional colorways to give each product color options
            $extraColors = $colors->where('id', '!=', $defaultColor->id)->take(3);
            foreach ($extraColors as $color) {
                foreach ($sizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'sku' => $product->sku . '-' . $color->slug . '-' . $size->code,
                        'price' => null,
                        'sale_price' => null,
                        'stock_quantity' => rand(5, 20),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
