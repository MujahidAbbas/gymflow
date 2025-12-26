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
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('duration_type', ['daily', 'weekly', 'monthly', 'quarterly', 'half_yearly', 'yearly', 'lifetime']);
            $table->integer('duration_value')->default(1); // number of months/days etc
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // JSON array of features
            $table->integer('max_classes')->nullable(); // max classes per month (null = unlimited)
            $table->boolean('personal_training')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
