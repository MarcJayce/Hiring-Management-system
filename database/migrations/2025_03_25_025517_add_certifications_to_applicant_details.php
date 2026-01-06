<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->text('certifications')->nullable()->after('position_id');
        });
    }

    public function down()
    {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->dropColumn('certifications');
        });
    }
};
