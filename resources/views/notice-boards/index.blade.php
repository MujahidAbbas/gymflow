@extends('layouts.master')

@section('title') Notice Board @endsection

@push('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
@component('components.breadcrumb')
@slot('li_1') CRM @endslot
@slot('li_1')
    Management
@endslot
@slot('title')
    Notice Board
@endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">Notice Board</h4>
                    <div class="d-flex gap-2">
                        <div class="search-box">
                            <form action="{{ route('notice-boards.index') }}" method="GET">
                                <input type="text" class="form-control search" name="search" value="{{ request('search') }}" placeholder="Search notices...">
                                <i class="ri-search-line search-icon"></i>
                            </form>
                        </div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#noticeModal" onclick="openCreateModal()">
                            <i class="ri-add-line align-bottom me-1"></i> Add Notice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse($notices as $notice)
        <div class="col-xxl-3 col-md-6">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="d-flex flex-column h-100">
                        {{-- Header with Priority and Actions --}}
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                @php
                                    $priorityClass = match($notice->priority) {
                                        'high' => 'bg-danger',
                                        'medium' => 'bg-warning',
                                        default => 'bg-info',
                                    };
                                @endphp
                                <span class="badge {{ $priorityClass }}">{{ ucfirst($notice->priority) }}</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-more-2-fill fs-17"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" onclick="openEditModal({{ $notice->id }}); return false;">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteNotice({{ $notice->id }}); return false;">
                                        <i class="ri-delete-bin-fill align-bottom me-2"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Notice Info --}}
                        <div class="mb-3">
                            <h5 class="mb-1 fs-16">
                                <a href="#" class="text-body notice-title" data-bs-toggle="modal" data-bs-target="#viewNoticeModal" onclick="openViewModal('{{ $notice->id }}')">
                                    {{ $notice->title }}
                                </a>
                            </h5>
                            <p class="text-muted mb-0">
                                <i class="ri-calendar-event-line align-bottom me-1"></i>
                                {{ $notice->publish_date->format('d M, Y') }}
                            </p>
                        </div>

                        {{-- Content Preview --}}
                        <div class="mt-auto">
                            <p class="text-muted text-truncate-two-lines mb-0 notice-content">
                                {{ Str::limit($notice->content, 100) }}
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Card Footer --}}
                <div class="card-footer bg-transparent border-top-dashed py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            @if($notice->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                            @endif
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#viewNoticeModal" onclick="openViewModal('{{ $notice->id }}')">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="ri-notification-3-line display-4 text-muted"></i>
                    </div>
                    <h5>No Notices Found</h5>
                    <p class="text-muted">Get started by adding your first notice.</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#noticeModal" onclick="openCreateModal()">
                        <i class="ri-add-line me-1"></i> Add Notice
                    </button>
                </div>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="row">
    <div class="col-12">
        <div class="mt-3">
            {{ $notices->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- Notice Modal --}}
<div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noticeModalLabel">Add Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="noticeForm" method="POST" action="javascript:void(0);" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="noticeId" name="notice_id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter notice title">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="4" placeholder="Enter notice content"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="publish_date" name="publish_date">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveNoticeBtn">
                        <i class="ri-save-line me-1"></i> Save Notice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Notice Modal --}}
<div class="modal fade" id="viewNoticeModal" tabindex="-1" aria-labelledby="viewNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNoticeModalLabel">Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h5 class="mb-1" id="view_title">Loading...</h5>
                    <div class="d-flex gap-2 mt-2">
                        <span class="badge" id="view_priority"></span>
                        <span class="badge" id="view_status"></span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" style="width: 150px;">Publish Date</th>
                                <td class="text-muted" id="view_publish_date">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Expiry Date</th>
                                <td class="text-muted" id="view_expiry_date">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Content</th>
                                <td class="text-muted" id="view_content">-</td>
                            </tr>
                            <tr id="view_attachment_row" style="display: none;">
                                <th class="ps-0">Attachment</th>
                                <td><a href="#" id="view_attachment_link" target="_blank" class="text-primary"><i class="ri-attachment-line me-1"></i> Download</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="view_edit_btn">
                    <i class="ri-pencil-line me-1"></i> Edit Notice
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
function openCreateModal() {
    $('#noticeModalLabel').text('Add Notice');
    $('#noticeForm')[0].reset();
    $('#noticeId').val('');
    $('#formMethod').val('POST');
    $('#is_active').prop('checked', true);
    $('#attachment').val(''); // Clear attachment field
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    $('#publish_date').val(today);
    
    clearValidationErrors();
}

