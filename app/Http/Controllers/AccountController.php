<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $defaultAddress = $user->defaultAddress ?: $user->addresses()->first();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $reviewsCount = Review::where('user_id', $user->id)->count();

        return view('account.dashboard', compact(
            'user',
            'recentOrders',
            'defaultAddress',
            'wishlistCount',
            'reviewsCount'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Your personal details have been updated.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Your password has been changed successfully.');
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();
        return view('account.addresses', compact('user', 'addresses'));
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
            'address_type' => ['required', 'string', 'in:home,work,other'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_default') || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $user->addresses()->create($validated);

        return back()->with('success', 'New address saved to your address book.');
    }

    public function updateAddress(Request $request, int $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
            'address_type' => ['required', 'string', 'in:home,work,other'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_default')) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $address->update($validated);

        return back()->with('success', 'Address updated successfully.');
    }

    public function deleteAddress(int $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $first = $user->addresses()->first();
            if ($first) {
                $first->update(['is_default' => true]);
            }
        }

        return back()->with('info', 'Address removed from your address book.');
    }

    public function setDefaultAddress(int $id)
    {
        $user = Auth::user();
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $id)->update(['is_default' => true]);

        return back()->with('success', 'Default delivery address updated.');
    }

    public function myReviews()
    {
        $user = Auth::user();
        $reviews = Review::with('product')->where('user_id', $user->id)->latest()->paginate(10);
        return view('account.reviews', compact('reviews'));
    }
}
