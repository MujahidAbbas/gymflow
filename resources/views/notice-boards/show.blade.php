@extends('layouts.master')

@section('title')
    Notice Details
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1')
    Notice Board
@endslot
@slot('title')
    Notice Details
@endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Notice Information</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('notice-boards.edit', $noticeBoard->id) }}" class="btn btn-primary btn-sm">
                        <i class="ri-pencil-line align-middle me-1"></i> Edit Notice
                    </a>
                    <a href="{{ route('notice-boards.index') }}" class="btn btn-secondary btn-sm ms-1">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-3">{{ $noticeBoard->title }}</h5>
                        <div class="mb-4">
                            {!! nl2br(e($noticeBoard->content)) !!}
                        </div>
                        
                        @if($noticeBoard->attachment)
                            <div class="mt-4">
                                <h5>Attachment</h5>
                                <a href="{{ URL::asset('storage/' . $noticeBoard->attachment) }}" target="_blank" class="btn btn-soft-primary btn-sm">
                                    <i class="ri-download-line align-middle me-1"></i> Download Attachment
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0" scope="row">Priority :</th>
                                            <td>
                                                <span class="badge 
                                                    @if($noticeBoard->priority == 'high') bg-danger
                                                    @elseif($noticeBoard->priority == 'medium') bg-warning text-dark
                                                    @else bg-info
                                                    @endif
                                                ">{{ ucfirst($noticeBoard->priority) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Publish Date :</th>
                                            <td class="text-muted">{{ $noticeBoard->publish_date->format('d M, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Expiry Date :</th>
                                            <td class="text-muted">{{ $noticeBoard->expiry_date ? $noticeBoard->expiry_date->format('d M, Y') : 'No expiry' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Status :</th>
                                            <td>
                                                @if($noticeBoard->is_active && !$noticeBoard->isExpired())
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
