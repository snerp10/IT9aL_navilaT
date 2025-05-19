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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->decimal('total_product_cost', 12, 2);
            $table->decimal('revenue_from_services', 12, 2);
            $table->decimal('total_expenses', 12, 2);
            $table->decimal('net_profit', 12, 2)->storedAs('(revenue_from_services + total_product_cost) - total_expenses');
            $table->date('report_date');
            $table->string('report_type')->default('custom');
            // Fix foreign key constraint - explicit reference to user_id
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Add the foreign key constraint explicitly
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};