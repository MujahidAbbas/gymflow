# FitHub Quick Reference Guide

> [!TIP]
> This is a quick reference card for common patterns, commands, and conventions used in the FitHub codebase. Bookmark this for fast lookup during development.

## Multi-Tenancy Helpers

```php
// Get current tenant ID
$tenantId = parentId();

// Get tenant setting
$value = settings('key', 'default');

// Get all tenant settings
$settings = settings();
echo $settings->site_name;

// Log user login history
userLoggedHistory();
```

## Common Controller Patterns

### Tenant-Scoped Query
```php
$records = Model::where('parent_id', parentId())->get();
```

### Ownership Verification
```php
if ($record->parent_id != parentId()) {
    abort(403, 'Unauthorized access');
}
```

### Search & Filter
```php
$query = Model::where('parent_id', parentId());

if ($request->has('search')) {
    $query->where('name', 'like', "%{$request->search}%");
}

$records = $query->latest()->paginate(15);
```

### File Upload
```php
if ($request->hasFile('photo')) {
    // Delete old file
    if ($model->photo) {
        Storage::disk('public')->delete($model->photo);
    }
    // Store new file
    $data['photo'] = $request->file('photo')->store('members', 'public');
}
```

## Authentication Routes

| Route | Method | Purpose |
|-------|--------|---------|
| `/login` | GET | Show login form |
| `/login` | POST | Process login |
| `/logout` | POST | Logout user |
| `/2fa` | GET | Show 2FA form |
| `/2fa/verify` | POST | Verify OTP |
| `/2fa/enable` | POST | Enable 2FA |
| `/2fa/disable` | POST | Disable 2FA |
| `/email/verify` | GET | Verification notice |
| `/email/verify/{code}` | GET | Verify email |

## Payment Gateways

| Gateway | Status | Config Key |
|---------|--------|------------|
| Stripe | ✅ Active | `STRIPE_KEY`, `STRIPE_SECRET` |
| PayPal | ✅ Active | `PAYPAL_CLIENT_ID`, `PAYPAL_SECRET` |
| Flutterwave | ⚠️ Config | `FLUTTERWAVE_PUBLIC_KEY`, `FLUTTERWAVE_SECRET` |
| Paystack | ⚠️ Config | `PAYSTACK_PUBLIC_KEY`, `PAYSTACK_SECRET` |
| Bank Transfer | ✅ Active | Manual verification |

## Model Scopes

### Member
```php
Member::active()->get();         // Active members
Member::expired()->get();        // Expired members
```

### Subscription
```php
Subscription::active()->get();        // Active subscriptions
Subscription::expiringSoon()->get(); // Expiring in 7 days
```

### MembershipPlan
```php
MembershipPlan::active()->get();     // Active plans
```

## Common Relationships

```php
// Member
$member->membershipPlan;  // BelongsTo
$member->user;            // BelongsTo
$member->parent;          // BelongsTo (owner)

// GymClass
$gymClass->category;       // BelongsTo
$gymClass->schedules;      // HasMany
$gymClass->assigns;        // HasMany

// Subscription
$subscription->member;     // BelongsTo
$subscription->plan;       // BelongsTo
$subscription->transactions; // HasMany

// Invoice
$invoice->member;          // BelongsTo
$invoice->items;           // HasMany
$invoice->payments;        // HasMany
```

## Artisan Commands

### Development
```bash
php artisan serve                    # Start dev server
php artisan migrate --seed           # Run migrations + seeders
php artisan db:seed                  # Run seeders only
php artisan migrate:fresh --seed     # Fresh database
php artisan tinker                   # REPL
```

### Code Generation
```bash
php artisan make:model ModelName --migration --factory
php artisan make:controller ControllerName --resource
php artisan make:request StoreModelRequest
php artisan make:migration create_table_name
php artisan make:seeder ModelSeeder
php artisan make:test FeatureTest
php artisan make:test UnitTest --unit
```

### Caching
```bash
php artisan config:cache      # Cache config
php artisan route:cache       # Cache routes
php artisan view:cache        # Cache views
php artisan optimize          # All optimizations
php artisan optimize:clear    # Clear all caches
```

## Testing Commands

```bash
php artisan test                        # All tests
php artisan test --filter=MemberTest    # Specific test
php artisan test --parallel             # Parallel execution
php artisan test --coverage             # With coverage
```

## Database Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Table | Plural, snake_case | `members`, `gym_classes` |
| Primary Key | `id` | `$table->id()` |
| Foreign Key | `model_id` | `member_id`, `parent_id` |
| Pivot Table | Alphabetical | `class_member` |
| Timestamps | `created_at`, `updated_at` | `$table->timestamps()` |
| Soft Deletes | `deleted_at` | `$table->softDeletes()` |

## Validation Patterns

### Form Request Structure
```php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:members,email,' . $this->member?->id,
        'phone' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date|before:today',
        'photo' => 'nullable|image|max:2048',
        'status' => 'required|in:active,inactive,expired',
    ];
}
```

### Common Validation Rules
- `required` - Must be present
- `nullable` - Can be null
- `string` - Must be string
- `email` - Valid email
- `date` - Valid date
- `image` - Image file (jpg, png, etc.)
- `max:X` - Max length/size
- `min:X` - Min length/size
- `unique:table,column,except` - Unique value
- `in:val1,val2` - Must be one of values
- `exists:table,column` - Must exist in table

## Model Quick Reference

