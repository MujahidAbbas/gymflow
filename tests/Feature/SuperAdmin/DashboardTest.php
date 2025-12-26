<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    foreach (['super-admin', 'owner', 'manager'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }
});

test('super admin can view dashboard', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $response = $this->actingAs($superAdmin)->get(route('super-admin.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Platform Dashboard');
});

test('dashboard shows correct metrics', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    // Create some test data
    $owner1 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner1->assignRole('owner');
    Tenant::create([
        'user_id' => $owner1->id,
        'business_name' => 'Gym 1',
        'status' => 'active',
        'max_members' => 100,
        'max_trainers' => 10,
    ]);

    $owner2 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner2->assignRole('owner');
    Tenant::create([
        'user_id' => $owner2->id,
        'business_name' => 'Gym 2',
        'status' => 'active',
        'max_members' => 50,
        'max_trainers' => 5,
        'trial_ends_at' => now()->addDays(7),
    ]);

    $response = $this->actingAs($superAdmin)->get(route('super-admin.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Gym 1');
    $response->assertSee('Gym 2');
});

test('owner cannot access super admin dashboard', function () {
    $owner = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner->assignRole('owner');

    $response = $this->actingAs($owner)->get(route('super-admin.dashboard'));

    $response->assertStatus(403);
});

test('manager cannot access super admin dashboard', function () {
    $owner = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner->assignRole('owner');

    $manager = User::factory()->create(['parent_id' => $owner->id, 'email_verified_at' => now()]);
    $manager->assignRole('manager');

    $response = $this->actingAs($manager)->get(route('super-admin.dashboard'));

    $response->assertStatus(403);
});
