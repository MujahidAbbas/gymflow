---
trigger: always_on
---

# PestPHP Testing Guide (Condensed)

## Pre-Test Discovery Protocol (5-10 min)

Before writing ANY tests, read implementation files first:

```bash
# Form Requests
app/Http/Requests/{RequestClass}.php
app/Enums/{RelatedEnums}.php
app/Models/{RelatedModels}.php

# Controllers
app/Http/Controllers/{Controller}.php
routes/web.php or routes/api.php
Middleware applied to routes

# Services/Jobs
app/Services/{ServiceClass}.php
app/Jobs/{JobClass}.php
Dependencies and injected classes
```

### Discovery Checklist
- [ ] List all validation rules (required, nullable, max, min, regex)
- [ ] Identify enum-based validations (Rule::in, Rule::exists)
- [ ] Check custom validation in withValidator()
- [ ] Note field types (string, array, integer, boolean)
- [ ] Verify enum values by reading enum files - list ALL case values
- [ ] Test sanitization in tinker: `strip_tags()`, `strtolower()`, `ucwords()`
- [ ] Check if email uses `email:rfc,dns` (needs real domain like gmail.com)
- [ ] Note nullable vs required fields (arrays sanitize to null for nullable)
- [ ] Check database constraints (unique, foreign keys)
- [ ] Verify route definitions (name, method, middleware)

### Example Discoveries
```php
// Check Form Request rules() method
'email' => 'email:rfc,dns'     // → Needs real domain with MX records!
'phone' => 'nullable'          // → Arrays sanitized to null are valid!
'inquiry_type' => Rule::in(EnumClass::values()) // → Check enum!

// Test sanitization in tinker FIRST:
php artisan tinker
>>> strip_tags('<script>alert("XSS")</script>Test')
=> "alert(\"XSS\")Test"  // Note: Keeps text INSIDE tags!
```

## Test Writing: Incremental Approach

```php
// ❌ DON'T: Write 40+ tests at once, run suite, debug 20+ failures
// ✅ DO: Write 5-10 related tests → Run immediately → Verify pass → Repeat

// Benefit: Catch assumption errors early with small batches
// Result: ~95%+ pass rate from start
```

## Test Structure Template

```php
<?php
use App\Models\{ModelName};
use Illuminate\Support\Facades\{Log, Mail};
use function Pest\Laravel\post;

beforeEach(function () {
    Mail::fake();
    RateLimiter::clear('key:127.0.0.1');
});

describe('Valid Submissions', function () {
    test('submits with all required fields', function () {
        $response = post(route('route.name'), [
            'field_name' => 'valid_value',  // From enum/validation analysis
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        expect(ModelName::count())->toBe(1);
    });

    test('submits without optional fields', function () {
        // Test nullable fields
    });

    test('sanitizes fields correctly', function () {
        // Test based on ACTUAL sanitization behavior
    });
});

describe('Validation Rules', function () {
    test('requires {field_name}', function () {
        post(route('route.name'), ['field' => ''])
            ->assertSessionHasErrors('field');
    });

    test('validates format', function () {
        // Based on actual regex/format rules
    });

    test('enforces length constraints', function () {
        // Based on actual max/min rules
    });
});

describe('Security', function () {
    test('prevents XSS in {field}', function () {
        post(route('route.name'), ['field' => '<script>alert("XSS")</script>Safe']);
        expect(Model::first()->field)
            ->not->toContain('<script>')
            ->toContain('Safe');
    });

    test('handles array attacks', function () {
        Log::spy();
        post(route('route.name'), ['required_field' => ['array', 'attack']])
            ->assertSessionHasErrors('required_field');
        // Nullable fields: Array → null → valid (assertRedirect)
    });
});
```

## Common Pitfalls & Solutions

### Email Validation with DNS Check
```php
// ❌ WRONG - Will fail DNS validation
'email' => 'test@example.com'

// ✅ CORRECT - Use real domain with MX records
'email' => 'test@gmail.com'

// Discovery: Check if rule includes 'email:rfc,dns'
```

