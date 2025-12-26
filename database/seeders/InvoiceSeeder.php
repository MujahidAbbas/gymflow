<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('invoice_payments')->truncate();
        DB::table('invoice_items')->truncate();
        DB::table('invoices')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $owner = User::role('owner')->first();

        if (! $owner) {
            $this->command->warn('No owner user found. Please run UserSeeder first.');

            return;
        }

        $parentId = $owner->id;
        $members = Member::where('parent_id', $parentId)->get();
        $plans = MembershipPlan::where('parent_id', $parentId)->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Please run MemberSeeder first.');

            return;
        }

        $paymentMethods = ['cash', 'card', 'bank_transfer'];
        $invoiceCount = 0;
        $itemCount = 0;
        $paymentCount = 0;

        // Create 40 invoices spanning last 3 months
        for ($i = 0; $i < 40; $i++) {
            $member = $members->random();
            $plan = $plans->isNotEmpty() ? $plans->random() : null;

            // Random date within last 3 months
            $invoiceDate = now()->subDays(rand(0, 90));
            $dueDate = $invoiceDate->copy()->addDays(15);

            // Determine status (valid: paid, unpaid, partially_paid, cancelled)
            $statusRoll = rand(1, 100);
            if ($statusRoll <= 50) {
                $status = 'paid';
            } elseif ($statusRoll <= 75) {
                $status = 'unpaid';
            } elseif ($statusRoll <= 90) {
                $status = 'partially_paid';
            } else {
                $status = 'cancelled';
            }

            // Mark some unpaid invoices as overdue by setting past due dates
            $isOverdue = $status === 'unpaid' && rand(1, 100) <= 30;

            // Calculate amounts
            $subtotal = $plan ? $plan->price : rand(30, 100);
            $taxAmount = round($subtotal * 0.10, 2); // 10% tax
            $discountAmount = rand(0, 1) ? round($subtotal * 0.05, 2) : 0; // 5% discount sometimes
            $totalAmount = round($subtotal + $taxAmount - $discountAmount, 2);

            // Calculate paid amount based on status
            switch ($status) {
                case 'paid':
                    $paidAmount = $totalAmount;
                    break;
                case 'partially_paid':
                    $paidAmount = round($totalAmount * (rand(30, 70) / 100), 2);
                    break;
                default:
                    $paidAmount = 0;
                    break;
            }

            $invoice = Invoice::create([
                'parent_id' => $parentId,
                'member_id' => $member->id,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'notes' => $isOverdue ? 'Payment reminder sent - OVERDUE' : null,
            ]);
            $invoiceCount++;

            // Create invoice item
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $plan ? "Membership: {$plan->name}" : 'Gym Services',
                'quantity' => 1,
                'unit_price' => $subtotal,
                'total_price' => $subtotal,
            ]);
            $itemCount++;

            // Create payment if paid or partially paid
            if ($paidAmount > 0) {
                InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $paidAmount,
                    'payment_date' => $status === 'paid'
                        ? $invoiceDate->copy()->addDays(rand(1, 10))
                        : $invoiceDate->copy()->addDays(rand(5, 20)),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'reference_number' => 'REF-'.strtoupper(substr(md5(uniqid()), 0, 8)),
                    'notes' => null,
                ]);
                $paymentCount++;
            }
        }

        $this->command->info("✅ {$invoiceCount} invoices, {$itemCount} items, {$paymentCount} payments created!");
    }
}
