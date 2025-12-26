<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get owner user created by UserSeeder
        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        // Create tenant for the default owner
        Tenant::firstOrCreate(
            ['user_id' => $owner->id],
            [
                'business_name' => 'Fitness Hub Gym',
                'subdomain' => 'fitnesshub',
                'status' => 'active',
                'max_members' => 100,
                'max_trainers' => 10,
                'trial_ends_at' => now()->addDays(14),
            ]
        );

        $this->command->info('✅ Tenant created for owner: '.$owner->email);
    }
}
