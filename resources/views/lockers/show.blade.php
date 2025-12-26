@extends('layouts.master')

@section('title')
    Locker Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Lockers
@endslot
@slot('title')
    Locker Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Locker Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('lockers.edit', $locker->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Locker
                    </a>
                    <a href="{{ route('lockers.index') }}" class="btn btn-secondary btn-sm ms-1">
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
                                        <th class="ps-0" scope="row">Locker Number :</th>
                                        <td class="text-muted">{{ $locker->locker_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Location :</th>
                                        <td class="text-muted">{{ $locker->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Monthly Fee :</th>
                                        <td class="text-muted">${{ number_format($locker->monthly_fee, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($locker->status == 'available') bg-success
                                                @elseif($locker->status == 'occupied') bg-danger
                                                @else bg-warning text-dark
                                                @endif
                                            ">{{ ucfirst($locker->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Notes :</th>
                                        <td class="text-muted">{{ $locker->notes ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Current Assignment</h5>
                            </div>
                            <div class="card-body">
                                @if($locker->currentAssignment)
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="ps-0" scope="row">Member :</th>
                                                <td>{{ $locker->currentAssignment->member->name ?? 'Unknown' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">Assigned Date :</th>
                                                <td>{{ $locker->currentAssignment->start_date->format('d M, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0" scope="row">End Date :</th>
                                                <td>{{ $locker->currentAssignment->end_date ? $locker->currentAssignment->end_date->format('d M, Y') : 'Ongoing' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted mb-0">This locker is currently not assigned to any member.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="mb-3">Assignment History</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($locker->assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->member->name ?? 'Unknown' }}</td>
                                            <td>{{ $assignment->start_date->format('d M, Y') }}</td>
                                            <td>{{ $assignment->end_date ? $assignment->end_date->format('d M, Y') : '-' }}</td>
                                            <td>
                                                <span class="badge {{ $assignment->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No history found</td>
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
