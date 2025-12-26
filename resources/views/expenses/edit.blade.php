@extends('layouts.master')

@section('title')
    Edit Expense
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('expenses.index') }}">Expenses</a>
@endslot
@slot('title')
    Edit Expense: {{ $expense->expense_number }}
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Expense Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type_id" class="form-label">Expense Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type_id') is-invalid @enderror" 
                                        id="type_id" name="type_id" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id', $expense->type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('expense_date') is-invalid @enderror" 
                                       id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $expense->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" 
                                       step="0.01" min="0" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ old('payment_method', $expense->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="bank_transfer" {{ old('payment_method', $expense->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cheque" {{ old('payment_method', $expense->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="other" {{ old('payment_method', $expense->payment_method) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $expense->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="receipt" class="form-label">Receipt</label>
                                @if($expense->receipt)
                                    <div class="mb-2">
                                        <a href="{{ $expense->receipt_url }}" target="_blank" class="btn btn-sm btn-soft-primary">
                                            <i class="ri-file-text-line"></i> View Current Receipt
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('receipt') is-invalid @enderror" 
                                       id="receipt" name="receipt" accept="image/*,.pdf">
                                <small class="text-muted">Upload new receipt to replace existing. Accepted: JPG, PNG, PDF (Max: 2MB)</small>
                                @error('receipt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i> Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
