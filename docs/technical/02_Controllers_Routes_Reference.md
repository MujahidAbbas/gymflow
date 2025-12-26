# Controllers & Routes Reference
## FitHub SaaS - API & Controller Documentation

**Version**: 1.0  
**Date**: 2025-11-28  
**Total Controllers**: 43  
**Route File**: routes/web.php

---

## Overview

### Controller Architecture
- **Base Controller**: `app/Http/Controllers/Controller.php`
- **Auth Controllers**: 9 controllers in `app/Http/Controllers/Auth/`
- **Resource Controllers**: 34 business logic controllers
- **Middleware**: Applied via route groups

### Route Organization
All routes defined in `routes/web.php`:
- **Public routes**: Landing page, auth pages
- **Authenticated routes**: Main application (middleware: auth)
- **Protected routes**: XSS middleware for forms

---

## Authentication Controllers

### 1. Auth/AuthenticatedSessionController
**Purpose**: Login/logout handling

**Routes**:
```php
GET  /login                    - Show login form
POST /login                    - Process login
POST /logout                   - Logout user
```

**Key Methods**:
- `create()` - Display login form
- `store(LoginRequest $request)` - Authenticate user
- `destroy(Request $request)` - Logout and invalidate session

---

### 2. Auth/RegisteredUserController
**Purpose**: User registration

**Routes**:
```php
GET  /register                 - Show registration form  
POST /register                 - Create new account
```

**Key Methods**:
- `create()` - Display registration form
- `store(Request $request)` - Create owner account

**Validation**:
```php
[
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|unique:users',
    'password' => 'required|min:6|confirmed',
]
```

---

### 3. Auth/PasswordResetLinkController
**Purpose**: Password reset email

**Routes**:
```php
GET  /forgot-password          - Show form
POST /forgot-password          - Send reset link
```

---

### 4. OTPController
**Purpose**: Two-factor authentication

**Routes**:
```php
GET  /login/otp                - Show OTP form
POST /login/otp                - Verify OTP
POST /login/2fa/disable        - Disable 2FA
```

**Key Methods**:
- `show()` - Display OTP input
- `check(Request $request)` - Verify Google2FA code
- `disable2FA()` - Disable 2FA for user

---

## Core Business Controllers

### 1. UserController
**Purpose**: User management (CRUD)

**Routes**:
```php
GET    /users                  - List users
GET    /users/create           - Create form
POST   /users                  - Store user
GET    /users/{id}             - View details
GET    /users/{id}/edit        - Edit form
PUT    /users/{id}             - Update user
DELETE /users/{id}             - Delete user
```

**Additional Routes**:
```php
GET    /logged-history         - Login history
GET    /logged-history/{id}    - View log entry  
DELETE /logged-history/{id}    - Delete log
```

**Controller Methods** (10 total):
1. `index()` - List with filters
2. `create()` - Show form
3. `store(Request $request)` - Create user (171 lines - includes email, role assignment)
4. `show($id)` - Display profile
5. `edit($id)` - Edit form
6. `update(Request $request, $id)` - Update profile
7. `destroy($id)` - Delete user
8. `loggedHistory()` - View login logs
9. `loggedHistoryShow($id)` - Single log
10. `loggedHistoryDestroy($id)` - Delete log

**Validation Rules**:
```php
// Create
[
    'name' => 'required',
    'email' => 'required|email|unique:users',
    'type' => 'required',
    'phone_number' => 'required',
    'password' => 'required|min:6',
]

// Update
[
    'name' => 'required',
    'email' => 'required|email|unique:users,email,'.$id,
    'type' => 'required',
    'phone_number' => 'required',
]
```

---

### 2. TrainerController
**Purpose**: Trainer management

**Routes**:
```php
Resource: /trainers (CRUD)
GET /trainer-number            - Auto-generate ID
```

**Controller Methods** (8 total):
1. `index()` - List trainers
2. `create()` - Create form
3. `store(Request $request)` - Create trainer (sends welcome email)
4. `show($id)` - View details
5. `edit($id)` - Edit form
6. `update(Request $request, $id)` - Update trainer
7. `destroy($id)` - Delete trainer
8. `trainerNumber()` - Generate next trainer ID

