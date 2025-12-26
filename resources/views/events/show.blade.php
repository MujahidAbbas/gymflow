@extends('layouts.master')

@section('title')
    Event Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Events
@endslot
@slot('title')
    Event Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Event Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Event
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            @if($event->image)
                                <img src="{{ URL::asset('storage/' . $event->image) }}" class="rounded avatar-xl img-thumbnail user-profile-image" alt="event-image">
                            @else
                                <div class="avatar-xl">
                                    <div class="avatar-title rounded bg-light text-primary text-uppercase fs-1">
                                        {{ substr($event->title, 0, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <h5 class="fs-16 mb-1">{{ $event->title }}</h5>
                        <p class="text-muted mb-0">{{ $event->location ?? 'No location specified' }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Title :</th>
                                        <td class="text-muted">{{ $event->title }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Start Time :</th>
                                        <td class="text-muted">{{ $event->start_time->format('d M, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">End Time :</th>
                                        <td class="text-muted">{{ $event->end_time->format('d M, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Participants :</th>
                                        <td class="text-muted">{{ $event->registered_count }} / {{ $event->max_participants ?? 'Unlimited' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($event->status == 'scheduled') bg-info
                                                @elseif($event->status == 'ongoing') bg-success
                                                @elseif($event->status == 'completed') bg-secondary
                                                @else bg-danger
                                                @endif
                                            ">{{ ucfirst($event->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Description :</th>
                                        <td class="text-muted">{{ $event->description ?? 'N/A' }}</td>
                                    </tr>
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
