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
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicant_details')->onDelete('cascade');
            $table->string('type');
            $table->date('date');
            $table->string('time');
            $table->string('duration');
            $table->string('location');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('round');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};
