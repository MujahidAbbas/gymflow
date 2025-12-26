<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutActivity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('workout_activities')->truncate();
        DB::table('workouts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $members = Member::where('parent_id', $parentId)->where('status', 'active')->get();
        $trainers = Trainer::where('parent_id', $parentId)->where('status', 'active')->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Please run MemberSeeder first.');

            return;
        }

        $workoutTypes = [
            ['name' => 'Upper Body Strength', 'activities' => ['Bench Press', 'Shoulder Press', 'Bicep Curls', 'Tricep Dips', 'Pull-ups']],
            ['name' => 'Lower Body Power', 'activities' => ['Squats', 'Lunges', 'Leg Press', 'Calf Raises', 'Hamstring Curls']],
            ['name' => 'Full Body Circuit', 'activities' => ['Burpees', 'Mountain Climbers', 'Kettlebell Swings', 'Box Jumps', 'Plank']],
            ['name' => 'Cardio Blast', 'activities' => ['Treadmill Run', 'Rowing Machine', 'Jump Rope', 'Cycling', 'Stair Climber']],
            ['name' => 'Core Conditioning', 'activities' => ['Crunches', 'Leg Raises', 'Russian Twists', 'Plank Hold', 'Dead Bug']],
            ['name' => 'HIIT Session', 'activities' => ['Sprint Intervals', 'Battle Ropes', 'Box Jumps', 'Burpees', 'Kettlebell Swings']],
        ];

        $workoutCount = 0;
        $activityCount = 0;

        // Create workouts for active members over last 30 days
        foreach ($members->take(15) as $member) {
            // Each member gets 2-5 workouts
            $numWorkouts = rand(2, 5);

            for ($i = 0; $i < $numWorkouts; $i++) {
                $workoutType = $workoutTypes[array_rand($workoutTypes)];
                $trainer = $trainers->isNotEmpty() && rand(0, 1) ? $trainers->random() : null;
                $workoutDate = now()->subDays(rand(1, 30));

                $statusRoll = rand(1, 100);
                if ($statusRoll <= 75) {
                    $status = 'completed';
                } elseif ($statusRoll <= 95) {
                    $status = 'active';
                } else {
                    $status = 'cancelled';
                }

                $workout = Workout::create([
                    'parent_id' => $parentId,
                    'member_id' => $member->id,
                    'trainer_id' => $trainer?->id,
                    'name' => $workoutType['name'],
                    'description' => "Personal workout session: {$workoutType['name']}",
                    'workout_date' => $workoutDate,
                    'status' => $status,
                    'notes' => $status === 'completed' ? 'Great session!' : null,
                ]);
                $workoutCount++;

                // Create activities for this workout
                foreach ($workoutType['activities'] as $index => $activityName) {
                    WorkoutActivity::create([
                        'workout_id' => $workout->id,
                        'exercise_name' => $activityName,
                        'description' => null,
                        'sets' => rand(3, 5),
                        'reps' => rand(8, 15),
                        'weight_kg' => rand(1, 100) > 30 ? rand(10, 100) : null,
                        'duration_minutes' => rand(1, 100) > 50 ? rand(5, 20) : null,
                        'rest_seconds' => rand(30, 90),
                        'order' => $index + 1,
                        'is_completed' => $status === 'completed',
                        'notes' => null,
                    ]);
                    $activityCount++;
                }
            }
        }

        $this->command->info("✅ {$workoutCount} workouts, {$activityCount} activities created!");
    }
}
