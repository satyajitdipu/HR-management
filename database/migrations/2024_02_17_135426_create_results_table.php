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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apply_for_job_id')->constrained('apply_for_jobs')->onDelete('cascade');
            $table->foreignId('add_job_id')->constrained('add_jobs')->onDelete('cascade');
            $table->json('catagory_wise_mark')->nullable();
            $table->string('total_mark')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
