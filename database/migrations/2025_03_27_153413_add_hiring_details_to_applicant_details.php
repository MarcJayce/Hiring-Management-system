<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->date('hiring_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->dropColumn(['department', 'hiring_date']);
        });
    }
};
