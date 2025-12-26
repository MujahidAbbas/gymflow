<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please create an owner user first.');

            return;
        }

        $parentId = $owner->id;

        $categories = [
            [
                'name' => 'Yoga',
                'description' => 'Mind and body practice combining physical poses, breathing, and meditation',
                'color' => '#9C27B0',
                'is_active' => true,
            ],
            [
                'name' => 'Cardio',
                'description' => 'Aerobic exercises to improve cardiovascular endurance',
                'color' => '#F44336',
                'is_active' => true,
            ],
            [
                'name' => 'Strength Training',
                'description' => 'Resistance training to build muscle and strength',
                'color' => '#FF9800',
                'is_active' => true,
            ],
            [
                'name' => 'CrossFit',
                'description' => 'High-intensity functional training combining various exercises',
                'color' => '#4CAF50',
                'is_active' => true,
            ],
            [
                'name' => 'Pilates',
                'description' => 'Low-impact exercises focusing on core strength and flexibility',
                'color' => '#2196F3',
                'is_active' => true,
            ],
            [
                'name' => 'HIIT',
                'description' => 'High-Intensity Interval Training for maximum calorie burn',
                'color' => '#E91E63',
                'is_active' => true,
            ],
            [
                'name' => 'Spinning',
                'description' => 'Indoor cycling classes with varying intensity',
                'color' => '#00BCD4',
                'is_active' => true,
            ],
            [
                'name' => 'Boxing',
                'description' => 'Combat sport training for fitness and self-defense',
                'color' => '#795548',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create(array_merge($category, ['parent_id' => $parentId]));
        }

        $this->command->info('Categories created successfully for parent_id: '.$parentId);
    }
}
