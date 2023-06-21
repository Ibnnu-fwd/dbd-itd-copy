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
        Schema::table('abj', function (Blueprint $table) {
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('village_id')->references('id')->on('villages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abj', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
        });
    }
};
