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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->unsignedBigInteger('user_id')->nullable(); // link to users table
            $table->string('member_id')->unique(); // #MBR-0001
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('membership_plan_id')->nullable();
            $table->date('membership_start_date')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->index('parent_id');
            $table->index('member_id');
            $table->index('status');
        });

        // Add foreign key after both tables exist
        Schema::table('members', function (Blueprint $table) {
            $table->foreign('membership_plan_id')->references('id')->on('membership_plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
