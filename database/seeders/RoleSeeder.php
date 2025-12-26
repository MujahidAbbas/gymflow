<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all 6 roles for FitHub (including Super Admin)
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $trainer = Role::firstOrCreate(['name' => 'trainer', 'guard_name' => 'web']);
        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'guard_name' => 'web']);
        $member = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);

        // Super Admin gets ALL permissions (platform-wide)
        $superAdmin->givePermissionTo(Permission::all());

        // Owner (Gym Customer) gets all gym management permissions (but not platform permissions)
        $owner->givePermissionTo([
            // User Management
            'view users', 'create users', 'edit users', 'delete users',

            // Role Management
            'view roles', 'create roles', 'edit roles', 'delete roles',

            // Settings Management
            'manage settings',

            // Member Management
            'view members', 'create members', 'edit members', 'delete members',

            // Plan Management
            'view plans', 'create plans', 'edit plans', 'delete plans',

            // Payment Management
            'view payments', 'create payments', 'edit payments', 'delete payments',

            // Subscription Management
            'view subscriptions', 'create subscriptions', 'edit subscriptions', 'delete subscriptions',

            // Trainer Management
            'view trainers', 'create trainers', 'edit trainers', 'delete trainers',

            // Class Management
            'view classes', 'create classes', 'edit classes', 'delete classes',

            // Attendance Management
            'view attendance', 'manage attendance',

            // Reports
            'view reports', 'download reports',

            // Notifications
            'send notifications',

            // Dashboard
            'view dashboard',
        ]);

        // Manager gets most permissions except user/role deletion
        $manager->givePermissionTo([
            // User Management (except delete)
            'view users', 'create users', 'edit users',

            // Role Management (view only)
            'view roles',

            // Settings Management
            'manage settings',

            // Member Management (full access)
            'view members', 'create members', 'edit members', 'delete members',

            // Plan Management (full access)
            'view plans', 'create plans', 'edit plans', 'delete plans',

            // Payment Management (except delete)
            'view payments', 'create payments', 'edit payments',

            // Subscription Management (except delete)
            'view subscriptions', 'create subscriptions', 'edit subscriptions',

            // Trainer Management (except delete)
            'view trainers', 'create trainers', 'edit trainers',

            // Class Management (full access)
            'view classes', 'create classes', 'edit classes', 'delete classes',

            // Attendance Management (full access)
            'view attendance', 'manage attendance',

            // Reports (full access)
            'view reports', 'download reports',

            // Notifications
            'send notifications',

            // Dashboard
            'view dashboard',
        ]);

        // Trainer permissions (class and attendance focused)
        $trainer->givePermissionTo([
            // Member Management (view only)
            'view members',

            // Class Management (view only)
            'view classes',

            // Attendance Management (full access)
            'view attendance', 'manage attendance',

            // Dashboard
            'view dashboard',
        ]);

        // Receptionist permissions (front desk operations)
        $receptionist->givePermissionTo([
            // Member Management (create and edit)
            'view members', 'create members', 'edit members',

            // Payment Management (create and view)
            'view payments', 'create payments',

            // Subscription Management (create and view)
            'view subscriptions', 'create subscriptions',

            // Plan Management (view only)
            'view plans',

            // Class Management (view only)
            'view classes',

            // Attendance Management (view only)
            'view attendance',

            // Dashboard
            'view dashboard',
        ]);

        // Member permissions (minimal - self-service portal)
        $member->givePermissionTo([
            'view dashboard',
        ]);

        $this->command->info('✅ 6 Roles created successfully!');
        $this->command->info('   - Super Admin: Platform administrator (all permissions)');
        $this->command->info('   - Owner: Gym customer (all gym permissions)');
        $this->command->info('   - Manager: Most permissions (except critical deletions)');
        $this->command->info('   - Trainer: Class & attendance management');
        $this->command->info('   - Receptionist: Front desk operations');
        $this->command->info('   - Member: Dashboard access only');
    }

}
