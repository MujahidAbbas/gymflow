@extends('layouts.master')

@section('title') Ticket Details @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('support-tickets.index') }}">Tickets</a> @endslot
@slot('title') {{ $supportTicket->ticket_number }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $supportTicket->subject }}</h5>
                    <span class="badge {{ $supportTicket->status == 'resolved' ? 'bg-success' : 'bg-warning' }}">
                        {{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Ticket #:</strong> {{ $supportTicket->ticket_number }}
                </div>
                <div class="mb-3">
                    <strong>Member:</strong> {{ $supportTicket->member->name ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Priority:</strong> 
                    <span class="badge bg-{{ $supportTicket->priority == 'urgent' ? 'danger' : 'info' }}">
                        {{ ucfirst($supportTicket->priority) }}
                    </span>
                </div>
                <hr>
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p class="mt-2">{{ $supportTicket->description }}</p>
                </div>

                <hr>
                <h6 class="mb-3">Conversation</h6>

                @forelse($supportTicket->replies as $reply)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $reply->user->name }}</strong>
                                <small class="text-muted">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            @if($reply->is_internal_note)
                                <span class="badge bg-secondary">Internal Note</span>
                            @endif
                            <p class="mt-2 mb-0">{{ $reply->message }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No replies yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Add Reply</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('support-tickets.reply', $supportTicket->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea name="message" id="message" class="form-control" rows="6" required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_internal_note" name="is_internal_note">
                        <label class="form-check-label" for="is_internal_note">
                            Internal Note (Not visible to member)
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="ri-send-plane-line me-1"></i> Add Reply
                    </button>
                </form>
                <hr>
                <a href="{{ route('support-tickets.edit', $supportTicket->id) }}" class="btn btn-info w-100">
                    <i class="ri-pencil-line me-1"></i> Edit Ticket
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
