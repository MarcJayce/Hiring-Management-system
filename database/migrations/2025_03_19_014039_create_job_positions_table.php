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
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->string('position_title');
            $table->string('department');
            $table->string('work_setup');
            $table->string('reports_to');
            $table->string('job_duration');
            $table->string('work_hours');
            $table->string('compensation');
            $table->text('position_description');
            $table->longText('key_responsibilities');
            $table->longText('benefits')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('availability');
            $table->string('status');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('job_positions');
    }
};
