@extends('layouts.master')

@section('title') Super Admin Dashboard @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('title') Super Admin Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col">
        <div class="h-100">
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- Total Customers -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total Customers</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                        <i class="ri-building-line text-primary"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ number_format($totalCustomers) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Active Subscriptions -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Active Subscriptions</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-soft-success rounded fs-3">
                                        <i class="ri-bank-card-line text-success"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ number_format($activeSubscriptions) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Total Revenue -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total Revenue</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded fs-3">
                                        <i class="ri-money-dollar-circle-line text-info"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">${{ number_format($totalRevenue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Total Users -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total End Users</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning rounded fs-3">
                                        <i class="ri-group-line text-warning"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-0">{{ number_format($totalUsers) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Recent Customers</h4>
                            <div class="flex-shrink-0">
                                <a href="{{ route('super-admin.customers.index') }}" class="btn btn-soft-primary btn-sm">
                                    View All <i class="ri-arrow-right-line align-middle"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th scope="col">Business Name</th>
                                            <th scope="col">Owner</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Joined</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentCustomers as $customer)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">{{ $customer->business_name }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $customer->owner->name }}</td>
                                            <td>
                                                @if($customer->status === 'active')
                                                    <span class="badge badge-soft-success">Active</span>
                                                @elseif($customer->status === 'suspended')
                                                    <span class="badge badge-soft-danger">Suspended</span>
                                                @else
                                                    <span class="badge badge-soft-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('super-admin.customers.show', $customer) }}" class="btn btn-sm btn-soft-info">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No recent customers</td>
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
</div>
@endsection
