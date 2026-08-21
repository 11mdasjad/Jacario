<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Fetch 5 Hero Promotional Banners
        $banners = Banner::hero()->get();
        if ($banners->isEmpty()) {
            $banners = collect([
                (object)[
                    'id' => 1,
                    'title' => 'The Polo, Perfected.',
                    'subtitle' => 'Meticulously tailored from 100% long-staple American Supima® cotton with our stay-flat collar guarantee.',
                    'badge_text' => '✦ The Haute Collection',
                    'cta_text' => 'Shop Now',
                    'cta_url' => '/shop?fabric=Supima',
                    'image_url' => asset('images/banners/banner-studio-haute.jpg'),
                    'mobile_image_url' => asset('images/banners/banner-studio-haute.jpg'),
                    'text_alignment' => 'right',
                    'sort_order' => 1,
                ],
                (object)[
                    'id' => 2,
                    'title' => 'Mulberry Silk & Supima®',
                    'subtitle' => 'Silken drape meets athletic breathability. An ultra-fine 24-gauge knit engineered for effortless evening elegance.',
                    'badge_text' => '★ Rare 24-Gauge Silk Knit',
                    'cta_text' => 'Explore Haute Knits',
                    'cta_url' => '/shop?fabric=Silk',
                    'image_url' => asset('images/banners/banner-silk-atelier.jpg'),
                    'mobile_image_url' => asset('images/banners/banner-silk-atelier.jpg'),
                    'text_alignment' => 'right',
                    'sort_order' => 2,
                ],
                (object)[
                    'id' => 3,
                    'title' => 'Riviera Earth & Espresso',
                    'subtitle' => '14 new season colorways inspired by Portofino coastlines and dyed with eco-friendly reactive pigments.',
                    'badge_text' => '◆ New Season Drop',
                    'cta_text' => 'Shop New Arrivals',
                    'cta_url' => '/shop?collection=new-arrivals',
                    'image_url' => asset('images/banners/banner-riviera-earth.jpg'),
                    'mobile_image_url' => asset('images/banners/banner-riviera-earth.jpg'),
                    'text_alignment' => 'right',
                    'sort_order' => 3,
                ],
                (object)[
                    'id' => 4,
                    'title' => 'Loved. Worn. Repeated.',
                    'subtitle' => 'Our highest-rated customer icons, engineered with genuine Australian mother-of-pearl hardware.',
                    'badge_text' => '● 4.9/5 Star Customer Icons',
                    'cta_text' => 'Shop Best Sellers',
                    'cta_url' => '/shop?collection=bestsellers',
                    'image_url' => asset('images/banners/banner-classic-bestseller.jpg'),
                    'mobile_image_url' => asset('images/banners/banner-classic-bestseller.jpg'),
                    'text_alignment' => 'right',
                    'sort_order' => 4,
                ],
                (object)[
                    'id' => 5,
                    'title' => 'Collar Engineering That Never Curls.',
                    'subtitle' => 'Internal micro-fused interlining and reinforced plackets engineered to stay razor-sharp through 50+ washes.',
                    'badge_text' => '✦ Atelier Craftsmanship',
                    'cta_text' => 'Explore Stay-Flat Series',
                    'cta_url' => '/shop?fabric=Supima',
                    'image_url' => asset('images/banners/banner-studio-haute.jpg'),
                    'mobile_image_url' => asset('images/banners/banner-studio-haute.jpg'),
                    'text_alignment' => 'right',
                    'sort_order' => 5,
                ],
            ]);
        }

        // 2. Query Categories & Counts
        $categories = Category::active()->withCount('products')->get();
        $totalProductsCount = Product::active()->count();

        // 3. Section 1: New Arrivals (8 products)
        $newArrivals = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active()
            ->where('is_new_arrival', true)
            ->take(8)
            ->get();

        // 4. Section 2: Best Sellers (8 products)
        $bestsellers = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active()
            ->where('is_bestseller', true)
            ->take(8)
            ->get();

        // 5. Section 3: Trending Polos (8 products)
        $trendingPolos = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active()
            ->where('is_featured', true)
            ->where('is_bestseller', false)
            ->where('is_new_arrival', false)
            ->take(8)
            ->get();

        // Fallback if not enough trending items
        if ($trendingPolos->count() < 8) {
            $trendingIds = $trendingPolos->pluck('id')->merge($newArrivals->pluck('id'))->merge($bestsellers->pluck('id'));
            $extraTrending = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
                ->active()
                ->whereNotIn('id', $trendingIds)
                ->take(8 - $trendingPolos->count())
                ->get();
            $trendingPolos = $trendingPolos->concat($extraTrending);
        }

        // 6. Section 4: Premium Collection (8 products - Silk, Supima, Egyptian Cotton)
        $premiumCollection = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active()
            ->where(function ($q) {
                $q->where('fabric', 'like', '%Silk%')
                  ->orWhere('fabric', 'like', '%Supima%')
                  ->orWhere('fabric', 'like', '%Egyptian%')
                  ->orWhere('base_price', '>=', 2199);
            })
            ->take(8)
            ->get();

        // 7. Section 5: Everyday Essentials (8 products - ₹799 - ₹1,299)
        $everydayEssentials = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active()
            ->where(function ($q) {
                $q->where('base_price', '<=', 1299)
                  ->orWhere('name', 'like', '%Essential%');
            })
            ->take(8)
            ->get();

        // 8. Section 6: All Polo T-Shirts Preview (10 remaining products)
        $shownIds = $newArrivals->pluck('id')
            ->merge($bestsellers->pluck('id'))
            ->merge($trendingPolos->pluck('id'))
            ->merge($premiumCollection->pluck('id'))
            ->merge($everydayEssentials->pluck('id'))
            ->unique();

        $allPolosPreview = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active()
            ->whereNotIn('id', $shownIds)
            ->take(10)
            ->get();

        if ($allPolosPreview->count() < 8) {
            $allPolosPreview = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
                ->active()
                ->latest()
                ->take(10)
                ->get();
        }

        // 9. Customer Reviews
        $featuredReviews = Review::with(['user', 'product'])
            ->approved()
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact(
            'banners',
            'categories',
            'newArrivals',
            'bestsellers',
            'trendingPolos',
            'premiumCollection',
            'everydayEssentials',
            'allPolosPreview',
            'totalProductsCount',
            'featuredReviews'
        ));
    }

    public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $validated['email']],
            ['is_active' => true]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for joining the JACARIO Society. Your welcome gift code is on its way.',
            ]);
        }

        return back()->with('success', 'Thank you for subscribing to JACARIO updates.');
    }
}
