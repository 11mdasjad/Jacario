<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartSummary = $this->cartService->getSummary();
        return view('cart.index', compact('cartSummary'));
    }

    public function summaryApi()
    {
        return response()->json($this->cartService->getSummary());
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $result = $this->cartService->addItem($validated['variant_id'], $quantity);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:cart_items,id'],
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $result = $this->cartService->updateQuantity($validated['item_id'], $validated['quantity']);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:cart_items,id'],
        ]);

        $result = $this->cartService->removeItem($validated['item_id']);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return back()->with('info', $result['message']);
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $result = $this->cartService->applyCoupon($validated['code']);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function removeCoupon(Request $request)
    {
        $result = $this->cartService->removeCoupon();

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return back()->with('info', $result['message']);
    }
}
