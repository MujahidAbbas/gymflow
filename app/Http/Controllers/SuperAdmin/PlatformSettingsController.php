<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UpdatePlatformSettingsRequest;
use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlatformSettingsController extends Controller
{
    /**
     * Display platform settings.
     */
    public function index(): View
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $settings = PlatformSetting::all()->groupBy('group');

        return view('super-admin.settings.index', compact('settings'));
    }

    /**
     * Update platform settings.
     */
    public function update(UpdatePlatformSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        foreach ($validated as $key => $value) {
            $group = $this->getSettingGroup($key);
            $type = $this->getSettingType($key);

            PlatformSetting::set($key, $value, $type, $group);
        }

        // Clear all settings cache
        PlatformSetting::clearCache();

        return redirect()
            ->route('super-admin.settings.index')
            ->with('success', 'Platform settings updated successfully.');
    }

    /**
     * Get setting group based on key.
     */
    protected function getSettingGroup(string $key): string
    {
        $groupMap = [
            // Branding
            'platform_name' => 'branding',
            'platform_logo' => 'branding',
            'platform_favicon' => 'branding',
            'platform_logo_light' => 'branding',

            // Features
            'enable_registration' => 'features',
            'enable_landing_page' => 'features',
            'enable_email_verification' => 'features',
            'enable_pricing_display' => 'features',

            // Payment Defaults
            'default_payment_gateway' => 'payment',
            'default_currency' => 'payment',

            // Email Defaults
            'default_mail_from_name' => 'email',
            'default_mail_from_address' => 'email',
        ];

        return $groupMap[$key] ?? 'general';
    }

    /**
     * Get setting type based on key.
     */
    protected function getSettingType(string $key): string
    {
        $booleanSettings = [
            'enable_registration',
            'enable_landing_page',
            'enable_email_verification',
            'enable_pricing_display',
        ];

        return in_array($key, $booleanSettings) ? 'boolean' : 'string';
    }
}
