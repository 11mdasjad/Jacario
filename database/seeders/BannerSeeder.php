<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            // 5 Homepage Hero Promotional Banners
            [
                'title' => 'The Polo, Perfected.',
                'subtitle' => 'Meticulously tailored from 100% long-staple American Supima® cotton with our stay-flat collar guarantee.',
                'badge_text' => '✦ The Haute Collection',
                'cta_text' => 'Shop Now',
                'cta_url' => '/shop?fabric=Supima',
                'image_path' => '/images/banners/banner-studio-haute.jpg',
                'text_alignment' => 'right',
                'position' => 'hero',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Mulberry Silk & Supima®',
                'subtitle' => 'Silken drape meets athletic breathability. An ultra-fine 24-gauge knit engineered for effortless evening elegance.',
                'badge_text' => '★ Rare 24-Gauge Silk Knit',
                'cta_text' => 'Explore Haute Knits',
                'cta_url' => '/shop?fabric=Silk',
                'image_path' => '/images/banners/banner-silk-atelier.jpg',
                'text_alignment' => 'right',
                'position' => 'hero',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Riviera Earth & Espresso',
                'subtitle' => '14 new season colorways inspired by Portofino coastlines and dyed with eco-friendly reactive pigments.',
                'badge_text' => '◆ New Season Drop',
                'cta_text' => 'Shop New Arrivals',
                'cta_url' => '/shop?collection=new-arrivals',
                'image_path' => '/images/banners/banner-riviera-earth.jpg',
                'text_alignment' => 'right',
                'position' => 'hero',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Loved. Worn. Repeated.',
                'subtitle' => 'Our highest-rated customer icons, engineered with genuine Australian mother-of-pearl hardware.',
                'badge_text' => '● 4.9/5 Star Customer Icons',
                'cta_text' => 'Shop Best Sellers',
                'cta_url' => '/shop?collection=bestsellers',
                'image_path' => '/images/banners/banner-classic-bestseller.jpg',
                'text_alignment' => 'right',
                'position' => 'hero',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Collar Engineering That Never Curls.',
                'subtitle' => 'Internal micro-fused interlining and reinforced plackets engineered to stay razor-sharp through 50+ washes.',
                'badge_text' => '✦ Atelier Craftsmanship',
                'cta_text' => 'Explore Stay-Flat Series',
                'cta_url' => '/shop?fabric=Supima',
                'image_path' => '/images/banners/banner-studio-haute.jpg',
                'text_alignment' => 'right',
                'position' => 'hero',
                'sort_order' => 5,
                'is_active' => true,
            ],

            // 3 Shop Catalog Header Running Banners
            [
                'title' => 'The Polo Collection',
                'subtitle' => 'Meticulously tailored from 100% American Supima® cotton, Mulberry silk blends, and stay-flat collar technology.',
                'badge_text' => '✦ 50 Bespoke Silhouettes',
                'cta_text' => 'Shop Supima® Series',
                'cta_url' => '/shop?fabric=Supima',
                'image_path' => '/images/banners/banner-studio-haute.jpg',
                'text_alignment' => 'left',
                'position' => 'shop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Mulberry Silk & 24-Gauge Knits',
                'subtitle' => 'Ultra-fine natural luster meets athletic breathability for effortless evening elegance.',
                'badge_text' => '★ Rare Silk Collection',
                'cta_text' => 'Explore Silk Knits',
                'cta_url' => '/shop?fabric=Silk',
                'image_path' => '/images/banners/banner-silk-atelier.jpg',
                'text_alignment' => 'left',
                'position' => 'shop',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Riviera Earth & Espresso Drop',
                'subtitle' => '14 new season colorways inspired by Portofino coastlines and dyed with eco-friendly reactive pigments.',
                'badge_text' => '◆ New Season Arrivals',
                'cta_text' => 'Shop New Arrivals',
                'cta_url' => '/shop?collection=new-arrivals',
                'image_path' => '/images/banners/banner-riviera-earth.jpg',
                'text_alignment' => 'left',
                'position' => 'shop',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::updateOrCreate(
                [
                    'position' => $bannerData['position'],
                    'sort_order' => $bannerData['sort_order'],
                ],
                $bannerData
            );
        }
    }
}
