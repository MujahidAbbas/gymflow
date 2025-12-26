<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class OTPController extends Controller
{
    /**
     * Display the 2FA verification view.
     */
    public function show(): View
    {
        return view('auth.2fa');
    }

    /**
     * Verify the 2FA code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = Auth::user();

        $google2fa = new Google2FA;

        $valid = $google2fa->verifyKey($user->twofa_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Invalid 2FA code']);
        }

        // Mark 2FA as verified in session
        $request->session()->put('2fa_verified', true);

        // Log user login history
        userLoggedHistory();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Enable 2FA for the user.
     */
    public function enable(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();

        $user->twofa_secret = $secret;
        $user->twofa_enabled = true;
        $user->save();

        return back()->with('success', '2FA enabled successfully')->with('qr_code', $this->getQRCode($user, $secret));
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $user->twofa_enabled = false;
        $user->twofa_secret = null;
        $user->save();

        return back()->with('success', '2FA disabled successfully');
    }

    /**
     * Generate QR code for 2FA setup.
     */
    protected function getQRCode($user, $secret): string
    {
        $google2fa = new Google2FA;
        $companyName = config('app.name');
        $companyEmail = $user->email;

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secret
        );

        return $qrCodeUrl;
    }
}
