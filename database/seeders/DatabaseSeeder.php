<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // =====================================================
            // PHASE 1: Foundation (Authentication & Authorization)
            // =====================================================
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            TenantSeeder::class,
            SettingsSeeder::class,

            // =====================================================
            // PHASE 2: Core Reference Data
            // =====================================================
            CategorySeeder::class,
            TypeSeeder::class,
            MembershipPlanSeeder::class,

            // =====================================================
            // PHASE 3: Products (Required for Sales)
            // =====================================================
            ProductCategorySeeder::class,
            ProductSeeder::class,

            // =====================================================
            // PHASE 4: People (Members, Trainers, Contacts)
            // =====================================================
            TrainerSeeder::class,
            MemberSeeder::class,
            ContactSeeder::class,

            // =====================================================
            // PHASE 5: Classes & Schedules
            // =====================================================
            GymClassSeeder::class,
            ClassScheduleSeeder::class,
            ClassAssignSeeder::class,

            // =====================================================
            // PHASE 6: Facilities
            // =====================================================
            LockerSeeder::class,

            // =====================================================
            // PHASE 7: Financial
            // =====================================================
            InvoiceSeeder::class,
            ExpenseSeeder::class,
            ProductSaleSeeder::class,

            // =====================================================
            // PHASE 8: Activities
            // =====================================================
            WorkoutSeeder::class,
            HealthSeeder::class,
            AttendanceSeeder::class,

            // =====================================================
            // PHASE 9: Communication
            // =====================================================
            EventSeeder::class,
            NoticeBoardSeeder::class,
            SupportTicketSeeder::class,

            // =====================================================
            // PHASE 10: Subscriptions
            // =====================================================
            SubscriptionSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->newLine();
        $this->command->table(
            ['Category', 'Seeders Run'],
            [
                ['Foundation', 'Permissions, Roles, Users, Tenants, Settings'],
                ['Core Data', 'Categories, Types, Membership Plans'],
                ['Products', 'Product Categories, Products'],
                ['People', 'Trainers, Members, Contacts'],
                ['Classes', 'Gym Classes, Schedules, Assignments'],
                ['Facilities', 'Lockers & Assignments'],
                ['Financial', 'Invoices, Expenses, Product Sales'],
                ['Activities', 'Workouts, Health Records, Attendance'],
                ['Communication', 'Events, Notice Board, Support Tickets'],
                ['Subscriptions', 'Plans & Member Subscriptions'],
            ]
        );
        $this->command->newLine();
        $this->command->info('📧 Login credentials: owner@fithub.com / password');
    }
}
