@extends('layouts.master')

@section('title')
    Edit Category
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('categories.index') }}">Categories</a>
@endslot
@slot('title')
    Edit Category: {{ $category->name }}
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                        <input type="color" class="form-control form-control-color w-100" 
                               id="color" name="color" value="{{ old('color', $category->color) }}" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" 
                               name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active Category
                        </label>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
