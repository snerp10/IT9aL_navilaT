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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id('treatment_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('dentist_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->decimal('cost', 10, 2);
            $table->enum('status', ['Planned', 'In Progress', 'Completed'])->default('Planned');
            $table->date('treatment_date');
            $table->integer('tooth_number')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreign('appointment_id')->references('appointment_id')->on('appointments')->onDelete('set null');
            $table->foreign('dentist_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('dental_services')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};