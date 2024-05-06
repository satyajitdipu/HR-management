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
        Schema::create('payments',function(Blueprint $table) {
            $table->increments('id');
            $table->string('r_payment_id');
            $table->string('method');
            $table->string('currency');
            $table->string('user_email');
            $table->string('amount');
            $table->longText('json_response');
            $table->unsignedBigInteger('job_id');
            $table->foreign('job_id')->references('id')->on('add_jobs');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('apply_job');
            $table->foreign('apply_job')->references('id')->on('apply_for_jobs');
            $table->timestamps();
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
