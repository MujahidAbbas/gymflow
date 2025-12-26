@extends('layouts.master')

@section('title')
    Edit Membership Plan
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Membership Plans
        @endslot
        @slot('title')
            Edit Plan
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Membership Plan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('membership-plans.update', $membershipPlan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Plan Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $membershipPlan->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $membershipPlan->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="duration_type" class="form-label">Duration Type</label>
                                <select class="form-select @error('duration_type') is-invalid @enderror" id="duration_type"
                                    name="duration_type" required>
                                    <option value="daily" {{ $membershipPlan->duration_type == 'daily' ? 'selected' : '' }}>Daily
                                    </option>
                                    <option value="weekly" {{ $membershipPlan->duration_type == 'weekly' ? 'selected' : '' }}>
                                        Weekly</option>
                                    <option value="monthly" {{ $membershipPlan->duration_type == 'monthly' ? 'selected' : '' }}>
                                        Monthly</option>
                                    <option value="quarterly"
                                        {{ $membershipPlan->duration_type == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="half_yearly"
                                        {{ $membershipPlan->duration_type == 'half_yearly' ? 'selected' : '' }}>Half Yearly
                                    </option>
                                    <option value="yearly" {{ $membershipPlan->duration_type == 'yearly' ? 'selected' : '' }}>
                                        Yearly</option>
                                    <option value="lifetime" {{ $membershipPlan->duration_type == 'lifetime' ? 'selected' : '' }}>
                                        Lifetime</option>
                                </select>
                                @error('duration_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="duration_value" class="form-label">Duration Value</label>
                                <input type="number" class="form-control @error('duration_value') is-invalid @enderror"
                                    id="duration_value" name="duration_value"
                                    value="{{ old('duration_value', $membershipPlan->duration_value) }}" required>
                                @error('duration_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $membershipPlan->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="personal_training"
                                        name="personal_training" value="1"
                                        {{ old('personal_training', $membershipPlan->personal_training) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="personal_training">Includes Personal Training</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                        name="is_active" value="1"
                                        {{ old('is_active', $membershipPlan->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('membership-plans.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
