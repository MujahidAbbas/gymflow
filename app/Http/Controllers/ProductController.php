<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('products.index');

//        return view('products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = ProductCategory::where('parent_id', parentId())->active()->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean',
            'track_inventory' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['parent_id'] = parentId();
        $validated['active'] = $request->has('active');
        $validated['track_inventory'] = $request->has('track_inventory');

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function show(Product $product): View
    {
        if ($product->parent_id != parentId()) {
            abort(403);
        }

        $product->load(['category', 'sales' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        if ($product->parent_id != parentId()) {
            abort(403);
        }

        $categories = ProductCategory::where('parent_id', parentId())->active()->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if ($product->parent_id != parentId()) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'image' => 'nullable|image|max:2048',
            'active' => 'boolean',
            'track_inventory' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['active'] = $request->has('active');
        $validated['track_inventory'] = $request->has('track_inventory');

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->parent_id != parentId()) {
            abort(403);
        }

        // Check if product has sales
        if ($product->sales()->count() > 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Cannot delete product with existing sales records'
            ]);
//            return back()->with('error', 'Cannot delete product with existing sales records');
        }

        $product->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
