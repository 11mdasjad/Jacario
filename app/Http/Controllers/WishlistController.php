<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $user = Auth::user();
        $wishlistItems = Wishlist::with([
            'product.category',
            'product.images',
            'product.activeVariants.color',
            'product.activeVariants.size',
        ])
        ->where('user_id', $user->id)
        ->latest()
        ->paginate(12);

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please sign in to save items to your wishlist.',
            ], 401);
        }

        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $user = Auth::user();
        $productId = $validated['product_id'];

        $existing = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $isAdded = false;
            $message = 'Removed from your wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $isAdded = true;
            $message = 'Saved to your wishlist.';
        }

        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'is_added' => $isAdded,
            'message' => $message,
            'wishlist_count' => $wishlistCount,
        ]);
    }

    public function moveToCart(Request $request, int $id)
    {
        $user = Auth::user();
        $wishlistItem = Wishlist::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $product = $wishlistItem->product;

        $variant = $product->activeVariants()->where('stock_quantity', '>', 0)->first();
        if (!$variant) {
            return back()->with('error', 'This Polo T-Shirt is currently out of stock.');
        }

        $this->cartService->addItem($variant->id, 1);
        $wishlistItem->delete();

        return redirect()->route('cart.index')->with('success', "Moved {$product->name} to your shopping bag.");
    }
}
