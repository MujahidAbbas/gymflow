<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendances')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $members = Member::where('parent_id', $parentId)->where('status', 'active')->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Please run MemberSeeder first.');

            return;
        }

        $count = 0;

        // Create attendance for last 30 days
        for ($day = 29; $day >= 0; $day--) {
            $date = now()->subDays($day);

            // Skip creating too many on weekends
            $isWeekend = $date->isWeekend();
            $memberVisits = $isWeekend ? rand(5, 10) : rand(10, 18);

            // Get random members for this day
            $visitingMembers = $members->random(min($memberVisits, $members->count()));

            foreach ($visitingMembers as $member) {
                // Random check-in time between 6am and 8pm
                $checkInHour = rand(6, 20);
                $checkInMinute = rand(0, 59);
                $checkInTime = sprintf('%02d:%02d:00', $checkInHour, $checkInMinute);

                // Duration between 30 mins and 3 hours
                $duration = rand(30, 180);
                $checkOutHour = $checkInHour + floor($duration / 60);
                $checkOutMinute = ($checkInMinute + ($duration % 60)) % 60;

                // Make sure check-out doesn't exceed 11pm
                if ($checkOutHour >= 23) {
                    $checkOutHour = 23;
                    $checkOutMinute = 0;
                }

                $checkOutTime = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMinute);

                // 10% chance member forgot to check out
                $hasCheckOut = rand(1, 100) > 10;

                // Skip if this would create a duplicate
                $exists = Attendance::where('member_id', $member->id)
                    ->where('date', $date->format('Y-m-d'))
                    ->where('check_in_time', $checkInTime)
                    ->exists();

                if ($exists) {
                    continue;
                }

                Attendance::create([
                    'parent_id' => $parentId,
                    'member_id' => $member->id,
                    'date' => $date,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => $hasCheckOut ? $checkOutTime : null,
                    'duration_minutes' => $hasCheckOut ? $duration : null,
                    'notes' => ! $hasCheckOut ? 'Member did not check out' : null,
                ]);
                $count++;
            }
        }

        $this->command->info("✅ {$count} attendance records created successfully!");
    }
}
