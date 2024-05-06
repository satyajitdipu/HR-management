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
        Schema::create('leave_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_admin_id')->constrained('leaves_admins');
            $table->string('employee_id');
            $table->string('Approved_by')->nullable();
            $table->string('leave_type');
            $table->enum('status', ['New', 'Pending', 'Approve', 'Decline'])->default('New');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('day');
            $table->string('leave_reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_employees');
    }
};
