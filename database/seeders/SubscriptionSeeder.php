<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subscriptions')->truncate();
        DB::table('subscription_plans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        // Create subscription plans
        $plans = [
            [
                'name' => 'Basic Plan',
                'slug' => 'basic-plan-'.Str::random(4),
                'description' => 'Essential gym access with basic features',
                'price' => 29.99,
                'duration_days' => 30,
                'trial_days' => 7,
                'features' => ['Gym access', 'Locker room', 'Basic equipment'],
                'is_active' => true,
                'is_featured' => false,
                'max_members' => null,
            ],
            [
                'name' => 'Standard Plan',
                'slug' => 'standard-plan-'.Str::random(4),
                'description' => 'Full access with group classes included',
                'price' => 49.99,
                'duration_days' => 30,
                'trial_days' => 7,
                'features' => ['All Basic features', 'Group classes', 'Sauna access', 'Towel service'],
                'is_active' => true,
                'is_featured' => true,
                'max_members' => null,
            ],
            [
                'name' => 'Premium Plan',
                'slug' => 'premium-plan-'.Str::random(4),
                'description' => 'Complete access with personal training',
                'price' => 79.99,
                'duration_days' => 30,
                'trial_days' => 14,
                'features' => ['All Standard features', 'Personal training (2 sessions/month)', 'Nutrition consultation', 'Priority booking'],
                'is_active' => true,
                'is_featured' => false,
                'max_members' => null,
            ],
            [
                'name' => 'Annual Basic',
                'slug' => 'annual-basic-'.Str::random(4),
                'description' => 'Basic plan billed annually with 2 months free',
                'price' => 299.99,
                'duration_days' => 365,
                'trial_days' => 7,
                'features' => ['All Basic features', 'Annual billing discount'],
                'is_active' => true,
                'is_featured' => false,
                'max_members' => null,
            ],
        ];

        $createdPlans = [];
        foreach ($plans as $planData) {
            $createdPlans[] = SubscriptionPlan::create(array_merge($planData, [
                'parent_id' => $parentId,
            ]));
        }

        $this->command->info('✅ '.count($plans).' subscription plans created!');

        // Create subscriptions for members
        $members = Member::where('parent_id', $parentId)->where('status', 'active')->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found for subscriptions.');

            return;
        }

        $paymentGateways = ['stripe', 'paypal', null];
        $subscriptionCount = 0;

        foreach ($members->take(20) as $member) {
            $plan = $createdPlans[array_rand($createdPlans)];

            // Determine subscription status and dates
            $statusRoll = rand(1, 100);
            if ($statusRoll <= 70) {
                $status = 'active';
                $startDate = now()->subDays(rand(1, 60));
                $endDate = $startDate->copy()->addDays($plan->duration_days);
            } elseif ($statusRoll <= 85) {
                $status = 'trial';
                $startDate = now()->subDays(rand(1, 5));
                $endDate = $startDate->copy()->addDays($plan->trial_days);
            } elseif ($statusRoll <= 95) {
                $status = 'expired';
                $startDate = now()->subDays(rand(60, 120));
                $endDate = $startDate->copy()->addDays($plan->duration_days);
            } else {
                $status = 'cancelled';
                $startDate = now()->subDays(rand(30, 90));
                $endDate = $startDate->copy()->addDays($plan->duration_days);
            }

            $gateway = $paymentGateways[array_rand($paymentGateways)];

            Subscription::create([
                'member_id' => $member->id,
                'subscription_plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'trial_end_date' => $status === 'trial' ? $endDate : null,
                'status' => $status,
                'auto_renew' => $status === 'active' && rand(0, 1),
                'payment_gateway' => $gateway,
                'gateway_subscription_id' => $gateway ? 'sub_'.Str::random(14) : null,
            ]);
            $subscriptionCount++;
        }

        $this->command->info("✅ {$subscriptionCount} member subscriptions created!");
    }
}
