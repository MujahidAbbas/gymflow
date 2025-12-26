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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_class_id');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('trainer_id')->nullable(); // Assigned trainer
            $table->string('room_location')->nullable();
            $table->timestamps();

            $table->foreign('gym_class_id')->references('id')->on('gym_classes')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('set null');

            $table->index('gym_class_id');
            $table->index('day_of_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
