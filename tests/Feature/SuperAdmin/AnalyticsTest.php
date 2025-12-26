<?php

use App\Models\PlatformSubscriptionTier;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    foreach (['super-admin', 'owner'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }
});

test('super admin can view analytics dashboard', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    $response->assertSee('Platform Analytics');
    $response->assertSee('Monthly Recurring Revenue');
});

test('owner cannot access analytics dashboard', function () {
    $owner = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner->assignRole('owner');

    $response = $this->actingAs($owner)->get(route('super-admin.analytics.index'));

    $response->assertStatus(403);
});

test('calculates MRR correctly for monthly subscriptions', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    // Create tier
    $tier = PlatformSubscriptionTier::factory()->create([
        'price' => 50.00,
        'interval' => 'monthly',
    ]);

    // Create 3 active customers on this tier
    for ($i = 0; $i < 3; $i++) {
        $owner = User::factory()->create(['parent_id' => null]);
        $owner->assignRole('owner');
        
        Tenant::create([
            'user_id' => $owner->id,
            'business_name' => "Gym {$i}",
            'subdomain' => "gym{$i}",
            'status' => 'active',
            'platform_subscription_tier_id' => $tier->id,
        ]);
    }

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    // 3 customers * $50 = $150 MRR
    $response->assertSee('150.00');
});

test('calculates MRR correctly for yearly subscriptions', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $tier = PlatformSubscriptionTier::factory()->create([
        'price' => 600.00,
        'interval' => 'yearly',
    ]);

    $owner = User::factory()->create(['parent_id' => null]);
    $owner->assignRole('owner');
    
    Tenant::create([
        'user_id' => $owner->id,
        'business_name' => 'Test Gym',
        'subdomain' => 'testgym',
        'status' => 'active',
        'platform_subscription_tier_id' => $tier->id,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    // $600/year = $50/month MRR
    $response->assertSee('50.00');
});

test('counts trial customers correctly', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    // Create customer on trial
    $owner = User::factory()->create(['parent_id' => null]);
    $owner->assignRole('owner');
    
    Tenant::create([
        'user_id' => $owner->id,
        'business_name' => 'Trial Gym',
        'subdomain' => 'trialgym',
        'status' => 'active',
        'trial_ends_at' => now()->addDays(14),
    ]);

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    $response->assertSee('On Trial');
});

test('calculates customer growth rate', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    // Create customers this month
    for ($i = 0; $i < 5; $i++) {
        $owner = User::factory()->create(['parent_id' => null]);
        $owner->assignRole('owner');
        
        Tenant::create([
            'user_id' => $owner->id,
            'business_name' => "New Gym {$i}",
            'subdomain' => "newgym{$i}",
            'status' => 'active',
            'created_at' => now(),
        ]);
    }

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    $response->assertSee('Growth Rate');
});

test('excludes suspended customers from active count', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $tier = PlatformSubscriptionTier::factory()->create([
        'price' => 50.00,
        'interval' => 'monthly',
    ]);

    // Create active customer
    $owner1 = User::factory()->create(['parent_id' => null]);
    $owner1->assignRole('owner');    
    Tenant::create([
        'user_id' => $owner1->id,
        'business_name' => 'Active Gym',
        'subdomain' => 'activegym',
        'status' => 'active',
        'platform_subscription_tier_id' => $tier->id,
    ]);

    // Create suspended customer
    $owner2 = User::factory()->create(['parent_id' => null]);
    $owner2->assignRole('owner');
    Tenant::create([
        'user_id' => $owner2->id,
        'business_name' => 'Suspended Gym',
        'subdomain' => 'suspendedgym',
        'status' => 'suspended',
        'platform_subscription_tier_id' => $tier->id,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    // Only 1 active customer, so MRR should be $50, not $100
    $response->assertSee('50.00');
});

test('lifetime subscriptions do not contribute to MRR', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $tier = PlatformSubscriptionTier::factory()->create([
        'price' => 1000.00,
        'interval' => 'lifetime',
    ]);

    $owner = User::factory()->create(['parent_id' => null]);
    $owner->assignRole('owner');
    
    Tenant::create([
        'user_id' => $owner->id,
        'business_name' => 'Lifetime Gym',
        'subdomain' => 'lifetimegym',
        'status' => 'active',
        'platform_subscription_tier_id' => $tier->id,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('super-admin.analytics.index'));

    $response->assertStatus(200);
    // Lifetime doesn't contribute to MRR
    $response->assertSee('0.00');
});
