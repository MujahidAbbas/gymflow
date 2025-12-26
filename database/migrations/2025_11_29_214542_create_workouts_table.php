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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->string('workout_id')->unique(); // #WRK-0001
            $table->unsignedBigInteger('member_id')->nullable(); // assigned to member
            $table->unsignedBigInteger('trainer_id')->nullable(); // created by trainer
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('workout_date')->nullable(); // specific date or null for template
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('set null');

            $table->index('parent_id');
            $table->index('workout_id');
            $table->index('member_id');
            $table->index('workout_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
