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
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->date('offer_end_date')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->dropColumn('offer_end_date');
        });
    }
};
