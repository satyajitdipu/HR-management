<?php

use App\Models\AddJob;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_for_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title')->nullable();
            $table->string('name')->nullable();
            $table->enum('status', ['New', 'Hired', 'Rejected', 'Interviewed','Offered','Pending'])->default('New');
            $table->enum('offer_status', ['Approved', 'Rejected', 'Requested', 'Interviewed','Offered','Pending'])->default(null);
            $table->enum('status_selection', ['Action pending', 'Resume selected', 'Resume Rejected', 'Aptitude Selected','Aptitude rejected','video call selected','Video call rejected','Offered'])->default(null);
            $table->string('phone')->nullable();
            $table->json('interview')->nullable();
            $table->string('email')->nullable();
            $table->string('message')->nullable();
            $table->string('round')->nullable();
            $table->string('job_id');
            $table->foreign('job_id')->references('id')->on('add_jobs');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('cv_upload')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apply_for_jobs');
    }
};
