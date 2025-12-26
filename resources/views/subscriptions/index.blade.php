@extends('layouts.master')

@section('title')
    Subscription Plans
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Subscription
@endslot
@slot('title')
    Choose Your Plan
@endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="text-center mb-5">
            <h3 class="mb-3">Choose the plan that's right for you</h3>
            <p class="text-muted">Select a subscription plan and get started with your fitness journey</p>
        </div>

        <div class="row g-4">
            @foreach($plans as $plan)
            <div class="col-lg-4">
                <div class="card pricing-box {{ $plan->is_featured ? 'border-primary' : '' }}">
                    @if($plan->is_featured)
                    <div class="ribbon-two ribbon-two-primary"><span>Popular</span></div>
                    @endif
                    
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h5 class="mb-1">{{ $plan->name }}</h5>
                            <p class="text-muted">{{ $plan->description }}</p>
                        </div>

                        <div class="py-4 text-center">
                            <h1 class="month"><sup><small>$</small></sup><span class="ff-secondary fw-bold">{{ number_format($plan->price, 0) }}</span> <span class="fs-13 text-muted">/{{ $plan->duration_days }} days</span></h1>
                        </div>

                        <div class="mb-4">
                            <h6 class="fs-15 fw-semibold text-uppercase mb-3">Features:</h6>
                            <ul class="list-unstyled vstack gap-3">
                                @if($plan->features)
                                    @foreach($plan->features as $feature)
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-2">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $feature }}
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                @endif
                                
                                @if($plan->max_members)
                                <li>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 text-success me-2">
                                            <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Up to {{ $plan->max_members }} members
                                        </div>
                                    </div>
                                </li>
                                @endif

                                @if($plan->trial_days > 0)
                                <li>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 text-primary me-2">
                                            <i class="ri-gift-line fs-15 align-middle"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>{{ $plan->trial_days }} days FREE trial</strong>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('subscriptions.checkout', $plan->id) }}" class="btn {{ $plan->is_featured ? 'btn-primary' : 'btn-soft-primary' }} w-100">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 text-center">
            <p class="text-muted mb-2">
                <i class="ri-secure-payment-line align-middle me-1"></i>
                Secure payment processing with Stripe and PayPal
            </p>
        </div>
    </div>
</div>
@endsection
