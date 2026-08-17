<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with(['items', 'user']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::with(['items.product', 'payments', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,processing,packed,shipped,out_for_delivery,delivered,cancelled,returned,refunded'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'courier_name' => ['nullable', 'string', 'max:100'],
            'payment_status' => ['nullable', 'string', 'in:pending,authorized,captured,failed,refunded'],
        ]);

        $this->orderService->updateOrderStatus(
            $order,
            $validated['status'],
            $validated['tracking_number'] ?? null,
            $validated['courier_name'] ?? null
        );

        if (!empty($validated['payment_status'])) {
            $this->orderService->updatePaymentStatus($order, $validated['payment_status']);
        }

        return back()->with('success', "Order #{$order->order_number} status updated to " . ucfirst(str_replace('_', ' ', $validated['status'])));
    }

    public function cancel(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $reason = $request->input('reason', 'Cancelled by administrator');

        try {
            $this->orderService->cancelOrder($order, $reason);
            return back()->with('success', "Order #{$order->order_number} cancelled and items returned to inventory.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
