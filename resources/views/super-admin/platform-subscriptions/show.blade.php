@extends('layouts.master')

@section('title') {{ $platformSubscription->name }} @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('super-admin.platform-subscriptions.index') }}">Subscription Tiers</a> @endslot
@slot('title') {{ $platformSubscription->name }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">{{ $platformSubscription->name }}</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('super-admin.platform-subscriptions.edit', $platformSubscription) }}" class="btn btn-sm btn-primary">
                            <i class="ri-pencil-line align-bottom me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" scope="row">Price:</th>
                                <td class="text-muted">
                                    <strong class="fs-5">{{ $platformSubscription->formatted_price }}</strong>{{ $platformSubscription->interval_label }}
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Billing Interval:</th>
                                <td class="text-muted"><span class="badge bg-info">{{ ucfirst($platformSubscription->interval) }}</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Trial Period:</th>
                                <td class="text-muted">
                                    @if($platformSubscription->trial_days > 0)
                                        <span class="text-success">{{ $platformSubscription->trial_days }} days</span>
                                    @else
                                        No trial
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Description:</th>
                                <td class="text-muted">{{ $platformSubscription->description ?? 'No description' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Status:</th>
                                <td class="text-muted">
                                    @if($platformSubscription->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                    
                                    @if($platformSubscription->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Limits Per Customer</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" scope="row">Max Members:</th>
                                <td class="text-muted">
                                    {{ $platformSubscription->hasUnlimitedMembers() ? 'Unlimited' : number_format($platformSubscription->max_members_per_tenant) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Max Trainers:</th>
                                <td class="text-muted">
                                    {{ $platformSubscription->hasUnlimitedTrainers() ? 'Unlimited' : number_format($platformSubscription->max_trainers_per_tenant) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Max Staff:</th>
                                <td class="text-muted">
                                    {{ $platformSubscription->max_staff_per_tenant ? number_format($platformSubscription->max_staff_per_tenant) : 'Unlimited' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3 class="mb-1">{{ $platformSubscription->tenants_count }}</h3>
                    <p class="text-muted mb-0">Total Customers</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if($tenants->isNotEmpty())
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Customers on this Tier</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Business Name</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenants as $tenant)
                            <tr>
                                <td>
                                    <strong>{{ $tenant->business_name }}</strong>
                                    @if($tenant->subdomain)
                                        <br><small class="text-muted">{{ $tenant->subdomain }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $tenant->owner->name }}<br>
                                    <small class="text-muted">{{ $tenant->owner->email }}</small>
                                </td>
                                <td>
                                    @if($tenant->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($tenant->status === 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('super-admin.customers.show', $tenant) }}" class="btn btn-sm btn-soft-primary">
                                        <i class="ri-eye-line align-bottom"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
