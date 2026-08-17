<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()->withCount('products')->get();
        
        $featuredProducts = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->featured()
            ->take(8)
            ->get();

        $bestsellers = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->bestsellers()
            ->take(6)
            ->get();

        $newArrivals = Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
            ->newArrivals()
            ->take(6)
            ->get();

        $featuredReviews = Review::with(['user', 'product'])
            ->featured()
            ->take(6)
            ->get();

        return view('home', compact(
            'categories',
            'featuredProducts',
            'bestsellers',
            'newArrivals',
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
