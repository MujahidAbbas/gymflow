@extends('layouts.master')

@section('title')
    User Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Users
@endslot
@slot('title')
    User Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">User Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit User
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            @if($user->avatar)
                                <img src="{{ URL::asset('images/' . $user->avatar) }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            @else
                                <div class="avatar-xl">
                                    <div class="avatar-title rounded-circle bg-light text-primary text-uppercase fs-1">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <h5 class="fs-16 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Full Name :</th>
                                        <td class="text-muted">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Email :</th>
                                        <td class="text-muted">{{ $user->email }}</td>
                                    </tr>

                                    <tr>
                                        <th class="ps-0" scope="row">Role :</th>
                                        <td class="text-muted">
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-success">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Joined Date :</th>
                                        <td class="text-muted">{{ $user->created_at->format('d M, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Status :</th>
                                        <td class="text-muted">
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning">Unverified</span>
                                            @endif
                                        </td>
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
