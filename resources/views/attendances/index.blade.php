@extends('layouts.master')

@section('title')
    Attendance Tracking
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Attendance
@endslot
@slot('title')
    Attendance Tracking
@endslot
@endcomponent

<!-- Stats Cards -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Visits Today</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-2">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ $todayStats['total'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="ri-user-line text-success"></i>
                        </span>
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
                        <p class="text-uppercase fw-medium text-muted mb-0">Currently In Gym</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-2">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ $todayStats['checked_in'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info rounded fs-3">
                            <i class="ri-login-box-line text-info"></i>
                        </span>
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
                        <p class="text-uppercase fw-medium text-muted mb-0">Checked Out</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-2">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ $todayStats['checked_out'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning rounded fs-3">
                            <i class="ri-logout-box-line text-warning"></i>
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
                <h4 class="card-title mb-0 flex-grow-1">Attendance Records</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('attendances.report') }}" class="btn btn-info btn-sm me-2">
                        <i class="ri-bar-chart-line align-middle me-1"></i> View Report
                    </a>
                    <a href="{{ route('attendances.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-login-circle-line align-middle me-1"></i> Check In
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

               <form method="get" action="{{route('attendances.index')}}">
                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-5">
                        <select name="mamber" class="form-select">
                            <option value="">All Members</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-filter-line"></i> Apply
                        </button>
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

<!-- Check Out Modal -->
<div class="modal fade" id="checkOutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="checkOutForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Check Out Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="check_out_time" class="form-label">Check Out Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="check_out_time" name="check_out_time" 
                               value="{{ date('H:i') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-logout-circle-line me-1"></i> Check Out
                    </button>
                </div>
            </form>
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

<script>


function checkOut(attendanceId) {
    const modal = new bootstrap.Modal(document.getElementById('checkOutModal'));
    const form = document.getElementById('checkOutForm');
    form.action = '/attendances/' + attendanceId;
    modal.show();
}

function deleteAttendance(attendanceId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this attendance record!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '/attendances/' + attendanceId;
            form.submit();
        }
    });
}
</script>
@endsection
