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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->time('punch_in')->nullable();
            $table->time('punch_out')->nullable();
            $table->float('production_hours')->default(0); // Set a default value of 0
            $table->float('break_hours')->default(0);
            $table->float('overtime_hours')->default(0);
            $table->boolean('punch');
            $table->timestamps();
    
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
