# AJAX-Based CRUD Implementation Guide

This guide documents the patterns and conventions for implementing AJAX-based CRUD operations in this Laravel application, based on the Contacts module implementation.

## Overview

AJAX-based CRUD provides a smoother user experience by:
- Using modals instead of separate pages for create/edit/view
- Updating the UI without full page reloads
- Showing instant feedback with SweetAlert notifications

---

## 1. Controller Structure

### Key Principles
- Controllers should handle both AJAX and regular HTTP requests
- Use `$request->ajax() || $request->expectsJson()` to detect AJAX requests
- Return JSON for AJAX, redirect/view for regular requests

### Example Controller

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display listing - always returns a view (the main page with modals)
     */
    public function index(Request $request): View
    {
        $contacts = Contact::where('parent_id', parentId())
            ->latest()
            ->paginate(20);

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Store - returns JSON for AJAX, redirect for regular requests
     */
    public function store(StoreContactRequest $request): JsonResponse|RedirectResponse
    {
        $contact = Contact::create([
            ...$request->validated(),
            'parent_id' => parentId(),
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact created successfully',
                'contact' => $contact
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully');
    }

    /**
     * Show - returns JSON for AJAX (modal), redirect for regular requests
     */
    public function show(Request $request, Contact $contact): JsonResponse|RedirectResponse
    {
        // Authorization check
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'contact' => $contact
            ]);
        }

        // No separate view page - redirect to index
        return redirect()->route('contacts.index');
    }

    /**
     * Edit - returns JSON for AJAX (to populate modal)
     */
    public function edit(Contact $contact): JsonResponse|View
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json(['contact' => $contact]);
        }

        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update - returns JSON for AJAX, redirect for regular requests
     */
    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse|RedirectResponse
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        $contact->update($request->validated());

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact updated successfully',
                'contact' => $contact
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully');
    }

    /**
     * Destroy - returns JSON for AJAX, redirect for regular requests
     */
    public function destroy(Contact $contact): JsonResponse|RedirectResponse
    {
        if ($contact->parent_id != parentId()) {
            abort(403);
        }

        $contact->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully'
            ]);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully');
    }
}
```

---

## 2. Form Request Validation

### Key Principles
- Always use Form Request classes (not inline validation)
- Include custom error messages when helpful
- Use array syntax for rules

### Example Form Request

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'contact_number' => 'contact number',
        ];
    }
}
```

---

## 3. Blade View Structure

### Key Principles
- Single index page contains all modals (create, edit, view)
- Use `@push('css')` and `@push('scripts')` for page-specific assets
- Include SweetAlert2 for confirmations and notifications
- Use Remix Icons (ri-*) consistently

### Page Structure

```blade
@extends('layouts.master')

@section('title') Contacts @endsection

@push('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    {{-- Page Header with Add Button --}}
    {{-- Data Grid/Cards --}}
    {{-- Create/Edit Modal --}}
    {{-- View Modal --}}
@endsection

@push('scripts')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    // JavaScript functions
</script>
@endpush
```

### Modal Structure

```blade
{{-- Create/Edit Modal (shared) --}}
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
                    {{-- Form fields with validation feedback --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name">
                        <div class="invalid-feedback"></div>
                    </div>
                    {{-- More fields... --}}
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

{{-- View Modal (read-only) --}}
<div class="modal fade" id="viewContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Display fields --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="view_edit_btn">
                    <i class="ri-pencil-line me-1"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## 4. JavaScript Patterns

### Modal Functions

```javascript
// Open Create Modal
function openCreateModal() {
    $('#contactModalLabel').text('Add Contact');
    $('#contactForm')[0].reset();
    $('#contactId').val('');
    $('#formMethod').val('POST');
    $('#saveContactBtn').html('<i class="ri-save-line me-1"></i> Save Contact');
    clearValidationErrors();
}

// Open Edit Modal
function openEditModal(contactId) {
    $('#contactModalLabel').text('Edit Contact');
    $('#formMethod').val('PUT');
    $('#contactId').val(contactId);
    $('#saveContactBtn').html('<i class="ri-save-line me-1"></i> Update Contact');
    clearValidationErrors();

    // Fetch data via AJAX
    $.ajax({
        url: `/contacts/${contactId}/edit`,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(data) {
            // Populate form fields
            $('#name').val(data.contact.name || '');
            $('#email').val(data.contact.email || '');
            // ... more fields
            
            // Show modal
            const modalEl = document.getElementById('contactModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Failed to load contact data.', 'error');
        }
    });
}

