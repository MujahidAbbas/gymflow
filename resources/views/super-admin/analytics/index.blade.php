@extends('layouts.master')

@section('title') Platform Analytics @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('title') Analytics @endslot
@endcomponent

<div class="row">
    <!-- Revenue Metrics -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Monthly Recurring Revenue</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">${{ number_format($metrics['mrr'], 2) }}</h4>
                        <span class="badge bg-success-subtle text-success mb-0">
                            <i class="ri-arrow-up-line align-middle"></i> MRR
                        </span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="bx bx-dollar-circle text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Customers</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($metrics['total_customers']) }}</h4>
                        <span class="badge bg-info-subtle text-info mb-0">
                            <i class="ri-user-line align-middle"></i> {{ $metrics['active_customers'] }} Active
                        </span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded fs-3">
                            <i class="bx bx-user-circle text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Rate -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Growth Rate</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ number_format($metrics['customer_growth_rate'], 1) }}%</h4>
                        <span class="badge {{ $metrics['customer_growth_rate'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} mb-0">
                            <i class="ri-arrow-{{ $metrics['customer_growth_rate'] >= 0 ? 'up' : 'down' }}-line align-middle"></i> This Month
                        </span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i class="bx bx-trending-up text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trial Customers -->
    <div class="col-lg-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">On Trial</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $metrics['customers_on_trial'] }}</h4>
                        <span class="badge bg-primary-subtle text-primary mb-0">
                            <i class="ri-time-line align-middle"></i> Active Trials
                        </span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="bx bx-timer text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('super-admin.customers.index') }}" class="btn btn-soft-primary w-100">
                            <i class="ri-user-line me-1"></i> View All Customers
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('super-admin.platform-subscriptions.index') }}" class="btn btn-soft-success w-100">
                            <i class="ri-price-tag-3-line me-1"></i> Manage Pricing Tiers
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('super-admin.settings.index') }}" class="btn btn-soft-warning w-100">
                            <i class="ri-settings-3-line me-1"></i> Platform Settings
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('super-admin.customers.create') }}" class="btn btn-soft-info w-100">
                            <i class="ri-add-line me-1"></i> Add New Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Platform Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Total Customers</th>
                                <td>{{ $metrics['total_customers'] }}</td>
                                <th scope="row">Active Customers</th>
                                <td>{{ $metrics['active_customers'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Monthly Recurring Revenue (MRR)</th>
                                <td>${{ number_format($metrics['mrr'], 2) }}</td>
                                <th scope="row">Annual Recurring Revenue (ARR)</th>
                                <td>${{ number_format($metrics['mrr'] * 12, 2) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Customers on Trial</th>
                                <td>{{ $metrics['customers_on_trial'] }}</td>
                                <th scope="row">Growth Rate (Monthly)</th>
                                <td>{{ number_format($metrics['customer_growth_rate'], 1) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
