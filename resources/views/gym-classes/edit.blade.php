@extends('layouts.master')

@section('title')
    Edit Gym Class
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('gym-classes.index') }}">Gym Classes</a>
@endslot
@slot('title')
    Edit Class: {{ $gymClass->name }}
@endslot
@endcomponent

<form action="{{ route('gym-classes.update', $gymClass->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Class Information</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>Class ID:</strong> {{ $gymClass->class_id }}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Class Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $gymClass->name) }}" required>
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
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $gymClass->category_id) == $category->id ? 'selected' : '' }}>
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
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $gymClass->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_capacity" class="form-label">Max Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_capacity" name="max_capacity" 
                                       value="{{ old('max_capacity', $gymClass->max_capacity) }}" min="1" max="100" required>
                                <small class="text-muted">Current enrolled: {{ $gymClass->enrolled_count }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duration_minutes" class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" 
                                       value="{{ old('duration_minutes', $gymClass->duration_minutes) }}" min="15" max="240" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="difficulty_level" class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                                <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                                    <option value="beginner" {{ old('difficulty_level', $gymClass->difficulty_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('difficulty_level', $gymClass->difficulty_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('difficulty_level', $gymClass->difficulty_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Class Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @if($gymClass->image)
                                    <small class="text-muted">Current: {{ basename($gymClass->image) }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ old('status', $gymClass->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $gymClass->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="cancelled" {{ old('status', $gymClass->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Current Schedules</h4>
                </div>
                <div class="card-body">
                    @if($gymClass->schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Trainer</th>
                                        <th>Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gymClass->schedules as $schedule)
                                        <tr>
                                            <td>{{ ucfirst($schedule->day_of_week) }}</td>
                                            <td>{{ $schedule->time_range }}</td>
                                            <td>{{ $schedule->trainer ? $schedule->trainer->name : 'No trainer' }}</td>
                                            <td>{{ $schedule->room_location ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-2">Note: To modify schedules, please delete this class and create a new one, or contact support for schedule management.</p>
                    @else
                        <p class="text-muted">No schedules configured for this class.</p>
                    @endif
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('gym-classes.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Update Class
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
