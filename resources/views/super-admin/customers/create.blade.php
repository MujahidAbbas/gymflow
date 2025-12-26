@extends('layouts.master')

@section('title') Create Customer @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('li_2') <a href="{{ route('super-admin.customers.index') }}">Customers</a> @endslot
@slot('title') Create Customer @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Create New Customer</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('super-admin.customers.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold mb-3">Business Information</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                   id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subdomain" class="form-label">Subdomain</label>
                            <input type="text" class="form-control @error('subdomain') is-invalid @enderror" 
                                   id="subdomain" name="subdomain" value="{{ old('subdomain') }}" 
                                   placeholder="e.g., testgym">
                            <small class="text-muted">Optional - will be used for custom domain access</small>
                            @error('subdomain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12"><hr></div>

                        <div class="col-md-12">
                            <h6 class="font-weight-bold mb-3">Owner Account Details</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="owner_name" class="form-label">Owner Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                   id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                            @error('owner_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="owner_email" class="form-label">Owner Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('owner_email') is-invalid @enderror" 
                                   id="owner_email" name="owner_email" value="{{ old('owner_email') }}" required>
                            @error('owner_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="owner_password" class="form-label">Owner Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('owner_password') is-invalid @enderror" 
                                   id="owner_password" name="owner_password" required>
                            <small class="text-muted">Minimum 8 characters</small>
                            @error('owner_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12"><hr></div>

                        <div class="col-md-12">
                            <h6 class="font-weight-bold mb-3">Plan Limits</h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="max_members" class="form-label">Max Members <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_members') is-invalid @enderror" 
                                   id="max_members" name="max_members" value="{{ old('max_members', 100) }}" required min="1">
                            @error('max_members')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="max_trainers" class="form-label">Max Trainers <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_trainers') is-invalid @enderror" 
                                   id="max_trainers" name="max_trainers" value="{{ old('max_trainers', 10) }}" required min="1">
                            @error('max_trainers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="trial_days" class="form-label">Trial Days</label>
                            <input type="number" class="form-control @error('trial_days') is-invalid @enderror" 
                                   id="trial_days" name="trial_days" value="{{ old('trial_days', 14) }}" min="0" max="90">
                            <small class="text-muted">0-90 days</small>
                            @error('trial_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Customer</button>
                        <a href="{{ route('super-admin.customers.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="ri-information-line text-primary"></i> Owner will receive login credentials via email</li>
                    <li class="mb-2"><i class="ri-shield-check-line text-success"></i> Trial period starts immediately upon creation</li>
                    <li class="mb-2"><i class="ri-settings-3-line text-info"></i> You can adjust limits later if needed</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
