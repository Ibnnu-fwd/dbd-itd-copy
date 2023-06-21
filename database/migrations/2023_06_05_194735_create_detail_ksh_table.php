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
        Schema::create('detail_ksh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ksh_id')->nullable()->constrained('ksh');
            $table->string('house_name');
            $table->string('house_owner');
            $table->foreignId('tpa_type_id')->nullable()->constrained('tpa_types');
            $table->boolean('larva_status');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_ksh');
    }
};
