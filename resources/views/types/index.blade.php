@extends('layouts.master')

@section('title')
    Finance Types
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Finance
@endslot
@slot('title')
    Finance Types
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Finance Types</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('types.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line align-middle me-1"></i> Create Type
                    </a>
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

               <form method="get" action="{{route('types.index')}}">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                             Apply
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{route('types.index')}}" class="btn btn-secondary w-100">
                            <i class="ri-refresh-line"></i> Reset
                        </a>
                    </div>
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

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
    {!! $dataTable->scripts() !!}
@endsection
