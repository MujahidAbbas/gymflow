<?php

namespace Database\Seeders;

use App\Models\NoticeBoard;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoticeBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notice_boards')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        $notices = [
            [
                'title' => 'Holiday Hours - Christmas & New Year',
                'content' => "Please note our modified operating hours for the holiday season:\n\n**December 24 (Christmas Eve):** 6:00 AM - 2:00 PM\n**December 25 (Christmas Day):** CLOSED\n**December 31 (New Year's Eve):** 6:00 AM - 6:00 PM\n**January 1 (New Year's Day):** 10:00 AM - 6:00 PM\n\nRegular hours resume January 2nd.",
                'priority' => 'high',
                'publish_date' => now()->subDays(5),
                'expiry_date' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'title' => 'New Equipment Arrival',
                'content' => "We're excited to announce new equipment additions to our gym floor:\n\n• 2 new cable machines\n• Upgraded rowing machines\n• Additional kettlebell sets (8-32 kg)\n• New adjustable benches\n\nTrainers are available to demonstrate proper usage.",
                'priority' => 'medium',
                'publish_date' => now()->subDays(3),
                'expiry_date' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'title' => 'Pool Maintenance Notice',
                'content' => "The swimming pool will undergo scheduled maintenance on the following dates:\n\n**January 15-16:** Deep cleaning and filter replacement\n\nThe pool will be closed during this time. Locker rooms remain accessible. We apologize for any inconvenience.",
                'priority' => 'high',
                'publish_date' => now()->subDays(1),
                'expiry_date' => now()->addDays(35),
                'is_active' => true,
            ],
            [
                'title' => 'Parking Lot Reminder',
                'content' => "Please remember:\n\n• Park only in designated spaces\n• Display your member parking pass\n• Maximum 3-hour parking during peak times\n• Respect handicap spaces\n\nVehicles without valid passes may be towed.",
                'priority' => 'low',
                'publish_date' => now()->subDays(10),
                'expiry_date' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Personal Training Special Offer',
                'content' => "Start the new year strong! Book a package of 10 personal training sessions and get 2 FREE sessions.\n\nOffer valid until January 31st. Contact the front desk or speak with any trainer to book.",
                'priority' => 'medium',
                'publish_date' => now()->subDays(2),
                'expiry_date' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'title' => 'Locker Room Etiquette',
                'content' => "Friendly reminders for all members:\n\n• Bring a lock for your locker\n• Keep belongings in designated areas\n• Clean up after yourself\n• Report any maintenance issues to staff\n• Respect other members' privacy\n\nThank you for keeping our facilities clean!",
                'priority' => 'low',
                'publish_date' => now()->subDays(20),
                'expiry_date' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Guest Policy Update',
                'content' => "Updated guest policy effective immediately:\n\n• Members may bring 1 guest per visit\n• Guest fee: $15 per day\n• Guests must sign a waiver\n• Premium members: 2 free guest passes per month\n\nGuests under 16 must be accompanied by a parent/guardian.",
                'priority' => 'medium',
                'publish_date' => now()->subDays(7),
                'expiry_date' => null,
                'is_active' => true,
            ],
            // Expired notice
            [
                'title' => 'Thanksgiving Hours',
                'content' => 'Gym will be closed on Thanksgiving Day. Happy Holidays!',
                'priority' => 'high',
                'publish_date' => now()->subDays(30),
                'expiry_date' => now()->subDays(15),
                'is_active' => false,
            ],
        ];

        foreach ($notices as $noticeData) {
            NoticeBoard::create(array_merge($noticeData, [
                'parent_id' => $parentId,
            ]));
        }

        $this->command->info('✅ '.count($notices).' notice board entries created successfully!');
    }
}
