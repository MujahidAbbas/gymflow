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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->string('name'); // e.g., "Basic", "Premium", "Enterprise"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Monthly price
            $table->integer('duration_days')->default(30); // Billing cycle
            $table->integer('trial_days')->default(0);
            $table->json('features')->nullable(); // JSON array of features
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('max_members')->nullable(); // Member limit
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('parent_id');
            $table->index('slug');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
