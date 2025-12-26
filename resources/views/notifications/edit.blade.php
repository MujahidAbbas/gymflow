@extends('layouts.master')

@section('title')
    Edit Email Template
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    <a href="{{ route('notifications.index') }}">Email Templates</a>
@endslot
@slot('title')
    Edit Template
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Email Template: <strong>{{ ucfirst(str_replace('_', ' ', $notification->module)) }}</strong></h4>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('notifications.update', $notification->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="module" value="{{ $notification->module }}" disabled>
                                <small class="text-muted">Module cannot be changed</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" name="subject" value="{{ old('subject', $notification->subject) }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="message" class="form-label">Email Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="10" required>{{ old('message', $notification->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">You can use shortcodes like {gym_name}, {user_name}, {email}, etc.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="enabled_email" name="enabled_email" value="1"
                                           {{ old('enabled_email', $notification->enabled_email) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enabled_email">
                                        <strong>Enable Email Notification</strong>
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, emails will be sent automatically for this event</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="enabled_web" name="enabled_web" value="1"
                                           {{ old('enabled_web', $notification->enabled_web) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enabled_web">
                                        <strong>Enable Web Notification</strong>
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, notifications will appear in dashboard</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="ri-information-line"></i> Available Shortcodes for {{ ucfirst(str_replace('_', ' ', $notification->module)) }}</h6>
                        <p class="mb-0 small">
                            <code>{gym_name}</code> <code>{user_name}</code> <code>{email}</code> <code>{password}</code> 
                            <code>{member_name}</code> <code>{member_id}</code> <code>{trainer_name}</code> <code>{trainer_id}</code>
                            <code>{membership_plan}</code> <code>{expiry_date}</code> <code>{class_name}</code> <code>{schedule_time}</code>
                            <code>{capacity}</code> <code>{workout_id}</code> <code>{duration}</code> <code>{weight}</code> 
                            <code>{bmi}</code> <code>{date}</code> <code>{check_in_time}</code> <code>{check_out_time}</code>
                            <code>{invoice_number}</code> <code>{amount}</code> <code>{due_date}</code> <code>{locker_number}</code>
                            <code>{start_date}</code>
                        </p>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line"></i> Update Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
