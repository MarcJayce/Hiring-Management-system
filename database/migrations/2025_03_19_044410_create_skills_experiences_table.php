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
        Schema::create('skills_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicant_details')->onDelete('cascade');
            $table->json('skills')->nullable(); // Stores selected skills in JSON format
            $table->text('volunteer_experience')->nullable();
            $table->text('part_time_jobs')->nullable();
            $table->text('extracurricular')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills_experiences');
    }
};
