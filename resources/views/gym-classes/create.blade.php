@extends('layouts.master')

@section('title')
    Create Gym Class
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('gym-classes.index') }}">Gym Classes</a>
@endslot
@slot('title')
    Create New Class
@endslot
@endcomponent

<form action="{{ route('gym-classes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Class Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Class Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_capacity" class="form-label">Max Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_capacity" name="max_capacity" 
                                       value="{{ old('max_capacity', 20) }}" min="1" max="100" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duration_minutes" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" 
                                       value="{{ old('duration_minutes', 60) }}" min="15" max="240" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                                <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                                    <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Class Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
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
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Class Schedules</h4>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addSchedule()">
                        <i class="ri-add-line"></i> Add Schedule
                    </button>
                </div>
                <div class="card-body">
                    <div id="schedules-container">
                        <!-- Schedules will be added here dynamically -->
                    </div>
                    <p class="text-muted" id="no-schedules-msg">Click "Add Schedule" to create class schedules</p>
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('gym-classes.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Create Class
                </button>
            </div>
        </div>
    </div>
</form>

@section('script')
<script>
let scheduleIndex = 0;

function addSchedule() {
    const container = document.getElementById('schedules-container');
    const noSchedulesMsg = document.getElementById('no-schedules-msg');
    
    if (noSchedulesMsg) {
        noSchedulesMsg.style.display = 'none';
    }
    
    const scheduleHtml = `
        <div class="schedule-item border rounded p-3 mb-3" data-index="${scheduleIndex}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Schedule ${scheduleIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeSchedule(${scheduleIndex})">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Day of Week <span class="text-danger">*</span></label>
                        <select class="form-select" name="schedules[${scheduleIndex}][day_of_week]" required>
                            <option value="">Select Day</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="schedules[${scheduleIndex}][start_time]" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="schedules[${scheduleIndex}][end_time]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Trainer</label>
                        <select class="form-select" name="schedules[${scheduleIndex}][trainer_id]">
                            <option value="">No Trainer</option>
                            @foreach($trainers as $trainer)
                                <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Room Location</label>
                        <input type="text" class="form-control" name="schedules[${scheduleIndex}][room_location]" placeholder="e.g., Studio A">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', scheduleHtml);
    scheduleIndex++;
}

function removeSchedule(index) {
    const scheduleItem = document.querySelector(`[data-index="${index}"]`);
    if (scheduleItem) {
        scheduleItem.remove();
    }
    
    // Show no schedules message if all removed
    const container = document.getElementById('schedules-container');
    const noSchedulesMsg = document.getElementById('no-schedules-msg');
    if (container.children.length === 0 && noSchedulesMsg) {
        noSchedulesMsg.style.display = 'block';
    }
}

// Add one schedule by default
document.addEventListener('DOMContentLoaded', function() {
    addSchedule();
});
</script>
@endsection
@endsection
