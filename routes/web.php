<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes(['verify' => false]); // Disable default email verification

// Login Routes
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

// 2FA Routes
Route::middleware(['auth'])->group(function () {
    Route::get('2fa', [OTPController::class, 'show'])->name('login.2fa');
    Route::post('2fa/verify', [OTPController::class, 'verify'])->name('login.2fa.verify');
    Route::post('2fa/enable', [OTPController::class, 'enable'])->name('2fa.enable');
    Route::post('2fa/disable', [OTPController::class, 'disable'])->name('2fa.disable');
});

// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])->name('email.verification.notice');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'send'])->name('email.verification.send');
    Route::get('email/verify/{code}', [EmailVerificationController::class, 'verify'])->name('email.verify');
});

// Impersonation Routes
Route::impersonate();

// Protected Routes (Requires Authentication + 2FA Verification)
Route::middleware(['auth', 'verify2fa'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'root'])->name('dashboard');

    // Settings Management
    Route::get('settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/{key}', [App\Http\Controllers\SettingsController::class, 'show'])->name('settings.show');

    // User Management
    //    Route::resource('users', App\Http\Controllers\UserController::class);

    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\UserController::class, 'create'])->name('create');

        Route::group(['prefix' => '{user}'], function () {
            Route::get('edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\UserController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\UserController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        });
    });

    // Member Management
//    Route::resource('members', App\Http\Controllers\MemberController::class);

    Route::group(['prefix' => 'members', 'as' => 'members.'], function () {
        Route::get('/', [App\Http\Controllers\MemberController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\MemberController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\MemberController::class, 'create'])->name('create');

        Route::group(['prefix' => '{member}'], function () {
            Route::get('edit', [App\Http\Controllers\MemberController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\MemberController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\MemberController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\MemberController::class, 'destroy'])->name('destroy');
        });
    });
    //    Route::resource('membership-plans', App\Http\Controllers\MembershipPlanController::class);

    Route::group(['prefix' => 'membership-plans', 'as' => 'membership-plans.'], function () {
        Route::get('/', [App\Http\Controllers\MembershipPlanController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\MembershipPlanController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\MembershipPlanController::class, 'create'])->name('create');

        Route::group(['prefix' => '{membershipPlan}'], function () {
            Route::get('edit', [App\Http\Controllers\MembershipPlanController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\MembershipPlanController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\MembershipPlanController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\MembershipPlanController::class, 'destroy'])->name('destroy');
        });
    });

    // Trainer Management
//    Route::resource('trainers', App\Http\Controllers\TrainerController::class);

    Route::group(['prefix' => 'trainers', 'as' => 'trainers.'], function () {
        Route::get('/', [App\Http\Controllers\TrainerController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\TrainerController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\TrainerController::class, 'create'])->name('create');

        Route::group(['prefix' => '{trainer}'], function () {
            Route::get('edit', [App\Http\Controllers\TrainerController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\TrainerController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\TrainerController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\TrainerController::class, 'delete'])->name('destroy');
        });
    });

    // Classes & Scheduling
    //    Route::resource('categories', App\Http\Controllers\CategoryController::class);

    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\CategoryController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\CategoryController::class, 'create'])->name('create');

        Route::group(['prefix' => '{category}'], function () {
            Route::get('edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\CategoryController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\CategoryController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\CategoryController::class, 'delete'])->name('destroy');
        });
    });
    Route::resource('gym-classes', App\Http\Controllers\GymClassController::class);

    // Workouts & Health Tracking
    Route::get('workouts/today', [App\Http\Controllers\WorkoutController::class, 'today'])->name('workouts.today');
    Route::resource('workouts', App\Http\Controllers\WorkoutController::class);
    Route::resource('healths', App\Http\Controllers\HealthController::class);

    // Attendance Tracking
    Route::get('attendances/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendances.report');
    Route::resource('attendances', App\Http\Controllers\AttendanceController::class);

    // Financial Management
    Route::resource('types', App\Http\Controllers\TypeController::class);
