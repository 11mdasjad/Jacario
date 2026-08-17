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
                'image' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Round Neck T-Shirts',
                'slug' => 'round-neck-t-shirts',
                'description' => 'Featherweight premium combed cotton round neck tees designed with a sculpted drape, reinforced ribbing, and ultra-soft tactile feel.',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'New Arrival T-Shirts',
                'slug' => 'new-arrival-t-shirts',
                'description' => 'Latest seasonal drops featuring limited-edition colorways, performance knit textures, and bespoke sartorial finishes.',
                'image' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
