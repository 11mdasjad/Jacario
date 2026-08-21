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

        // Fallback if categories are not present
        if (!$poloCat) {
            $poloCat = Category::firstOrCreate(['slug' => 'mens-polo-t-shirts'], [
                'name' => "Men's Polo T-Shirts",
                'description' => 'Tailored luxury polo t-shirts engineered from Supima® cotton, Mulberry silk blends, and stay-flat collars.',
                'image' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'sort_order' => 1
            ]);
        }

        $sizes = Size::all();
        $colors = Color::all();

        // 50 High-Quality Real Polo T-Shirt Fashion Photography Assets (Unsplash curations)
        $curatedImages = [
            'black_1' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80',
            'black_2' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
            'navy_1' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900&auto=format&fit=crop&q=80',
            'navy_2' => 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
            'white_1' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900&auto=format&fit=crop&q=80',
            'white_2' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
            'olive_1' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=900&auto=format&fit=crop&q=80',
            'olive_2' => 'https://images.unsplash.com/photo-1618354691438-25bc04584c23?w=900&auto=format&fit=crop&q=80',
            'grey_1' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
            'grey_2' => 'https://images.unsplash.com/photo-1625910513413-5fc5d2a67e4e?w=900&auto=format&fit=crop&q=80',
            'blue_1' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=900&auto=format&fit=crop&q=80',
            'blue_2' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=900&auto=format&fit=crop&q=80',
            'burgundy_1' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
            'burgundy_2' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=900&auto=format&fit=crop&q=80',
            'sand_1' => 'https://images.unsplash.com/photo-1622445268462-328fb1660d00?w=900&auto=format&fit=crop&q=80',
            'sand_2' => 'https://images.unsplash.com/photo-1589902860314-e910697dea18?w=900&auto=format&fit=crop&q=80',
            'green_1' => 'https://images.unsplash.com/photo-1618354691229-88d47f285158?w=900&auto=format&fit=crop&q=80',
            'green_2' => 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
            'mustard_1' => 'https://images.unsplash.com/photo-1503342452485-86b7f54527ef?w=900&auto=format&fit=crop&q=80',
            'charcoal_1' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=900&auto=format&fit=crop&q=80',
        ];

        // 50 Definitive Polo T-Shirts Catalog for JACARIO
        $products = [
            // --- SECTION 1: NEW ARRIVALS (1 to 8) ---
            [
                'name' => 'JACARIO Riviera Amalfi Silk Polo',
                'sku' => 'JAC-POLO-001',
                'base_price' => 2499,
                'sale_price' => 1999,
                'fabric' => '70% Egyptian Mako Cotton, 30% Mulberry Silk',
                'fit' => 'Slim Fit',
                'collar_type' => 'Italian Spread Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'sky-blue',
                'img' => [$curatedImages['blue_1'], $curatedImages['navy_1']],
                'desc' => 'Featherweight silk-cotton knit inspired by coastal yachting elegance with hand-stitched mother-of-pearl buttons.'
            ],
            [
                'name' => 'JACARIO Tuscan Olive Honeycomb Polo',
                'sku' => 'JAC-POLO-002',
                'base_price' => 1899,
                'sale_price' => 1499,
                'fabric' => '100% Organic Piqué Cotton (230 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Stay-Flat Ribbed Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'olive-dusk',
                'img' => [$curatedImages['olive_1'], $curatedImages['olive_2']],
                'desc' => 'Structured honeycomb weave with internal micro-interlining that guarantees zero collar curling through 50+ washes.'
            ],
            [
                'name' => 'JACARIO Pure Ivory Mercerized Polo',
                'sku' => 'JAC-POLO-003',
                'base_price' => 2199,
                'sale_price' => 1799,
                'fabric' => 'Double-Mercerized Supima® Cotton',
                'fit' => 'Slim Fit',
                'collar_type' => 'Classic 3-Button Placket',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'pure-white',
                'img' => [$curatedImages['white_1'], $curatedImages['white_2']],
                'desc' => 'Brilliant non-translucent ivory tone with a silken sheen, reinforced side vents, and heat-sealed seams.'
            ],
            [
                'name' => 'JACARIO Forest Emerald Textured Knit Polo',
                'sku' => 'JAC-POLO-004',
                'base_price' => 1999,
                'sale_price' => 1599,
                'fabric' => 'Stretch Cotton Piqué (95% Cotton, 5% Elastane)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Tailored Ribbed Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'forest-emerald',
                'img' => [$curatedImages['green_1'], $curatedImages['green_2']],
                'desc' => 'Deep emerald hue woven with flexible 4-way stretch for all-day comfort in tropical and temperate climates.'
            ],
            [
                'name' => 'JACARIO Royal Sapphire Johnny Collar Polo',
                'sku' => 'JAC-POLO-005',
                'base_price' => 2399,
                'sale_price' => 1899,
                'fabric' => '100% Extra-Long Staple Supima® Cotton',
                'fit' => 'Relaxed Fit',
                'collar_type' => 'Buttonless Johnny Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'royal-blue',
                'img' => [$curatedImages['blue_2'], $curatedImages['navy_2']],
                'desc' => 'A relaxed open-collar silhouette paying homage to mid-century resort wear with a contemporary tapered hem.'
            ],
            [
                'name' => 'JACARIO Sand Dune Waffle Knit Polo',
                'sku' => 'JAC-POLO-006',
                'base_price' => 1799,
                'sale_price' => 1399,
                'fabric' => 'Cotton Blend (80% Cotton, 20% Microfiber)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Ribbed Knit Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'desert-sand',
                'img' => [$curatedImages['sand_1'], $curatedImages['sand_2']],
                'desc' => 'Warm earth tone with a tactile waffle texture, engineered to stay cool and wrinkle-free all day long.'
            ],
            [
                'name' => 'JACARIO Burgundy Royale Tailored Polo',
                'sku' => 'JAC-POLO-007',
                'base_price' => 2299,
                'sale_price' => 1699,
                'fabric' => '100% Supima® Cotton (240 GSM)',
                'fit' => 'Slim Fit',
                'collar_type' => 'Structured Pointed Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'burgundy-royale',
                'img' => [$curatedImages['burgundy_1'], $curatedImages['burgundy_2']],
                'desc' => 'Rich Bordeaux wine shade with Australian mother-of-pearl accents and high-tensile twin-needle stitching.'
            ],
            [
                'name' => 'JACARIO Slate Charcoal Performance Polo',
                'sku' => 'JAC-POLO-008',
                'base_price' => 1699,
                'sale_price' => 1299,
                'fabric' => 'Performance Fabric (Moisture-Wicking Cotton Tech)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Self-Fabric Structured Collar',
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'slate-charcoal',
                'img' => [$curatedImages['charcoal_1'], $curatedImages['grey_1']],
                'desc' => 'Technical active polo engineered for effortless boardroom-to-golf-course versatility with antimicrobial finish.'
            ],

            // --- SECTION 2: BEST SELLERS (9 to 16) ---
            [
                'name' => 'JACARIO Signature Obsidian Supima Polo',
                'sku' => 'JAC-POLO-009',
                'base_price' => 2499,
                'sale_price' => 1999,
                'fabric' => '100% American Supima® Cotton (240 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Anti-Curl Structured Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => true,
                'color' => 'obsidian-black',
                'img' => [$curatedImages['black_1'], $curatedImages['black_2']],
                'desc' => 'Our #1 bestseller worldwide. Jet black, thermally pre-shrunk, with genuine iridescent oyster shell hardware.'
            ],
            [
                'name' => 'JACARIO Monaco Navy Piqué Polo',
                'sku' => 'JAC-POLO-010',
                'base_price' => 1999,
                'sale_price' => 1499,
                'fabric' => 'Premium Pique Cotton (230 GSM)',
                'fit' => 'Slim Fit',
                'collar_type' => 'Stay-Flat Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => true,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_1'], $curatedImages['navy_2']],
                'desc' => 'Deep maritime navy with a tailored arm band and reinforced stepped side split hem for optimal tucking.'
            ],
            [
                'name' => 'JACARIO Oxford White Classic Polo',
                'sku' => 'JAC-POLO-011',
                'base_price' => 1799,
                'sale_price' => 1349,
                'fabric' => 'Premium Cotton (100% Combed Compact Yarn)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Classic Polo Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => true,
                'color' => 'pure-white',
                'img' => [$curatedImages['white_1'], $curatedImages['white_2']],
                'desc' => 'The cornerstone of smart-casual dressing. Pristine optical white with double-stitched arm cuffs.'
            ],
            [
                'name' => 'JACARIO Milano Heather Grey Polo',
                'sku' => 'JAC-POLO-012',
                'base_price' => 1699,
                'sale_price' => 1299,
                'fabric' => 'Stretch Cotton (95% Cotton, 5% Spandex)',
                'fit' => 'Slim Fit',
                'collar_type' => 'Ribbed Spread Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => false,
                'color' => 'heather-grey',
                'img' => [$curatedImages['grey_1'], $curatedImages['grey_2']],
                'desc' => 'Multi-tonal heather grey with athletic micro-stretch, offering sculptured drape without clinging.'
            ],
            [
                'name' => 'JACARIO British Racing Green Polo',
                'sku' => 'JAC-POLO-013',
                'base_price' => 2099,
                'sale_price' => 1599,
                'fabric' => '100% Long-Staple Pima Cotton',
                'fit' => 'Regular Fit',
                'collar_type' => 'Stay-Flat Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => true,
                'color' => 'forest-emerald',
                'img' => [$curatedImages['green_1'], $curatedImages['olive_1']],
                'desc' => 'Heritage British racing green hue with subtle luster and unmatched colorfastness across 100 washes.'
            ],
            [
                'name' => 'JACARIO Midnight Blue Luxury Polo',
                'sku' => 'JAC-POLO-014',
                'base_price' => 2299,
                'sale_price' => 1799,
                'fabric' => 'Supima® Cotton-Silk Blend',
                'fit' => 'Slim Fit',
                'collar_type' => 'Italian Micro-Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => true,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_2'], $curatedImages['blue_1']],
                'desc' => 'A sartorial dark navy with Mulberry silk fibers providing an airy touch and natural temperature regulation.'
            ],
            [
                'name' => 'JACARIO Desert Sand Pima Polo',
                'sku' => 'JAC-POLO-015',
                'base_price' => 1899,
                'sale_price' => 1449,
                'fabric' => '100% Pima Cotton (220 GSM)',
                'fit' => 'Relaxed Fit',
                'collar_type' => 'Relaxed Open Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => false,
                'color' => 'desert-sand',
                'img' => [$curatedImages['sand_1'], $curatedImages['sand_2']],
                'desc' => 'Neutral beige tone perfect with linen trousers or tailored shorts for sun-drenched European getaways.'
            ],
            [
                'name' => 'JACARIO Venetian Maroon Ribbed Polo',
                'sku' => 'JAC-POLO-016',
                'base_price' => 1999,
                'sale_price' => 1499,
                'fabric' => 'Premium Cotton Blend',
                'fit' => 'Regular Fit',
                'collar_type' => 'Textured Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'is_featured' => false,
                'color' => 'classic-maroon',
                'img' => [$curatedImages['burgundy_1'], $curatedImages['burgundy_2']],
                'desc' => 'Regal deep maroon tone with contrasting micro-tipping along the placket for subtle sophistication.'
            ],

            // --- SECTION 3: TRENDING POLOS (17 to 24) ---
            [
                'name' => 'JACARIO Capri Sky Blue Piqué Polo',
                'sku' => 'JAC-POLO-017',
                'base_price' => 1799,
                'sale_price' => 1299,
                'fabric' => 'Premium Pique Cotton (230 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Classic Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'sky-blue',
                'img' => [$curatedImages['blue_1'], $curatedImages['blue_2']],
                'desc' => 'Vibrant Mediterranean sky blue with anti-shrink thermal treatment and soft-feel mercerized cotton.'
            ],
            [
                'name' => 'JACARIO Tuscan Mustard Knit Polo',
                'sku' => 'JAC-POLO-018',
                'base_price' => 1899,
                'sale_price' => 1399,
                'fabric' => 'Cotton Blend (85% Cotton, 15% Linen)',
                'fit' => 'Relaxed Fit',
                'collar_type' => 'Camp Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'warm-mustard',
                'img' => [$curatedImages['mustard_1'], $curatedImages['sand_1']],
                'desc' => 'Warm golden mustard shade with a touch of Mediterranean linen for airy texture and relaxed drape.'
            ],
            [
                'name' => 'JACARIO French Navy Stripe-Tipped Polo',
                'sku' => 'JAC-POLO-019',
                'base_price' => 2099,
                'sale_price' => 1599,
                'fabric' => '100% Combed Compact Cotton',
                'fit' => 'Slim Fit',
                'collar_type' => 'Tipped Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_1'], $curatedImages['navy_2']],
                'desc' => 'Navy base accented with dual ivory tipping on the collar edge for a refined collegiate finish.'
            ],
            [
                'name' => 'JACARIO Alpine White Performance Polo',
                'sku' => 'JAC-POLO-020',
                'base_price' => 1699,
                'sale_price' => 1199,
                'fabric' => 'Performance Fabric (Quick-Dry Breathable Piqué)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Laser-Fused Flat Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'pure-white',
                'img' => [$curatedImages['white_2'], $curatedImages['white_1']],
                'desc' => 'Engineered for golf, tennis, and warm city days. Hydrophilic micro-pores wick sweat in seconds.'
            ],
            [
                'name' => 'JACARIO Vintage Charcoal Enzyme Polo',
                'sku' => 'JAC-POLO-021',
                'base_price' => 1799,
                'sale_price' => 1349,
                'fabric' => 'Enzyme-Washed Cotton (220 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Soft-Roll Rib Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'slate-charcoal',
                'img' => [$curatedImages['charcoal_1'], $curatedImages['grey_1']],
                'desc' => 'Enzyme-washed for that perfectly lived-in feel on day one, with reinforced placket stitching.'
            ],
            [
                'name' => 'JACARIO Portofino Ivory Silk-Blend Polo',
                'sku' => 'JAC-POLO-022',
                'base_price' => 2499,
                'sale_price' => 1999,
                'fabric' => '75% Pima Cotton, 25% Silk',
                'fit' => 'Slim Fit',
                'collar_type' => 'Italian Button-Down Polo Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'ivory-cream',
                'img' => [$curatedImages['white_1'], $curatedImages['sand_2']],
                'desc' => 'Features hidden collar buttons that keep the collar neatly upright under blazers and sport coats.'
            ],
            [
                'name' => 'JACARIO Olive Green Tactile Knit Polo',
                'sku' => 'JAC-POLO-023',
                'base_price' => 1899,
                'sale_price' => 1399,
                'fabric' => 'Stretch Cotton Piqué',
                'fit' => 'Slim Fit',
                'collar_type' => 'Stay-Flat Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'olive-dusk',
                'img' => [$curatedImages['olive_2'], $curatedImages['olive_1']],
                'desc' => 'Muted army olive with high-density yarn weave for supreme durability and sharp silhouette retention.'
            ],
            [
                'name' => 'JACARIO Royal Cobalt Stretch Polo',
                'sku' => 'JAC-POLO-024',
                'base_price' => 1799,
                'sale_price' => 1299,
                'fabric' => 'Stretch Cotton (96% Cotton, 4% Elastane)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Ribbed Polo Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'royal-blue',
                'img' => [$curatedImages['blue_2'], $curatedImages['navy_1']],
                'desc' => 'High-impact electric cobalt blue that resists fading, treated with optical brightness enhancers.'
            ],

            // --- SECTION 4: PREMIUM COLLECTION (25 to 32) ---
            [
                'name' => 'JACARIO Haute Atelier Mulberry Silk Polo',
                'sku' => 'JAC-POLO-025',
                'base_price' => 2499,
                'sale_price' => null,
                'fabric' => '60% Grade-6A Mulberry Silk, 40% Giza Cotton',
                'fit' => 'Slim Fit',
                'collar_type' => 'Seamless Milano Knit Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'obsidian-black',
                'img' => [$curatedImages['black_1'], $curatedImages['black_2']],
                'desc' => 'The crown jewel of the JACARIO atelier. Woven from Grade-6A Mulberry silk for an incomparable liquid drape.'
            ],
            [
                'name' => 'JACARIO Giza 87 Egyptian Cotton Polo',
                'sku' => 'JAC-POLO-026',
                'base_price' => 2399,
                'sale_price' => null,
                'fabric' => '100% Certified Egyptian Giza 87 Cotton',
                'fit' => 'Regular Fit',
                'collar_type' => 'Hand-Stitched Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_1'], $curatedImages['blue_1']],
                'desc' => 'Harvested along the Nile delta, Giza 87 is the world’s most uniform cotton yarn with a cashmere-like hand.'
            ],
            [
                'name' => 'JACARIO Cashmere-Touch Supima Polo',
                'sku' => 'JAC-POLO-027',
                'base_price' => 2499,
                'sale_price' => 2199,
                'fabric' => '100% American Supima® Cotton (Brushed)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Stay-Flat Structured Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'heather-grey',
                'img' => [$curatedImages['grey_1'], $curatedImages['grey_2']],
                'desc' => 'Micro-brushed surface yields a buttery soft texture reminiscent of fine Scottish cashmere.'
            ],
            [
                'name' => 'JACARIO Florence Heritage Pearl Polo',
                'sku' => 'JAC-POLO-028',
                'base_price' => 2299,
                'sale_price' => null,
                'fabric' => 'Double-Mercerized Combed Cotton',
                'fit' => 'Slim Fit',
                'collar_type' => 'Italian Spread Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'pure-white',
                'img' => [$curatedImages['white_1'], $curatedImages['white_2']],
                'desc' => 'Adorned with genuine 4mm thick Australian mother-of-pearl buttons with laser-etched JACARIO insignia.'
            ],
            [
                'name' => 'JACARIO Como Silk-Knit Open Collar Polo',
                'sku' => 'JAC-POLO-029',
                'base_price' => 2499,
                'sale_price' => 1999,
                'fabric' => '50% Mulberry Silk, 50% Supima® Cotton',
                'fit' => 'Relaxed Fit',
                'collar_type' => 'Open Riviera Johnny Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'forest-emerald',
                'img' => [$curatedImages['green_1'], $curatedImages['olive_2']],
                'desc' => 'Knit on 18-gauge precision Japanese machines for seamless finish and ultra-clean contours.'
            ],
            [
                'name' => 'JACARIO Imperial Bordeaux Pima Polo',
                'sku' => 'JAC-POLO-030',
                'base_price' => 2199,
                'sale_price' => null,
                'fabric' => '100% Peruvian Pima Cotton (240 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Reinforced Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'burgundy-royale',
                'img' => [$curatedImages['burgundy_1'], $curatedImages['burgundy_2']],
                'desc' => 'Hand-picked Peruvian Pima fibers known for exceptional staple length and resistance to pilling.'
            ],
            [
                'name' => 'JACARIO Monaco Gold Monogram Polo',
                'sku' => 'JAC-POLO-031',
                'base_price' => 2399,
                'sale_price' => 1899,
                'fabric' => '100% Supima® Cotton (240 GSM)',
                'fit' => 'Slim Fit',
                'collar_type' => 'Tailored Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'obsidian-black',
                'img' => [$curatedImages['black_2'], $curatedImages['black_1']],
                'desc' => 'Subtle tonal metallic crest embroidery on left chest and gold-threaded collar interior.'
            ],
            [
                'name' => 'JACARIO Riviera Sandstone Silk Polo',
                'sku' => 'JAC-POLO-032',
                'base_price' => 2499,
                'sale_price' => null,
                'fabric' => '70% Supima Cotton, 30% Mulberry Silk',
                'fit' => 'Regular Fit',
                'collar_type' => 'Italian Spread Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'desert-sand',
                'img' => [$curatedImages['sand_1'], $curatedImages['sand_2']],
                'desc' => 'Understated luxury in sandstone beige with a subtle sheen that reflects natural sunlight gorgeously.'
            ],

            // --- SECTION 5: EVERYDAY ESSENTIALS (33 to 40) ---
            [
                'name' => 'JACARIO Essential Pitch Black Polo',
                'sku' => 'JAC-POLO-033',
                'base_price' => 999,
                'sale_price' => 799,
                'fabric' => '100% Combed Cotton (210 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'obsidian-black',
                'img' => [$curatedImages['black_1'], $curatedImages['black_2']],
                'desc' => 'Your daily workhorse polo. Breathable 100% combed cotton built for everyday rotation.'
            ],
            [
                'name' => 'JACARIO Essential Crisp White Polo',
                'sku' => 'JAC-POLO-034',
                'base_price' => 999,
                'sale_price' => 799,
                'fabric' => '100% Combed Cotton (210 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'pure-white',
                'img' => [$curatedImages['white_1'], $curatedImages['white_2']],
                'desc' => 'Pure white everyday tee with ribbed collar and comfortable, non-constricting arm bands.'
            ],
            [
                'name' => 'JACARIO Essential Deep Navy Polo',
                'sku' => 'JAC-POLO-035',
                'base_price' => 1099,
                'sale_price' => 849,
                'fabric' => '100% Combed Cotton (210 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_1'], $curatedImages['navy_2']],
                'desc' => 'Rich everyday navy polo that pairs effortlessly with chinos, denim, and shorts.'
            ],
            [
                'name' => 'JACARIO Essential Marl Grey Polo',
                'sku' => 'JAC-POLO-036',
                'base_price' => 1099,
                'sale_price' => 849,
                'fabric' => '90% Cotton, 10% Viscose',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'heather-grey',
                'img' => [$curatedImages['grey_1'], $curatedImages['grey_2']],
                'desc' => 'Light grey marl knit with heathered softness and natural stretch for all-day ease.'
            ],
            [
                'name' => 'JACARIO Essential Charcoal Heather Polo',
                'sku' => 'JAC-POLO-037',
                'base_price' => 1199,
                'sale_price' => 899,
                'fabric' => '100% Ring-Spun Cotton',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'slate-charcoal',
                'img' => [$curatedImages['charcoal_1'], $curatedImages['grey_1']],
                'desc' => 'Durable dark charcoal cotton with reactive dyes that prevent fading in harsh sunlight.'
            ],
            [
                'name' => 'JACARIO Essential Olive Drab Polo',
                'sku' => 'JAC-POLO-038',
                'base_price' => 1199,
                'sale_price' => 899,
                'fabric' => '100% Combed Cotton',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'olive-dusk',
                'img' => [$curatedImages['olive_1'], $curatedImages['olive_2']],
                'desc' => 'Versatile earthy olive tone designed for relaxed weekend outings and coffee runs.'
            ],
            [
                'name' => 'JACARIO Essential Maroon Polo',
                'sku' => 'JAC-POLO-039',
                'base_price' => 1199,
                'sale_price' => 899,
                'fabric' => '100% Cotton Piqué',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'classic-maroon',
                'img' => [$curatedImages['burgundy_1'], $curatedImages['burgundy_2']],
                'desc' => 'Classic maroon polo with 2-button placket, pre-washed for shrink protection.'
            ],
            [
                'name' => 'JACARIO Essential Sky Breeze Polo',
                'sku' => 'JAC-POLO-040',
                'base_price' => 1199,
                'sale_price' => 899,
                'fabric' => '100% Cotton Piqué',
                'fit' => 'Regular Fit',
                'collar_type' => 'Standard Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'sky-blue',
                'img' => [$curatedImages['blue_1'], $curatedImages['blue_2']],
                'desc' => 'Crisp light pastel blue polo offering airy breathability and clean tailored seams.'
            ],

            // --- SECTION 6: CATALOG EXTENSIONS (41 to 50) ---
            [
                'name' => 'JACARIO Contrast Collar Club Polo',
                'sku' => 'JAC-POLO-041',
                'base_price' => 1799,
                'sale_price' => 1399,
                'fabric' => '100% Combed Cotton Piqué',
                'fit' => 'Slim Fit',
                'collar_type' => 'Two-Tone Contrast Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'pure-white',
                'img' => [$curatedImages['white_1'], $curatedImages['navy_1']],
                'desc' => 'Sporty white body contrasted with deep navy collar and cuff trims for timeless athletic style.'
            ],
            [
                'name' => 'JACARIO Riviera Nautical Stripe Polo',
                'sku' => 'JAC-POLO-042',
                'base_price' => 1899,
                'sale_price' => 1449,
                'fabric' => 'Yarn-Dyed Piqué Cotton',
                'fit' => 'Regular Fit',
                'collar_type' => 'Solid Navy Rib Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_1'], $curatedImages['blue_2']],
                'desc' => 'Classic Breton micro-stripes yarn-dyed before knitting to ensure crisp color integrity.'
            ],
            [
                'name' => 'JACARIO Aero Coolmax Tech Polo',
                'sku' => 'JAC-POLO-043',
                'base_price' => 1999,
                'sale_price' => 1499,
                'fabric' => 'Performance Fabric (Coolmax® Infused Cotton Tech)',
                'fit' => 'Slim Fit',
                'collar_type' => 'Bonded Non-Curling Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'slate-charcoal',
                'img' => [$curatedImages['charcoal_1'], $curatedImages['grey_2']],
                'desc' => 'Designed for 35°C+ summer heat with active evaporative cooling channels.'
            ],
            [
                'name' => 'JACARIO Amalfi Sunset Terracotta Polo',
                'sku' => 'JAC-POLO-044',
                'base_price' => 1799,
                'sale_price' => 1299,
                'fabric' => '100% Organic Piqué Cotton',
                'fit' => 'Relaxed Fit',
                'collar_type' => 'Stay-Flat Rib Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'classic-maroon',
                'img' => [$curatedImages['burgundy_2'], $curatedImages['sand_1']],
                'desc' => 'Warm terracotta clay tone inspired by sun-drenched Italian coastlines.'
            ],
            [
                'name' => 'JACARIO Cypress Forest Heavy Piqué Polo',
                'sku' => 'JAC-POLO-045',
                'base_price' => 2099,
                'sale_price' => 1599,
                'fabric' => 'Heavyweight Pique Cotton (260 GSM)',
                'fit' => 'Regular Fit',
                'collar_type' => 'Structured Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'forest-emerald',
                'img' => [$curatedImages['green_1'], $curatedImages['olive_1']],
                'desc' => 'Substantial 260 GSM heavyweight knit providing an exceptionally clean, non-clinging structure.'
            ],
            [
                'name' => 'JACARIO Midnight Shadow Matte Polo',
                'sku' => 'JAC-POLO-046',
                'base_price' => 1699,
                'sale_price' => 1299,
                'fabric' => '95% Cotton, 5% Spandex',
                'fit' => 'Slim Fit',
                'collar_type' => 'Matte Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'obsidian-black',
                'img' => [$curatedImages['black_2'], $curatedImages['black_1']],
                'desc' => 'Deep matte black with tonal black buttons for an ultra-clean monochrome aesthetic.'
            ],
            [
                'name' => 'JACARIO Vintage Sand Heather Polo',
                'sku' => 'JAC-POLO-047',
                'base_price' => 1799,
                'sale_price' => 1349,
                'fabric' => 'Tri-Blend Cotton Knit',
                'fit' => 'Regular Fit',
                'collar_type' => 'Ribbed Knit Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'desert-sand',
                'img' => [$curatedImages['sand_2'], $curatedImages['sand_1']],
                'desc' => 'Subtly mottled sand heather weave providing great texture underneath blazers.'
            ],
            [
                'name' => 'JACARIO Azure Blue Micro-Piqué Polo',
                'sku' => 'JAC-POLO-048',
                'base_price' => 1899,
                'sale_price' => 1399,
                'fabric' => '100% Combed Mercerized Cotton',
                'fit' => 'Slim Fit',
                'collar_type' => 'Tailored Ribbed Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'royal-blue',
                'img' => [$curatedImages['blue_1'], $curatedImages['blue_2']],
                'desc' => 'Vivid azure hue with tight micro-piqué knit that stays smooth throughout the day.'
            ],
            [
                'name' => 'JACARIO Sage Mist Relaxed Polo',
                'sku' => 'JAC-POLO-049',
                'base_price' => 1699,
                'sale_price' => 1249,
                'fabric' => '100% Organic Pima Cotton',
                'fit' => 'Relaxed Fit',
                'collar_type' => 'Camp-Style Polo Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => false,
                'color' => 'olive-dusk',
                'img' => [$curatedImages['olive_2'], $curatedImages['green_2']],
                'desc' => 'Calming pale sage green tone offering effortless styling with light grey or cream chinos.'
            ],
            [
                'name' => 'JACARIO Grand Prix Royal Navy Polo',
                'sku' => 'JAC-POLO-050',
                'base_price' => 2199,
                'sale_price' => 1699,
                'fabric' => '100% Supima® Cotton (240 GSM)',
                'fit' => 'Slim Fit',
                'collar_type' => 'Engineered Stay-Flat Collar',
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'is_featured' => true,
                'color' => 'royal-navy',
                'img' => [$curatedImages['navy_2'], $curatedImages['navy_1']],
                'desc' => 'Commemorative 50th edition silhouette featuring reinforced triple-stitch placket and gold-rimmed pearl hardware.'
            ],
        ];

        // Seed each product safely using updateOrCreate
        foreach ($products as $idx => $p) {
            $slug = Str::slug($p['name']);
            $defaultColorSlug = $p['color'];
            $imageUrls = $p['img'];

            $product = Product::updateOrCreate(
                ['sku' => $p['sku']],
                [
                    'name' => $p['name'],
                    'slug' => $slug,
                    'category_id' => $poloCat->id,
                    'base_price' => $p['base_price'],
                    'sale_price' => $p['sale_price'],
                    'short_description' => $p['desc'],
                    'description' => $p['desc'] . "\n\nEngineered with JACARIO's proprietary stay-flat collar technology that prevents edge curl even after 50+ machine washes. Features authentic cross-stitched mother-of-pearl buttons and pre-shrunk cotton yarn for a definitive silhouette that stands the test of time.",
                    'fabric' => $p['fabric'],
                    'fit' => $p['fit'],
                    'pattern' => 'Solid',
                    'collar_type' => $p['collar_type'],
                    'sleeve_type' => 'Short Sleeve with Knitted Arm Ribbing',
                    'wash_care' => 'Machine wash cold with like colors, dry flat in shade, do not tumble dry',
                    'country_of_origin' => 'India',
                    'is_bestseller' => $p['is_bestseller'],
                    'is_new_arrival' => $p['is_new_arrival'],
                    'is_featured' => $p['is_featured'],
                    'is_active' => true,
                    'seo_title' => $p['name'] . ' | JACARIO Luxury Polo T-Shirts',
                    'seo_description' => 'Shop ' . $p['name'] . ' crafted from ' . $p['fabric'] . '. Premium Indian pricing, express shipping, and 15-day doorstep exchanges at JACARIO.',
                ]
            );

            // Re-seed Product Images (Primary + Alternate Detail angles)
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($imageUrls as $imgIdx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgUrl,
                    'alt_text' => $product->name . ' - View ' . ($imgIdx + 1),
                    'sort_order' => $imgIdx + 1,
                    'is_primary' => ($imgIdx === 0),
                ]);
            }

            // Seed Variants across all 5 sizes (S, M, L, XL, XXL)
            ProductVariant::where('product_id', $product->id)->delete();
            $defaultColor = Color::where('slug', $defaultColorSlug)->first() ?: $colors->first();

            // 1. Primary color variants
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                    'color_id' => $defaultColor->id,
                    'sku' => $product->sku . '-' . $defaultColor->slug . '-' . $size->code,
                    'price' => null,
                    'sale_price' => null,
                    'stock_quantity' => rand(12, 35),
                    'is_active' => true,
                ]);
            }

            // 2. Extra colorways for swatch variety
            $extraColors = $colors->where('id', '!=', $defaultColor->id)->take(3);
            foreach ($extraColors as $extraColor) {
                foreach ($sizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $extraColor->id,
                        'sku' => $product->sku . '-' . $extraColor->slug . '-' . $size->code,
                        'price' => null,
                        'sale_price' => null,
                        'stock_quantity' => rand(6, 25),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
