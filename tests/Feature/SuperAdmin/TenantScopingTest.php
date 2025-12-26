<?php

use App\Models\Member;
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

test('owner can only see their own members', function () {
    $owner1 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner1->assignRole('owner');

    $owner2 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner2->assignRole('owner');

    // Create members for owner1
    $member1 = Member::create([
        'parent_id' => $owner1->id,
        'name' => 'Member 1',
        'email' => 'member1@gym1.com',
        'phone' => '1234567890',
        'status' => 'active',
    ]);

    // Create members for owner2
    $member2 = Member::create([
        'parent_id' => $owner2->id,
        'name' => 'Member 2',
        'email' => 'member2@gym2.com',
        'phone' => '0987654321',
        'status' => 'active',
    ]);

    // Login as owner1 - should only see member1
    $this->actingAs($owner1);
    $members = Member::all();
    
    expect($members->count())->toBe(1);
    expect($members->first()->id)->toBe($member1->id);
    expect($members->first()->name)->toBe('Member 1');
});

test('owner cannot see other customer members', function () {
    $owner1 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner1->assignRole('owner');

    $owner2 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner2->assignRole('owner');

    // Create member for owner2
    Member::create([
        'parent_id' => $owner2->id,
        'name' => 'Member 2',
        'email' => 'member2@gym2.com',
        'phone' => '0987654321',
        'status' => 'active',
    ]);

    // Login as owner1 - should see no members
    $this->actingAs($owner1);
    $members = Member::all();
    
    expect($members->count())->toBe(0);
});

test('super admin bypasses tenant scope', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $owner1 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner1->assignRole('owner');

    $owner2 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner2->assignRole('owner');

    // Create members for both owners
    Member::create([
        'parent_id' => $owner1->id,
        'name' => 'Member 1',
        'email' => 'member1@gym1.com',
        'phone' => '1234567890',
        'status' => 'active',
    ]);

    Member::create([
        'parent_id' => $owner2->id,
        'name' => 'Member 2',
        'email' => 'member2@gym2.com',
        'phone' => '0987654321',
        'status' => 'active',
    ]);

    // Login as super admin - should see all members
    $this->actingAs($superAdmin);
    $members = Member::all();
    
    expect($members->count())->toBe(2);
});

test('manager only sees members from their owner', function () {
    $owner1 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner1->assignRole('owner');

    $manager1 = User::factory()->create(['parent_id' => $owner1->id, 'email_verified_at' => now()]);
    $manager1->assignRole('manager');

    $owner2 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);
    $owner2->assignRole('owner');

    // Create members for both owners
    $member1 = Member::create([
        'parent_id' => $owner1->id,
        'name' => 'Member 1',
        'email' => 'member1@gym1.com',
        'phone' => '1234567890',
        'status' => 'active',
    ]);

    Member::create([
        'parent_id' => $owner2->id,
        'name' => 'Member 2',
        'email' => 'member2@gym2.com',
        'phone' => '0987654321',
        'status' => 'active',
    ]);

    // Login as manager1 - should only see members from owner1
    $this->actingAs($manager1);
    $members = Member::all();
    
    expect($members->count())->toBe(1);
    expect($members->first()->id)->toBe($member1->id);
});

test('unauthenticated users dont trigger scope', function () {
    $owner1 = User::factory()->create(['parent_id' => null, 'email_verified_at' => now()]);

    Member::create([
        'parent_id' => $owner1->id,
        'name' => 'Member 1',
        'email' => 'member1@gym1.com',
        'phone' => '1234567890',
        'status' => 'active',
    ]);

    // Not authenticated - scope should not apply
    // This would show all members (or fail based on application logic)
    $members = Member::all();
    
    // When not authenticated, scope doesn't filter
    expect($members->count())->toBe(1);
});
