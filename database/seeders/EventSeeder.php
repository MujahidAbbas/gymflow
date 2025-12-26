<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('events')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        $events = [
            // Past events
            [
                'title' => 'Summer Fitness Challenge',
                'description' => 'Join us for our annual summer fitness challenge! Complete various workout challenges throughout the week and win prizes. Categories include cardio, strength, and flexibility.',
                'start_time' => now()->subDays(30)->setTime(9, 0),
                'end_time' => now()->subDays(30)->setTime(17, 0),
                'location' => 'Main Gym Floor',
                'max_participants' => 50,
                'registered_count' => 45,
                'status' => 'completed',
            ],
            [
                'title' => 'Yoga Workshop: Advanced Poses',
                'description' => 'Master advanced yoga poses with our certified instructor. Learn inversions, arm balances, and deep stretches in this intensive workshop.',
                'start_time' => now()->subDays(14)->setTime(10, 0),
                'end_time' => now()->subDays(14)->setTime(14, 0),
                'location' => 'Studio A',
                'max_participants' => 20,
                'registered_count' => 18,
                'status' => 'completed',
            ],
            // Ongoing event
            [
                'title' => 'December Weight Loss Challenge',
                'description' => 'Our month-long weight loss challenge. Weekly weigh-ins, nutrition guidance, and group workouts. Prizes for top 3 participants!',
                'start_time' => now()->startOfMonth()->setTime(6, 0),
                'end_time' => now()->endOfMonth()->setTime(22, 0),
                'location' => 'Entire Facility',
                'max_participants' => 100,
                'registered_count' => 67,
                'status' => 'ongoing',
            ],
            // Upcoming events
            [
                'title' => 'New Year Kickoff Party',
                'description' => 'Celebrate the new year with us! Free workout classes, healthy snacks, gym tours for friends, and special membership offers.',
                'start_time' => now()->addDays(20)->setTime(10, 0),
                'end_time' => now()->addDays(20)->setTime(16, 0),
                'location' => 'Main Gym Floor',
                'max_participants' => 150,
                'registered_count' => 23,
                'status' => 'scheduled',
            ],
            [
                'title' => 'CrossFit Competition',
                'description' => 'Test your fitness in our friendly CrossFit competition. Categories for beginners and experienced athletes. Medals and prizes for winners!',
                'start_time' => now()->addDays(30)->setTime(8, 0),
                'end_time' => now()->addDays(30)->setTime(18, 0),
                'location' => 'CrossFit Area',
                'max_participants' => 30,
                'registered_count' => 12,
                'status' => 'scheduled',
            ],
            [
                'title' => 'Nutrition Seminar',
                'description' => 'Learn about proper nutrition for fitness goals. Topics include meal planning, supplements, pre/post workout nutrition, and healthy cooking tips.',
                'start_time' => now()->addDays(10)->setTime(18, 30),
                'end_time' => now()->addDays(10)->setTime(20, 30),
                'location' => 'Conference Room',
                'max_participants' => 40,
                'registered_count' => 15,
                'status' => 'scheduled',
            ],
            [
                'title' => 'Spin Marathon',
                'description' => '4-hour spin marathon for charity! Multiple instructors will rotate throughout the session. All proceeds go to local children\'s hospital.',
                'start_time' => now()->addDays(15)->setTime(8, 0),
                'end_time' => now()->addDays(15)->setTime(12, 0),
                'location' => 'Spin Room',
                'max_participants' => 25,
                'registered_count' => 8,
                'status' => 'scheduled',
            ],
            // Cancelled event
            [
                'title' => 'Outdoor Bootcamp',
                'description' => 'Join us for an outdoor bootcamp session in the park. Bring water and wear comfortable shoes.',
                'start_time' => now()->addDays(5)->setTime(7, 0),
                'end_time' => now()->addDays(5)->setTime(8, 30),
                'location' => 'Central Park',
                'max_participants' => 35,
                'registered_count' => 0,
                'status' => 'cancelled',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create(array_merge($eventData, [
                'parent_id' => $parentId,
            ]));
        }

        $this->command->info('✅ '.count($events).' events created successfully!');
    }
}
