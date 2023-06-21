<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_sample_morphotypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('morphotype_id')->constrained('morphotypes');
            $table->integer('amount')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_sample_morphotypes');
    }
};
