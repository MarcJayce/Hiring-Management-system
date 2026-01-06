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
        Schema::create('evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_schedule_id')->constrained()->onDelete('cascade');
            $table->text('overall_impression_summary');
            $table->text('strengths');
            $table->text('areas_for_improvement');
            $table->text('technical_assessment')->nullable();
            $table->text('cultural_fit');
            $table->integer('rating_score')->nullable(); // Assuming this is an integer (1-5)
            $table->decimal('expected_salary', 8, 2)->nullable();
            $table->text('follow_up_actions');
            $table->enum('overall_rating', ['Strong Hire', 'Hire', 'Hold', 'Reject']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_forms');
    }
};
