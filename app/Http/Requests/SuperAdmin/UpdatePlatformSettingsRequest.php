<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatformSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Branding
            'platform_name' => 'nullable|string|max:255',
            'platform_logo' => 'nullable|image|max:2048',
            'platform_favicon' => 'nullable|image|max:1024',
            'platform_logo_light' => 'nullable|image|max:2048',

            // Features
            'enable_registration' => 'nullable|boolean',
            'enable_landing_page' => 'nullable|boolean',
            'enable_email_verification' => 'nullable|boolean',
            'enable_pricing_display' => 'nullable|boolean',

            // Payment Defaults
            'default_payment_gateway' => 'nullable|string|in:stripe,paypal,bank_transfer,flutterwave,paystack',
            'default_currency' => 'nullable|string|max:3',

            // Email Defaults
            'default_mail_from_name' => 'nullable|string|max:255',
            'default_mail_from_address' => 'nullable|email|max:255',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'platform_name' => 'platform name',
            'platform_logo' => 'platform logo',
            'platform_favicon' => 'favicon',
            'platform_logo_light' => 'light mode logo',
            'enable_registration' => 'registration',
            'enable_landing_page' => 'landing page',
            'enable_email_verification' => 'email verification',
            'enable_pricing_display' => 'pricing display',
            'default_payment_gateway' => 'default payment gateway',
            'default_currency' => 'default currency',
            'default_mail_from_name' => 'default from name',
            'default_mail_from_address' => 'default from address',
        ];
    }
}