// Open View Modal
function openViewModal(contactId) {
    // Clear previous data
    $('#view_name').text('Loading...');
    
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
                $('#view_name').text(contact.name || 'N/A');
                $('#view_email').text(contact.email || 'N/A');
                // ... more fields
                
                // Set edit button action
                $('#view_edit_btn').off('click').on('click', function() {
                    switchToEditModal(contact.id);
                });
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Failed to load contact details.', 'error');
        }
    });
}
```

### Form Submission

```javascript
$('#contactForm').on('submit', function(e) {
    e.preventDefault();
    clearValidationErrors();

    const formData = new FormData(this);
    const contactId = $('#contactId').val();
    const method = $('#formMethod').val();
    const url = contactId ? `/contacts/${contactId}` : '/contacts';

    // Laravel method spoofing for PUT
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        type: 'POST',  // Always POST, Laravel handles method spoofing
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if (data.success) {
                // Hide modal
                const modalEl = document.getElementById('contactModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Reload to update list
                setTimeout(() => location.reload(), 1500);
            }
        },
        error: function(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                displayValidationErrors(xhr.responseJSON.errors);
            } else {
                Swal.fire('Error!', 'An error occurred.', 'error');
            }
        }
    });
});
```

### Delete with SweetAlert Confirmation

```javascript
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
                        // Remove from DOM with animation
                        $(`.contact-card[data-contact-id="${contactId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            if ($('.contact-card').length === 0) {
                                location.reload();
                            }
                        });
                        
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Record has been deleted.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Failed to delete record.', 'error');
                }
            });
        }
    });
}
```

### Validation Error Handling

```javascript
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
```

---

## 5. Routes

Use Laravel's resourceful routing:

```php
Route::resource('contacts', ContactController::class);
```

This provides all standard CRUD routes:
- `GET /contacts` - index
- `POST /contacts` - store
- `GET /contacts/{contact}` - show
- `GET /contacts/{contact}/edit` - edit
- `PUT /contacts/{contact}` - update
- `DELETE /contacts/{contact}` - destroy

---

## 6. Testing

### Key Test Cases

```php
// List records
test('owner can list contacts', function () {
    $contact = Contact::factory()->create(['parent_id' => $this->owner->id]);
    
    $this->actingAs($this->owner)
        ->get(route('contacts.index'))
        ->assertOk()
        ->assertSee($contact->name);
});

// Create via AJAX
test('owner can create contact via ajax', function () {
    $this->actingAs($this->owner)
        ->postJson(route('contacts.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])
        ->assertOk()
        ->assertJson(['success' => true]);
});

// View via AJAX
test('owner can view contact details via ajax', function () {
    $contact = Contact::factory()->create(['parent_id' => $this->owner->id]);
    
    $this->actingAs($this->owner)
        ->getJson(route('contacts.show', $contact))
        ->assertOk()
        ->assertJson([
            'success' => true,
            'contact' => ['name' => $contact->name],
        ]);
});

// Validation
test('name is required when creating contact', function () {
    $this->actingAs($this->owner)
        ->post(route('contacts.store'), ['name' => null])
        ->assertSessionHasErrors('name');
});

// Authorization
test('owner cannot access other tenant contacts', function () {
    $otherContact = Contact::factory()->create(['parent_id' => 999]);
    
    $this->actingAs($this->owner)
        ->getJson(route('contacts.show', $otherContact))
        ->assertForbidden();
});
```

---

## 7. Checklist for New AJAX CRUD

- [ ] Create Model with `$fillable` and relationships
- [ ] Create Migration
- [ ] Create Factory (for testing)
- [ ] Create StoreRequest and UpdateRequest with validation
- [ ] Create Controller handling both AJAX and regular requests
- [ ] Create index.blade.php with:
  - [ ] Data grid/cards display
  - [ ] Create/Edit modal (shared)
  - [ ] View modal
  - [ ] JavaScript functions (openCreateModal, openEditModal, openViewModal, delete)
  - [ ] Form submission handler
  - [ ] Validation error display
  - [ ] SweetAlert confirmations
- [ ] Add resourceful route
- [ ] Write tests for all CRUD operations
- [ ] Test authorization/tenant isolation

---

## 8. Common Patterns Reference

### AJAX Headers
```javascript
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
}
```

### Bootstrap 5 Modal Control
```javascript
// Show modal - use getOrCreateInstance to avoid duplicate instances
const modalEl = document.getElementById('myModal');
const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
modal.show();

// Alternative (manual check)
let modal = bootstrap.Modal.getInstance(modalEl);
if (!modal) {
    modal = new bootstrap.Modal(modalEl);
}
modal.show();

// Hide modal - always check if instance exists
const modal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
if (modal) {
    modal.hide();
}
```

**Bootstrap 5 Modal Methods:**
- `new bootstrap.Modal(element)` - Creates new instance (can cause duplicates!)
- `bootstrap.Modal.getInstance(element)` - Gets existing instance or `null`
- `bootstrap.Modal.getOrCreateInstance(element)` - Gets existing or creates new ✅ (recommended)

### FormData for File Uploads
```javascript
const formData = new FormData(document.getElementById('myForm'));
// Don't forget these options:
processData: false,
contentType: false,
```

### Laravel Method Spoofing
```javascript
// For PUT/PATCH/DELETE via AJAX
formData.append('_method', 'PUT');
// Then use type: 'POST' in AJAX call
```

