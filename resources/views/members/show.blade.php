@extends('layouts.master')

@section('title')
    Member Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Members
@endslot
@slot('title')
    Member Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Member Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Member
                    </a>
                    <a href="{{ route('members.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            @if($member->photo)
                                <img src="{{ URL::asset('storage/' . $member->photo) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            @else
                                <div class="avatar-xl">
                                    <div class="avatar-title rounded-circle bg-light text-primary text-uppercase fs-1">
                                        {{ substr($member->name, 0, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <h5 class="fs-16 mb-1">{{ $member->name }}</h5>
                        <p class="text-muted mb-0">{{ $member->email }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Member ID :</th>
                                        <td class="text-muted">{{ $member->member_id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Full Name :</th>
                                        <td class="text-muted">{{ $member->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Email :</th>
                                        <td class="text-muted">{{ $member->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Phone :</th>
                                        <td class="text-muted">{{ $member->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Membership Plan :</th>
                                        <td class="text-muted">
                                            @if($member->membershipPlan)
                                                <span class="badge bg-info">{{ $member->membershipPlan->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">No Plan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Start Date :</th>
                                        <td class="text-muted">{{ $member->membership_start_date ? $member->membership_start_date->format('d M, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">End Date :</th>
                                        <td class="text-muted">
                                            @if($member->membership_end_date)
                                                {{ $member->membership_end_date->format('d M, Y') }}
                                                @if($member->isExpired())
                                                    <span class="badge bg-danger ms-1">Expired</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Lifetime</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($member->status == 'active') bg-success
                                                @elseif($member->status == 'inactive') bg-secondary
                                                @elseif($member->status == 'expired') bg-danger
                                                @else bg-warning text-dark
                                                @endif
                                            ">{{ ucfirst($member->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Address :</th>
                                        <td class="text-muted">{{ $member->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Emergency Contact :</th>
                                        <td class="text-muted">{{ $member->emergency_contact ?? 'N/A' }}</td>
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
