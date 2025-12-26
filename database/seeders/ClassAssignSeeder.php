<?php

namespace Database\Seeders;

use App\Models\ClassAssign;
use App\Models\GymClass;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('class_assigns')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $classes = GymClass::where('parent_id', $parentId)->where('status', 'active')->get();
        $members = Member::where('parent_id', $parentId)->where('status', 'active')->get();

        if ($classes->isEmpty()) {
            $this->command->warn('No gym classes found. Please run GymClassSeeder first.');

            return;
        }

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Please run MemberSeeder first.');

            return;
        }

        $count = 0;
        $usedPairs = [];

        // Randomly assign members to classes
        foreach ($classes as $class) {
            // Determine how many members to enroll (30-70% of capacity)
            $enrollCount = rand((int) ($class->max_capacity * 0.3), (int) ($class->max_capacity * 0.7));
            $enrollCount = min($enrollCount, $members->count());

            // Get random members for this class
            $selectedMembers = $members->random($enrollCount);

            foreach ($selectedMembers as $member) {
                $pairKey = "{$class->id}-{$member->id}";

                // Skip if already assigned (shouldn't happen with fresh seed but safety check)
                if (isset($usedPairs[$pairKey])) {
                    continue;
                }

                $usedPairs[$pairKey] = true;

                // Random status distribution
                $statusRoll = rand(1, 100);
                if ($statusRoll <= 80) {
                    $status = 'active';
                } elseif ($statusRoll <= 95) {
                    $status = 'completed';
                } else {
                    $status = 'cancelled';
                }

                // Random enrollment date within last 3 months
                $enrollmentDate = now()->subDays(rand(1, 90));

                ClassAssign::create([
                    'gym_class_id' => $class->id,
                    'member_id' => $member->id,
                    'enrollment_date' => $enrollmentDate,
                    'status' => $status,
                    'notes' => $status === 'cancelled' ? 'Cancelled due to schedule conflict' : null,
                ]);
                $count++;
            }
        }

        $this->command->info("✅ {$count} class assignments created successfully!");
    }
}
