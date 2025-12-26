<?php

namespace Database\Seeders;

use App\Models\PlatformSubscriptionTier;
use Illuminate\Database\Seeder;

class PlatformSubscriptionTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Starter Plan',
                'slug' => 'starter-plan',
                'description' => 'Perfect for small gyms just getting started with basic features',
                'price' => 29.00,
                'interval' => 'monthly',
                'trial_days' => 14,
                'max_members_per_tenant' => 50,
                'max_trainers_per_tenant' => 5,
                'max_staff_per_tenant' => 2,
                'features' => [
                    'Member Management',
                    'Trainer Management',
                    'Class Scheduling',
                    'Attendance Tracking',
                    'Basic Reporting',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional Plan',
                'slug' => 'professional-plan',
                'description' => 'For growing gyms with advanced features and higher limits',
                'price' => 79.00,
                'interval' => 'monthly',
                'trial_days' => 14,
                'max_members_per_tenant' => 200,
                'max_trainers_per_tenant' => 20,
                'max_staff_per_tenant' => 10,
                'features' => [
                    'All Starter Features',
                    'Workout Planning',
                    'Health Tracking',
                    'Financial Management',
                    'Product Management',
                    'Email Notifications',
                    'Advanced Reporting',
                    'Priority Support',
                ],
                'is_active' => true,
                'is_featured' => true, // Most Popular
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise Plan',
                'slug' => 'enterprise-plan',
                'description' => 'Unlimited everything for large fitness centers and chains',
                'price' => 199.00,
                'interval' => 'monthly',
                'trial_days' => 30,
                'max_members_per_tenant' => null, // Unlimited
                'max_trainers_per_tenant' => null, // Unlimited
                'max_staff_per_tenant' => null, // Unlimited
                'features' => [
                    'All Professional Features',
                    'Unlimited Members',
                    'Unlimited Trainers',
                    'Unlimited Staff',
                    'Custom Branding',
                    'API Access',
                    'White Label',
                    'Dedicated Support',
                    'Custom Integrations',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($tiers as $tier) {
            PlatformSubscriptionTier::firstOrCreate(
                ['slug' => $tier['slug']],
                $tier
            );
        }

        $this->command->info('✅ 3 Platform Subscription Tiers created successfully!');
        $this->command->info('   - Starter Plan: $29/month (50 members, 5 trainers)');
        $this->command->info('   - Professional Plan: $79/month (200 members, 20 trainers) [Featured]');
        $this->command->info('   - Enterprise Plan: $199/month (unlimited)');
    }
}
