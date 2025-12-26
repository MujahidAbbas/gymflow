@extends('layouts.master')

@section('title')
    My Subscription
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Subscription
@endslot
@slot('title')
    My Subscription
@endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-lg-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line align-middle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line align-middle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($subscription)
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Current Subscription</h4>
                    <div class="flex-shrink-0">
                        <span class="badge 
                            @if($subscription->status == 'active') bg-success
                            @elseif($subscription->status == 'trial') bg-primary
                            @elseif($subscription->status == 'cancelled') bg-danger
                            @else badge-soft-warning
                            @endif fs-12">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="fs-16 mb-1">{{ $subscription->plan->name }}</h5>
                        <p class="text-muted">{{ $subscription->plan->description }}</p>
                        <h2 class="fs-32 fw-bold text-primary">{{ $subscription->plan->formatted_price }}</h2>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="p-3 border border-dashed rounded">
                                <h6 class="mb-2 text-muted">Start Date</h6>
                                <h5 class="fs-14 mb-0">{{ $subscription->start_date->format('M d, Y') }}</h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border border-dashed rounded">
                                <h6 class="mb-2 text-muted">End Date</h6>
                                <h5 class="fs-14 mb-0">{{ $subscription->end_date->format('M d, Y') }}</h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border border-dashed rounded">
                                <h6 class="mb-2 text-muted">Days Remaining</h6>
                                <h5 class="fs-14 mb-0">{{ $subscription->days_remaining }} days</h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border border-dashed rounded">
                                <h6 class="mb-2 text-muted">Payment Method</h6>
                                <h5 class="fs-14 mb-0 text-uppercase">{{ $subscription->payment_gateway }}</h5>
                            </div>
                        </div>
                    </div>

                    @if($subscription->isActive() || $subscription->isOnTrial())
                        <div class="alert alert-warning" role="alert">
                            <strong>Note:</strong> Cancelling your subscription will disable auto-renewal. You will still have access until the end of the current billing period.
                        </div>
                        <div class="text-end">
                            <form action="{{ route('subscriptions.cancel.post', $subscription->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your subscription?');">
                                @csrf
                                <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transaction History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscription->transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                        <td>{{ $transaction->transaction_id }}</td>
                                        <td>{{ $transaction->formatted_amount }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($transaction->status == 'completed') badge-soft-success
                                                @elseif($transaction->status == 'failed') badge-soft-danger
                                                @else badge-soft-warning
                                                @endif">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="ri-vip-crown-line display-4 text-muted"></i>
                </div>
                <h3>No Active Subscription</h3>
                <p class="text-muted mb-4">You don't have an active subscription plan. Choose a plan to get started.</p>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">View Plans</a>
            </div>
        @endif
    </div>
</div>
@endsection
