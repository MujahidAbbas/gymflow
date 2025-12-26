@extends('layouts.master')

@section('title') {{ $customer->business_name }} @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('li_2') <a href="{{ route('super-admin.customers.index') }}">Customers</a> @endslot
@slot('title') {{ $customer->business_name }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <!-- Business Details -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">Business Details</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('super-admin.customers.edit', $customer) }}" class="btn btn-sm btn-soft-primary">
                            <i class="ri-pencil-line align-middle"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Business Name</p>
                        <h6>{{ $customer->business_name }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Subdomain</p>
                        <h6>{{ $customer->subdomain ?? 'Not set' }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Status</p>
                        <h6>
                            @if($customer->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($customer->status === 'suspended')
                                <span class="badge bg-danger">Suspended</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Subscription Type</p>
                        <h6>
                            @if($customer->isOnTrial())
                                <span class="badge bg-info">Trial ({{ $customer->trial_ends_at->diffForHumans() }})</span>
                            @else
                                <span class="badge bg-success">Paid</span>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Owner Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Name</p>
                        <h6>{{ $customer->owner->name }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Email</p>
                        <h6>{{ $customer->owner->email }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Account Created</p>
                        <h6>{{ $customer->created_at->format('M d, Y') }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Last Updated</p>
                        <h6>{{ $customer->updated_at->format('M d, Y') }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Usage Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Members</p>
                        <h6>
                            {{ $memberCount }} / {{ $customer->max_members }}
                            @if($memberCount >= $customer->max_members)
                                <span class="badge bg-warning">Limit Reached</span>
                            @endif
                        </h6>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ ($memberCount / $customer->max_members) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Trainers</p>
                        <h6>
                            {{ $trainerCount }} / {{ $customer->max_trainers }}
                            @if($trainerCount >= $customer->max_trainers)
                                <span class="badge bg-warning">Limit Reached</span>
                            @endif
                        </h6>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ ($trainerCount / $customer->max_trainers) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <!-- Impersonate -->
                    <form action="{{ route('super-admin.customers.impersonate', $customer) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-soft-info w-100">
                            <i class="ri-user-shared-line align-middle"></i> Impersonate Owner
                        </button>
                    </form>

                    <!-- Suspend -->
                    @if($customer->status !== 'suspended')
                    <form action="{{ route('super-admin.customers.suspend', $customer) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-soft-warning w-100">
                            <i class="ri-lock-line align-middle"></i> Suspend Account
                        </button>
                    </form>
                    @endif

                    <!-- Delete -->
                    <form action="{{ route('super-admin.customers.destroy', $customer) }}" method="POST" 
                          onsubmit="return confirm('Are you sure? This will delete all customer data!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger w-100">
                            <i class="ri-delete-bin-line align-middle"></i> Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Plan Limits -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Plan Limits</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between mb-2">
                        <span>Max Members:</span>
                        <strong>{{ $customer->max_members }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Max Trainers:</span>
                        <strong>{{ $customer->max_trainers }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
