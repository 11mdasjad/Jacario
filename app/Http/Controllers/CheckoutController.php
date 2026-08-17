<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\RazorpayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected RazorpayService $razorpayService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        RazorpayService $razorpayService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->razorpayService = $razorpayService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your shopping bag is empty.');
        }

        $cartSummary = $this->cartService->getSummary();
        $user = Auth::user();
        $savedAddresses = $user ? $user->addresses : collect();

        return view('checkout.index', compact('cartSummary', 'savedAddresses', 'user'));
    }

    public function placeOrder(Request $request)
    {
        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your bag is empty.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'address_id' => ['nullable', 'integer', 'exists:addresses,id'],
            'address_line_1' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'state' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'postal_code' => ['required_without:address_id', 'nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
            'save_address' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'string', 'in:razorpay,cod'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $shippingAddress = [];

        if (!empty($validated['address_id']) && $user) {
            $saved = $user->addresses()->find($validated['address_id']);
            if ($saved) {
                $shippingAddress = [
                    'full_name' => $saved->full_name,
                    'phone' => $saved->phone,
                    'address_line_1' => $saved->address_line_1,
                    'address_line_2' => $saved->address_line_2,
                    'landmark' => $saved->landmark,
                    'city' => $saved->city,
                    'state' => $saved->state,
                    'postal_code' => $saved->postal_code,
                    'country' => $saved->country,
                ];
            }
        }

        if (empty($shippingAddress)) {
            $shippingAddress = [
                'full_name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'] ?? 'India',
            ];

            if ($user && $request->boolean('save_address')) {
                $user->addresses()->create(array_merge($shippingAddress, [
                    'address_type' => 'home',
                    'is_default' => $user->addresses()->count() === 0,
                ]));
            }
        }

        $customerData = [
            'user_id' => $user ? $user->id : null,
            'name' => $validated['customer_name'],
            'email' => $validated['customer_email'],
            'phone' => $validated['customer_phone'],
        ];

        try {
            $order = $this->orderService->createOrder(
                $cart,
                $customerData,
                $shippingAddress,
                $validated['payment_method'],
                $shippingAddress,
                $validated['notes'] ?? null
            );

            // Empty the cart
            $this->cartService->clearCart();

            if ($validated['payment_method'] === 'cod') {
                $this->orderService->updatePaymentStatus($order, 'pending');
                $order->update(['status' => 'confirmed']);
                return redirect()->route('checkout.success', $order->order_number)
                    ->with('success', 'Your Cash on Delivery order has been placed successfully.');
            }

            // Razorpay Payment Flow
            $razorpayOrder = $this->razorpayService->createRazorpayOrder($order);

            return view('checkout.payment-razorpay', [
                'order' => $order,
                'razorpayOrder' => $razorpayOrder,
                'razorpayKey' => $this->razorpayService->getKeyId(),
                'isLiveReady' => $this->razorpayService->isLiveReady(),
            ]);

        } catch (Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function verifyRazorpay(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $order = Order::findOrFail($validated['order_id']);

        $isValid = $this->razorpayService->verifyPaymentSignature(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        );

        if (!$isValid) {
            $this->orderService->updatePaymentStatus($order, 'failed', $validated['razorpay_payment_id']);
            return redirect()->route('checkout.failed', ['order' => $order->order_number])
                ->with('error', 'Payment verification failed. Please try again or contact concierge.');
        }

        $this->orderService->updatePaymentStatus(
            $order,
            'captured',
            $validated['razorpay_payment_id'],
            $request->all()
        );

        return redirect()->route('checkout.success', $order->order_number)
            ->with('success', 'Payment successful! Your JACARIO bespoke order is confirmed.');
    }

    public function success(string $orderNumber)
    {
        $order = Order::with(['items', 'payments'])->where('order_number', $orderNumber)->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    public function failed(Request $request)
    {
        $orderNumber = $request->query('order');
        $order = $orderNumber ? Order::where('order_number', $orderNumber)->first() : null;
        return view('checkout.failed', compact('order'));
    }
}
