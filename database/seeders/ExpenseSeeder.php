<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('expenses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;

        // Get expense types
        $expenseTypes = Type::where('parent_id', $parentId)
            ->where('category', 'expense')
            ->get()
            ->keyBy('name');

        if ($expenseTypes->isEmpty()) {
            $this->command->warn('No expense types found. Please run TypeSeeder first.');

            return;
        }

        $paymentMethods = ['cash', 'card', 'bank_transfer', 'cheque'];

        // Monthly recurring expenses for last 3 months
        $recurringExpenses = [
            ['type' => 'Rent', 'title' => 'Monthly Rent', 'amount' => 5000.00],
            ['type' => 'Utilities', 'title' => 'Electricity Bill', 'amount' => 800.00],
            ['type' => 'Utilities', 'title' => 'Water Bill', 'amount' => 200.00],
            ['type' => 'Salaries', 'title' => 'Staff Salaries', 'amount' => 15000.00],
            ['type' => 'Marketing', 'title' => 'Social Media Advertising', 'amount' => 500.00],
        ];

        $count = 0;

        // Create recurring expenses for last 3 months
        for ($month = 2; $month >= 0; $month--) {
            $expenseDate = now()->subMonths($month)->startOfMonth();

            foreach ($recurringExpenses as $expense) {
                $type = $expenseTypes->get($expense['type']);
                if (! $type) {
                    continue;
                }

                Expense::create([
                    'parent_id' => $parentId,
                    'type_id' => $type->id,
                    'title' => $expense['title'],
                    'description' => "{$expense['title']} for ".now()->subMonths($month)->format('F Y'),
                    'amount' => $expense['amount'] + rand(-50, 50), // Slight variation
                    'expense_date' => $expenseDate->copy()->addDays(rand(1, 10)),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                ]);
                $count++;
            }
        }

        // One-time expenses
        $oneTimeExpenses = [
            ['type' => 'Equipment', 'title' => 'New Treadmill', 'amount' => 2500.00, 'days_ago' => 45],
            ['type' => 'Equipment', 'title' => 'Dumbbell Set', 'amount' => 800.00, 'days_ago' => 30],
            ['type' => 'Equipment', 'title' => 'Yoga Mats (10x)', 'amount' => 350.00, 'days_ago' => 60],
            ['type' => 'Maintenance', 'title' => 'AC Repair', 'amount' => 450.00, 'days_ago' => 20],
            ['type' => 'Maintenance', 'title' => 'Locker Room Cleaning', 'amount' => 200.00, 'days_ago' => 15],
            ['type' => 'Maintenance', 'title' => 'Floor Polishing', 'amount' => 600.00, 'days_ago' => 75],
            ['type' => 'Supplies', 'title' => 'Cleaning Supplies', 'amount' => 150.00, 'days_ago' => 10],
            ['type' => 'Supplies', 'title' => 'Towels Bulk Order', 'amount' => 300.00, 'days_ago' => 55],
            ['type' => 'Marketing', 'title' => 'Flyers and Brochures', 'amount' => 250.00, 'days_ago' => 40],
            ['type' => 'Marketing', 'title' => 'Local Newspaper Ad', 'amount' => 400.00, 'days_ago' => 25],
        ];

        foreach ($oneTimeExpenses as $expense) {
            $type = $expenseTypes->get($expense['type']);
            if (! $type) {
                continue;
            }

            Expense::create([
                'parent_id' => $parentId,
                'type_id' => $type->id,
                'title' => $expense['title'],
                'description' => null,
                'amount' => $expense['amount'],
                'expense_date' => now()->subDays($expense['days_ago']),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
            ]);
            $count++;
        }

        $this->command->info("✅ {$count} expenses created successfully!");
    }
}
