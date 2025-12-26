@extends('layouts.master')

@section('title')
    View Membership Plan
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Membership Plans
        @endslot
        @slot('title')
            View Plan
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Plan Details</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('membership-plans.edit', $membershipPlan->id) }}" class="btn btn-primary">
                            <i class="ri-pencil-line align-bottom me-1"></i> Edit
                        </a>
                        <a href="{{ route('membership-plans.index') }}" class="btn btn-light">
                            Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Name :</th>
                                    <td class="text-muted">{{ $membershipPlan->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Price :</th>
                                    <td class="text-muted">{{ $membershipPlan->price }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Duration :</th>
                                    <td class="text-muted">{{ $membershipPlan->duration_value }} {{ ucfirst($membershipPlan->duration_type) }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Status :</th>
                                    <td class="text-muted">
                                        @if ($membershipPlan->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Personal Training :</th>
                                    <td class="text-muted">
                                        @if ($membershipPlan->personal_training)
                                            <span class="badge bg-info">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Description :</th>
                                    <td class="text-muted">{{ $membershipPlan->description }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Features :</th>
                                    <td class="text-muted">
                                        @if($membershipPlan->features)
                                            <ul>
                                                @foreach($membershipPlan->features as $feature)
                                                    <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            N/A
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
@endsection
