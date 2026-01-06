<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('internship_specifics', function (Blueprint $table) {
            $table->dropColumn('desired_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('internship_specifics', function (Blueprint $table) {
            $table->date('desired_end_date')->nullable();
        });
    }
};
