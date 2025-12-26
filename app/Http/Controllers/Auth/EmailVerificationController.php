<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    /**
     * Send a new email verification notification.
     */
    public function send(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Generate verification code
        $code = Str::random(32);
        $user->code = $code;
        $user->save();

        // Send verification email
        Mail::to($user->email)->send(new EmailVerification($user, $code));

        return back()->with('resent', true);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, string $code): RedirectResponse
    {
        $user = Auth::user();

        if ($user->code !== $code) {
            return redirect()->route('email.verification.notice')
                ->with('error', 'Invalid verification code');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        $user->markEmailAsVerified();
        $user->code = null;
        $user->save();

        event(new \Illuminate\Auth\Events\Verified($user));

        return redirect()->intended(route('dashboard'))
            ->with('verified', true);
    }
}