function openEditModal(noticeId) {
    $('#noticeModalLabel').text('Edit Notice');
    $('#formMethod').val('PUT');
    $('#noticeId').val(noticeId);
    $('#attachment').val(''); // Clear attachment field for security reasons
    clearValidationErrors();

    $.ajax({
        url: `/notice-boards/${noticeId}/edit`,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(data) {
            const notice = data.notice;
            $('#title').val(notice.title);
            $('#content').val(notice.content);
            $('#priority').val(notice.priority);
            $('#publish_date').val(notice.publish_date.split('T')[0]);
            $('#expiry_date').val(notice.expiry_date ? notice.expiry_date.split('T')[0] : '');
            $('#is_active').prop('checked', notice.is_active);
            
            const modalEl = document.getElementById('noticeModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            showToast('Error loading notice data', 'danger');
        }
    });
}

function openViewModal(noticeId) {
    $('#view_title').text('Loading...');
    
    $.ajax({
        url: `/notice-boards/${noticeId}`,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(data) {
            if (data.success) {
                const notice = data.notice;
                
                $('#view_title').text(notice.title);
                $('#view_content').text(notice.content);
                
                // Priority Badge
                const priorityClass = {
                    'high': 'bg-danger',
                    'medium': 'bg-warning',
                    'low': 'bg-info'
                }[notice.priority] || 'bg-secondary';
                $('#view_priority').attr('class', `badge ${priorityClass}`).text(notice.priority.charAt(0).toUpperCase() + notice.priority.slice(1));
                
                // Status Badge
                const statusClass = notice.is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                $('#view_status').attr('class', `badge ${statusClass}`).text(notice.is_active ? 'Active' : 'Inactive');
                
                // Dates
                $('#view_publish_date').text(new Date(notice.publish_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }));
                $('#view_expiry_date').text(notice.expiry_date ? new Date(notice.expiry_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '-');
                
                // Attachment
                if (notice.attachment) {
                    $('#view_attachment_row').show();
                    $('#view_attachment_link').attr('href', '/storage/' + notice.attachment);
                } else {
                    $('#view_attachment_row').hide();
                }

                const editBtn = document.getElementById('view_edit_btn');
                if (editBtn) {
                    editBtn.onclick = function() {
                        switchToEditModal(notice.id);
                    };
                }
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            showToast('Error loading notice details', 'danger');
        }
    });
}

function switchToEditModal(noticeId) {
    const viewModalEl = document.getElementById('viewNoticeModal');
    const viewModal = bootstrap.Modal.getInstance(viewModalEl);
    if (viewModal) {
        viewModal.hide();
    }
    
    // Small delay to allow modal to close
    setTimeout(() => {
        openEditModal(noticeId);
    }, 500);
}

function deleteNotice(noticeId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-primary w-xs me-2 mt-2',
            cancelButton: 'btn btn-danger w-xs mt-2'
        },
        buttonsStyling: false,
        showCloseButton: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: `/notice-boards/${noticeId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Notice has been deleted.',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs mt-2'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong.',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary w-xs mt-2'
                        },
                        buttonsStyling: false
                    });
                }
            });
        }
    });
}

function clearValidationErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function displayValidationErrors(errors) {
    clearValidationErrors();
    for (const field in errors) {
        const input = $(`#${field}`);
        input.addClass('is-invalid');
        input.next('.invalid-feedback').text(errors[field][0]);
    }
}

function showToast(message, type = 'success') {
    // Simple toast implementation or use a library
    // For now, we can use SweetAlert for errors
    if (type === 'danger') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
        });
    }
}

$(document).ready(function() {
    $('#noticeForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(this);
        const noticeId = $('#noticeId').val();
        const method = $('#formMethod').val();
        
        let url = '{{ route("notice-boards.store") }}';
        if (method === 'PUT') {
            url = `/notice-boards/${noticeId}`;
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    const modalEl = document.getElementById('noticeModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                    
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-primary w-xs mt-2'
                        },
                        buttonsStyling: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    displayValidationErrors(xhr.responseJSON.errors);
                } else {
                    console.error('Error:', xhr);
                    showToast('An error occurred. Please try again.', 'danger');
                }
            }
        });
    });
});
</script>
@endpush
