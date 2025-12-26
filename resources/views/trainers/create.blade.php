@extends('layouts.master')

@section('title')
    Create Trainer
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('trainers.index') }}">Trainers</a>
@endslot
@slot('title')
    Create New Trainer
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('trainers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
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
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
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
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address') }}</textarea>
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
                    <h4 class="card-title mb-0">Professional Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio/Description</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
                                <small class="text-muted">Brief introduction and background</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="specializations" class="form-label">Specializations</label>
                                <select class="form-select" id="specializations" name="specializations[]" multiple>
                                    <option value="Yoga">Yoga</option>
                                    <option value="Cardio">Cardio</option>
                                    <option value="Strength Training">Strength Training</option>
                                    <option value="CrossFit">CrossFit</option>
                                    <option value="Pilates">Pilates</option>
                                    <option value="HIIT">HIIT</option>
                                    <option value="Boxing">Boxing</option>
                                    <option value="Spinning">Spinning</option>
                                    <option value="Nutrition">Nutrition</option>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="certifications" class="form-label">Certifications</label>
                                <input type="text" class="form-control" id="certifications_input" 
                                       placeholder="Enter certification and press Enter">
                                <div id="certifications_tags" class="mt-2"></div>
                                <input type="hidden" name="certifications[]" id="certifications_hidden">
                                <small class="text-muted">Type and press Enter to add certifications</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="years_of_experience" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror" 
                                       id="years_of_experience" name="years_of_experience" 
                                       value="{{ old('years_of_experience', 0) }}" min="0" max="50" required>
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
                                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" 
                                       value="{{ old('hourly_rate') }}" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('trainers.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Create Trainer
                </button>
            </div>
        </form>
    </div>
</div>

@section('script')
<script>
// Certifications tag system
let certifications = [];

document.getElementById('certifications_input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value && !certifications.includes(value)) {
            certifications.push(value);
            updateCertificationsTags();
            this.value = '';
        }
    }
});

function updateCertificationsTags() {
    const container = document.getElementById('certifications_tags');
    container.innerHTML = certifications.map((cert, index) => `
        <span class="badge badge-soft-primary me-1 mb-1">
            ${cert}
            <i class="ri-close-line ms-1" style="cursor: pointer;" onclick="removeCertification(${index})"></i>
        </span>
    `).join('');
    
    // Update hidden inputs
    const form = document.querySelector('form');
    form.querySelectorAll('input[name="certifications[]"]').forEach(input => input.remove());
    certifications.forEach(cert => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'certifications[]';
        input.value = cert;
        form.appendChild(input);
    });
}

function removeCertification(index) {
    certifications.splice(index, 1);
    updateCertificationsTags();
}
</script>
@endsection
@endsection
