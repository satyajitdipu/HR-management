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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('task_status', ['Completed Tasks', 'Inprogress Tasks', 'On Hold Tasks', 'Pending Tasks', 'Review Tasks'])->default('Review Tasks');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');
            $table->integer('time_assign');
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
