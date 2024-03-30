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
        Schema::create('battery_ingredient_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('battery_id')->nullable();
            $table->bigInteger('ingredient_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('wastage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battery_ingredient_details');
    }
};
