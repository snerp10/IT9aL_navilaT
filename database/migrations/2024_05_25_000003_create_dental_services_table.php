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
        Schema::create('dental_services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('standard_cost', 10, 2);
            $table->integer('standard_duration')->nullable(); // Duration in minutes
            $table->string('category')->nullable(); // e.g., Cleaning, Cosmetic, Orthodontic, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_services');
    }
};