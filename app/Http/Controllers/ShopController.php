<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->active();

        // 1. Search Query (Name, SKU, Fabric, Fit, Pattern)
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('fabric', 'like', "%{$search}%")
                  ->orWhere('fit', 'like', "%{$search}%")
                  ->orWhere('pattern', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQ) use ($search) {
                      $catQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Style / Category Filter
        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        // 3. Fit Filter (Classic Fit, Slim Fit, Tailored Fit, Athletic Fit, Relaxed Luxury Fit)
        if ($request->filled('fit')) {
            $fits = (array) $request->input('fit');
            $query->whereIn('fit', $fits);
        }

        // 4. Fabric Filter
        if ($request->filled('fabric')) {
            $fabric = $request->input('fabric');
            $query->where('fabric', 'like', "%{$fabric}%");
        }

        // 5. Size Filter
        if ($request->filled('size')) {
            $sizeCodes = (array) $request->input('size');
            $query->whereHas('activeVariants.size', function ($q) use ($sizeCodes) {
                $q->whereIn('code', $sizeCodes)->where('stock_quantity', '>', 0);
            });
        }

        // 6. Color Filter
        if ($request->filled('color')) {
            $colorSlugs = (array) $request->input('color');
            $query->whereHas('activeVariants.color', function ($q) use ($colorSlugs) {
                $q->whereIn('slug', $colorSlugs)->where('stock_quantity', '>', 0);
            });
        }

        // 7. Price Range Filter
        if ($request->filled('min_price')) {
            $min = (float) $request->input('min_price');
            $query->where(function ($q) use ($min) {
                $q->where('base_price', '>=', $min)
                  ->orWhere('sale_price', '>=', $min);
            });
        }
        if ($request->filled('max_price')) {
            $max = (float) $request->input('max_price');
            $query->where(function ($q) use ($max) {
                $q->where(function ($sub) use ($max) {
                    $sub->whereNotNull('sale_price')->where('sale_price', '<=', $max);
                })->orWhere(function ($sub) use ($max) {
                    $sub->whereNull('sale_price')->where('base_price', '<=', $max);
                });
            });
        }

        // 8. Availability Filter (In stock only)
        if ($request->boolean('in_stock')) {
            $query->whereHas('activeVariants', function ($q) {
                $q->where('stock_quantity', '>', 0);
            });
        }

        // 9. Quick Collection Filters (Bestsellers / New Arrivals)
        if ($request->filled('collection')) {
            $col = $request->input('collection');
            if ($col === 'bestsellers') {
                $query->where('is_bestseller', true);
            } elseif ($col === 'new-arrivals') {
                $query->where('is_new_arrival', true);
            } elseif ($col === 'on-sale') {
                $query->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'base_price');
            }
        }

        // 10. Sorting
        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'price-asc':
                $query->orderByRaw('COALESCE(sale_price, base_price) ASC');
                break;
            case 'price-desc':
                $query->orderByRaw('COALESCE(sale_price, base_price) DESC');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'bestselling':
                $query->orderByDesc('is_bestseller')->latest();
                break;
            case 'rating':
                $query->withAvg('approvedReviews', 'rating')->orderByDesc('approved_reviews_avg_rating');
                break;
            case 'featured':
            default:
                $query->orderByDesc('is_featured')->orderByDesc('is_bestseller')->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Filter sidebar data
        $categories = Category::active()->withCount('products')->get();
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::all();
        $fits = Product::distinct()->pluck('fit')->filter()->values();

        return view('shop.index', compact(
            'products',
            'categories',
            'sizes',
            'colors',
            'fits'
        ));
    }
}
