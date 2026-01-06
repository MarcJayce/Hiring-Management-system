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
        Schema::create('internship_specifics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicant_details')->onDelete('cascade');
            $table->enum('internship_type', ['voluntary', 'academic']);
            $table->date('desired_start_date');
            $table->date('desired_end_date');
            $table->integer('hours_required');
            $table->string('weekly_availability');
            $table->text('internship_goals');
            $table->text('internship_interest');
            $table->text('why_hire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_specifics');
    }
};
