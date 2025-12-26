<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(CategoryDataTable $dataTable)
    {


        return $dataTable->render('categories.index');

//        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
//            'is_active' => 'boolean',
        ]);

        $validated['parent_id'] = parentId();
        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category): View
    {
        // Check multi-tenant isolation
        if ($category->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category): RedirectResponse
    {

        // Check multi-tenant isolation
        if ($category->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
//            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified category
     */
    public function delete(Category $category)
    {
        // Check multi-tenant isolation
        if ($category->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $category->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
