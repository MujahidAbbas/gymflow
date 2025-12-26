<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Income Types
            ['name' => 'Membership Fee', 'category' => 'income', 'description' => 'Monthly or annual membership fees'],
            ['name' => 'Personal Training', 'category' => 'income', 'description' => 'Personal training sessions'],
            ['name' => 'Class Fees', 'category' => 'income', 'description' => 'Group class fees'],
            ['name' => 'Product Sales', 'category' => 'income', 'description' => 'Gym equipment and supplements sales'],
            ['name' => 'Locker Rental', 'category' => 'income', 'description' => 'Locker rental fees'],

            // Expense Types
            ['name' => 'Equipment', 'category' => 'expense', 'description' => 'Gym equipment purchase and maintenance'],
            ['name' => 'Utilities', 'category' => 'expense', 'description' => 'Electricity, water, and other utilities'],
            ['name' => 'Rent', 'category' => 'expense', 'description' => 'Facility rent or lease'],
            ['name' => 'Salaries', 'category' => 'expense', 'description' => 'Staff salaries and wages'],
            ['name' => 'Marketing', 'category' => 'expense', 'description' => 'Advertising and promotional expenses'],
            ['name' => 'Maintenance', 'category' => 'expense', 'description' => 'Facility and equipment maintenance'],
            ['name' => 'Supplies', 'category' => 'expense', 'description' => 'Cleaning and operational supplies'],
        ];

        $owner = \App\Models\User::role('owner')->first();
        $parentId = $owner ? $owner->id : 1;

        foreach ($types as $type) {
            $type['parent_id'] = $parentId;
            $type['is_active'] = true;
            Type::create($type);
        }
    }
}
