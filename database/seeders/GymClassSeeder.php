<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GymClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GymClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('class_assigns')->truncate();
        DB::table('class_schedules')->truncate();
        DB::table('gym_classes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $categories = Category::where('parent_id', $parentId)->get()->keyBy('name');

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');

            return;
        }

        $classes = [
            // Yoga classes
            [
                'category' => 'Yoga',
                'name' => 'Morning Sunrise Yoga',
                'description' => 'Start your day with this gentle yet energizing yoga flow. Perfect for all levels, focusing on breath work and mindful movement to prepare you for the day ahead.',
                'max_capacity' => 25,
                'duration_minutes' => 60,
                'difficulty_level' => 'beginner',
                'status' => 'active',
            ],
            [
                'category' => 'Yoga',
                'name' => 'Power Vinyasa Flow',
                'description' => 'Dynamic and challenging yoga class linking breath with movement. Build strength, flexibility, and endurance in this fast-paced session.',
                'max_capacity' => 20,
                'duration_minutes' => 75,
                'difficulty_level' => 'intermediate',
                'status' => 'active',
            ],
            // Cardio classes
            [
                'category' => 'Cardio',
                'name' => 'Cardio Blast',
                'description' => 'High-energy cardiovascular workout combining dance moves, aerobics, and body-weight exercises. Burn calories and have fun!',
                'max_capacity' => 30,
                'duration_minutes' => 45,
                'difficulty_level' => 'intermediate',
                'status' => 'active',
            ],
            [
                'category' => 'Cardio',
                'name' => 'Step Aerobics',
                'description' => 'Classic step aerobics class with modern music. Great for improving coordination while getting an effective cardio workout.',
                'max_capacity' => 25,
                'duration_minutes' => 50,
                'difficulty_level' => 'beginner',
                'status' => 'active',
            ],
            // Strength Training
            [
                'category' => 'Strength Training',
                'name' => 'Total Body Conditioning',
                'description' => 'Full body strength workout using dumbbells, barbells, and body weight. Build muscle and increase metabolism.',
                'max_capacity' => 20,
                'duration_minutes' => 60,
                'difficulty_level' => 'intermediate',
                'status' => 'active',
            ],
            [
                'category' => 'Strength Training',
                'name' => 'Core & More',
                'description' => 'Focused core strengthening class targeting abs, obliques, and lower back. Essential for stability and posture.',
                'max_capacity' => 25,
                'duration_minutes' => 30,
                'difficulty_level' => 'beginner',
                'status' => 'active',
            ],
            // CrossFit
            [
                'category' => 'CrossFit',
                'name' => 'CrossFit Fundamentals',
                'description' => 'Learn the basics of CrossFit movements in a supportive environment. Perfect introduction to functional fitness.',
                'max_capacity' => 15,
                'duration_minutes' => 60,
                'difficulty_level' => 'beginner',
                'status' => 'active',
            ],
            [
                'category' => 'CrossFit',
                'name' => 'CrossFit WOD',
                'description' => 'Workout of the Day - high-intensity functional movements. Different workout every day to keep you challenged.',
                'max_capacity' => 12,
                'duration_minutes' => 60,
                'difficulty_level' => 'advanced',
                'status' => 'active',
            ],
            // HIIT
            [
                'category' => 'HIIT',
                'name' => 'HIIT Express',
                'description' => 'Quick but intense interval training session. Maximum results in minimum time with work-rest intervals.',
                'max_capacity' => 20,
                'duration_minutes' => 30,
                'difficulty_level' => 'intermediate',
                'status' => 'active',
            ],
            [
                'category' => 'HIIT',
                'name' => 'Tabata Training',
                'description' => 'Classic Tabata format - 20 seconds work, 10 seconds rest. Extreme calorie burn in a short time.',
                'max_capacity' => 18,
                'duration_minutes' => 45,
                'difficulty_level' => 'advanced',
                'status' => 'active',
            ],
            // Spinning
            [
                'category' => 'Spinning',
                'name' => 'Spin & Sweat',
                'description' => 'Indoor cycling class with music-driven intervals. Hills, sprints, and endurance rides combined.',
                'max_capacity' => 25,
                'duration_minutes' => 45,
                'difficulty_level' => 'intermediate',
                'status' => 'active',
            ],
            [
                'category' => 'Spinning',
                'name' => 'Beginner Spin',
                'description' => 'Introduction to indoor cycling for newcomers. Learn proper form and bike setup.',
                'max_capacity' => 20,
                'duration_minutes' => 40,
                'difficulty_level' => 'beginner',
                'status' => 'active',
            ],
            // Pilates
            [
                'category' => 'Pilates',
                'name' => 'Mat Pilates',
                'description' => 'Classical Pilates exercises on the mat. Focus on core strength, flexibility, and body awareness.',
                'max_capacity' => 20,
                'duration_minutes' => 55,
                'difficulty_level' => 'beginner',
                'status' => 'active',
            ],
            // Boxing
            [
                'category' => 'Boxing',
                'name' => 'Boxing Bootcamp',
                'description' => 'Combine boxing techniques with cardio exercises. Learn punching combinations while getting fit.',
                'max_capacity' => 15,
                'duration_minutes' => 60,
                'difficulty_level' => 'intermediate',
                'status' => 'active',
            ],
            // Cancelled class for testing
            [
                'category' => 'Yoga',
                'name' => 'Advanced Ashtanga',
                'description' => 'Traditional Ashtanga yoga primary series. For experienced practitioners only.',
                'max_capacity' => 15,
                'duration_minutes' => 90,
                'difficulty_level' => 'advanced',
                'status' => 'cancelled',
            ],
        ];

        foreach ($classes as $classData) {
            $categoryName = $classData['category'];
            unset($classData['category']);

            $category = $categories->get($categoryName);

            GymClass::create(array_merge($classData, [
                'parent_id' => $parentId,
                'category_id' => $category?->id,
            ]));
        }

        $this->command->info('✅ '.count($classes).' gym classes created successfully!');
    }
}
