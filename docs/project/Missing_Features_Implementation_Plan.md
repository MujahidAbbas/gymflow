# Implementation Plan: Missing Features for FitHub SaaS
**Goal**: Complete remaining 15% of features to reach 100% implementation  
**Current Status**: 85% Complete  
**Target**: Production-Ready Status  
**Total Estimated Time**: 6-8 weeks

---

## Priority Legend
- 🔥 **CRITICAL** - Blocks production deployment
- ⚡ **HIGH** - Required for MVP
- 📊 **MEDIUM** - Important but can be deferred
- 📝 **LOW** - Nice to have

---

## Phase 1: Security Hardening (Week 1) 🔥 CRITICAL

**Priority**: Implement FIRST - blocks production deployment  
**Estimated Time**: 5-7 days  
**Dependencies**: None

### 1.1 XSS Protection Middleware

**File**: `app/Http/Middleware/XSS.php`

```php
<?php
namespace App\Http\Middleware;

use Closure;

class XSS
{
    protected $except = [
        'api/*', // Exclude API routes if needed
    ];

    public function handle($request, Closure $next)
    {
        $input = $request->all();
        $filtered = $this->clean($input);
        $request->merge($filtered);
        
        return $next($request);
    }

    private function clean($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'clean'], $data);
        }
        
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
```

**Register in** `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\XSS::class);
})
```

**Time**: 1 day

---

### 1.2 reCAPTCHA Integration

**Step 1**: Install package
```bash
composer require anhskohbo/no-captcha
```

**Step 2**: Create `config/captcha.php`
```php
<?php
return [
    'secret' => env('RECAPTCHA_SECRET_KEY'),
    'sitekey' => env('RECAPTCHA_SITE_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];
```

**Step 3**: Add to `.env`
```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

**Step 4**: Add to login form (`resources/views/auth/login.blade.php`)
```blade
<div class="mb-3">
    {!! NoCaptcha::renderJs() !!}
    {!! NoCaptcha::display() !!}
    @error('g-recaptcha-response')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
```

**Time**: 2 days

---

### 1.3 Rate Limiting

Add to LoginController:

```php
use Illuminate\Support\Facades\RateLimiter;

public function login(Request $request)
{
    $key = $request->ip();
    
    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        throw ValidationException::withMessages([
            'email' => ["Too many attempts. Retry in {$seconds}s."],
        ]);
    }
    
    // Login logic...
    
    RateLimiter::hit($key, 60);
}
```

**Time**: 1 day

---

## Phase 2: Email Notifications (Week 2) ⚡ HIGH

### 2.1 NotificationController with CRUD
**Time**: 2 days

### 2.2 Default Email Templates Seeder
- 10 templates (user_create, trainer_create, etc.)
**Time**: 1 day

### 2.3 Helper Functions
- MessageReplace() - Shortcode replacement
- commonEmailSend() - Email sending
**Time**: 1 day

### 2.4 Integration
- Hook emails into all modules
**Time**: 2 days

---

## Phase 3: Additional Modules (Week 3-4) 📊 MEDIUM

### 3.1 Nutrition Schedule
- Migration, Model, Controller, Views
**Time**: 2 days

### 3.2 Product Management
- 3 tables: products, product_bookings, product_booking_items
- Stock tracking, inventory management
**Time**: 3-4 days

### 3.3 Contact Management
- Simple contact form module
**Time**: 1 day

### 3.4 Support Ticket System
- Supports table + support_replies table
- Priority system, staff assignment
**Time**: 3 days

### 3.5 Event Types
- Categorization for events
**Time**: 0.5 day

---

## Phase 4: CMS (Week 5) 📊 MEDIUM

### 4.1 FAQ System
**Time**: 2 days

### 4.2 HomePage CMS
**Time**: 2 days

### 4.3 AuthPage Customization
**Time**: 1 day

---

## Phase 5: Coupon System (Week 6) 📊 MEDIUM

- Coupons table + coupon_histories table
- Validation, usage tracking
**Time**: 3 days

---

## Phase 6: Multi-Language (Week 7) 📝 LOW

- 13 language files
- Language switcher
- RTL support
**Time**: 5 days

---

## Phase 7: Helper Functions (Week 8) 📊 MEDIUM

Add 17+ missing functions:
- trainerPrefix(), traineePrefix(), etc.
- dateFormat(), timeFormat(), priceFormat()
- assignSubscription(), calculateExpiryDate()
**Time**: 2 days

---

## Critical Path to Production (6 weeks)

**Week 1**: Security Hardening 🔥  
**Week 2**: Email Notifications ⚡  
**Week 3**: Support Tickets + Contacts  
**Week 4**: Product Management + Nutrition  
**Week 5**: Testing & QA  
**Week 6**: Deployment prep

**Optional** (Weeks 7-8): Coupon, CMS, Multi-Language

---

## Next Steps

1. Start with Security Hardening (XSS, reCAPTCHA, Rate Limiting)
2. Implement Email Notification System
3. Add Support Ticket System
4. Complete remaining modules
5. Comprehensive testing
6. Production deployment

---

*Estimated time to 100%: 6-8 weeks*
