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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->string('type')->default('member')->after('email'); // owner, admin, trainer, receptionist, member
            $table->string('subscription')->nullable()->after('type');
            $table->date('subscription_expire_date')->nullable()->after('subscription');

            $table->index('parent_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['type']);
            $table->dropColumn(['parent_id', 'type', 'subscription', 'subscription_expire_date']);
        });
    }
};
