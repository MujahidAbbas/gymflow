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
        Schema::create('notice_boards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // multi-tenant
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('publish_date');
            $table->date('expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('parent_id');
            $table->index('priority');
            $table->index('publish_date');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_boards');
    }
};