//    Route::resource('invoices', App\Http\Controllers\InvoiceController::class);

    Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function () {
        Route::get('/', [App\Http\Controllers\InvoiceController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\InvoiceController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\InvoiceController::class, 'create'])->name('create');

        Route::group(['prefix' => '{invoice}'], function () {
            Route::get('edit', [App\Http\Controllers\InvoiceController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\InvoiceController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\InvoiceController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('destroy');
        });
    });
    Route::post('invoices/{invoice}/payment', [App\Http\Controllers\InvoiceController::class, 'addPayment'])->name('invoices.addPayment');
//    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);

    Route::group(['prefix' => 'expenses', 'as' => 'expenses.'], function () {
        Route::get('/', [App\Http\Controllers\ExpenseController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\ExpenseController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\ExpenseController::class, 'create'])->name('create');

        Route::group(['prefix' => '{expense}'], function () {
            Route::get('edit', [App\Http\Controllers\ExpenseController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\ExpenseController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\ExpenseController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('destroy');
        });
    });

    // Locker, Event, Notice Board
    Route::resource('lockers', App\Http\Controllers\LockerController::class);
    Route::post('lockers/{locker}/assign', [App\Http\Controllers\LockerController::class, 'assign'])->name('lockers.assign');
    Route::resource('events', App\Http\Controllers\EventController::class);
    Route::resource('notice-boards', App\Http\Controllers\NoticeBoardController::class);

    // Email Notification Templates
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{notification}/edit', [App\Http\Controllers\NotificationController::class, 'edit'])->name('notifications.edit');
    Route::put('notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'update'])->name('notifications.update');

    // Additional Modules - Phase 3
    //    Route::resource('products', App\Http\Controllers\ProductController::class);

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\ProductController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\ProductController::class, 'create'])->name('create');

        Route::group(['prefix' => '{product}'], function () {
            Route::get('edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\ProductController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\ProductController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
        });
    });
    Route::resource('contacts', App\Http\Controllers\ContactController::class);

    Route::post('contacts/{contact}/reply', [App\Http\Controllers\ContactController::class, 'reply'])->name('contacts.reply');
    Route::resource('support-tickets', App\Http\Controllers\SupportTicketController::class);
    Route::post('support-tickets/{ticket}/reply', [App\Http\Controllers\SupportTicketController::class, 'addReply'])->name('support-tickets.reply');

    // Subscription & Payment
    Route::get('subscriptions', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{plan}/checkout', [App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::post('subscriptions/{plan}/purchase', [App\Http\Controllers\SubscriptionController::class, 'purchase'])->name('subscriptions.purchase');
    Route::get('subscriptions/{subscription}/success', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscriptions.success');
    Route::get('subscriptions/{subscription}/paypal-success', [App\Http\Controllers\SubscriptionController::class, 'paypalSuccess'])->name('subscriptions.paypal.success');
    Route::get('subscriptions/{subscription}/cancel', [App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::get('my-subscription', [App\Http\Controllers\SubscriptionController::class, 'mySubscription'])->name('subscriptions.mine');
    Route::post('subscriptions/{subscription}/cancel', [App\Http\Controllers\SubscriptionController::class, 'cancelSubscription'])->name('subscriptions.cancel.post');

    // User Profile Management
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');
});

// Payment Webhooks (No auth required)
Route::post('webhooks/stripe', [App\Http\Controllers\PaymentWebhookController::class, 'stripe'])->name('webhooks.stripe');
Route::post('webhooks/paypal', [App\Http\Controllers\PaymentWebhookController::class, 'paypal'])->name('webhooks.paypal');

// Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Super Admin Routes (Platform Administration)
Route::middleware(['auth', 'verify2fa', 'role:super-admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

    // Customer Management
    //    Route::resource('customers', App\Http\Controllers\SuperAdmin\CustomerController::class);

    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'create'])->name('create');

        Route::group(['prefix' => '{customer}'], function () {
            Route::get('edit', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'destroy'])->name('destroy');
        });
    });
    Route::post('customers/{customer}/impersonate', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'impersonate'])->name('customers.impersonate');
    Route::post('customers/{customer}/suspend', [App\Http\Controllers\SuperAdmin\CustomerController::class, 'suspend'])->name('customers.suspend');

    // Platform Subscription Tier Management
    //    Route::resource('platform-subscriptions', App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class);

    Route::group(['prefix' => 'platform-subscriptions', 'as' => 'platform-subscriptions.'], function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'store'])->name('store');
        Route::get('create', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'create'])->name('create');

        Route::group(['prefix' => '{platformSubscription}'], function () {
            Route::get('edit', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'edit'])->name('edit');
            Route::get('show', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'show'])->name('show');
            Route::put('update', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'update'])->name('update');
            Route::post('delete', [App\Http\Controllers\SuperAdmin\PlatformSubscriptionController::class, 'destroy'])->name('destroy');
        });
    });

    // Platform Settings
    Route::get('settings', [App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'update'])->name('settings.update');

    // Platform Analytics
    Route::get('analytics', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/revenue', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'revenue'])->name('analytics.revenue');
    Route::get('analytics/customers', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'customers'])->name('analytics.customers');
    Route::get('analytics/subscriptions', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'subscriptions'])->name('analytics.subscriptions');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
