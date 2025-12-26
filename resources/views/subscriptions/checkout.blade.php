@extends('layouts.master')

@section('title')
    Checkout
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('subscriptions.index') }}">Subscriptions</a>
@endslot
@slot('title')
    Checkout
@endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-lg-8">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line align-middle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold">{{ $plan->name }}</h6>
                            <p class="text-muted">{{ $plan->description }}</p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td>Plan Price:</td>
                                        <td class="text-end">${{ number_format($plan->price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Duration:</td>
                                        <td class="text-end">{{ $plan->duration_days }} days</td>
                                    </tr>
                                    @if($plan->trial_days > 0)
                                    <tr>
                                        <td>Trial Period:</td>
                                        <td class="text-end text-success">{{ $plan->trial_days }} days FREE</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top pt-2">
                                        <th>Total:</th>
                                        <th class="text-end">${{ number_format($plan->price, 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Select Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('subscriptions.purchase', $plan->id) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Member Information</label>
                                <div class="border p-3 rounded">
                                    <p class="mb-1"><strong>{{ $member->first_name }} {{ $member->last_name }}</strong></p>
                                    <p class="mb-1 text-muted">{{ $member->email }}</p>
                                    <p class="mb-0 text-muted">{{ $member->phone }}</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Choose Payment Gateway</label>
                                @foreach($gateways as $key => $gateway)
                                <div class="form-check card-radio mb-2">
                                    <input class="form-check-input" type="radio" name="payment_gateway" 
                                           id="gateway_{{ $key }}" value="{{ $key }}" 
                                           {{ $loop->first ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="gateway_{{ $key }}">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="{{ $gateway['icon'] }} fs-20"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">{{ $gateway['name'] }}</h6>
                                                <p class="text-muted mb-0">{{ $gateway['description'] }}</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <div class="alert alert-info">
                                <i class="ri-information-line align-middle me-2"></i>
                                You will be redirected to the secure payment page.
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="ri-secure-payment-line me-1"></i> Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
