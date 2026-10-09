<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\CustomizableProduct;
use App\Models\CustomizableProductVariant;
use App\Models\SavedCustomDesign;
use App\Models\Size;
use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomizationController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $products = CustomizableProduct::with(['settings', 'variants.color', 'variants.size'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $colors = Color::orderBy('name')->get();

        return view('customize.index', compact('products', 'colors'));
    }

    public function studio(string $slug)
    {
        $product = CustomizableProduct::with(['settings', 'variants.color', 'variants.size'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $colors = Color::whereIn('id', $product->variants->where('is_active', true)->pluck('color_id')->unique())
            ->orderBy('name')
            ->get();

        $sizes = Size::whereIn('id', $product->variants->where('is_active', true)->pluck('size_id')->unique())
            ->orderBy('sort_order')
            ->get();

        $savedDesigns = Auth::check() 
            ? Auth::user()->savedCustomDesigns()->where('customizable_product_id', $product->id)->latest()->get()
            : collect();

        return view('customize.studio', compact('product', 'colors', 'sizes', 'savedDesigns'));
    }

    public function uploadArtwork(Request $request)
    {
        $request->validate([
            'artwork' => ['required', 'file', 'mimes:png,jpg,jpeg,svg,webp', 'max:15360'], // 15MB max
        ]);

        try {
            $file = $request->file('artwork');
            $extension = $file->getClientOriginalExtension();
            $filename = 'art_' . Str::random(16) . '_' . time() . '.' . $extension;
            
            // Store inside public storage disk
            $path = $file->storeAs('customizations/artworks', $filename, 'public');

            return response()->json([
                'success' => true,
                'file_path' => 'storage/' . $path,
                'file_url' => asset('storage/' . $path),
                'original_name' => $file->getClientOriginalName(),
                'size_bytes' => $file->getSize(),
            ]);
        } catch (Exception $e) {
            Log::error('Customization artwork upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload artwork. Please verify file format and try again.',
            ], 422);
        }
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:customizable_products,id'],
            'color_id' => ['required', 'integer', 'exists:colors,id'],
            'size_id' => ['required', 'integer', 'exists:sizes,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
            'customization_data' => ['required', 'array'],
            'preview_image' => ['nullable', 'string'], // base64 data URI
            'artwork_file_path' => ['nullable', 'string'],
            'artwork_files' => ['nullable', 'array'],
        ]);

        try {
            $previewPath = null;
            if (!empty($validated['preview_image']) && str_starts_with($validated['preview_image'], 'data:image')) {
                $previewData = $validated['preview_image'];
                $imageParts = explode(';base64,', $previewData);
                if (count($imageParts) === 2) {
                    $imageTypeAux = explode('image/', $imageParts[0]);
                    $imageType = $imageTypeAux[1] ?? 'png';
                    $imageBase64 = base64_decode($imageParts[1]);
                    $previewFilename = 'preview_' . Str::random(16) . '_' . time() . '.' . $imageType;
                    
                    Storage::disk('public')->put('customizations/previews/' . $previewFilename, $imageBase64);
                    $previewPath = 'storage/customizations/previews/' . $previewFilename;
                }
            }

            $result = $this->cartService->addCustomItem([
                'product_id' => $validated['product_id'],
                'color_id' => $validated['color_id'],
                'size_id' => $validated['size_id'],
                'quantity' => $validated['quantity'],
                'customization_data' => $validated['customization_data'],
                'preview_path' => $previewPath,
                'artwork_file_path' => $validated['artwork_file_path'] ?? null,
                'artwork_files' => $validated['artwork_files'] ?? [],
            ]);

            return response()->json($result, $result['success'] ? 200 : 422);
        } catch (Exception $e) {
            Log::error('Failed to add custom item to bag', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to add custom item to your shopping bag: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function saveDesign(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'require_auth' => true,
                'message' => 'Please sign in to save your custom designs to your Jacario account.',
            ], 401);
        }

        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:customizable_products,id'],
            'color_id' => ['nullable', 'integer', 'exists:colors,id'],
            'size_id' => ['nullable', 'integer', 'exists:sizes,id'],
            'title' => ['required', 'string', 'max:100'],
            'design_data' => ['required', 'array'],
            'preview_image' => ['nullable', 'string'],
            'artwork_paths' => ['nullable', 'array'],
        ]);

        $previewPath = null;
        if (!empty($validated['preview_image']) && str_starts_with($validated['preview_image'], 'data:image')) {
            $imageParts = explode(';base64,', $validated['preview_image']);
            if (count($imageParts) === 2) {
                $imageTypeAux = explode('image/', $imageParts[0]);
                $imageType = $imageTypeAux[1] ?? 'png';
                $imageBase64 = base64_decode($imageParts[1]);
                $previewFilename = 'saved_' . Str::random(16) . '_' . time() . '.' . $imageType;
                
                Storage::disk('public')->put('customizations/previews/' . $previewFilename, $imageBase64);
                $previewPath = 'storage/customizations/previews/' . $previewFilename;
            }
        }

        $saved = SavedCustomDesign::create([
            'user_id' => Auth::id(),
            'customizable_product_id' => $validated['product_id'],
            'color_id' => $validated['color_id'] ?? null,
            'size_id' => $validated['size_id'] ?? null,
            'title' => $validated['title'],
            'design_data' => $validated['design_data'],
            'preview_path' => $previewPath,
            'artwork_paths' => $validated['artwork_paths'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Design saved successfully to your Jacario profile.',
            'design_id' => $saved->id,
        ]);
    }
}
