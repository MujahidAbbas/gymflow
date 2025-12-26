<?php

use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

test('email verification screen can be rendered', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->get(route('email.verification.notice'));

    $response->assertStatus(200);
});

test('email verification link can be sent', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->post(route('email.verification.send'));

    $response->assertSessionHas('resent');

    Mail::assertSent(EmailVerification::class);
});

test('email can be verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'code' => $code = Str::random(32),
    ]);

    $response = $this->actingAs($user)->get(route('email.verify', ['code' => $code]));

    $response->assertRedirect(route('dashboard'));
    $this->assertTrue($user->fresh()->hasVerifiedEmail());
    $this->assertNull($user->fresh()->code);
});

test('email cannot be verified with invalid code', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
        'code' => Str::random(32),
    ]);

    $response = $this->actingAs($user)->get(route('email.verify', ['code' => 'invalid-code']));

    $response->assertRedirect(route('email.verification.notice'));
    $response->assertSessionHas('error');
    $this->assertFalse($user->fresh()->hasVerifiedEmail());
});
