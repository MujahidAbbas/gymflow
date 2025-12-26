# Helper Functions Reference
## FitHub SaaS - Complete Helper Library Documentation

**Version**: 1.0  
**Date**: 2025-11-28  
**File**: `app/Helper/helper.php`  
**Total Lines**: 2,227  
**Total Functions**: 25+

---

## Overview

The helper file contains critical utility functions used throughout the application. These functions handle multi-tenancy, formatting, email notifications, subscription management, and more.

### Function Categories
1. **Multi-Tenancy** - parentId()
2. **Settings Management** - settings(), settingsById(), getSettingsValByName()
3. **Formatting** - dateFormat(), timeFormat(), priceFormat()
4. **Auto-ID Generation** - Prefix generators for various entities
5. **Email & Notifications** - Email sending and template management
6. **Subscription** - Subscription assignment logic
7. **Permissions** - Default permission assignment

---

## Multi-Tenancy Functions

### parentId()
**Purpose**: Get the current user's parent ID for multi-tenancy isolation

**Returns**: `int` - The parent user ID

**Usage**:
```php
$trainers = User::where('parent_id', parentId())
    ->where('type', 'trainer')
    ->get();
```

**Implementation**:
```php
function parentId()
{
    if (Auth::user()->type == 'owner' || Auth::user()->type == 'super admin') {
        return Auth::user()->id;
    } else {
        return Auth::user()->parent_id;
    }
}
```

**Critical**: This function MUST be used in every query that accesses tenant-specific data.

---

## Settings Management Functions

### settings($userId = null)
**Purpose**: Get all settings for a specific user (or current user)

**Parameters**:
- `$userId` (int, optional) - User ID to get settings for

**Returns**: `array` - Associative array of settings

**Usage**:
```php
$settings = settings();
$logoPath = $settings['company_logo'];
$appName = $settings['app_name'];
$recaptchaEnabled = $settings['recaptcha_enable'];
```

**Implementation**:
```php
function settings($userId = null)
{
    if (!$userId) {
        $userId = parentId();
    }
    
    $data = DB::table('settings')
        ->where('parent_id', '=', $userId)
        ->get();
    
    $settings = [];
    foreach ($data as $row) {
        $settings[$row->name] = $row->value;
    }
    
    // Fill missing keys with defaults
    $settingsKeys = settingsKeys();
    foreach ($settingsKeys as $key => $value) {
        if (!isset($settings[$key])) {
            $settings[$key] = $value;
        }
    }
    
    return $settings;
}
```

---

### settingsById($id)
**Purpose**: Get settings for a specific user by ID

**Parameters**:
- `$id` (int) - User ID

**Returns**: `array` - Settings array

**Usage**:
```php
$ownerSettings = settingsById(5);
```

---

### getSettingsValByName($key)
**Purpose**: Get a single setting value by key name

**Parameters**:
- `$key` (string) - Setting name

**Returns**: `mixed` - Setting value

**Usage**:
```php
$currency = getSettingsValByName('CURRENCY');
// Returns: 'USD'
```

---

### settingsKeys()
**Purpose**: Define all default setting keys with default values

**Returns**: `array` - All setting keys with defaults

**Settings Categories**:
```php
[
    // General
    'app_name' => '',
    'theme_mode' => 'light',
    'theme_color' => 'theme-1',
    'sidebar_mode' => 'light',
    'layout' => 'ltr',
    
    // SMTP
    'mail_driver' => '',
    'mail_host' => '',
    'mail_port' => '',
    'mail_username' => '',
    'mail_password' => '',
    'mail_encryption' => '',
    'mail_from_address' => '',
    'mail_from_name' => '',
    
    // Payment
    'CURRENCY' => 'USD',
    'CURRENCY_SYMBOL' => '$',
    'stripe_payment' => 'off',
    'STRIPE_KEY' => '',
    'STRIPE_SECRET' => '',
    'paypal_payment' => 'off',
    'PAYPAL_MODE' => 'sandbox',
    'PAYPAL_CLIENT_ID' => '',
    'PAYPAL_SECRET' => '',
    
    // SEO
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',
    'meta_seo_image' => '',
    
    // Company
    'company_name' => '',
    'company_email' => '',
    'company_phone' => '',
    'company_address' => '',
    'company_logo' => '',
    
    // Security
    'recaptcha_enable' => 'off',
    'recaptcha_key' => '',
    'recaptcha_secret' => '',
    'owner_email_verification' => 'off',
    
    // ... 50+ more keys
]
```

