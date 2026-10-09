<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\CustomizableProduct;
use App\Models\CustomizableProductVariant;
use App\Models\CustomizationSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Size;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomizationController extends Controller
{
    public function index()
    {
        // Customization Dashboard KPIs
        $customizedItems = OrderItem::where('is_customized', true);

        $totalCustomizedOrders = Order::whereHas('items', function ($q) {
            $q->where('is_customized', true);
        })->count();

        $pendingProductionCount = OrderItem::where('is_customized', true)
            ->whereIn('custom_production_status', ['pending', 'artwork_approved', 'in_printing'])
            ->count();

        $completedProductionCount = OrderItem::where('is_customized', true)
            ->where('custom_production_status', 'completed')
            ->count();

        $customizationRevenue = OrderItem::where('is_customized', true)
            ->sum('subtotal');

        // Most customized product
        $mostCustomized = OrderItem::where('is_customized', true)
            ->select('customizable_product_id', DB::raw('count(*) as count'), DB::raw('sum(quantity) as total_qty'))
            ->groupBy('customizable_product_id')
            ->orderByDesc('total_qty')
            ->with('customizableProduct')
            ->first();

        $recentCustomOrders = Order::whereHas('items', function ($q) {
            $q->where('is_customized', true);
        })
        ->with(['items' => function ($q) {
            $q->where('is_customized', true);
        }])
        ->latest()
        ->take(10)
        ->get();

        $products = CustomizableProduct::with(['settings', 'variants'])->get();

        return view('admin.customizations.index', compact(
            'totalCustomizedOrders',
            'pendingProductionCount',
            'completedProductionCount',
            'customizationRevenue',
            'mostCustomized',
            'recentCustomOrders',
            'products'
        ));
    }

    public function products()
    {
        $products = CustomizableProduct::with(['settings', 'variants'])->get();
        return view('admin.customizations.products', compact('products'));
    }

    public function editProduct(int $id)
    {
        $product = CustomizableProduct::with(['settings', 'variants.color', 'variants.size'])->findOrFail($id);
        return view('admin.customizations.product-edit', compact('product'));
    }

    public function updateProduct(Request $request, int $id)
    {
        $product = CustomizableProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'sku' => ['required', 'string', 'max:50', 'unique:customizable_products,sku,' . $product->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.customizations.products')->with('success', "Product '{$product->name}' updated successfully.");
    }

    public function variants(int $id)
    {
        $product = CustomizableProduct::with(['variants.color', 'variants.size'])->findOrFail($id);
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('name')->get();

        return view('admin.customizations.variants', compact('product', 'sizes', 'colors'));
    }

    public function updateVariants(Request $request, int $id)
    {
        $product = CustomizableProduct::findOrFail($id);

        $variantsData = $request->input('variants', []);
        foreach ($variantsData as $variantId => $vData) {
            $variant = CustomizableProductVariant::where('customizable_product_id', $product->id)->find($variantId);
            if ($variant) {
                $variant->update([
                    'stock_quantity' => max(0, (int) ($vData['stock_quantity'] ?? 0)),
                    'price_override' => !empty($vData['price_override']) ? (float) $vData['price_override'] : null,
                    'is_active' => isset($vData['is_active']),
                ]);
            }
        }

        return redirect()->back()->with('success', "Variants for '{$product->name}' updated successfully.");
    }

    public function settings(int $id)
    {
        $product = CustomizableProduct::with('settings')->findOrFail($id);
        $settings = $product->settings ?: new CustomizationSetting(['customizable_product_id' => $product->id]);

        return view('admin.customizations.settings', compact('product', 'settings'));
    }

    public function updateSettings(Request $request, int $id)
    {
        $product = CustomizableProduct::findOrFail($id);

        $validated = $request->validate([
            'allow_front' => ['nullable', 'boolean'],
            'allow_back' => ['nullable', 'boolean'],
            'allowed_file_types' => ['nullable', 'string'], // comma-separated
            'max_file_size_mb' => ['required', 'integer', 'min:1', 'max:50'],
            'allowed_fonts' => ['nullable', 'string'], // comma-separated
            'min_text_len' => ['required', 'integer', 'min:1'],
            'max_text_len' => ['required', 'integer', 'min:1', 'max:100'],
            'max_images' => ['required', 'integer', 'min:1', 'max:10'],
            'base_customization_fee' => ['required', 'numeric', 'min:0'],
            'extra_image_fee' => ['required', 'numeric', 'min:0'],
            'extra_text_fee' => ['required', 'numeric', 'min:0'],
            'bulk_min_qty' => ['nullable', 'array'],
            'bulk_discount' => ['nullable', 'array'],
        ]);

        $fileTypes = array_filter(array_map('trim', explode(',', $validated['allowed_file_types'] ?? 'png,jpg,jpeg,svg,webp')));
        $fonts = array_filter(array_map('trim', explode(',', $validated['allowed_fonts'] ?? 'Cinzel, Plus Jakarta Sans, Inter, Montserrat')));

        $bulkTiers = [];
        if (!empty($validated['bulk_min_qty']) && !empty($validated['bulk_discount'])) {
            foreach ($validated['bulk_min_qty'] as $idx => $minQty) {
                $discount = $validated['bulk_discount'][$idx] ?? 0;
                if ((int) $minQty > 0 && (float) $discount > 0) {
                    $bulkTiers[] = [
                        'min_qty' => (int) $minQty,
                        'discount_percent' => (float) $discount,
                    ];
                }
            }
        }

        CustomizationSetting::updateOrCreate(
            ['customizable_product_id' => $product->id],
            [
                'allow_front' => $request->has('allow_front'),
                'allow_back' => $request->has('allow_back'),
                'allowed_file_types' => array_values($fileTypes),
                'max_file_size_mb' => $validated['max_file_size_mb'],
                'allowed_fonts' => array_values($fonts),
                'min_text_len' => $validated['min_text_len'],
                'max_text_len' => $validated['max_text_len'],
                'max_images' => $validated['max_images'],
                'base_customization_fee' => $validated['base_customization_fee'],
                'extra_image_fee' => $validated['extra_image_fee'],
                'extra_text_fee' => $validated['extra_text_fee'],
                'bulk_pricing' => $bulkTiers,
            ]
        );

        return redirect()->back()->with('success', "Studio settings for '{$product->name}' updated successfully.");
    }

    public function orders(Request $request)
    {
        $query = OrderItem::where('is_customized', true)
            ->with(['order', 'customizableProduct'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('custom_production_status', $request->status);
        }

        if ($request->filled('product_id')) {
            $query->where('customizable_product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(20);
        $products = CustomizableProduct::all();

        return view('admin.customizations.orders', compact('items', 'products'));
    }

    public function showOrderItem(int $itemId)
    {
        $item = OrderItem::where('is_customized', true)
            ->with(['order', 'customizableProduct.settings'])
            ->findOrFail($itemId);

        return view('admin.customizations.order-detail', compact('item'));
    }

    public function updateProductionStatus(Request $request, int $itemId)
    {
        $item = OrderItem::where('is_customized', true)->findOrFail($itemId);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,artwork_approved,in_printing,completed'],
        ]);

        $item->update(['custom_production_status' => $validated['status']]);

        return redirect()->back()->with('success', "Production status for customized item updated to '" . ucfirst(str_replace('_', ' ', $validated['status'])) . "'.");
    }

    public function downloadArtwork(int $itemId, Request $request)
    {
        $item = OrderItem::where('is_customized', true)->findOrFail($itemId);
        $artworkPath = $request->query('path', $item->artwork_file_path);

        if (!$artworkPath) {
            return back()->with('error', 'No artwork file uploaded for this customization.');
        }

        // Convert path to local filesystem path
        $cleanPath = str_replace(['storage/', '/storage/'], '', $artworkPath);
        $fullPath = storage_path('app/public/' . $cleanPath);

        if (!file_exists($fullPath)) {
            // Also check if stored in public directory directly
            $altPath = public_path($artworkPath);
            if (file_exists($altPath)) {
                $fullPath = $altPath;
            } else {
                return back()->with('error', 'Artwork file not found on the server storage.');
            }
        }

        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $downloadName = 'JACARIO_Artwork_' . ($item->order ? $item->order->order_number : 'Order') . '_Item' . $item->id . '.' . $extension;

        return response()->download($fullPath, $downloadName);
    }
}
