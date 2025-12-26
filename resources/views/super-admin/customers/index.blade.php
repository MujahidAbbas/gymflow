@extends('layouts.master')

@section('title') Customers @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('title') Customers @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">Customer List</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('super-admin.customers.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-bottom me-1"></i> Add Customer
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter -->
                <form method="GET" action="{{ route('super-admin.customers.index') }}">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <input type="text" name="search_value" class="form-control" placeholder="Search by business name or email..." value="{{ request('search_value') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('super-admin.customers.index') }}" class="btn btn-light">Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
            {!! $dataTable->table() !!}

                <!-- Pagination -->

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
<script>
    // In your blade file or separate JS file
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#customer-table').DataTable();

        // Force sorting icons to show
        table.on('draw', function() {
            // Add sorting class to sortable columns
            $('table.dataTable thead th').each(function() {
                if ($(this).hasClass('sorting')) {
                    $(this).addClass('sorting_1');
                }
            });
        });
    });

    function impersonateCustomer(id) {
        $.ajax({
            url: "{{ url('super-admin/customers') }}/" + id + "/impersonate",
            cache: false,
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status === true) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                    });
                    setTimeout(function() {
                        location.replace('{{ route('dashboard') }}');
                    }, 1000);
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: "warning",
                    });
                }
            },
            error: function(xhr, status, errorThrown) {
                Swal.fire({
                    text: xhr.responseJSON?.message || 'An error occurred',
                    icon: "warning",
                });
            }
        });
    }
</script>
    @endpush
