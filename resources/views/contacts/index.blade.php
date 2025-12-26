@extends('layouts.master')

@section('title') Contacts @endsection

@push('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
@component('components.breadcrumb')
@slot('li_1') CRM @endslot
@slot('title') Contacts @endslot
@endcomponent

<div class="row g-4 mb-3">
    <div class="col-sm-auto">
        <div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#contactModal" onclick="openCreateModal()">
                <i class="ri-add-line align-bottom me-1"></i> Add Contact
            </button>
        </div>
    </div>
    <div class="col-sm">
        <div class="d-flex justify-content-sm-end gap-2">
            <form method="GET" class="d-flex gap-2">
                <div class="search-box">
                    <input type="text" name="search" class="form-control" placeholder="Search contacts..." value="{{ request('search') }}">
                    <i class="ri-search-line search-icon"></i>
                </div>
                @if(request('search'))
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                        <i class="ri-refresh-line me-1"></i> Clear
                    </a>
                @endif
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" id="success-alert">
        <i class="ri-check-line me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row" id="contacts-grid">
    @forelse($contacts as $contact)
        <div class="col-xxl-3 col-sm-6 contact-card" data-contact-id="{{ $contact->id }}">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="d-flex flex-column h-100">
                        {{-- Header with Actions --}}
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-4">
                                    <i class="ri-time-line align-bottom me-1"></i>
                                    <span class="contact-time">{{ $contact->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-17"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" onclick="openEditModal({{ $contact->id }}); return false;">
                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#" onclick="deleteContact({{ $contact->id }}); return false;">
                                            <i class="ri-delete-bin-fill align-bottom me-2"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Info --}}
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20 contact-avatar">
                                        {{ strtoupper(substr($contact->name ?? 'N', 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fs-16">
                                    <a href="#" class="text-body contact-name" data-bs-toggle="modal" data-bs-target="#viewContactModal" onclick="openViewModal('{{ $contact->id }}')">
                                        {{ $contact->name ?? 'N/A' }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-1 contact-email">
                                    @if($contact->email)
                                        <i class="ri-mail-line align-bottom me-1"></i>
                                        {{ Str::limit($contact->email, 25) }}
                                    @else
                                        No email
                                    @endif
                                </p>
                                <p class="text-muted mb-0 contact-phone">
                                    @if($contact->contact_number)
                                        <i class="ri-phone-line align-bottom me-1"></i>
                                        {{ $contact->contact_number }}
                                    @else
                                        <span class="text-muted" style="visibility: hidden;">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Subject/Message Preview --}}
                        <div class="mt-auto">
                            <div class="contact-subject">
                                @if($contact->subject)
                                    <div class="mb-2">
                                        <span class="badge bg-info-subtle text-info">
                                            {{ Str::limit($contact->subject, 30) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-muted text-truncate-two-lines mb-0 contact-message">
                                @if($contact->message)
                                    {{ Str::limit($contact->message, 80) }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Card Footer --}}
                <div class="card-footer bg-transparent border-top-dashed py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-muted contact-date">
                                <i class="ri-calendar-event-fill me-1 align-bottom"></i>
                                {{ $contact->created_at->format('d M, Y') }}
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#viewContactModal" onclick="openViewModal('{{ $contact->id }}')">
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
                        <i class="ri-contacts-line display-4 text-muted"></i>
                    </div>
                    <h5>No Contacts Found</h5>
                    <p class="text-muted">Get started by adding your first contact.</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#contactModal" onclick="openCreateModal()">
                        <i class="ri-add-line me-1"></i> Add Contact
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
            {{ $contacts->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- Contact Modal --}}
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Add Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contactForm" method="POST" action="javascript:void(0);">
                @csrf
                <input type="hidden" id="contactId" name="contact_id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter contact name">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter contact number">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message/Notes</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter any notes or message"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveContactBtn">
                        <i class="ri-save-line me-1"></i> Save Contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Contact Modal --}}
<div class="modal fade" id="viewContactModal" tabindex="-1" aria-labelledby="viewContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContactModalLabel">Contact Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-md">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-24" id="view_avatar">
                                N
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1" id="view_name">Loading...</h5>
                        <p class="text-muted mb-0" id="view_email_header"></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" style="width: 150px;">Email</th>
                                <td class="text-muted" id="view_email">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Contact Number</th>
                                <td class="text-muted" id="view_contact_number">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Subject</th>
                                <td class="text-muted" id="view_subject">-</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Message</th>
                                <td class="text-muted" id="view_message">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="view_edit_btn">
                    <i class="ri-pencil-line me-1"></i> Edit Contact
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
// Global functions accessible from onclick handlers
function openCreateModal() {
    $('#contactModalLabel').text('Add Contact');
    $('#contactForm')[0].reset();
    $('#contactId').val('');
    $('#formMethod').val('POST');
    clearValidationErrors();
}

function openEditModal(contactId) {
    $('#contactModalLabel').text('Edit Contact');
    $('#formMethod').val('PUT');
    $('#contactId').val(contactId);
    clearValidationErrors();

    // Fetch contact data
    $.ajax({
        url: `/contacts/${contactId}/edit`,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(data) {
            $('#name').val(data.contact.name || '');
            $('#email').val(data.contact.email || '');
            $('#contact_number').val(data.contact.contact_number || '');
            $('#subject').val(data.contact.subject || '');
            $('#message').val(data.contact.message || '');
            
            // Show modal using Bootstrap 5 API
            const modalEl = document.getElementById('contactModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            showToast('Error loading contact data', 'danger');
        }
    });
}

function openViewModal(contactId) {
    // Clear previous data
    $('#view_name').text('Loading...');
    $('#view_email').text('-');
    $('#view_email_header').text('');
    $('#view_contact_number').text('-');
    $('#view_subject').text('-');
    $('#view_message').text('-');
    $('#view_avatar').text('...');
    
    // Fetch contact data
    $.ajax({
        url: `/contacts/${contactId}`,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(data) {
            if (data.success) {
                const contact = data.contact;
                const name = contact.name || 'N/A';
                const email = contact.email || 'N/A';
                
                $('#view_name').text(name);
                $('#view_avatar').text(name.charAt(0).toUpperCase());
                $('#view_email').text(email);
                $('#view_email_header').text(email !== 'N/A' ? email : '');
                $('#view_contact_number').text(contact.contact_number || 'N/A');
                $('#view_subject').text(contact.subject || 'N/A');
                $('#view_message').text(contact.message || 'N/A');
                
                // Set edit button action
                const editBtn = document.getElementById('view_edit_btn');
                if (editBtn) {
                    editBtn.onclick = function() {
                        switchToEditModal(contact.id);
                    };
                }
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            showToast('Error loading contact details', 'danger');
        }
    });
}

function switchToEditModal(contactId) {
    // Hide view modal
    const viewModalEl = document.getElementById('viewContactModal');
    const viewModal = bootstrap.Modal.getInstance(viewModalEl);
    if (viewModal) {
        viewModal.hide();
    }
    
    // Open edit modal after a short delay to allow animation to finish
    setTimeout(() => {
        openEditModal(contactId);
    }, 500);
}

function deleteContact(contactId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/contacts/${contactId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        // Remove card from DOM with fade effect
                        $(`.contact-card[data-contact-id="${contactId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if no contacts left
                            if ($('.contact-card').length === 0) {
                                location.reload();
                            }
                        });
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Contact has been deleted.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete contact.',
                        icon: 'error'
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
    $.each(errors, function(field, messages) {
        const $input = $('#' + field);
        if ($input.length) {
            $input.addClass('is-invalid');
            $input.next('.invalid-feedback').text(messages[0]);
        }
    });
}

function showToast(message, type = 'success') {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'ri-check-line' : 'ri-error-warning-line';
    
    const $alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show">
            <i class="${icon} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('.row.g-4.mb-3').after($alert);
    
    setTimeout(() => $alert.alert('close'), 3000);
}

// Document ready for event handlers
$(document).ready(function() {
    // Handle form submission
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        clearValidationErrors();

        const formData = new FormData(this);
        const contactId = $('#contactId').val();
        const method = $('#formMethod').val();
        const url = contactId ? `/contacts/${contactId}` : '/contacts';

        // Add _method for Laravel method spoofing
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success) {
                    // Hide modal using Bootstrap 5 API
                    const modalEl = document.getElementById('contactModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                    
                    showToast(data.message, 'success');
                    
                    // Reload page to update the list
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    displayValidationErrors(xhr.responseJSON.errors);
                } else {
                    console.error('Error:', xhr);
                    showToast('An error occurred', 'danger');
                }
            }
        });
    });
});
</script>
@endpush
