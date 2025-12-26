<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'module' => 'user_create',
                'subject' => 'Welcome to {gym_name}',
                'message' => "Hi {user_name},\n\nYour account has been created successfully.\n\nLogin Email: {email}\nTemporary Password: {password}\n\nPlease log in and change your password.\n\nThank you!",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'trainer_create',
                'subject' => 'Welcome Trainer - {gym_name}',
                'message' => "Hi {trainer_name},\n\nYou have been added as a trainer to our gym.\n\nTrainer ID: {trainer_id}\nEmail: {email}\n\nWelcome to the team!",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'member_create',
                'subject' => 'Welcome Member - {gym_name}',
                'message' => "Hi {member_name},\n\nWelcome to {gym_name}!\n\nMember ID: {member_id}\nMembership Plan: {membership_plan}\nValid Until: {expiry_date}\n\nWe look forward to seeing you at the gym!",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'trainer_assign',
                'subject' => 'New Trainee Assigned - {gym_name}',
                'message' => "Hi {trainer_name},\n\nA new trainee has been assigned to you:\n\nTrainee Name: {member_name}\nMember ID: {member_id}\n\nPlease reach out  to them to schedule your first session.",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'new_class',
                'subject' => 'New Class Available: {class_name}',
                'message' => "Hi,\n\nA new class has been scheduled:\n\nClass: {class_name}\nInstructor: {trainer_name}\nSchedule: {schedule_time}\nCapacity: {capacity}\n\nEnroll now!",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'workout_create',
                'subject' => 'New Workout Plan Assigned - {gym_name}',
                'message' => "Hi {member_name},\n\nA new workout plan has been created for you!\n\nWorkout ID: {workout_id}\nDuration: {duration}\n\nCheck your dashboard for details.",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'health_update',
                'subject' => 'Health Measurement Recorded - {gym_name}',
                'message' => "Hi {member_name},\n\nYour health measurements have been updated:\n\nWeight: {weight} kg\nBMI: {bmi}\nRecorded on: {date}\n\nKeep up the great work!",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'attendance_marked',
                'subject' => 'Attendance Confirmation - {gym_name}',
                'message' => "Hi {member_name},\n\nYour attendance has been marked:\n\nDate: {date}\nCheck-in: {check_in_time}\nCheck-out: {check_out_time}\n\nTotal Duration: {duration}",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'invoice_create',
                'subject' => 'New Invoice: {invoice_number} - {gym_name}',
                'message' => "Hi {member_name},\n\nA new invoice has been generated:\n\nInvoice Number: {invoice_number}\nAmount: {amount}\nDue Date: {due_date}\n\nPlease make payment by the due date.",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
            [
                'module' => 'locker_assign',
                'subject' => 'Locker Assigned: {locker_number} - {gym_name}',
                'message' => "Hi {member_name},\n\nLocker has been assigned to you:\n\nLocker Number: {locker_number}\nAssigned on: {start_date}\nValid until: {expiry_date}\n\nEnjoy your locker!",
                'enabled_email' => true,
                'enabled_web' => false,
            ],
        ];

        foreach ($templates as $template) {
            Notification::updateOrCreate(
                ['module' => $template['module'], 'parent_id' => 1], // Super admin templates
                $template
            );
        }

        $this->command->info('10 email templates seeded successfully!');
    }
}