| Model | Primary Use | Auto-Generated ID |
|-------|-------------|-------------------|
| Member | Gym member records | `#MBR-XXXX` |
| Trainer | Trainer profiles | No |
| GymClass | Class definitions | No |
| Attendance | Check-in tracking | No |
| Invoice | Member invoices | No |
| Subscription | SaaS subscriptions | No |
| PaymentTransaction | Payment records | No |
| Locker | Locker inventory | No |
| Event | Events calendar | No |
| Workout | Workout sessions | No |
| Health | Health metrics | No |

## Controller Quick Reference

| Controller | Resource Route | Key Methods |
|------------|---------------|-------------|
| MemberController | `/members` | index, create, store, show, edit, update, destroy |
| TrainerController | `/trainers` | Standard CRUD |
| GymClassController | `/gym-classes` | Standard CRUD |
| AttendanceController | `/attendances` | index, store, report |
| WorkoutController | `/workouts` | Standard CRUD + today |
| SubscriptionController | `/subscriptions` | index, checkout, purchase, success, cancel |
| InvoiceController | `/invoices` | Standard CRUD |
| ExpenseController | `/expenses` | Standard CRUD |
| SettingsController | `/settings` | index, update, show |

## Environment Variables

### Core
```bash
APP_NAME=FitHub
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```

### Database
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fithub
DB_USERNAME=root
DB_PASSWORD=
```

### Payment
```bash
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
PAYPAL_CLIENT_ID=xxx
PAYPAL_SECRET=xxx
PAYPAL_MODE=sandbox
```

### Email
```bash
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

## File Structure

```
app/
├── Console/         # Artisan commands
├── Exceptions/      # Exception handlers
├── Helper/          # Helper functions
│   └── helper.php   # Global helpers
├── Http/
│   ├── Controllers/ # Controllers (23 files)
│   ├── Middleware/  # Custom middleware
│   └── Requests/    # Form requests
├── Models/          # Eloquent models (28 files)
├── Notifications/   # Notification classes
├── Providers/       # Service providers
└── Services/        # Business logic services

database/
├── factories/       # Model factories
├── migrations/      # Database migrations (37 files)
└── seeders/         # Database seeders

resources/
├── views/           # Blade templates
│   ├── auth/        # Authentication views
│   ├── layouts/     # Layout templates
│   ├── members/     # Member views
│   ├── trainers/    # Trainer views
│   └── ...
└── js/              # JavaScript files

routes/
├── web.php          # Web routes
├── api.php          # API routes
├── console.php      # Console routes
└── channels.php     # Broadcast channels
```

## Status Values

### Member Status
- `active` - Active member
- `inactive` - Inactive member
- `expired` - Expired membership

### Subscription Status
- `trial` - Trial period
- `active` - Active subscription
- `cancelled` - Cancelled by user
- `expired` - Expired subscription
- `pending` - Pending payment

### Payment Transaction Status
- `pending` - Awaiting confirmation
- `completed` - Payment successful
- `failed` - Payment failed
- `refunded` - Payment refunded

### Locker Status
- `available` - Available for assignment
- `assigned` - Assigned to member
- `maintenance` - Under maintenance

## User Types

- `super admin` - Platform admin
- `owner` - Gym owner (tenant)
- `manager` - Gym manager (sub-user)
- `trainer` - Gym trainer (sub-user)
- `staff` - Gym staff (sub-user)

## Default Credentials

**Email**: user@gmail.com  
**Password**: password

---

## Useful SQL Queries

### Find members expiring soon
```sql
SELECT * FROM members 
WHERE parent_id = ?
AND membership_end_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
AND status = 'active';
```

### Get revenue by month
```sql
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(amount) as revenue
FROM payment_transactions
WHERE status = 'completed'
GROUP BY month
ORDER BY month DESC;
```

### Active members count
```sql
SELECT COUNT(*) FROM members
WHERE parent_id = ?
AND status = 'active'
AND (membership_end_date IS NULL OR membership_end_date > NOW());
```

---

## Debugging Tips

### Enable Query Log
```php
DB::enableQueryLog();
// ... run queries
dd(DB::getQueryLog());
```

### Check Parent ID
```php
dd(parentId(), Auth::user());
```

### Verify Relationships
```php
dd($member->membershipPlan);
dd($member->relationLoaded('membershipPlan'));
```

### Test Scope
```php
dd(Member::active()->toSql());
```

---

## Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| 403 Forbidden | Cross-tenant access | Check `parent_id` matches `parentId()` |
| Column not found | Missing migration | Run `php artisan migrate` |
| Class not found | Autoload issue | Run `composer dump-autoload` |
| Route not defined | Missing route | Check `routes/web.php` |
| View not found | Wrong view path | Check `resources/views/` structure |
| Vite manifest error | Assets not built | Run `npm run build` or `npm run dev` |

---

## Git Workflow

```bash
git checkout -b feature/new-feature
# Make changes
git add .
git commit -m "feat: add new feature"
git push origin feature/new-feature
# Create PR
```

### Commit Conventions
- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation
- `style:` Formatting
- `refactor:` Code restructure
- `test:` Testing
- `chore:` Maintenance

---

This quick reference should speed up your development workflow. For comprehensive details, refer to the main [CODEBASE_KNOWLEDGE_BASE.md](file:///Users/mujahid/.gemini/antigravity/brain/59b6e8bf-098f-40a8-a3e2-e5f1198a8ab0/CODEBASE_KNOWLEDGE_BASE.md).
