@extends('layouts.master')

@section('title')
    Type Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Finance Types
@endslot
@slot('title')
    Type Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Type Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('types.edit', $type->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Type
                    </a>
                    <a href="{{ route('types.index') }}" class="btn btn-secondary btn-sm ms-1">
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
                                        <th class="ps-0" scope="row">Name :</th>
                                        <td class="text-muted">{{ $type->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Category :</th>
                                        <td class="text-muted">
                                            <span class="badge {{ $type->category == 'income' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($type->category) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $type->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Description :</th>
                                        <td class="text-muted">{{ $type->description ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="mb-3">Related Expenses</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($type->expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date->format('d M, Y') }}</td>
                                            <td>{{ $expense->title }}</td>
                                            <td>${{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No expenses found for this type</td>
                                        </tr>
                                    @endforelse
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
