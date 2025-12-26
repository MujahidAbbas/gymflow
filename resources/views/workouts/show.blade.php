@extends('layouts.master')

@section('title')
    Workout Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Workouts
@endslot
@slot('title')
    Workout Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Workout Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('workouts.edit', $workout->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Workout
                    </a>
                    <a href="{{ route('workouts.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Workout ID :</th>
                                        <td class="text-muted">{{ $workout->workout_id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Name :</th>
                                        <td class="text-muted">{{ $workout->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Member :</th>
                                        <td class="text-muted">{{ $workout->member->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Trainer :</th>
                                        <td class="text-muted">{{ $workout->trainer->name ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Date :</th>
                                        <td class="text-muted">{{ $workout->workout_date ? $workout->workout_date->format('d M, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($workout->status == 'active') bg-primary
                                                @elseif($workout->status == 'completed') bg-success
                                                @else bg-danger
                                                @endif
                                            ">{{ ucfirst($workout->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Description :</th>
                                        <td class="text-muted">{{ $workout->description ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Notes :</th>
                                        <td class="text-muted">{{ $workout->notes ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="mb-3">Workout Activities</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Exercise</th>
                                        <th>Sets</th>
                                        <th>Reps</th>
                                        <th>Weight (kg)</th>
                                        <th>Duration (min)</th>
                                        <th>Rest (sec)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($workout->activities as $activity)
                                        <tr>
                                            <td>
                                                <strong>{{ $activity->exercise_name }}</strong>
                                                @if($activity->description)
                                                    <br><small class="text-muted">{{ $activity->description }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $activity->sets ?? '-' }}</td>
                                            <td>{{ $activity->reps ?? '-' }}</td>
                                            <td>{{ $activity->weight_kg ?? '-' }}</td>
                                            <td>{{ $activity->duration_minutes ?? '-' }}</td>
                                            <td>{{ $activity->rest_seconds ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No activities found</td>
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
