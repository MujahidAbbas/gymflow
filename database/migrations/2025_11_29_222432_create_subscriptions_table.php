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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('subscription_plan_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('trial_end_date')->nullable();
            $table->enum('status', ['active', 'cancelled', 'expired', 'trial'])->default('trial');
            $table->boolean('auto_renew')->default(true);
            $table->string('payment_gateway')->nullable(); // stripe, paypal, flutterwave
            $table->string('gateway_subscription_id')->nullable(); // External subscription ID
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');

            $table->index('member_id');
            $table->index('subscription_plan_id');
            $table->index('status');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
