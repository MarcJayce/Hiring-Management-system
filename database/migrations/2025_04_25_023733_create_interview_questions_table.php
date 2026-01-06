<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_text');
            $table->unsignedBigInteger('set_id');
            $table->string('question_type');
            $table->timestamps();
            
            $table->foreign('set_id')->references('id')->on('interview_sets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_questions');
    }
};
