<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Katalog + otorisasi edit/delete via ProductPolicy (setara PostPolicy).
// Aturan: view = semua login, create/update = admin+editor, delete = admin.
class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // Eager loading category agar tidak N+1 (bonus demo)
        $products = Product::with('category')
            ->active()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    public function edit(Product $product): View
    {
        // Policy check — 403 jika user biasa mencoba edit (No.7 + No.8)
        $this->authorize('update', $product);

        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('products.index')->with('status', 'Produk diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Hanya admin (ProductPolicy::delete)
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produk dihapus.');
    }
}
