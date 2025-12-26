@extends('layouts.master')

@section('title')
    Payment Cancelled
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="text-center mt-5">
            <div class="mb-4">
                <i class="ri-close-circle-line display-1 text-warning"></i>
            </div>
            
            <h3 class="mb-3">Payment Cancelled</h3>
            <p class="text-muted mb-4">
                Your payment was cancelled. No charges were made to your account.
            </p>

            @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line align-middle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">What happened?</h5>
                    <p class="text-muted mb-0">
                        You cancelled the payment process before it could be completed. 
                        If this was a mistake, you can try again by selecting a subscription plan.
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary me-2">
                    <i class="ri-restart-line me-1"></i> Try Again
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-soft-secondary">
                    <i class="ri-home-line me-1"></i> Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
