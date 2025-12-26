<?php

use App\Models\Member;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->owner->assignRole('owner');

    $this->plan = SubscriptionPlan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro-plan',
        'price' => 29.99,
        'duration_days' => 30,
        'is_active' => true,
        'parent_id' => $this->owner->id,
    ]);

    $this->memberUser = User::factory()->create(['parent_id' => $this->owner->id]);
    $this->memberUser->assignRole('member');

    $this->member = Member::create([
        'user_id' => $this->memberUser->id,
        'name' => 'John Member',
        'email' => $this->memberUser->email,
        'parent_id' => $this->owner->id,
        'status' => 'active',
    ]);
});

test('member can list subscription plans', function () {
    $this->actingAs($this->memberUser)
        ->get(route('subscriptions.index'))
        ->assertOk()
        ->assertSee($this->plan->name);
});

test('member can view checkout page', function () {
    $this->actingAs($this->memberUser)
        ->get(route('subscriptions.checkout', $this->plan))
        ->assertOk()
        ->assertSee($this->plan->name);
});

test('member can view my subscription page', function () {
    $subscription = Subscription::create([
        'member_id' => $this->member->id,
        'subscription_plan_id' => $this->plan->id,
        'start_date' => now(),
        'end_date' => now()->addDays(30),
        'status' => 'active',
        'payment_gateway' => 'stripe',
    ]);

    $this->actingAs($this->memberUser)
        ->get(route('subscriptions.mine'))
        ->assertOk()
        ->assertSee($this->plan->name)
        ->assertSee('Active');
});

test('owner cannot access subscription plans from other tenant', function () {
    $otherOwner = User::factory()->create();
    $otherPlan = SubscriptionPlan::create([
        'name' => 'Other Plan',
        'slug' => 'other-plan',
        'price' => 10,
        'duration_days' => 30,
        'is_active' => true,
        'parent_id' => $otherOwner->id,
    ]);

    // Index filters by parent_id, so it shouldn't see other plan
    $this->actingAs($this->owner)
        ->get(route('subscriptions.index'))
        ->assertOk()
        ->assertDontSee($otherPlan->name);
});
