<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::where('role', 'customer')->get();

        if ($products->isEmpty() || $users->isEmpty()) {
            return;
        }

        $reviewTemplates = [
            [
                'rating' => 5,
                'title' => 'Unbeatable fabric and collar structure',
                'comment' => 'I have bought polo shirts from Ralph Lauren, Sunspel, and Loro Piana over the years. The JACARIO Supima Polo genuinely stands toe-to-toe with luxury European houses. The collar does not roll or fray after multiple washes, and the drape is flawless.',
                'featured' => true,
            ],
            [
                'rating' => 5,
                'title' => 'The absolute gold standard in polo shirts',
                'comment' => 'The mother-of-pearl buttons, split hem, and dense piqué knit feel exceptionally premium. Wore this to both casual client lunches and weekend golf rounds. Highly recommend.',
                'featured' => true,
            ],
            [
                'rating' => 5,
                'title' => 'Incredible handfeel and fit',
                'comment' => 'The fit through the shoulders and arms is tailored without being restrictive. Breathable even in humid weather. Ordered 3 more colors.',
                'featured' => true,
            ],
            [
                'rating' => 4,
                'title' => 'Exceptional craftsmanship and packaging',
                'comment' => 'Arrived in a sleek embossed matte black box with tissue wrap. The fabric is heavy yet breathable. Only giving 4 stars because my preferred size XXL was low on stock!',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'title' => 'Pure luxury silk-cotton blend',
                'comment' => 'The Riviera Johnny collar polo is a work of art. The subtle sheen and buttery softness turn heads wherever I go. Essential summer knitwear.',
                'featured' => true,
            ],
            [
                'rating' => 5,
                'title' => 'Golf performance is top tier',
                'comment' => 'Full range of motion during tee-off and keeps you bone dry throughout 18 holes. The collar remains crisp and does not curl under sweaters.',
                'featured' => false,
            ],
        ];

        $i = 0;
        foreach ($products as $product) {
            // Add 2-3 reviews per product
            $numReviews = rand(2, 4);
            for ($k = 0; $k < $numReviews; $k++) {
                $user = $users[($i + $k) % $users->count()];
                $template = $reviewTemplates[($i + $k) % count($reviewTemplates)];

                Review::updateOrCreate(
                    ['product_id' => $product->id, 'user_id' => $user->id],
                    [
                        'rating' => $template['rating'],
                        'title' => $template['title'],
                        'comment' => $template['comment'],
                        'is_verified_purchase' => true,
                        'is_approved' => true,
                        'is_featured' => $template['featured'],
                    ]
                );
            }
            $i++;
        }
    }
}
