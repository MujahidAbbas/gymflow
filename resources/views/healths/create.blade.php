@extends('layouts.master')

@section('title')
    Record Health Measurement
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('healths.index') }}">Health</a>
@endslot
@slot('title')
    Record New Measurement
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('healths.store') }}" method="POST">
            @csrf
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Measurement Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                                <select class="form-select @error('member_id') is-invalid @enderror" 
                                        id="member_id" name="member_id" required>
                                    <option value="">Select Member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->member_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="measurement_date" class="form-label">Measurement Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="measurement_date" name="measurement_date" 
                                       value="{{ old('measurement_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Body Measurements</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Enter the measurements you want to track. You don't need to fill all fields.</p>
                    
                    <div class="row">
                        @foreach($measurementTypes as $key => $label)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="measurement_{{ $key }}" class="form-label">{{ $label }}</label>
                                    <input type="number" class="form-control" 
                                           id="measurement_{{ $key }}" 
                                           name="measurements[{{ $key }}]" 
                                           value="{{ old('measurements.' . $key) }}"
                                           step="0.1" min="0">
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                <small class="text-muted">Additional observations or comments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('healths.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Record Measurement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
