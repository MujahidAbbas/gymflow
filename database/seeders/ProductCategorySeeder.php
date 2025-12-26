<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Truncate tables (disable foreign key checks temporarily)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Truncated product_categories table.');

        $categories = [
            [
                'name' => 'Supplements',
                'description' => 'Protein powders, vitamins, and nutritional supplements',
                'active' => true,
            ],
            [
                'name' => 'Equipment',
                'description' => 'Gym equipment and workout accessories',
                'active' => true,
            ],
            [
                'name' => 'Apparel',
                'description' => 'Gym clothing and workout wear',
                'active' => true,
            ],
            [
                'name' => 'Accessories',
                'description' => 'Gym bags, water bottles, and other accessories',
                'active' => true,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Energy drinks, protein shakes, and sports drinks',
                'active' => true,
            ],
        ];

        // Get super admin (parent_id = null)
        $superAdmin = \App\Models\User::whereNull('parent_id')->first();

        if (!$superAdmin) {
            $this->command->error('No super admin found. Please create a super admin first.');
            return;
        }

        foreach ($categories as $category) {
            ProductCategory::create(array_merge($category, [
                'parent_id' => $superAdmin->id,
            ]));
        }

        $this->command->info('Product categories seeded successfully!');
    }
}
