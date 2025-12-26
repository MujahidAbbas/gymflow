@extends('layouts.master')

@section('title')
    Events
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
    Event Calendar
@endslot
@endcomponent

<div class="row">
    <!-- Upcoming Events -->
    @if($upcomingEvents->count() > 0)
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upcoming Events</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($upcomingEvents as $event)
                    <div class="col-md-4">
                        <div class="card border">
                            @if($event->image)
                            <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $event->title }}</h6>
                                <p class="text-muted mb-2"><i class="ri-calendar-line me-1"></i> {{ $event->start_time->format('M d, Y H:i') }}</p>
                                <p class="text-muted mb-2"><i class="ri-map-pin-line me-1"></i> {{ $event->location ?? 'TBA' }}</p>
                                @if($event->max_participants)
                                <p class="mb-0"><span class="badge bg-info">{{ $event->available_spots }} spots left</span></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">All Events</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('events.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line align-middle me-1"></i> Add Event
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
                    <table id="eventsTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Start Time</th>
                                <th>Location</th>
                                <th>Participants</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td><strong>{{ $event->title }}</strong></td>
                                    <td>{{ $event->start_time->format('M d, Y H:i') }}</td>
                                    <td>{{ $event->location ?? '-' }}</td>
                                    <td>
                                        @if($event->max_participants)
                                            {{ $event->registered_count }}/{{ $event->max_participants }}
                                        @else
                                            {{ $event->registered_count }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($event->status == 'scheduled') bg-primary
                                            @elseif($event->status == 'ongoing') bg-success
                                            @elseif($event->status == 'completed') bg-secondary
                                            @else bg-danger
                                            @endif
                                        ">{{ ucfirst($event->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-3 flex-wrap">
                                            <a href="{{ route('events.show', $event->id) }}" class="link-success">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('events.edit', $event->id) }}" class="link-info">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="link-danger" onclick="deleteEvent({{ $event->id }})">
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
                    {{ $events->links() }}
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
    $('#eventsTable').DataTable({
        responsive: true,
        pageLength: 20,
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});

function deleteEvent(eventId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete this event!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteForm');
            form.action = '/events/' + eventId;
            form.submit();
        }
    });
}
</script>
@endsection
