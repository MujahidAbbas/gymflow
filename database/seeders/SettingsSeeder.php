<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first owner user as parent (created by UserSeeder)
        // Get the first owner user as parent (created by UserSeeder)
        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->error('⚠️  No owner user found! Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        $defaultSettings = [
            // App settings
            ['name' => 'app_name', 'value' => 'FitHub', 'type' => 'app'],
            ['name' => 'app_logo', 'value' => '/images/logo.png', 'type' => 'app'],
            ['name' => 'app_favicon', 'value' => '/images/favicon.ico', 'type' => 'app'],
            ['name' => 'app_timezone', 'value' => 'UTC', 'type' => 'app'],
            ['name' => 'app_currency', 'value' => 'USD', 'type' => 'app'],
            ['name' => 'app_language', 'value' => 'en', 'type' => 'app'],
            ['name' => 'app_date_format', 'value' => 'Y-m-d', 'type' => 'app'],
            ['name' => 'app_time_format', 'value' => 'H:i:s', 'type' => 'app'],

            // Email settings
            ['name' => 'mail_mailer', 'value' => 'smtp', 'type' => 'email'],
            ['name' => 'mail_host', 'value' => 'smtp.mailtrap.io', 'type' => 'email'],
            ['name' => 'mail_port', 'value' => '2525', 'type' => 'email'],
            ['name' => 'mail_username', 'value' => '', 'type' => 'email'],
            ['name' => 'mail_password', 'value' => '', 'type' => 'email'],
            ['name' => 'mail_encryption', 'value' => 'tls', 'type' => 'email'],
            ['name' => 'mail_from_address', 'value' => 'noreply@fithub.com', 'type' => 'email'],
            ['name' => 'mail_from_name', 'value' => 'FitHub', 'type' => 'email'],

            // Payment settings
            ['name' => 'stripe_key', 'value' => '', 'type' => 'payment'],
            ['name' => 'stripe_secret', 'value' => '', 'type' => 'payment'],
            ['name' => 'paypal_mode', 'value' => 'sandbox', 'type' => 'payment'],
            ['name' => 'paypal_client_id', 'value' => '', 'type' => 'payment'],
            ['name' => 'paypal_secret', 'value' => '', 'type' => 'payment'],
            ['name' => 'payment_currency', 'value' => 'USD', 'type' => 'payment'],

            // Security settings
            ['name' => 'email_verification_enable', 'value' => 'on', 'type' => 'security'],
            ['name' => 'recaptcha_enable', 'value' => 'off', 'type' => 'security'],
            ['name' => 'recaptcha_site_key', 'value' => '', 'type' => 'security'],
            ['name' => 'recaptcha_secret_key', 'value' => '', 'type' => 'security'],
            ['name' => '2fa_enable', 'value' => 'off', 'type' => 'security'],
            ['name' => 'session_lifetime', 'value' => '120', 'type' => 'security'],

            // Notification settings
            ['name' => 'notification_email', 'value' => 'on', 'type' => 'notification'],
            ['name' => 'notification_sms', 'value' => 'off', 'type' => 'notification'],
            ['name' => 'notification_push', 'value' => 'off', 'type' => 'notification'],
            ['name' => 'notification_welcome_email', 'value' => 'on', 'type' => 'notification'],
            ['name' => 'notification_invoice_email', 'value' => 'on', 'type' => 'notification'],

            // Business settings
            ['name' => 'business_name', 'value' => 'FitHub Gym', 'type' => 'business'],
            ['name' => 'business_address', 'value' => '', 'type' => 'business'],
            ['name' => 'business_phone', 'value' => '', 'type' => 'business'],
            ['name' => 'business_email', 'value' => 'info@fithub.com', 'type' => 'business'],
            ['name' => 'business_website', 'value' => 'https://fithub.com', 'type' => 'business'],

            // Subscription settings
            ['name' => 'trial_days', 'value' => '14', 'type' => 'subscription'],
            ['name' => 'subscription_enabled', 'value' => 'on', 'type' => 'subscription'],
            ['name' => 'auto_renewal', 'value' => 'on', 'type' => 'subscription'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                [
                    'name' => $setting['name'],
                    'parent_id' => $parentId,
                ],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                ]
            );
        }

        $this->command->info('Default settings created successfully for parent_id: '.$parentId);
    }
}
