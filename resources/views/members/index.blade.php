@extends('layouts.master')

@section('title')
    Members Management
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Members
@endslot
@slot('title')
    Member Management
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Members List</h4>
                <div class="flex-shrink-0">
                    @can('create members')
                        <a href="{{ route('members.create') }}" class="btn btn-success btn-sm">
                            <i class="ri-add-line align-middle me-1"></i> Add New Member
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
                <form method="get" action="{{route('members.index')}}">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <input type="text" name="search_value" class="form-control" placeholder="Search members...">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="expired">Expired</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="plan" class="form-select">
                            <option value="">All Plans</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Submit
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{route('members.index')}}" class="btn btn-secondary w-100">
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

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
    {!! $dataTable->scripts() !!}

<script>


function deleteMember(memberId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the member!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '/members/' + memberId;
            form.submit();
        }
    });
}
</script>
@endsection
