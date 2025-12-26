@extends('layouts.master')

@section('title') Edit Support Ticket @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('support-tickets.index') }}">Tickets</a> @endslot
@slot('title') Edit Ticket @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Ticket - {{ $supportTicket->ticket_number }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('support-tickets.update', $supportTicket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="member_id" class="form-label">Member (Optional)</label>
                        <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror">
                            <option value="">-- Not Assigned to Member --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id', $supportTicket->member_id) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->member_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $supportTicket->subject) }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="6" required>{{ old('description', $supportTicket->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority', $supportTicket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $supportTicket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $supportTicket->priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $supportTicket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="open" {{ old('status', $supportTicket->status) == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $supportTicket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ old('status', $supportTicket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ old('status', $supportTicket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                            <option value="">-- Unassigned --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $supportTicket->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($supportTicket->resolved_at)
                        <div class="alert alert-success">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            <strong>Resolved:</strong> {{ $supportTicket->resolved_at->format('M d, Y H:i') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Update Ticket
                        </button>
                        <a href="{{ route('support-tickets.show', $supportTicket->id) }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
