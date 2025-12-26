@extends('layouts.master')

@section('title') Edit Customer @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') Platform @endslot
@slot('li_2') <a href="{{ route('super-admin.customers.index') }}">Customers</a> @endslot
@slot('title') Edit Customer @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Customer: {{ $customer->business_name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('super-admin.customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold mb-3">Business Information</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                   id="business_name" name="business_name" value="{{ old('business_name', $customer->business_name) }}" required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subdomain" class="form-label">Subdomain</label>
                            <input type="text" class="form-control @error('subdomain') is-invalid @enderror" 
                                   id="subdomain" name="subdomain" value="{{ old('subdomain', $customer->subdomain) }}">
                            @error('subdomain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
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
                                   id="max_members" name="max_members" value="{{ old('max_members', $customer->max_members) }}" required min="1">
                            @error('max_members')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="max_trainers" class="form-label">Max Trainers <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_trainers') is-invalid @enderror" 
                                   id="max_trainers" name="max_trainers" value="{{ old('max_trainers', $customer->max_trainers) }}" required min="1">
                            @error('max_trainers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="trial_days" class="form-label">Extend Trial (Days)</label>
                            <input type="number" class="form-control @error('trial_days') is-invalid @enderror" 
                                   id="trial_days" name="trial_days" value="{{ old('trial_days') }}" min="0" max="90">
                            <small class="text-muted">Leave empty to keep current trial period</small>
                            @error('trial_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Customer</button>
                        <a href="{{ route('super-admin.customers.show', $customer) }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Owner Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $customer->owner->name }}</p>
                <p><strong>Email:</strong> {{ $customer->owner->email }}</p>
                <p><strong>Created:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                @if($customer->trial_ends_at)
                <p><strong>Trial Ends:</strong> {{ $customer->trial_ends_at->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
