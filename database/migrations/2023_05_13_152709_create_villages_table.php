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
        Schema::create('villages', function (Blueprint $table) {
            $table->char('id')->primary();
            $table->char('district_id')->nullable();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts');
        });

        Schema::table('broods', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('cases', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('environment_variables', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('larvae', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');

        Schema::table('samples', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('broods', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('cases', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('environment_variables', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('larvae', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });
    }
};
