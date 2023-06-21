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
        Schema::create('detail_sample_viruses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples');
            $table->foreignId('virus_id')->constrained('viruses');
            $table->integer('amount')->nullable();
            $table->timestamps();
        });

        Schema::table('detail_sample_morphotypes', function (Blueprint $table) {
            $table->foreignId('detail_sample_virus_id')->constrained('detail_sample_viruses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sample_viruses');

        Schema::table('detail_sample_morphotypes', function (Blueprint $table) {
            $table->dropForeign(['detail_sample_virus_id']);
        });
    }
};
