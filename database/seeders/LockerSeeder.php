<?php

namespace Database\Seeders;

use App\Models\Locker;
use App\Models\LockerAssignment;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LockerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('locker_assignments')->truncate();
        DB::table('lockers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $members = Member::where('parent_id', $parentId)->where('status', 'active')->get();

        $locations = [
            'Ground Floor - East Wing',
            'Ground Floor - West Wing',
            'First Floor - Near Pool',
            'First Floor - Near Gym',
        ];

        $fees = [15.00, 20.00, 25.00];

        // Create 50 lockers
        $lockers = [];
        for ($i = 1; $i <= 50; $i++) {
            $lockerNumber = '#LKR-'.str_pad($i, 4, '0', STR_PAD_LEFT);
            $location = $locations[array_rand($locations)];
            $fee = $fees[array_rand($fees)];

            // Status distribution: 60% available, 35% occupied, 5% maintenance
            $roll = rand(1, 100);
            if ($roll <= 60) {
                $status = 'available';
            } elseif ($roll <= 95) {
                $status = 'occupied';
            } else {
                $status = 'maintenance';
            }

            $lockers[] = Locker::create([
                'parent_id' => $parentId,
                'locker_number' => $lockerNumber,
                'location' => $location,
                'status' => $status,
                'monthly_fee' => $fee,
                'notes' => $status === 'maintenance' ? 'Lock mechanism needs repair' : null,
            ]);
        }

        $this->command->info('✅ 50 lockers created successfully!');

        // Create locker assignments for occupied lockers
        $occupiedLockers = collect($lockers)->filter(fn ($l) => $l->status === 'occupied');
        $assignmentCount = 0;

        if ($members->isEmpty()) {
            $this->command->warn('No members found for locker assignments.');

            return;
        }

        foreach ($occupiedLockers as $locker) {
            $member = $members->random();

            LockerAssignment::create([
                'locker_id' => $locker->id,
                'member_id' => $member->id,
                'assigned_date' => now()->subDays(rand(1, 90)),
                'expiry_date' => now()->addDays(rand(30, 180)),
                'status' => 'active',
            ]);
            $assignmentCount++;
        }

        $this->command->info("✅ {$assignmentCount} locker assignments created successfully!");
    }
}
