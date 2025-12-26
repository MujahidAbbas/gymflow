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
        Schema::create('locker_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('locker_id');
            $table->unsignedBigInteger('member_id');
            $table->date('assigned_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('locker_id')->references('id')->on('lockers')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            $table->index('locker_id');
            $table->index('member_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_assignments');
    }
};
