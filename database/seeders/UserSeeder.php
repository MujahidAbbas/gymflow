<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Super Admin (Platform Administrator)
        $superAdmin = User::firstOrCreate(
            ['email' => 'super-admin@fithub.com'],
            [
                'name' => 'Platform Admin',
                'password' => $password,
                'email_verified_at' => now(),
                'parent_id' => null, // Super admin has no parent

                'avatar' => 'avatar-1.jpg',
            ]
        );
        $superAdmin->assignRole('super-admin');

        // 2. Owner (Gym Customer - example owner)
        $owner = User::firstOrCreate(
            ['email' => 'owner@fithub.com'],
            [
                'name' => 'Gym Owner',
                'password' => $password,
                'email_verified_at' => now(),
                'parent_id' => null, // Owners have no parent

                'avatar' => 'avatar-2.jpg',
            ]
        );
        $owner->assignRole('owner');

        // 2. Manager (for Gym #1)
        $manager = User::firstOrCreate(
            ['email' => 'manager@fithub.com'],
            [
                'name' => 'Gym Manager',
                'password' => $password,
                'email_verified_at' => now(),
                'parent_id' => $owner->id,

                'avatar' => 'avatar-2.jpg',
            ]
        );
        $manager->assignRole('manager');

        // 3. Trainer (for Gym #1)
        $trainer = User::firstOrCreate(
            ['email' => 'trainer@fithub.com'],
            [
                'name' => 'John Trainer',
                'password' => $password,
                'email_verified_at' => now(),
                'parent_id' => $owner->id,

                'avatar' => 'avatar-3.jpg',
            ]
        );
        $trainer->assignRole('trainer');

        // 4. Receptionist (for Gym #1)
        $receptionist = User::firstOrCreate(
            ['email' => 'receptionist@fithub.com'],
            [
                'name' => 'Front Desk',
                'password' => $password,
                'email_verified_at' => now(),
                'parent_id' => $owner->id,

                'avatar' => 'avatar-4.jpg',
            ]
        );
        $receptionist->assignRole('receptionist');

        // 5. Member (for Gym #1)
        $member = User::firstOrCreate(
            ['email' => 'member@fithub.com'],
            [
                'name' => 'John Member',
                'password' => $password,
                'email_verified_at' => now(),
                'parent_id' => $owner->id,

                'avatar' => 'avatar-5.jpg',
            ]
        );
        $member->assignRole('member');

        $this->command->info('✅ 6 Default users created successfully!');
        $this->command->info('   📧 All users use password: password');
        $this->command->info('   👤 super-admin@fithub.com (Platform Super Admin)');
        $this->command->info('   👤 owner@fithub.com (Gym Owner/Customer)');
        $this->command->info('   👤 manager@fithub.com (Manager)');
        $this->command->info('   👤 trainer@fithub.com (Trainer)');
        $this->command->info('   👤 receptionist@fithub.com (Receptionist)');
        $this->command->info('   👤 member@fithub.com (Member)');
    }
}
