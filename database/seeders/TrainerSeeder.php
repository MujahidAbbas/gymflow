<?php

namespace Database\Seeders;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('trainers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        $trainers = [
            [
                'name' => 'Marcus Johnson',
                'email' => 'marcus.trainer@fithub.com',
                'phone' => '555-1001',
                'date_of_birth' => '1988-03-15',
                'gender' => 'male',
                'address' => '123 Trainer Lane, Fitness City',
                'bio' => 'Certified personal trainer with 8+ years of experience specializing in strength training and body transformation. Former college athlete passionate about helping clients achieve their fitness goals.',
                'specializations' => ['Strength Training', 'CrossFit', 'HIIT'],
                'certifications' => [
                    ['name' => 'NASM Certified Personal Trainer', 'year' => 2016],
                    ['name' => 'CrossFit Level 2 Trainer', 'year' => 2018],
                    ['name' => 'TRX Suspension Training', 'year' => 2019],
                ],
                'years_of_experience' => 8,
                'hourly_rate' => 75.00,
                'status' => 'active',
                'availability' => [
                    'monday' => ['06:00-12:00', '16:00-20:00'],
                    'tuesday' => ['06:00-12:00', '16:00-20:00'],
                    'wednesday' => ['06:00-12:00'],
                    'thursday' => ['06:00-12:00', '16:00-20:00'],
                    'friday' => ['06:00-12:00', '16:00-20:00'],
                    'saturday' => ['08:00-14:00'],
                ],
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya.trainer@fithub.com',
                'phone' => '555-1002',
                'date_of_birth' => '1991-07-22',
                'gender' => 'female',
                'bio' => 'Yoga instructor and wellness coach dedicated to helping clients find balance in body and mind. Specializes in Vinyasa, Hatha, and restorative yoga practices.',
                'specializations' => ['Yoga', 'Pilates', 'Meditation'],
                'certifications' => [
                    ['name' => 'RYT-500 Yoga Alliance', 'year' => 2017],
                    ['name' => 'Pilates Mat Certification', 'year' => 2018],
                    ['name' => 'Mindfulness Meditation Teacher', 'year' => 2020],
                ],
                'years_of_experience' => 6,
                'hourly_rate' => 65.00,
                'status' => 'active',
                'availability' => [
                    'monday' => ['07:00-11:00', '17:00-21:00'],
                    'tuesday' => ['07:00-11:00', '17:00-21:00'],
                    'wednesday' => ['07:00-11:00', '17:00-21:00'],
                    'thursday' => ['07:00-11:00'],
                    'friday' => ['07:00-11:00', '17:00-21:00'],
                    'sunday' => ['09:00-13:00'],
                ],
            ],
            [
                'name' => 'Carlos Mendez',
                'email' => 'carlos.trainer@fithub.com',
                'phone' => '555-1003',
                'date_of_birth' => '1985-11-08',
                'gender' => 'male',
                'address' => '456 Boxing Blvd',
                'bio' => 'Former professional boxer turned fitness trainer. Expertise in boxing conditioning, cardio training, and weight loss programs. Known for high-energy sessions that deliver results.',
                'specializations' => ['Boxing', 'Cardio', 'Weight Loss'],
                'certifications' => [
                    ['name' => 'USA Boxing Certified Coach', 'year' => 2012],
                    ['name' => 'ACE Personal Trainer', 'year' => 2015],
                    ['name' => 'Precision Nutrition Level 1', 'year' => 2019],
                ],
                'years_of_experience' => 12,
                'hourly_rate' => 85.00,
                'status' => 'active',
                'availability' => [
                    'monday' => ['05:00-10:00', '18:00-21:00'],
                    'tuesday' => ['05:00-10:00', '18:00-21:00'],
                    'wednesday' => ['05:00-10:00', '18:00-21:00'],
                    'thursday' => ['05:00-10:00', '18:00-21:00'],
                    'friday' => ['05:00-10:00'],
                    'saturday' => ['07:00-12:00'],
                ],
            ],
            [
                'name' => 'Emma Williams',
                'email' => 'emma.trainer@fithub.com',
                'phone' => '555-1004',
                'date_of_birth' => '1993-05-30',
                'gender' => 'female',
                'bio' => 'Spinning and indoor cycling specialist with infectious energy. Creates dynamic workout experiences that combine music, motivation, and effective training techniques.',
                'specializations' => ['Spinning', 'HIIT', 'Group Fitness'],
                'certifications' => [
                    ['name' => 'Spinning Certified Instructor', 'year' => 2018],
                    ['name' => 'AFAA Group Fitness', 'year' => 2017],
                    ['name' => 'Schwinn Cycling Certification', 'year' => 2019],
                ],
                'years_of_experience' => 5,
                'hourly_rate' => 55.00,
                'status' => 'active',
                'availability' => [
                    'monday' => ['06:00-09:00', '17:00-20:00'],
                    'tuesday' => ['06:00-09:00', '17:00-20:00'],
                    'wednesday' => ['17:00-20:00'],
                    'thursday' => ['06:00-09:00', '17:00-20:00'],
                    'friday' => ['06:00-09:00', '17:00-20:00'],
                    'saturday' => ['08:00-11:00'],
                    'sunday' => ['09:00-12:00'],
                ],
            ],
            [
                'name' => 'David Park',
                'email' => 'david.trainer@fithub.com',
                'phone' => '555-1005',
                'date_of_birth' => '1982-09-12',
                'gender' => 'male',
                'address' => '789 Wellness Way',
                'bio' => 'Sports rehabilitation specialist focusing on injury prevention and recovery. Works with athletes and fitness enthusiasts to optimize performance and prevent injuries.',
                'specializations' => ['Sports Rehabilitation', 'Functional Training', 'Flexibility'],
                'certifications' => [
                    ['name' => 'NSCA Certified Strength and Conditioning Specialist', 'year' => 2010],
                    ['name' => 'FMS Level 2 Certified', 'year' => 2014],
                    ['name' => 'Corrective Exercise Specialist', 'year' => 2016],
                ],
                'years_of_experience' => 14,
                'hourly_rate' => 90.00,
                'status' => 'active',
                'availability' => [
                    'monday' => ['08:00-16:00'],
                    'tuesday' => ['08:00-16:00'],
                    'wednesday' => ['08:00-16:00'],
                    'thursday' => ['08:00-16:00'],
                    'friday' => ['08:00-14:00'],
                ],
            ],
            [
                'name' => 'Lisa Monroe',
                'email' => 'lisa.trainer@fithub.com',
                'phone' => '555-1006',
                'date_of_birth' => '1995-02-18',
                'gender' => 'female',
                'bio' => 'Energetic Zumba and dance fitness instructor bringing fun to fitness. Certified in multiple dance fitness formats, making exercise enjoyable for all levels.',
                'specializations' => ['Zumba', 'Dance Fitness', 'Aerobics'],
                'certifications' => [
                    ['name' => 'Zumba Instructor Network', 'year' => 2018],
                    ['name' => 'STRONG by Zumba', 'year' => 2019],
                    ['name' => 'AFAA Primary Group Exercise', 'year' => 2017],
                ],
                'years_of_experience' => 4,
                'hourly_rate' => 50.00,
                'status' => 'active',
                'availability' => [
                    'monday' => ['09:00-12:00', '18:00-21:00'],
                    'tuesday' => ['18:00-21:00'],
                    'wednesday' => ['09:00-12:00', '18:00-21:00'],
                    'thursday' => ['18:00-21:00'],
                    'friday' => ['09:00-12:00', '18:00-21:00'],
                    'saturday' => ['10:00-14:00'],
                ],
            ],
            [
                'name' => 'Robert Chen',
                'email' => 'robert.trainer@fithub.com',
                'phone' => '555-1007',
                'date_of_birth' => '1978-12-05',
                'gender' => 'male',
                'bio' => 'Senior fitness specialist focusing on training adults over 50. Expertise in safe, effective exercises for maintaining strength, balance, and mobility as we age.',
                'specializations' => ['Senior Fitness', 'Balance Training', 'Low Impact'],
                'certifications' => [
                    ['name' => 'ACE Senior Fitness Specialist', 'year' => 2012],
                    ['name' => 'SilverSneakers Instructor', 'year' => 2014],
                    ['name' => 'Arthritis Foundation Exercise Program', 'year' => 2016],
                ],
                'years_of_experience' => 15,
                'hourly_rate' => 60.00,
                'status' => 'inactive',
                'availability' => [
                    'monday' => ['09:00-14:00'],
                    'wednesday' => ['09:00-14:00'],
                    'friday' => ['09:00-14:00'],
                ],
                'notes' => 'Currently on medical leave - returning next month',
            ],
        ];

        foreach ($trainers as $trainerData) {
            Trainer::create(array_merge($trainerData, [
                'parent_id' => $parentId,
            ]));
        }

        $this->command->info('✅ '.count($trainers).' trainers created successfully!');
    }
}
