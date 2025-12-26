@extends('layouts.master')

@section('title')
    Class Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Gym Classes
@endslot
@slot('title')
    Class Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Class Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('gym-classes.edit', $gymClass->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Class
                    </a>
                    <a href="{{ route('gym-classes.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            @if($gymClass->image)
                                <img src="{{ URL::asset('storage/' . $gymClass->image) }}" class="rounded avatar-xl img-thumbnail user-profile-image" alt="class-image">
                            @else
                                <div class="avatar-xl">
                                    <div class="avatar-title rounded bg-light text-primary text-uppercase fs-1">
                                        {{ substr($gymClass->name, 0, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <h5 class="fs-16 mb-1">{{ $gymClass->name }}</h5>
                        <p class="text-muted mb-0">{{ $gymClass->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Class ID :</th>
                                        <td class="text-muted">{{ $gymClass->class_id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Name :</th>
                                        <td class="text-muted">{{ $gymClass->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Duration :</th>
                                        <td class="text-muted">{{ $gymClass->duration_minutes }} Minutes</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Capacity :</th>
                                        <td class="text-muted">{{ $gymClass->enrolled_count }} / {{ $gymClass->max_capacity }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Difficulty :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($gymClass->difficulty_level == 'beginner') bg-success
                                                @elseif($gymClass->difficulty_level == 'intermediate') bg-warning text-dark
                                                @else bg-danger
                                                @endif
                                            ">{{ ucfirst($gymClass->difficulty_level) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($gymClass->status == 'active') bg-success
                                                @elseif($gymClass->status == 'inactive') bg-secondary
                                                @else bg-danger
                                                @endif
                                            ">{{ ucfirst($gymClass->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Description :</th>
                                        <td class="text-muted">{{ $gymClass->description ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="mb-3">Class Schedules</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Trainer</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gymClass->schedules as $schedule)
                                        <tr>
                                            <td>{{ ucfirst($schedule->day_of_week) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                                            <td>{{ $schedule->trainer->name ?? 'N/A' }}</td>
                                            <td>{{ $schedule->room_location ?? 'Main Hall' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No schedules found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
