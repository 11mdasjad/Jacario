<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleApproved(int $id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => !$review->is_approved]);

        $status = $review->is_approved ? 'approved' : 'hidden';
        return back()->with('success', "Review #{$review->id} has been {$status}.");
    }

    public function toggleFeatured(int $id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_featured' => !$review->is_featured]);

        $status = $review->is_featured ? 'featured on homepage' : 'removed from featured';
        return back()->with('success', "Review #{$review->id} {$status}.");
    }

    public function destroy(int $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('info', "Review #{$review->id} deleted.");
    }
}
