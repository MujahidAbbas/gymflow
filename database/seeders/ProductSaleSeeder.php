<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_sales')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        // Products were created with superAdmin's ID, not owner's ID
        // Look for any products that exist
        $superAdmin = User::whereNull('parent_id')->first();
        $productParentId = $superAdmin ? $superAdmin->id : $parentId;

        // Use withoutGlobalScopes to bypass tenant scope since we're in a seeder
        $products = Product::withoutGlobalScopes()->where('parent_id', $productParentId)->where('active', true)->get();
        $members = Member::withoutGlobalScopes()->where('parent_id', $parentId)->get();
        $staff = User::where('parent_id', $parentId)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');

            return;
        }

        $paymentMethods = ['cash', 'card', 'bank_transfer'];
        $count = 0;

        // Create 50 product sales over last 60 days
        for ($i = 0; $i < 50; $i++) {
            $product = $products->random();
            $member = $members->isNotEmpty() && rand(0, 1) ? $members->random() : null;
            $soldBy = $staff->isNotEmpty() ? $staff->random() : null;

            $quantity = rand(1, 3);
            $unitPrice = $product->price;
            $totalAmount = round($unitPrice * $quantity, 2);

            // Apply discount sometimes (10% of sales get 5-15% discount)
            $discount = 0;
            if (rand(1, 100) <= 10) {
                $discountPercent = rand(5, 15);
                $discount = round($totalAmount * ($discountPercent / 100), 2);
            }

            $finalAmount = round($totalAmount - $discount, 2);
            $saleDate = now()->subDays(rand(0, 60))->subHours(rand(9, 20)); // Business hours

            $saleId = '#SALE-'.str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            ProductSale::create([
                'parent_id' => $parentId,
                'product_id' => $product->id,
                'member_id' => $member?->id,
                'sold_by' => $soldBy?->id,
                'sale_id' => $saleId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'final_amount' => $finalAmount,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'notes' => $discount > 0 ? 'Member discount applied' : null,
                'sale_date' => $saleDate,
            ]);
            $count++;
        }

        $this->command->info("✅ {$count} product sales created successfully!");
    }
}
