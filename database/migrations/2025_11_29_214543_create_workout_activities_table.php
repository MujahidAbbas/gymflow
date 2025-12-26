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
        Schema::create('workout_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workout_id');
            $table->string('exercise_name');
            $table->text('description')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->integer('duration_minutes')->nullable(); // for cardio
            $table->integer('rest_seconds')->default(60); // rest between sets
            $table->decimal('weight_kg', 8, 2)->nullable(); // weight used
            $table->integer('order')->default(0); // order in workout
            $table->boolean('is_completed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('workout_id')->references('id')->on('workouts')->onDelete('cascade');

            $table->index('workout_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_activities');
    }
};
