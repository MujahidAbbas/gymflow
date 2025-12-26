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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->unsignedBigInteger('user_id')->nullable(); // link to users table
            $table->string('trainer_id')->unique(); // #TRNR-0001
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->json('specializations')->nullable(); // ['Yoga', 'Cardio', 'Strength Training']
            $table->json('certifications')->nullable(); // Array of certification objects
            $table->integer('years_of_experience')->default(0);
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('availability')->nullable(); // Weekly schedule
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->index('parent_id');
            $table->index('trainer_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
