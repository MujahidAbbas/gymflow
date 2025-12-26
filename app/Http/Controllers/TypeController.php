<?php

namespace App\Http\Controllers;

use App\DataTables\TypeDataTable;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypeController extends Controller
{
    /**
     * Display a listing of the types
     */
    public function index(TypeDataTable $dataTable)
    {

        return $dataTable->render('types.index');
    }

    /**
     * Show the form for creating a new type
     */
    public function create(): View
    {
        return view('types.create');
    }

    /**
     * Store a newly created type
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:income,expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['parent_id'] = parentId();
        $validated['is_active'] = $request->has('is_active');

        Type::create($validated);

        return redirect()->route('types.index')
            ->with('success', 'Finance type created successfully');
    }

    /**
     * Display the specified type
     */
    public function show(Type $type): View
    {
        // Check multi-tenant isolation
        if ($type->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $type->load('expenses');

        return view('types.show', compact('type'));
    }

    /**
     * Show the form for editing the specified type
     */
    public function edit(Type $type): View
    {
        // Check multi-tenant isolation
        if ($type->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        return view('types.edit', compact('type'));
    }

    /**
     * Update the specified type
     */
    public function update(Request $request, Type $type): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($type->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:income,expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $type->update($validated);

        return redirect()->route('types.index')
            ->with('success', 'Finance type updated successfully');
    }

    /**
     * Remove the specified type
     */
    public function destroy(Type $type)
    {
        // Check multi-tenant isolation
        if ($type->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Check if type is being used
        if ($type->expenses()->count() > 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Cannot delete type that is being used in expenses'
            ]);
//            return back()->with('error', 'Cannot delete type that is being used in expenses');
        }

        $type->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
