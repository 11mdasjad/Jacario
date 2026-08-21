<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $position = $request->query('position', 'all');
        $query = Banner::orderBy('position', 'asc')->orderBy('sort_order', 'asc');
        
        if ($position === 'hero') {
            $query->where('position', 'hero');
        } elseif ($position === 'shop') {
            $query->where('position', 'shop');
        }

        $banners = $query->get();
        $heroCount = Banner::where('position', 'hero')->count();
        $shopCount = Banner::where('position', 'shop')->count();

        return view('admin.banners.index', compact('banners', 'position', 'heroCount', 'shopCount'));
    }

    public function create(Request $request)
    {
        $defaultPosition = $request->query('position', 'hero');
        $nextOrder = (Banner::where('position', $defaultPosition)->max('sort_order') ?? 0) + 1;
        return view('admin.banners.create', compact('nextOrder', 'defaultPosition'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'cta_text' => 'required|string|max:100',
            'cta_url' => 'required|string|max:255',
            'image_url' => 'nullable|url|max:1000',
            'image_file' => 'nullable|image|max:10240',
            'mobile_image_url' => 'nullable|url|max:1000',
            'sort_order' => 'required|integer|min:1|max:99',
            'position' => 'nullable|in:hero,shop',
            'text_alignment' => 'nullable|in:left,center,right',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $validated['image_url'] ?? '';

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('banners', 'public');
            $imagePath = $path;
        }

        if (empty($imagePath)) {
            return back()->withInput()->withErrors(['image_url' => 'Please provide an Image URL or upload an image file.']);
        }

        Banner::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'cta_text' => $validated['cta_text'],
            'cta_url' => $validated['cta_url'],
            'image_path' => $imagePath,
            'mobile_image_path' => $validated['mobile_image_url'] ?? null,
            'position' => $validated['position'] ?? 'hero',
            'text_alignment' => $validated['text_alignment'] ?? 'left',
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'cta_text' => 'required|string|max:100',
            'cta_url' => 'required|string|max:255',
            'image_url' => 'nullable|url|max:1000',
            'image_file' => 'nullable|image|max:10240',
            'mobile_image_url' => 'nullable|url|max:1000',
            'sort_order' => 'required|integer|min:1|max:99',
            'position' => 'nullable|in:hero,shop',
            'text_alignment' => 'nullable|in:left,center,right',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $banner->image_path;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('banners', 'public');
            $imagePath = $path;
        } elseif (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $banner->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'cta_text' => $validated['cta_text'],
            'cta_url' => $validated['cta_url'],
            'image_path' => $imagePath,
            'mobile_image_path' => $validated['mobile_image_url'] ?? $banner->mobile_image_path,
            'position' => $validated['position'] ?? $banner->position,
            'text_alignment' => $validated['text_alignment'] ?? $banner->text_alignment,
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function toggleActive(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        $status = $banner->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Banner #{$banner->sort_order} has been {$status}.");
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner removed successfully.');
    }
}
