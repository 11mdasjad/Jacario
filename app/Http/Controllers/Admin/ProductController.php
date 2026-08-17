<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variants.size', 'variants.color'])
            ->withTrashed();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('fabric', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->whereNull('deleted_at')->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->whereNull('deleted_at')->where('is_active', false);
            } elseif ($status === 'trashed') {
                $query->onlyTrashed();
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::all();

        return view('admin.products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'fabric' => ['nullable', 'string', 'max:255'],
            'fit' => ['nullable', 'string', 'max:255'],
            'pattern' => ['nullable', 'string', 'max:255'],
            'collar_type' => ['nullable', 'string', 'max:255'],
            'sleeve_type' => ['nullable', 'string', 'max:255'],
            'wash_care' => ['nullable', 'string', 'max:255'],
            'is_bestseller' => ['nullable', 'boolean'],
            'is_new_arrival' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'selected_sizes' => ['nullable', 'array'],
            'selected_colors' => ['nullable', 'array'],
            'default_stock' => ['nullable', 'integer', 'min:0'],
            'variants' => ['nullable', 'array'],
            'image_urls' => ['nullable', 'array'],
            'image_urls.*' => ['nullable', 'string'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'primary_image_index' => ['nullable', 'integer'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug .= '-' . Str::lower(Str::random(4));
        }

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => strtoupper($validated['sku']),
            'category_id' => $validated['category_id'],
            'base_price' => $validated['base_price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'fabric' => $validated['fabric'] ?? '100% Supima® Cotton (240 GSM)',
            'fit' => $validated['fit'] ?? 'Tailored Regular Fit',
            'pattern' => $validated['pattern'] ?? 'Solid',
            'collar_type' => $validated['collar_type'] ?? 'Stay-Flat Ribbed Collar',
            'sleeve_type' => $validated['sleeve_type'] ?? 'Short Sleeve with Ribbed Cuff',
            'wash_care' => $validated['wash_care'] ?? 'Machine wash cold with like colors. Dry flat.',
            'is_bestseller' => $request->boolean('is_bestseller'),
            'is_new_arrival' => $request->boolean('is_new_arrival', true),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
            'seo_title' => $validated['seo_title'] ?? "{$validated['name']} | JACARIO Luxury Polo",
            'seo_description' => $validated['seo_description'] ?? ($validated['short_description'] ?? $validated['name']),
        ]);

        // 1. Create variants
        $defaultStock = (int) ($validated['default_stock'] ?? 20);
        $allSizes = Size::orderBy('sort_order')->get();
        $allColors = Color::all();

        $chosenSizes = !empty($validated['selected_sizes']) 
            ? Size::whereIn('id', $validated['selected_sizes'])->get() 
            : $allSizes;

        $chosenColors = !empty($validated['selected_colors']) 
            ? Color::whereIn('id', $validated['selected_colors'])->get() 
            : $allColors->take(1);

        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $v) {
                if (isset($v['size_id'], $v['color_id'])) {
                    $size = Size::find($v['size_id']);
                    $color = Color::find($v['color_id']);
                    if ($size && $color) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size_id' => $size->id,
                            'color_id' => $color->id,
                            'sku' => "{$product->sku}-{$color->slug}-{$size->code}",
                            'stock_quantity' => (int) ($v['stock'] ?? $defaultStock),
                            'is_active' => true,
                        ]);
                    }
                }
            }
        } else {
            // Auto-generate matrix for chosen sizes and colors
            foreach ($chosenColors as $color) {
                foreach ($chosenSizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'sku' => "{$product->sku}-{$color->slug}-{$size->code}",
                        'stock_quantity' => $defaultStock,
                        'is_active' => true,
                    ]);
                }
            }
        }

        // 2. Handle Multiple Images (URLs + Uploaded Files)
        $collectedImages = [];

        // Check image URLs
        if (!empty($validated['image_urls'])) {
            foreach ($validated['image_urls'] as $idx => $url) {
                if (!empty(trim($url))) {
                    $collectedImages[] = [
                        'type' => 'url',
                        'path' => trim($url),
                    ];
                }
            }
        }

        // Check uploaded image files
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $idx => $file) {
                if ($file && $file->isValid()) {
                    $storedPath = $file->store('products', 'public');
                    $collectedImages[] = [
                        'type' => 'file',
                        'path' => $storedPath,
                    ];
                }
            }
        }

        $primaryIdx = (int) ($validated['primary_image_index'] ?? 0);

        if (!empty($collectedImages)) {
            foreach ($collectedImages as $i => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img['path'],
                    'is_primary' => ($i === $primaryIdx),
                    'sort_order' => $i,
                ]);
            }
        } else {
            // Fallback luxury placeholder
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop&q=80',
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' with " . count($collectedImages) . " images created successfully.");
    }

    public function edit(int $id)
    {
        $product = Product::with(['variants.size', 'variants.color', 'images'])->withTrashed()->findOrFail($id);
        $categories = Category::active()->get();
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::all();

        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'fabric' => ['nullable', 'string', 'max:255'],
            'fit' => ['nullable', 'string', 'max:255'],
            'pattern' => ['nullable', 'string', 'max:255'],
            'collar_type' => ['nullable', 'string', 'max:255'],
            'sleeve_type' => ['nullable', 'string', 'max:255'],
            'wash_care' => ['nullable', 'string', 'max:255'],
            'is_bestseller' => ['nullable', 'boolean'],
            'is_new_arrival' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'variant_stock' => ['nullable', 'array'],
            'variant_stock.*' => ['nullable', 'integer', 'min:0'],
            'image_urls' => ['nullable', 'array'],
            'image_urls.*' => ['nullable', 'string'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'primary_image_id' => ['nullable', 'integer'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:product_images,id'],
        ]);

        $product->update([
            'name' => $validated['name'],
            'sku' => strtoupper($validated['sku']),
            'category_id' => $validated['category_id'],
            'base_price' => $validated['base_price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'fabric' => $validated['fabric'] ?? $product->fabric,
            'fit' => $validated['fit'] ?? $product->fit,
            'pattern' => $validated['pattern'] ?? $product->pattern,
            'collar_type' => $validated['collar_type'] ?? $product->collar_type,
            'sleeve_type' => $validated['sleeve_type'] ?? $product->sleeve_type,
            'wash_care' => $validated['wash_care'] ?? $product->wash_care,
            'is_bestseller' => $request->boolean('is_bestseller'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // 1. Delete marked images
        if (!empty($validated['delete_image_ids'])) {
            $imagesToDelete = ProductImage::whereIn('id', $validated['delete_image_ids'])
                ->where('product_id', $product->id)
                ->get();

            foreach ($imagesToDelete as $img) {
                if (!str_starts_with($img->image_path, 'http') && Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
        }

        // 2. Add new image URLs
        if (!empty($validated['image_urls'])) {
            foreach ($validated['image_urls'] as $url) {
                if (!empty(trim($url))) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => trim($url),
                        'is_primary' => false,
                    ]);
                }
            }
        }

        // 3. Add new uploaded files
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $file) {
                if ($file && $file->isValid()) {
                    $storedPath = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $storedPath,
                        'is_primary' => false,
                    ]);
                }
            }
        }

        // 4. Update primary image
        if (!empty($validated['primary_image_id'])) {
            ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            ProductImage::where('id', $validated['primary_image_id'])
                ->where('product_id', $product->id)
                ->update(['is_primary' => true]);
        } elseif (!ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists()) {
            // Ensure at least one primary image exists
            ProductImage::where('product_id', $product->id)->first()?->update(['is_primary' => true]);
        }

        // 5. Update variant stocks
        if (!empty($validated['variant_stock'])) {
            foreach ($validated['variant_stock'] as $varId => $stock) {
                ProductVariant::where('id', $varId)->where('product_id', $product->id)->update([
                    'stock_quantity' => (int) $stock,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully.");
    }

    public function updateVariantStock(Request $request, int $variantId)
    {
        $validated = $request->validate([
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        $variant = ProductVariant::findOrFail($variantId);
        $variant->update(['stock_quantity' => $validated['stock_quantity']]);

        return response()->json([
            'success' => true,
            'message' => "Stock for {$variant->sku} updated to {$variant->stock_quantity}.",
            'stock' => $variant->stock_quantity,
        ]);
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('info', "Product '{$product->name}' has been moved to trash.");
    }

    public function restore(int $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' restored.");
    }
}
