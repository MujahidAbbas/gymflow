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
        Schema::create('class_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_class_id');
            $table->unsignedBigInteger('member_id');
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('gym_class_id')->references('id')->on('gym_classes')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            // Prevent duplicate enrollments
            $table->unique(['gym_class_id', 'member_id']);

            $table->index('gym_class_id');
            $table->index('member_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_assigns');
    }
};
