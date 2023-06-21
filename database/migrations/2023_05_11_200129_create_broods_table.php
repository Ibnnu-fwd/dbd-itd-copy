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
        Schema::create('broods', function (Blueprint $table) {
            $table->id();
            $table->char('district_id')->nullable();
            $table->char('village_id')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->string('houseowner_name', 100);
            $table->integer('amount_indoor');
            $table->integer('amount_outdoor');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broods');
    }
};