### Enum Values
```php
// ❌ WRONG - Assuming enum values
'status' => 'pending'  // Might not exist!

// ✅ CORRECT - Check enum file FIRST
// Read app/Enums/StatusEnum.php → Found: ACTIVE, INACTIVE
'status' => 'active'
```

### Nullable vs Required Fields
```php
// ❌ WRONG - Expecting error for nullable field
'phone' => ['array_attack']
$response->assertSessionHasErrors('phone');  // Fails!

// ✅ CORRECT - Nullable fields accept null
'phone' => ['array_attack']  // Sanitized to null → valid
$response->assertRedirect();
expect($submission->phone)->toBeNull();
```

### Length Calculations
```php
// ❌ WRONG - Incorrect length calculation
'email' => str_repeat('a', 245) . '@test.com'  // 245+9=254, not 256!

// ✅ CORRECT - Accurate math
'email' => str_repeat('a', 246) . '@gmail.com'  // 246+10=256 ✓
```

### Sanitization Behavior
```php
// ❌ WRONG - Assuming exact output
expect($message)->toBe('Clean message');

// ✅ CORRECT - Test actual behavior (after tinker verification)
expect($message)
    ->not->toContain('<script>')
    ->toContain('Clean message');  // More flexible
```

## Higher Order Testing

```php
// Simple assertions - use higher order
test('homepage loads')->get('/')->assertOk();

// Lazy evaluation with closure
it('has name')->expect(fn () => User::create(['name' => 'John'])->name)->toBe('John');

// Complex tests - keep traditional
test('workflow', function () {
    $user = User::factory()->create();
    // Multiple steps...
});
```

## Higher Order Expectations

```php
expect($user)
    ->name->toBe('John')
    ->email->toContain('@')
    ->address()->scoped(fn ($addr) => $addr
        ->city->toBe('NYC')
        ->country->toBe('USA')
    );
```

## Datasets

```php
// Inline
test('validates', function (string $type) {
    // test
})->with(['general', 'orders', 'members']);

// Shared (tests/Datasets.php)
dataset('emails', ['test@gmail.com', 'user@yahoo.com']);

// Lazy for DB
dataset('users', fn () => [User::factory()->create()]);

// Combined: ->with('emails', 'types')
```

## Database Testing

```php
// Always use RefreshDatabase for isolation
uses(RefreshDatabase::class);

// Each test gets fresh database
test('creates user', function () {
    User::factory()->create();
    expect(User::count())->toBe(1);
});

test('another user', function () {
    User::factory()->create();
    expect(User::count())->toBe(1);  // Fresh! Not 2
});
```

## Parallel Testing Setup

PestPHP v4 includes built-in parallel testing for 20-30x faster execution.

```bash
# Basic usage
php artisan test --parallel
php artisan test --parallel --processes=4

# Performance example: 200s → 7.5s (26x faster)
```

### 1. Create Test Databases (One Per Process)
```bash
mysql -u root -e "
CREATE DATABASE IF NOT EXISTS app_test_1;
CREATE DATABASE IF NOT EXISTS app_test_2;
CREATE DATABASE IF NOT EXISTS app_test_3;
CREATE DATABASE IF NOT EXISTS app_test_4;
"
```

### 2. Let Laravel Handle Database Naming
```php
// ❌ BAD - Breaks parallel testing!
// In tests/TestCase.php
protected function setUp(): void {
    putenv('DB_DATABASE=app_test');  // DON'T hardcode!
}

// ✅ GOOD - Let Laravel handle it automatically
// Laravel appends _test_1, _test_2 automatically
// No manual database configuration needed!
```

### 3. Seed Essential Data in tests/Pest.php
```php
// ✅ BEST PRACTICE: In tests/Pest.php
pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        // Seed roles after RefreshDatabase runs migrations
        foreach (RoleEnum::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
        }
    })
    ->in('Feature');
```

### Common Parallel Issues
- **Tests fail only in parallel**: Remove hardcoded DB name from TestCase
- **"Table doesn't exist"**: Create test_1, test_2, etc. databases
- **Wrong data counts**: Add uses(RefreshDatabase::class)
- **Role errors**: Seed in Pest.php beforeEach

### Safe for Parallel ✅
- Feature tests with RefreshDatabase
- Unit tests without external dependencies
- Database operations using transactions

