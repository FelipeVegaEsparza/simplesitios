<?php

namespace App\Http\Controllers\Client\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        $products = $client->products()
            ->with(['category', 'variants'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);
        
        return view('client.store.products.index', compact('products'));
    }

    public function create()
    {
        $user = auth()->user();
        $client = $user->client;
        
        $categories = $client->productCategories()->active()->orderBy('name')->get();
        
        return view('client.store.products.create', compact('categories'));
    }

    public function store(Request $request, ImageProcessingService $imageService)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
            'base_price' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'track_stock' => 'boolean',
            'images.*' => 'nullable|image|max:5120',
            'has_variants' => 'boolean',
            'variants' => 'nullable|array|required_if:has_variants,1',
            'variants.*.variant_name' => 'required_with:variants|string|max:100',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price_override' => 'nullable|integer|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
        ]);

        $validated['client_id'] = $client->id;
        $validated['track_stock'] = $request->boolean('track_stock', true);
        $validated['has_variants'] = $request->boolean('has_variants', false);
        
        // Procesar imágenes
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $result = $imageService->processAndStore($image, 'products/' . $client->id);
                $images[] = $result['path'];
            }
        }
        $validated['images'] = $images;
        
        // Si no tiene variantes, el stock total es el base
        if (!$validated['has_variants']) {
            $validated['total_stock'] = $request->input('base_stock', 0);
        }

        $product = Product::create($validated);

        // Crear variantes si aplica
        if ($validated['has_variants'] && !empty($validated['variants'])) {
            foreach ($validated['variants'] as $index => $variantData) {
                $variantData['price_override'] = $variantData['price_override'] ?? null;
                $variantData['sort_order'] = $index;
                $product->variants()->create($variantData);
            }
            $product->updateTotalStock();
        }

        return redirect()->route('client.store.products.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        
        if ($product->client_id !== $user->client_id) {
            abort(403);
        }
        
        $product->load('variants');
        $categories = $user->client->productCategories()->active()->orderBy('name')->get();
        
        return view('client.store.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product, ImageProcessingService $imageService)
    {
        $user = auth()->user();
        
        if ($product->client_id !== $user->client_id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
            'base_price' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'track_stock' => 'boolean',
            'images.*' => 'nullable|image|max:5120',
            'existing_images' => 'nullable|array',
            'has_variants' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.variant_name' => 'required_with:variants|string|max:100',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price_override' => 'nullable|integer|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
        ]);

        $validated['track_stock'] = $request->boolean('track_stock', true);
        $validated['has_variants'] = $request->boolean('has_variants', false);
        
        // Procesar imágenes
        $images = $request->input('existing_images', []);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $result = $imageService->processAndStore($image, 'products/' . $user->client_id);
                $images[] = $result['path'];
            }
        }
        $validated['images'] = $images;

        $product->update($validated);

        // Actualizar variantes
        if ($validated['has_variants']) {
            $existingIds = [];
            foreach ($request->input('variants', []) as $index => $variantData) {
                $variantData['price_override'] = $variantData['price_override'] ?? null;
                
                if (!empty($variantData['id'])) {
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant && $variant->product_id === $product->id) {
                        $variant->update($variantData);
                        $existingIds[] = $variant->id;
                    }
                } else {
                    $variantData['sort_order'] = $index;
                    $newVariant = $product->variants()->create($variantData);
                    $existingIds[] = $newVariant->id;
                }
            }
            // Eliminar variantes que ya no existen
            $product->variants()->whereNotIn('id', $existingIds)->delete();
            $product->updateTotalStock();
        } else {
            // Si ya no tiene variantes, eliminar todas
            $product->variants()->delete();
            $product->total_stock = $request->input('base_stock', 0);
            $product->save();
        }

        return redirect()->route('client.store.products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();
        
        if ($product->client_id !== $user->client_id) {
            abort(403);
        }
        
        // Devolver stock antes de eliminar si hay órdenes pendientes
        $product->delete();

        return redirect()->route('client.store.products.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function updateStatus(Request $request, Product $product)
    {
        $user = auth()->user();
        
        if ($product->client_id !== $user->client_id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        
        $product->status = $validated['status'];
        $product->save();

        return back()->with('success', 'Estado actualizado correctamente');
    }
}
