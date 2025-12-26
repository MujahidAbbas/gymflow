@extends('layouts.master')

@section('title')
    Lockers
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Management
@endslot
@slot('title')
    Locker Management
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <!-- Stats Cards -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Lockers</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ $stats['total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Available</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-success">{{ $stats['available'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Occupied</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-danger">{{ $stats['occupied'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Lockers</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('lockers.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add Locker
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

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-10">
                        <select id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="resetFilters" class="btn btn-secondary w-100">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="lockersTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Locker #</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Monthly Fee</th>
                                <th>Current Assignment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lockers as $locker)
                                <tr>
                                    <td><strong>{{ $locker->locker_number }}</strong></td>
                                    <td>{{ $locker->location ?? '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($locker->status == 'available') bg-success
                                            @elseif($locker->status == 'occupied') bg-danger
                                            @else bg-warning text-dark
                                            @endif
                                        ">{{ ucfirst($locker->status) }}</span>
                                    </td>
                                    <td>${{ number_format($locker->monthly_fee, 2) }}</td>
                                    <td>
                                        @if($locker->currentAssignment)
                                            <span class="badge bg-info">{{ $locker->currentAssignment->member->member_id }}</span>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack gap-3 flex-wrap">
                                            <a href="{{ route('lockers.show', $locker->id) }}" class="link-success">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('lockers.edit', $locker->id) }}" class="link-info">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="link-danger" onclick="deleteLocker({{ $locker->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $lockers->links() }}
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
<script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    var table = $('#lockersTable').DataTable({
        responsive: true,
        pageLength: 20,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });

    $('#statusFilter').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    $('#resetFilters').on('click', function() {
        $('#statusFilter').val('');
        table.columns().search('').draw();
    });
});

function deleteLocker(lockerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this locker!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '/lockers/' + lockerId;
            form.submit();
        }
    });
}
</script>
@endsection
