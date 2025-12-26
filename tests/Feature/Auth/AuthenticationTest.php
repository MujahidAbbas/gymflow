<?php

use App\Models\User;

test('login page is accessible', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertViewIs('auth.login');
});

test('users can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'password'),
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('users cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect('/');

    $this->assertGuest();
});
