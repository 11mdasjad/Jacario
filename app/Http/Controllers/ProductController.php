<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([
            'category',
            'images',
            'activeVariants.color',
            'activeVariants.size',
            'approvedReviews.user',
        ])
        ->where('slug', $slug)
        ->active()
        ->firstOrFail();

        // Related Polo T-Shirts
        $relatedProducts = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->active()
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $extra = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->active()
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($extra);
        }

        // Available colors and sizes map for Alpine.js interactive variant state
        $variantsMatrix = $product->activeVariants->map(function ($v) {
            return [
                'id' => $v->id,
                'size_id' => $v->size_id,
                'size_code' => $v->size->code,
                'size_name' => $v->size->name,
                'color_id' => $v->color_id,
                'color_slug' => $v->color->slug,
                'color_name' => $v->color->name,
                'color_hex' => $v->color->hex_code,
                'sku' => $v->sku,
                'price' => (float) $v->effective_price,
                'stock' => $v->stock_quantity,
                'in_stock' => $v->stock_quantity > 0,
            ];
        });

        // Structured JSON-LD Schema
        $schemaData = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $product->primaryImage ? [$product->primaryImage->url] : [],
            'description' => $product->short_description,
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'JACARIO',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('products.show', $product->slug),
                'priceCurrency' => 'INR',
                'price' => (string) $product->effective_price,
                'availability' => $product->is_in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ],
            'aggregateRating' => $product->reviews_count > 0 ? [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format($product->average_rating, 1),
                'reviewCount' => (string) $product->reviews_count,
            ] : null,
        ];

        // Check if user has purchased this item for verified badge
        $canReview = false;
        if (Auth::check()) {
            $user = Auth::user();
            $hasPurchased = $user->orders()->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })->whereIn('status', ['delivered', 'shipped'])->exists();
            $alreadyReviewed = $product->reviews()->where('user_id', $user->id)->exists();
            $canReview = $hasPurchased && !$alreadyReviewed;
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'variantsMatrix',
            'schemaData',
            'canReview'
        ));
    }

    public function checkPincode(Request $request)
    {
        $request->validate([
            'pincode' => ['required', 'string', 'min:6', 'max:6', 'regex:/^[1-9][0-9]{5}$/'],
        ]);

        $pincode = $request->input('pincode');
        
        // Realistic serviceable pincodes across India
        $metroPrefixes = ['11', '12', '20', '40', '50', '56', '60', '70'];
        $prefix = substr($pincode, 0, 2);

        if (in_array($prefix, $metroPrefixes)) {
            $deliveryDate = now()->addDays(2)->format('l, M j');
            return response()->json([
                'available' => true,
                'message' => "Express Delivery available! Expected delivery by {$deliveryDate}.",
                'is_metro' => true,
                'cod_available' => true,
            ]);
        }

        $deliveryDate = now()->addDays(4)->format('l, M j');
        return response()->json([
            'available' => true,
            'message' => "Standard Express Delivery available. Expected delivery by {$deliveryDate}.",
            'is_metro' => false,
            'cod_available' => true,
        ]);
    }

    public function storeReview(Request $request, string $slug)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please sign in to write a review.');
        }

        $product = Product::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $hasPurchased = $user->orders()->whereHas('items', function ($q) use ($product) {
            $q->where('product_id', $product->id);
        })->whereIn('status', ['delivered', 'shipped'])->exists();

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => $user->id],
            [
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'comment' => $validated['comment'],
                'is_verified_purchase' => $hasPurchased,
                'is_approved' => true, // Auto-approve or admin moderation
            ]
        );

        return back()->with('success', 'Thank you for reviewing the JACARIO Polo. Your feedback has been published.');
    }
}
