@extends('layouts.master')

@section('title')
    Payment Successful
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="text-center mt-5">
            <div class="mb-4">
                <i class="ri-checkbox-circle-line display-1 text-success"></i>
            </div>
            
            <h3 class="mb-3">Payment Successful!</h3>
            <p class="text-muted mb-4">
                Your subscription has been activated successfully. Thank you for your purchase!
            </p>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Subscription Details</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Plan:</td>
                                    <td><strong>{{ $subscription->plan->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        <span class="badge badge-soft-success">{{ ucfirst($subscription->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Start Date:</td>
                                    <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">End Date:</td>
                                    <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                                </tr>
                                @if($subscription->trial_end_date)
                                <tr>
                                    <td class="text-muted">Trial Ends:</td>
                                    <td class="text-success">{{ $subscription->trial_end_date->format('M d, Y') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-primary me-2">
                    <i class="ri-home-line me-1"></i> Go to Dashboard
                </a>
                <a href="{{ route('subscriptions.mine') }}" class="btn btn-soft-primary">
                    <i class="ri-file-list-line me-1"></i> View My Subscription
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
