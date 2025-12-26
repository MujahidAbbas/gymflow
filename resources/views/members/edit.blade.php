@extends('layouts.master')

@section('title')
    Edit Member
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('members.index') }}">Members</a>
@endslot
@slot('title')
    Edit Member: {{ $member->name }}
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">
                                <strong>Member ID:</strong> {{ $member->member_id }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $member->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $member->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $member->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" 
                                       value="{{ old('date_of_birth', $member->date_of_birth?->format('Y-m-d')) }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                @if($member->photo)
                                    <small class="text-muted">Current photo: {{ basename($member->photo) }}</small>
                                @endif
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $member->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Emergency Contact</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" 
                                       id="emergency_contact_name" name="emergency_contact_name" 
                                       value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" 
                                       id="emergency_contact_phone" name="emergency_contact_phone" 
                                       value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="medical_conditions" class="form-label">Medical Conditions</label>
                                <textarea class="form-control" id="medical_conditions" 
                                          name="medical_conditions" rows="2">{{ old('medical_conditions', $member->medical_conditions) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Membership Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="membership_plan_id" class="form-label">Membership Plan <span class="text-danger">*</span></label>
                                <select class="form-select @error('membership_plan_id') is-invalid @enderror" 
                                        id="membership_plan_id" name="membership_plan_id" required>
                                    <option value="">Select Plan</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" 
                                            {{ old('membership_plan_id', $member->membership_plan_id) == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - ${{ $plan->price }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('membership_plan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="membership_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('membership_start_date') is-invalid @enderror" 
                                       id="membership_start_date" name="membership_start_date" 
                                       value="{{ old('membership_start_date', $member->membership_start_date?->format('Y-m-d')) }}" required>
                                @error('membership_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" 
                                       value="{{ $member->membership_end_date?->format('M d, Y') ?? 'Lifetime' }}" readonly>
                                <small class="text-muted">Auto-calculated based on plan</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="expired" {{ old('status', $member->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="suspended" {{ old('status', $member->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $member->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Update Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
