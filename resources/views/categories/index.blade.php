@extends('layouts.master')

@section('title')
    Categories
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Classes
@endslot
@slot('title')
    Categories Management
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Class Categories</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add Category
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

                    {!! $dataTable->table() !!}
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
