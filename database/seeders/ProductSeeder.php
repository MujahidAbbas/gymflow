<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate tables (disable foreign key checks temporarily)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_sales')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Truncated products and product_sales tables.');

        $superAdmin = \App\Models\User::whereNull('parent_id')->first();

        if (! $superAdmin) {
            $this->command->error('No super admin found.');

            return;
        }

        // Get categories
        $supplements = ProductCategory::where('name', 'Supplements')->first();
        $equipment = ProductCategory::where('name', 'Equipment')->first();
        $apparel = ProductCategory::where('name', 'Apparel')->first();
        $accessories = ProductCategory::where('name', 'Accessories')->first();
        $beverages = ProductCategory::where('name', 'Beverages')->first();

        $products = [
            // Supplements
            [
                'category_id' => $supplements?->id,
                'name' => 'Whey Protein Powder',
                'description' => '100% pure whey protein isolate - 2kg',
                'sku' => 'SUP-WP-001',
                'price' => 49.99,
                'cost_price' => 30.00,
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'unit' => 'kg',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $supplements?->id,
                'name' => 'Creatine Monohydrate',
                'description' => 'Micronized creatine for muscle strength - 500g',
                'sku' => 'SUP-CR-002',
                'price' => 29.99,
                'cost_price' => 18.00,
                'stock_quantity' => 35,
                'min_stock_level' => 10,
                'unit' => 'kg',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $supplements?->id,
                'name' => 'BCAA Powder',
                'description' => 'Branch chain amino acids - Tropical flavor',
                'sku' => 'SUP-BC-003',
                'price' => 34.99,
                'cost_price' => 20.00,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'unit' => 'kg',
                'active' => true,
                'track_inventory' => true,
            ],

            // Equipment
            [
                'category_id' => $equipment?->id,
                'name' => 'Resistance Bands Set',
                'description' => 'Set of 5 resistance bands with varying strengths',
                'sku' => 'EQP-RB-001',
                'price' => 24.99,
                'cost_price' => 12.00,
                'stock_quantity' => 40,
                'min_stock_level' => 8,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $equipment?->id,
                'name' => 'Yoga Mat Premium',
                'description' => 'Non-slip premium yoga mat with carry strap',
                'sku' => 'EQP-YM-002',
                'price' => 39.99,
                'cost_price' => 22.00,
                'stock_quantity' => 30,
                'min_stock_level' => 10,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $equipment?->id,
                'name' => 'Dumbbell Set 20kg',
                'description' => 'Adjustable dumbbell set - 2.5kg to 20kg',
                'sku' => 'EQP-DB-003',
                'price' => 89.99,
                'cost_price' => 55.00,
                'stock_quantity' => 15,
                'min_stock_level' => 5,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],

            // Apparel
            [
                'category_id' => $apparel?->id,
                'name' => 'Performance T-Shirt',
                'description' => 'Moisture-wicking athletic t-shirt - Various sizes',
                'sku' => 'APP-TS-001',
                'price' => 19.99,
                'cost_price' => 8.00,
                'stock_quantity' => 60,
                'min_stock_level' => 15,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $apparel?->id,
                'name' => 'Training Shorts',
                'description' => 'Lightweight training shorts with pockets',
                'sku' => 'APP-SH-002',
                'price' => 24.99,
                'cost_price' => 12.00,
                'stock_quantity' => 45,
                'min_stock_level' => 10,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],

            // Accessories
            [
                'category_id' => $accessories?->id,
                'name' => 'Gym Bag Premium',
                'description' => 'Large capacity gym bag with shoe compartment',
                'sku' => 'ACC-GB-001',
                'price' => 44.99,
                'cost_price' => 25.00,
                'stock_quantity' => 20,
                'min_stock_level' => 5,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $accessories?->id,
                'name' => 'Water Bottle 1L',
                'description' => 'BPA-free sports water bottle with measurements',
                'sku' => 'ACC-WB-002',
                'price' => 12.99,
                'cost_price' => 5.00,
                'stock_quantity' => 80,
                'min_stock_level' => 20,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],

            // Beverages
            [
                'category_id' => $beverages?->id,
                'name' => 'Energy Drink',
                'description' => 'Pre-workout energy drink - 250ml can',
                'sku' => 'BEV-ED-001',
                'price' => 3.99,
                'cost_price' => 1.50,
                'stock_quantity' => 100,
                'min_stock_level' => 30,
                'unit' => 'piece',
                'active' => true,
                'track_inventory' => true,
            ],
            [
                'category_id' => $beverages?->id,
                'name' => 'Protein Shake RTD',
                'description' => 'Ready-to-drink protein shake - Chocolate 330ml',
                'sku' => 'BEV-PS-002',
                'price' => 4.99,
                'cost_price' => 2.50,
                'stock_quantity' => 75,
                'min_stock_level' => 25,
                'unit' => 'bottle',
                'active' => true,
                'track_inventory' => true,
            ],
        ];

        // Create products with manual product_id to avoid conflicts
        foreach ($products as $index => $product) {
            $productId = '#PRD-'.str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            Product::create(array_merge($product, [
                'parent_id' => $superAdmin->id,
                'product_id' => $productId,
            ]));
        }

        $this->command->info('Sample products seeded successfully!');
    }
}