---

### subscriptionPaymentSettings()
**Purpose**: Get payment gateway settings for subscription payments

**Returns**: `array` - Payment configuration

**Usage**:
```php
$paymentSettings = subscriptionPaymentSettings();
$stripeKey = $paymentSettings['STRIPE_KEY'];
```

---

## Formatting Functions

### dateFormat($date)
**Purpose**: Format date according to user's settings

**Parameters**:
- `$date` (string|Carbon) - Date to format

**Returns**: `string` - Formatted date

**Usage**:
```php
$formattedDate = dateFormat('2025-01-15');
// Returns: '15 Jan, 2025' (based on setting)
```

**Implementation**:
```php
function dateFormat($date)
{
    $setting = settings();
    $format = $setting['date_format'] ?? 'M j, Y';
    
    return \Carbon\Carbon::parse($date)->format($format);
}
```

---

### timeFormat($time)
**Purpose**: Format time according to user's settings

**Parameters**:
- `$time` (string) - Time to format

**Returns**: `string` - Formatted time

**Usage**:
```php
$formattedTime = timeFormat('14:30');
// Returns: '2:30 PM' (12-hour) or '14:30' (24-hour)
```

---

### priceFormat($price)
**Purpose**: Format price with currency symbol

**Parameters**:
- `$price` (float) - Price amount

**Returns**: `string` - Formatted price

**Usage**:
```php
$formattedPrice = priceFormat(1999.99);
// Returns: '$1,999.99'
```

**Implementation**:
```php
function priceFormat($price)
{
    $settings = settings();
    $symbol = $settings['CURRENCY_SYMBOL'] ?? '$';
    
    return $symbol . number_format($price, 2);
}
```

---

## Auto-ID Generation Functions

### trainerPrefix()
**Purpose**: Generate next trainer ID

**Returns**: `string` - Trainer ID (e.g., '#TRNR-0001')

**Usage**:
```php
$trainerId = trainerPrefix();
// Returns: '#TRNR-0005'
```

**Implementation**:
```php
function trainerPrefix()
{
    $lastTrainer = TrainerDetail::where('parent_id', parentId())
        ->orderBy('id', 'desc')
        ->first();
    
    if ($lastTrainer) {
        $number = (int) substr($lastTrainer->trainer_id, 6) + 1;
    } else {
        $number = 1;
    }
    
    return '#TRNR-' . str_pad($number, 4, '0', STR_PAD_LEFT);
}
```

---

### traineePrefix()
**Purpose**: Generate next trainee ID

**Returns**: `string` - Trainee ID (e.g., '#TRNE-0001')

**Usage**:
```php
$traineeId = traineePrefix();
```

---

### invoicePrefix()
**Purpose**: Generate next invoice number

**Returns**: `string` - Invoice number (e.g., '#INV-0001')

**Usage**:
```php
$invoiceNumber = invoicePrefix();
```

---

### expensePrefix()
**Purpose**: Generate next expense number

**Returns**: `string` - Expense number (e.g., '#EXP-0001')

---

### lockerPrefix()
**Purpose**: Generate next locker number

**Returns**: `string` - Locker number (e.g., '#LO-0001')

---

## Email & Notification Functions

### commonEmailSend($to, $data)
**Purpose**: Send email with error handling

**Parameters**:
- `$to` (string) - Recipient email
- `$data` (array) - Email data

**Data Array**:
```php
[
    'subject' => 'Email Subject',
    'message' => 'Email content (HTML)',
    'module' => 'trainer_create',
    'logo' => '/path/to/logo.png',
]
```

**Returns**: `array` - Status and message

**Usage**:
```php
$result = commonEmailSend('user@example.com', [
    'subject' => 'Welcome!',
    'message' => '<p>Welcome to FitHub</p>',
    'module' => 'user_create',
    'logo' => settings()['company_logo'],
]);

if ($result['status'] == 'success') {
    // Email sent
} else {
    // Handle error: $result['message']
}
```

