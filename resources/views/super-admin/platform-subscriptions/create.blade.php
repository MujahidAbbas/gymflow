@extends('layouts.master')

@section('title') Create Platform Subscription Tier @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('super-admin.platform-subscriptions.index') }}">Subscription Tiers</a> @endslot
@slot('title') Create Tier @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('super-admin.platform-subscriptions.store') }}" method="POST">
            @csrf
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tier Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="interval" class="form-label">Billing Interval <span class="text-danger">*</span></label>
                                <select class="form-select @error('interval') is-invalid @enderror" id="interval" name="interval" required>
                                    <option value="monthly" {{ old('interval') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('interval') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ old('interval') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    <option value="lifetime" {{ old('interval') == 'lifetime' ? 'selected' : '' }}>Lifetime</option>
                                </select>
                                @error('interval')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trial_days" class="form-label">Trial Days</label>
                                <input type="number" class="form-control @error('trial_days') is-invalid @enderror" id="trial_days" name="trial_days" value="{{ old('trial_days', 0) }}" min="0">
                                <small class="text-muted">0 = no trial period</small>
                                @error('trial_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Limits Per Customer</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_members_per_tenant" class="form-label">Max Members</label>
                                <input type="number" class="form-control @error('max_members_per_tenant') is-invalid @enderror" id="max_members_per_tenant" name="max_members_per_tenant" value="{{ old('max_members_per_tenant') }}" min="0">
                                <small class="text-muted">0 or empty = unlimited</small>
                                @error('max_members_per_tenant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_trainers_per_tenant" class="form-label">Max Trainers</label>
                                <input type="number" class="form-control @error('max_trainers_per_tenant') is-invalid @enderror" id="max_trainers_per_tenant" name="max_trainers_per_tenant" value="{{ old('max_trainers_per_tenant') }}" min="0">
                                <small class="text-muted">0 or empty = unlimited</small>
                                @error('max_trainers_per_tenant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_staff_per_tenant" class="form-label">Max Staff</label>
                                <input type="number" class="form-control @error('max_staff_per_tenant') is-invalid @enderror" id="max_staff_per_tenant" name="max_staff_per_tenant" value="{{ old('max_staff_per_tenant') }}" min="0">
                                <small class="text-muted">0 or empty = unlimited</small>
                                @error('max_staff_per_tenant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check form-switch mb-3 mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check form-switch mb-3 mt-4">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured (Most Popular)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-end mb-3">
                <a href="{{ route('super-admin.platform-subscriptions.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Tier</button>
            </div>
        </form>
    </div>
</div>
@endsection
