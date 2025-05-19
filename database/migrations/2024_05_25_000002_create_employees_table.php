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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id'); // Changed to auto-incrementing ID
            $table->unsignedBigInteger('user_id'); // Non-nullable user_id (all staff must have accounts)
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('birth_date');
            $table->string('contact_number');
            $table->string('email');
            $table->text('address');
            $table->enum('role', ['Admin', 'Dentist', 'Receptionist']);
            $table->string('specialization')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->text('education')->nullable();
            $table->text('certifications')->nullable();
            $table->decimal('salary', 10, 2);
            $table->date('hire_date');
            $table->enum('employment_status', ['Active', 'Inactive', 'Terminated']);
            $table->timestamps();

            // Foreign key constraint ensuring each employee has a user account
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            // Added a unique constraint to ensure one user can only have one employee record
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};