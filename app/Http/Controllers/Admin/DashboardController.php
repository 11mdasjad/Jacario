<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // High-level Metrics
        $totalSales = Order::whereIn('payment_status', ['captured', 'authorized', 'paid'])->sum('total_amount');
        $todaySales = Order::whereIn('payment_status', ['captured', 'authorized', 'paid'])->whereDate('created_at', $today)->sum('total_amount');
        $monthSales = Order::whereIn('payment_status', ['captured', 'authorized', 'paid'])->where('created_at', '>=', $thisMonth)->sum('total_amount');

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::whereIn('status', ['confirmed', 'processing', 'packed', 'shipped', 'out_for_delivery'])->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        // Low stock (< 5 units) & Out of stock (0 units)
        $lowStockVariants = ProductVariant::whereHas('product')
            ->with(['product', 'size', 'color'])
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 5)
            ->get();

        $outOfStockVariants = ProductVariant::whereHas('product')
            ->with(['product', 'size', 'color'])
            ->where('stock_quantity', '<=', 0)
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['items', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Top Selling Polos
        $topSellingProducts = Product::with(['category', 'images'])
            ->where('is_bestseller', true)
            ->take(5)
            ->get();

        // 7-day revenue trend data
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $daySales = Order::whereIn('payment_status', ['captured', 'authorized', 'paid'])
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $revenueTrend[] = [
                'date' => $date->format('M j'),
                'amount' => (float) $daySales,
            ];
        }

        return view('admin.dashboard', compact(
            'totalSales',
            'todaySales',
            'monthSales',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders',
            'totalCustomers',
            'totalProducts',
            'lowStockVariants',
            'outOfStockVariants',
            'recentOrders',
            'topSellingProducts',
            'revenueTrend'
        ));
    }
}
