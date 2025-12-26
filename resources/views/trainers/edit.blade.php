@extends('layouts.master')

@section('title')
    Edit Trainer
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('trainers.index') }}">Trainers</a>
@endslot
@slot('title')
    Edit Trainer: {{ $trainer->name }}
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
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
                                <strong>Trainer ID:</strong> {{ $trainer->trainer_id }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $trainer->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $trainer->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" 
                                       id="phone" name="phone" value="{{ old('phone', $trainer->phone) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" 
                                       id="date_of_birth" name="date_of_birth" 
                                       value="{{ old('date_of_birth', $trainer->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $trainer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $trainer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $trainer->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                @if($trainer->photo)
                                    <small class="text-muted">Current photo: {{ basename($trainer->photo) }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $trainer->address) }}</textarea>
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
                                <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', $trainer->bio) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="specializations" class="form-label">Specializations</label>
                                <select class="form-select" id="specializations" name="specializations[]" multiple>
                                    @php
                                        $selected = old('specializations', $trainer->specializations ?? []);
                                        $options = ['Yoga', 'Cardio', 'Strength Training', 'CrossFit', 'Pilates', 'HIIT', 'Boxing', 'Spinning', 'Nutrition'];
                                    @endphp
                                    @foreach($options as $option)
                                        <option value="{{ $option }}" {{ in_array($option, $selected) ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
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
                                <small class="text-muted">Type and press Enter to add certifications</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="years_of_experience" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" 
                                       id="years_of_experience" name="years_of_experience" 
                                       value="{{ old('years_of_experience', $trainer->years_of_experience) }}" min="0" max="50" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
                                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" 
                                       value="{{ old('hourly_rate', $trainer->hourly_rate) }}" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ old('status', $trainer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $trainer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $trainer->notes) }}</textarea>
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
                    <i class="ri-save-line me-1"></i> Update Trainer
                </button>
            </div>
        </form>
    </div>
</div>

@section('script')
<script>
// Initialize certifications from existing data
let certifications = @json(old('certifications', $trainer->certifications ?? []));
updateCertificationsTags();

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
