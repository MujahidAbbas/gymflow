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
        Schema::create('platform_subscription_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Starter Plan", "Professional Plan"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Monthly price
            $table->enum('interval', ['monthly', 'quarterly', 'yearly', 'lifetime'])->default('monthly');
            $table->integer('trial_days')->default(0); // Free trial period
            
            // Limits per tenant (gym owner)
            $table->integer('max_members_per_tenant')->nullable(); // 0 or null = unlimited
            $table->integer('max_trainers_per_tenant')->nullable();
            $table->integer('max_staff_per_tenant')->nullable();
            
            // Features
            $table->json('features')->nullable(); // JSON array of feature flags
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Highlight as "Most Popular"
            
            // Display order
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_subscription_tiers');
    }
};