**Implementation**:
```php
function commonEmailSend($to, $data)
{
    try {
        Mail::to($to)->send(new \App\Mail\Common($data));
        return ['status' => 'success', 'message' => 'Email sent'];
    } catch (\Exception $e) {
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}
```

---

### MessageReplace($notification, $entityId)
**Purpose**: Replace shortcodes in email templates with actual data

**Parameters**:
- `$notification` (Notification) - Email template
- `$entityId` (int) - Entity ID (trainer, trainee, etc.)

**Returns**: `array` - Processed subject and message

**Shortcodes**:
```php
'{app_name}'       - Application name
'{trainer_name}'   - Trainer's name
'{trainer_email}'  - Trainer's email
'{trainee_name}'   - Trainee's name
'{class_name}'     - Class title
'{invoice_number}' - Invoice ID
'{amount}'         - Payment amount
// ... and more
```

**Usage**:
```php
$notification = Notification::where('module', 'trainer_create')->first();
$processedEmail = MessageReplace($notification, $trainerId);

$subject = $processedEmail['subject'];
$message = $processedEmail['message'];
```

---

### defaultTemplate($module)
**Purpose**: Create default email template for a module

**Parameters**:
- `$module` (string) - Module name

**Modules**:
- `user_create`
- `trainer_create`
- `trainee_create`
- `trainer_assign`
- `new_classes`
- `workout_create`
- `health_update`
- `attendance_create`
- `invoice_create`
- `locker_assign`

**Usage**:
```php
defaultTemplate('trainer_create');
// Creates default email template in notifications table
```

**Template Structure**:
```php
[
    'module' => 'trainer_create',
    'subject' => 'Welcome to {app_name}',
    'message' => '<h1>Hello {trainer_name}</h1><p>Your account has been created...</p>',
    'enabled_email' => 1,
    'enabled_web' => 0,
    'parent_id' => parentId(),
]
```

---

### defaultTrainerCreate($trainerId)
**Purpose**: Send welcome email to newly created trainer

**Parameters**:
- `$trainerId` (int) - Trainer ID

**Usage**:
```php
// After creating trainer
defaultTrainerCreate($trainer->id);
```

**Sends**: Welcome email with login credentials and gym details

---

### defaultTraineeCreate($traineeId)
**Purpose**: Send welcome email to newly created trainee

**Parameters**:
- `$traineeId` (int) - Trainee ID

**Usage**:
```php
// After creating trainee
defaultTraineeCreate($trainee->id);
```

---

## Subscription Functions

### assignSubscription($subscriptionId)
**Purpose**: Activate subscription for current user and enforce limits

**Parameters**:
- `$subscriptionId` (int) - Subscription plan ID

**Returns**: `array` - Success status and error message (if any)

**Usage**:
```php
$result = assignSubscription(5);

if ($result['is_success']) {
    // Subscription activated
} else {
    // Error: $result['error']
}
```

**Process**:
1. Find subscription plan
2. Update user's subscription field
3. Calculate expiry date based on interval
4. Save user
5. Enforce user limits (activate/deactivate users)
6. Return success/error

**Implementation**:
```php
function assignSubscription($id)
{
    $subscription = Subscription::find($id);
    
    if (!$subscription) {
        return ['is_success' => false, 'error' => 'Subscription not found'];
    }
    
    $user = Auth::user();
    $user->subscription = $subscription->id;
    
    // Set expiry date
    switch ($subscription->interval) {
        case 'Monthly':
            $user->subscription_expire_date = Carbon::now()->addMonths(1);
            break;
        case 'Quarterly':
            $user->subscription_expire_date = Carbon::now()->addMonths(3);
            break;
        case 'Yearly':
            $user->subscription_expire_date = Carbon::now()->addYear();
            break;
        case 'Unlimited':
            $user->subscription_expire_date = null;
            break;
    }
    
    $user->save();
    
    // Enforce limits
    if ($subscription->user_limit > 0) {
        $totalUsers = User::where('parent_id', $user->id)->count();
        
        if ($totalUsers > $subscription->user_limit) {
            // Deactivate excess users
            $excess = $totalUsers - $subscription->user_limit;
            $usersToDeactivate = User::where('parent_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->skip($subscription->user_limit)
                ->take($excess)
                ->get();
            
            foreach ($usersToDeactivate as $u) {
                $u->is_active = 0;
                $u->save();
            }
        }
    }
    
    return ['is_success' => true];
}
```

