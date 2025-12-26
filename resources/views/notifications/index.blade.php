@extends('layouts.master')

@section('title')
    Email Notification Templates
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Settings
@endslot
@slot('title')
    Email Notification Templates
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Email Templates Management</h4>
                <div class="flex-shrink-0">
                    <span class="badge bg-success">{{ $notifications->count() }} Templates</span>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-line align-middle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%">Module</th>
                                <th style="width: 30%">Subject</th>
                                <th style="width: 15%">Email Status</th>
                                <th style="width: 15%">Web Status</th>
                                <th style="width: 15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $notification->module)) }}</strong>
                                        <br><small class="text-muted">{{ $notification->module }}</small>
                                    </td>
                                    <td>{{ $notification->subject }}</td>
                                    <td>
                                        @if($notification->enabled_email)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->enabled_web)
                                            <span class="badge bg-info">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('notifications.edit', $notification->id) }}" class="btn btn-sm btn-primary">
                                            <i class="ri-pencil-line align-middle"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4">
                    <h5 class="alert-heading"><i class="ri-information-line"></i> Available Shortcodes</h5>
                    <p class="mb-0">Use these shortcodes in your email templates - they will be automatically replaced with actual values:</p>
                    <ul class="mt-2 mb-0">
                        <li><code>{gym_name}</code> - Gym/Application name</li>
                        <li><code>{user_name}</code>, <code>{member_name}</code>, <code>{trainer_name}</code> - User names</li>
                        <li><code>{email}</code>, <code>{password}</code> - Login credentials</li>
                        <li><code>{member_id}</code>, <code>{trainer_id}</code>, <code>{invoice_number}</code> - IDs</li>
                        <li><code>{membership_plan}</code>, <code>{expiry_date}</code> - Membership details</li>
                        <li><code>{class_name}</code>, <code>{schedule_time}</code>, <code>{capacity}</code> - Class details</li>
                        <li><code>{weight}</code>, <code>{bmi}</code>, <code>{date}</code> - Health tracking</li>
                        <li><code>{locker_number}</code>, <code>{start_date}</code> - Locker details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
