<?php

namespace Database\Seeders;

use App\Models\Health;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('healths')->truncate();
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

        // Create health records for members (2-4 records per member)
        foreach ($members->take(20) as $member) {
            $numRecords = rand(2, 4);
            $baseWeight = rand(55, 95); // kg
            $baseBodyFat = rand(15, 30); // %

            for ($i = $numRecords - 1; $i >= 0; $i--) {
                $measurementDate = now()->subWeeks($i * 4); // Monthly measurements

                // Simulate slight progress over time
                $weightChange = $i > 0 ? rand(-2, 1) : 0;
                $bodyFatChange = $i > 0 ? rand(-1, 0) : 0;

                $weight = $baseWeight + $weightChange;
                $height = rand(160, 190); // cm
                $bmi = round(($weight / (($height / 100) ** 2)), 1);
                $bodyFat = max(10, $baseBodyFat + $bodyFatChange);

                $measurements = [
                    'weight_kg' => $weight,
                    'height_cm' => $height,
                    'bmi' => $bmi,
                    'body_fat_percentage' => $bodyFat,
                    'chest_cm' => rand(85, 110),
                    'waist_cm' => rand(70, 100),
                    'hips_cm' => rand(85, 110),
                    'arm_cm' => rand(28, 40),
                    'thigh_cm' => rand(50, 65),
                    'resting_heart_rate' => rand(55, 80),
                    'blood_pressure_systolic' => rand(110, 130),
                    'blood_pressure_diastolic' => rand(70, 85),
                ];

                Health::create([
                    'parent_id' => $parentId,
                    'member_id' => $member->id,
                    'measurement_date' => $measurementDate,
                    'measurements' => $measurements,
                    'notes' => $i === 0 ? 'Latest measurement' : null,
                ]);
                $count++;
            }
        }

        $this->command->info("✅ {$count} health records created successfully!");
    }
}
