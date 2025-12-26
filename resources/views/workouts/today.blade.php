@extends('layouts.master')

@section('title')
    Today's Workouts
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Workouts
@endslot
@slot('title')
    Today's Workouts
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Today's Schedule ({{ now()->format('M d, Y') }})</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('workouts.index') }}" class="btn btn-primary btn-sm me-2">
                        <i class="ri-list-check align-middle me-1"></i> All Workouts
                    </a>
                    <a href="{{ route('workouts.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line align-middle me-1"></i> Create Workout
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

                <!-- Table -->
                <div class="table-responsive">
                    <table id="workoutsTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Workout ID</th>
                                <th>Name</th>
                                <th>Member</th>
                                <th>Trainer</th>
                                <th>Activities</th>
                                <th>Completion</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workouts as $workout)
                                <tr>
                                    <td><strong>{{ $workout->workout_id }}</strong></td>
                                    <td>{{ $workout->name }}</td>
                                    <td>{{ $workout->member ? $workout->member->name : 'N/A' }}</td>
                                    <td>{{ $workout->trainer ? $workout->trainer->name : 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-soft-info">{{ $workout->activities->count() }} exercises</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $workout->completion_percentage }}%"
                                                 aria-valuenow="{{ $workout->completion_percentage }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $workout->completion_percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($workout->status == 'active') badge-soft-success
                                            @elseif($workout->status == 'completed') badge-soft-info
                                            @else badge-soft-danger
                                            @endif
                                        ">{{ ucfirst($workout->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-3 flex-wrap">
                                            <a href="{{ route('workouts.show', $workout->id) }}" class="link-success">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('workouts.edit', $workout->id) }}" class="link-info">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="link-danger" onclick="deleteWorkout({{ $workout->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    $('#workoutsTable').DataTable({
        responsive: true,
        pageLength: 15,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [7] }
        ]
    });
});

function deleteWorkout(workoutId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the workout and all its activities!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '/workouts/' + workoutId;
            form.submit();
        }
    });
}
</script>
@endsection
