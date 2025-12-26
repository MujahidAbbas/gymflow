<?php

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

test('user with 2fa enabled is redirected to 2fa verification', function () {
    $user = User::factory()->create([
        'twofa_enabled' => true,
        'twofa_secret' => 'ADUMJO5634NPDEKW',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login.2fa'));
});

test('user can verify 2fa with valid code', function () {
    $google2fa = new Google2FA;
    $secret = $google2fa->generateSecretKey();

    $user = User::factory()->create([
        'twofa_enabled' => true,
        'twofa_secret' => $secret,
    ]);

    $this->actingAs($user);

    $code = $google2fa->getCurrentOtp($secret);

    $response = $this->post(route('login.2fa.verify'), [
        'code' => $code,
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertTrue(session('2fa_verified'));
});

test('user cannot verify 2fa with invalid code', function () {
    $user = User::factory()->create([
        'twofa_enabled' => true,
        'twofa_secret' => 'ADUMJO5634NPDEKW', // 16 chars base32 compatible
    ]);

    $this->actingAs($user);

    $response = $this->post(route('login.2fa.verify'), [
        'code' => 'invalid-code',
    ]);

    $response->assertSessionHasErrors('code');
    $this->assertFalse(session()->has('2fa_verified'));
});
