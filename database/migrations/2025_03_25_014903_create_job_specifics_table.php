<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('job_specifics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicant_details')->onDelete('cascade');
            $table->decimal('desired_salary', 10, 2)->nullable();
            $table->date('available_date');
            $table->text('job_interest');
            $table->text('why_hire');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_specifics');
    }
};
