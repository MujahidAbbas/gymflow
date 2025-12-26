<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'platform_name', 'value' => 'FitHub', 'type' => 'string', 'group' => 'branding', 'description' => 'Platform name displayed across the application'],
            
            // Features
            ['key' => 'enable_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Allow new gym owners to register'],
            ['key' => 'enable_landing_page', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Show public landing page'],
            ['key' => 'enable_email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Require email verification for new owners'],
            ['key' => 'enable_pricing_display', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Display pricing tiers publicly'],
            
            // Payment Defaults
            ['key' => 'default_payment_gateway', 'value' => 'stripe', 'type' => 'string', 'group' => 'payment', 'description' => 'Default payment gateway for new tenants'],
            ['key' => 'default_currency', 'value' => 'USD', 'type' => 'string', 'group' => 'payment', 'description' => 'Default currency'],
            
            // Email Defaults
            ['key' => 'default_mail_from_name', 'value' => 'FitHub', 'type' => 'string', 'group' => 'email', 'description' => 'Default from name for emails'],
            ['key' => 'default_mail_from_address', 'value' => 'noreply@fithub.com', 'type' => 'string', 'group' => 'email', 'description' => 'Default from address for emails'],
        ];

        foreach ($settings as $setting) {
            PlatformSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Platform settings seeded successfully!');
    }
}