**Trainer Creation Flow**:
1. Validate input
2. Create User record (type='trainer')
3. Create TrainerDetail record
4. Auto-generate trainer_id (#TRNR-000X)
5. Assign default permissions
6. Send welcome email via `defaultTrainerCreate()`

---

### 3. TraineeController
**Purpose**: Trainee/member management

**Routes**:
```php
Resource: /trainees (CRUD)
GET  /trainee-number           - Auto-generate ID
GET  /membership-renewal/{id}  - Renewal form
POST /membership-renewal       - Process renewal
```

**Controller Methods** (10 total):
1. `index()` - List trainees
2. `create()` - Create form
3. `store(Request $request)` - Create trainee (generates invoice)
4. `show($id)` - View details
5. `edit($id)` - Edit form
6. `update(Request $request, $id)` - Update trainee
7. `destroy($id)` - Delete trainee
8. `traineeNumber()` - Generate ID
9. `membershipRenewal($id)` - Show renewal form
10. `membershipRenewalStore(Request $request)` - Process renewal

**Membership Renewal Process**:
1. Select trainee
2. Choose new membership plan
3. Set start date
4. Auto-calculate expiry using `Membership::calculateExpiryDate()`
5. Update trainee_details
6. Optional: Generate invoice
7. Send confirmation email

---

### 4. ClassesController
**Purpose**: Class management with schedules

**Routes**:
```php
Resource: /classes (CRUD)
POST /classes/schedule-destroy       - Delete schedule
GET  /classes/assign/{id}/{type}     - Assignment form
POST /classes/assign/{id}/{type}     - Process assignment
GET  /classes/assign-remove/{id}     - Remove assignment
```

**Controller Methods** (11 total):
1. `index()` - List classes
2. `create()` - Create form
3. `store(Request $request)` - Create class (handles multiple schedules)
4. `show($id)` - View details
5. `edit($id)` - Edit form
6. `update(Request $request, $id)` - Update class
7. `destroy($id)` - Delete class
8. `scheduleDestroy(Request $request)` - Delete schedule
9. `userAssign($class_id, $user_type)` - Show assign form (trainer/trainee)
10. `userAssignStore(Request $request, $class_id, $user_type)` - Assign users
11. `userAssignRemove($id)` - Remove user from class

**Class Creation with Schedules**:
```php
// Multiple schedules can be added:
'days[]' => ['Monday', 'Wednesday', 'Friday']
'start_time[]' => ['09:00', '09:00', '09:00']
'end_time[]' => ['10:00', '10:00', '10:00']
```

---

### 5. WorkoutController
**Purpose**: Workout plan management

**Routes**:
```php
Resource: /workouts (CRUD)
GET /today-workouts            - Today's workouts
```

**Controller Methods** (8 total):
1. `index()` - List workouts (filtered by user type)
2. `create()` - Create form
3. `store(Request $request)` - Create workout with activities
4. `show($id)` - View details
5. `edit($id)` - Edit form
6. `update(Request $request, $id)` - Update workout
7. `destroy($id)` - Delete workout
8. `todayWorkout()` - Filter workouts for today

**Workout Structure**:
```php
// Workout has many WorkoutActivities:
[
    'trainee_id' => required,
    'start_date' => required,
    'end_date' => required,
    'activities' => [
        [
            'name' => 'Push-ups',
            'sets' => 3,
            'reps' => 15,
            'duration' => '10 min',
            'rest_time' => '1 min',
        ]
    ]
]
```

---

### 6. InvoiceController
**Purpose**: Invoice and payment management

**Routes**:
```php
Resource: /invoices (CRUD)
GET    /invoice-number         - Auto-generate invoice #
POST   /invoice-type-destroy   - Delete invoice item
GET    /invoice-payment/create/{id}     - Payment form
POST   /invoice-payment/{id}             - Record payment
DELETE /invoice-payment/{invoice_id}/{payment_id} - Delete payment
```

**Controller Methods** (12 total):
1. `index()` - List invoices
2. `create()` - Create form
3. `store(Request $request)` - Create invoice with items
4. `show($id)` - View invoice
5. `edit($id)` - Edit form
6. `update(Request $request, $id)` - Update invoice
7. `destroy($id)` - Delete invoice
8. `invoiceNumber()` - Generate next invoice #
9. `invoiceTypeDestroy(Request $request)` - Remove line item
10. `invoicePaymentCreate($invoice_id)` - Payment form
11. `invoicePaymentStore(Request $request, $invoice_id)` - Record payment
12. `invoicePaymentDestroy($invoice_id, $id)` - Delete payment

**Invoice Payment Process**:
1. Calculate remaining amount
2. Record payment
3. Update invoice status (paid/partial_paid/unpaid)
4. Send payment confirmation email

---

### 7. SubscriptionController
**Purpose**: SaaS subscription management

**Routes**:
```php
Resource: /subscriptions (CRUD)
GET  /subscription-transaction  - View transactions
POST /subscription/stripe/{id}  - Stripe payment
```

**Controller Methods** (9 total):
1. `index()` - List plans
2. `create()` - Create plan form
3. `store(Request $request)` - Create subscription plan
4. `show($id)` - View plan details
5. `edit($id)` - Edit plan
6. `update(Request $request, $id)` - Update plan
7. `destroy($id)` - Delete plan
8. `transaction()` - View all transactions
9. `stripePayment(Request $request, $id)` - Process Stripe payment

---

### 8. PaymentController
**Purpose**: Payment gateway integrations

**Routes**:
```php
POST /subscription-payment/{id}                    - Initiate payment
GET  /subscription/stripe/{id}                     - Stripe checkout
GET  /subscription/stripe/{id}/callback            - Stripe callback
GET  /subscription/paypal/{id}                     - PayPal checkout  
GET  /subscription/paypal/{id}/{status}            - PayPal callback
POST /subscription/bank-transfer/{id}              - Bank transfer upload
POST /subscription/bank-transfer/action            - Admin approve/reject
GET  /subscription/flutterwave/{id}                - Flutterwave checkout
GET  /subscription/flutterwave/{id}/callback       - Flutterwave callback
GET  /subscription/paystack/{id}                   - Paystack checkout
GET  /subscription/paystack/{id}/{pay_id}/{plan}   - Paystack callback
POST /subscription/manually-assign/{id}            - Manual assignment (super admin)
```

**Payment Flow** (Stripe example):
1. User selects plan
2. Applies coupon (optional)
3. Redirects to Stripe checkout
4. On success, Stripe callback
5. Verify payment with Stripe API
6. Create PackageTransaction record
7. Call `assignSubscription($id)` helper
8. Activate subscription, set expiry
9. Enforce user limits
10. Send confirmation email

---

## Settings & Configuration

### SettingController
**Purpose**: Application configuration management

**Routes**:
```php
POST /account-data              - Update profile
POST /password-data             - Change password
POST /account-delete            - Delete account
POST /general-data              - General settings
POST /smtp-data                 - SMTP config
GET  /smtp-test                 - SMTP test page
POST /smtp-test-mail            - Send test email
POST /payment-data              - Payment gateway config
POST /company-data              - Company details
POST /site-seo-data             - SEO settings
POST /google-recaptcha-data     - reCAPTCHA config
GET  /lang-change/{lang}        - Change language
POST /theme-settings            - Theme customization
POST /twofa/enable              - Enable/disable 2FA
GET  /footer-setting            - Footer settings
POST /footer-data               - Update footer
```

**Controller Methods** (15+ total) - Each handles specific settings category

**Settings Storage**:
```php
// All settings stored in `settings` table:
DB::insert('insert into settings (name, value, type, parent_id) 
    values (?, ?, ?, ?) 
    ON DUPLICATE KEY UPDATE value = VALUES(value)', 
    [$name, $value, $type, parentId()]
);
```

---

## Additional Controllers Summary

### CMS Controllers
- **HomePageController**: Landing page CMS
- **PageController**: Custom pages
- **AuthPageController**: Auth page customization
- **FAQController**: FAQ management

### Business Controllers
- **CategoryController**: Class categories
- **MembershipController**: Membership plans
- **HealthController**: Health measurements
- **AttendanceController**: Attendance (individual + bulk)
- **ExpenseController**: Expense tracking
- **TypeController**: Financial categories
- **LockerController**: Locker management
- **EventController**: Calendar events
- **NutritionScheduleController**: Meal plans
- **ProductController**: Product catalog
- **ProductBookingController**: Product sales
- **ContactController**: Contact inquiries
- **NoticeBoardController**: Internal notices
- **WorkoutActivityController**: Exercise library

### System Controllers
- **PermissionController**: Permission management
- **RoleController**: Role management
- **NotificationController**: Email template management
- **CouponController**: Coupon management

---

## Middleware Applied

### Route Groups
```php
// Public routes
Route::get('/')->name('home');
Route::get('/register')->name('register');

// Authenticated routes
Route::middleware(['auth', 'XSS'])->group(function () {
    // All application routes
});

// 2FA verification
Route::middleware(['auth', 'Verify2FA'])->group(function () {
    // Protected routes requiring 2FA
});
```

### Common Middleware
1. **auth**: Laravel authentication
2. **XSS**: Custom XSS protection (sanitizes input)
3. **Verify2FA**: Checks 2FA status
4. **web**: Session, CSRF, cookies
5. **guest**: Redirect if authenticated

---

## Request Validation Patterns

### Standard CRUD Validation
```php
// Common pattern across all controllers:
$validator = \Validator::make(
    $request->all(),
    [
        'field' => 'required',
        'email' => 'required|email|unique:table',
    ]
);

if ($validator->fails()) {
    return redirect()->back()->with('error', $validator->errors()->first());
}
```

### File Upload Validation
```php
[
    'profile' => 'image|mimes:png|max:2048',
    'document' => 'file|mimes:pdf,doc,docx|max:5120',
]
```

---

## Response Patterns

### Success Response
```php
return redirect()->route('entity.index')
    ->with('success', __('Entity successfully created.'));
```

### Error Response
```php
return redirect()->back()
    ->with('error', __('Permission Denied.') . ' ' . $errorMessage);
```

### JSON Response (API)
```php
return response()->json([
    'success' => true,
    'data' => $entity,
    'message' => 'Success'
]);
```

---

## Permission Checks

### Standard Pattern
```php
if (\Auth::user()->can('create entity')) {
    // Allowed
} else {
    return redirect()->back()->with('error', __('Permission Denied.'));
}
```

### Permission Names
Format: `{action} {module}`
- Actions: create, edit, delete, show, manage
- Modules: user, trainer, trainee, class, invoice, etc.

Examples:
- `create trainer`
- `edit invoice`
- `manage attendance`
- `show health update`

---

## Important Notes

### Multi-Tenancy in Controllers
All queries must scope by tenant:
```php
// Correct:
$entities = Entity::where('parent_id', parentId())->get();

// Also used:
if (\Auth::user()->type == 'trainer') {
    // Filter by assigned trainees
}
```

### Email Notifications
Most create/update actions trigger emails:
```php
$notification = Notification::where('parent_id', parentId())
    ->where('module', 'trainer_create')
    ->first();
    
if ($notification && $notification->enabled_email == 1) {
    $data = MessageReplace($notification, $trainer->id);
    commonEmailSend($trainer->email, $data);
}
```

### Helper Function Usage
Controllers heavily use helper functions:
- `parentId()` - Get current tenant ID
- `settings()` - Get all settings
- `dateFormat()` / `timeFormat()` - Format dates
- `priceFormat()` - Format currency
- Auto-ID generators: `trainerPrefix()`, `invoicePrefix()`, etc.

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-28  
**Next Document**: Authentication & Security Architecture
