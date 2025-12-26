<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contacts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        $contacts = [
            [
                'name' => 'Alex Thompson',
                'email' => 'alex.thompson@email.com',
                'contact_number' => '555-2001',
                'subject' => 'Membership Inquiry',
                'message' => 'Hi, I am interested in joining your gym. Could you please provide information about your monthly membership plans and what facilities are included? I am particularly interested in weight training and group classes.',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'contact_number' => '555-2002',
                'subject' => 'Personal Training Rates',
                'message' => 'I would like to know your personal training rates. I am a beginner and would need guidance on proper form and creating a workout routine. How many sessions per week would you recommend?',
                'created_at' => now()->subDays(1),
            ],
            [
                'name' => 'Tom Richards',
                'email' => 'tom.richards@email.com',
                'contact_number' => '555-2003',
                'subject' => 'Group Class Schedule',
                'message' => 'What group classes do you offer in the evenings after 6 PM? I work until 5:30 and am looking for classes that fit my schedule. Specifically interested in spinning and HIIT.',
                'created_at' => now()->subHours(12),
            ],
            [
                'name' => 'Jenny Liu',
                'email' => 'jenny.liu@email.com',
                'contact_number' => '555-2004',
                'subject' => 'Corporate Membership',
                'message' => 'Does your gym offer corporate membership discounts? Our company has about 20 employees interested in joining. Please send me details about any group rates available.',
                'created_at' => now()->subDays(3),
            ],
            [
                'name' => 'Steve Miller',
                'email' => 'steve.miller@email.com',
                'contact_number' => '555-2005',
                'subject' => 'Trial Membership',
                'message' => 'Do you offer any trial memberships or day passes? I would like to try out the facilities before committing to a monthly plan. Also, what are your operating hours?',
                'created_at' => now()->subHours(6),
            ],
            [
                'name' => 'Diana Ross',
                'email' => 'diana.ross@email.com',
                'contact_number' => '555-2006',
                'subject' => 'Yoga Classes for Beginners',
                'message' => 'I have never done yoga before but want to start. Do you have beginner-friendly yoga classes? What should I bring to my first class?',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Chris Evans',
                'email' => 'chris.evans@email.com',
                'contact_number' => '555-2007',
                'subject' => 'Swimming Pool Availability',
                'message' => 'Does your gym have a swimming pool? If so, what are the pool hours and is there lap swimming available?',
                'created_at' => now()->subDays(7),
            ],
            [
                'name' => 'Linda Green',
                'email' => 'linda.green@email.com',
                'contact_number' => null,
                'subject' => 'Senior Fitness Programs',
                'message' => 'I am 62 years old and looking for a gym with programs suitable for seniors. Do you have trainers specialized in working with older adults? What low-impact classes do you offer?',
                'created_at' => now()->subDays(4),
            ],
        ];

        foreach ($contacts as $contactData) {
            Contact::create(array_merge($contactData, [
                'parent_id' => $parentId,
            ]));
        }

        $this->command->info('✅ '.count($contacts).' contacts/inquiries created successfully!');
    }
}
