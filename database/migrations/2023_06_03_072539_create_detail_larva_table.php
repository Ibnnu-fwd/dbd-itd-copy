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
        Schema::create('detail_larvae', function (Blueprint $table) {
            $table->id();
            $table->foreignId('larva_id')->nullable()->constrained('larvae');
            $table->foreignId('tpa_type_id')->nullable()->constrained('tpa_types');
            $table->integer('amount_larva')->nullable();
            $table->integer('amount_egg')->nullable();
            $table->integer('number_of_adults')->nullable();
            $table->integer('water_temperature')->nullable();
            $table->integer('salinity')->nullable();
            $table->integer('ph')->nullable();
            $table->enum('aquatic_plant', ['available', 'not_available'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_larvae');
    }
};
