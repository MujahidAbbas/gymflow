@extends('layouts.master')

@section('title')
    Expense Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Expenses
@endslot
@slot('title')
    Expense Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Expense Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Expense
                    </a>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Expense Number :</th>
                                        <td class="text-muted">{{ $expense->expense_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Title :</th>
                                        <td class="text-muted">{{ $expense->title }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Type :</th>
                                        <td class="text-muted">
                                            <span class="badge bg-info">{{ $expense->type->name ?? 'Uncategorized' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Amount :</th>
                                        <td class="text-muted">${{ number_format($expense->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Date :</th>
                                        <td class="text-muted">{{ $expense->expense_date->format('d M, Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Payment Method :</th>
                                        <td class="text-muted">{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Description :</th>
                                        <td class="text-muted">{{ $expense->description ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Notes :</th>
                                        <td class="text-muted">{{ $expense->notes ?? 'N/A' }}</td>
                                    </tr>
                                    @if($expense->receipt)
                                        <tr>
                                            <th class="ps-0" scope="row">Receipt :</th>
                                            <td>
                                                <a href="{{ URL::asset('storage/' . $expense->receipt) }}" target="_blank" class="btn btn-soft-primary btn-sm">
                                                    <i class="ri-download-line align-middle me-1"></i> View Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
