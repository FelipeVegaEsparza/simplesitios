<?php

namespace App\Http\Controllers\Client\Store;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        $categories = $client->productCategories()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);
        
        return view('client.store.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['client_id'] = $client->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $client->productCategories()->count();

        ProductCategory::create($validated);

        return back()->with('success', 'Categoría creada correctamente');
    }

    public function update(Request $request, ProductCategory $category)
    {
        $user = auth()->user();
        
        if ($category->client_id !== $user->client_id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);

        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return back()->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(ProductCategory $category)
    {
        $user = auth()->user();
        
        if ($category->client_id !== $user->client_id) {
            abort(403);
        }
        
        if ($category->products()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría con productos asociados');
        }
        
        $category->delete();

        return back()->with('success', 'Categoría eliminada correctamente');
    }

    public function reorder(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:product_categories,id',
        ]);

        foreach ($validated['categories'] as $index => $categoryId) {
            $category = ProductCategory::find($categoryId);
            if ($category && $category->client_id === $client->id) {
                $category->update(['sort_order' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }
}
