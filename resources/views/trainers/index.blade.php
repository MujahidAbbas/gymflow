@extends('layouts.master')

@section('title')
    Trainers Management
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Trainers
@endslot
@slot('title')
    Trainer Management
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Trainers List</h4>
                <div class="flex-shrink-0">
                    @can('create trainers')
                        <a href="{{ route('trainers.create') }}" class="btn btn-success btn-sm">
                            <i class="ri-add-line align-middle me-1"></i> Add New Trainer
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
                <form method="get" action="{{route('trainers.index')}}">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search_value" class="form-control" placeholder="Search trainers...">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            Submit
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{route('trainers.index')}}" class="btn btn-secondary w-100">
                            <i class="ri-refresh-line"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>

                </form>
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
