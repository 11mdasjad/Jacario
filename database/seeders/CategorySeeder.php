<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => "Men's Polo T-Shirts",
                'slug' => 'mens-polo-t-shirts',
                'description' => 'Meticulously tailored luxury polo t-shirts engineered from 100% Supima® cotton, Mulberry silk blends, and stay-flat structured collars.',
                'image' => '/images/catalog/categories/polo-category-card.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Round Neck T-Shirts',
                'slug' => 'round-neck-t-shirts',
                'description' => 'Featherweight premium combed cotton round neck tees designed with a sculpted drape, reinforced ribbing, and ultra-soft tactile feel.',
                'image' => '/images/catalog/categories/roundneck-category-card.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'New Arrival T-Shirts',
                'slug' => 'new-arrival-t-shirts',
                'description' => 'Latest seasonal drops featuring limited-edition colorways, performance knit textures, and bespoke sartorial finishes.',
                'image' => '/images/catalog/banners/new-arrivals-banner.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
