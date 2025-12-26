<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('member_id');
            $table->string('transaction_id')->unique(); // Our internal transaction ID
            $table->string('gateway_transaction_id')->nullable(); // External payment ID
            $table->string('payment_gateway'); // stripe, paypal, flutterwave
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->enum('type', ['subscription', 'one_time'])->default('subscription');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Store gateway response
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            $table->index('subscription_id');
            $table->index('member_id');
            $table->index('transaction_id');
            $table->index('gateway_transaction_id');
            $table->index('payment_gateway');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
