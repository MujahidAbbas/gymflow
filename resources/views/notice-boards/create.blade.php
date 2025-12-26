@extends('layouts.master')

@section('title')
    Create Notice
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('notice-boards.index') }}">Notice Board</a>
@endslot
@slot('title')
    Create New Notice
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Notice Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('notice-boards.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="title" class="form-label">Notice Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="5" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="publish_date" name="publish_date" 
                                       value="{{ old('publish_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                                       value="{{ old('expiry_date') }}">
                                <small class="text-muted">Leave empty for no expiry</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attachment</label>
                                <input type="file" class="form-control" id="attachment" name="attachment" 
                                       accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">PDF, DOC, JPG, PNG (Max: 5MB)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('notice-boards.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i> Create Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
