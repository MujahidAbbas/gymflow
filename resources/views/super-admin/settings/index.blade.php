@extends('layouts.master')

@section('title') Platform Settings @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('title') Settings @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Platform Settings</h5>
            </div>
            <div class="card-body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#branding" role="tab">
                            <i class="ri-palette-line me-1 align-bottom"></i> Branding
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#features" role="tab">
                            <i class="ri-toggle-line me-1 align-bottom"></i> Features
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#defaults" role="tab">
                            <i class="ri-settings-3-line me-1 align-bottom"></i> Defaults
                        </a>
                    </li>
                </ul>

                <form action="{{ route('super-admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Tab Content -->
                    <div class="tab-content text-muted">
                        <!-- Branding Tab -->
                        <div class="tab-pane active" id="branding" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="platform_name" class="form-label">Platform Name</label>
                                        <input type="text" class="form-control @error('platform_name') is-invalid @enderror" 
                                               id="platform_name" name="platform_name" 
                                               value="{{ old('platform_name', $settings->get('branding', collect())->get('platform_name', 'FitHub')) }}">
                                        @error('platform_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">The name displayed across the platform</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platform_logo" class="form-label">Platform Logo (Dark Mode)</label>
                                        <input type="file" class="form-control @error('platform_logo') is-invalid @enderror" 
                                               id="platform_logo" name="platform_logo" accept="image/*">
                                        @error('platform_logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Recommended size: 200x50px</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platform_logo_light" class="form-label">Platform Logo (Light Mode)</label>
                                        <input type="file" class="form-control @error('platform_logo_light') is-invalid @enderror" 
                                               id="platform_logo_light" name="platform_logo_light" accept="image/*">
                                        @error('platform_logo_light')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Recommended size: 200x50px</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platform_favicon" class="form-label">Favicon</label>
                                        <input type="file" class="form-control @error('platform_favicon') is-invalid @enderror" 
                                               id="platform_favicon" name="platform_favicon" accept="image/*">
                                        @error('platform_favicon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Recommended size: 32x32px or 64x64px</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features Tab -->
                        <div class="tab-pane" id="features" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input" type="checkbox" id="enable_registration" 
                                                       name="enable_registration" value="1" 
                                                       {{ old('enable_registration', $settings->get('features', collect())->get('enable_registration', '1')) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_registration">
                                                    <strong>Enable Registration</strong>
                                                    <p class="text-muted mb-0 small">Allow new gym owners to register</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input" type="checkbox" id="enable_landing_page" 
                                                       name="enable_landing_page" value="1" 
                                                       {{ old('enable_landing_page', $settings->get('features', collect())->get('enable_landing_page', '1')) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_landing_page">
                                                    <strong>Enable Landing Page</strong>
                                                    <p class="text-muted mb-0 small">Show public marketing page</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input" type="checkbox" id="enable_email_verification" 
                                                       name="enable_email_verification" value="1" 
                                                       {{ old('enable_email_verification', $settings->get('features', collect())->get('enable_email_verification', '1')) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_email_verification">
                                                    <strong>Require Email Verification</strong>
                                                    <p class="text-muted mb-0 small">New owners must verify their email</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input" type="checkbox" id="enable_pricing_display" 
                                                       name="enable_pricing_display" value="1" 
                                                       {{ old('enable_pricing_display', $settings->get('features', collect())->get('enable_pricing_display', '1')) == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_pricing_display">
                                                    <strong>Display Pricing Publicly</strong>
                                                    <p class="text-muted mb-0 small">Show subscription tiers on landing page</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Defaults Tab -->
                        <div class="tab-pane" id="defaults" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="mb-3">Payment Defaults</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="default_payment_gateway" class="form-label">Default Payment Gateway</label>
                                        <select class="form-select @error('default_payment_gateway') is-invalid @enderror" 
                                                id="default_payment_gateway" name="default_payment_gateway">
                                            <option value="">Select Gateway</option>
                                            <option value="stripe" {{ old('default_payment_gateway', $settings->get('payment', collect())->get('default_payment_gateway')) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                            <option value="paypal" {{ old('default_payment_gateway', $settings->get('payment', collect())->get('default_payment_gateway')) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                            <option value="bank_transfer" {{ old('default_payment_gateway', $settings->get('payment', collect())->get('default_payment_gateway')) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="flutterwave" {{ old('default_payment_gateway', $settings->get('payment', collect())->get('default_payment_gateway')) == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
                                            <option value="paystack" {{ old('default_payment_gateway', $settings->get('payment', collect())->get('default_payment_gateway')) == 'paystack' ? 'selected' : '' }}>Paystack</option>
                                        </select>
                                        @error('default_payment_gateway')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">New tenants will use this gateway by default</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="default_currency" class="form-label">Default Currency</label>
                                        <input type="text" class="form-control @error('default_currency') is-invalid @enderror" 
                                               id="default_currency" name="default_currency" 
                                               value="{{ old('default_currency', $settings->get('payment', collect())->get('default_currency', 'USD')) }}" 
                                               maxlength="3" placeholder="USD">
                                        @error('default_currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">3-letter currency code (e.g., USD, GBP, EUR)</small>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <h6 class="mb-3">Email Defaults</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="default_mail_from_name" class="form-label">Default From Name</label>
                                        <input type="text" class="form-control @error('default_mail_from_name') is-invalid @enderror" 
                                               id="default_mail_from_name" name="default_mail_from_name" 
                                               value="{{ old('default_mail_from_name', $settings->get('email', collect())->get('default_mail_from_name', 'FitHub')) }}">
                                        @error('default_mail_from_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Used in system emails to customers</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="default_mail_from_address" class="form-label">Default From Address</label>
                                        <input type="email" class="form-control @error('default_mail_from_address') is-invalid @enderror" 
                                               id="default_mail_from_address" name="default_mail_from_address" 
                                               value="{{ old('default_mail_from_address', $settings->get('email', collect())->get('default_mail_from_address', 'noreply@fithub.com')) }}">
                                        @error('default_mail_from_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Used in system emails to customers</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line align-bottom me-1"></i> Save Settings
                        </button>
                        <a href="{{ route('super-admin.dashboard') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
