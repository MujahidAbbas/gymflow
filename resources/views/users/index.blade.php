@extends('layouts.master')

@section('title')
    Users Management
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Users
@endslot
@slot('title')
    User Management
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Users List</h4>
                <div class="flex-shrink-0">
                    @can('create users')
                        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                            <i class="ri-add-line align-middle me-1"></i> Add New User
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
                <form method="get" action="{{route('users.index')}}">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search_value" name="search_value" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select  class="form-select" name="role">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">

                        <button type="submit" class="btn btn-primary w-100">
                             Submit
                        </button>
                    </div>
                    <div class="col-md-2">

                        <a href="{{route('users.index')}}" class="btn btn-secondary w-100">
                            <i class="ri-refresh-line"></i> Reset
                        </a>
                    </div>
                </div>
                </form>

                <!-- Table -->
                    {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
{{--<script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>--}}

{!! $dataTable->scripts() !!}

<script>
    // Initialize DataTable (disable built-in search/filter since we use server-side)

    // Server-side filtering function
    function applyFilters() {
        var search = $('#searchInput').val();
        var role = $('#roleFilter').val();
        var url = new URL(window.location.href);
        
        if (search) {
            url.searchParams.set('search_value', search);
        } else {
            url.searchParams.delete('search_value');
        }
        
        if (role) {
            url.searchParams.set('role', role);
        } else {
            url.searchParams.delete('role');
        }
        
        // Reset to page 1 when filtering
        url.searchParams.delete('page');
        
        window.location.href = url.toString();
    }

    // Search on Enter key
    $('#searchInput').on('keypress', function(e) {
        if (e.which == 13) {
            applyFilters();
        }
    });

    // Role filter
    $('#roleFilter').on('change', function() {
        applyFilters();
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        var url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('role');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

function deleteUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '/users/' + userId;
            form.submit();
        }
    });
}
</script>
@endsection
