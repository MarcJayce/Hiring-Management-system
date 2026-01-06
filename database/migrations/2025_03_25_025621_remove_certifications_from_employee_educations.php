<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employee_educations', function (Blueprint $table) {
            $table->dropColumn('certifications');
        });
    }

    public function down()
    {
        Schema::table('employee_educations', function (Blueprint $table) {
            $table->text('certifications')->nullable();
        });
    }
};

