<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['items', 'payments'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(string $orderNumber)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $order = Order::with(['items.product', 'items.variant.color', 'items.variant.size', 'payments'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (!$user->isStaff() && $order->user_id !== $user->id) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function cancel(Request $request, string $orderNumber)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if (!$user->isStaff() && $order->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->orderService->cancelOrder($order, $validated['reason'] ?? 'Cancelled by customer request');
            return back()->with('success', 'Order has been cancelled and items returned to inventory.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function track(Request $request)
    {
        $orderNumber = $request->query('order_number');
        $email = $request->query('email');
        $order = null;

        if ($orderNumber && $email) {
            $order = Order::with('items')
                ->where('order_number', trim($orderNumber))
                ->where('customer_email', trim($email))
                ->first();

            if (!$order) {
                return back()->withInput()->with('error', 'No order found matching the provided order number and email address.');
            }
        }

        return view('orders.track', compact('order'));
    }

    public function invoice(string $orderNumber)
    {
        $user = Auth::user();
        $order = Order::with(['items', 'payments'])->where('order_number', $orderNumber)->firstOrFail();

        if ($user && !$user->isStaff() && $order->user_id && $order->user_id !== $user->id) {
            abort(403);
        }

        return view('orders.invoice', compact('order'));
    }
}
