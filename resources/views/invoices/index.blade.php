@extends('layouts.master')

@section('title')
    Invoices
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Financial
@endslot
@slot('title')
    Invoices
@endslot
@endcomponent

{{-- Summary Cards --}}
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Invoiced</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">${{ number_format($totalAmount, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded fs-3">
                            <i class="ri-file-list-3-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Paid</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-success">${{ number_format($totalPaid, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="ri-checkbox-circle-line text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Due</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-danger">${{ number_format($totalDue, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-danger rounded fs-3">
                            <i class="ri-error-warning-line text-danger"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Invoices List</h4>
                <div class="flex-shrink-0">
                    @can('create payments')
                        <a href="{{ route('invoices.create') }}" class="btn btn-success btn-sm">
                            <i class="ri-add-line align-middle me-1"></i> Create Invoice
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-line align-middle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line align-middle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('invoices.index') }}" class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search invoice # or member...">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-filter-2-line"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
    {!! $dataTable->scripts() !!}
@endsection
