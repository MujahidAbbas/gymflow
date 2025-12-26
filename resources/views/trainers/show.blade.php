@extends('layouts.master')

@section('title')
    Trainer Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Trainers
@endslot
@slot('title')
    Trainer Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Trainer Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('trainers.edit', $trainer->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Trainer
                    </a>
                    <a href="{{ route('trainers.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            @if($trainer->photo)
                                <img src="{{ URL::asset('storage/' . $trainer->photo) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            @else
                                <div class="avatar-xl">
                                    <div class="avatar-title rounded-circle bg-light text-primary text-uppercase fs-1">
                                        {{ substr($trainer->name, 0, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <h5 class="fs-16 mb-1">{{ $trainer->name }}</h5>
                        <p class="text-muted mb-0">{{ $trainer->email }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Trainer ID :</th>
                                        <td class="text-muted">{{ $trainer->trainer_id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Full Name :</th>
                                        <td class="text-muted">{{ $trainer->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Email :</th>
                                        <td class="text-muted">{{ $trainer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Phone :</th>
                                        <td class="text-muted">{{ $trainer->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Specialization :</th>
                                        <td class="text-muted">{{ $trainer->specialization ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Experience :</th>
                                        <td class="text-muted">{{ $trainer->experience_years ? $trainer->experience_years . ' Years' : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Joined Date :</th>
                                        <td class="text-muted">{{ $trainer->created_at->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            <span class="badge 
                                                @if($trainer->status == 'active') bg-success
                                                @elseif($trainer->status == 'inactive') bg-secondary
                                                @else bg-warning text-dark
                                                @endif
                                            ">{{ ucfirst($trainer->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Bio :</th>
                                        <td class="text-muted">{{ $trainer->bio ?? 'N/A' }}</td>
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