---

### assignManuallySubscription($subscriptionId, $userId)
**Purpose**: Manually assign subscription to a user (super admin only)

**Parameters**:
- `$subscriptionId` (int) - Subscription plan ID
- `$userId` (int) - Target user ID

**Returns**: `array` - Success status

**Usage**:
```php
// Super admin assigns subscription to owner
assignManuallySubscription(3, 10);
```

---

## Permission Functions

### defaultPermission($userType)
**Purpose**: Get default permissions for a user type

**Parameters**:
- `$userType` (string) - 'trainer' or 'trainee'

**Returns**: `array` - Permission names

**Usage**:
```php
$permissions = defaultPermission('trainer');
// Returns: ['manage contacts', 'manage notes', 'show trainees', ...]
```

**Trainer Permissions**:
```php
[
    'manage contacts',
    'manage notes',
    'show trainees',
    'manage classes',
    'manage workouts',
    'manage today workout',
    'manage health update',
    'manage attendance',
]
```

**Trainee Permissions**:
```php
[
    'manage contacts',
    'manage notes',
    'show trainers',
    'show classes',
    'show workouts',
    'manage today workout',
    'show health update',
    'show attendance',
]
```

---

## Database Helper Functions

### smtpDetail($userId)
**Purpose**: Get SMTP configuration for a specific user

**Parameters**:
- `$userId` (int) - User ID

**Returns**: `array` - SMTP settings

**Usage**:
```php
$smtp = smtpDetail(5);
config([
    'mail.driver' => $smtp['mail_driver'],
    'mail.host' => $smtp['mail_host'],
    // ...
]);
```

---

## User Logging Functions

### userLoggedHistory()
**Purpose**: Record user login event with device/location data

**Parameters**: None (uses current authenticated user)

**Returns**: `void`

**Usage**:
```php
// After successful login
userLoggedHistory();
```

**Captured Data**:
- IP address
- Browser name and version
- Operating System
- Device type (mobile/tablet/desktop)
- Country (from IP lookup)
- Referrer URL
- Timestamp

---

## Utility Functions

### getBrowser($userAgent)
**Purpose**: Extract browser name from User-Agent string

**Parameters**:
- `$userAgent` (string) - User-Agent header

**Returns**: `string` - Browser name

---

### getOS($userAgent)
**Purpose**: Extract OS from User-Agent string

**Parameters**:
- `$userAgent` (string) - User-Agent header

**Returns**: `string` - Operating system name

---

### getLocationFromIP($ip)
**Purpose**: Get country from IP address

**Parameters**:
- `$ip` (string) - IP address

**Returns**: `string` - Country name

**Implementation**: Uses IP geolocation API

---

## Important Notes

### 1. Helper Auto-Loading
Helpers are auto-loaded via `composer.json`:
```json
"autoload": {
    "files": [
        "app/Helper/helper.php"
    ]
}
```

After updating helpers:
```bash
composer dump-autoload
```

### 2. Multi-Tenancy Critical Functions
These functions MUST be used correctly:
- `parentId()` - In every tenant-scoped query
- `settings()` - Get tenant settings
- Auto-ID generators - Ensure uniqueness per tenant

### 3. Error Handling
Email functions return status arrays:
```php
['status' => 'success|error', 'message' => '...']
```

Always check status before assuming success.

### 4. Subscription Logic
`assignSubscription()` is CRITICAL:
- Handles expiry calculation
- Enforces ALL user limits
- Deactivates excess users
- Must be called after any subscription payment

---

## Quick Reference

### Most Used Functions
```php
// Multi-tenancy
$parentId = parentId();

// Settings
$settings = settings();
$value = getSettingsValByName('key');

// Formatting
$date = dateFormat($rawDate);
$price = priceFormat($amount);

// Auto-IDs
$trainerId = trainerPrefix();
$invoiceId = invoicePrefix();

// Email
$result = commonEmailSend($email, $data);

// Subscription
assignSubscription($planId);
```

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-28  
**Total Functions Documented**: 25+  
**File Location**: app/Helper/helper.php
