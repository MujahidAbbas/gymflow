<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Models\Expense;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses
     */
    public function index(ExpenseDataTable $dataTable)
    {
        $parentId = parentId();

        $types = Type::where('parent_id', $parentId)->expense()->active()->get();

        // Calculate total
//        $totalExpenses = $query->sum('amount');
        $totalExpenses = 0;

        return $dataTable->render('expenses.index', compact( 'types', 'totalExpenses'));
    }

    /**
     * Show the form for creating a new expense
     */
    public function create(): View
    {
        $parentId = parentId();
        $types = Type::where('parent_id', $parentId)->expense()->active()->get();

        return view('expenses.create', compact('types'));
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,other',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        $validated['parent_id'] = parentId();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully with ID: '.$expense->expense_number);
    }

    /**
     * Display the specified expense
     */
    public function show(Expense $expense): View
    {
        // Check multi-tenant isolation
        if ($expense->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $expense->load('type');

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense
     */
    public function edit(Expense $expense): View
    {
        // Check multi-tenant isolation
        if ($expense->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $parentId = parentId();
        $types = Type::where('parent_id', $parentId)->expense()->active()->get();

        return view('expenses.edit', compact('expense', 'types'));
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        // Check multi-tenant isolation
        if ($expense->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'type_id' => 'required|exists:types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,other',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt) {
                Storage::disk('public')->delete($expense->receipt);
            }
            $validated['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully');
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense)
    {
        // Check multi-tenant isolation
        if ($expense->parent_id != parentId()) {
            abort(403, 'Unauthorized access');
        }

        // Delete receipt if exists
        if ($expense->receipt) {
            Storage::disk('public')->delete($expense->receipt);
        }

        $expense->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
