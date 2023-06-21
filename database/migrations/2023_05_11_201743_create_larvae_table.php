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
        Schema::create('larvae', function (Blueprint $table) {
            $table->id();
            $table->char('regency_id')->nullable();
            $table->char('district_id')->nullable();
            $table->char('village_id')->nullable();
            $table->foreignId('location_type_id')->nullable()->constrained('location_types');
            $table->foreignId('settlement_type_id')->nullable()->constrained('settlement_types');
            $table->foreignId('environment_type_id')->nullable()->constrained('environment_types');
            $table->foreignId('building_type_id')->nullable()->constrained('building_types');
            $table->foreignId('floor_type_id')->nullable()->constrained('floor_types');
            $table->longText('address')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('regency_id')->references('id')->on('regencies');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('larvae');
    }
};
