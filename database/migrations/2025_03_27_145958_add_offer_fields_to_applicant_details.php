<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->date('offer_date')->nullable();
            $table->date('start_date')->nullable();
            $table->string('hiring_manager')->nullable();
        });
    }

    public function down() {
        Schema::table('applicant_details', function (Blueprint $table) {
            $table->dropColumn(['offer_date', 'start_date', 'hiring_manager']);
        });
    }
};
