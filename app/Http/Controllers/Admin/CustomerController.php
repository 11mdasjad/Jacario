<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->whereIn('payment_status', ['captured', 'authorized', 'paid']);
            }], 'total_amount')
            ->where('role', 'customer');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(int $id)
    {
        $customer = User::with(['orders.items', 'addresses', 'reviews.product'])->where('role', 'customer')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function toggleActive(int $id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Customer account {$customer->name} has been {$status}.");
    }
}
