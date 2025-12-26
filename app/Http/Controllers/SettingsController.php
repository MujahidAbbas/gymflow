<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index(): View
    {
        $parentId = parentId();

        $settings = Setting::where('parent_id', $parentId)
            ->get()
            ->groupBy('type');

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(SettingsUpdateRequest $request): RedirectResponse
    {
        $parentId = parentId();

        foreach ($request->validated() as $key => $value) {
            Setting::updateOrCreate(
                [
                    'name' => $key,
                    'parent_id' => $parentId,
                ],
                [
                    'value' => $value,
                    'type' => $this->getSettingType($key),
                ]
            );
        }

        // Clear settings cache
        Cache::forget('settings_'.$parentId);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }

    /**
     * Get setting by key
     */
    public function show(string $key)
    {
        $parentId = parentId();

        $setting = Setting::where('parent_id', $parentId)
            ->where('name', $key)
            ->first();

        if (! $setting) {
            return response()->json(['error' => 'Setting not found'], 404);
        }

        return response()->json([
            'name' => $setting->name,
            'value' => $setting->value,
            'type' => $setting->type,
        ]);
    }

    /**
     * Store a new setting
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|string',
            'type' => 'nullable|string|max:100',
        ]);

        $parentId = parentId();

        Setting::updateOrCreate(
            [
                'name' => $request->name,
                'parent_id' => $parentId,
            ],
            [
                'value' => $request->value,
                'type' => $request->type ?? $this->getSettingType($request->name),
            ]
        );

        Cache::forget('settings_'.$parentId);

        return redirect()->route('settings.index')
            ->with('success', 'Setting created successfully');
    }

    /**
     * Delete a setting
     */
    public function destroy(string $id): RedirectResponse
    {
        $parentId = parentId();

        $setting = Setting::where('parent_id', $parentId)
            ->where('id', $id)
            ->first();

        if (! $setting) {
            return redirect()->route('settings.index')
                ->with('error', 'Setting not found');
        }

        $setting->delete();
        Cache::forget('settings_'.$parentId);

        return redirect()->route('settings.index')
            ->with('success', 'Setting deleted successfully');
    }

    /**
     * Determine setting type based on key name
     */
    protected function getSettingType(string $key): string
    {
        $typeMap = [
            // App settings
            'app_name' => 'app',
            'app_logo' => 'app',
            'app_favicon' => 'app',
            'app_timezone' => 'app',
            'app_currency' => 'app',
            'app_language' => 'app',

            // Email settings
            'mail_mailer' => 'email',
            'mail_host' => 'email',
            'mail_port' => 'email',
            'mail_username' => 'email',
            'mail_password' => 'email',
            'mail_encryption' => 'email',
            'mail_from_address' => 'email',
            'mail_from_name' => 'email',

            // Payment settings
            'stripe_key' => 'payment',
            'stripe_secret' => 'payment',
            'paypal_mode' => 'payment',
            'paypal_client_id' => 'payment',
            'paypal_secret' => 'payment',

            // Security settings
            'email_verification_enable' => 'security',
            'recaptcha_enable' => 'security',
            'recaptcha_site_key' => 'security',
            'recaptcha_secret_key' => 'security',
            '2fa_enable' => 'security',

            // Notification settings
            'notification_email' => 'notification',
            'notification_sms' => 'notification',
            'notification_push' => 'notification',
        ];

        return $typeMap[$key] ?? 'general';
    }
}
