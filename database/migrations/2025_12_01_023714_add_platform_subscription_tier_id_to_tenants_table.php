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
        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('platform_subscription_tier_id')->nullable()->after('user_id');
            
            $table->foreign('platform_subscription_tier_id')
                  ->references('id')
                  ->on('platform_subscription_tiers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['platform_subscription_tier_id']);
            $table->dropColumn('platform_subscription_tier_id');
        });
    }
};
