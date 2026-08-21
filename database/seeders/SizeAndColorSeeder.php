<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeAndColorSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['name' => 'S', 'code' => 'S', 'chest' => '38 in / 96 cm', 'length' => '27.5 in / 70 cm', 'shoulder' => '17.0 in / 43 cm', 'sort_order' => 1],
            ['name' => 'M', 'code' => 'M', 'chest' => '40 in / 102 cm', 'length' => '28.5 in / 72 cm', 'shoulder' => '17.7 in / 45 cm', 'sort_order' => 2],
            ['name' => 'L', 'code' => 'L', 'chest' => '42 in / 107 cm', 'length' => '29.5 in / 75 cm', 'shoulder' => '18.5 in / 47 cm', 'sort_order' => 3],
            ['name' => 'XL', 'code' => 'XL', 'chest' => '44 in / 112 cm', 'length' => '30.5 in / 77 cm', 'shoulder' => '19.3 in / 49 cm', 'sort_order' => 4],
            ['name' => 'XXL', 'code' => 'XXL', 'chest' => '46 in / 117 cm', 'length' => '31.5 in / 80 cm', 'shoulder' => '20.1 in / 51 cm', 'sort_order' => 5],
        ];

        foreach ($sizes as $s) {
            Size::updateOrCreate(['code' => $s['code']], $s);
        }

        $colors = [
            ['name' => 'Obsidian Black', 'slug' => 'obsidian-black', 'hex_code' => '#18181B'],
            ['name' => 'Pure White', 'slug' => 'pure-white', 'hex_code' => '#F8FAFC'],
            ['name' => 'Royal Navy', 'slug' => 'royal-navy', 'hex_code' => '#1E293B'],
            ['name' => 'Royal Blue', 'slug' => 'royal-blue', 'hex_code' => '#1D4ED8'],
            ['name' => 'Sky Blue', 'slug' => 'sky-blue', 'hex_code' => '#7DD3FC'],
            ['name' => 'Olive Dusk', 'slug' => 'olive-dusk', 'hex_code' => '#3F4E3F'],
            ['name' => 'Forest Green', 'slug' => 'forest-emerald', 'hex_code' => '#1B4332'],
            ['name' => 'Heather Grey', 'slug' => 'heather-grey', 'hex_code' => '#64748B'],
            ['name' => 'Slate Charcoal', 'slug' => 'slate-charcoal', 'hex_code' => '#334155'],
            ['name' => 'Sand Beige', 'slug' => 'desert-sand', 'hex_code' => '#D6C7B2'],
            ['name' => 'Classic Maroon', 'slug' => 'classic-maroon', 'hex_code' => '#800000'],
            ['name' => 'Burgundy Royale', 'slug' => 'burgundy-royale', 'hex_code' => '#5C1D24'],
            ['name' => 'Warm Mustard', 'slug' => 'warm-mustard', 'hex_code' => '#D97706'],
            ['name' => 'Ivory Cream', 'slug' => 'ivory-cream', 'hex_code' => '#FFFBEB'],
        ];

        foreach ($colors as $c) {
            Color::updateOrCreate(['slug' => $c['slug']], $c);
        }
    }
}
