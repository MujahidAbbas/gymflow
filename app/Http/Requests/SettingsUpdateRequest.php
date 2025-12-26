<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage settings') || $this->user()->type === 'owner';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // App settings
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|string|max:500',
            'app_favicon' => 'nullable|string|max:500',
            'app_timezone' => 'nullable|string|max:100',
            'app_currency' => 'nullable|string|max:10',
            'app_language' => 'nullable|string|max:10',

            // Email settings
            'mail_mailer' => 'nullable|string|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',

            // Payment settings
            'stripe_key' => 'nullable|string|max:500',
            'stripe_secret' => 'nullable|string|max:500',
            'paypal_mode' => 'nullable|string|in:sandbox,live',
            'paypal_client_id' => 'nullable|string|max:500',
            'paypal_secret' => 'nullable|string|max:500',

            // Security settings
            'email_verification_enable' => 'nullable|string|in:on,off',
            'recaptcha_enable' => 'nullable|string|in:on,off',
            'recaptcha_site_key' => 'nullable|string|max:500',
            'recaptcha_secret_key' => 'nullable|string|max:500',
            '2fa_enable' => 'nullable|string|in:on,off',

            // Notification settings
            'notification_email' => 'nullable|string|in:on,off',
            'notification_sms' => 'nullable|string|in:on,off',
            'notification_push' => 'nullable|string|in:on,off',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'app_name.required' => 'Application name is required',
            'app_name.max' => 'Application name cannot exceed 255 characters',
            'mail_mailer.in' => 'Invalid mail provider selected',
            'mail_port.min' => 'Port number must be at least 1',
            'mail_port.max' => 'Port number cannot exceed 65535',
            'mail_encryption.in' => 'Encryption must be either TLS or SSL',
            'mail_from_address.email' => 'Please enter a valid email address',
        ];
    }
}
