<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('sold_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('sale_id')->unique();
           $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'other'])->default('cash');
            $table->text('notes')->nullable();
            $table->timestamp('sale_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sales');
    }
};
