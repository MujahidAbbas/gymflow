<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please create an owner user first.');

            return;
        }

        $parentId = $owner->id;

        $plans = [
            [
                'name' => 'Basic Monthly',
                'description' => 'Access to gym facilities and basic equipment',
                'price' => 29.99,
                'duration_type' => 'monthly',
                'duration_value' => 1,
                'is_active' => true,
                'features' => ['Gym access', 'Locker room', 'Basic equipment'],
                'max_classes' => 5,
                'personal_training' => false,
            ],
            [
                'name' => 'Standard Monthly',
                'description' => 'All basic features plus group classes',
                'price' => 49.99,
                'duration_type' => 'monthly',
                'duration_value' => 1,
                'is_active' => true,
                'features' => ['Gym access', 'Locker room', 'All equipment', 'Group classes'],
                'max_classes' => null, // unlimited
                'personal_training' => false,
            ],
            [
                'name' => 'Premium Monthly',
                'description' => 'All features including personal training sessions',
                'price' => 79.99,
                'duration_type' => 'monthly',
                'duration_value' => 1,
                'is_active' => true,
                'features' => ['Gym access', 'Locker room', 'All equipment', 'Unlimited classes', 'Personal trainer', 'Nutrition plan'],
                'max_classes' => null,
                'personal_training' => true,
            ],
            [
                'name' => 'Quarterly Basic',
                'description' => '3 months basic membership at discounted rate',
                'price' => 79.99,
                'duration_type' => 'quarterly',
                'duration_value' => 1,
                'is_active' => true,
                'features' => ['Gym access', 'Locker room', 'Basic equipment'],
                'max_classes' => 5,
                'personal_training' => false,
            ],
            [
                'name' => 'Annual Premium',
                'description' => 'Full year premium access with all benefits',
                'price' => 799.99,
                'duration_type' => 'yearly',
                'duration_value' => 1,
                'is_active' => true,
                'features' => ['All Premium features', 'Priority booking', 'Exclusive events', 'Guest passes'],
                'max_classes' => null,
                'personal_training' => true,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create(array_merge($plan, ['parent_id' => $parentId]));
        }

        $this->command->info('Membership plans created successfully for parent_id: '.$parentId);
    }
}
