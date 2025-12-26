@extends('layouts.master')

@section('title')
    Edit Finance Type
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('types.index') }}">Finance Types</a>
@endslot
@slot('title')
    Edit Type: {{ $type->name }}
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Type Information</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('types.update', $type->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Type Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $type->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="income" {{ old('category', $type->category) == 'income' ? 'selected' : '' }}>Income</option>
                                    <option value="expense" {{ old('category', $type->category) == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $type->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', $type->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('types.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line me-1"></i> Update Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
