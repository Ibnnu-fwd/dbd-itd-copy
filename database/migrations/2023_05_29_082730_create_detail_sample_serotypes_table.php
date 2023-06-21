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
        Schema::create('detail_sample_serotypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_sample_morphotype_id')->constrained('detail_sample_morphotypes');
            $table->foreignId('serotype_id')->constrained('serotypes');
            $table->integer('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sample_serotypes');
    }
};
