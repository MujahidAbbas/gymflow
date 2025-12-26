<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ticket_replies')->truncate();
        DB::table('support_tickets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $members = Member::where('parent_id', $parentId)->get();
        $staff = User::where('parent_id', $parentId)->get();

        $tickets = [
            [
                'subject' => 'Locker lock not working',
                'description' => 'My locker (#47) lock seems to be jammed. I cannot open it to retrieve my belongings. Please help!',
                'priority' => 'urgent',
                'status' => 'resolved',
                'days_ago' => 5,
                'replies' => [
                    ['is_staff' => true, 'message' => 'We apologize for the inconvenience. Our maintenance team will check it immediately. Can you confirm your member ID?', 'hours_after' => 1],
                    ['is_staff' => false, 'message' => 'My member ID is #MBR-0003. I\'m at the gym right now.', 'hours_after' => 2],
                    ['is_staff' => true, 'message' => 'The lock has been fixed. Your belongings are safe. We\'ve issued you a new lock code: 4521. Sorry for the trouble!', 'hours_after' => 3],
                ],
            ],
            [
                'subject' => 'Billing discrepancy',
                'description' => 'I was charged twice for my membership this month. My bank statement shows two charges of $49.99 on December 1st and December 3rd. Please review and refund.',
                'priority' => 'high',
                'status' => 'in_progress',
                'days_ago' => 2,
                'replies' => [
                    ['is_staff' => true, 'message' => 'Thank you for bringing this to our attention. We\'re reviewing your account and will process a refund if confirmed. This may take 2-3 business days.', 'hours_after' => 4],
                ],
            ],
            [
                'subject' => 'Request for class schedule change',
                'description' => 'I love the evening yoga class but it conflicts with my work schedule now. Is there any possibility of adding a morning yoga session on weekdays?',
                'priority' => 'low',
                'status' => 'open',
                'days_ago' => 1,
                'replies' => [],
            ],
            [
                'subject' => 'Equipment malfunction - Treadmill #5',
                'description' => 'Treadmill #5 on the main floor is making a loud squeaking noise and the belt seems to be slipping. It could be dangerous.',
                'priority' => 'high',
                'status' => 'closed',
                'days_ago' => 10,
                'replies' => [
                    ['is_staff' => true, 'message' => 'Thank you for reporting this. We\'ve tagged the equipment for maintenance and put it out of service temporarily.', 'hours_after' => 2],
                    ['is_staff' => true, 'message' => 'The treadmill has been repaired and is back in service. Thank you for helping us maintain a safe environment!', 'hours_after' => 48],
                ],
            ],
            [
                'subject' => 'Personal training session cancellation',
                'description' => 'I need to cancel my PT session scheduled for tomorrow at 3 PM due to a family emergency. Can I reschedule for next week?',
                'priority' => 'medium',
                'status' => 'resolved',
                'days_ago' => 7,
                'replies' => [
                    ['is_staff' => true, 'message' => 'Session cancelled with no penalty due to emergency circumstances. Available slots for next week: Monday 3PM, Wednesday 4PM, Friday 2PM. Which works for you?', 'hours_after' => 1],
                    ['is_staff' => false, 'message' => 'Wednesday 4PM works perfectly. Thank you for understanding!', 'hours_after' => 3],
                    ['is_staff' => true, 'message' => 'Rescheduled for Wednesday 4PM. Hope everything is okay with your family!', 'hours_after' => 4],
                ],
            ],
            [
                'subject' => 'Lost item - Water bottle',
                'description' => 'I left my stainless steel water bottle (blue, Hydroflask brand) in the spin room yesterday around 6 PM. Has anyone turned it in?',
                'priority' => 'low',
                'status' => 'open',
                'days_ago' => 0,
                'replies' => [],
            ],
            [
                'subject' => 'WiFi connectivity issues',
                'description' => 'The gym WiFi has been very slow the past few days. I cannot stream my workout videos. Is there an issue with the network?',
                'priority' => 'medium',
                'status' => 'in_progress',
                'days_ago' => 3,
                'replies' => [
                    ['is_staff' => true, 'message' => 'We\'ve noticed increased usage lately. Our IT team is working on upgrading bandwidth. Should be resolved by end of week.', 'hours_after' => 6],
                ],
            ],
        ];

        $ticketCount = 0;
        $replyCount = 0;

        foreach ($tickets as $ticketData) {
            $member = $members->isNotEmpty() ? $members->random() : null;
            $staffMember = $staff->isNotEmpty() ? $staff->random() : $owner;
            $assignedTo = $ticketData['status'] !== 'open' && $staff->isNotEmpty() ? $staff->random() : null;

            $createdAt = now()->subDays($ticketData['days_ago']);

            $ticket = SupportTicket::create([
                'parent_id' => $parentId,
                'ticket_number' => '#TKT-'.str_pad($ticketCount + 1, 4, '0', STR_PAD_LEFT),
                'member_id' => $member?->id,
                'created_by' => $staffMember->id,
                'subject' => $ticketData['subject'],
                'description' => $ticketData['description'],
                'priority' => $ticketData['priority'],
                'status' => $ticketData['status'],
                'assigned_to' => $assignedTo?->id,
                'resolved_at' => in_array($ticketData['status'], ['resolved', 'closed']) ? $createdAt->copy()->addDays(1) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $ticketCount++;

            // Create replies
            foreach ($ticketData['replies'] as $replyData) {
                $replyUser = $replyData['is_staff']
                    ? ($staff->isNotEmpty() ? $staff->random() : $owner)
                    : $staffMember;

                TicketReply::create([
                    'support_ticket_id' => $ticket->id,
                    'user_id' => $replyUser->id,
                    'message' => $replyData['message'],
                    'created_at' => $createdAt->copy()->addHours($replyData['hours_after']),
                ]);
                $replyCount++;
            }
        }

        $this->command->info("✅ {$ticketCount} support tickets, {$replyCount} replies created!");
    }
}
