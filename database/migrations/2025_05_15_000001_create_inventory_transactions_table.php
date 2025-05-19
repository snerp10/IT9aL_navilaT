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
        // Check if the table exists before trying to create it
        if (!Schema::hasTable('inventory_transactions')) {
            Schema::create('inventory_transactions', function (Blueprint $table) {
                $table->id('transaction_id');
                $table->unsignedBigInteger('inventory_id');
                $table->unsignedBigInteger('product_id');
                $table->enum('transaction_type', ['Stock In', 'Stock Out', 'Adjustment']);
                $table->integer('quantity');
                $table->string('reference')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
                $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
                $table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};