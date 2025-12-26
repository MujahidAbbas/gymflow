@extends('layouts.master')

@section('title')
    Create Workout
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('workouts.index') }}">Workouts</a>
@endslot
@slot('title')
    Create New Workout
@endslot
@endcomponent

<form action="{{ route('workouts.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Workout Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Workout Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workout_date" class="form-label">Workout Date</label>
                                <input type="date" class="form-control" id="workout_date" name="workout_date" 
                                       value="{{ old('workout_date') }}">
                                <small class="text-muted">Leave empty to create a template</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="member_id" class="form-label">Assign to Member</label>
                                <select class="form-select" id="member_id" name="member_id">
                                    <option value="">No specific member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->member_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainer_id" class="form-label">Created by Trainer</label>
                                <select class="form-select" id="trainer_id" name="trainer_id">
                                    <option value="">No trainer</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                            {{ $trainer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
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

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Workout Activities</h4>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addActivity()">
                        <i class="ri-add-line"></i> Add Exercise
                    </button>
                </div>
                <div class="card-body">
                    <div id="activities-container">
                        <!-- Activities will be added here dynamically -->
                    </div>
                    <p class="text-muted" id="no-activities-msg">Click "Add Exercise" to add activities to this workout</p>
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('workouts.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Create Workout
                </button>
            </div>
        </div>
    </div>
</form>

@section('script')
<script>
let activityIndex = 0;

function addActivity() {
    const container = document.getElementById('activities-container');
    const noActivitiesMsg = document.getElementById('no-activities-msg');
    
    if (noActivitiesMsg) {
        noActivitiesMsg.style.display = 'none';
    }
    
    const activityHtml = `
        <div class="activity-item border rounded p-3 mb-3" data-index="${activityIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Exercise ${activityIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeActivity(${activityIndex})">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Exercise Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="activities[${activityIndex}][exercise_name]" 
                               placeholder="e.g., Bench Press, Squats, Running" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="activities[${activityIndex}][description]" 
                                  rows="1" placeholder="Exercise instructions or notes"></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Sets</label>
                        <input type="number" class="form-control" name="activities[${activityIndex}][sets]" 
                               placeholder="3" min="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Reps</label>
                        <input type="number" class="form-control" name="activities[${activityIndex}][reps]" 
                               placeholder="10" min="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Duration (min)</label>
                        <input type="number" class="form-control" name="activities[${activityIndex}][duration_minutes]" 
                               placeholder="30" min="1">
                        <small class="text-muted">For cardio</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" class="form-control" name="activities[${activityIndex}][weight_kg]" 
                               placeholder="50" step="0.5" min="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Rest Time (seconds)</label>
                        <input type="number" class="form-control" name="activities[${activityIndex}][rest_seconds]" 
                               value="60" min="0">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', activityHtml);
    activityIndex++;
}

function removeActivity(index) {
    const activityItem = document.querySelector(`[data-index="${index}"]`);
    if (activityItem) {
        activityItem.remove();
    }
    
    // Show no activities message if all removed
    const container = document.getElementById('activities-container');
    const noActivitiesMsg = document.getElementById('no-activities-msg');
    if (container.children.length === 0 && noActivitiesMsg) {
        noActivitiesMsg.style.display = 'block';
    }
}

// Add one activity by default
document.addEventListener('DOMContentLoaded', function() {
    addActivity();
});
</script>
@endsection
@endsection
