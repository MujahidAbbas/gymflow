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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->unsignedBigInteger('member_id');
            $table->date('date');
            $table->time('check_in_time');
            $table->time('check_out_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // calculated on check-out
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            $table->index('parent_id');
            $table->index('member_id');
            $table->index('date');

            // Prevent duplicate check-ins for same member on same day
            $table->unique(['member_id', 'date', 'check_in_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
