<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Settings Management
            'manage settings',

            // Member Management
            'view members',
            'create members',
            'edit members',
            'delete members',

            // Plan Management
            'view plans',
            'create plans',
            'edit plans',
            'delete plans',

            // Payment Management
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',

            // Subscription Management
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',

            // Trainer Management
            'view trainers',
            'create trainers',
            'edit trainers',
            'delete trainers',

            // Class Management
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',

            // Attendance Management
            'view attendance',
            'manage attendance',

            // Reports
            'view reports',
            'download reports',

            // Notifications
            'send notifications',

            // Dashboard
            'view dashboard',

            // Super Admin Permissions - Customer Management
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'impersonate customers',

            // Super Admin Permissions - Platform Management
            'manage platform settings',
            'view platform analytics',
            'view all subscriptions',
            'manage all subscriptions',

            // Super Admin Permissions - Billing & Revenue
            'view platform revenue',
            'manage customer billing',

            // Super Admin Permissions - System Monitoring
            'view audit logs',
            'view system health',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permissions created successfully!');
    }

}
