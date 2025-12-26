@extends('layouts.master')

@section('title')
    Add Locker
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('lockers.index') }}">Lockers</a>
@endslot
@slot('title')
    Add New Locker
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Locker Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('lockers.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" placeholder="e.g., Ground Floor - East Wing">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="monthly_fee" class="form-label">Monthly Fee ($) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('monthly_fee') is-invalid @enderror" 
                                       id="monthly_fee" name="monthly_fee" value="{{ old('monthly_fee', '20.00') }}" 
                                       step="0.01" min="0" required>
                                @error('monthly_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('lockers.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i> Add Locker
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