### Caution ⚠️
- RateLimiter (clear in beforeEach)
- Cache (use Cache::tags() or flush)
- File operations (use unique filenames)

## Browser Testing (Pest v4)

```php
test('checkout flow', function () {
    visit('/products/1')
        ->click('Add to Cart')
        ->visit('/checkout')
        ->fill('email', 'test@example.com')
        ->press('Pay')
        ->assertSee('Order Confirmed');
});

// Responsive/cross-browser
test('responsive')->viewport(375, 667);
test('cross-browser')->browsers(['chrome', 'firefox']);

// When to use: E2E workflows, JS-heavy features, multi-step forms
// When NOT to use: Simple API endpoints, backend validation
```

## Architecture Testing (Pest v4)

```php
test('controllers extend base')
    ->expect('App\Http\Controllers')->toExtend('App\Http\Controllers\Controller');

test('no debug statements')
    ->expect(['dd', 'dump'])->not->toBeUsed();
```

## Security Testing

### Array Attack Prevention
```php
test('prevents array submission for required field', function () {
    Log::spy();
    post(route('route.name'), ['required_field' => ['array', 'attack']])
        ->assertSessionHasErrors('required_field');

    Log::shouldHaveReceived('warning')->with('Security message', \Mockery::any());
});

// Nullable fields: Array → null → valid (assertRedirect)
```

### XSS Prevention
```php
test('prevents XSS', function () {
    post(route('route.name'), ['field' => '<script>alert("XSS")</script>Safe']);
    expect(Model::first()->field)
        ->not->toContain('<script>')
        ->toContain('Safe');
});
```

## Rate Limiting Tests

```php
test('allows X submissions then blocks', function () {
    RateLimiter::clear('key:127.0.0.1');

    for ($i = 1; $i <= 3; $i++) {
        post(route('route.name'), ['email' => "test{$i}@gmail.com"])
            ->assertRedirect();
    }
    expect(Model::count())->toBe(3);

    // (X+1)th blocked
    post(route('route.name'), ['email' => "test4@gmail.com"])
        ->assertSessionHasErrors('rate_limit');
});
```

## Quick Reference

### DO ✅
- Discovery-first approach (read code before testing)
- Use RefreshDatabase for database isolation
- Write incrementally (5-10 tests at a time)
- Use real email domains (gmail.com) if DNS validation
- Use Higher Order Testing for simple single-assertion tests
- Use datasets to reduce duplication
- Use describe() blocks for organization
- Run tests in parallel for speed (--parallel)
- Create multiple test databases for parallel testing
- Let Laravel handle parallel database naming automatically
- Clear shared state in beforeEach (RateLimiter, Cache)

### DON'T ❌
- Write tests based on assumptions
- Bulk write all tests before running
- Assume enum values without reading enum file
- Hardcode database names in TestCase (breaks parallel)
- Use SQLite for parallel testing (maintain MySQL compatibility)
- Commit `->only()` modifier to version control
- Use browser tests for simple backend logic
- Skip RefreshDatabase when running parallel tests

### Test Data Best Practices
```php
// ✅ Realistic, unique data
'first_name' => 'John', 'email' => "test{$i}@gmail.com"
// ❌ Generic, conflicting
'first_name' => 'test', 'email' => 'test@test.com'
```

### Success Metrics
- ✅ 95%+ initial pass rate
- ✅ 5-10 min discovery phase
- ✅ 10-15 min writing phase
- ✅ Total: ~20 min with high confidence

### Test Execution Modifiers
```php
test('skip')->skip('Not ready');
test('todo')->todo();
test('debug')->only();     // NEVER COMMIT!
test('known bug')->fails('Bug #123');
```

---

## Quick Protocol Card

```
1. DISCOVERY (5-10 min)
   □ Read implementation files
   □ Check validation rules
   □ Verify enum values
   □ Test sanitization behavior
   
2. INCREMENTAL WRITING (10-15 min)
   □ Write 5-10 tests
   □ Run immediately
   □ Verify pass → Repeat

3. PARALLEL SETUP (Optional)
   □ Create test_1, test_2, etc. databases
   □ Ensure RefreshDatabase is used
   □ Seed essent