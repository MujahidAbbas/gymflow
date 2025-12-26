<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\GymClass;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('class_schedules')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $classes = GymClass::where('parent_id', $parentId)->where('status', 'active')->get();
        $trainers = Trainer::where('parent_id', $parentId)->where('status', 'active')->get();

        if ($classes->isEmpty()) {
            $this->command->warn('No gym classes found. Please run GymClassSeeder first.');

            return;
        }

        $rooms = ['Studio A', 'Studio B', 'Main Floor', 'Spin Room', 'Boxing Ring'];
        $count = 0;

        // Create schedules for each class
        foreach ($classes as $class) {
            $schedules = $this->getSchedulesForClass($class->name);

            foreach ($schedules as $schedule) {
                // Assign a trainer if available
                $trainer = $trainers->isNotEmpty() ? $trainers->random() : null;

                ClassSchedule::create([
                    'gym_class_id' => $class->id,
                    'day_of_week' => $schedule['day'],
                    'start_time' => $schedule['start'],
                    'end_time' => $schedule['end'],
                    'trainer_id' => $trainer?->id,
                    'room_location' => $rooms[array_rand($rooms)],
                ]);
                $count++;
            }
        }

        $this->command->info("✅ {$count} class schedules created successfully!");
    }

    /**
     * Get schedules for a specific class
     */
    private function getSchedulesForClass(string $className): array
    {
        $schedules = [
            'Morning Sunrise Yoga' => [
                ['day' => 'monday', 'start' => '06:30', 'end' => '07:30'],
                ['day' => 'wednesday', 'start' => '06:30', 'end' => '07:30'],
                ['day' => 'friday', 'start' => '06:30', 'end' => '07:30'],
            ],
            'Power Vinyasa Flow' => [
                ['day' => 'tuesday', 'start' => '18:00', 'end' => '19:15'],
                ['day' => 'thursday', 'start' => '18:00', 'end' => '19:15'],
                ['day' => 'saturday', 'start' => '10:00', 'end' => '11:15'],
            ],
            'Cardio Blast' => [
                ['day' => 'monday', 'start' => '12:00', 'end' => '12:45'],
                ['day' => 'wednesday', 'start' => '12:00', 'end' => '12:45'],
                ['day' => 'friday', 'start' => '12:00', 'end' => '12:45'],
            ],
            'Step Aerobics' => [
                ['day' => 'tuesday', 'start' => '09:00', 'end' => '09:50'],
                ['day' => 'thursday', 'start' => '09:00', 'end' => '09:50'],
            ],
            'Total Body Conditioning' => [
                ['day' => 'monday', 'start' => '17:30', 'end' => '18:30'],
                ['day' => 'wednesday', 'start' => '17:30', 'end' => '18:30'],
                ['day' => 'saturday', 'start' => '08:00', 'end' => '09:00'],
            ],
            'Core & More' => [
                ['day' => 'tuesday', 'start' => '12:30', 'end' => '13:00'],
                ['day' => 'thursday', 'start' => '12:30', 'end' => '13:00'],
                ['day' => 'saturday', 'start' => '11:30', 'end' => '12:00'],
            ],
            'CrossFit Fundamentals' => [
                ['day' => 'monday', 'start' => '19:00', 'end' => '20:00'],
                ['day' => 'wednesday', 'start' => '19:00', 'end' => '20:00'],
            ],
            'CrossFit WOD' => [
                ['day' => 'tuesday', 'start' => '06:00', 'end' => '07:00'],
                ['day' => 'thursday', 'start' => '06:00', 'end' => '07:00'],
                ['day' => 'saturday', 'start' => '07:00', 'end' => '08:00'],
            ],
            'HIIT Express' => [
                ['day' => 'monday', 'start' => '07:00', 'end' => '07:30'],
                ['day' => 'wednesday', 'start' => '07:00', 'end' => '07:30'],
                ['day' => 'friday', 'start' => '07:00', 'end' => '07:30'],
            ],
            'Tabata Training' => [
                ['day' => 'tuesday', 'start' => '17:00', 'end' => '17:45'],
                ['day' => 'thursday', 'start' => '17:00', 'end' => '17:45'],
            ],
            'Spin & Sweat' => [
                ['day' => 'monday', 'start' => '18:00', 'end' => '18:45'],
                ['day' => 'wednesday', 'start' => '18:00', 'end' => '18:45'],
                ['day' => 'friday', 'start' => '18:00', 'end' => '18:45'],
                ['day' => 'sunday', 'start' => '09:00', 'end' => '09:45'],
            ],
            'Beginner Spin' => [
                ['day' => 'tuesday', 'start' => '10:00', 'end' => '10:40'],
                ['day' => 'saturday', 'start' => '09:00', 'end' => '09:40'],
            ],
            'Mat Pilates' => [
                ['day' => 'tuesday', 'start' => '11:00', 'end' => '11:55'],
                ['day' => 'thursday', 'start' => '11:00', 'end' => '11:55'],
                ['day' => 'saturday', 'start' => '10:00', 'end' => '10:55'],
            ],
            'Boxing Bootcamp' => [
                ['day' => 'monday', 'start' => '19:30', 'end' => '20:30'],
                ['day' => 'wednesday', 'start' => '19:30', 'end' => '20:30'],
                ['day' => 'friday', 'start' => '17:00', 'end' => '18:00'],
            ],
        ];

        return $schedules[$className] ?? [
            ['day' => 'saturday', 'start' => '14:00', 'end' => '15:00'],
        ];
    }
}
