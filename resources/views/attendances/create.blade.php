@extends('layouts.master')

@section('title')
    Check In Member
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('attendances.index') }}">Attendance</a>
@endslot
@slot('title')
    Check In Member
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Member Check-In</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="member_id" class="form-label">Select Member <span class="text-danger">*</span></label>
                        <select class="form-select @error('member_id') is-invalid @enderror" 
                                id="member_id" name="member_id" required autofocus>
                            <option value="">Choose member...</option>
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

                    <div class="mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" 
                               value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="check_in_time" class="form-label">Check-In Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="check_in_time" name="check_in_time" 
                               value="{{ old('check_in_time', date('H:i')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        <small class="text-muted">Optional notes about this check-in</small>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-login-circle-line me-1"></i> Check In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
