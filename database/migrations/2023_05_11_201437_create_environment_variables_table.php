<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\Table\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('environment_variables', function (Blueprint $table) {
            $table->id();
            $table->char('district_id')->nullable()->constrained('districts');
            $table->char('village_id')->nullable()->constrained('villages');
            $table->double('moisture');
            $table->double('temperature');
            $table->integer('rain_day');
            $table->date('date');
            $table->integer('quarter');
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
        Schema::dropIfExists('environment_variables');
    }
};
