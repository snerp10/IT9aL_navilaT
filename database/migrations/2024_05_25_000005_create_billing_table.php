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
        Schema::create('billing', function (Blueprint $table) {
            $table->id('billing_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('treatment_id');
            $table->string('invoice_number')->unique();
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->enum('payment_status', ['Pending', 'Paid', 'Overdue']);
            $table->enum('payment_method', ['Cash', 'GCash', 'Maya', 'PayPal']);
            $table->dateTime('due_date')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreign('treatment_id')->references('treatment_id')->on('treatments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing');
    }
};